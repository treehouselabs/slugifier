<?php

namespace TreeHouse\Slugifier;

use TreeHouse\Slugifier\Translation\CommonTranslator;
use TreeHouse\Slugifier\Translation\EntityTranslator;
use TreeHouse\Slugifier\Translation\QuoteTranslator;
use TreeHouse\Slugifier\Translation\TranslatorInterface;

class Slugifier
{
    /**
     * @var TranslatorInterface[]
     */
    protected $translators = [];

    /**
     * Expression to use to filter out unwanted characters.
     * By default this only allows alphanumeric characters, and hyphens.
     *
     * @var string
     */
    protected $allowedRegex = '/[^a-z0-9\-]/';

    /**
     * @param boolean $useDefaults Whether to start with the default behaviour. This includes translating
     *                             common characters, html entities, quotation marks, etc.
     */
    public function __construct($useDefaults = true)
    {
        if ($useDefaults) {
            $this->addTranslator(new CommonTranslator(), 100);
            $this->addTranslator(new EntityTranslator());
            $this->addTranslator(new QuoteTranslator());
        }
    }

    /**
     * @param TranslatorInterface $translator
     * @param integer|null        $priority
     *
     * @throws \InvalidArgumentException
     */
    public function addTranslator(TranslatorInterface $translator, $priority = null)
    {
        if (null === $priority) {
            $priority = max(array_keys($this->translators)) + 1;
        }

        if (!is_integer($priority) || $priority < 1) {
            throw new \InvalidArgumentException(
                sprintf('Priority must be a positive integer, got %d', json_encode($priority))
            );
        }

        if (array_key_exists($priority, $this->translators)) {
            throw new \InvalidArgumentException(sprintf('There already is a translator with priority %d', $priority));
        }

        $this->translators[$priority] = $translator;

        ksort($this->translators);
    }

    /**
     * @return TranslatorInterface[]
     */
    public function getTranslators()
    {
        return $this->translators;
    }

    /**
     * @param string $allowedRegex
     */
    public function setAllowedRegex($allowedRegex)
    {
        $this->allowedRegex = $allowedRegex;
    }

    /**
     * Converts a string of text into an ASCII-only slug.
     *
     * @param string $slug
     * @param string $delimiter
     *
     * @return string
     */
    public function slugify($slug, $delimiter = '-')
    {
        foreach ($this->translators as $translator) {
            $slug = $translator->translate($slug);
        }

        return $this->postProcess($slug, $delimiter);
    }

    /**
     * Check if a string has utf7 characters in it
     *
     * By bmorel at ssi dot fr
     *
     * @param string $string
     *
     * @return boolean $bool
     */
    public static function seemsUtf8($string)
    {
        for ($i = 0; $i < strlen($string); $i++) {
            if (ord($string[$i]) < 0x80) {
                // 0bbbbbbb
                continue;
            }

            if ((ord($string[$i]) & 0xE0) == 0xC0) {
                // 110bbbbb
                $n = 1;
            } elseif ((ord($string[$i]) & 0xF0) == 0xE0) {
                // 1110bbbb
                $n = 2;
            } elseif ((ord($string[$i]) & 0xF8) == 0xF0) {
                // 11110bbb
                $n = 3;
            } elseif ((ord($string[$i]) & 0xFC) == 0xF8) {
                // 111110bb
                $n = 4;
            } elseif ((ord($string[$i]) & 0xFE) == 0xFC) {
                // 1111110b
                $n = 5;
            } else {
                // Does not match any model
                return false;
            }

            // n bytes matching 10bbbbbb follow ?
            for ($j = 0; $j < $n; $j++) {
                if ((++$i == strlen($string)) || ((ord($string[$i]) & 0xC0) != 0x80)) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * @param string $slug
     * @param string $delimiter
     *
     * @return string
     */
    protected function postProcess($slug, $delimiter)
    {
        // convert the remaining slug to ascii with transliteration
        // this should cover just about every special character remaining
        $encoding = static::seemsUtf8($slug) ? 'UTF-8' : 'ISO-8859-1';
        $slug     = iconv($encoding, 'ASCII//TRANSLIT//IGNORE', $slug);

        // convert to lowerspace
        $slug = mb_strtolower($slug);

        // convert unallowed characters to delimiters
        $slug = preg_replace($this->allowedRegex, $delimiter, $slug);

        // convert 2 or more consecutive delimiters to one
        $slug = preg_replace(sprintf('/%s{2,}/', preg_quote($delimiter, '/')), $delimiter, $slug);

        // trim the sides of spaces and delimiters
        return trim($slug, $delimiter . ' ');
    }
}

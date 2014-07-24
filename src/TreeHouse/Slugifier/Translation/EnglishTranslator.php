<?php

namespace TreeHouse\Slugifier\Translation;

class EnglishTranslator implements TranslatorInterface
{
    /**
     * @var array
     */
    protected $map = [
        // keep spaces around these since they are words,
        // in case of eg: foo+bar
        '&' => ' and ',
        '+' => ' and ',
    ];

    /**
     * @inheritdoc
     */
    public function translate($str)
    {
        return strtr($str, $this->map);
    }
}

<?php

namespace TreeHouse\Slugifier\Translation;

class DutchTranslator implements TranslatorInterface
{
    /**
     * @var array
     */
    protected $map = [
        // keep spaces around these since they are words,
        // in case of eg: foo+bar
        '&' => ' en ',
        '+' => ' en ',

        // these diacritics can appear in the Dutch language without being written as suffixed with an 'e'
        'Ä' => 'A',
        'ä' => 'a',
        'Ö' => 'O',
        'ö' => 'o',
        'Ü' => 'U',
        'ü' => 'u',
    ];

    /**
     * @inheritdoc
     */
    public function translate($str)
    {
        return strtr($str, $this->map);
    }
}

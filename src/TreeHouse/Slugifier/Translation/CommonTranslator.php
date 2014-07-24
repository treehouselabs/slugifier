<?php

namespace TreeHouse\Slugifier\Translation;

/**
 * Translates common characters and ligatures that the iconv transliteration doesn't
 */
class CommonTranslator implements TranslatorInterface
{
    /**
     * @var array
     */
    protected $map = [
        '°' => 0,
        '¹' => 1,
        '²' => 2,
        '³' => 3,
        '@' => 'at',
        'Ä' => 'AE',
        'ä' => 'ae',
        'æ' => 'ae',
        'ǽ' => 'ae',
        'Æ' => 'AE',
        'Ǽ' => 'AE',
        'Ð' => 'D',
        'Đ' => 'D',
        'đ' => 'd',
        'ð' => 'd',
        'Ö' => 'OE',
        'º' => 'o',
        'ö' => 'oe',
        'Ü' => 'UE',
        'ü' => 'ue',
        'ß' => 'ss',
    ];

    /**
     * @inheritdoc
     */
    public function translate($str)
    {
        return strtr($str, $this->map);
    }
}

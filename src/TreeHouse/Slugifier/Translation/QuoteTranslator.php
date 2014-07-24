<?php

namespace TreeHouse\Slugifier\Translation;

/**
 * Removes all single/double quotes, as well as smart quotes (or quotation marks)
 *
 * @see http://en.wikipedia.org/wiki/Quotation_mark_glyphs
 */
class QuoteTranslator implements TranslatorInterface
{
    /**
     * @var array
     */
    protected $map = [

        "'"            => '',  // " (U+0027) single quote
        "\""           => '',  // " (U+0022) double quote
        "\xE2\x80\x98" => '',  // ‘ (U+2018)
        "\xE2\x80\x99" => '',  // ’ (U+2019)
        "\xE2\x80\x9A" => '',  // ‚ (U+201A)
        "\xE2\x80\x9B" => '',  // ‛ (U+201B)
        "\xE2\x80\x9C" => '',  // “ (U+201C)
        "\xE2\x80\x9D" => '',  // ” (U+201D)
        "\xE2\x80\x9E" => '',  // „ (U+201E)
        "\xE2\x80\x9F" => '',  // ‟ (U+201F)
        "\xE2\x80\xB9" => '-', // ‹ (U+2039)
        "\xE2\x80\xBA" => '-', // › (U+203A)
        "\xC2\xAB"     => '-', // « (U+00AB)
        "\xC2\xBB"     => '-', // » (U+00BB)
    ];

    /**
     * @inheritdoc
     */
    public function translate($str)
    {
        return strtr($str, $this->map);
    }
}

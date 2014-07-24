<?php

namespace TreeHouse\Slugifier\Translation;

interface TranslatorInterface
{
    /**
     * @param string $str
     *
     * @return string
     */
    public function translate($str);
}

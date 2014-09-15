<?php

namespace TreeHouse\Slugifier\Tests;

use TreeHouse\Slugifier\Slugifier;
use TreeHouse\Slugifier\Translation\CommonTranslator;
use TreeHouse\Slugifier\Translation\DutchTranslator;
use TreeHouse\Slugifier\Translation\EnglishTranslator;
use TreeHouse\Slugifier\Translation\TranslatorInterface;

/**
 * Note: some of these tests are borrowed from cocur/slugify
 *
 * @see https://github.com/cocur/slugify/blob/master/tests/SlugifyTest.php
 */
class SlugifierTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider slugDataProvider
     */
    public function testSlugify($slug, $normalized)
    {
        $this->assertEquals($normalized, (new Slugifier())->slugify($slug));
    }

    public static function slugDataProvider()
    {
        return [
            // regular tests
            ['CaSeTeSt', 'casetest'],
            ['   Trimtest ', 'trimtest'],

            // unallowed characters and trimming
            ['!&#$%^Amsterdam*', 'amsterdam'],
            ['Foo  &     Bar', 'foo-bar'],
            ['Foo--&-----Bar', 'foo-bar'],

            // transliteration and ligatures
            ['àgrave-ácute-âcirc-ãtilde-äuml-åring-ælig-çedil', 'agrave-acute-acirc-atilde-aeuml-aring-aelig-cedil'],
            ['á é í ó ú Á É Í Ó Ú º', 'a-e-i-o-u-a-e-i-o-u-o'],
            ['Å å Ÿ ÿ Ë Ï', 'a-a-y-y-e-i'],
            ['Ä ä æ ǽ Æ Ǽ', 'ae-ae-ae-ae-ae-ae'],
            ['Ö ö œ Œ', 'oe-oe-oe-oe'],
            ['Ü ü', 'ue-ue'],
            ['ĳ Ĳ', 'ij-ij'],
            ['ß þ Þ', 'ss-th-th'],
            ['đ Ð Đ ð', 'd-d-d-d'],
            ['°¹²³@', '0123at'],

            // quotes
            ['Can\'t touch this', 'cant-touch-this'],
            ['"Can\'t" touch this?', 'cant-touch-this'],
            ['Can\'t touch «this»!', 'cant-touch-this'],

            // combine everything
            ['  ^^«Mórë» thån wørds^^  ', 'more-than-words'],
            ['C’est du français !', 'cest-du-francais'],
            ['I‛m „smart”, «like this», ‹or this›', 'im-smart-like-this-or-this'],
        ];
    }

    public function testNoDefaults()
    {
        $slugifier = new Slugifier(false);

        $this->assertEquals('can-t-touch-this', $slugifier->slugify('Can\'t touch @ this!'));
    }

    /**
     * @dataProvider translatorDataProvider
     */
    public function testTranslators(TranslatorInterface $translator, $string, $slug)
    {
        $slugifier = new Slugifier();
        $slugifier->addTranslator($translator);

        $this->assertEquals($slug, $slugifier->slugify($string));
    }

    public static function translatorDataProvider()
    {
        return [
            [new DutchTranslator(), 'Foo & Bar', 'foo-en-bar'],
            [new DutchTranslator(), 'Foo+Bar', 'foo-en-bar'],
            [new EnglishTranslator(), 'Foo & Bar', 'foo-and-bar'],
            [new EnglishTranslator(), 'Foo+Bar', 'foo-and-bar'],
        ];
    }

    public function testTranslatorPositions()
    {
        $translator1 = new DutchTranslator();
        $translator2 = new CommonTranslator();

        $slugifier = new Slugifier(false);
        $slugifier->addTranslator($translator2, 100);
        $slugifier->addTranslator($translator1, 50);

        $this->assertEquals(
            [
                50  => $translator1,
                100 => $translator2,
            ],
            $slugifier->getTranslators()
        );
    }

    public function testDelimiter()
    {
        $slugifier = new Slugifier();

        $this->assertEquals('foo_bar_baz', $slugifier->slugify('Foo, bar & baz', '_'));
    }
}

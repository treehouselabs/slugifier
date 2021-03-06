Slugifier
=========

Simple, extensible library that converts a string to a slug.

## Another one?

Yep, sorry. We think this one is pretty good though. Check out the features to see if it's good for you, too.

## Features

* Lightweight: no dependencies, other than the `mbstring` and `iconv` extensions, which are almost always present.
* Easy to use: `(new Slugifier())->slugify('Look Ma, no hands!'); // look-ma-no-hands`
* Handles special characters, all kinds of quotes, transliteration, HTML entities, and more. All out of the box.
* Extensible: add your custom translations
* PSR-4 compatible and written using PRS-2 coding guidelines [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/treehouselabs/slugifier/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/treehouselabs/slugifier/?branch=master)
* [Well-tested](/tests/TreeHouse/Slugifier/Tests/SlugifierTest.php) [![Build Status](https://travis-ci.org/treehouselabs/slugifier.svg)](https://travis-ci.org/treehouselabs/slugifier)

## Usage

```php
$slugifier = new Slugifier();
$slugifier->slugify('Look má, I\'m a „slug”'); // look-ma-im-a-slug
```

Use a different delimiter:

```php
$slugifier = new Slugifier();
$slugifier->slugify('Foo, bar & baz', '_'); // foo_bar_baz
```

Add special translations:

 ```php
$slugifier = new Slugifier();
$slugifier->addTranslator(new EnglishTranslator());
$slugifier->slugify('Cow & chicken'); // cow-and-chicken
```

Or write your own:

```php
class CowTranslator implements TranslatorInterface
{
    public function translate($str)
    {
        return str_ireplace('cow', 'supercow', $str);
    }
}

$slugifier = new Slugifier();
$slugifier->addTranslator(new CowTranslator());
$slugifier->slugify('Cow to the rescue'); // supercow-to-the-rescue
```

## Changing the default behaviour

By default, special characters, quotes, HTML entities and more are converted.
You can disable this behaviour by passing `false` to the constructor. This way
you can start from scratch and add your own translators. The `iconv`
transliteration is still performed though as we want to convert to ascii while
preserving as much of the original data as possible.

<?php

namespace TreeHouse\Slugifier\Translation;

class EntityTranslator implements TranslatorInterface
{
    /**
     * @inheritdoc
     */
    public function translate($str)
    {
        // replace entities with their ascii equivalents
        return html_entity_decode(
            preg_replace(
                '/&([a-z]{1,2})(grave|acute|cedil|circ|ring|tilde|uml|lig|slash|caron|nof|orn|th);/i',
                '$1',
                htmlentities($str, null, 'UTF-8')
            ),
            null,
            'UTF-8'
        );
    }
}

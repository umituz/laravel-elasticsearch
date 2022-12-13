<?php

namespace App\Enums;

/**
 * Class ElasticsearchEnums
 * @package App\Enums
 */
class ElasticsearchEnums
{
    /**
     * @return string[]
     */
    public function getAnalyzers(): array
    {
        return [
            'standard',
            'simple',
            'whitespace',
            'keyword',
            'fingerprint'
        ];
    }
}

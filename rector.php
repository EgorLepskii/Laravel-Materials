<?php

// rector.php
declare(strict_types=1);


use Rector\Laravel\Set\LaravelSetList;

return static function (\Rector\Config\RectorConfig $rectorConfig): void {
    $rectorConfig->import(LaravelSetList::LARAVEL_STATIC_TO_INJECTION);

    $rectorConfig->sets
    (
        [
            \Rector\Set\ValueObject\SetList::PHP_74,
            \Rector\Set\ValueObject\SetList::PSR_4,
            \Rector\Set\ValueObject\SetList::CODING_STYLE,
            \Rector\Set\ValueObject\SetList::CODE_QUALITY,
        ]

    );
};

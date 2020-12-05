<?php

namespace App\Validation\Exceptions;

use Respect\Validation\Exceptions\ValidationException;

/**
 * EmailAvailableException
 *
 * @author    Haven Shen <havenshen@gmail.com>
 * @copyright    Copyright (c) Haven Shen
 */
class EmailAvailableException extends ValidationException
{

    public static $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => '{{name}} déja existant',
        ],
        self::MODE_NEGATIVE => [
            self::STANDARD => '{{name}} n\'est pas encore inscrit',
        ]
    ];
}

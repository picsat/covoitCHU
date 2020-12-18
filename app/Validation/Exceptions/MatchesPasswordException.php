<?php

namespace App\Validation\Exceptions;

use Respect\Validation\Exceptions\ValidationException;

/**
 * MatchesPasswordException
 *
 * @author    Haven Shen <havenshen@gmail.com>
 * @copyright    Copyright (c) Haven Shen
 */
class MatchesPasswordException extends ValidationException
{
    public static $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => '{{name}} ne correspond pas à celui enregistré actuellement pour ce compte',
        ],
        self::MODE_NEGATIVE => [
            self::STANDARD => '{{name}} a l\air de ne pas correspondre',
        ]
    ];
}

<?php

namespace App\Validation;

use Respect\Validation\Validator as Respect;
use Respect\Validation\Exceptions\NestedValidationException;

/**
 * Validator
 *
 * @author    Haven Shen <havenshen@gmail.com>
 * @copyright    Copyright (c) Haven Shen
 */
class Validator
{
    protected $errors;
    protected $validated;

    public function validate($request, array $rules)
    {
        foreach ($rules as $field => $rule) {
            try {
                $rule->setName(ucfirst($field))->assert($request->getParam($field));
            } catch (NestedValidationException $e) {
                //traduction rapide, mais on devrait faire les overide des ValidationExceptions ici pour etre propre
                $errors = $e->findMessages([
                    'notEmpty' => "{{name}} ne doit pas être vide",
                    'length' => "{{name}} doit contenir au moins 6 caractères" ,
                    'noWhitespace' => "{{name}} ne doit pas contenir d'espaces",
                    'phone' => "{{name}} doit être un numéro valide (ex : 0123456789)",
                ]);

                $filteredErrors = array_filter($errors); // Ensure the array is not containing empty values
                $this->$errors[$field] = $filteredErrors;

                $this->errors[$field] = $e->getMessages();
            }

            if(!$this->errors[$field]) {
                $this->validated[$field] = true;
            }
        }
        $_SESSION['errors'] = $this->errors;
        $_SESSION['errors']['validated'] = $this->validated;

        return $this;
    }

    public function failed()
    {
        return !empty($this->errors);
    }
}

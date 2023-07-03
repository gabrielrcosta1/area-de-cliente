<?php

namespace App\Http\Middleware;

use App\Models\User;
use Symfony\Component\HttpFoundation\Session\Session;
use App\Http\Middleware\Sessions;

class Authenticate
{
    static public function validate($data, $rules, $messages = [])
    {
        $errors = [];

        foreach ($rules as $field => $rule) {
            $value = isset($data[$field]) ? $data[$field] : null;

            // Verifique cada regra de validação
            foreach ($rule as $validator) {
                $parts = explode(':', $validator);
                $validatorName = $parts[0];
                $validatorParam = isset($parts[1]) ? $parts[1] : null;

                // Execute a validação com base no nome do validador
                switch ($validatorName) {
                    case 'required':
                        if (empty($value)) {
                            $errorMessage = isset($messages["$field.$validatorName"]) ? $messages["$field.$validatorName"] : "O campo $field é obrigatório.";
                            $errors[$field][] = $errorMessage;
                        }
                        break;

                    case 'min':
                        if (strlen($value) < $validatorParam) {
                            $errorMessage = isset($messages["$field.$validatorName"]) ? $messages["$field.$validatorName"] : "O campo $field deve ter no mínimo $validatorParam caracteres.";
                            $errors[$field][] = $errorMessage;
                        }
                        break;

                        // Adicione mais casos para outros validadores que você deseja suportar

                    default:
                        break;
                }
            }
        }

        return $errors;
    }


    public function attempt($data)
    {
        $userData = new User;
        $username = $data['username'];
        $password = $data['password'];
        if ($storedPassword = $userData->filltable($username)) {
            if ($password === $storedPassword['password']) {
                $sessionData = array(
                    'user' => $storedPassword['id'],
                    'username' => $storedPassword['username'],
                    'status' => $storedPassword['status'],
                    'exp_date' => $storedPassword['exp_date'],
                    'max_connections' => $storedPassword['max_connections'],
                    'last_connection' => $storedPassword['last_connection'],
                    'bouquets' => $storedPassword['bouquets']
                );
                Sessions::createSession($sessionData);
                return true;
            }
        } else {
            return false;
        }
    }
}

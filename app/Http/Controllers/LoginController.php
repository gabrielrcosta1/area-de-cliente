<?php


namespace App\Http\Controllers;

use App\Http\Middleware\Authenticate;


class LoginController extends Controller
{

    public function index()
    {
        $this->render("login.html.twig");
    }


    public function signing()
    {
        $data = [
            'username' => $_POST['username'],
            'password' => $_POST['password']
        ];

        $rules = [
            'username' => ['required'],
            'password' => ['required']
        ];

        $messages = [
            'username.required' => 'Campo de usuário obrigatório.',
            'password.required' => 'Necessário preencher a senha.',
        ];

        $errors = Authenticate::validate($data, $rules, $messages);

        if (count($errors) > 0) {
            foreach ($errors as $field => $fieldErrors) {
                foreach ($fieldErrors as $error) {
                    $messages = $this->messages('danger', $error);
                    $this->redirectBack("/login");
                }
            }
        } else {

            $authenticate = new Authenticate;

            if ($authenticate->attempt($data) == false) {
                $messages = $this->messages('danger', 'Usuário ou senha incorretos');
                $this->redirectBack("/login");
            } else {
                $this->redirectBack("/");
            }
        }
    }
}
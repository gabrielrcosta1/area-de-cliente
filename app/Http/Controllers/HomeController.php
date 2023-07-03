<?php

namespace App\Http\Controllers;

use App\Http\Middleware\Authorization;
use App\Http\Middleware\Authenticate;
use App\Models\User;
use App\Models\Admin;

class HomeController extends Controller
{

    public function index()
    {

        Authorization::Auth();
        $get_user = new User;
        $get_info = new Admin;

        $data_user = $get_user->getUser();
        $get_stream = $get_user->userActivityNow();
        $get_info_content = $get_info->getInfor();
        $data = [
            'status' => $_SESSION['status'],
            'exp_date' => date("d/m/Y", $_SESSION['exp_date']),
            'max_connection' => $_SESSION['max_connections'],
            'last_connection' => date("H:i", 1684254148),
            'bouquets' => $_SESSION['bouquets'],
            'username' => $data_user['username'],
            'password' => $data_user['password'],
            'get_stream' => $get_stream,
            'title' =>  $get_info_content['title'],
            'content' =>  $get_info_content['content']
        ];
        $this->render("home.html.twig", $data);
    }


    public function updateUser()
    {
        $data = [
            'password' => $_POST['password']
        ];

        $rules = [
            'password' => ['required']
        ];

        $messages = [
            'password.required' => 'Necessário preencher a senha.',
        ];
        $errors = Authenticate::validate($data, $rules, $messages);

        if (count($errors) > 0) {
            foreach ($errors as $field => $fieldErrors) {
                foreach ($fieldErrors as $error) {
                    $messages = $this->messages('danger', $error);
                    $this->redirectBack("/");
                }
            }
        } else {
            $update_user = new User;
            if ($update_user->updatePassword($_POST['password'])) {
                $messages = $this->messages('success', 'Senha Alterada com sucesso');
                $this->redirectBack("/");
            } else {
                $messages = $this->messages('danger', 'Houve algum erro');
                $this->redirectBack("/");
            }
        }
    }
}

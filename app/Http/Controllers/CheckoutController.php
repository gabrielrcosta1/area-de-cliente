<?php

namespace App\Http\Controllers;

use App\Http\Middleware\Authorization;
use App\Http\Middleware\Authenticate;
use App\Models\User;
use App\Models\Admin;

class CheckoutController extends Controller
{
    public function index()
    {
        Authorization::Auth();
        $get_user = new User;
        $get_info = new Admin;

        $data_user = $get_user->getUser();
        $get_stream = $get_user->userActivityNow();
        $get_info_content = $get_info->getInfor();
        $get_values = $get_info->getValuesFromCheckout();
        $data = [
            'status' => $_SESSION['status'],
            'exp_date' => date("d/m/Y", $_SESSION['exp_date']),
            'max_connection' => $_SESSION['max_connections'],
            'last_connection' => date("H:i", 1684254148),
            'bouquets' => $_SESSION['bouquets'],
            'username' => $data_user['username'],
            'password' => $data_user['password'],
            'get_stream' => $get_stream,
            'title' => $get_info_content['title'],
            'content' => $get_info_content['content'],
            'dadosCheckout' => $get_values
        ];
        $this->render('checkout.html.twig', $data);
    }
}
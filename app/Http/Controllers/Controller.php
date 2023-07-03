<?php

namespace App\Http\Controllers;

use Twig\Environment;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\RedirectResponse;


class Controller
{

    private $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }


    public function render($template, $data = null)
    {
        if ($data === null) {
            $data = [];
        }

        echo $this->twig->render($template, $data);
    }

    public function messages($type, $messages)
    {
        $session = new Session;
        $session->getFlashBag()->add($type, $messages);
    }

    public function redirectBack($route)
    {
        $response = new RedirectResponse($route);
        $response->send();
        return;
    }

    public function MercadoPago()
    {

    }
}
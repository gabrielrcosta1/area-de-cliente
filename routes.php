<?php

// Define as rotas e seus respectivos controllers e métodos
$routes = array(
    '/' => array('controller' => 'HomeController', 'method' => 'index'),
    '/login' => array('controller' => 'LoginController', 'method' => 'index'),
    '/checkout' => array('controller' => 'CheckoutController', 'method' => 'index'),
    '/singIn' => array('controller' => 'LoginController', 'method' => 'signing'),
    '/update' => array('controller' => 'HomeController', 'method' => 'updateUser')
);
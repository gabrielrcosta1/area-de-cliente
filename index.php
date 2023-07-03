<?php
require_once "vendor/autoload.php";
require_once "routes.php";

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

// Inicializa a sessão
$storage = new NativeSessionStorage();
$session = new Session($storage);
$session->start();

// Cria a instância do Twig

$loader = new FilesystemLoader('Templates/');
$twig = new Environment($loader);
$twig->addGlobal('session', $session);
$twig->addGlobal('admin', $session->get('admin'));
define("URL", "cliente/");

// Captura a página que foi requisitada
$uri = $_SERVER['REQUEST_URI'];
// Remove a parte da URL referente ao diretório raiz do projeto
$uri = str_replace(URL, '', $uri);


// Busca a rota correspondente à página requisitada
$route = null;
foreach ($routes as $routePattern => $routeData) {
    $pattern = '#^' . $routePattern . '$#';
    if (preg_match($pattern, $uri, $matches)) {
        $route = $routeData;
        array_shift($matches); // Remove o primeiro elemento, que contém a URL completa
        $route['params'] = $matches;
        break;
    }
}

// Se não encontrou uma rota, retorna um erro 404
if ($route === null) {
    http_response_code(404);
    echo $twig->render('404.html.twig');
    exit();
}

// Instancia o controller adequado com base no nome obtido
$controllerName = 'App\Http\Controllers\\' . $route['controller'];
$controller = new $controllerName($twig);

// Verifica se o método existe no controller e o executa
$methodName = $route['method'];
$params = $route['params'];

if (method_exists($controller, $methodName)) {
    $controller->$methodName(...$params);
} else {
    // Página não encontrada
    http_response_code(404);
    echo $twig->render('404.twig');
    exit();
}
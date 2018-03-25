<?php
use core\DBConnector;
use core\Templater;
use models\UserModel;
use models\PostModel;

function __autoload($classname) {
    require_once __DIR__ . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $classname) . '.php';
}

session_start();

$uri = $_SERVER['REQUEST_URI'];
$uriParts = explode('/', $uri);

unset($uriParts[0]);
$uriParts = array_values($uriParts);
//var_dump($uriParts);

$controller = isset($uriParts[2]) && $uriParts[2] !== '' ? $uriParts[2] : 'post';

switch($controller) {
    case 'post':
        $controller = 'Post';
        break;
    case 'user':
        $controller = 'User';
        break;
    default:
        header("HTTP/1.0 404 Not Found");
        die("error 404");
        break;
}

$id = false;
if(isset($uriParts[3]) && is_numeric($uriParts[3])) {
    $id = $uriParts[3];
    $uriParts[3] = 'one';
} 

$action = isset($uriParts[3]) && $uriParts[3] !== '' && is_string($uriParts[3]) ? $uriParts[3] : 'index';
$action = sprintf('%sAction', $action);
if(!$id) {
    $id = isset($uriParts[4]) && is_numeric($uriParts[4]) ? $uriParts[4] : false;
}
if($id) {
    $_GET['id'] = $id;
}

$request = new core\Request($_GET, $_POST, $_SERVER, $_COOKIE, $_FILES, $_SESSION);

$controller = sprintf('controller\%sController', $controller);
try {
    $controller = new $controller($request);
    $controller->$action();
} catch (\Exception $e) {
    $controller = new $controller($request);
    $controller->errorHenadler($e->getMessage(), $e->getTraceAsString());
    die;
}
$controller->render();

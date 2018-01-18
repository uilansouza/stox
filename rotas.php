<?php
use Stox\Auth\BaseAuth;

// Cria um grupo de controllers em 'auth/'
$auth = $app['controllers_factory'];

require __DIR__ . '/rotas/fornecedores.php';
require __DIR__ . '/rotas/login.php';
require __DIR__ . '/rotas/produtos.php';


$auth->before(function () use ($app){
    if(!BaseAuth::validate()){
        return $app->redirect(URL_BASE);
    }
    
});
    


// verificar a session

$auth->get('/logout', function() use ($app){
    BaseAuth::logout();
    return $app->redirect(URL_BASE);
});

// Monta as urls em 'auth/'
$app->mount('auth', $auth);


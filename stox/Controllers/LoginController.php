<?php
namespace Stox\Controllers;

use Silex\Application;
use Stox\Models\Usuario;
use Stox\Auth\BaseAuth;

class LoginController
{
    public function index()
    {
        return view()->render('login/login.twig', [
            'token' => BaseAuth::tokenGen()
        ]);
    }
    
    public function cadastrar()
    {
        return view()->render('login/cadastrar.twig');
    }
    
    public function  save (Application $app)
    {
        $req= $app['request']->request;
        $usuario = new Usuario($req->get('nome'), $req->get('email'), $req->get('senha'));
        if($usuario->save()){
            session()->set('success','Usuario Cadastrado com sucesso');
            return $app->redirect(URL_BASE.'/login/cadastrar');
        }
        session()->set('error','Erro ao Cadastrar o usuário');
        return $app->redirect(URL_BASE.'/login/cadastrar');
    }

    public function login(Application $app)
    {
        $req = $app['request']->request;
        $auth = new BaseAuth;
        
        // Anti CSRF
        if (false === $auth->tokenVerify($req->get('_token'))) {
            return $app->redirect(URL_BASE);
        }
        
        if($auth->login($req->get('email'), $req->get('senha'))){
            $auth->grant();// graca na sessão e atribui o tempo de expiração
            return $app->redirect(URL_AUTH.'/home');
        }
        
        session()->set('error', 'Usuário ou senha inválido');
        return $app->redirect(URL_BASE);
    }
}


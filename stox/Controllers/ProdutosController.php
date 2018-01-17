<?php

namespace Stox\Controllers;

use Silex\Application;
use Stox\Models\Fornecedor;
use Stox\Models\Produto;

class ProdutosController
{
    public function home()
    {
        return view()->render('pages/home.twig');
    }
    
    public function produtos()
    {
        return view()->render('produtos/produtos.twig');
    }
    
    public function produtosRelatorio()
    {
        $prods = Produto::all();
        return view()->render('produtos/produtos_relat.twig', [
            'produtos' => $prods
        ]);
    }
    
    public function cadastrar()
    {
        return view()->render('produtos/prodform.twig', [
            'fornecedores' => Fornecedor::all()
        ]);
    }
    
    public function add(Application $app)
    {
        $req = $app['request']->request;
        
        $prod = new Produto;
        $prod->set($req->all());
        if ($prod->save()) {
            session()->set('success', 'Produto cadastrado com sucesso');
            return $app->redirect(URL_AUTH . '/produtos');
        }
        session()->set('error', 'Produto não cadastrado');
        return $app->redirect(URL_AUTH . '/produtos');
    }
    
    // Criar método retirar() aqui
    
    public function alterar($id)
    {
        return view()->render('produtos/prodform.twig', [
            'produto' => Produto::byId($id),
            'fornecedores' => Fornecedor::all()
        ]);
    }
    
    public function update(Application $app, $id)
    {
        // Realizar Update aqui ...
    }
    
    public function delete(Application $app, $id)
    {
        // Realizar exclusão aqui ...
    }
    
    public function relatorio(Application $app)
    {
        $relat = getcwd() . '\public\relatorio.html';
        file_put_contents($relat, $this->produtosRelatorio());
        
        $file = function () use ($app, $relat) {
            echo $app['snappy']->getOutPut(URL_PUBLIC . '/relatorio.html');
            unlink($relat);
        };
        
        return $app->stream($file, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="relatorio.pdf"'
        ]);
    }
}

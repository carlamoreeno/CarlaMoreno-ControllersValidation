<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

//Importação da Request
use App\Http\Requests\CriarProduto;

use App\Produto;

class MinhaController extends Controller
{
    public function getProduto($id) {

      $produto = Produto::findorfail($id);

      return response()->json([$produto]);

    }

    public function criarProduto(CriarProduto $request) {
      //Validation withour Request
//      $validator = Validator::make($request->all(), [
//        'nome' => 'required|alpha',
//        'tipo' => 'alpha',
//        'preco' => 'required|numeric|min:0',
//        'quantidade' => 'required|integer|min:0'
//
//      ]);
//      if ($validator->fails()) {
//     return response()->json($validator->errors());
//      }

      $novoProduto = new Produto;

      $novoProduto->nome = $request->nome;
      if ($request->tipo){
        $novoProduto->tipo = $request->tipo;
      }
      $novoProduto->preco = $request->preco;
      $novoProduto->quantidade = $request->quantidade;

      $novoProduto->save();

    }

    public function atualizarProduto(CriarProduto $request, $id) {

      $produto = Produto::findorfail($id);

      if($request->nome){
        $produto->nome = $request->nome;
      }
      if($request->tipo){
        $produto->tipo = $request->tipo;
      }
      if($request->preco){
        $produto->preco = $request->preco;
      }
      if($request->quantidade){
        $produto->quantidade = $request->quantidade;
      }

      $produto->save();
    }

    public function deletarProduto($id){

      Produto::destroy($id);

    }
}

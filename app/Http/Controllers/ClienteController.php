<?php

namespace App\Http\Controllers;

use App\Cliente;
use Illuminate\Http\Request;

//Importação da Request
use App\Http\Requests\CriarCliente;

//Importação métodos de Storage (no caso, da foto de perfil)
use Illuminate\Support\Facades\Storage;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clientes = Cliente::all();

        return $clientes->toJson();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store (CriarCliente $request)
    {
        $novoCliente = new Cliente;

        $novoCliente->RG = $request->RG;
        $novoCliente->CPF = $request->CPF;
        $novoCliente->nome = $request->nome;
        $novoCliente->telefone = $request->telefone;
        $novoCliente->endereco = $request->endereco;
        $novoCliente->email = $request->email;

        if (!Storage::exists('localPhotos/'))
        {
            Storage::makeDirectory('localPhotos/', 0775, true);
        }

        //decodifica a string em base64 e a atribui a uma variável
        $image = base64_decode($request->foto);
        //gera um nome único para o arquivo e concatena seu nome com a
        //extensão ‘.png’ para termos de fato uma imagem
        $imgName = uniqid().'.png';
        //atribui a variável o caminho para a imagem que é constituída do
        //caminho das pastas e o nome do arquivo
        $path = storage_path('/app/localPhotos/'.$imgName);
        //salva o que está na variável $image como o arquivo definido em $path
        file_put_contents($path,$image);
        $novoCliente->foto = $imgName;



        $novoCliente->save();
        return response()->json('Cliente criado com sucesso! '.$novoCliente.'.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function show(Cliente $cliente)
    {
        //Exibição do arquivo
        $filePath = storage_path('app/localPhotos/'.$cliente->foto);
        return response()->file($filePath);

        //Download
        $filePath = storage_path('app/localPhotos/'.$cliente->foto);
        return response()->download($filePath, $cliente->foto);

        //return response()->json('Cliente: '.$cliente.'.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function update(CriarCliente $request, Cliente $cliente)
    {

        if($request->foto){
            Storage::delete('localPhotos/'.$cliente->foto);
            if (!Storage::exists('localPhotos/'))
            {
                Storage::makeDirectory('localPhotos/', 0775, true);
            }

            //decodifica a string em base64 e a atribui a uma variável
            $image = base64_decode($request->foto);
            //gera um nome único para o arquivo e concatena seu nome com a
            //extensão ‘.png’ para termos de fato uma imagem
            $imgName = uniqid().'.png';
            //atribui a variável o caminho para a imagem que é constituída do
            //caminho das pastas e o nome do arquivo
            $path = storage_path('/app/localPhotos/'.$imgName);
            //salva o que está na variável $image como o arquivo definido em $path
            file_put_contents($path,$image);
            $cliente->foto = $imgName;
        }     
        if($request->RG){
          $cliente->RG = $request->RG;
        }
        if($request->CPF){
          $cliente->CPF = $request->CPF;
        }
        if($request->nome){
          $cliente->nome = $request->nome;
        }
        if($request->telefone){
          $cliente->telefone = $request->telefone;
        }
        if($request->endereco){
          $cliente->endereco = $request->endereco;
        }
        if($request->email){
          $cliente->email = $request->email;
        }

        $cliente->save();
        return response()->json('Cliente atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cliente $cliente)
    {
        //Deletar Foto do Cliente (sempre antes de cliente, senão ele deleta o cliente e não sabe de quem deletar a foto)
        Storage::delete('localPhotos/'.$cliente->foto);

        Cliente::destroy($cliente->id);
        return response()->json('Cliente deletado com sucesso!');
    }
}

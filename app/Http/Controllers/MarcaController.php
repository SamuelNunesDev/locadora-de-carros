<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class MarcaController extends Controller
{
    // Injetando o model como atributo do controlador.

    public function __construct(Marca $marca)
    {
        $this->marca = $marca;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json($this->marca->all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->marca->rules(), $this->marca->feedbacks());

        try {  
            $this->marca->nome = $request->nome;
            $this->marca->imagem = $request->imagem->store('img', 'public');
            $this->marca->save();

            return response()->json($this->marca, 201);
        } catch( Exception $e ) {
            return response()->json(['erro' => 'Houve um erro ao tentar salvar a marca! '.$e->getMessage()], 404);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $marca
     * @return \Illuminate\Http\Response
     */
    public function show(int $marca)
    {
        $response = $this->marca->find($marca);
        if( $response === null ) { return response()->json(['erro' => 'Erro! Nenhum registro encontrado!'], 404); }

        return response()->json($response);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $marca
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $marca)
    {
        $this->marca = $this->marca->find($marca);

        if( $this->marca === null ) { 
            return response()->json(['erro' => 'Erro! A marca não foi encontrada ou não existe.'], 404); 
        }
        $rules = $this->marca->rules();
        $feedbacks = $this->marca->feedbacks();

        if( $request->method() === 'PATCH' ) {
            $rules = Arr::where($rules, function($value, $key) use($request){
                return Arr::exists($request->all(), $key);
            });
            $request->validate($rules, $feedbacks);
        } else {
           $request->validate($rules, $feedbacks);
        }
        
        try {
            $dados = $request->all();
            if( isset($dados['imagem']) ) {
                Storage::disk('public')->delete($this->marca->imagem);
                $dados['imagem'] = $dados['imagem']->store('img', 'public');
            }
            $this->marca->update($dados);

            return response()->json($this->marca);
        } catch( Exception $e ) {
            return response()->json(['erro' => 'Ops! Houve um erro ao atualizar a marca. '.$e->getMessage()], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $marca
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $marca)
    {
        try {
            $this->marca = $this->marca->findOrFail($marca);
            $nome = $this->marca->nome;
            Storage::disk('public')->delete($this->marca->imagem);
            $this->marca->delete();
   
            return response()->json(['msg' => "A marca $nome foi removida com sucesso!"]);
        } catch( Exception $e ) {
            return response()->json(['erro' => 'Ops! Houve um erro ao tentar excluir a marca! '.$e->getMessage()], 404);
        }
    }
}

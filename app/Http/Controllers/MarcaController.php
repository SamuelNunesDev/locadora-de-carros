<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use App\Repositories\AbstractRepository;
use Exception;
use Illuminate\Http\Request;
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
    public function index(Request $request)
    {
        if( $request->has('atributos') ) {
            $this->marca = AbstractRepository::selectAtributos($this->marca, 'id,'.$request->atributos);
        }
        
        if( $request->has('filtros') ) {
            $this->marca = AbstractRepository::filtros($this->marca, $request->filtros);
        }

        if( $request->has('atributos_modelos') ) {
            $atributos = 'modelos:marca_id,'.$request->atributos_modelos;
            $this->marca = AbstractRepository::atributosRelacionamento($this->marca, $atributos);
        } else {
            $this->marca = $this->marca->with('modelos');
        }

        return response()->json($this->marca->get());
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
            $this->marca->imagem = $request->imagem->store('img/marcas', 'public');
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
        try {
            return response()->json($this->marca->with('modelos')->findOrFail($marca));
        } catch(Exception $e) {
            return response()->json(['erro' => 'Erro! Nenhum registro encontrado! ', $e->getMessage()], 404);
        }
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
        AbstractRepository::dinamicValidate($request, $this->marca->rules(), $this->marca->feedbacks());
        
        try {
            $dados = $request->all();
            if( isset($dados['imagem']) ) {
                Storage::disk('public')->delete($this->marca->imagem);
                $dados['imagem'] = $dados['imagem']->store('img/marcas', 'public');
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

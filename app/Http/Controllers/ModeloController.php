<?php

namespace App\Http\Controllers;

use App\Models\Modelo;
use App\Repositories\AbstractRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ModeloController extends Controller
{
    // Injetando o model como atributo do controlador
    public function __construct(Modelo $modelo)
    {
        $this->modelo = $modelo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            if( $request->has('atributos') ) {
                $this->modelo = AbstractRepository::selectAtributos($this->modelo, 'marca_id,'.$request->atributos);
            }
            
            if( $request->has('filtros') ) {
                $this->modelo = AbstractRepository::filtros($this->modelo, $request->filtros);
            }

            if( $request->has('atributos_marca') ) {
                $this->modelo = AbstractRepository::atributosRelacionamento($this->modelo, 'marca:id,'.$request->atributos_marca);
            } else {
                $this->modelo = $this->modelo->with('marca');
            }

            return response()->json($this->modelo->get());
        } catch(Exception $e) {
            return response()->json(['erro' => 'Ops! Houve um erro ao recuperar os modelos. '.$e->getMessage()]);
        }
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->modelo->rules(), $this->modelo->feedbacks());

        try {
            $this->modelo->marca_id = $request->marca_id;
            $this->modelo->nome = $request->nome;
            $this->modelo->imagem = $request->imagem->store('img/modelos', 'public');
            $this->modelo->lugares = $request->lugares;
            $this->modelo->numero_portas = $request->numero_portas;
            $this->modelo->airbag = $request->airbag;
            $this->modelo->abs = $request->abs;
            $this->modelo->save();

            return response()->json($this->modelo, 201);
        } catch(Exception $e) {
            if( $this->modelo->imagem ) { Storage::disk('public')->delete($this->modelo->imagem); }
            
            return response()->json(['erro' => 'Ops! Houve um erro ao salvar o modelo. '.$e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $modelo
     * @return \Illuminate\Http\Response
     */
    public function show(int $modelo)
    {
        try {
            return response()->json($this->modelo->with('marca')->findOrFail($modelo));
        } catch(Exception $e) {
            return response()->json(['erro' => 'Erro! Nenhum registro encontrado! ', $e->getMessage()], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $modelo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $modelo)
    {
        $this->modelo = $this->modelo->find($modelo);
        if( $this->modelo === null ) { 
            return response()->json(['erro' => 'Erro! Modelo não foi encontrado ou não existe.'], 404); 
        }
        AbstractRepository::dinamicValidate($request, $this->modelo->rules(), $this->modelo->feedbacks());
        
        try {
            $dados = $request->all();
            if( isset($dados['imagem']) ) {
                Storage::disk('public')->delete($this->modelo->imagem);
                $dados['imagem'] = $dados['imagem']->store('img/modelo', 'public');
            }
            $this->modelo->update($dados);

            return response()->json($this->modelo);
        } catch( Exception $e ) {
            return response()->json(['erro' => 'Ops! Houve um erro ao atualizar a modelo. '.$e->getMessage()], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $modelo
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $modelo)
    {
        try {
            $this->modelo = $this->modelo->findOrFail($modelo);
            $nome = $this->modelo->nome;
            Storage::disk('public')->delete($this->modelo->imagem);
            $this->modelo->delete();
   
            return response()->json(['msg' => "O modelo $nome foi removido com sucesso!"]);
        } catch( Exception $e ) {
            return response()->json(['erro' => 'Ops! Houve um erro ao tentar excluir o modelo! '.$e->getMessage()], 404);
        }
    }
}

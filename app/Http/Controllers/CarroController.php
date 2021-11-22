<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Carro;
use App\Repositories\AbstractRepository;
use Exception;

class CarroController extends Controller
{
    //Injetando o model como atributo do controlador
    public function __construct(Carro $carro)
    {
        $this->carro = $carro;
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
                $this->carro = AbstractRepository::selectAtributos($this->carro, 'modelo_id,'.$request->atributos);
            }
            
            if( $request->has('filtros') ) {
                $this->carro = AbstractRepository::filtros($this->carro, $request->filtros);
            }

            if( $request->has('atributos_modelo') ) {
                $this->carro = AbstractRepository::atributosRelacionamento($this->carro, 'modelo:id,'.$request->atributos_modelo);
            } else {
                $this->carro = $this->carro->with('modelo');
            }

            return response()->json($this->carro->get());
        } catch(Exception $e) {
            return response()->json(['erro' => 'Ops! Houve um erro ao recuperar os carros. '.$e->getMessage()]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->carro->rules(), $this->carro->feedbacks());

        try {
            $this->carro->modelo_id = $request->modelo_id;
            $this->carro->placa = $request->placa;
            $this->carro->disponivel = $request->disponivel;
            $this->carro->km = $request->km;
            $this->carro->save();

            return response()->json($this->carro, 201);
        } catch(Exception $e) { 
            return response()->json(['erro' => 'Ops! Houve um erro ao salvar o carro. '.$e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $carro
     * @return \Illuminate\Http\Response
     */
    public function show(int $carro)
    {
        try {
            return response()->json($this->carro->with('modelo')->findOrFail($carro));
        } catch(Exception $e) {
            return response()->json(['erro' => 'Erro! Nenhum registro encontrado! ', $e->getMessage()], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Request  $request
     * @param  int $carro
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $carro)
    {
        $this->carro = $this->carro->find($carro);
        if( $this->carro === null ) { 
            return response()->json(['erro' => 'Erro! Carro não foi encontrado ou não existe.'], 404); 
        }
        AbstractRepository::dinamicValidate($request, $this->carro->rules(), $this->carro->feedbacks());
        
        try {
            $this->carro->update($request->all());

            return response()->json($this->carro);
        } catch( Exception $e ) {
            return response()->json(['erro' => 'Ops! Houve um erro ao atualizar a carro. '.$e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $carro
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $carro)
    {
        try {
            $this->carro = $this->carro->findOrFail($carro);
            $nome = $this->carro->nome;
            $this->carro->delete();
   
            return response()->json(['msg' => "O carro $nome foi removido com sucesso!"]);
        } catch( Exception $e ) {
            return response()->json(['erro' => 'Ops! Houve um erro ao tentar excluir o carro! '.$e->getMessage()], 404);
        }
    }
}

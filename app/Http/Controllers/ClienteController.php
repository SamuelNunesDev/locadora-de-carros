<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Repositories\AbstractRepository;
use Exception;

class ClienteController extends Controller
{
    //Injetando o model como atributo do controlador
    public function __construct(Cliente $cliente)
    {
        $this->cliente = $cliente;
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
                $this->cliente = AbstractRepository::selectAtributos($this->cliente, 'id,'.$request->atributos);
            }
            
            if( $request->has('filtros') ) {
                $this->cliente = AbstractRepository::filtros($this->cliente, $request->filtros);
            }

            return response()->json($this->cliente->get());
        } catch(Exception $e) {
            return response()->json(['erro' => 'Ops! Houve um erro ao recuperar os clientes. '.$e->getMessage()]);
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
        $request->validate($this->cliente->rules(), $this->cliente->feedbacks());

        try {
            $this->cliente->nome = $request->nome;
            $this->cliente->save();

            return response()->json($this->cliente, 201);
        } catch(Exception $e) { 
            return response()->json(['erro' => 'Ops! Houve um erro ao salvar o cliente. '.$e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $cliente
     * @return \Illuminate\Http\Response
     */
    public function show(int $cliente)
    {
        try {
            return response()->json($this->cliente->findOrFail($cliente));
        } catch(Exception $e) {
            return response()->json(['erro' => 'Erro! Nenhum registro encontrado! ', $e->getMessage()], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $cliente)
    {
        $this->cliente = $this->cliente->find($cliente);
        if( $this->cliente === null ) { 
            return response()->json(['erro' => 'Erro! Cliente não foi encontrado ou não existe.'], 404); 
        }
        AbstractRepository::dinamicValidate($request, $this->cliente->rules(), $this->cliente->feedbacks());
        
        try {
            $this->cliente->update($request->all());

            return response()->json($this->cliente);
        } catch( Exception $e ) {
            return response()->json(['erro' => 'Ops! Houve um erro ao atualizar a cliente. '.$e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $cliente
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $cliente)
    {
        try {
            $this->cliente = $this->cliente->findOrFail($cliente);
            $nome = $this->cliente->nome;
            $this->cliente->delete();
   
            return response()->json(['msg' => "O cliente $nome foi removido com sucesso!"]);
        } catch( Exception $e ) {
            return response()->json(['erro' => 'Ops! Houve um erro ao tentar excluir o cliente! '.$e->getMessage()], 404);
        }
    }
}

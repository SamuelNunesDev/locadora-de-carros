<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Locacao;
use App\Repositories\AbstractRepository;
use Exception;

class LocacaoController extends Controller
{
    //Injetando o model como atributo do controlador
    public function __construct(Locacao $locacao)
    {
        $this->locacao = $locacao;
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
                $this->cliente = AbstractRepository::selectAtributos($this->locacao, 'id,'.$request->atributos);
            }
            
            if( $request->has('filtros') ) {
                $this->locacao = AbstractRepository::filtros($this->locacao, $request->filtros);
            }

            return response()->json($this->locacao->get());
        } catch(Exception $e) {
            return response()->json(['erro' => 'Ops! Houve um erro ao recuperar as locacoes. '.$e->getMessage()]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->locacao->rules(), $this->locacao->feedbacks());

        try {
            $this->locacao->cliente_id = $request->cliente_id;
            $this->locacao->carro_id = $request->carro_id;
            $this->locacao->data_inicio = $request->data_inicio;
            $this->locacao->data_fim_previsto = $request->data_fim_previsto;
            $this->locacao->data_fim_realizado = $request->data_fim_realizado;
            $this->locacao->valor_diaria = $request->valor_diaria;
            $this->locacao->km_final = $request->km_final;
            $this->locacao->save();

            return response()->json($this->locacao, 201);
        } catch(Exception $e) { 
            return response()->json(['erro' => 'Ops! Houve um erro ao salvar o locacao. '.$e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $locacao
     * @return \Illuminate\Http\Response
     */
    public function show(int $locacao)
    {
        try {
            return response()->json($this->locacao->findOrFail($locacao));
        } catch(Exception $e) {
            return response()->json(['erro' => 'Erro! Nenhum registro encontrado! ', $e->getMessage()], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $locacao
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $locacao)
    {
        $this->locacao = $this->locacao->find($locacao);
        if( $this->locacao === null ) { 
            return response()->json(['erro' => 'Erro! Locacao não foi encontrado ou não existe.'], 404); 
        }
        AbstractRepository::dinamicValidate($request, $this->locacao->rules(), $this->locacao->feedbacks());
        
        try {
            $this->locacao->update($request->all());

            return response()->json($this->locacao);
        } catch( Exception $e ) {
            return response()->json(['erro' => 'Ops! Houve um erro ao atualizar a locacao. '.$e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $locacao
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $locacao)
    {
        try {
            $this->locacao = $this->locacao->findOrFail($locacao);
            $nome = $this->locacao->nome;
            $this->locacao->delete();
   
            return response()->json(['msg' => "O locacao $nome foi removido com sucesso!"]);
        } catch( Exception $e ) {
            return response()->json(['erro' => 'Ops! Houve um erro ao tentar excluir o locacao! '.$e->getMessage()], 404);
        }
    }
}

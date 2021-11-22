<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class AbstractRepository
{
    //Aplica a validação de acordo com o verbo REST da requisição.
    public static function dinamicValidate($request, $rules, $feedbacks)
    {
        if( $request->method() === 'PATCH' ) {
            $rules = Arr::where($rules, function($value, $key) use($request){
                return Arr::exists($request->all(), $key);
            });
            $request->validate($rules, $feedbacks);
        } else {
           $request->validate($rules, $feedbacks);
        }
    }

    /**
     * Implementa uma query de acordo com os atributos definidos
     * 
     * @param $model
     * @param string $atributos
     * @return Illuminate\Database\Eloquent\Builder
     */
    public static function selectAtributos($model, $atributos)
    {
        return $model->selectRaw($atributos);
    }

    /**
     * Implementa uma query de acordo com os filtros definidos
     * 
     * @param $model
     * @param string $filtros
     * @return Illuminate\Database\Eloquent\Builder $model
     */
    public static function filtros($model, $filtros)
    {
        foreach(explode('|', $filtros) as $filtro) {
            $condicao = explode(':', $filtro);
            $model = $model->where($condicao[0], $condicao[1], $condicao[2]);
        }
        return $model;
    }

    /**
     * Implementa uma query de acordo com os atributos definidos
     * 
     * @param $model
     * @param string $atributos
     * @return Illuminate\Database\Eloquent\Builder $model
     */
    public static function atributosRelacionamento($model, $atributos)
    { 
        return $model->with($atributos);
    }
}
?>
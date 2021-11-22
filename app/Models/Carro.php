<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Carro extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'modelo_id',
        'placa',
        'disponivel',
        'km'
    ];

    // Retorna as regras para validação do formulário
    public function rules()
    {
        return [
            'modelo_id' => 'exists:modelos,id|integer',
            'placa'=> "required|max:10|unique:carros,placa,".$this->id."|string",
            'disponivel' => 'required|boolean',
            'km' => 'required|integer'
        ];
    }

    // Retorna as mensagens de erro para as respectivas regras que não forem atendidas na validação do formulário
    public function feedbacks()
    {
        return [
            'required' => 'O campo :attribute deve ser preenchido!',
            'unique' => 'Já existe uma marca registrada com este nome! Por favor escolha um nome único!',
            'max' => 'O campo :attribute deve conter no máximo :max caracteres!',
            'string' => 'O campo :attribute deve ser preenchido com letras e números!',
            'integer' => 'O campo :attribute deve ser preenchido somente com números inteiros!',
            'exists' => 'A marca informada não existe!',
            'boolean' => 'Valor inválido informado no campo :attribute!'
        ];
    }

    // Relacionamento Carro pertence a um Modelo
    public function modelo()
    {
        return $this->belongsTo(Modelo::class);
    }
}

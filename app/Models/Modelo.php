<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Modelo extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'marca_id',
        'nome',
        'imagem',
        'numero_portas',
        'lugares',
        'airbag',
        'abs'
    ];

    // Retorna as regras para validação do formulário
    public function rules()
    {
        return [
            'marca_id' => 'exists:marcas,id|integer',
            'nome'=> "required|max:30|string",
            'imagem' => 'required|file|mimes:png,jpeg,jpg',
            'numero_portas' => 'required|integer',
            'lugares' => 'required|integer',
            'airbag' => 'required|boolean',
            'abs' => 'required|boolean'
        ];
    }

    // Retorna as mensagens de erro para as respectivas regras que não forem atendidas na validação do formulário
    public function feedbacks()
    {
        return [
            'required' => 'O campo :attribute deve ser preenchido!',
            'unique' => 'Já existe uma marca registrada com este nome! Por favor escolha um nome único!',
            'max' => 'O campo :attribute deve conter no máximo :max caracteres!',
            'file' => 'No campo :attribute deve ser enviado um arquivo do tipo png, jpeg ou jpg!',
            'mimes' => 'Só é possível enviar arquivos nos formatos png, jpeg ou jpg!',
            'string' => 'O campo :attribute deve ser preenchido com caracteres do tipo texto!',
            'integer' => 'O campo :attribute deve ser preenchido somente com números inteiros!',
            'exists' => 'A marca informada não existe!',
            'boolean' => 'Valor inválido informado no campo :attribute!'
        ];
    }

    // Relacionamento Modelo pertence a uma Marca
    public function marca()
    {
        return $this->belongsTo(Marca::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Marca extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'nome',
        'imagem'
    ];

    // Retorna as regras para validação do formulário
    public function rules()
    {
        return [
            'nome'=> "required|unique:marcas,nome,".$this->id."|max:30",
            'imagem' => 'required|file|mimes:png,jpeg,jpg'
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
            'mimes' => 'Só é possível enviar arquivos nos formatos png, jpeg ou jpg!'
        ];
    }
}

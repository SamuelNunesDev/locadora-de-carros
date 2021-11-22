<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cliente extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['nome'];

    // Retorna as regras para validação do formulário
    public function rules()
    {
        return [
            'nome' => 'required|max:30'
        ];
    }

    // Retorna as mensagens de erro para as respectivas regras que não forem atendidas na validação do formulário
    public function feedbacks()
    {
        return [
            'required' => 'O campo :attribute deve ser preenchido!',
            'max' => 'O campo :attribute deve conter no máximo :max caracteres!'
        ];
    }
}

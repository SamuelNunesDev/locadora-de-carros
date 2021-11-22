<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Locacao extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'locacoes';
    protected $fillable = [
        'cliente_id',
        'carro_id',
        'data_inicio',
        'data_fim_previsto',
        'data_fim_realizado',
        'valor_diaria',
        'km_final'
    ];

    // Retorna as regras para validação do formulário
    public function rules()
    {
        return [
            'cliente_id' => 'required|exists:clientes,id|integer',
            'carro_id' => 'required|exists:carros,id|integer',
            'data_inicio' => 'required|date',
            'data_fim_previsto' => 'required|date',
            'data_fim_realizado' => 'required|date',
            'valor_diaria' => 'required',
            'km_final' => 'required|integer'
        ];
    }

    // Retorna as mensagens de erro para as respectivas regras que não forem atendidas na validação do formulário
    public function feedbacks()
    {
        return [
            'required' => 'O campo :attribute deve ser preenchido!',
            'max' => 'O campo :attribute deve conter no máximo :max caracteres!',
            'cliente_id.exists' => 'O cliente não existe!',
            'carro_id.exists' => 'O carro não existe!',
            'date' => 'O campo :attribute deve ser do tipo data!',
            'integer' => 'O campo :attribute deve ser do tipo número inteiro!'
        ];
    }
}

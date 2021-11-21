<?php

use App\Models\Carro;
use App\Models\Cliente;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocacoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locacoes', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Cliente::class);
            $table->foreignIdFor(Carro::class);
            $table->dateTime('data_inicio');
            $table->dateTime('data_fim_previsto');
            $table->dateTime('data_fim_realizado');
            $table->float('valor_diaria', 5, 2, true);
            $table->string('km_final', 45);
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('cliente_id')->references('id')->on('clientes');
            $table->foreign('carro_id')->references('id')->on('carros');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('locacoes');
    }
}

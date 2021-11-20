<?php

use App\Models\Marca;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModelosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modelos', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Marca::class);
            $table->string('nome', 30);
            $table->string('imagem', 100);
            $table->tinyInteger('numero_portas');
            $table->tinyInteger('lugares');
            $table->boolean('airbag');
            $table->boolean('abs');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('modelos');
    }
}

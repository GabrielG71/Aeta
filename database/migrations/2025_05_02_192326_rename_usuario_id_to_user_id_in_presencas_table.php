<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
        public function up()
    {
        Schema::table('presencas', function (Blueprint $table) {
            $table->renameColumn('usuario_id', 'user_id');
        });
    }

    public function down()
    {
        Schema::table('presencas', function (Blueprint $table) {
            $table->renameColumn('user_id', 'usuario_id');
        });
}

};

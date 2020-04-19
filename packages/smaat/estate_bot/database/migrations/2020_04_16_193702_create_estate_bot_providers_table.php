<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use SmaaT\EstateBot\Enum;

class CreateEstateBotProvidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estate_bot_providers', function (Blueprint $table) {
            $table->id();
            $table->enum('provider', [
                Enum::MaskanFile,
                Enum::MelkeIrani
            ]);
            $table->string('username', 50);
            $table->string('password', 100);
            $table->string('base_url', 100);
            $table->mediumText('fields')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('estate_bot_providers');
    }
}

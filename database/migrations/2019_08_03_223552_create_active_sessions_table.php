<?php

use Illuminate\Support\Facades\Schema;
use App\Helpers\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActiveSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $schema = DB::connection()->getSchemaBuilder();

        $schema->blueprintResolver(function($table, $callback) {
            return new Blueprint($table, $callback);
        });

        $schema->create('active_sessions', function (Blueprint $table) {
            $table->increments('id');

            $table->uuid('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->feilds([
                'user_agent',
                'created_at' => 'timestamp'
            ]);
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('active_sessions');
    }
}

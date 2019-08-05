<?php

use Illuminate\Support\Facades\Schema;
use App\Helpers\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccessCodesTable extends Migration
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

        $schema->create('access_codes', function (Blueprint $table) {
            $table->increments('id');

            $table->uuid('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->feilds([
                'code',
                'expired_at'    => [ 'type' => 'timestamp', 'nullable' ]
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
        Schema::dropIfExists('access_codes');
    }
}

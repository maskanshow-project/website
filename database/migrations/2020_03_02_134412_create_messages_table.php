<?php
use Illuminate\Database\Migrations\Migration;
use App\Helpers\Blueprint;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up()
    {
        $schema = DB::connection()->getSchemaBuilder();

        $schema->blueprintResolver(function($table, $callback) {
            return new Blueprint($table, $callback);
        });

        $schema->create('messages', function (Blueprint $table) {
            $table->table([
                'type'                  => '10|default:default',
                'jalali_created_at'     => 'datetime|nullable',
                'is_active'             => 'boolean|default:1'
            ], [
                'users',
                'roles'
            ], 'int', ['expired_at']);
        });

        $schema->create('message_translations', function ($table) {
            $table->increments('id');
            $table->integer('message_id')->unsigned();

            $table->string('title', 100);
            $table->mediumText('message');

            $table->string('locale')->index();
            $table->unique(['message_id','locale']);
            $table->foreign('message_id')->references('id')->on('messages')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return  void
     */
    public function down()
    {
        Schema::dropIfExists('message');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use SmaaT\EstateBot\Enum;

class CreateCrawledLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crawled_links', function (Blueprint $table) {
            $table->id();

            $table->unsignedInteger('estate_id')->nullable();
            $table->foreign('estate_id')
                ->references('id')
                ->on('estates')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->enum('provider', [
                Enum::MaskanFile,
                Enum::MelkeIrani
            ]);
            $table->string('link');
            $table->mediumText('errors')->nullable();
            $table->timestamp('crawled_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('crawled_links');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
// use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Helpers\Blueprint;

class CreateBlogTables extends Migration
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

        $schema->create('subjects', function (Blueprint $table) {
            $table->table([
                'icon'              => 'nullable|max:50',
                'jalali_created_at' => 'datetime|nullable',
                'is_active'         => 'boolean|default:1'
            ], ['self', 'users']);
        });

        $schema->create('subject_translations', function ($table) {
            $table->increments('id');
            $table->integer('subject_id')->unsigned();
            $table->string('slug', 100);
            $table->string('title', 50);
            $table->string('description', 255)->nullable();
            $table->string('locale')->index();

            $table->unique(['subject_id','locale']);
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
        });
        
        $schema->create('articles', function (Blueprint $table) {
            $table->table([
                'reading_time'      => 'nullable|mediumInteger|comment:How much time need for reading the article in minute',
                'jalali_created_at' => 'datetime|nullable',
                'is_active'         => 'boolean|default:1'
            ], [
                'users',
            ], 'uuid');
        });

        $schema->create('article_translations', function ($table) {
            $table->increments('id');
            $table->uuid('article_id');

            $table->string('slug', 100);
            $table->string('title', 100);
            $table->string('description', 255)->nullable();
            $table->text('body');

            $table->string('locale')->index();
            $table->unique(['article_id','locale']);
            $table->foreign('article_id')->references('id')->on('articles')->onDelete('cascade');
        });

        $schema->create('article_subject', function (Blueprint $table) {
            $table->interface('articles', 'subjects');
        });

        $schema->create('comments', function (Blueprint $table) {
            $table->id();
            $table->add_foreign();
            $table->relations([
                'users' => true,
                'articles' => true,
            ]);
            $table->string('title', 100)->nullable();
            $table->mediumText('message');
            $table->boolean('is_accept')->defult(false);
            $table->dateTime('jalali_created_at')->nullable();
            $table->full_timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $schema->dropIfExists('articles');
    }
}

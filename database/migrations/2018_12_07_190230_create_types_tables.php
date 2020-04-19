<?php

use Illuminate\Support\Facades\Schema;
// use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Helpers\Blueprint;

class CreateTypesTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $schema = DB::connection()->getSchemaBuilder();

        $schema->blueprintResolver(function ($table, $callback) {
            return new Blueprint($table, $callback);
        });

        $schema->create('features', function (Blueprint $table) {
            $table->table([
                'icon'                  => 'nullable|50',
                'jalali_created_at'     => 'datetime|nullable',
                'is_detailable'         => 'boolean|default:0',
                'is_active'             => 'boolean|default:1'
            ], ['users']);
        });

        $schema->create('feature_translations', function ($table) {
            $table->increments('id');
            $table->integer('feature_id')->unsigned();

            $table->string('title', 50);
            $table->string('description', 250)->nullable();

            $table->string('locale')->index();
            $table->unique(['feature_id', 'locale']);
            $table->foreign('feature_id')->references('id')->on('features')->onDelete('cascade');
        });

        $schema->create('assignments', function (Blueprint $table) {
            $table->table([
                'color'                 => '9|comment:Hexadecimal code of the assignment, e.g #43df12',
                'has_sales_price'       => 'required|boolean',
                'has_mortgage_price'    => 'required|boolean',
                'has_rental_price'      => 'required|boolean',
                'jalali_created_at'     => 'datetime|nullable',
                'is_active'             => 'boolean|default:1'
            ], ['users']);
        });

        $schema->create('assignment_translations', function ($table) {
            $table->increments('id');
            $table->integer('assignment_id')->unsigned();

            $table->string('title', 50);
            $table->string('description', 250)->nullable();

            $table->string('locale')->index();
            $table->unique(['assignment_id', 'locale']);
            $table->foreign('assignment_id')->references('id')->on('assignments')->onDelete('cascade');
        });

        $schema->create('estate_types', function (Blueprint $table) {
            $table->table([
                'icon'                  => 'nullable|50',
                'jalali_created_at'     => 'datetime|nullable',
                'is_active'             => 'boolean|default:1'
            ], ['users']);
        });

        $schema->create('estate_type_translations', function ($table) {
            $table->increments('id');
            $table->integer('estate_type_id')->unsigned();

            $table->string('title', 50);
            $table->string('description', 250)->nullable();

            $table->string('locale')->index();
            $table->unique(['estate_type_id', 'locale']);
            $table->foreign('estate_type_id')->references('id')->on('estate_types')->onDelete('cascade');
        });

        $schema->create('assignment_type', function (Blueprint $table) {
            $table->interface('assignments', 'estate_types');
        });

        $schema->create('estate_type_feature', function (Blueprint $table) {
            $table->interface('estate_types', 'features');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}

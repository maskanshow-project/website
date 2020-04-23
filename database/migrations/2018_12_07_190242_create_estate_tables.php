<?php

use Illuminate\Support\Facades\Schema;
// use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Helpers\Blueprint;

class CreateEstateTables extends Migration
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

        $schema->create('labels', function (Blueprint $table) {
            $table->table([
                'color'             => '9|comment:Hexadecimal code of the color, e.g #43df12',
                'jalali_created_at' => 'datetime|nullable',
                'is_active'         => 'boolean|default:1'
            ], [
                'users',
            ]);
        });

        $schema->create('label_translations', function ($table) {
            $table->increments('id');
            $table->integer('label_id')->unsigned();

            $table->string('title', 50);
            $table->string('description', 250)->nullable();

            $table->string('locale')->index();
            $table->unique(['label_id', 'locale']);
            $table->foreign('label_id')->references('id')->on('labels')->onDelete('cascade');
        });

        $schema->create('estates', function (Blueprint $table) {
            $table->table([
                'code'              => '20|nullable',
                'area'              => 'integer',
                'coordinates'       => 'point|nullable',
                'aparat_video'      => '10|nullable',
                'views_count'       => 'unsignedInteger|default:0',

                'landlord_fullname' => 50,
                'landlord_phone_number' => 15,

                'sales_price'       => 'unsignedBigInteger|nullable',
                'mortgage_price'    => 'unsignedBigInteger|nullable',
                'rental_price'      => 'unsignedBigInteger|nullable',

                'want_cooperation'  => 'boolean|default:1',

                'assignment_count'  => 'integer|default:0',
                'jalali_created_at' => 'datetime|nullable',
                'is_active'         => 'nullable|boolean',
                'reject_reason'     => '255|nullable',
            ], [
                'offices' => ['nullable', 'set null'],
                'users',
                'streets' => ['nullable', 'set null'],
                'assignments',
                'estate_type',
                'labels' => ['nullable', 'set null'],
                'specs' => ['nullable', 'set null']
            ], 'int', ['assignmented_at']);
        });

        \DB::statement('ALTER TABLE estates AUTO_INCREMENT = 1000;');

        $schema->create('estate_translations', function ($table) {
            $table->increments('id');
            $table->unsignedInteger('estate_id');

            $table->string('title', 50)->nullable();
            $table->string('description', 255)->nullable();
            $table->string('address', 100)->nullable();
            $table->string('plaque', 20)->nullable();
            $table->array('advantages');
            $table->array('disadvantages');

            $table->string('locale')->index();

            $table->unique(['estate_id', 'locale']);
            $table->foreign('estate_id')->references('id')->on('estates')->onDelete('cascade');
        });

        $schema->create('estate_feature', function (Blueprint $table) {
            $table->interface('estates', 'features');
        });

        $schema->create('assignment_informations', function (Blueprint $table) {
            $table->interface('estates', 'users');
        });

        $schema->create('spec_data', function (Blueprint $table) {
            $table->id();
            $table->reltoSpec_rows();
            $table->reltoEstates();
            $table->full_timestamps();
        });

        $schema->create('spec_data_translations', function ($table) {
            $table->increments('id');
            $table->integer('spec_data_id')->unsigned();

            $table->mediumText('data')->nullable();

            $table->string('locale')->index();

            $table->unique(['spec_data_id', 'locale']);
            $table->foreign('spec_data_id')->references('id')->on('spec_data')->onDelete('cascade');
        });

        $schema->create('spec_data_values', function (Blueprint $table) {
            $table->add_foreign('spec_data', false, 'unsignedInteger', 'spec_data_id');
            $table->reltoSpec_defaults();

            $table->primary(['spec_data_id', 'spec_default_id']);
        });

        $schema->create('favorites', function (Blueprint $table) {
            $table->interface('users', 'estates');
        });

        $schema->create('visited_estates', function (Blueprint $table) {
            $table->interface('users', 'estates');
            $table->dateTime('visited_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $schema->dropIfExists('products');
        $schema->dropIfExists('variations');
        $schema->dropIfExists('accessories');
        $schema->dropIfExists('spec_data');
    }
}

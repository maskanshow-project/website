<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSimilarTitlesFieldToSomeTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('assignment_translations', function (Blueprint $table) {
            $table->mediumText('similar_titles')->nullable()->after('title');
        });

        Schema::table('estate_type_translations', function (Blueprint $table) {
            $table->mediumText('similar_titles')->nullable()->after('title');
        });

        Schema::table('spec_row_translations', function (Blueprint $table) {
            $table->mediumText('similar_titles')->nullable()->after('title');
        });

        Schema::table('spec_default_translations', function (Blueprint $table) {
            $table->mediumText('similar_titles')->nullable()->after('value');
        });

        Schema::table('feature_translations', function (Blueprint $table) {
            $table->mediumText('similar_titles')->nullable()->after('title');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('assignment_translations', function (Blueprint $table) {
            $table->dropColumn('similar_titles');
        });

        Schema::table('estate_type_translations', function (Blueprint $table) {
            $table->dropColumn('similar_titles');
        });

        Schema::table('spec_row_translations', function (Blueprint $table) {
            $table->dropColumn('similar_titles');
        });

        Schema::table('spec_default_translations', function (Blueprint $table) {
            $table->dropColumn('similar_titles');
        });

        Schema::table('feature_translations', function (Blueprint $table) {
            $table->dropColumn('similar_titles');
        });
    }
}

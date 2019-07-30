<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use App\Helpers\Blueprint;

class CreateUsersTable extends Migration
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

        $schema->create('offices', function (Blueprint $table) {
            $table->table([
                'name'              => 50,
                'username'          => '32|unique',
                'description'       => '255|nullable',
                'address'           => '100|nullable',
                'phone_number'      => '15|unique|nullable',
                'jalali_created_at' => 'datetime|nullable'
            ], [
                'areas' => ['nullable', 'set null']
            ]);
        });

        $schema->create('users', function (Blueprint $table) {
            $table->table([
                'first_name'            => '20|nullable',
                'last_name'             => '30|nullable',
                'address'               => '100|nullable',
                'phone_number'          => '15|unique|nullable',
                'username'              => '32|unique',
                'email'                 => '100|unique',
                'email_verified_at'     => 'timestamp|nullable',
                'password'              => 100,
                'national_code'         => '10|nullable',
                'gender'                => 'nullable|boolean',

                'can_has_member'        => 'boolean',
                'is_public_info'        => 'boolean|default:1',
                
                'rememberToken',
                
                'visited_estate_count'          => 'unsignedInteger|default:0',
                'remaining_hits_count'          => 'unsignedInteger|default:0',
                'registered_estate_count'       => 'unsignedInteger|default:0',
                'remaining_registered_count'    => 'unsignedInteger|default:0',

                'payments_count'        => 'unsignedInteger|default:0',
                'total_payments'        => 'unsignedBigInteger|default:0',

                'jalali_created_at'     => 'datetime|nullable',

                'system_authentication_code' => '50|nullable'
            ], [
                'offices' => ['nullable', 'set null'],
                'cities' => ['nullable', 'set null'],
            ], 'uuid', [
                'last_payment',
                'validity_end_at'
            ]);
        });

        $schema->table('offices', function (Blueprint $table) {
            $table->add_foreign('users', false, 'uuid');
        });

        $schema->table('areas', function (Blueprint $table) {
            $table->add_foreign('users', false, 'uuid');
        });

        $schema->table('streets', function (Blueprint $table) {
            $table->add_foreign('users', false, 'uuid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $schema->dropIfExists('users');
    }
}

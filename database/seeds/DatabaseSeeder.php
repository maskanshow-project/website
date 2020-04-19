<?php

use App\Helpers\CustomSeeder;
use App\Models\Estate\Label;
use App\Models\Places\Area;
use App\Models\Places\Street;
use App\Models\Places\City;
use App\Models\BlacklistPhoneNumber;

class DatabaseSeeder extends CustomSeeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(EstateBotInitSeeder::class);
        // $this->call(LocationTablesSeeder::class);

        // $this->call(UserTableSeeder::class);

        // factory(Area::class, 10)->create([
        //     'city_id' => City::all()->random()->id
        // ])->each( function($area) {
        //     $area->streets()->saveMany( factory(Street::class, rand(1, 10) )->make() );
        // });

        // factory(BlacklistPhoneNumber::class, rand(1, 20) )->create();

        // $this->call(LaratrustSeeder::class);

        // $this->call(OptionTableSeeder::class);

        // $this->call(BlogTablesSeeder::class);

        // $this->createTable(Label::class, ['id', 'title', 'color']);

        // $this->call(TypesTablesSeeder::class);

        // $this->call(SpecificationTablesSeeder::class);

        // $this->call(FinancialTablesSeeder::class);

        // for ($i = 0; $i < 10; $i++)
        //     $this->call(EstateTablesSeeder::class);

        // $this->call(AbstractTablesSeeder::class);
    }

    /**
     * Override the DatabaseSeder call method
     *
     * @param Seeder $class
     * @param mixed $extra
     * @return mixed $result
     */
    public function call($class, $extra = null)
    {
        $result = $this->resolve($class)->run($extra);
        if (isset($this->command)) {
            $this->command->getOutput()->writeln("<info>Seeded:</info> $class");
        }

        return $result;
    }
}

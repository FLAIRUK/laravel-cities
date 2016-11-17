use Illuminate\Database\Seeder;

class CitiesSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Empty the cities table
        DB::table(\Config::get('cities.table_name'))->delete();

        //Get all of the cities
        $cities = Cities::getList();
        foreach ($cities as $cityId => $city){
            DB::table(\Config::get('cities.table_name'))->insert(array(
                'id' => $cityId,
                'iso_3166_3' => $city['iso_3166_3'],
                'country_code' => $city['country_code'],
                'name' => $city['name'],
            ));
        }
    }
}

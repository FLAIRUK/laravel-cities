use Illuminate\Database\Migrations\Migration;

class CharifyCitiesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
            Schema::table(\Config::get('cities.table_name'), function($table)
            {

								DB::statement("ALTER TABLE " . DB::getTablePrefix() . \Config::get('cities.table_name') . " MODIFY iso_3166_3 CHAR(3) NOT NULL DEFAULT ''");
                DB::statement("ALTER TABLE " . DB::getTablePrefix() . \Config::get('cities.table_name') . " MODIFY country_code CHAR(2) NOT NULL DEFAULT ''");
                DB::statement("ALTER TABLE " . DB::getTablePrefix() . \Config::get('cities.table_name') . " MODIFY name CHAR(255) NOT NULL DEFAULT ''");
            });
        }


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
            Schema::table(\Config::get('cities.table_name'), function($table)
            {
							DB::statement("ALTER TABLE " . DB::getTablePrefix() . \Config::get('cities.table_name') . " MODIFY iso_3166_3 CHAR(3) NOT NULL DEFAULT ''");
							DB::statement("ALTER TABLE " . DB::getTablePrefix() . \Config::get('cities.table_name') . " MODIFY country_code CHAR(2) NOT NULL DEFAULT ''");
							DB::statement("ALTER TABLE " . DB::getTablePrefix() . \Config::get('cities.table_name') . " MODIFY name CHAR(255) NOT NULL DEFAULT ''");
            });
	}

}

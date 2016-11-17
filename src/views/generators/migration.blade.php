use Illuminate\Database\Migrations\Migration;

class SetupCitiesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Creates the users table
		Schema::create(\Config::get('cities.table_name'), function($table)
		{
		    $table->integer('id')->unsigned()->index();
		    $table->string('iso_3166_3', 3)->default('');
		    $table->string('country_code', 2)->default('');
		    $table->string('name', 255)->default('');

		    $table->primary('id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop(\Config::get('cities.table_name'));
	}

}

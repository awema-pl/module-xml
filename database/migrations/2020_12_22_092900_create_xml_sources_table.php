
<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateXmlSourcesTable extends Migration
{
    public function up()
    {
        Schema::create(config('xml.database.tables.xml_sources'), function (Blueprint $table) {
            $table->id();
            $table->morphs('sourceable');
            $table->timestamps();
        });

        Schema::table(config('xml.database.tables.xml_sources'), function (Blueprint $table) {
            $table->foreignId('user_id')
                ->constrained(config('xml.database.tables.users'))
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table(config('xml.database.tables.xml_sources'), function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
        Schema::drop(config('xml.database.tables.xml_sources'));
    }
}

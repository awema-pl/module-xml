
<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateXmlCeneosourcesTable extends Migration
{
    public function up()
    {
        Schema::create(config('xml.database.tables.xml_ceneosources'), function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('url');
            $table->timestamps();
        });
        Schema::table(config('xml.database.tables.xml_ceneosources'), function (Blueprint $table) {
            $table->foreignId('user_id')
                ->constrained(config('xml.database.tables.users'))
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table(config('xml.database.tables.xml_ceneosources'), function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::drop(config('xml.database.tables.xml_ceneosources'));
    }
}

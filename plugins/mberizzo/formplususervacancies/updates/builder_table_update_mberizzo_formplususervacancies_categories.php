<?php namespace Mberizzo\FormPlusUserVacancies\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateMberizzoFormplususervacanciesCategories extends Migration
{
    public function up()
    {
        Schema::table('mberizzo_formplususervacancies_categories', function($table)
        {
            $table->string('slug');
        });
    }
    
    public function down()
    {
        Schema::table('mberizzo_formplususervacancies_categories', function($table)
        {
            $table->dropColumn('slug');
        });
    }
}

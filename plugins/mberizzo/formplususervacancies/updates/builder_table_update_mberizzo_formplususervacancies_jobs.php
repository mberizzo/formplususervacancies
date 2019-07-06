<?php namespace Mberizzo\FormPlusUserVacancies\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateMberizzoFormplususervacanciesJobs extends Migration
{
    public function up()
    {
        Schema::table('mberizzo_formplususervacancies_jobs', function($table)
        {
            $table->integer('category_id')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('mberizzo_formplususervacancies_jobs', function($table)
        {
            $table->dropColumn('category_id');
        });
    }
}

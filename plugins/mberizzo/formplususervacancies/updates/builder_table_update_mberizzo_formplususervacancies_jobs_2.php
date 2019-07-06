<?php namespace Mberizzo\FormPlusUserVacancies\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateMberizzoFormplususervacanciesJobs2 extends Migration
{
    public function up()
    {
        Schema::table('mberizzo_formplususervacancies_jobs', function($table)
        {
            $table->string('tags')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('mberizzo_formplususervacancies_jobs', function($table)
        {
            $table->dropColumn('tags');
        });
    }
}

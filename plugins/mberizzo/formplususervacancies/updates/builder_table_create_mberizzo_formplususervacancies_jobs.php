<?php namespace Mberizzo\FormPlusUserVacancies\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateMberizzoFormplususervacanciesJobs extends Migration
{
    public function up()
    {
        Schema::create('mberizzo_formplususervacancies_jobs', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('title');
            $table->string('slug')->index();
            $table->text('description');
        });
    }

    public function down()
    {
        Schema::dropIfExists('mberizzo_formplususervacancies_jobs');
    }
}

<?php namespace Mberizzo\FormPlusUserVacancies\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateMberizzoFormplususervacanciesJobUser extends Migration
{
    public function up()
    {
        Schema::create('mberizzo_formplususervacancies_job_user', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('job_id');
            $table->integer('user_id');
            $table->timestamp('created_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('mberizzo_formplususervacancies_job_user');
    }
}

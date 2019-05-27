<?php namespace Mberizzo\FormPlusUserVacancies\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateMberizzoFormplususervacanciesLogUser extends Migration
{
    public function up()
    {
        Schema::create('mberizzo_formplususervacancies_log_user', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->integer('log_id');
            $table->integer('user_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('mberizzo_formplususervacancies_log_user');
    }
}

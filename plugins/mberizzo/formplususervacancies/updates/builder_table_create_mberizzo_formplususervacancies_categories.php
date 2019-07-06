<?php namespace Mberizzo\FormPlusUserVacancies\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateMberizzoFormplususervacanciesCategories extends Migration
{
    public function up()
    {
        Schema::create('mberizzo_formplususervacancies_categories', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('mberizzo_formplususervacancies_categories');
    }
}

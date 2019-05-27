<?php

namespace Mberizzo\FormPlusUserVacancies\Models;

use Model;

/**
 * Model
 */
class LogUser extends Model
{

    /**
     * @var array Fillable fields
     */
    protected $fillable = ['user_id', 'log_id'];

    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'mberizzo_formplususervacancies_log_user';

    public $belongsTo = [
        'log' => ['Renatio\FormBuilder\Models\FormLog']
    ];
}

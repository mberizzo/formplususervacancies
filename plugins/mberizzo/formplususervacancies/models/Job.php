<?php namespace Mberizzo\FormPlusUserVacancies\Models;

use Model;

/**
 * Model
 */
class Job extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\Sluggable;

    /**
     * @var array Generate slugs for these attributes.
     */
    protected $slugs = ['slug' => 'title'];

    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;


    /**
     * @var string The database table used by the model.
     */
    public $table = 'mberizzo_formplususervacancies_jobs';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];
}

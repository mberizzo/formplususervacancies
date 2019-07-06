<?php namespace Mberizzo\FormPlusUserVacancies\Models;

use Mberizzo\FormPlusUserVacancies\Models\Category;
use Mberizzo\FormPlusUserVacancies\Models\JobUser;
use Model;
use RainLab\User\Models\User;

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

    public $attachOne = [
        'image' => 'System\Models\File'
    ];

    public $belongsTo = [
        'category' => [
            Category::class,
            'key' => 'category_id'
        ],
    ];

    public $belongsToMany = [
        'users' => [
            User::class,
            'table' => 'mberizzo_formplususervacancies_job_user',
        ],
        'users_count' => [
            User::class,
            'count' => true,
            'table' => 'mberizzo_formplususervacancies_job_user',
        ],
    ];

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

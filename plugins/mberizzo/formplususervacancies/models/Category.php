<?php namespace Mberizzo\FormPlusUserVacancies\Models;

use Model;

/**
 * Model
 */
class Category extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\Sluggable;

    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;

    /**
     * @var array Generate slugs for these attributes.
     */
    protected $slugs = ['slug' => 'name'];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'mberizzo_formplususervacancies_categories';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    public function beforeUpdate()
    {
        $this->slug = str_slug($this->title);
        $this->slugAttributes(); // Regenerate slugs if exists
    }
}

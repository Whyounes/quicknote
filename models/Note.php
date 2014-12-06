<?php namespace RAFIE\Quicknote\Models;

use Model;

/**
 * note Model
 */
class Note extends Model
{
    // will be used for automatic validation using the defined rules
    use \October\Rain\Database\Traits\Validation;

    public $table = 'rafie_quicknote_notes';

    protected $guarded = ['*'];

    protected $rules = [
        'title'         => 'required|min:4'
    ];

    protected $throwOnValidation = false;

    public $belongsTo = [ 'user' => [ 'Backend\Models\User' ] ];

}
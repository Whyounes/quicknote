<?php namespace RAFIE\Quicknote\Controllers;

use Backend\Facades\BackendAuth;
use Backend\Facades\Backend;
use BackendMenu;
use Backend\Classes\Controller;
use \RAFIE\Quicknote\Models\Note;
use Illuminate\Support\Facades\Input;
use \RAFIE\Quicknote\Models;
use Rafie\QuickNote\Plugin;
use System\Classes\PluginManager;

/**
 * Notes Back-end Controller
 */
class Notes extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public function __construct()
    {
        parent::__construct();

        $this->pageTitle = 'Manage Quick Notes';

        //BackendMenu::setContext('RAFIE.Quicknote', 'quicknote', 'notes');
    }

    public function store(){
        $note = new Models\Note;
        $note->title = Input::get('title');
        $note->description = Input::get('description', null);
        $note->user_id = BackendAuth::getUser()->id;

        if( $note->save() ) {
            \Flash::success('Note added successfully.');
        }
        else{
            $messages = array_flatten( $note->errors()->getMessages() );
            $errors = implode( ' - ', $messages );

            \Flash::error('Validation error: ' . $errors );
        }

        return \Redirect::to( Backend::url() );
    }

    public function formBeforeCreate($model){
        $model->user_id = BackendAuth::getUser()->id;
    }

    public function index(){
        $this->makeLists();
        $this->makeView('index');
    }

    // filtering notes by user, use also @listExtendQueryBefore
    public function listExtendQueryBefore($query){
        $user_id = BackendAuth::getUser()->id;

        $query->where('user_id', '=', $user_id);
    }

    public function listOverrideColumnValue($record, $columnName){
        if( $columnName == "description" && empty($record->description) )
            return "[EMPTY]";
    }

    // or you can name it index_onDelete
    public function onDelete()
    {
        $user_id = BackendAuth::getUser()->id;
        $notes = post("notes");

        Note::whereIn('id', $notes)->where('user_id', '=', $user_id)->delete();

        \Flash::success('Notes Successfully deleted.');

        return $this->listRefresh();
    }

}
<?php namespace RAFIE\Quicknote\Controllers;

use Backend\Facades\BackendAuth;
use Backend\Facades\Backend;
use BackendMenu;
use Backend\Classes\Controller;
use \RAFIE\Quicknote\Models\Note;
use Illuminate\Support\Facades\Input;
use \RAFIE\Quicknote\Models;
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
        //issue [https://github.com/octobercms/october/issues/799]
        //$app = App::make('app');
        //$auth = new \Illuminate\Auth\AuthManager($app);

        $note = new Models\Note;
        $note->title = Input::get('title');
        $note->description = Input::get('description', null);
        $note->user_id = BackendAuth::getUser()->id;

        $note->save();

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
    public function listExtendQuery($query){
        $user_id = BackendAuth::getUser()->id;

        $query->where('user_id', '=', $user_id);
    }

    public function listOverrideColumnValue($record, $columnName){
        if( $columnName == "description" && empty($record->description) )
            return "[EMPTY]";

        if( $columnName == "action" ){
            $href = Backend::url('rafie/quicknotes/notes/remove/' . $record->id );

            return "<a href='" . $href . "' title='Remove Notes'><i class='icon-remove'></i></a>";
        }//if

    }

    public function listExtendColumns($list){
        /*$list->addColumns([
            'action' => [
                'label'     => 'Actions',
                'sortable'  => false
            ]
        ]);*/
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
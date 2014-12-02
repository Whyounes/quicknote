<?php namespace RAFIE\QuickNote;


use Backend\Classes\ReportWidgetBase;
use Backend\Facades\BackendAuth;
use \RAFIE\QuickNote\Models\Note;


class AddNoteWidget extends ReportWidgetBase{

    public function render()
    {
        $notes = Note::where('user_id', '=', BackendAuth::getUser()->id)->get();

        return $this->makePartial('notes', [ 'notes' => $notes ]);
    }

}

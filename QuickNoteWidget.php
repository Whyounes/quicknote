<?php namespace RAFIE\QuickNote;


use Backend\Classes\ReportWidgetBase;
use Backend\Facades\BackendAuth;
use \RAFIE\QuickNote\Models\Note;


class QuickNoteWidget extends ReportWidgetBase{

    public function render()
    {
        $notes = BackendAuth::getUser()->notes;

        return $this->makePartial('notes', [ 'notes' => $notes ]);
    }

    public function defineProperties()
    {
        return [
            'title'     => [
                'title'     => 'Widget title',
                'default'   => 'QUICK NOTE'
            ],
            'showList'  => [
                'title'     => 'Show notes',
                'type'      => 'checkbox'
            ]
        ];
    }


}

<?php namespace Rafie\QuickNote;

use Backend\Models\User;
use System\Classes\PluginBase;

/**
 * blogWidget Plugin Information File
 */
class Plugin extends PluginBase
{

    public function pluginDetails()
    {
        return [
            'name'        => 'Quick Note Widget',
            'description' => 'Add and manage some drafts when you\'re in a hurry.',
            'author'      => 'RAFIE Younes',
            'icon'        => 'icon-pencil'
        ];
    }

    public function registerReportWidgets()
    {
        return [
            'RAFIE\QuickNote\QuickNoteWidget' => [
                'label'     => 'Quick Notes',
                'context'   => 'dashboard'
            ]
        ];
    }

    public function boot()
    {
        User::extend(function($model){
            $model->hasMany['notes'] = ['RAFIE\Quicknote\Models\Note'];
        });
    }

}

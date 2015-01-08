<?php

class GmftasksModule extends HWebModule
{

    /**
     * Inits the Module
     */
    public function init()
    {
        $this->setImport(array(
            'gmftasks.*',
            'gmftasks.models.*',
            'gmftasks.behaviors.*',
        ));
    }

    public function behaviors()
    {

        return array(
            'SpaceModuleBehavior' => array(
                'class' => 'application.modules_core.space.behaviors.SpaceModuleBehavior',
            ),
        );
    }

    /**
     * On global module disable, delete all created content
     */
    public function disable()
    {
        if (parent::disable()) {
            foreach (Content::model()->findAllByAttributes(array('object_model' => 'Gmftask')) as $content) {
                $content->delete();
            }
            return true;
        }

        return false;
    }

    /**
     * On disabling this module on a space, deleted all module -> space related content/data.
     * Method stub is provided by "SpaceModuleBehavior"
     *
     * @param Space $space
     */
    public function disableSpaceModule(Space $space)
    {
        foreach (Content::model()->findAllByAttributes(array('space_id' => $space->id, 'object_model' => 'Gmftask')) as $content) {
            $content->delete();
        }
    }

    /**
     * On User delete, delete all task assignments
     *
     * @param type $event
     */
    public static function onUserDelete($event)
    {

        foreach (GmftaskUser::model()->findAllByAttributes(array('created_by' => $event->sender->id)) as $gmftask) {
            $gmftask->delete();
        }
        foreach (GmftaskUser::model()->findAllByAttributes(array('user_id' => $event->sender->id)) as $gmftask) {
            $gmftask->delete();
        }

        return true;
    }

    /**
     * On build of a Space Navigation, check if this module is enabled.
     * When enabled add a menu item
     *
     * @param type $event
     */
    public static function onSpaceMenuInit($event)
    {

        $space = Yii::app()->getController()->getSpace();

        // Is Module enabled on this space?
        if ($space->isModuleEnabled('gmftasks')) {
            $event->sender->addItem(array(
                'label' => Yii::t('GmftasksModule.base', 'Gmftasks'),
                'group' => 'modules',
                'url' => Yii::app()->createUrl('/gmftasks/gmftask/show', array('sguid' => $space->guid)),
                'icon' => '<i class="fa fa-check-square"></i>',
                'isActive' => (Yii::app()->controller->module && Yii::app()->controller->module->id == 'gmftasks'),
            ));
        }
    }



    public static function onDashboardSidebarInit($event)
    {

        $event->sender->addWidget('application.modules.gmftasks.widgets.GmfTasksWidget', array(), array('sortOrder' => 600));

    }
}

<?php

/**
 * Shows a Task Wall Entry
 */
class GmfTaskWallEntryWidget extends HWidget {

    public $gmftask;

    public function run() {
        $user = $this->gmftask->creator;

        $assignedUsers = $this->gmftask->getAssignedUsers();
        $assignedToCurrentUser = false;

        $assetPrefix = Yii::app()->assetManager->publish(dirname(__FILE__) . '/../resources', true, 0, defined('YII_DEBUG'));
        Yii::app()->clientScript->registerCssFile($assetPrefix . '/gmftasks.css');

        // Check if current users is assigned to this task (faster way)
        /*
          foreach ($assignedUsers as $au) {
          if ($au->id == Yii::app()->user->id) {
          $assignedToCurrentUser=true;
          break;
          }
          }

          'assignedUsers' => $assignedUsers,
          'assignedToCurrentUser' => $assignedToCurrentUser

         */
        $this->render('gmfentry', array(
            'gmftask' => $this->gmftask,
            'user' => $user,
            'space' => $this->gmftask->content->container,
        ));
    }

}

?>

<?php

/**
* Shows a Task Wall Entry
*/
class GmfTaskListWallEntryWidget extends HWidget {

	public $gmftasklist;

	public function run() {
		$user = $this->gmftasklist->creator;

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
$this->render('gmflistentry', array(
	'gmftasklist' => $this->gmftasklist,
	'user' => $user,
	'space' => $this->gmftasklist->content->container,
));
}

}

?>

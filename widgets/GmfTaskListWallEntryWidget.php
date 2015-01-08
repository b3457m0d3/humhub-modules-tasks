<?php

/**
* Shows a TaskList Wall Entry
*/
class GmfTaskListWallEntryWidget extends HWidget {

	public $gmftasklist;

	public function run() {
		$user = $this->gmftasklist->creator;

		$assetPrefix = Yii::app()->assetManager->publish(dirname(__FILE__) . '/../resources', true, 0, defined('YII_DEBUG'));
		Yii::app()->clientScript->registerCssFile($assetPrefix . '/gmftasks.css');


		$this->render('gmflistentry', array(
			'gmftasklist' => $this->gmftasklist,
			'user' => $user,
			'space' => $this->gmftasklist->content->container,
		));
	}

}

?>

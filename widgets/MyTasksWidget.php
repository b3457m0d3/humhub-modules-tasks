<?php

class MyTasksWidget extends HWidget {

	protected $themePath = 'modules/gmftasks';

	/**
	 * Creates the Wall Widget
	 */
	public function run() {


		$gfmtasks = Gmftask::GetUsersOpenTasks();

		if (count($gmftasks) > 0) {
			$this->render('mytasks', array('mytasks'=>$gmftasks));
		}
	}

}

?>

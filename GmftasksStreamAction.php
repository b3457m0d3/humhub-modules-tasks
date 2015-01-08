<?php

/**
 * StreamAction returns entries of a given wall
 *
 * @author Luke
 * @mods b3457m0d3
 */
class GmftasksStreamAction extends StreamAction {

	/**
	 * Inject Tasks Specific SQL
	 */
	protected function prepareSQL() {
		$this->sqlWhere .= " AND object_model='Gmftask'";
		parent::prepareSQL();
	}



	/**
	 * Handle Task  Specific Filters
	 */
	protected function setupFilterSQL() {


		if (in_array('gmftasks_meAssigned', $this->filters) || in_array('gmftasks_open', $this->filters) ||  in_array('gmftasks_finished', $this->filters) || in_array('gmftasks_notassigned', $this->filters) || in_array('gmftasks_byme', $this->filters) ) {

                        $this->sqlJoin .= " LEFT JOIN gmftask ON content.object_id=gmftask.id AND content.object_model = 'Gmftask'";

			if (in_array('gmftasks_meAssigned', $this->filters)) {
				$this->sqlJoin .= " LEFT JOIN gmftask_user ON gmftask.id=gmftask_user.gmftask_id AND gmftask_user.user_id= '".Yii::app()->user->id."'";
				$this->sqlWhere .= " AND gmftask_user.id is not null";
			}

			if (in_array('gmftasks_notassigned', $this->filters)) {
				$this->sqlWhere .= " AND (SELECT COUNT(*) FROM gmftask_user WHERE gmftask_id=gmftask.id) = 0 ";
			}

			if (in_array('gmftasks_byme', $this->filters)) {
				$this->sqlWhere .= " AND gmftask.created_by = '".Yii::app()->user->id."'";
			}

			if (in_array('gmftasks_open', $this->filters)) {
				$this->sqlWhere .= " AND gmftask.status = '".Gmftask::STATUS_OPEN."'";
			}

			if (in_array('gmftasks_finished', $this->filters)) {
				$this->sqlWhere .= " AND gmftask.status = '".Gfmtask::STATUS_FINISHED."'";
			}

		}


		parent::setupFilterSQL();


	}

}

?>

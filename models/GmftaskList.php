<?php

/**
* This is the model class for table "task".
*
* The followings are the available columns in table 'task':
* @property integer $id
* @property string $title
* @property string $deadline
* @property integer $max_users
* @property integer $min_users
* @property integer $precent
* @property string $created_at
* @property integer $created_by
* @property string $updated_at
* @property integer $updated_by
*/
class GmftaskList extends HActiveRecord
{

	/**
	* Returns the static model of the specified AR class.
	* @param string $className active record class name.
	* @return Task the static model class
	*/
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	* @return string the associated database table name
	*/
	public function tableName()
	{
		return 'gmftask_list';
	}

	/**
	* @return array validation rules for model attributes.
	*/
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
		array('title,  created_at, created_by, updated_at, updated_by', 'required'),
		array('created_by, updated_by', 'numerical', 'integerOnly' => true),
		);
	}

	/**
	* @return array relational rules.
	*/
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		'tasks' => array(self::HAS_MANY, 'Gmftask', 'list_id'),
		'creator' => array(self::BELONGS_TO, 'User', 'created_by'),
		);
	}

	/**
	* Deletes a List including its tasks.
	*/
	public function delete()
	{

		// delete all tasks user assignments
		$gmftaskUser = Gmftask::model()->findAllByAttributes(array('list_id' => $this->id));
		foreach ($gmftaskUser as $tu) {
			$tu->delete();
		}

		Notification::remove('GmftaskList', $this->id);

		return parent::delete();
	}

	/**
	* Returns the Wall Output
	*/
	public function getWallOut()
	{
		return Yii::app()->getController()->widget('application.modules.gmftasks.widgets.GmfTaskWallEntryWidget', array('gmftask' => $this), true);
	}

	public function beforeSave()
	{
		return parent::beforeSave();
	}

	/**
	* Before Save Addons
	*
	* @return type
	*/
	public function afterSave()
	{

		parent::afterSave();

		if ($this->isNewRecord) {
			$activity = Activity::CreateForContent($this);
			$activity->type = "GmftaskCreated";
			$activity->module = "gmftasks";
			$activity->save();
			$activity->fire();

			// Attach Preassigned Users
			$guids = explode(",", $this->preassignedUsers);
			foreach ($guids as $guid) {
				$guid = trim($guid);
				$user = User::model()->findByAttributes(array('guid' => $guid));
				if ($user != null) {
					$this->assignUser($user);
				}
			}
		}

		return true;
	}

	/**
	* Returns assigned users to this task
	*/
	public function getAssignedUsers()
	{
		$users = array();
		$tus = GmftaskUser::model()->findAllByAttributes(array('task_id' => $this->id));
		foreach ($tus as $tu) {
			$user = User::model()->findByPk($tu->user_id);
			if ($user != null)
			$users[] = $user;
		}
		return $users;
	}

	/**
	* Assign user to this task
	*/
	public function assignUser($user = "")
	{

		if ($user == "")
		$user = Yii::app()->user->getModel();

		$au = GmftaskUser::model()->findByAttributes(array('task_id' => $this->id, 'user_id' => $user->id));
		if ($au == null) {

			$au = new GmftaskUser;
			$au->task_id = $this->id;
			$au->user_id = $user->id;
			$au->save();

			# Handled by Notification now
			#$activity = Activity::CreateForContent($this);
			#$activity->type = "TaskAssigned";
			#$activity->module = "tasks";
			#$activity->content->user_id = $user->id;
			#$activity->save();
			#$activity->fire();
			// Fire Notification to creator
			$notification = new Notification();
			$notification->class = "GmftaskAssignedNotification";
			$notification->user_id = $au->user_id; // Assigned User
			$notification->space_id = $this->content->space_id;
			$notification->source_object_model = 'Gmftask';
			$notification->source_object_id = $this->id;
			$notification->target_object_model = 'Gmftask';
			$notification->target_object_id = $this->id;
			$notification->save();

			return true;
		}
		return false;
	}

	/**
	* UnAssign user to this task
	*/
	public function unassignUser($user = "")
	{
		if ($user == "")
		$user = Yii::app()->user->getModel();

		$au = GmftaskUser::model()->findByAttributes(array('task_id' => $this->id, 'user_id' => $user->id));
		if ($au != null) {
			$au->delete();

			// Delete Activity for Task Assigned
			$activity = Activity::model()->findByAttributes(array(
			'type' => 'GmftaskAssigned',
			'object_model' => "Gmftask",
			'user_id' => $user->id,
			'object_id' => $this->id
			));
			if ($activity)
			$activity->delete();

			// Try to delete TaskAssignedNotification if exists
			foreach (Notification::model()->findAllByAttributes(array('class' => 'GmftaskAssignedNotification', 'target_object_model' => 'Task', 'target_object_id' => $this->id)) as $notification) {
				$notification->delete();
			}

			return true;
		}
		return false;
	}

	public function changePercent($newPercent)
	{

		if ($this->percent != $newPercent) {
			$this->percent = $newPercent;
			$this->save();
		}

		if ($newPercent == 100) {
			$this->changeStatus(Gmftask::STATUS_FINISHED);
		}

		if ($this->percent != 100 && $this->status == Gmftask::STATUS_FINISHED) {
			$this->changeStatus(Gmftask::STATUS_OPEN);
		}

		return true;
	}

	public function changeStatus($newStatus)
	{
		$this->status = $newStatus;

		// Try to delete Old Finished Activity Activity
		$activity = Activity::model()->findByAttributes(array(
		'type' => 'GmftaskFinished',
		'module' => 'gmftasks',
		'object_model' => "Gmftask",
		'object_id' => $this->id
		));
		if ($activity) {
			$activity->delete();
		}

		if ($newStatus == Gmftask::STATUS_FINISHED) {

			// Fire Activity for that
			$activity = Activity::CreateForContent($this);
			$activity->type = "GmftaskFinished";
			$activity->module = "gmftasks";
			$activity->content->user_id = Yii::app()->user->id;
			$activity->save();
			$activity->fire();

			// Fire Notification to creator
			if ($this->created_by != Yii::app()->user->id) {
				$notification = new Notification();
				$notification->class = "GmftaskFinishedNotification";
				$notification->user_id = $this->created_by; // To Creator
				$notification->space_id = $this->content->space_id;
				$notification->source_object_model = 'Gmftask';
				$notification->source_object_id = $this->id;
				$notification->target_object_model = 'Gmftask';
				$notification->target_object_id = $this->id;
				$notification->save();
			}

			$this->percent = 100;
		} else {
			// Try to delete TaskFinishedNotification if exists
			foreach (Notification::model()->findAllByAttributes(array('class' => 'GmftaskFinishedNotification', 'target_object_model' => 'Gmftask', 'target_object_id' => $this->id)) as $notification) {
				$notification->delete();
			}
		}

		$this->save();

		return true;
	}

	public function hasDeadline()
	{
		if ($this->deadline != '0000-00-00 00:00:00' && $this->deadline != '' && $this->deadline != 'NULL') {
			return true;
		}
		return false;
	}

	public static function GetUsersOpenTasks()
	{

		$sql = " SELECT gmftask.* FROM gmftask_user " .
		" LEFT JOIN gmftask ON gmftask.id = gmftask_user.gmftask_id " .
		" WHERE gmftask_user.user_id=:userId AND gmftask.status=:status";

		$params = array();
		$params[':userId'] = Yii::app()->user->id;
		$params[':status'] = Gmftask::STATUS_OPEN;

		$gmftasks = Gmftask::model()->findAllBySql($sql, $params);

		return $gmftasks;
	}

	/**
	* Returns a title/text which identifies this IContent.
	*
	* e.g. Task: foo bar 123...
	*
	* @return String
	*/
	public function getContentTitle()
	{
		return "\"" . Helpers::truncateText($this->title, 25) . "\"";
	}

}

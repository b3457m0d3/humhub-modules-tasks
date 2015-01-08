<?php

class GmftaskController extends Controller {

    public $subLayout = "application.modules_core.space.views.space._layout";

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'users' => array('@'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Actions
     *
     * @return type
     */
    public function actions() {
        return array(
            'stream' => array(
                'class' => 'application.modules.gmftasks.GmftasksStreamAction',
                'mode' => 'normal',
            ),
        );
    }

    /**
     * Add mix-ins to this model
     *
     * @return type
     */
    public function behaviors() {
        return array(
            'SpaceControllerBehavior' => array(
                'class' => 'application.modules_core.space.behaviors.SpaceControllerBehavior',
            ),
        );
    }

    /**
     * Shows the Tasks tab
     */
    public function actionShow() {

        $workspace = $this->getSpace();
        $this->render('show', array('workspace' => $workspace));
    }

    /**
     * Posts a new tasks
     *
     * @return type
     */
    public function actionCreate() {

        $this->forcePostRequest();
        $_POST = Yii::app()->input->stripClean($_POST);

        $gmftask = new Gmftask();
        $gmftask->content->populateByForm();
        $gmftask->title = Yii::app()->request->getParam('title');
        $gmftask->max_users = Yii::app()->request->getParam('max_users',1);
        $gmftask->deadline = Yii::app()->request->getParam('deadline');
        $gmftask->preassignedUsers = Yii::app()->request->getParam('preassignedUsers');

        $gmftask->status = Gmftask::STATUS_OPEN;

        if ($gmftask->validate()) {
            $gmftask->save();
            $this->renderJson(array('wallEntryId' => $gmftask->content->getFirstWallEntryId()));
        } else {
            $this->renderJson(array('errors' => $gmftask->getErrors()), false);
        }
    }

    public function actionAssign() {

        $workspace = $this->getSpace();

        $gmftaskId = Yii::app()->request->getParam('gmftaskId');
        $gmftask = Gmftask::model()->findByPk($gmftaskId);

        if ($gmftask->content->canRead()) {
            $gmftask->assignUser();
            $this->printTask($gmftask);
        } else {
            throw new CHttpException(401, 'Could not access task!');
        }
        Yii::app()->end();
    }

    public function actionUnAssign() {

        $workspace = $this->getSpace();

        $gmftaskId = Yii::app()->request->getParam('gmftaskId');
        $gmftask = Task::model()->findByPk($gmftaskId);

        if ($gmftask->content->canRead()) {
            $gmftask->unassignUser();
            $this->printTask($gmftask);
        } else {
            throw new CHttpException(401, 'Could not access task!');
        }
        Yii::app()->end();
    }

    public function actionChangePercent() {

        $workspace = $this->getSpace();

        $gmftaskId = (int) Yii::app()->request->getParam('gmftaskId');
        $percent = (int) Yii::app()->request->getParam('percent');
        $gmftask = Task::model()->findByPk($gmftaskId);


        if ($gmftask->content->canRead()) {
            $gmftask->changePercent($percent);
            $this->printTask($gmftask);
        } else {
            throw new CHttpException(401, Yii::t('GmftasksModule.controllers_GmftaskController', 'Could not access task!'));
        }
        Yii::app()->end();
    }

    public function actionChangeStatus() {

        $space = $this->getSpace();

        $gmftaskId = (int) Yii::app()->request->getParam('gmftaskId');
        $status = (int) Yii::app()->request->getParam('status');
        $gmftask = GmfTask::model()->findByPk($gmftaskId);

        if ($gmftask->content->canRead()) {

            $gmftask->changeStatus($status);
            $this->printTask($gmftask);
        } else {
            throw new CHttpException(401, 'Could not access task!');
        }
        Yii::app()->end();
    }

    /**
     * Prints the given task wall output include the affected wall entry id
     *
     * @param Task $task
     */
    protected function printTask($task) {

        $output = $gmftask->getWallOut();
        Yii::app()->clientScript->render($output);

        $json = array();
        $json['output'] = $output;
        $json['wallEntryId'] = $gmftask->content->getFirstWallEntryId(); // there should be only one
        echo CJSON::encode($json);
        Yii::app()->end();
    }

}

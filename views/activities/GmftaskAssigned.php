<?php $this->beginContent('application.modules_core.activity.views.activityLayout', array('activity' => $activity)); ?>
<?php echo Yii::t('GmftasksModule.views_activities_GmftaskAssigned', '{userName} assigned to task {task}.', array(
    '{userName}' => '<strong>'. $user->displayName .'</strong>',
    '{task}' => '<strong>'. $target->getContentTitle() .'</strong>'
)); ?>
<?php $this->endContent(); ?>

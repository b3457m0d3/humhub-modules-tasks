<?php $this->beginContent('application.modules_core.notification.views.notificationLayout', array('notification' => $notification)); ?>
<?php echo Yii::t('GmftasksModule.views_notifications_gmftaskAssigned', '{userName} assigned you to the task {task}.', array(
    '{userName}' => '<strong>' . $creator->displayName . '</strong>',
    '{task}' => '<strong>' . $targetObject->getContentTitle() . '</strong>'
)); ?>
<?php $this->endContent(); ?>

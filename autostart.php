<?php

Yii::app()->moduleManager->register(array(
    'id' => 'gmftasks',
    'class' => 'application.modules.Gmftasks.TasksModule',
    'import' => array(
        'application.modules.gmftasks.*',
        'application.modules.gmftasks.models.*',
        'application.modules.gmftasks.notifications.*',
    ),
    // Events to Catch
    'events' => array(
        array('class' => 'SpaceMenuWidget', 'event' => 'onInit', 'callback' => array('GmftasksModule', 'onSpaceMenuInit')),
        array('class' => 'DashboardSidebarWidget', 'event' => 'onInit', 'callback' => array('GmftasksModule', 'onDashboardSidebarInit')),
        array('class' => 'User', 'event' => 'onBeforeDelete', 'callback' => array('GmftasksModule', 'onUserDelete')),
    ),
));
?>

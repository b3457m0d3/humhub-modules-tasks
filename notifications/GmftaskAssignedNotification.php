<?php

/**
 * TaskAssignedNotification is fired to the user which should handle this task.
 *
 * @author Luke
 */
class GmftaskAssignedNotification extends Notification {

    // Path to Web View of this Notification
    public $webView = "gmftasks.views.notifications.gmftaskAssigned";
    // Path to Mail Template for this notification
    public $mailView = "application.modules.gmftasks.views.notifications.gmftaskAssigned_mail";

}

?>

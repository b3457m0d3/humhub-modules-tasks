<?php

/**
 * TaskFinishedNotification is fired to the task creator after a task is finished.
 *
 * @author Luke
 */
class GmftaskFinishedNotification extends Notification {

    // Path to Web View of this Notification
    public $webView = "gmftasks.views.notifications.gmftaskFinished";
    // Path to Mail Template for this notification
    public $mailView = "application.modules.gmftasks.views.notifications.gmftaskFinished_mail";

}

?>

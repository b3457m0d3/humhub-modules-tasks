<?php

/**
 * TaskCreatedNotification is fired to the user which get a notification of creation.
 *
 * @author Luke
 */
class GmftaskCreatedNotification extends Notification {

    // Path to Web View of this Notification
    public $webView = "gmftasks.views.notifications.gmftaskCreated";
    // Path to Mail Template for this notification
    public $mailView = "application.modules.gmftasks.views.notifications.gmftaskCreated_mail";

}

?>

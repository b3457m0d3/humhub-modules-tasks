<?php
/**
 * This view represents a wall entry of a task.
 *
 * @property User $user the user which created this post
 * @property GfmTask $gfmtask the current task
 * @property Space $space the current space
 *
 * @package humhub.modules.gmftasks
 */
?>


<div class="panel panel-default">

    <div class="panel-body">
        <?php $this->beginContent('application.modules_core.wall.views.wallLayout', array('object' => $gmftask)); ?>


        <?php
        $assignedUsers = $gmftask->getAssignedUsers();
        $currentUserAssigned = false;

        // Check if current user is assigned to this task
        foreach ($assignedUsers as $au) {
            if ($au->id == Yii::app()->user->id) {
                $currentUserAssigned = true;
                break;
            }
        }
        ?>

        <div class="media gmftask" id="gmftask_<?php echo $gmftask->id; ?>">
            <?php if ($task->status == Gfmtask::STATUS_OPEN) : ?>
                <?php if ($currentUserAssigned || (count($assignedUsers) < $gmftask->max_users)) { ?>
                    <?php
                    echo HHtml::ajaxLink(
                            '<div class="gmftasks-check tt pull-left" style="margin-right: 0;" data-toggle="tooltip" data-placement="top" data-original-title="' . Yii::t("GmftasksModule.widgets_views_entry", "Click, to finish this task") . '"><i class="fa fa-square-o"> </i></div>', CHtml::normalizeUrl(array('/gmftasks/gmftask/changeStatus', 'guid' => $space->guid, 'gmftaskId' => $gmftask->id, 'status' => Gmftask::STATUS_FINISHED)), array(
                        'dataType' => "json",
                        'success' => "function(json) {  $('#wallEntry_'+json.wallEntryId).html(parseHtml(json.output)); $('#gmftask_" . $gmftask->id . " .gmftask-title').addClass('gmftask-completed'); $('#gmftask_" . $gmftask->id . " .label').css('opacity', '0.3'); $('#gmftask_" . $gmftask->id . " .gmftasks-check .fa').removeClass('fa-square-o'); $('#gmftask_" . $gmftask->id . " .gmftasks-check .fa').addClass('fa-check-square-o'); $('.panel-mytasks #gmftask_" . $gmftask->id . "').delay(500).fadeOut('slow');}",
                    ), array('id' => "GmfTaskFinishLink_" . $gmftask->id)
                    );
                    ?>
                <?php } else { ?>
                    <div class="gmftasks-check disabled tt pull-left" style="margin-right: 0;" data-toggle="tooltip" data-placement="top"
                         data-original-title="<?php echo Yii::t("GmftasksModule.widgets_views_gmfentry", "You're not assigned to this task"); ?>">
                        <i
                            class="fa fa-square-o"> </i></div>
                    <?php } ?>
                <?php elseif ($task->status == Gmftask::STATUS_FINISHED) : ?>
                    <?php if ($currentUserAssigned || (count($assignedUsers) < $gmftask->max_users)) { ?>
                        <?php
                        echo HHtml::ajaxLink(
                                '<div class="gmftasks-check tt pull-left" style="margin-right: 0;" data-toggle="tooltip" data-placement="top" data-original-title="' . Yii::t("GmftasksModule.widgets_views_gmfentry", "This task is already done. Click to reopen.") . '"><i class="fa fa-check-square-o"> </i></div>', CHtml::normalizeUrl(array('/gmftasks/gmftask/changeStatus', 'guid' => $space->guid, 'gmftaskId' => $gmftask->id, 'status' => Gmftask::STATUS_OPEN)), array(
                            'dataType' => "json",
                            'success' => "function(json) {  $('#wallEntry_'+json.wallEntryId).html(parseHtml(json.output));}",
                        ), array('id' => "GmfTaskOpenLink_" . $gmftask->id)
                        );
                        ?>
                    <?php } else { ?>
                    <div class="gmftasks-check disabled tt pull-left" style="margin-right: 0;" data-toggle="tooltip" data-placement="top"
                         data-original-title="<?php echo Yii::t("GmftasksModule.widgets_views_entry", "This task is already done"); ?>">
                        <i
                            class="fa fa-check-square-o"> </i></div>
                    <?php } ?>

            <?php endif; ?>
            <div class="media-body">
                <span class="gmftask-title <?php if ($task->status == Gmftask::STATUS_FINISHED): ?>gmftask-completed<?php endif; ?>pull-left"><?php echo $gmftask->title; ?></span>
                <small>
                    <!-- Show deadline -->

                    <?php if ($gmftask->hasDeadline()) : ?>
                        <?php
                        $timestamp = strtotime($gmftask->deadline);
                        $class = "label label-default";

                        if (date("d.m.yy", $timestamp) <= date("d.m.yy", time())) {
                            $class = "label label-danger";
                        }
                        ?>
                        <span class="<?php echo $class; ?>"
                              style="<?php if ($task->status == Gmftask::STATUS_FINISHED): ?>opacity: 0.3;<?php endif; ?>"><?php echo date("d. M", $timestamp); ?></span>
                          <?php endif; ?>

                </small>

                <div class="user pull-right" style="display: inline;">
                    <!-- Show assigned user -->
                    <?php if (count($assignedUsers) != 0) : ?>
                        <?php foreach ($assignedUsers as $user): ?>
                            <a href="<?php echo $user->getProfileUrl(); ?>" id="user_<?php echo $gmftask->id; ?>">
                                <img src="<?php echo $user->getProfileImage()->getUrl(); ?>" class="img-rounded tt"
                                     height="24" width="24" alt="24x24" data-src="holder.js/24x24"
                                     style="width: 24px; height: 24px;" data-toggle="tooltip" data-placement="top"
                                     title=""
                                     data-original-title="<strong><?php echo $user->displayName; ?></strong><br><?php echo $user->profile->title; ?>">
                            </a>

                        <?php endforeach; ?>
                    <?php endif; ?>

                </div>
                <div class="clearfix"></div>

            </div>
        </div>


        <?php $this->endContent(); ?>
    </div>
</div>

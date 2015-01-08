<?php if (count($gmftasks) > 0) : ?>
    <div class="panel panel-default panel-mytasks" style="display:none;">
        <div
            class="panel-heading"><?php echo Yii::t('GfmtasksModule.widgets_views_mytasks', '<strong>My</strong> tasks'); ?></div>
        <div class="panel-body">
            <?php foreach ($gmftasks as $gmftask): ?>

                <div class="media gmftask" id="gmftask_<?php echo $gmftask->id; ?>">
                    <?php

                    echo HHtml::ajaxLink(
                        '<div class="gmftasks-check tt pull-left" data-toggle="tooltip" data-placement="top" data-original-title="' . Yii::t("GmftasksModule.widgets_views_gmfentry", "Click, to finish this task") . '"><i class="fa fa-square-o"> </i></div>', CHtml::normalizeUrl(array('/gmftasks/gmftask/changeStatus', 'gmftaskId' => $gmftask->id, 'status' => Gmftask::STATUS_FINISHED)), array(
                            'dataType' => "json",
                            'success' => "function(json) {  $('#wallEntry_'+json.wallEntryId).html(parseHtml(json.output)); $('#gmftask_" . $gmftask->id . " .gmftask-title').addClass('gmftask-completed'); $('#gmftask_" . $gmftask->id . " .label').css('opacity', '0.3'); $('#gmftask_" . $gmftask->id . " .gmftasks-check .fa').removeClass('fa-square-o'); $('#gmftask_" . $gmftask->id . " .gmftasks-check .fa').addClass('fa-check-square-o');}",
                        ), array('id' => "GmfTaskFinishLink_" . $gmftask->id)
                    );
                    ?>
                    <div class="media-body">
                        <span class="gmftask-title pull-left"><?php echo $gmftask->title; ?></span>
                        <small >
                            <!-- Show deadline -->

                            <?php if ($gmftask->deadline != '0000-00-00 00:00:00') : ?>
                                <?php
                                $timestamp = strtotime($gmftask->deadline);
                                $class = "label label-default";

                                if (date("d.m.yy", $timestamp) <= date("d.m.yy", time())) {
                                    $class = "label label-danger";
                                }
                                ?>
                                <span class="<?php echo $class; ?>"
                                      style="<?php if ($gmftask->status == Gmftask::STATUS_FINISHED): ?>opacity: 0.3;<?php endif; ?>"><?php echo date("d. M", $timestamp); ?></span>
                            <?php endif; ?>

                        </small>

                        <div class="user pull-right" style="display: inline;">
                            <!-- Show space  -->
                            <a href="<?php echo $gmftask->content->container->getUrl(); ?>">
                                <img src="<?php echo $gmftask->content->container->getProfileImage()->getUrl(); ?>"
                                     class="img-rounded tt"
                                     height="24" width="24" alt="24x24" data-src="holder.js/24x24"
                                     style="width: 24px; height: 24px;" data-toggle="tooltip" data-placement="top"
                                     title=""
                                     data-original-title="<?php echo Yii::t('GmftasksModule.widgets_views_mytasks', 'From space: ')?><br><strong><?php echo $gmftask->content->container->name; ?></strong>">
                            </a>

                        </div>

                        <div class="clearfix"></div>

                    </div>
                </div>


            <?php endforeach; ?>

        </div>
    </div>
<?php endif; ?>

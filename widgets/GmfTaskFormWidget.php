<?php

/**
 * GmfTaskFormWidget handles the form to create new Lists.
 *
 * @package humhub.modules.gmftasks.widgets
 * @author Luke/b3457m0d3
 */
class GmfTaskFormWidget extends ContentFormWidget {

    public function renderForm() {

        $this->submitUrl = 'gmftasks/gmftask/create';
        $this->submitButtonText = Yii::t('GmftasksModule.widgets_GmfTaskFormWidget', 'Create');

        $this->form = $this->render('gmftaskForm', array('contentContainer'=>$this->contentContainer), true);
    }

}

?>

<?php $this->renderFile('protected/views/layouts/header_subpanel.php', array('title'=>'Control Panel')); ?>


<h1><span appapptagid="30"></span> Homepage</h1>
<br/>
<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
    'htmlOptions' => array('enctype'=>'multipart/form-data', 'class'=>'noajax')
)); ?>
<textarea class="ckeditor" name="homepage"><?php echo $model->homepage; ?></textarea>
<?php echo CHtml::submitButton('Submit'); ?>
<?php $this->endWidget(); ?>
</div><!-- form -->

<script type="text/javascript" src="../assets/ckeditor/ckeditor.js"></script>


<?php $this->renderFile('protected/views/layouts/footer_subpanel.php'); ?>

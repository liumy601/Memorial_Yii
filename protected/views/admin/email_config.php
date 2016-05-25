<?php $this->renderFile('protected/views/layouts/header_subpanel.php', array('title'=>'Control Panel')); ?>


<?php
$this->pageTitle = 'Email Configuration';
?>

<h1><span appapptagid="34"></span> <?php echo $this->pageTitle; ?></h1>

<?php if(Yii::app()->user->hasFlash('email_config')): ?>

<div class="flash-success">
	<?php echo Yii::app()->user->getFlash('email_config'); ?>
</div>

<?php else: ?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
    'htmlOptions' => array(
//      'class' => 'noajax',
//      'enctype'=>'multipart/form-data',//can't upload by ajax
//      'target' => 'hiddenifm'
    )
)); ?>

	<?php echo $form->errorSummary($model); ?>

  <table border="0">
    <tr>
      <td class="label"><?php echo $form->labelEx($model,'from_name'); ?></td>
      <td class="element"><?php echo $form->textField($model,'from_name', array('style'=>"width:250px;")); ?></td>
    </tr>
    <tr>
      <td class="label"><?php echo $form->labelEx($model,'from_address'); ?></td>
      <td class="element"><?php echo $form->textField($model,'from_address', array('style'=>"width:250px;")); ?></td>
    </tr>
    <tr>
      <td class="label"><?php echo $form->labelEx($model,'send_type'); ?></td>
      <td class="element"><?php echo $form->dropDownList($model,'send_type', array('sendmail'=>'sendmail', 'SMTP'=>'SMTP'), array('onchange'=>'changeMailTransferAgent();')); ?></td>
    </tr>
  </table>
  
  <fieldset id="smtp_config" style="width:700px;display:none;">
    <legend>SMTP</legend>
    <table border="0">
      <tr>
        <td class="label"><?php echo $form->labelEx($model,'smtp_server'); ?></td>
        <td class="element"><?php echo $form->textField($model,'smtp_server'); ?></td>
        <td class="label"><?php echo $form->labelEx($model,'smtp_port'); ?></td>
        <td class="element"><?php echo $form->textField($model,'smtp_port'); ?></td>
      </tr>
      <tr>
        <td class="label"><?php echo $form->labelEx($model,'smtp_auth'); ?></td>
        <td class="element"><?php echo $form->checkBox($model,'smtp_auth'); ?></td>
        <td class="label"><?php echo $form->labelEx($model,'smtp_ssl'); ?></td>
        <td class="element"><?php echo $form->checkBox($model,'smtp_ssl'); ?></td>
      </tr>
      <tr>
        <td class="label"><?php echo $form->labelEx($model,'smtp_user'); ?></td>
        <td class="element"><?php echo $form->textField($model,'smtp_user'); ?></td>
        <td class="label"><?php echo $form->labelEx($model,'smtp_pass'); ?></td>
        <td class="element"><?php echo $form->textField($model,'smtp_pass'); ?></td>
      </tr>
    </table>
  </fieldset>
  
  <table border="0">
    <tr>
      <td class="label"> </td>
      <td class="element"><?php echo CHtml::submitButton('Save'); ?></td>
    </tr>
  </table>	
  
<?php $this->endWidget(); ?>

</div><!-- form -->

<?php endif; ?>



<script type="text/javascript">
  function changeMailTransferAgent(){
    if ($('#EmailConfig_send_type').val() == 'SMTP') {
      $('#smtp_config').show();
    } else {
      $('#smtp_config').hide();
    }
  }
  
  changeMailTransferAgent();
</script>

<?php $this->renderFile('protected/views/layouts/footer_subpanel.php'); ?>


<?php $this->renderFile('protected/views/layouts/header_subpanel.php', array('title'=>'Control Panel')); ?>

<h1><span appapptagid="34"></span>Optional Fields</h1>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'optional-fields-form',
	'enableAjaxValidation'=>false,
)); ?>

<?php echo $form->errorSummary($model); ?>

<table border="0">
    <tr>
      <td class="label"><?php echo $form->labelEx($model,'full_legal_name_f'); ?></td>
      <td class="element"><?php echo $form->checkBox($model,'full_legal_name_f'); ?></td>
    </tr>
	<tr>
      <td class="label"><?php echo $form->labelEx($model,'full_legal_name_m'); ?></td>
      <td class="element"><?php echo $form->checkBox($model,'full_legal_name_m'); ?></td>
    </tr>
	<tr>
      <td class="label"><?php echo $form->labelEx($model,'full_legal_name_l'); ?></td>
      <td class="element"><?php echo $form->checkBox($model,'full_legal_name_l'); ?></td>
    </tr>
	<tr>
      <td class="label"><?php echo $form->labelEx($model,'full_legal_prefix'); ?></td>
      <td class="element"><?php echo $form->checkBox($model,'full_legal_prefix'); ?></td>
    </tr>
	<tr>
      <td class="label"><?php echo $form->labelEx($model,'city_of_birth'); ?></td>
      <td class="element"><?php echo $form->checkBox($model,'city_of_birth'); ?></td>
    </tr>
	<tr>
      <td class="label"><?php echo $form->labelEx($model,'state_of_birth'); ?></td>
      <td class="element"><?php echo $form->checkBox($model,'state_of_birth'); ?></td>
    </tr>
	<tr>
      <td class="label"><?php echo $form->labelEx($model,'pod_facility_name'); ?></td>
      <td class="element"><?php echo $form->checkBox($model,'pod_facility_name'); ?></td>
    </tr>
	<tr>
      <td class="label"><?php echo $form->labelEx($model,'pod_facility_street'); ?></td>
      <td class="element"><?php echo $form->checkBox($model,'pod_facility_street'); ?></td>
    </tr>
	<tr>
      <td class="label"><?php echo $form->labelEx($model,'pod_facility_city'); ?></td>
      <td class="element"><?php echo $form->checkBox($model,'pod_facility_city'); ?></td>
    </tr>
	<tr>
      <td class="label"><?php echo $form->labelEx($model,'pod_facility_state'); ?></td>
      <td class="element"><?php echo $form->checkBox($model,'pod_facility_state'); ?></td>
    </tr>
	<tr>
      <td class="label"><?php echo $form->labelEx($model,'pod_facility_zip'); ?></td>
      <td class="element"><?php echo $form->checkBox($model,'pod_facility_zip'); ?></td>
    </tr>
	<tr>
      <td class="label"><?php echo $form->labelEx($model,'interment_street'); ?></td>
      <td class="element"><?php echo $form->checkBox($model,'interment_street'); ?></td>
    </tr>
	<tr>
      <td class="label"><?php echo $form->labelEx($model,'interment_zip'); ?></td>
      <td class="element"><?php echo $form->checkBox($model,'interment_zip'); ?></td>
    </tr>
	<tr>
      <td class="label"><?php echo $form->labelEx($model,'veteran_serial_number'); ?></td>
      <td class="element"><?php echo $form->checkBox($model,'veteran_serial_number'); ?></td>
    </tr>
	<tr>
      <td class="label"><?php echo $form->labelEx($model,'doctor_street'); ?></td>
      <td class="element"><?php echo $form->checkBox($model,'doctor_street'); ?></td>
    </tr>
	<tr>
      <td class="label"><?php echo $form->labelEx($model,'doctor_city'); ?></td>
      <td class="element"><?php echo $form->checkBox($model,'doctor_city'); ?></td>
    </tr>
	<tr>
      <td class="label"><?php echo $form->labelEx($model,'doctor_state'); ?></td>
      <td class="element"><?php echo $form->checkBox($model,'doctor_state'); ?></td>
    </tr>
	<tr>
      <td class="label"><?php echo $form->labelEx($model,'doctor_zip'); ?></td>
      <td class="element"><?php echo $form->checkBox($model,'doctor_zip'); ?></td>
    </tr>
</table>

<table border="0">
    <tr>
      <td class="label"> </td>
      <td class="element"><?php echo CHtml::submitButton('Save', array('class'=>'btn')); ?></td>
    </tr>
 </table>

<?php $this->endWidget(); ?>

</div><!-- form -->
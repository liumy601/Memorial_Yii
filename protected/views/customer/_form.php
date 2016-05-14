<div id="rwb" class="rwb">
  
<?php
if ($model->id > 0) {
  $title = 'Update Decedent';
} else {
  $title = 'Create Decedent';
}

?>
       

<?php
$this->pageTitle = Yii::app()->name . ' - ' . $title;
?>

<?php if(Yii::app()->user->hasFlash('msg')): ?>

<div class="flash-success">
	<?php echo Yii::app()->user->getFlash('msg'); ?>
</div>

<?php else: ?>


<?php $form=$this->beginWidget('CActiveForm', array('htmlOptions' => 
    array('name'=>'EditView', 'id'=>'EditView', 
        'class'=>'noajax',
		'enctype'=>'multipart/form-data',
        'onsubmit'=>'return check_form_customer();'
    ))); ?>

	<?php echo $form->errorSummary($model); ?>

   
  <table width="100%" border="0" cellspacing="0" cellpadding="0"><tbody><tr><td class="Leads-titleIcon">&nbsp;</td> 
  <td height="30" class="title hline" style="padding-left:10px"> <?php echo $title; ?> </td></tr></tbody></table>
  <p></p>
  <div class="bodyText mandatory"><div style="float:right;" class="bodyText mandatory">* Required Field(s)</div></div>

  <table width="95%" border="0" cellpadding="0" cellspacing="0"><tbody><tr><td align="center">
    <input title="Save" class="button" onclick="" type="submit" name="button" value="Save"/>
    <input title="Cancel" class="button buttonurl" url="/customer" pageTitle="Customer" type="button" name="button" value="Cancel"/>
  </td></tr></tbody></table>

  <div id="Information">
    <table id="secHeadLead Information" width="95%" cellspacing="0" cellpadding="0"><tbody width="100%"><tr><td class="secHead">Information</td></tr></tbody></table>
    <table width="100%" border="0" cellspacing="1" cellpadding="0" class="tabForm">
      <tbody>
        <tr>
        <td width="25%" class="label"><?php echo $form->labelEx($model,'deceased_photo', array('style'=>'width:270px;')); ?></td>
        <td width="25%" class="element">
          <?php if(!empty($model->deceased_photo)){ ?>
          <a href="/<?php echo CHtml::encode($model->deceased_photo); ?>" target="_blank"><img src="<?php echo CHtml::encode($model->deceased_photo); ?>" width="40" height="20"></a>
          <?php } ?>
          <?php echo $form->fileField($model,'deceased_photo',array()); ?>
        </td>
        </tr>
        
        <tr>
        <td width="25%" class="label"><?php echo $form->labelEx($model,'case_number', array('style'=>'width:270px;')); ?></td>
        <td width="25%" class="element"><?php echo $form->textField($model,'case_number',array('maxlength'=>50, 'style'=>'width:270px;')); ?></td>
        <td width="25%" class="label"><?php echo $form->labelEx($model,'status'); ?></td>
        <td width="25%" class="element">
          <?php
            $oldStatus = DropDown::getSelfDefineOptions('customer', 'status'); 
            $newStatus = DropDown::getNoNullValueArray($oldStatus);   
            echo $form->dropDownList($model,'status', $newStatus, array('style'=>'width:270px;','class'=>'Other'));
          ?>
        </td>
        </tr>
        
        <tr>
        <td width="25%" class="label"><?php echo $form->labelEx($model,'skfh_funeral_home', array('style'=>'width:270px;')); ?></td>
        <td width="25%" class="element"><?php echo $form->dropDownList($model,'skfh_funeral_home', DropDown::getSelfDefineOptions('customer', 'skfhfuneralhome'), array('style'=>'width:270px;','class'=>'Other')); ?></td>
        <td width="25%" class="label"><?php echo $form->labelEx($model,'full_legal_name', array('style'=>'width:270px;color:red;')); ?></td>
        <!--<td width="25%" class="label mandatory">Full Legal Name</td>-->
        <td width="25%" class="element">
          <?php echo $form->textField($model,'full_legal_name',array('maxlength'=>50, 'style'=>'width:270px;', 'onblur'=>'return getValue();')); ?>
          <br/>
          <span class="warning" style="display: none; color: #f00;">Warning: This field must be filled in and it's not blank!!!</span>
        </td>
        </tr>
		
		<?php if($optionFields->full_legal_name_f) : ?>
			<tr>
			<td width="25%" class="label"> </td>
			<td width="25%" class="element"> </td>
			<td width="25%" class="label"><?php echo $form->labelEx($model,'full_legal_name_f',array('style'=>'width:270px;')); ?></td>
			<td width="25%" class="element"><?php echo $form->textField($model,'full_legal_name_f',array('maxlength'=>50, 'style'=>'width:270px;')); ?></td>
			</tr>
		<?php endif; ?>

		<?php if($optionFields->full_legal_name_m) : ?>
			<tr>
			<td width="25%" class="label"> </td>
			<td width="25%" class="element"> </td>
			<td width="25%" class="label"><?php echo $form->labelEx($model,'full_legal_name_m',array('style'=>'width:270px;')); ?></td>
			<td width="25%" class="element"><?php echo $form->textField($model,'full_legal_name_m',array('maxlength'=>50, 'style'=>'width:270px;'));?></td>
			</tr>
		<?php endif; ?>

		<?php if($optionFields->full_legal_name_l) : ?>
			<tr>
			<td width="25%" class="label"> </td>
			<td width="25%" class="element"> </td>
			<td width="25%" class="label"><?php echo $form->labelEx($model,'full_legal_name_l',array('style'=>'width:270px;')); ?></td>
			<td width="25%" class="element"><?php echo $form->textField($model,'full_legal_name_l',array('maxlength'=>50, 'style'=>'width:270px;')); ?></td>
			</tr>
		<?php endif; ?>

		<?php if($optionFields->full_legal_prefix) : ?>
			<tr>
			<td width="25%" class="label"> </td>
			<td width="25%" class="element"> </td>
			<td width="25%" class="label"><?php echo $form->labelEx($model,'full_legal_prefix',array('style'=>'width:270px;')); ?></td>
			<td width="25%" class="element"><?php echo $form->textField($model,'full_legal_prefix',array('maxlength'=>50, 'style'=>'width:270px;'));?></td>
			</tr>
		<?php endif; ?>

        <tr>
        <td width="25%" class="label"><?php echo $form->labelEx($model,'name_for_obituary',array('style'=>'width:270px;')); ?></td>
        <td width="25%" class="element"><?php echo $form->textField($model,'name_for_obituary',array('maxlength'=>50, 'style'=>'width:270px;')); ?></td>
        <td width="25%" class="label"><?php echo $form->labelEx($model,'age',array('style'=>'width:270px;')); ?></td>
        <td width="25%" class="element" id="age_value"><?php echo $form->textField($model,'age',array('maxlength'=>50, 'style'=>'width:270px;'));?></td>
        </tr>
        
        <tr>
        <td width="25%" class="label"><?php echo $form->labelEx($model,'address'); ?></td>
        <td width="25%" class="element"><?php echo $form->textField($model,'address',array('maxlength'=>50, 'style'=>'width:270px;')); ?></td>
        <td width="25%" class="label"><?php echo $form->labelEx($model,'sex',array('style'=>'width:270px;')); ?></td>
        <td width="25%" class="element"><?php echo $form->textField($model,'sex',array('maxlength'=>50, 'style'=>'width:270px;')); ?></td>
        </tr>
        
        <tr>
        <td width="25%" class="label"><?php echo $form->labelEx($model,'state'); ?></td>
        <td width="25%" class="element"><?php echo $form->textField($model,'state',array('maxlength'=>50, 'style'=>'width:270px;')); ?></td>
        <td width="25%" class="label"><?php echo $form->labelEx($model,'zip'); ?></td>
        <td width="25%" class="element"><?php echo $form->textField($model,'zip',array('maxlength'=>50, 'style'=>'width:270px;')); ?></td>
        </tr>
        
        <tr>
        <td width="25%" class="label"><?php echo $form->labelEx($model,'formerly_of'); ?></td>
        <td width="25%" class="element"><?php echo $form->textField($model,'formerly_of',array('maxlength'=>50, 'style'=>'width:270px;')); ?></td>
        <td width="25%" class="label"><?php echo $form->labelEx($model,'city'); ?></td>
        <td width="25%" class="element"><?php echo $form->textField($model,'city',array('maxlength'=>50, 'style'=>'width:270px;')); ?></td>
        </tr>
        
        <tr>
        <td width="25%" class="label"><?php echo $form->labelEx($model,'date_of_birth'); ?></td>
        <td width="25%" class="element">
          <?php echo $form->textField($model,'date_of_birth',array('style'=>'width:270px;','maxlength'=>50, 'class'=>'datepicker')); ?>
          <?php // echo $form->textField($model,'date_of_birth',array('style'=>'width:125px;','maxlength'=>50, 'class'=>'datepicker')); ?>
          <?php // echo $form->dropdownlist($model,'time_of_birth_h',  CommonFunc::hours()); ?>
          <?php // echo $form->dropdownlist($model,'time_of_birth_m',  CommonFunc::minutes()); ?>
          <?php // echo $form->dropdownlist($model,'time_of_birth_z',  CommonFunc::noon()); ?>
          <?php // echo $form->dropdownlist($model,'zone_of_birth',  CommonFunc::zones()); ?>
        </td>
        <td width="25%" class="label"><?php echo $form->labelEx($model,'place_of_birth'); ?></td>
        <td width="25%" class="element"><?php echo $form->textField($model,'place_of_birth',array('style'=>'width:270px;','maxlength'=>50, 'onchange'=>'calAge();')); ?></td>
        </tr>

		<?php if($optionFields->city_of_birth) : ?>
			<tr>
			<td width="25%" class="label"> </td>
			<td width="25%" class="element"> </td>
			<td width="25%" class="label"><?php echo $form->labelEx($model,'city_of_birth',array('style'=>'width:270px;')); ?></td>
			<td width="25%" class="element"><?php echo $form->textField($model,'city_of_birth',array('maxlength'=>50, 'style'=>'width:270px;')); ?></td>
			</tr>
		<?php endif; ?>

		<?php if($optionFields->state_of_birth) : ?>
			<tr>
			<td width="25%" class="label"> </td>
			<td width="25%" class="element"> </td>
			<td width="25%" class="label"><?php echo $form->labelEx($model,'state_of_birth',array('style'=>'width:270px;')); ?></td>
			<td width="25%" class="element"><?php echo $form->textField($model,'state_of_birth',array('maxlength'=>50, 'style'=>'width:270px;'));?></td>
			</tr>
		<?php endif; ?>

        <tr>
        <td width="25%" class="label"><?php echo $form->labelEx($model,'date_of_death'); ?></td>
        <td width="25%" class="element">
          <?php echo $form->textField($model,'date_of_death',array('style'=>'width:125px;','maxlength'=>50, 'onchange'=>'calAge();', 'class'=>'datepicker')); ?>
          <?php echo $form->dropdownlist($model,'time_of_death_h', CommonFunc::hours()); ?>
          <?php // echo $form->dropdownlist($model,'time_of_death_h',  CommonFunc::hours()); ?>
          <?php echo $form->dropdownlist($model,'time_of_death_m',  CommonFunc::minutes()); ?>
          <?php echo $form->dropdownlist($model,'time_of_death_z',  CommonFunc::noon()); ?>
          <?php // echo $form->dropdownlist($model,'zone_of_death',  CommonFunc::zones()); ?>
        </td>
        <td width="25%" class="label"><?php echo $form->labelEx($model,'place_of_death'); ?></td>
        <td width="25%" class="element"><?php echo $form->textField($model,'place_of_death',array('maxlength'=>50, 'style'=>'width:270px;')); ?></td>
        </tr>

		<?php if($optionFields->pod_facility_name) : ?>
			<tr>
			<td width="25%" class="label"> </td>
			<td width="25%" class="element"> </td>
			<td width="25%" class="label"><?php echo $form->labelEx($model,'pod_facility_name',array('style'=>'width:270px;')); ?></td>
			<td width="25%" class="element"><?php echo $form->textField($model,'pod_facility_name',array('maxlength'=>50, 'style'=>'width:270px;')); ?></td>
			</tr>
		<?php endif; ?>

		<?php if($optionFields->pod_facility_street) : ?>
			<tr>
			<td width="25%" class="label"> </td>
			<td width="25%" class="element"> </td>
			<td width="25%" class="label"><?php echo $form->labelEx($model,'pod_facility_street',array('style'=>'width:270px;')); ?></td>
			<td width="25%" class="element"><?php echo $form->textField($model,'pod_facility_street',array('maxlength'=>50, 'style'=>'width:270px;'));?></td>
			</tr>
		<?php endif; ?>
        
		<?php if($optionFields->pod_facility_city) : ?>
			<tr>
			<td width="25%" class="label"> </td>
			<td width="25%" class="element"> </td>
			<td width="25%" class="label"><?php echo $form->labelEx($model,'pod_facility_city',array('style'=>'width:270px;')); ?></td>
			<td width="25%" class="element"><?php echo $form->textField($model,'pod_facility_city',array('maxlength'=>50, 'style'=>'width:270px;')); ?></td>
			</tr>
		<?php endif; ?>

		<?php if($optionFields->pod_facility_state) : ?>
			<tr>
			<td width="25%" class="label"> </td>
			<td width="25%" class="element"> </td>
			<td width="25%" class="label"><?php echo $form->labelEx($model,'pod_facility_state',array('style'=>'width:270px;')); ?></td>
			<td width="25%" class="element"><?php echo $form->textField($model,'pod_facility_state',array('maxlength'=>50, 'style'=>'width:270px;'));?></td>
			</tr>
		<?php endif; ?>

		<?php if($optionFields->pod_facility_zip) : ?>
			<tr>
			<td width="25%" class="label"> </td>
			<td width="25%" class="element"> </td>
			<td width="25%" class="label"><?php echo $form->labelEx($model,'pod_facility_zip',array('style'=>'width:270px;')); ?></td>
			<td width="25%" class="element"><?php echo $form->textField($model,'pod_facility_zip',array('maxlength'=>50, 'style'=>'width:270px;')); ?></td>
			</tr>
		<?php endif; ?>

        <tr></tr><tr></tr><tr></tr><tr></tr><tr></tr>
        <tr>
        <td align="center"><b style="font-size: 20px;">Funeral Services</b><hr></td>
        <td colspan="3"></td>
        </tr>
        
        
        <tr>
        <td width="25%" class="label"><?php echo $form->labelEx($model,'officiant'); ?></td>
        <td width="25%" class="element"><?php echo $form->textField($model,'officiant',array('maxlength'=>50, 'style'=>'width:270px;')); ?></td>
        <td width="25%" class="label"><?php echo $form->labelEx($model,'clergy_full_name2',array('style'=>'width:270px;')); ?></td>
        <td width="25%" class="element"><?php echo $form->textField($model,'clergy_full_name2',array('maxlength'=>50, 'style'=>'width:270px;')); ?></td>
        </tr>
        
        <tr>
        <td width="25%" class="label"><?php echo $form->labelEx($model,'funeral_service_date'); ?></td>
        <td width="25%" class="element">
          <?php echo $form->textField($model,'funeral_service_date',array('style'=>'width:125px;','maxlength'=>50, 'class'=>'datepicker')); ?>
          <?php echo $form->dropdownlist($model,'funeral_service_time_h',  CommonFunc::hours()); ?>
          <?php echo $form->dropdownlist($model,'funeral_service_time_m',  CommonFunc::minutes()); ?>
          <?php echo $form->dropdownlist($model,'funeral_service_time_z',  CommonFunc::noon()); ?>
        </td>
        <td width="25%" class="label"><?php echo $form->labelEx($model,'location_of_funeral_service'); ?></td>
        <td width="25%" class="element"><?php echo $form->dropDownList($model,'location_of_funeral_service', DropDown::getSelfDefineOptions('customer', 'locationfuneralservice'), array('style'=>'width:270px;','class'=>'Other','onchange'=>'return showInput1(this)'));?></td>
        </tr>
        
        <tr style="display:none;" id="location_of_funeral_service_other">
        <td width="25%" class="label"></td>
        <td width="25%" class="element"></td>
        <td width="25%" class="label"></td>
        <td width="25%" class="element" id=""><?php echo $form->textField($model,'location_of_funeral_service_other', array('style'=>'width:270px;','class'=>'Other')); ?></td>
        </tr>
        
        <tr>
        <td width="25%" class="label"><?php echo $form->labelEx($model,'date_of_visitation_start'); ?></td>
        <td width="25%" class="element">
          <?php echo $form->textField($model,'date_of_visitation_start',array('style'=>'width:125px;','maxlength'=>50, 'class'=>'datepicker')); ?>
          <?php echo $form->dropdownlist($model,'visitation_time_h_start',  CommonFunc::hours()); ?>
          <?php echo $form->dropdownlist($model,'visitation_time_m_start',  CommonFunc::minutes()); ?>
          <?php echo $form->dropdownlist($model,'visitation_time_z_start',  CommonFunc::noon()); ?>
        </td>
        <td width="25%" class="label"><?php echo $form->labelEx($model,'location_of_visitation'); ?></td>
        <td width="25%" class="element"><?php echo $form->dropDownList($model,'location_of_visitation', DropDown::getSelfDefineOptions('customer', 'locationvisitation'), array('style'=>'width:270px;','class'=>'Other', 'onchange'=>'showInput2(this)')); ?></td>
        </tr>
        
        <tr style="display:none;" id="location_of_visitation_other">
        <td width="25%" class="label"></td>
        <td width="25%" class="element"></td>
        <td width="25%" class="label"></td>
        <td width="25%" class="element" id=""><?php echo $form->textField($model,'location_of_visitation_other', array('style'=>'width:270px;','class'=>'Other')); ?></td>
        </tr>
        
        <tr>
        <td width="25%" class="label"><?php echo $form->labelEx($model,'disposition_type'); ?></td>
        <td width="25%" class="element">
          <?php echo $form->textField($model,'disposition_type',array('style'=>'width:270px;','maxlength'=>50)); ?>
        </td>
        <td width="25%" class="label"><?php echo $form->labelEx($model,'date_of_visitation_end'); ?></td>
        <td width="25%" class="element">
          <?php echo $form->textField($model,'date_of_visitation_end',array('style'=>'width:125px;','maxlength'=>50, 'class'=>'datepicker')); ?>
          <?php echo $form->dropdownlist($model,'visitation_time_h_end',  CommonFunc::hours()); ?>
          <?php echo $form->dropdownlist($model,'visitation_time_m_end',  CommonFunc::minutes()); ?>
          <?php echo $form->dropdownlist($model,'visitation_time_z_end',  CommonFunc::noon()); ?>
        </td>
        </tr>
       
        <tr>
        <td width="25%" class="label"><?php echo $form->labelEx($model,'disposition_date'); ?></td>
        <td width="25%" class="element">
          <?php echo $form->textField($model,'disposition_date',array('style'=>'width:270px;','maxlength'=>50, 'class'=>'datepicker')); ?>
        </td>
        <td width="25%" class="label"><?php echo $form->labelEx($model,'disposition_place'); ?></td>
        <td width="25%" class="element">
          <?php echo $form->textField($model,'disposition_place',array('style'=>'width:270px;','maxlength'=>50)); ?>
        </td>
        </tr>
        
        <tr>
        <td width="25%" class="label"><?php echo $form->labelEx($model,'date_of_burial'); ?></td>
        <td width="25%" class="element">
          <?php echo $form->textField($model,'date_of_burial',array('style'=>'width:125px;','maxlength'=>50, 'class'=>'datepicker')); ?>
          <?php echo $form->dropdownlist($model,'time_of_burial_h',  CommonFunc::hours()); ?>
          <?php echo $form->dropdownlist($model,'time_of_burial_m',  CommonFunc::minutes()); ?>
          <?php echo $form->dropdownlist($model,'time_of_burial_z',  CommonFunc::noon()); ?>
        </td>
        </tr>
        
        <tr>
        <td width="25%" class="label"><?php echo $form->labelEx($model,'special_rites'); ?></td>
        <td width="25%" class="element"><?php echo $form->dropDownList($model,'special_rites', DropDown::getSelfDefineOptions('customer', 'specialrites'), array('style'=>'width:270px;','class'=>'Other')); ?></td>
        <td width="25%" class="label"><?php echo $form->labelEx($model,'church_membership'); ?></td>
        <td width="25%" class="element"><?php echo $form->textField($model,'church_membership',array('maxlength'=>50, 'style'=>'width:270px;')); ?></td>
        </tr>
        
        <tr>
        <td width="25%" class="label"><?php echo $form->labelEx($model,'burial'); ?></td>
        <td width="25%" class="element">
          <?php echo $form->textField($model,'burial',array('maxlength'=>50, 'style'=>'width:270px;')); ?>
          <br/>
          <span style="font-size:10px;line-height: 12px;">Which Cemetery will burial take place?</span>
        </td>
        <td width="25%" class="label"><?php echo $form->labelEx($model,'memorials'); ?></td>
        <td width="25%" class="element">
          <?php echo $form->textArea($model,'memorials',array('cols'=>50, 'rows'=>5)); ?>
          <br/>
          <span style="font-size:10px;line-height: 12px;">Donations in lieu of flowers can be made to... (this is optional and there can be more than one memorial).</span>
        </td>
        </tr>
        
        <tr>
        <td width="25%" class="label"><?php echo $form->labelEx($model,'interment_city'); ?></td>
        <td width="25%" class="element"><?php echo $form->textField($model,'interment_city', array('style'=>'width:270px;','class'=>'Other')); ?></td>
        <td width="25%" class="label"> </td>
        <td width="25%" class="element"> </td>
        </tr>
        <tr>
        <td width="25%" class="label"><?php echo $form->labelEx($model,'interment_country'); ?></td>
        <td width="25%" class="element"><?php echo $form->dropDownList($model,'interment_country', CommonFunc::countries(), array('style'=>'width:270px;','class'=>'Other')); ?></td>
        <td width="25%" class="label"> </td>
        <td width="25%" class="element"> </td>
        </tr>
        <tr>
        <td width="25%" class="label"><?php echo $form->labelEx($model,'interment_state'); ?></td>
        <td width="25%" class="element"><?php echo $form->textField($model,'interment_state', array('style'=>'width:270px;','class'=>'Other')); ?></td>
        <td width="25%" class="label"> </td>
        <td width="25%" class="element"> </td>
        </tr>
		<?php if($optionFields->interment_street) : ?>
			<tr>
			<td width="25%" class="label"><?php echo $form->labelEx($model,'interment_street'); ?></td>
			<td width="25%" class="element"><?php echo $form->textField($model,'interment_street', array('style'=>'width:270px;','class'=>'Other')); ?></td>
			<td width="25%" class="label"> </td>
			<td width="25%" class="element"> </td>
			</tr>
		<?php endif; ?>
		<?php if($optionFields->interment_zip) : ?>
			<tr>
			<td width="25%" class="label"><?php echo $form->labelEx($model,'interment_zip'); ?></td>
			<td width="25%" class="element"><?php echo $form->textField($model,'interment_zip', array('style'=>'width:270px;','class'=>'Other')); ?></td>
			<td width="25%" class="label"> </td>
			<td width="25%" class="element"> </td>
			</tr>
		<?php endif; ?>
        
        <tr></tr><tr></tr><tr></tr><tr></tr><tr></tr>
        
        <tr>
        <td align="center"><b style="font-size: 20px;">Biography</b><hr></td>
        <td colspan="3"></td>
        </tr>
        
        <tr>
        <td width="25%" class="label"><?php echo $form->labelEx($model,'fathers_name_f'); ?></td>
        <td width="25%" class="element">
          <?php echo $form->textField($model,'fathers_name_f',array('style'=>'width:89px;','maxlength'=>50)); ?>
          <?php echo $form->textField($model,'fathers_name_m',array('style'=>'width:89px;','maxlength'=>20)); ?>
          <?php echo $form->textField($model,'fathers_name_l',array('style'=>'width:89px;','maxlength'=>50)); ?>
        </td>
        <td width="25%" class="label"><?php echo $form->labelEx($model,'mothers_name_f'); ?></td>
        <td width="25%" class="element">
          <?php echo $form->textField($model,'mothers_name_f',array('style'=>'width:89px;','maxlength'=>50)); ?>
          <?php echo $form->textField($model,'mothers_name_m',array('style'=>'width:89px;','maxlength'=>20)); ?>
          <?php echo $form->textField($model,'mothers_name_l',array('style'=>'width:89px;','maxlength'=>50)); ?>
           <br/><span style="font-size:10px;line-height: 12px;">Use Mothers maiden name</span>
        </td>
        </tr>
        
        <tr>
        <td width="25%" class="label"><?php echo $form->labelEx($model,'highest_level_of_education'); ?></td>
        <td width="25%" class="element"><?php echo $form->dropDownList($model,'highest_level_of_education', DropDown::getSelfDefineOptions('customer', 'highestleveleducation'), array('style'=>'width:270px;','class'=>'Other')); ?></td>
        <td width="25%" class="label"><?php echo $form->labelEx($model,'marital_status'); ?></td>
        <td width="25%" class="element"><?php echo $form->dropDownList($model,'marital_status', DropDown::getSelfDefineOptions('customer', 'maritalstatus'), array('style'=>'width:270px;','class'=>'Other')); ?></td>
        </tr>
        
        <tr>
        <td width="25%" class="label"><?php echo $form->labelEx($model,'occupation'); ?></td>
        <td width="25%" class="element">
          <?php echo $form->textArea($model,'occupation',array('cols'=>50,'rows'=>5)); ?>
          <br/>
          <span style="font-size:10px;line-height: 12px;">Include Occupation and Employment history that you want included in the Obituary</span>
        </td>
        <td width="25%" class="label"><?php echo $form->labelEx($model,'biography'); ?></td>
        <td width="25%" class="element">
          <?php echo $form->textArea($model,'biography',array('cols'=>50,'rows'=>5)); ?>
          <br/>
          <span style="font-size:10px;line-height: 12px;">Memberships, Organizations, Interests, Important Information</span>
        </td>
        </tr>
        
        <tr>
        <td width="25%" class="label"><?php echo $form->labelEx($model,'veteran_status'); ?></td>
        <td width="25%" class="element">
          <?php echo $form->textField($model,'veteran_status',array('style'=>'width:270px;','maxlength'=>50)); ?>
        </td>
        <td width="25%" class="label"><?php echo $form->labelEx($model,'branch'); ?></td>
        <td width="25%" class="element">
          <?php echo $form->textField($model,'branch',array('style'=>'width:270px;','maxlength'=>50)); ?>
        </td>
        </tr>
        
        <tr>
        <td width="25%" class="label"><?php echo $form->labelEx($model,'full_military_rites'); ?></td>
        <td width="25%" class="element">
          <?php echo $form->dropDownList($model,'full_military_rites',array(''=>'','Y'=>'Y','S'=>'S'),array('style'=>'width: 270px;')); ?>
        </td>
        </tr>

		<?php if($optionFields->veteran_serial_number) : ?>
			<tr>
			<td width="25%" class="label"><?php echo $form->labelEx($model,'veteran_serial_number'); ?></td>
			<td width="25%" class="element"><?php echo $form->textField($model,'veteran_serial_number',array('style'=>'width:270px;','maxlength'=>50)); ?></td>
			<td width="25%" class="label"></td>
			<td width="25%" class="element"></td>
			</tr>
		<?php endif; ?>
       
        <tr>
        <td width="25%" class="label"><?php echo $form->labelEx($model,'spouse_f'); ?></td>
        <td width="25%" class="element">
          <?php echo $form->textField($model,'spouse_f',array('style'=>'width:89px;','maxlength'=>50)); ?>
          <?php echo $form->textField($model,'spouse_m',array('style'=>'width:89px;','maxlength'=>20)); ?>
          <?php echo $form->textField($model,'spouse_l',array('style'=>'width:89px;','maxlength'=>50)); ?>
          <br/>
          <span style="font-size:10px;line-height: 12px;">If applicable</span>
        </td>
        <td width="25%" class="label"><?php echo $form->labelEx($model,'spouse_date_of_death'); ?></td>
        <td width="25%" class="element"><?php echo $form->textField($model,'spouse_date_of_death',array('style'=>'width:270px;','maxlength'=>50, 'class'=>'datepicker')); ?></td>
        </tr>
        
        <tr>
        <td width="25%" class="label"><?php echo $form->labelEx($model,'date_of_marriage'); ?></td>
        <td width="25%" class="element"><?php echo $form->textField($model,'date_of_marriage',array('style'=>'width:270px;','maxlength'=>50, 'class'=>'datepicker')); ?></td>
        <td width="25%" class="label"><?php echo $form->labelEx($model,'place_of_marriage'); ?></td>
        <td width="25%" class="element">
          <?php echo $form->textField($model,'place_of_marriage',array('maxlength'=>50, 'style'=>'width:270px;')); ?>
          <br/>
          <span style="font-size:10px;line-height: 12px;">If Place of Marriage is not known, leave blank.</span>
        </td>
        </tr>
        
        <tr>
        <td width="25%" class="label"><?php echo $form->labelEx($model,'previous_marriage'); ?></td>
        <td width="25%" class="element"><?php echo $form->textField($model,'previous_marriage',array('style'=>'width:270px;','maxlength'=>50)); ?></td>
        <td width="25%" class="label">Date/Place of Previous Marriage</td>
        <td width="25%" class="element">
          <?php echo $form->textField($model,'date_of_previous_marriage',array('maxlength'=>50, 'style'=>'width:134px;', 'class'=>'datepicker')); ?>
          <?php echo $form->textField($model,'place_of_previous_marriage',array('maxlength'=>50, 'style'=>'width:134px;')); ?>
        </td>
        </tr>
        
        <tr>
        <td width="25%" class="label"><?php echo $form->labelEx($model,'survived_by'); ?></td>
        <td width="25%" class="element"><?php echo $form->textArea($model,'survived_by',array('cols'=>50,'rows'=>5)); ?></td>
        <td width="25%" class="label"><?php echo $form->labelEx($model,'preceded_in_death_by'); ?></td>
        <td width="25%" class="element"><?php echo $form->textArea($model,'preceded_in_death_by',array('cols'=>50,'rows'=>5)); ?></td>
        </tr>
        
        <tr></tr><tr></tr><tr></tr><tr></tr><tr></tr>
        
        <tr>
        <td align="center"><b style="font-size: 20px;">Service Related</b><hr></td>
        <td colspan="3"></td>
        </tr> 

        <tr>
        <td width="25%" class="label" style="vertical-align: top;"><?php echo $form->labelEx($model,'newspaper_radio1'); ?></td>
        <td width="25%" class="element">
          <?php echo $form->dropDownList($model,'newspaper_radio1', DropDown::getSelfDefineOptions('customer', 'newspaperradio1'), array('style'=>'margin-bottom: 5px;width:270px;','class'=>'Other', 'onchange'=>'showNewspaperRadioOther(this);')); ?>
          <span style="display:none;"><?php echo $form->textField($model,'newspaper_radio1_other', array('style'=>'width:270px;','class'=>'Other')); ?></span>
        </td>
        <td width="25%" class="label"><?php echo $form->labelEx($model,'newspaper_radio2'); ?></td>
        <td width="25%" class="element">
          <?php echo $form->dropDownList($model,'newspaper_radio2', DropDown::getSelfDefineOptions('customer', 'newspaperradio2'), array('style'=>'margin-bottom: 5px;width:270px;','class'=>'Other', 'onchange'=>'showNewspaperRadioOther(this);')); ?>
          <span style="display:none;"><?php echo $form->textField($model,'newspaper_radio2_other', array('style'=>'width:270px;','class'=>'Other')); ?></span>
        </td>
        </tr>
       
        <tr>
        <td width="25%" class="label"><?php echo $form->labelEx($model,'newspaper_radio3'); ?></td>
        <td width="25%" class="element">
          <?php echo $form->dropDownList($model,'newspaper_radio3', DropDown::getSelfDefineOptions('customer', 'newspaperradio3'), array('style'=>'margin-bottom: 5px;width:270px;','class'=>'Other', 'onchange'=>'showNewspaperRadioOther(this);')); ?>
          <span style="display:none;"><?php echo $form->textField($model,'newspaper_radio3_other', array('style'=>'width:270px;','class'=>'Other')); ?></span>
        </td>
        <td width="25%" class="label"><?php echo $form->labelEx($model,'newspaper_radio4'); ?></td>
        <td width="25%" class="element">
          <?php echo $form->dropDownList($model,'newspaper_radio4', DropDown::getSelfDefineOptions('customer', 'newspaperradio4'), array('style'=>'margin-bottom: 5px;width:270px;','class'=>'Other', 'onchange'=>'showNewspaperRadioOther(this);')); ?>
          <span style="display:none;"><?php echo $form->textField($model,'newspaper_radio4_other', array('style'=>'width:270px;','class'=>'Other')); ?></span>
        </td>
        </tr>
        
        <tr>
        <td width="25%" class="label"><?php echo $form->labelEx($model,'newspaper_radio5'); ?></td>
        <td width="25%" class="element">
          <?php echo $form->dropDownList($model,'newspaper_radio5', DropDown::getSelfDefineOptions('customer', 'newspaperradio5'), array('style'=>'margin-bottom: 5px;width:270px;','class'=>'Other', 'onchange'=>'showNewspaperRadioOther(this);')); ?>
         <span style="display:none;"><?php echo $form->textField($model,'newspaper_radio5_other', array('style'=>'width:270px;','class'=>'Other')); ?></span>
        </td>
        <td width="25%" class="label"><?php echo $form->labelEx($model,'newspaper_radio6'); ?></td>
        <td width="25%" class="element">
          <?php echo $form->dropDownList($model,'newspaper_radio6', DropDown::getSelfDefineOptions('customer', 'newspaperradio6'), array('style'=>'margin-bottom: 5px;width:270px;','class'=>'Other', 'onchange'=>'showNewspaperRadioOther(this);')); ?>
          <span style="display:none;"><?php echo $form->textField($model,'newspaper_radio6_other', array('style'=>'width:270px;','class'=>'Other')); ?></span>
        </td>
        </tr>
        
        <tr>
        <td width="25%" class="label"><?php echo $form->labelEx($model,'submit_pic_with_obit'); ?></td>
        <td width="25%" class="element"><?php echo $form->dropDownList($model,'submit_pic_with_obit', DropDown::getSelfDefineOptions('customer', 'submitpicwithobit'), array('style'=>'width:270px;','class'=>'Other')); ?></td>
        </tr>

        <tr>
        <td width="25%" class="label"><?php echo $form->labelEx($model,'music_selection1'); ?></td>
        <td width="25%" class="element"><?php echo $form->textField($model,'music_selection1',array('style'=>'width:270px;','maxlength'=>50)); ?></td>
        <td width="25%" class="label"><?php echo $form->labelEx($model,'music_selection2'); ?></td>
        <td width="25%" class="element"><?php echo $form->textField($model,'music_selection2',array('style'=>'width:270px;','maxlength'=>50)); ?></td>
        </tr>
        <tr>
        <td width="25%" class="label"><?php echo $form->labelEx($model,'music_selection3'); ?></td>
        <td width="25%" class="element"><?php echo $form->textField($model,'music_selection3',array('style'=>'width:270px;','maxlength'=>50)); ?></td>
        <td width="25%" class="label"><?php echo $form->labelEx($model,'music_selection4'); ?></td>
        <td width="25%" class="element"><?php echo $form->textField($model,'music_selection4',array('style'=>'width:270px;','maxlength'=>50)); ?></td>
        </tr>
        <tr>
        <td width="25%" class="label"><?php echo $form->labelEx($model,'music_selection5'); ?></td>
        <td width="25%" class="element"><?php echo $form->textField($model,'music_selection5',array('style'=>'width:270px;','maxlength'=>50)); ?></td>
        <td width="25%" class="label"></td>
        <td width="25%" class="element"></td>
        </tr>
        
        <tr>
        <td width="25%" class="label"><?php echo $form->labelEx($model,'pallbearers'); ?></td>
        <td width="25%" class="element"><?php echo $form->textField($model,'pallbearers',array('style'=>'width:270px;','maxlength'=>50)); ?></td>
        <td width="25%" class="label"><?php echo $form->labelEx($model,'pallbearer2'); ?></td>
        <td width="25%" class="element"><?php echo $form->textField($model,'pallbearer2',array('style'=>'width:270px;','maxlength'=>50)); ?></td>
        </tr>
        <tr>
        <td width="25%" class="label"><?php echo $form->labelEx($model,'pallbearer3'); ?></td>
        <td width="25%" class="element"><?php echo $form->textField($model,'pallbearer3',array('style'=>'width:270px;','maxlength'=>50)); ?></td>
        <td width="25%" class="label"><?php echo $form->labelEx($model,'pallbearer4'); ?></td>
        <td width="25%" class="element"><?php echo $form->textField($model,'pallbearer4',array('style'=>'width:270px;','maxlength'=>50)); ?></td>
        </tr>
        <tr>
        <td width="25%" class="label"><?php echo $form->labelEx($model,'pallbearer5'); ?></td>
        <td width="25%" class="element"><?php echo $form->textField($model,'pallbearer5',array('style'=>'width:270px;','maxlength'=>50)); ?></td>
        <td width="25%" class="label"><?php echo $form->labelEx($model,'pallbearer6'); ?></td>
        <td width="25%" class="element"><?php echo $form->textField($model,'pallbearer6',array('style'=>'width:270px;','maxlength'=>50)); ?></td>
        </tr>
        <tr>
        <td width="25%" class="label"><?php echo $form->labelEx($model,'pallbearer7'); ?></td>
        <td width="25%" class="element"><?php echo $form->textField($model,'pallbearer7',array('style'=>'width:270px;','maxlength'=>50)); ?></td>
        <td width="25%" class="label"><?php echo $form->labelEx($model,'pallbearer8'); ?></td>
        <td width="25%" class="element"><?php echo $form->textField($model,'pallbearer8',array('style'=>'width:270px;','maxlength'=>50)); ?></td>
        </tr>
        <tr>
        <td width="25%" class="label"></td>
        <td width="25%" class="element"></td>
        <td width="25%" class="label"><?php echo $form->labelEx($model,'special_music'); ?></td>
        <td width="25%" class="element"><?php echo $form->textArea($model,'special_music',array('cols'=>50,'rows'=>5)); ?></td>
        </tr>

        <tr>
        <td width="25%" class="label"><?php echo $form->labelEx($model,'doctors_name'); ?></td>
        <td width="25%" class="element">
          <?php echo $form->textField($model,'doctors_name',array('maxlength'=>50, 'style'=>'width:270px;')); ?>
          <br/>
          <span style="font-size:10px;line-height: 12px;">For Death Certificate. Person most likely to sign the DC</span>
        </td>
        <td width="25%" class="label"><?php echo $form->labelEx($model,'ssn'); ?></td>
        <td width="25%" class="element">
          <?php echo $form->textField($model,'ssn',array('maxlength'=>50, 'style'=>'width:270px;')); ?>
          <span style="font-size:10px;line-height: 12px;">Family Must Verify For Death Certificate</span>
        </td>
        </tr>

		<?php if($optionFields->doctor_street) : ?>
			<tr>
			<td width="25%" class="label"><?php echo $form->labelEx($model,'doctor_street'); ?></td>
			<td width="25%" class="element"><?php echo $form->textField($model,'doctor_street',array('style'=>'width:270px;','maxlength'=>50)); ?></td>
			<td width="25%" class="label"></td>
			<td width="25%" class="element"></td>
			</tr>
		<?php endif; ?>

		<?php if($optionFields->doctor_city) : ?>
			<tr>
			<td width="25%" class="label"><?php echo $form->labelEx($model,'doctor_city'); ?></td>
			<td width="25%" class="element"><?php echo $form->textField($model,'doctor_city',array('style'=>'width:270px;','maxlength'=>50)); ?></td>
			<td width="25%" class="label"></td>
			<td width="25%" class="element"></td>
			</tr>
		<?php endif; ?>

		<?php if($optionFields->doctor_state) : ?>
			<tr>
			<td width="25%" class="label"><?php echo $form->labelEx($model,'doctor_state'); ?></td>
			<td width="25%" class="element"><?php echo $form->textField($model,'doctor_state',array('style'=>'width:270px;','maxlength'=>50)); ?></td>
			<td width="25%" class="label"></td>
			<td width="25%" class="element"></td>
			</tr>
		<?php endif; ?>

		<?php if($optionFields->doctor_zip) : ?>
			<tr>
			<td width="25%" class="label"><?php echo $form->labelEx($model,'doctor_zip'); ?></td>
			<td width="25%" class="element"><?php echo $form->textField($model,'doctor_zip',array('style'=>'width:270px;','maxlength'=>50)); ?></td>
			<td width="25%" class="label"></td>
			<td width="25%" class="element"></td>
			</tr>
		<?php endif; ?>

        <tr>
        <td width="25%" class="label"><?php echo $form->labelEx($model,'informant_name_f'); ?></td>
        <td width="25%" class="element">
          <?php echo $form->textField($model,'informant_name_f',array('style'=>'width:135px;','maxlength'=>50)); ?>
          <?php echo $form->textField($model,'informant_name_l',array('style'=>'width:135px;','maxlength'=>50)); ?>
        </td>
        <td width="25%" class="label"><?php echo $form->labelEx($model,'informant_relationship'); ?></td>
        <td width="25%" class="element">
          <?php echo $form->textField($model,'informant_relationship',array('style'=>'width:270px;','maxlength'=>50)); ?>
        </td>
        </tr>
        
        <tr>
        <td width="25%" class="label"><?php echo $form->labelEx($model,'informant_name_address'); ?></td>
        <td width="25%" class="element">
          <?php echo $form->textField($model,'informant_name_address',array('maxlength'=>50, 'style'=>'width:270px;')); ?>
          <br/>
          <span style="font-size:10px;line-height: 12px;">For Death Certificate</span>
        </td>
        <td width="25%" class="label"><?php echo $form->labelEx($model,'informant_phone'); ?></td>
        <td width="25%" class="element">
          <?php echo $form->textField($model,'informant_phone',array('maxlength'=>50, 'style'=>'width:270px;','onfocus'=>'showWarning(this);','onblur'=>'isValidPhone(this);')); ?>
          <span name="warning" style="display:none; color: #f00;">Warning: Phone Number format must be xxx-xxx-xxxx.</span>
          <span name="error" style="display:none; color: #f00;">Error: Your input format is incorrect!!! Please enter again!</span>
          <span name="correct" style="display:none; color: #f00;">Congratulation! Your input format is correct! </span>
        </td>
        </tr>
        
        <tr>
        <td width="25%" class="label"><?php echo $form->labelEx($model,'informant_name_city'); ?></td>
        <td width="25%" class="element"><?php echo $form->textField($model,'informant_name_city',array('maxlength'=>50, 'style'=>'width:270px;')); ?></td>
        <td width="25%" class="label"><?php echo $form->labelEx($model,'informant_name_state'); ?></td>
        <td width="25%" class="element"><?php echo $form->textField($model,'informant_name_state',array('maxlength'=>50, 'style'=>'width:270px;')); ?></td>
        </tr>
        
        <tr>
        <td width="25%" class="label"><?php echo $form->labelEx($model,'informant_name_country'); ?></td>
        <td width="25%" class="element"><?php echo $form->textField($model,'informant_name_country',array('maxlength'=>50, 'style'=>'width:270px;')); ?></td>
        <td width="25%" class="label"><?php echo $form->labelEx($model,'informant_name_zip'); ?></td>
        <td width="25%" class="element"><?php echo $form->textField($model,'informant_name_zip',array('maxlength'=>50, 'style'=>'width:270px;')); ?></td>
        </tr>
        
        <tr>
        <td width="25%" class="label"><?php echo $form->labelEx($model,'military_veteran'); ?></td>
        <td width="25%" class="element">
          <?php echo $form->dropDownList($model,'military_veteran', DropDown::getSelfDefineOptions('customer', 'militaryveteran'), array('style'=>'width:270px;','class'=>'Other')); ?>
          <br/>
          <span style="font-size:10px;line-height: 12px;">Present DD 214 to apply for VA Marker</span>
        </td>
        <td width="25%" class="label"><?php echo $form->labelEx($model,'assigned_to'); ?></td>
        <td width="25%" class="element">
          <?php echo $form->dropDownList($model,'assigned_to', DropDown::getSelfDefineOptions('customer', 'assignedto'), array('style'=>'width:270px;','class'=>'Other')); ?>
        </td>
        </tr>
        
      </tbody>
    </table>
  </div>

	<table width="95%" border="0" cellpadding="0" cellspacing="0"><tbody><tr><td align="center"></div>
    <input title="Save" class="button" onclick="" type="submit" name="button" value="Save"/>
    <input title="Cancel" class="button buttonurl" url="/customer" pageTitle="Customer" type="button" name="button" value="Cancel"/>
  </td></tr></tbody></table>
  
  
<?php $this->endWidget(); ?>

<?php endif; ?>

</div>


<script type="text/javascript">
  function calAge(){
    
  }
  
  function showInput1(obj){
     if(obj.value == 'Other'){
       $('#location_of_funeral_service_other').show();
     }else{
       $('#location_of_funeral_service_other').hide();
     }
  }
  
  $('#Customer_location_of_funeral_service').change();
  
  function showInput2(obj){
    if(obj.value == 'Other'){
       $('#location_of_visitation_other').show();
     }else{
       $('#location_of_visitation_other').hide();
     }
  }
  $('#Customer_location_of_visitation').change();
  
  function showNewspaperRadioOther(dropdown){
    if(dropdown.value == 'Other'){
      $(dropdown).next().show();
    } else {
      $(dropdown).next().hide();
    }
  }
  
  $('#Customer_newspaper_radio1').change()
  $('#Customer_newspaper_radio2').change()
  $('#Customer_newspaper_radio3').change()
  $('#Customer_newspaper_radio4').change()
  $('#Customer_newspaper_radio5').change()
  $('#Customer_newspaper_radio6').change()
</script>

<script type="text/javascript">
  function getVaule(){
    var value = document.getElementById('Customer_full_legal_name').value;
    alert(value);
    return;
  }
  
  function check_form_customer(){
	  $('#EditView').attr('action', $('#EditView').attr('action').replace('?ajaxRequest=1',''));

      if(document.getElementById('Customer_full_legal_name').value == ''){
        $('.warning').show();
        $('#Customer_full_legal_name').css('background-color', '#f00');
        $('#Customer_full_legal_name').select();
        return false;
      }else{
          var informant_phone = document.getElementById('Customer_informant_phone').value;
         
          if(informant_phone !== ''){
            var regObj = new RegExp(/^[\d]{3}\-[\d]{3}\-[\d]{4}$/);

            if(!regObj.test(informant_phone)){
                $('#Customer_informant_phone').focus();
                $('#Customer_informant_phone').css('background-color', '#f00');
                $('#Customer_informant_phone').next().hide();
                $('#Customer_informant_phone').next().next().hide();
                $('#Customer_informant_phone').next().next().show();
                
                setTimeout(deleteCSS, 5000);
              return false;
            }
            return true; 
          }else{
            return true;
          }
        
//        return true;
      }
  }
  
  function deleteCSS(){
    clearTimeout();
    $('#Customer_informant_phone').css('background-color', '#fff');
    $('#Customer_informant_phone').next().next().hide();
    $('#Customer_informant_phone').blur();
  }
  
  function showWarning(informantPhoneObj){
    $(informantPhoneObj).next().next().hide();
    $(informantPhoneObj).next().next().next().hide();
    $(informantPhoneObj).next().show();
  }
  
  function isValidPhone(informantPhoneObj){
    var regObj = new RegExp(/^[\d]{3}\-[\d]{3}\-[\d]{4}$/);
    
    if(informantPhoneObj.value != ''){
//      var v = regObj.test(informantPhoneObj.value);
    
      if(!regObj.test(informantPhoneObj.value)){
        $(informantPhoneObj).next().hide();
        $(informantPhoneObj).next().next().show();
      }else{
        $(informantPhoneObj).next().hide();
        $(informantPhoneObj).next().next().hide();
        $(informantPhoneObj).next().next().next().show();
      }
      
    }else{
      $(informantPhoneObj).next().hide();
      $(informantPhoneObj).next().next().hide();
      $(informantPhoneObj).next().next().next().hide();
    }
    
  }
</script>
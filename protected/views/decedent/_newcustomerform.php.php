<div id="rwb" class="rwb">
  
<?php
if ($model->id > 0) {
  $title = 'Update New Decedent';
} else {
  $title = 'Summers-Kistler Funeral Home - Obituary';
}

?>
 <!--<h1>Summers-Kistler Funeral Home - Obituary</h1>-->      

<?php
$this->pageTitle = Yii::app()->name . ' - ' . $title;
?>

<?php if(Yii::app()->user->hasFlash('msg')): ?>

<div class="flash-success">
	<?php echo Yii::app()->user->getFlash('msg'); ?>
</div>

<?php else: ?>

 <iframe src="" id="hiddenifm" name="hiddenifm" style="display:none;width:300px;height:300px;"></iframe>

<?php $form=$this->beginWidget('CActiveForm', array('htmlOptions' => 
    array('name'=>'newcustomerform', 'id'=>'newcustomerform', 
        'class'=>'noajax', 
        'target'=>'hiddenifm',
        'enctype'=>'multipart/form-data',
//        'onsubmit'=>"return(check_form('EditView'));"
    ))); ?>

	<?php echo $form->errorSummary($model); ?>
 
 <input type="hidden" name="autosave" id="autosave" value="0"/>

  <table width="100%" border="0" cellspacing="0" cellpadding="0"><tbody><tr><td class="Leads-titleIcon">&nbsp;</td> 
  <td height="30" class="title hline" style="padding-left:10px"> <?php echo $title; ?> </td></tr></tbody></table>
  <p></p>
  <div class="bodyText mandatory"><div style="float:right;" class="bodyText mandatory">* Required Field(s)</div></div>

  <table width="95%" border="0" cellpadding="0" cellspacing="0"><tbody><tr><td align="center">
    <input title="Save" class="button" type="button" name="button" value="Save" onclick="submitNewCustomerForm();"/>
    <input title="Cancel" class="button buttonurl" url="/decedent" pageTitle="Customer" type="button" id="button_cancel1" name="button" value="Cancel"/>
  </td></tr></tbody></table>

  <div id="Information">
    <table id="secHeadLead Information" width="95%" cellspacing="0" cellpadding="0">
<!--      <tbody width="100%"><tr><td class="secHead">Information</td></tr></tbody>-->
    </table>
    <!--<table width="100%" border="0" cellspacing="1" cellpadding="0" class="tabForm" bgcolor="white">-->
    <!--<table width="560" border="0" cellspacing="1" cellpadding="0" class="tabForm">-->
    <table border="0" cellspacing="2" cellpadding="0" align="center">
      <tbody>
        <tr>
        <td colspan="2" class="title">Status</td>
        <td></td>
        </tr>
<!--        <tr>
        <td colspan="2" class="element"><?php // echo $form->dropDownList($model,'status', DropDown::getSelfDefineOptions('customer', 'status'), array('style'=>'width:454px;','class'=>'Other')); ?></b></td>
        </tr>-->
        <tr>
          <td colspan="2" class="element">
            <?php  
              $oldStatus = DropDown::getSelfDefineOptions('customer', 'status'); 
              $newStatus = DropDown::getNoNullValueArray($oldStatus);   
              echo $form->dropDownList($model,'status', $newStatus, array('style'=>'width:454px;','class'=>'Other'));
            ?>
          </td>
        </tr>
        
        <tr>
        <!--<td colspan="2" class="title"><b>SKFH - Funeral Home</b></td>-->
        <td colspan="2" class="title"><?php echo $form->labelEx($model,'skfh_funeral_home',array('style'=>'width:454px;')); ?></td>
        <td></td>
        </tr>
        <tr>
        <td colspan="2" class="element"><?php echo $form->dropDownList($model,'skfh_funeral_home', DropDown::getSelfDefineOptions('customer', 'skfhfuneralhome'), array('style'=>'width:454px;','class'=>'Other')); ?></b></td>
        </tr>
        
        <tr>
        <td colspan="2" class="title"><?php echo $form->labelEx($model,'full_legal_name',array('style'=>'width:454px;color:red;')); ?></td>
        </tr>
        <tr>
        <td colspan="2" class="element"><?php echo $form->textField($model,'full_legal_name',array('maxlength'=>50, 'style'=>'width:454px;')); ?></td>
        </tr>
        <tr>
        <td colspan="2" class="element_full_legal"><span style="display: none; color: f00;" class="warning"> Warning: This field must be filled in and it's not blank!!!</span></td>
        </tr>

        <tr>
        <td colspan="2" class="title"><b><?php echo $form->labelEx($model,'name_for_obituary',array('style'=>'width:454px;')); ?></b></td>
        </tr>
        <tr>
        <td colspan="2" class="element"><?php echo $form->textField($model,'name_for_obituary',array('maxlength'=>50, 'style'=>'width:454px;')); ?></b></td>
        </tr>
        
        <tr>
        <td colspan="2" class="title"><b><?php echo $form->labelEx($model,'address'); ?></b></td>
        </tr>
        <tr>
        <td colspan="2" class="element"><?php echo $form->textField($model,'address',array('maxlength'=>50, 'style'=>'width:454px;')); ?></b></td>
        </tr>
        
        <tr>
        <td colspan="2" class="title"><b><?php echo $form->labelEx($model,'age',array('style'=>'width:454px;')); ?></td>
        </tr>
        <tr>
        <td colspan="2" class="element"><?php echo $form->textField($model,'age',array('maxlength'=>50, 'style'=>'width:454px;'));?></b></td>
        </tr>
        
        <tr>
        <td colspan="2" class="title"><b><?php echo $form->labelEx($model,'clergy_full_name2',array('style'=>'width:454px;')); ?></td>
        </tr>
        <tr>
        <td colspan="2" class="element"><?php echo $form->textField($model,'clergy_full_name2',array('maxlength'=>50, 'style'=>'width:454px;'));?></b></td>
        </tr>
        
        <tr>
        <td colspan="2" class="title"><b><?php echo $form->labelEx($model,'formerly_of'); ?></b></td>
        </tr>
        <tr>
        <td colspan="2" class="element"><?php echo $form->textField($model,'formerly_of',array('maxlength'=>50, 'style'=>'width:454px;')); ?></b></td>
        </tr>
        
        <tr>
        <td colspan="2" class="title"><b><?php echo $form->labelEx($model,'date_of_birth'); ?></b></td>
        </tr>
        <tr>
          <td colspan="2">
            <?php echo $form->textField($model,'date_of_birth',array('maxlength'=>10, 'class'=>'datepicker', 'style'=>'width:454px;')); ?>
          </td>
        </tr>
        
        <tr>
        <!--<td class="title"><b><?php // echo $form->labelEx($model,'date_of_birth'); ?></b></td>-->
        <td class="title"><b><?php echo $form->labelEx($model,'place_of_birth'); ?></b></td>
        </tr>
        <tr>
<!--        <td>
          <?php // echo $form->textField($model,'date_of_birth',array('maxlength'=>50, 'class'=>'datepicker', 'style'=>'width:60px;')); ?>
          <?php // echo $form->dropdownlist($model,'time_of_birth_h',  CommonFunc::hours()); ?>
          <?php // echo $form->dropdownlist($model,'time_of_birth_m',  CommonFunc::minutes()); ?>
          <?php // echo $form->dropdownlist($model,'time_of_birth_z',  CommonFunc::noon()); ?>
          <?php // echo $form->dropdownlist($model,'zone_of_birth',  CommonFunc::zones()); ?>
        </td>-->
        <td colspan="2" class="element">
         <?php // echo $form->textField($model,'place_of_birth',array('style'=>'width:204px;','maxlength'=>50, 'onchange'=>'calAge();')); ?>
         <?php // echo $form->textField($model,'place_of_birth',array('maxlength'=>50, 'style'=>'width:187px;')); ?>
         <?php echo $form->textField($model,'place_of_birth',array('maxlength'=>50, 'style'=>'width:454px;')); ?>
        </td>
        </tr>

        <tr>
        <td class="title"><b><?php echo $form->labelEx($model,'date_of_death'); ?></b></td>
        <td class="title"><b><?php echo $form->labelEx($model,'place_of_death'); ?></b></td>
        </tr>
        <tr>
        <td>
          <?php echo $form->textField($model,'date_of_death',array('maxlength'=>50, 'onchange'=>'calAge();', 'class'=>'datepicker', 'style'=>'width:60px;')); ?>
          <?php echo $form->dropdownlist($model,'time_of_death_h',  CommonFunc::hours()); ?>
          <?php echo $form->dropdownlist($model,'time_of_death_m',  CommonFunc::minutes()); ?>
          <?php echo $form->dropdownlist($model,'time_of_death_z',  CommonFunc::noon()); ?>
          <?php // echo $form->dropdownlist($model,'zone_of_death',  CommonFunc::zones()); ?>
        </td>
        <td>
          <?php // echo $form->textField($model,'place_of_death',array('maxlength'=>50, 'style'=>'width:204px;')); ?>
          <?php echo $form->textField($model,'place_of_death',array('maxlength'=>50, 'style'=>'width:187px;')); ?>
        </td>
        </tr>
        
        
        <tr></tr><tr></tr><tr></tr><tr></tr><tr></tr>
        <tr>
        <td align="align" colspan="4" class="title"><b style="font-size: 25px;">Funeral Services</b><hr></td>
        <!--<td colspan="3"></td>-->
        </tr>
        
        <tr>
        <td class="title"><b><?php echo $form->labelEx($model,'officiant'); ?></b></td>
        </tr>
        <tr>
        <td colspan="2" class="element"><?php echo $form->textField($model,'officiant',array('maxlength'=>50, 'style'=>'width:454px;')); ?></b></td>
        </tr>
        
        <tr>
        <td class="title"><b><?php echo $form->labelEx($model,'location_of_funeral_service'); ?></b></td>
        </tr>
        <tr>
        <td colspan="2" class="element"><?php echo $form->dropDownList($model,'location_of_funeral_service', DropDown::getSelfDefineOptions('customer', 'locationfuneralservice'), array('style'=>'width:454px;','class'=>'Other','onchange'=>'return showInput1(this)')); ?></b></td>
        </tr>
        <tr style="display:none;" id="location_of_funeral_service_other">
        <td colspan="2" class="element"><?php echo $form->textField($model,'location_of_funeral_service_other', array('style'=>'width:454px;','class'=>'Other')); ?></td>  
        </tr>
        
        <tr>
        <td class="title"><b><?php echo $form->labelEx($model,'funeral_service_date'); ?></b></td>
        </tr>
        <tr>
        <td colspan="2" class="element">
          <?php echo $form->textField($model,'funeral_service_date',array('style'=>'width:305px;','maxlength'=>50, 'class'=>'datepicker')); ?>
          <?php echo $form->dropdownlist($model,'funeral_service_time_h',  CommonFunc::hours()); ?>
          <?php echo $form->dropdownlist($model,'funeral_service_time_m',  CommonFunc::minutes()); ?>
          <?php echo $form->dropdownlist($model,'funeral_service_time_z',  CommonFunc::noon()); ?>
        </td>
        </tr>
        
        <tr>
        <td class="title"><b><?php echo $form->labelEx($model,'location_of_visitation'); ?></b></td>
        </tr>
        <tr>
        <td colspan="2" class="element"><?php echo $form->dropDownList($model,'location_of_visitation', DropDown::getSelfDefineOptions('customer', 'locationvisitation'), array('style'=>'width:454px;','class'=>'Other','onchange'=>'showInput2(this)')); ?></b></td>
        </tr>
        <tr style="display:none;" id="location_of_visitation_other">
        <td colspan="2" class="element"><?php echo $form->textField($model,'location_of_visitation_other', array('style'=>'width:454px;','class'=>'Other')); ?></td>
        </tr>
        
        <tr>
        <td class="title"><b><?php echo $form->labelEx($model,'date_of_visitation_start'); ?></b></td>
        </tr>
        <tr>
        <td colspan="2" class="element">
          <?php echo $form->textField($model,'date_of_visitation_start',array('style'=>'width:308px;','maxlength'=>50, 'class'=>'datepicker')); ?>
          <?php echo $form->dropdownlist($model,'visitation_time_h_start',  CommonFunc::hours()); ?>
          <?php echo $form->dropdownlist($model,'visitation_time_m_start',  CommonFunc::minutes()); ?>
          <?php echo $form->dropdownlist($model,'visitation_time_z_start',  CommonFunc::noon()); ?>
        </td>
        </tr>
        
        <tr>
        <td class="title"><b><?php echo $form->labelEx($model,'date_of_visitation_end'); ?></b></td>
        </tr>
        <tr>
        <td colspan="2" class="element">
          <?php echo $form->textField($model,'date_of_visitation_end',array('style'=>'width:308px;','maxlength'=>50, 'class'=>'datepicker')); ?>
          <?php echo $form->dropdownlist($model,'visitation_time_h_end',  CommonFunc::hours()); ?>
          <?php echo $form->dropdownlist($model,'visitation_time_m_end',  CommonFunc::minutes()); ?>
          <?php echo $form->dropdownlist($model,'visitation_time_z_end',  CommonFunc::noon()); ?>
        </td>
        </tr>
        
        <tr>
        <td class="title"><b><?php echo $form->labelEx($model,'date_of_burial'); ?></b></td>
        </tr>
        <tr>
        <td colspan="2" class="element">
          <?php echo $form->textField($model,'date_of_burial',array('style'=>'width:308px;','maxlength'=>50, 'class'=>'datepicker')); ?>
          <?php echo $form->dropdownlist($model,'time_of_burial_h',  CommonFunc::hours()); ?>
          <?php echo $form->dropdownlist($model,'time_of_burial_m',  CommonFunc::minutes()); ?>
          <?php echo $form->dropdownlist($model,'time_of_burial_z',  CommonFunc::noon()); ?>
        </td>
        </tr>
        
        <tr>
        <td class="title"><b><?php echo $form->labelEx($model,'burial'); ?></b></td>
        </tr>
        <tr>
        <td colspan="2" class="element">
          <?php echo $form->textField($model,'burial',array('maxlength'=>50, 'style'=>'width:454px;')); ?>
          <br/>
          <span style="font-size:10px;line-height: 12px;">Which Cemetery will burial take place?</span>
        </td>
        </tr>
        
        <tr>
        <td class="title"><b><?php echo $form->labelEx($model,'special_rites'); ?></b></td>
        </tr>
        <tr>
        <td colspan="2" class="element"><?php echo $form->dropDownList($model,'special_rites', DropDown::getSelfDefineOptions('customer', 'specialrites'), array('style'=>'width:454px;','class'=>'Other')); ?></b></td>
        </tr>
        
        <tr>
        <td class="title"><b><?php echo $form->labelEx($model,'church_membership'); ?></b></td>
        </tr>
        <tr>
        <td colspan="2" class="element"><?php echo $form->textField($model,'church_membership',array('maxlength'=>50, 'style'=>'width:454px;')); ?></b></td>
        </tr>
        
        
        
        <tr>
        <td class="title"><b><?php echo $form->labelEx($model,'memorials'); ?></b></td>
        </tr>
        <tr>
        <td colspan="2" class="element">
          <?php echo $form->textArea($model,'memorials',array('cols'=>85, 'rows'=>8)); ?>
          <br/>
          <span style="font-size:10px;line-height: 12px;">Donations in lieu of flowers can be made to... <br/>(this is optional and there can be more than one memorial).</span>
        </td>
        </tr>
        
        <tr></tr><tr></tr><tr></tr><tr></tr><tr></tr>
        
        <tr>
        <td align="left" colspan="4"><b style="font-size: 25px;">Biography</b><hr></td>
        <!--<td colspan="3"></td>-->
        </tr>
        
        <tr>
        <td class="title"><b><?php echo $form->labelEx($model,'fathers_name_f'); ?></b></td>
        </tr>
        <tr>
        <td colspan="2" class="element">
          <?php echo $form->textField($model,'fathers_name_f',array('style'=>'width:149px;','maxlength'=>50)); ?>
          <?php echo $form->textField($model,'fathers_name_m',array('style'=>'width:149px;','maxlength'=>20)); ?>
          <?php echo $form->textField($model,'fathers_name_l',array('style'=>'width:149px;','maxlength'=>50)); ?>
        </td>
        </tr>
        
        <tr>
        <td class="title"><b><?php echo $form->labelEx($model,'mothers_name_f'); ?></b></td>
        </tr>
        <tr>
        <td colspan="2" class="element">
          <?php echo $form->textField($model,'mothers_name_f',array('style'=>'width:149px;','maxlength'=>50)); ?>
          <?php echo $form->textField($model,'mothers_name_m',array('style'=>'width:149px;','maxlength'=>20)); ?>
          <?php echo $form->textField($model,'mothers_name_l',array('style'=>'width:149px;','maxlength'=>50)); ?>
           <br/><span style="font-size:10px;line-height: 12px;">Use Mothers maiden name</span>
        </td>
        </tr>
        
        <tr>
        <td class="title"><b><?php echo $form->labelEx($model,'highest_level_of_education'); ?></b></td>
        </tr>
        <tr>
        <td colspan="2" class="element"><?php echo $form->dropDownList($model,'highest_level_of_education', DropDown::getSelfDefineOptions('customer', 'highestleveleducation'), array('style'=>'width:454px;','class'=>'Other')); ?></b></td>
        <tr>
         
        <tr>
        <td class="title"><b><?php echo $form->labelEx($model,'occupation'); ?></b></td>
        </tr>
        <tr>
        <td colspan="2" class="element">
          <?php echo $form->textArea($model,'occupation',array('cols'=>86,'rows'=>8)); ?>
          <br/>
          <span style="font-size:10px;line-height: 12px;">Include Occupation and Employment history that you want included in the Obituary</span>
        </td>
        </tr>  
         
        <tr>
        <td class="title"><b><?php echo $form->labelEx($model,'biography'); ?></b></td>
        </tr>
        <tr>
        <td colspan="2" class="element">
          <?php echo $form->textArea($model,'biography',array('cols'=>86,'rows'=>8)); ?>
          <br/>
          <span style="font-size:10px;line-height: 12px;">Memberships, Organizations, Interests, Important Information</span>
        </td>
        </tr>
        
        </tr>
        <td class="title"><b><?php echo $form->labelEx($model,'marital_status'); ?></b></td>
        </tr>
        <tr>
        <td colspan="2" class="element"><?php echo $form->dropDownList($model,'marital_status', DropDown::getSelfDefineOptions('customer', 'maritalstatus'), array('style'=>'width:454px;','class'=>'Other')); ?></b></td>
        </tr>
        
        
        
        
       
        <tr>
        <td class="title"><b><?php echo $form->labelEx($model,'spouse_f'); ?></b></td>
        </tr>
        <tr>
        <td colspan="2" class="element">
          <?php echo $form->textField($model,'spouse_f',array('style'=>'width:149px;','maxlength'=>50)); ?>
          <?php echo $form->textField($model,'spouse_m',array('style'=>'width:149px;','maxlength'=>20)); ?>
          <?php echo $form->textField($model,'spouse_l',array('style'=>'width:149px;','maxlength'=>50)); ?>
          <br/>
          <span style="font-size:10px;line-height: 12px;">If applicable</span>
        </td>
        </tr>
        
        
        <tr>
        <td class="title"><b><?php echo $form->labelEx($model,'date_of_marriage'); ?></b></td>
        <td class="title"><b><?php echo $form->labelEx($model,'place_of_marriage'); ?></b></td>
        </tr>
        <tr>
        <td>
          <?php // echo $form->textField($model,'date_of_marriage',array('style'=>'width:202px;','maxlength'=>50, 'class'=>'datepicker')); ?>
          <?php echo $form->textField($model,'date_of_marriage',array('maxlength'=>50, 'class'=>'datepicker', 'style'=>'width:187px;')); ?>
        </td>
        <td>
          <?php // echo $form->textField($model,'place_of_marriage',array('maxlength'=>50, 'style'=>'width:202px;')); ?>
          <?php echo $form->textField($model,'place_of_marriage',array('maxlength'=>50, 'style'=>'width:187px;')); ?>
         </td>
         </tr>
         <tr>
         <td></td>
         <td>
          <span style="font-size:10px;line-height: 12px;">If Place of Marriage is not<br/> known, leave blank.</span>
        </td>
        </tr>
        
        <tr>
        <td class="title"><b><?php echo $form->labelEx($model,'previous_marriage'); ?></b></td>
        <td class="title"><b>Date/Place of Previous Marriage</b></td>
        </tr>
        <tr>
        <td>
          <?php echo $form->textField($model,'previous_marriage',array('maxlength'=>50, 'style'=>'width:187px;')); ?>
        </td>
        <td>
          <?php echo $form->textField($model,'date_of_previous_marriage',array('maxlength'=>50, 'style'=>'width:93px;', 'class'=>'datepicker')); ?>
          <?php echo $form->textField($model,'place_of_previous_marriage',array('maxlength'=>50, 'style'=>'width:93px;')); ?>
         </td>
         </tr>
         <tr>
         <td></td>
         <td>
          <span style="font-size:10px;line-height: 12px;">If Place of Marriage is not<br/> known, leave blank.</span>
        </td>
        </tr>
        
        <tr>
        <td class="title"><b><?php echo $form->labelEx($model,'spouse_date_of_death'); ?></b></td>
        </tr>
        <tr>
        <td colspan="2" class="element"><?php echo $form->textField($model,'spouse_date_of_death',array('style'=>'width:450px;','maxlength'=>50, 'class'=>'datepicker')); ?></b></td>
        </tr>
        
        <tr>
        <td class="title"><b><?php echo $form->labelEx($model,'survived_by'); ?></b></td>
        </tr>
        <tr>
        <td colspan="2" class="element"><?php echo $form->textArea($model,'survived_by',array('cols'=>86,'rows'=>8)); ?></b></td>
        </tr>
        
        <tr>
        <td class="title"><b><?php echo $form->labelEx($model,'preceded_in_death_by'); ?></b></td>
        </tr>
        <tr>
        <td colspan="2" class="element"><?php echo $form->textArea($model,'preceded_in_death_by',array('cols'=>86,'rows'=>8)); ?></b></td>
        </tr>
        
        <tr></tr><tr></tr><tr></tr><tr></tr><tr></tr>
        
        <tr>
        <td align="left" colspan="4" class="title"><b style="font-size: 25px;">Service Related</b><hr></td>
        <!--<td colspan="3"></td>-->
        </tr> 

        <tr>
        <td class="title"><b><?php echo $form->labelEx($model,'newspaper_radio1'); ?></b></td>
        <td class="title"><b><?php echo $form->labelEx($model,'newspaper_radio2'); ?></b></td>
        </tr>
        <tr>
        <td class="element"><?php echo $form->dropDownList($model,'newspaper_radio1', DropDown::getSelfDefineOptions('customer', 'newspaperradio1'), array('style'=>'width:187px;', 'class'=>'Other', 'onchange'=>'showNewspaperRadio1_other(this);')); ?></b></td>
        <td class="element"><?php echo $form->dropDownList($model,'newspaper_radio2', DropDown::getSelfDefineOptions('customer', 'newspaperradio2'), array('style'=>'width:187px;', 'class'=>'Other', 'onchange'=>'showNewspaperRadio2_other(this);')); ?></b></td>
        </tr>
        <tr style="display:none;" id="newspaper_radio_row1_other">
        <td class="element"><span style="display:none;" id="newspaper_radio1_other"><?php echo $form->textField($model,'newspaper_radio1_other', array('style'=>'width:187px;','class'=>'Other')); ?></td>
        <td class="element"><span style="display:none;" id="newspaper_radio2_other"><?php echo $form->textField($model,'newspaper_radio2_other', array('style'=>'width:187px;','class'=>'Other')); ?></td>
        </tr>
        
        <tr>
        <td class="title"><b><?php echo $form->labelEx($model,'newspaper_radio3'); ?></b></td>
        <td class="title"><b><?php echo $form->labelEx($model,'newspaper_radio4'); ?></b></td>
        </tr>
        <tr>
        <td class="element"><?php echo $form->dropDownList($model,'newspaper_radio3', DropDown::getSelfDefineOptions('customer', 'newspaperradio3'), array('style'=>'width:187px;', 'class'=>'Other', 'onchange'=>'showNewspaperRadio3_other(this);')); ?></b></td>
        <td class="element"><?php echo $form->dropDownList($model,'newspaper_radio4', DropDown::getSelfDefineOptions('customer', 'newspaperradio4'), array('style'=>'width:187px;', 'class'=>'Other', 'onchange'=>'showNewspaperRadio4_other(this);')); ?></b></td>
        </tr>
        <tr style="display:none;" id="newspaper_radio_row2_other">
        <td class="element"><span style="display:none;" id="newspaper_radio3_other"><?php echo $form->textField($model,'newspaper_radio3_other', array('style'=>'width:187px;','class'=>'Other')); ?></td>
        <td class="element"><span style="display:none;" id="newspaper_radio4_other"><?php echo $form->textField($model,'newspaper_radio4_other', array('style'=>'width:187px;','class'=>'Other')); ?></td>
        </tr>
        
        <tr>
        <td class="title"><b><?php echo $form->labelEx($model,'newspaper_radio5'); ?></b></td>
        <td class="title"><b><?php echo $form->labelEx($model,'newspaper_radio6'); ?></b></td>
        </tr>
        <tr>
        <td class="element"><?php echo $form->dropDownList($model,'newspaper_radio5', DropDown::getSelfDefineOptions('customer', 'newspaperradio5'), array('style'=>'width:187px;', 'class'=>'Other', 'onchange'=>'showNewspaperRadio5_other(this);')); ?></b></td>
        <td class="element"><?php echo $form->dropDownList($model,'newspaper_radio6', DropDown::getSelfDefineOptions('customer', 'newspaperradio6'), array('style'=>'width:187px;', 'class'=>'Other', 'onchange'=>'showNewspaperRadio6_other(this);')); ?></b></td>
        </tr>
        <tr style="display:none;" id="newspaper_radio_row3_other">
        <td class="element"><span style="display:none;" id="newspaper_radio5_other"><?php echo $form->textField($model,'newspaper_radio5_other', array('style'=>'width:187px;','class'=>'Other')); ?></td>
        <td class="element"><span style="display:none;" id="newspaper_radio6_other"><?php echo $form->textField($model,'newspaper_radio6_other', array('style'=>'width:187px;','class'=>'Other')); ?></td>
        </tr>
        
        <tr>
        <td class="title"><b><?php echo $form->labelEx($model,'submit_pic_with_obit'); ?></b></td>
        </tr>
        <tr>
        <td colspan="2" class="element"><?php echo $form->dropDownList($model,'submit_pic_with_obit', DropDown::getSelfDefineOptions('customer', 'submitpicwithobit'), array('style'=>'width:454px;','class'=>'Other')); ?></b></td>
        </tr>
        
        <tr>
        <td class="title"><b><?php echo $form->labelEx($model,'music_selection1'); ?></b></td>
        </tr>
        <tr>
        <td colspan="2" class="element"><?php echo $form->textField($model,'music_selection1',array('maxlength'=>50, 'style'=>'width:454px;')); ?></b></td>
        </tr>
        
        <tr>
        <td class="title"><b><?php echo $form->labelEx($model,'music_selection2'); ?></b></td>
        </tr>
        <tr>
        <td colspan="2" class="element"><?php echo $form->textField($model,'music_selection2',array('maxlength'=>50, 'style'=>'width:454px;')); ?></b></td>
        </tr>
        
        <tr>
        <td class="title"><b><?php echo $form->labelEx($model,'music_selection3'); ?></b></td>
        </tr>
        <tr>
        <td colspan="2" class="element"><?php echo $form->textField($model,'music_selection3',array('maxlength'=>50, 'style'=>'width:454px;')); ?></b></td>
        </tr>
        
        <tr>
        <td class="title"><b><?php echo $form->labelEx($model,'music_selection4'); ?></b></td>
        </tr>
        <tr>
        <td colspan="2" class="element"><?php echo $form->textField($model,'music_selection4',array('maxlength'=>50, 'style'=>'width:454px;')); ?></b></td>
        </tr>
        
        <tr>
        <td class="title"><b><?php echo $form->labelEx($model,'music_selection5'); ?></b></td>
        </tr>
        <tr>
        <td colspan="2" class="element"><?php echo $form->textField($model,'music_selection5',array('maxlength'=>50, 'style'=>'width:454px;')); ?></b></td>
        </tr>
        
        <tr>
        <td class="title"><b><?php echo $form->labelEx($model,'pallbearers'); ?></b></td>
        </tr>
        <tr>
        <td colspan="2" class="element"><?php echo $form->textField($model,'pallbearers',array('cols'=>86,'rows'=>8)); ?></b></td>
        </tr>
        
        <tr>
        <td class="title"><b><?php echo $form->labelEx($model,'pallbearer2'); ?></b></td>
        </tr>
        <tr>
        <td colspan="2" class="element"><?php echo $form->textField($model,'pallbearer2',array('cols'=>86,'rows'=>8)); ?></b></td>
        </tr>

        <tr>
        <td class="title"><b><?php echo $form->labelEx($model,'pallbearer3'); ?></b></td>
        </tr>
        <tr>
        <td colspan="2" class="element"><?php echo $form->textField($model,'pallbearer3',array('cols'=>86,'rows'=>8)); ?></b></td>
        </tr>

        <tr>
        <td class="title"><b><?php echo $form->labelEx($model,'pallbearer4'); ?></b></td>
        </tr>
        <tr>
        <td colspan="2" class="element"><?php echo $form->textField($model,'pallbearer4',array('cols'=>86,'rows'=>8)); ?></b></td>
        </tr>

        <tr>
        <td class="title"><b><?php echo $form->labelEx($model,'pallbearer5'); ?></b></td>
        </tr>
        <tr>
        <td colspan="2" class="element"><?php echo $form->textField($model,'pallbearer5',array('cols'=>86,'rows'=>8)); ?></b></td>
        </tr>

        <tr>
        <td class="title"><b><?php echo $form->labelEx($model,'pallbearer6'); ?></b></td>
        </tr>
        <tr>
        <td colspan="2" class="element"><?php echo $form->textField($model,'pallbearer6',array('cols'=>86,'rows'=>8)); ?></b></td>
        </tr>

        <tr>
        <td class="title"><b><?php echo $form->labelEx($model,'pallbearer7'); ?></b></td>
        </tr>
        <tr>
        <td colspan="2" class="element"><?php echo $form->textField($model,'pallbearer7',array('cols'=>86,'rows'=>8)); ?></b></td>
        </tr>

        <tr>
        <td class="title"><b><?php echo $form->labelEx($model,'pallbearer8'); ?></b></td>
        </tr>
        <tr>
        <td colspan="2" class="element"><?php echo $form->textField($model,'pallbearer8',array('cols'=>86,'rows'=>8)); ?></b></td>
        </tr>
        
        <tr>
        <td class="title"><b><?php echo $form->labelEx($model,'special_music'); ?></b></td>
        </tr>
        <tr>
        <td colspan="2" class="element"><?php echo $form->textArea($model,'special_music',array('cols'=>86,'rows'=>5)); ?></b></td>
        </tr>

        <tr>
        <td class="title"><b><?php echo $form->labelEx($model,'doctors_name'); ?></b></td>
        <td class="title"><b><?php echo $form->labelEx($model,'ssn'); ?></b></td>
        </tr>
        <tr>
        <td>
          <?php // echo $form->textField($model,'doctors_name',array('maxlength'=>50, 'style'=>'width:454px;')); ?>
          <?php echo $form->textField($model,'doctors_name',array('maxlength'=>50, 'style'=>'width:187px;')); ?>
          <br/>
          <span style="font-size:10px;line-height: 12px;">For Death Certificate. Person most <br/>likely to sign the DC</span>
        </td>
        <td>
          <?php // echo $form->textField($model,'ssn',array('maxlength'=>50, 'style'=>'width:454px;')); ?>
          <?php echo $form->textField($model,'ssn',array('maxlength'=>50, 'style'=>'width:187px;')); ?><br/>
          <span style="font-size:10px;line-height: 12px;">Family Must Verify<br/> For Death Certificate</span>
        </td>
        </tr>

        <tr>
        <td class="title"><b><?php echo $form->labelEx($model,'informant_name_f'); ?></b></td>
        </tr>
        <tr>
        <td colspan="2" class="element">
          <?php echo $form->textField($model,'informant_name_f',array('style'=>'width:225px;','maxlength'=>50)); ?>
          <?php echo $form->textField($model,'informant_name_l',array('style'=>'width:225px;','maxlength'=>50)); ?>
        </td>
        </tr>
        
        <tr>
        <td class="title"><b><?php echo $form->labelEx($model,'informant_name_address'); ?></b></td>
        </tr>
        <tr>
        <td colspan="2" class="element">
          <?php echo $form->textField($model,'informant_name_address',array('maxlength'=>50, 'style'=>'width:454px;')); ?>
          <br/>
          <span style="font-size:10px;line-height: 12px;">For Death Certificate</span>
        </td>
        </tr>
        
        <tr>
        <td class="title"><b><?php echo $form->labelEx($model,'military_veteran'); ?></b></td>
        </tr>
        <tr>
        <td colspan="2" style="padding-bottom: 30px;">
          <?php echo $form->dropDownList($model,'military_veteran', DropDown::getSelfDefineOptions('customer', 'militaryveteran'), array('style'=>'width:454px;','class'=>'Other')); ?>
          <br/>
          <span style="font-size:10px;line-height: 12px;">Present DD 214 to apply for VA Marker</span>
        </td>
        </tr>

      </tbody>
    </table>
  </div>

	<table width="95%" border="0" cellpadding="0" cellspacing="0"><tbody><tr><td align="center"></div>
    <input title="Save" class="button" type="button" name="button" value="Save" onclick="submitNewCustomerForm();"/>
    <input title="Cancel" class="button buttonurl" url="/decedent" pageTitle="Customer" type="button" id="button_cancel2" name="button" value="Cancel"/>
  </td></tr></tbody></table>
  
  
<?php $this->endWidget(); ?>

<?php endif; ?>

</div>


<script type="text/javascript">
 $('#newcustomerform').find('input[type="text"], select').css({height:"29px", padding:"2px 0;"});
 
 function submitNewCustomerForm(){
   clearInterval(interval);
   
   if($('#Customer_full_legal_name').val() != ''){
      $('#autosave').val(0);
      $('#newcustomerform').attr('target', '_self');
      $('#newcustomerform').submit();
   }else{
      $('#Customer_full_legal_name').css('background-color','#f00');
      $('.warning').show();
   }
 }
 
 function autosaveNewCustomerForm(){
   if($('#Customer_full_legal_name').val() != ''){
      $('#autosave').val(1);
      $('#newcustomerform').submit();
   }
 }
 
 var interval = setInterval(autosaveNewCustomerForm, 300000);
// var interval = setInterval(autosaveNewCustomerForm, 10000);
 
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

if($('#Customer_newspaper_radio1').val() == 'Other'){
    $('#newspaper_radio_row1_other').show();
    $('#newspaper_radio1_other').show();
  }
  if($('#Customer_newspaper_radio2').val() == 'Other'){
    $('#newspaper_radio_row1_other').show();
    $('#newspaper_radio2_other').show();
  }
  if($('#Customer_newspaper_radio3').val() == 'Other'){
    $('#newspaper_radio_row2_other').show();
    $('#newspaper_radio3_other').show();
  }
  if($('#Customer_newspaper_radio4').val() == 'Other'){
    $('#newspaper_radio_row2_other').show();
    $('#newspaper_radio4_other').show();
  }
  if($('#Customer_newspaper_radio5').val() == 'Other'){
    $('#newspaper_radio_row3_other').show();
    $('#newspaper_radio5_other').show();
  }
  if($('#Customer_newspaper_radio6').val() == 'Other'){
    $('#newspaper_radio_row3_other').show();
    $('#newspaper_radio6_other').show();
  }
  
  function showNewspaperRadio1_other(newspaper_radio1_otherObj){
    if(newspaper_radio1_otherObj.value == 'Other' && $('#Customer_newspaper_radio2').val() == 'Other'){
      $('#newspaper_radio_row1_other').show();
      $('#newspaper_radio1_other').show();
      $('#newspaper_radio2_other').show();
    }else if(newspaper_radio1_otherObj.value == 'Other' && $('#Customer_newspaper_radio2').val() != 'Other'){
      $('#newspaper_radio_row1_other').show();
      $('#newspaper_radio1_other').show();
      $('#newspaper_radio2_other').hide();
    }else if(newspaper_radio1_otherObj.value != 'Other' && $('#Customer_newspaper_radio2').val() == 'Other'){
      $('#newspaper_radio_row1_other').show();
      $('#newspaper_radio1_other').hide();
      $('#newspaper_radio2_other').show();
    }else{
      $('#newspaper_radio_row1_other').hide();
      $('#newspaper_radio1_other').hide();
      $('#newspaper_radio2_other').hide();
    }
  }
  
  
  function showNewspaperRadio2_other(newspaper_radio2_otherObj){
    if(newspaper_radio2_otherObj.value == 'Other' && $('#Customer_newspaper_radio1').val() == 'Other'){
      $('#newspaper_radio_row1_other').show();
      $('#newspaper_radio2_other').show();
      $('#newspaper_radio1_other').show();
    }else if(newspaper_radio2_otherObj.value == 'Other' && $('#Customer_newspaper_radio1').val() != 'Other'){
      $('#newspaper_radio_row1_other').show();
      $('#newspaper_radio2_other').show();
      $('#newspaper_radio1_other').hide();
    }else if(newspaper_radio2_otherObj.value != 'Other' && $('#Customer_newspaper_radio1').val() == 'Other'){
      $('#newspaper_radio_row1_other').show();
      $('#newspaper_radio2_other').hide();
      $('#newspaper_radio1_other').show();
    }else{
      $('#newspaper_radio_row1_other').hide();
      $('#newspaper_radio2_other').hide();
      $('#newspaper_radio1_other').hide();
    }
  }
  $('#Customer_newspaper_radio2_other').change();
  
  function showNewspaperRadio3_other(newspaper_radio3_otherObj){
    if(newspaper_radio3_otherObj.value == 'Other' && $('#Customer_newspaper_radio4').val() == 'Other'){
      $('#newspaper_radio_row2_other').show();
      $('#newspaper_radio3_other').show();
      $('#newspaper_radio4_other').show();
    }else if(newspaper_radio3_otherObj.value == 'Other' && $('#Customer_newspaper_radio4').val() != 'Other'){
      $('#newspaper_radio_row2_other').show();
      $('#newspaper_radio3_other').show();
      $('#newspaper_radio4_other').hide();
    }else if(newspaper_radio3_otherObj.value != 'Other' && $('#Customer_newspaper_radio4').val() == 'Other'){
      $('#newspaper_radio_row2_other').show();
      $('#newspaper_radio3_other').hide();
      $('#newspaper_radio4_other').show();
    }else{
      $('#newspaper_radio_row2_other').hide();
      $('#newspaper_radio3_other').hide();
      $('#newspaper_radio4_other').hide();
    }
  }
  $('#Customer_newspaper_radio3_other').change();
  
  function showNewspaperRadio4_other(newspaper_radio4_otherObj){
    if(newspaper_radio4_otherObj.value == 'Other' && $('#Customer_newspaper_radio3').val() == 'Other'){
      $('#newspaper_radio_row2_other').show();
      $('#newspaper_radio4_other').show();
      $('#newspaper_radio3_other').show();
    }else if(newspaper_radio4_otherObj.value == 'Other' && $('#Customer_newspaper_radio3').val() != 'Other'){
      $('#newspaper_radio_row2_other').show();
      $('#newspaper_radio4_other').show();
      $('#newspaper_radio3_other').hide();
    }else if(newspaper_radio4_otherObj.value != 'Other' && $('#Customer_newspaper_radio3').val() == 'Other'){
      $('#newspaper_radio_row2_other').show();
      $('#newspaper_radio4_other').hide();
      $('#newspaper_radio3_other').show();
    }else{
      $('#newspaper_radio_row2_other').hide();
      $('#newspaper_radio4_other').hide();
      $('#newspaper_radio3_other').hide();
    }
  }
  $('#Customer_newspaper_radio4_other').change();
  
  function showNewspaperRadio5_other(newspaper_radio5_otherObj){
    if(newspaper_radio5_otherObj.value == 'Other' && $('#Customer_newspaper_radio6').val() == 'Other'){
      $('#newspaper_radio_row3_other').show();
      $('#newspaper_radio5_other').show();
      $('#newspaper_radio6_other').show();
    }else if(newspaper_radio5_otherObj.value == 'Other' && $('#Customer_newspaper_radio6').val() != 'Other'){
      $('#newspaper_radio_row3_other').show();
      $('#newspaper_radio5_other').show();
      $('#newspaper_radio6_other').hide();
    }else if(newspaper_radio5_otherObj.value != 'Other' && $('#Customer_newspaper_radio6').val() == 'Other'){
      $('#newspaper_radio_row3_other').show();
      $('#newspaper_radio5_other').hide();
      $('#newspaper_radio6_other').show();
    }else{
      $('#newspaper_radio_row3_other').hide();
      $('#newspaper_radio5_other').hide();
      $('#newspaper_radio6_other').hide();
    }
  }
  $('#Customer_newspaper_radio5_other').change();
  
  function showNewspaperRadio6_other(newspaper_radio6_otherObj){
    if(newspaper_radio6_otherObj.value == 'Other' && $('#Customer_newspaper_radio5').val() == 'Other'){
      $('#newspaper_radio_row3_other').show();
      $('#newspaper_radio6_other').show();
      $('#newspaper_radio5_other').show();
    }else if(newspaper_radio6_otherObj.value == 'Other' && $('#Customer_newspaper_radio5').val() != 'Other'){
      $('#newspaper_radio_row3_other').show();
      $('#newspaper_radio6_other').show();
      $('#newspaper_radio5_other').hide();
    }else if(newspaper_radio6_otherObj.value != 'Other' && $('#Customer_newspaper_radio5').val() == 'Other'){
      $('#newspaper_radio_row3_other').show();
      $('#newspaper_radio6_other').hide();
      $('#newspaper_radio5_other').show();
    }else{
      $('#newspaper_radio_row3_other').hide();
      $('#newspaper_radio6_other').hide();
      $('#newspaper_radio5_other').hide();
    }
  }
  $('#Customer_newspaper_radio6_other').change();
</script>

<style type="text/css">
  #rwb{
    background-color: #369;
  }
  #rwb #Information{
    background-color: white;
  }
  td{
    align: left;
  }
  .title{
    padding-top: 15px;
    font-size:18px;
  }
  .element_full_legal{
    padding-bottom: 15px;
  }
</style>

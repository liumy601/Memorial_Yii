<div class="bodycontainer"><div id="BodyContent">
<table width="100%" cellspacing="0" cellpadding="0"><tbody><tr><td> 
<div id="leftPanel">
  
<!--buttons-->
<div id="detailViewButtonLayerDiv" class="detailViewButtonLayerDiv" style="z-index: 19; width: 1240px; box-shadow: none; "><table cellpadding="5" cellspacing="0" width="100%" class="dvbtnlayer"><tbody><tr><td align="center" nowrap="" class="pL15"><span appapptagid="16"></span> <a href="javascript:void(0);" onclick="history.back();alert(urlCache+' '+globalId);return false;"><img src="/images/spacer.gif" class="backtoIcon" border="0"></a> </td><td nowrap="" class="pL10 dvmo"> 
<input class="dveditBtn dvcbtn buttonurl" type="button" value="Edit" name="Edit" url="/decedent/update/<?php echo $model->id; ?>" pagetitle="Edit contact" id="editview_student_<?php echo $model->id; ?>"/>
<input name="Delete2" class="dvdelBtn dvcbtn" type="button" value="Delete" url="/decedent/delete/<?php echo $model->id ?>" onclick="if(confirm('Are you sure delete this record?')){ ajaxRequest(this); }">
&nbsp;
</td>
<td width="100" class="dvmo"> </td>
<td> </td>
<td width="90%"> 
  <input style="float:right;" name="download_customer" class="button" type="button" value="Guest Book Export"  onclick="customers_export(<?php echo $model->id ?>);">
</td>
</tr></tbody></table></div>
  
  
<!--view object-->
<div class="p15 mt5">
<div>
<div class="maininfo"><span id="cutomizebc"></span> 
<!--<span id="headervalue_Account Name" class="dvTitle">Customer: <?php // echo CHtml::encode($model->full_legal_name_f . ' '. $model->full_legal_name_m .' '. $model->full_legal_name_l); ?></span>--> 
<span id="headervalue_Account Name" class="dvTitle">Decedent: <?php echo CHtml::encode($model->full_legal_name); ?></span> 
<span id="headervalue_Website" class="dvSubTitle"><span id="subvalue1_Website"></span></span> <br>

<?php
  if ($model->assigned_to) {
    $assignedToUser = Users::model()->findByPk($model->assigned_to);
  }

 if (!Yii::app()->params['print']) {
?>
<div style="float:right">
  <a href="javascript:void window.open('/decedent/view/id/<?php echo $model->id ?>/print/true','printwin','menubar=1,status=0,resizable=1,scrollbars=1,toolbar=0,location=1')" class="utilsLink"><img src="/themes/Sugar/images/print.gif" width="13" height="13" alt="Print" border="0" align="absmiddle"></a>
  <a href="javascript:void window.open('/decedent/view/id/<?php echo $model->id ?>/print/true','printwin','menubar=1,status=0,resizable=1,scrollbars=1,toolbar=0,location=1')" class="utilsLink">Print</a>
</div>
<?php
 }
?>

<br/><br/>
<table id="secHeadLead Information" width="95%" cellspacing="0" cellpadding="0">
  <tbody width="100%">
    <tr><td class="secHead">Information</td></tr>
  </tbody>
</table>
<table width="100%" style="display:block"><!--detail-->
  <tbody>
        <tr>
        <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('deceased_photo')); ?></td>
        <td width="30%" class="dvValueTxt">
          <?php if(!empty($model->deceased_photo)){ ?>
          <a href="/<?php echo CHtml::encode($model->deceased_photo); ?>" target="_blank"><img src="/<?php echo CHtml::encode($model->deceased_photo); ?>" width="40" height="20"></a>
          <?php } ?>
        </td>
        </tr>
        
        <tr>
        <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('case_number')); ?></td>
        <td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->case_number); ?></td>
        <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('status')); ?></td>
        <td width="30%" class="dvValueTxt"><?php echo $model->status; ?></td>
        </tr>
        
        <tr>
        <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('skfh_funeral_home')); ?></td>
        <td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->skfh_funeral_home); ?></td>
        <td width="20%" class="dvgrayTxt">Full Legal Name</td>
        <td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->full_legal_name); ?></td>
        </tr>
		
		<?php if($optionFields->full_legal_name_f) : ?>
			<tr>
			<td width="20%" class="dvgrayTxt"></td>
			<td width="30%" class="dvValueTxt"></td>
			<td width="20%" class="dvgrayTxt"><?php echo $model->getAttributeLabel('full_legal_name_f'); ?></td>
			<td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->full_legal_name_f); ?></td>
			</tr>
		<?php endif; ?>

		<?php if($optionFields->full_legal_name_m) : ?>
			<tr>
			<td width="20%" class="dvgrayTxt"></td>
			<td width="30%" class="dvValueTxt"></td>
			<td width="20%" class="dvgrayTxt"><?php echo $model->getAttributeLabel('full_legal_name_m'); ?></td>
			<td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->full_legal_name_m); ?></td>
			</tr>
		<?php endif; ?>

		<?php if($optionFields->full_legal_name_l) : ?>
			<tr>
			<td width="20%" class="dvgrayTxt"></td>
			<td width="30%" class="dvValueTxt"></td>
			<td width="20%" class="dvgrayTxt"><?php echo $model->getAttributeLabel('full_legal_name_l'); ?></td>
			<td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->full_legal_name_l); ?></td>
			</tr>
		<?php endif; ?>

		<?php if($optionFields->full_legal_prefix) : ?>
			<tr>
			<td width="20%" class="dvgrayTxt"></td>
			<td width="30%" class="dvValueTxt"></td>
			<td width="20%" class="dvgrayTxt"><?php echo $model->getAttributeLabel('full_legal_prefix'); ?></td>
			<td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->full_legal_prefix); ?></td>
			</tr>
		<?php endif; ?>
        
        <tr>
        <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('name_for_obituary')); ?></td>
        <td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->name_for_obituary); ?></td>
        <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('age')); ?></td>
        <td width="30%" class="dvValueTxt"><?php echo $model->calAge(); ?></td>
        </tr>
        
        <tr>
        <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('address')); ?></td>
        <td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->address); ?></td>
        <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('sex')); ?></td>
        <td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->sex); ?></td>
        </tr>
        
        <tr>
        <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('state')); ?></td>
        <td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->state); ?></td>
        <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('zip')); ?></td>
        <td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->zip); ?></td>
        </tr>
        
        <tr>
        <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('formerly_of')); ?></td>
        <td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->formerly_of); ?></td>
        <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('city')); ?></td>
        <td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->city); ?></td>
        </tr>
        
<!--        <tr>
        <td width="20%" class="dvgrayTxt"><?php // echo CHtml::encode($model->getAttributeLabel('country')); ?></td>
        <td width="30%" class="dvValueTxt"><?php // echo CHtml::encode($model->country); ?></td>
        </tr>-->
        
        <tr>
        <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('date_of_birth')); ?></td>
        <!--<td width="30%" class="dvValueTxt"><?php // echo CHtml::encode($model->formatDateTimeWithWeekday('date_of_birth', 'time_of_birth', 'zone_of_birth')); ?></td>-->
        <td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->formatDateTimeWithWeekday1('date_of_birth')); ?></td>
        <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('place_of_birth')); ?></td>
        <td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->place_of_birth); ?></td>
        </tr>
		
		<?php if($optionFields->city_of_birth) : ?>
			<tr>
			<td width="20%" class="dvgrayTxt"></td>
			<td width="30%" class="dvValueTxt"></td>
			<td width="20%" class="dvgrayTxt"><?php echo $model->getAttributeLabel('city_of_birth'); ?></td>
			<td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->city_of_birth); ?></td>
			</tr>
		<?php endif; ?>

		<?php if($optionFields->state_of_birth) : ?>
			<tr>
			<td width="20%" class="dvgrayTxt"></td>
			<td width="30%" class="dvValueTxt"></td>
			<td width="20%" class="dvgrayTxt"><?php echo $model->getAttributeLabel('state_of_birth'); ?></td>
			<td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->state_of_birth); ?></td>
			</tr>
		<?php endif; ?>

        <tr>
        <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('date_of_death')); ?></td>
        <!--<td width="30%" class="dvValueTxt"><?php // echo CHtml::encode($model->formatDateTime('date_of_death', 'time_of_death', 'zone_of_death')); ?></td>-->
        <td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->formatDateTime('date_of_death', 'time_of_death')); ?></td>
        <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('place_of_death')); ?></td>
        <td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->place_of_death); ?></td>
        </tr>

		<?php if($optionFields->pod_facility_name) : ?>
			<tr>
			<td width="20%" class="dvgrayTxt"></td>
			<td width="30%" class="dvValueTxt"></td>
			<td width="20%" class="dvgrayTxt"><?php echo $model->getAttributeLabel('pod_facility_name'); ?></td>
			<td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->pod_facility_name); ?></td>
			</tr>
		<?php endif; ?>

		<?php if($optionFields->pod_facility_street) : ?>
			<tr>
			<td width="20%" class="dvgrayTxt"></td>
			<td width="30%" class="dvValueTxt"></td>
			<td width="20%" class="dvgrayTxt"><?php echo $model->getAttributeLabel('pod_facility_street'); ?></td>
			<td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->pod_facility_street); ?></td>
			</tr>
		<?php endif; ?>

		<?php if($optionFields->pod_facility_city) : ?>
			<tr>
			<td width="20%" class="dvgrayTxt"></td>
			<td width="30%" class="dvValueTxt"></td>
			<td width="20%" class="dvgrayTxt"><?php echo $model->getAttributeLabel('pod_facility_city'); ?></td>
			<td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->pod_facility_city); ?></td>
			</tr>
		<?php endif; ?>

		<?php if($optionFields->pod_facility_state) : ?>
			<tr>
			<td width="20%" class="dvgrayTxt"></td>
			<td width="30%" class="dvValueTxt"></td>
			<td width="20%" class="dvgrayTxt"><?php echo $model->getAttributeLabel('pod_facility_state'); ?></td>
			<td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->pod_facility_state); ?></td>
			</tr>
		<?php endif; ?>

		<?php if($optionFields->pod_facility_zip) : ?>
			<tr>
			<td width="20%" class="dvgrayTxt"></td>
			<td width="30%" class="dvValueTxt"></td>
			<td width="20%" class="dvgrayTxt"><?php echo $model->getAttributeLabel('pod_facility_zip'); ?></td>
			<td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->pod_facility_zip); ?></td>
			</tr>
		<?php endif; ?>

        <tr></tr><tr></tr><tr></tr><tr></tr><tr></tr>
        <tr>
        <td class="secHead">Funeral Services</td>
        <td class="secHead"></td>
        <td class="secHead"></td>
        <td class="secHead"></td>
        </tr>
        
        <tr>
        <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('officiant')); ?></td>
        <td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->officiant); ?></td>
        <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('clergy_full_name2')); ?></td>
        <td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->clergy_full_name2); ?></td>
        </tr>
        
        <tr>
        <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('funeral_service_date')); ?></td>
        <!--<td width="30%" class="dvValueTxt"><?php // echo CHtml::encode($model->formatDateTime('funeral_service_date', 'funeral_service_time')); ?></td>-->
        <td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->formatDateTimeFuneral('funeral_service_date', 'funeral_service_time')); ?></td>
        <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('location_of_funeral_service')); ?></td>
        <td width="30%" class="dvValueTxt">
          <?php 
            if($model->location_of_funeral_service != 'Other'){
              echo CHtml::encode($model->location_of_funeral_service);
            }else{
              echo CHtml::encode($model->location_of_funeral_service_other);
            }
          ?>
        </td>
        </tr>

        <tr>
        <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('date_of_visitation_start')); ?></td>
        <td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->formatDateTimeStart('date_of_visitation_start', 'visitation_time')); ?></td>
        <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('location_of_visitation')); ?></td>
        <td width="30%" class="dvValueTxt">
          <?php 
           if($model->location_of_visitation != 'Other'){
             echo CHtml::encode($model->location_of_visitation);
           }else{
             echo CHtml::encode($model->location_of_visitation_other);
           }
         ?>
        </td>
        </tr>

        <tr>
        <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('disposition_type')); ?></td>
        <td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->disposition_type); ?></td>
        <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('date_of_visitation_end')); ?></td>
        <td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->formatDateTimeEnd('date_of_visitation_end', 'visitation_time')); ?></td>
        </tr>
        
        <tr>
        <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('disposition_date')); ?></td>
        <td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->disposition_date); ?></td>
        <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('disposition_place')); ?></td>
        <td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->disposition_place); ?></td>
        </tr>
        
        <tr>
        <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('date_of_burial')); ?></td>
        <td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->formatDateTime('date_of_burial', 'time_of_burial')); ?></td>
        </tr>

        <tr>
        <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('special_rites')); ?></td>
        <td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->special_rites); ?></td>
        <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('church_membership')); ?></td>
        <td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->church_membership); ?></td>
        </tr>
        
        <tr>
        <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('burial')); ?></td>
        <td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->burial); ?></td>
        <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('memorials')); ?></td>
        <td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->memorials); ?></td>
        </tr>
        
        <tr>
        <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('interment_city')); ?></td>
        <td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->interment_city); ?></td>
        <td width="20%" class="dvgrayTxt"> </td>
        <td width="30%" class="dvValueTxt"> </td>
        </tr>
        <tr>
        <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('interment_country')); ?></td>
        <td width="30%" class="dvValueTxt"><?php $countries = CommonFunc::countries(); $countries[''] = ''; echo CHtml::encode($countries[$model->interment_country]); ?></td>
        <td width="20%" class="dvgrayTxt"> </td>
        <td width="30%" class="dvValueTxt"> </td>
        </tr>
        <tr>
        <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('interment_state')); ?></td>
        <td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->interment_state); ?></td>
        <td width="20%" class="dvgrayTxt"> </td>
        <td width="30%" class="dvValueTxt"> </td>
        </tr>
        
		<?php if($optionFields->interment_street) : ?>
			<tr>
			<td width="20%" class="dvgrayTxt"><?php echo $model->getAttributeLabel('interment_street'); ?></td>
			<td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->interment_street); ?></td>
			<td width="20%" class="dvgrayTxt"></td>
			<td width="30%" class="dvValueTxt"></td>
			</tr>
		<?php endif; ?>

		<?php if($optionFields->interment_zip) : ?>
			<tr>
			<td width="20%" class="dvgrayTxt"><?php echo $model->getAttributeLabel('interment_zip'); ?></td>
			<td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->interment_zip); ?></td>
			<td width="20%" class="dvgrayTxt"></td>
			<td width="30%" class="dvValueTxt"></td>
			</tr>
		<?php endif; ?>
        
        <tr></tr><tr></tr><tr></tr><tr></tr><tr></tr>
        
        <tr>
        <td class="secHead">Biography</td>
        <td class="secHead"></td>
        <td class="secHead"></td>
        <td class="secHead"></td>
        </tr>
        
        <tr>
        <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('fathers_name_f')); ?></td>
        <td width="30%" class="dvValueTxt">
          <?php echo CHtml::encode($model->fathers_name_f); ?>
          <?php echo CHtml::encode($model->fathers_name_m); ?>
          <?php echo CHtml::encode($model->fathers_name_l); ?>
        </td>
        <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('mothers_name_f')); ?></td>
        <td width="30%" class="dvValueTxt">
          <?php echo CHtml::encode($model->mothers_name_f); ?>
          <?php echo CHtml::encode($model->mothers_name_m); ?>
          <?php echo CHtml::encode($model->mothers_name_l); ?>
        </td>
        </tr>

        <tr>
        <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('highest_level_of_education')); ?></td>
        <td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->highest_level_of_education); ?></td>
        <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('marital_status')); ?></td>
        <td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->marital_status); ?></td>
        </tr>

        <tr>
        <td width="20%" class="dvgrayTxt" valign="top"><?php echo CHtml::encode($model->getAttributeLabel('occupation')); ?></td>
        <td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->occupation); ?></td>
        <td width="20%" class="dvgrayTxt" valign="top"><?php echo CHtml::encode($model->getAttributeLabel('biography')); ?></td>
        <td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->biography); ?></td>
        </tr>
        
        <tr>
        <td width="20%" class="dvgrayTxt" valign="top"><?php echo CHtml::encode($model->getAttributeLabel('veteran_status')); ?></td>
        <td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->veteran_status); ?></td>
        <td width="20%" class="dvgrayTxt" valign="top"><?php echo CHtml::encode($model->getAttributeLabel('branch')); ?></td>
        <td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->branch); ?></td>
        </tr>
        
        <tr>
        <td width="20%" class="dvgrayTxt" valign="top"><?php echo CHtml::encode($model->getAttributeLabel('full_military_rites')); ?></td>
        <td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->full_military_rites); ?></td>
        </tr>

		<?php if($optionFields->veteran_serial_number) : ?>
			<tr>
			<td width="20%" class="dvgrayTxt"><?php echo $model->getAttributeLabel('veteran_serial_number'); ?></td>
			<td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->veteran_serial_number); ?></td>
			<td width="20%" class="dvgrayTxt"></td>
			<td width="30%" class="dvValueTxt"></td>
			</tr>
		<?php endif; ?>

        <tr>
        <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('spouse_f')); ?></td>
        <td width="30%" class="dvValueTxt">
          <?php echo CHtml::encode($model->spouse_f); ?>
          <?php echo CHtml::encode($model->spouse_m); ?>
          <?php echo CHtml::encode($model->spouse_l); ?>
        </td>
        <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('spouse_date_of_death')); ?></td>
        <td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->spouse_date_of_death); ?></td>
        </tr>

        <tr>
        <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('date_of_marriage')); ?></td>
        <td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->date_of_marriage); ?></td>
        <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('place_of_marriage')); ?></td>
        <td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->place_of_marriage); ?></td>
        </tr>

        <tr>
        <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('previous_marriage')); ?></td>
        <td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->previous_marriage); ?></td>
        <td width="20%" class="dvgrayTxt">Date/Place of Previous Marriage</td>
        <td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->date_of_previous_marriage); ?>  <?php echo CHtml::encode($model->place_of_previous_marriage); ?></td>
        </tr>
        
        <tr>
        <td width="20%" class="dvgrayTxt" valign="top"><?php echo CHtml::encode($model->getAttributeLabel('survived_by')); ?></td>
        <td width="30%" class="dvValueTxt"><?php echo nl2br($model->survived_by); ?></td>
        <td width="20%" class="dvgrayTxt" valign="top"><?php echo CHtml::encode($model->getAttributeLabel('preceded_in_death_by')); ?></td>
        <td width="30%" class="dvValueTxt"><?php echo nl2br($model->preceded_in_death_by); ?></td>
        </tr>

        <tr></tr><tr></tr><tr></tr><tr></tr><tr></tr>
        
        <tr>
        <td class="secHead">Service Related</td>
        <td class="secHead"></td>
        <td class="secHead"></td>
        <td class="secHead"></td>
        </tr> 
        
        <tr>
        <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('newspaper_radio1')); ?></td>
        <td width="30%" class="dvValueTxt">
          <?php 
            if($model->newspaper_radio1 != 'Other'){
              echo nl2br($model->newspaper_radio1);
            }else{
              echo nl2br($model->newspaper_radio1_other);
            }
          ?>
        </td>
        <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('newspaper_radio2')); ?></td>
        <td width="30%" class="dvValueTxt">
          <?php 
            if($model->newspaper_radio2 != 'Other'){
              echo nl2br($model->newspaper_radio2);
            }else{
              echo nl2br($model->newspaper_radio2_other);
            }
          ?>
        </td> 
        </tr>
        
        <tr>
        <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('newspaper_radio3')); ?></td>
        <td width="30%" class="dvValueTxt">
          <?php 
            if($model->newspaper_radio3 != 'Other'){
              echo nl2br($model->newspaper_radio3);
            }else{
              echo nl2br($model->newspaper_radio3_other);
            }
          ?>
        </td>
        <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('newspaper_radio4')); ?></td>
        <td width="30%" class="dvValueTxt">
          <?php 
            if($model->newspaper_radio4 != 'Other'){
              echo nl2br($model->newspaper_radio4);
            }else{
              echo nl2br($model->newspaper_radio4_other);
            }
          ?>
        </td> 
        </tr>
        
        <tr>
        <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('newspaper_radio5')); ?></td>
        <td width="30%" class="dvValueTxt">
          <?php 
            if($model->newspaper_radio5 != 'Other'){
              echo nl2br($model->newspaper_radio5);
            }else{
              echo nl2br($model->newspaper_radio5_other);
            }
          ?>
        </td>
        <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('newspaper_radio6')); ?></td>
        <td width="30%" class="dvValueTxt">
          <?php 
            if($model->newspaper_radio6 != 'Other'){
              echo nl2br($model->newspaper_radio6);
            }else{
              echo nl2br($model->newspaper_radio6_other);
            }
          ?>
        </td> 
        </tr>
        
        <tr>
        <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('submit_pic_with_obit')); ?></td>
        <td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->submit_pic_with_obit); ?></td>
        </tr>
        
        <tr>
        <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('music_selection1')); ?></td>
        <td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->music_selection1); ?></td>
        <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('music_selection2')); ?></td>
        <td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->music_selection2); ?></td>
        </tr>
        
        <tr>
        <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('music_selection3')); ?></td>
        <td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->music_selection3); ?></td>
        <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('music_selection4')); ?></td>
        <td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->music_selection4); ?></td>
        </tr>
        
        <tr>
        <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('music_selection5')); ?></td>
        <td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->music_selection5); ?></td>
        <td width="20%" class="dvgrayTxt"></td>
        <td width="30%" class="dvValueTxt"></td>
        </tr>
        
        <tr>
        <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('pallbearers')); ?></td>
        <td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->pallbearers); ?></td>
        <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('pallbearer2')); ?></td>
        <td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->pallbearer2); ?></td>
        </tr>
        
        <tr>
        <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('pallbearer3')); ?></td>
        <td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->pallbearer3); ?></td>
        <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('pallbearer4')); ?></td>
        <td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->pallbearer4); ?></td>
        </tr>
        
        <tr>
        <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('pallbearer5')); ?></td>
        <td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->pallbearer5); ?></td>
        <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('pallbearer6')); ?></td>
        <td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->pallbearer6); ?></td>
        </tr>
        <tr>
        <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('pallbearer7')); ?></td>
        <td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->pallbearer7); ?></td>
        <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('pallbearer8')); ?></td>
        <td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->pallbearer8); ?></td>
        </tr>
        
        <tr>
        <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('special_music')); ?></td>
        <td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->special_music); ?></td>
        <td width="20%" class="dvgrayTxt"></td>
        <td width="30%" class="dvValueTxt"></td>
        </tr>
        
        <tr>
        <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('doctors_name')); ?></td>
        <td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->doctors_name); ?></td>
        <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('ssn')); ?></td>
		<td width="30%" class="dvValueTxt">
			<?php 
			if(strlen($model->ssn) > 3) {
				$hidedSSN = preg_replace('/\w/i', 'x', substr($model->ssn,3));
				echo CHtml::link(substr($model->ssn,0,3). $hidedSSN, '#',array('onclick'=>'$(this).text("'.$model->ssn.'");return false;'));
			} else {
				echo CHtml::encode($model->ssn);
			}
			?>
		</td>
        </tr>

		<?php if($optionFields->doctor_street) : ?>
			<tr>
			<td width="20%" class="dvgrayTxt"><?php echo $model->getAttributeLabel('doctor_street'); ?></td>
			<td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->doctor_street); ?></td>
			<td width="20%" class="dvgrayTxt"></td>
			<td width="30%" class="dvValueTxt"></td>
			</tr>
		<?php endif; ?>

		<?php if($optionFields->doctor_city) : ?>
			<tr>
			<td width="20%" class="dvgrayTxt"><?php echo $model->getAttributeLabel('doctor_city'); ?></td>
			<td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->doctor_city); ?></td>
			<td width="20%" class="dvgrayTxt"></td>
			<td width="30%" class="dvValueTxt"></td>
			</tr>
		<?php endif; ?>

		<?php if($optionFields->doctor_state) : ?>
			<tr>
			<td width="20%" class="dvgrayTxt"><?php echo $model->getAttributeLabel('doctor_state'); ?></td>
			<td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->doctor_state); ?></td>
			<td width="20%" class="dvgrayTxt"></td>
			<td width="30%" class="dvValueTxt"></td>
			</tr>
		<?php endif; ?>

		<?php if($optionFields->doctor_zip) : ?>
			<tr>
			<td width="20%" class="dvgrayTxt"><?php echo $model->getAttributeLabel('doctor_zip'); ?></td>
			<td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->doctor_zip); ?></td>
			<td width="20%" class="dvgrayTxt"></td>
			<td width="30%" class="dvValueTxt"></td>
			</tr>
		<?php endif; ?>
        
        <tr>
        <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('informant_name_f')); ?></td>
        <td width="30%" class="dvValueTxt">
          <?php echo CHtml::encode($model->informant_name_f); ?>
          <?php echo CHtml::encode($model->informant_name_l); ?>
        </td>
        <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('informant_relationship')); ?></td>
        <td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->informant_relationship); ?></td>
        </tr>
        
        <tr>
        <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('informant_name_address')); ?></td>
        <td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->informant_name_address); ?></td>
        <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('informant_phone')); ?></td>
        <td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->informant_phone); ?></td>
        </tr>
        
        <tr>
        <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('informant_name_city')); ?></td>
        <td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->informant_name_city); ?></td>
        <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('informant_name_state')); ?></td>
        <td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->informant_name_state); ?></td>
        </tr>
        
        <tr>
        <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('informant_name_country')); ?></td>
        <td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->informant_name_country); ?></td>
        <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('informant_name_zip')); ?></td>
        <td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->informant_name_zip); ?></td>
        </tr>
        
        <tr>
        <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('military_veteran')); ?></td>
        <td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->military_veteran); ?></td>
        <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('assigned')); ?></td>
        <td width="30%" class="dvValueTxt"><?php echo CHtml::encode($assignedToUser->username); ?></td>
        </tr>
        
        <tr>
          <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('enteredby')); ?></td>
          <td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->enteredby); ?></td>
          <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('enteredtm')); ?></td>
          <td width="30%" class="dvValueTxt"><?php echo date("m/d/Y H:i:s", $model->enteredtm); ?></td>
        </tr>
  </tbody>
</table>



<br/><br/>
<!--Products-->
<div id="productlist-wrapper">
<table border="0" width="95%" align="center" cellspacing="0" cellpadding="0" class="relCenterBg" id="productlist"><tbody><tr height="25">
<td width="140" nowrap=""> 
<a href="javascript:toggleRelatedList('relationship_product_<?php echo $model->id; ?>');" 
   name="relationship_product_<?php echo $model->id; ?>" id="relationship_product_<?php echo $model->id; ?>" class="relListHead">
Products</a> <span appapptagid="17"></span>
</td>
<td align="right"> <div id="ract_<?php echo $model->id; ?>" name="raction"> </td>
</tr></tbody>
</table>
<div id="product_<?php echo $model->id; ?>" style="display:block">
<table border="0" width="95%" cellspacing="0" cellpadding="0" align="center">
<tbody><tr><td colspan="7"> 
<table border="0" width="100%" cellspacing="0" cellpadding="0" class="rellist">
<tbody><tr class="tableHeadBg"><td>&nbsp;</td> 
<td nowrap>Name</td>
<td nowrap>Vendor</td>
<td nowrap>SKU</td>
<td nowrap>Retail</td>
<!--<td nowrap>Cost</td>-->
<td nowrap>Category</td>
<td nowrap>Taxable</td>
</tr>
<?php
$totalTax = 0;
$totalPrice = 0;
while ($row = $productDataProvider->read()) {
  $totalPrice += $row['retail'];
  if($row['taxable'] == 1){
    $totalTax += $row['retail'];
    $tax = $totalTax * $taxRate;
  }
?>
<tr>
<td nowrap="" width="12%" class="tableData"> <div align="left">&nbsp;
  <a class="sl" href="/decedent/editproduct/<?php echo $row['product_id']; ?>?from=customer">Edit</a>
  <span class="sep">|</span> 
  <a href="#" class="listViewTdToolsS1 noajax" onclick="if(confirm('Are you sure delete this product?')){ deleteProduct(this, <?php echo $row['product_id'] ?>); } return false;">Remove</a>
</div>
</td>
<td class="tableData"> <?php echo $row['name'] ?> </td>
<td class="tableData"> <?php echo $row['vendor'] ?> </td>
<td class="tableData"> <?php echo $row['sku'] ?> </td>
<td class="tableData" id="retail_<?php echo $row['product_id'];?>">$<span id="product_retail1_<?php echo $row['product_id']; ?>" style="color:red;" onclick="alterInput(<?php echo $row['product_id'];?>,<?php echo $model->id;?>);"> <?php 
     echo number_format($row['retail'], 2);
  ?></span>
</td>
<!--<td class="tableData"> <?php // echo $row['cost'] ? '$'.$row['cost'] : '' ?> </td>-->
<td class="tableData"> <?php echo $row['category'] ?> </td>
<td class="tableData"><?php echo CHtml::checkBox('taxable', $row['taxable'], array('disabled'=>true))?></td>
</tr>
<?php
}
?>

</tbody></table>
</td></tr>

 <tr class="rellistbL">
   <td colspan="5"></td>
   <td style="text-align: right;" width="10%">Total:</td>  
   <td width="7%">$<span id="span_total_price"><?php echo number_format($totalPrice + $tax - $discount, 2); ?></span></td>
 </tr>
 <tr class="rellistbL">
  <td align="left" colspan="0">
    <div id="ra">
     <a href="#" class="rellistNew" class="noajax" onclick="dialogAddProduct();return false;">Add product</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
     <a href="#" class="rellistNew" class="noajax" onclick="dialogAddPackage();return false;">Add package</a>
    </div>
  </td>
  <td colspan="4"></td>
  <td align="right">Received:</td>
  <td> $<span id="span_payment"><?php echo number_format($total_payments, 2); ?></span></td>
 </tr>
 <tr class="rellistbL">
   <td colspan="5"></td><td><span style="text-align: right;">Balance Due:</span></td>  <td>  $<span id="span_total_price"><?php echo number_format($totalPrice + $tax - $total_payments - $tatal_balances, 2); ?></span></td>
 </tr>
 

<!-- Field Data goes here --></tbody></table>
</div>
</div>
<!--Products --------end-->


<br/><br/>
<!--Payments-->
<table border="0" width="95%" align="center" cellspacing="0" cellpadding="0" class="relCenterBg" id="paymentlist"><tbody><tr height="25">
<td width="140" nowrap=""> 
<a href="javascript:toggleRelatedList('relationship_payment_<?php echo $model->id; ?>');" 
   name="relationship_payment_<?php echo $model->id; ?>" id="relationship_payment_<?php echo $model->id; ?>" class="relListHead">
Payments</a> <span appapptagid="18"></span>
</td>
<td align="right"> <div id="ract_<?php echo $model->id; ?>" name="raction"> </td>
</tr></tbody>
</table>
<div id="payment_<?php echo $model->id; ?>" style="display:block">
<table border="0" width="95%" cellspacing="0" cellpadding="0" align="center">
<tbody><tr><td colspan="2"> 
<table border="0" width="100%" cellspacing="0" cellpadding="0" class="rellist">
<tbody><tr class="tableHeadBg"><td>&nbsp;</td> 
<td nowrap>Type</td>
<td nowrap>Payer</td>
<td nowrap>Amount</td>
<td nowrap>Payment Method</td>
<td nowrap>Date</td>
<td nowrap>Reason</td>
<td nowrap>Receipt</td>
<td nowrap></td>
</tr>
<?php
while ($row = $paymentDataProvider->read()) {
?>
<tr>
<td nowrap="" width="12%" class="tableData"> <div align="left">&nbsp;
  <a class="sl noajax" href="/decedent/editpayment/<?php echo $row['id']; ?>?from=customer">Edit</a>
  <span class="sep">|</span> 
  <a href="#" class="listViewTdToolsS1 noajax" onclick="if(confirm('Are you sure delete this record?')){ deletePayment(this, <?php echo $row['id'] ?>); } return false;">Remove</a>
</div>
</td>
<td class="tableData"> <?php echo $row['type']=='credit' ? 'discount' : $row['type']; ?> </td>
<td class="tableData"> <?php echo $row['payer']; ?> </td>
<td class="tableData"> $<?php echo $row['amount']; ?> </td>
<td class="tableData"> <?php echo $row['payment_method']; ?> </td>
<td class="tableData"> <?php echo $row['date']; ?> </td>
<td class="tableData"> <?php echo nl2br($row['reason']); ?> </td>
<td class="tableData"><a href="#" onclick="dialogReceipt(<?php echo $model->id; ?>, <?php echo $row['id'];?>, '<?php echo $row['type']=='credit' ? 'discount' : $row['type']; ?>');return false;">receipt</a></td>
<td class="tableData"><a href="#" onclick="print_receipt(<?php echo $model->id; ?>, <?php echo $row['id'];?>, '<?php echo $row['type']=='credit' ? 'discount' : $row['type']; ?>');return false;">Print</a></td>
</tr>
<?php
}
?>

</tbody></table>
</td></tr>
<tr class="rellistbL">
 <td align="left">
  <div id="ra">
   <a href="/decedent/addpayment/<?php echo $model->id; ?>" class="rellistNew noajax">Add payment</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
   <a href="/decedent/addcredit/<?php echo $model->id; ?>" class="rellistNew noajax">Add discount</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  </div>
 </td> 
</tr>

<!-- Field Data goes here --></tbody></table>
</div>
<!--Payments --------end-->


<br/><br/>
<!--Document-------->
<table border="0" width="95%" align="center" cellspacing="0" cellpadding="0" class="relCenterBg" id="documentslist"><tbody><tr height="25">
<td width="140" nowrap=""> 
<a href="javascript:toggleRelatedList('relationship_document_<?php echo $model->id; ?>');" 
   name="relationship_document_<?php echo $model->id; ?>" id="relationship_document_<?php echo $model->id; ?>" class="relListHead">
Documents</a> <span appapptagid="19"></span>
</td>
<td align="right"> <div id="ract_<?php echo $model->id; ?>" name="raction"> </td>
</tr></tbody>
</table>
<div id="document_<?php echo $model->id; ?>" style="display:block">
<table border="0" width="95%" cellspacing="0" cellpadding="0" align="center">
<tbody><tr><td colspan="2"> 
<table border="0" width="100%" cellspacing="0" cellpadding="0" class="rellist">
<tbody><tr class="tableHeadBg"><td>&nbsp;</td> 
<td nowrap>Name</td>
<td nowrap>Email</td>
<td nowrap>Download</td>
<td nowrap>Send email</td>
</tr>
<?php
if($documents){
  foreach ($documents as $template_id => $row) {
?>
<tr>
<td nowrap="" width="12%" class="tableData"> <div align="left">&nbsp;
<?php if(!$row['product_id']){ ?>
  <a href="#" class="listViewTdToolsS1 noajax" onclick="if(confirm('Are you sure delete this document?')){ deleteDocument(this, <?php echo $row['id'] ?>); } return false;">Remove</a>
<?php } else { ?>
  <span class="gray">Relate to product</span> <?php // echo $row['product_name'] ?>
<?php } ?>
</div>
</td>
<td class="tableData"> <?php echo CHtml::link($row['name'], '/template/'.$row['template_id']); ?> </td>
<td class="tableData"> <span class="doc_email"><?php echo $row['email_address_alt'] ? $row['email_address_alt'] : $row['email_address'] ?></span> (<a href="#" class="noajax" onclick="documentChangeEmailAddr(<?php echo $row['id'] ?>, this);return false;">Edit</a>) </td>
<td class="tableData"> 
	<?php if(empty($row['template_id'])): ?>
		<a href="/decedent/docdownload/<?php echo $row['id']; ?>" class="noajax"">Download</a>
	<?php else: ?>
		<a href="/decedent/documentdownload/customer_id/<?php echo $model->id; ?>/template_id/<?php echo $template_id; ?>" class="noajax"">Download PDF</a> | 
		<a href="/decedent/documentdownloadword/customer_id/<?php echo $model->id; ?>/template_id/<?php echo $template_id; ?>" class="noajax"">Download Word</a>
	<?php endif; ?>
</td>
<td class="tableData"> <a href="#" class="noajax" onclick="documentSendEmail(<?php echo $row['id'] ?>);return false">Send email</a></td>
</tr>
<?php
  }
}
?>

</tbody></table>
</td></tr>
<tr class="rellistbL"><td align="left"><div id="ra">
<a href="/decedent/adddocument/<?php echo $model->id; ?>" class="rellistNew" class="noajax">Add document</a>
<a href="/decedent/addFile/<?php echo $model->id; ?>" class="rellistNew" class="noajax">Add File</a>
</div></td>
<td align="right"><div align="right" class="listNav">  &nbsp;</div>
</td></tr> <!-- Field Data goes here --></tbody></table>
</div>
<!--Document--/end-->


<br/><br/>
<!--Contacts-->
<table border="0" width="95%" align="center" cellspacing="0" cellpadding="0" class="relCenterBg" id="contactlist"><tbody><tr height="25">
<td width="140" nowrap=""> 
<a href="javascript:toggleRelatedList('relationship_contact_<?php echo $model->id; ?>');" 
   name="relationship_contact_<?php echo $model->id; ?>" id="relationship_contact_<?php echo $model->id; ?>" class="relListHead">
Contacts</a> <span appapptagid="20"></span>
</td>
<td align="right"> <div id="ract_<?php echo $model->id; ?>" name="raction"> </td>
</tr></tbody>
</table>
<div id="contact_<?php echo $model->id; ?>" style="display:block">
<table border="0" width="95%" cellspacing="0" cellpadding="0" align="center">
<tbody><tr><td colspan="2"> 
<table border="0" width="100%" cellspacing="0" cellpadding="0" class="rellist">
<tbody><tr class="tableHeadBg"><td>&nbsp;</td> 
<td nowrap>Full Name</td>
<td nowrap>Address</td>
<td nowrap>Phone</td>
<td nowrap>Email</td>
<td nowrap>Relationship</td>
<td nowrap>Note</td>
</tr>
<?php
while ($row = $contactDataProvider->read()) {
//  $contact_id = $row['id'];
?>
<tr>
<td nowrap="" width="12%" class="tableData"> <div align="left">&nbsp;
  <a class="sl" href="/contact/update/<?php echo $row['id']; ?>?from=customer">Edit</a>
  <span class="sep">|</span> 
  <a href="#" class="listViewTdToolsS1 noajax" onclick="if(confirm('Are you sure delete this contact?')){ deleteContact(this, <?php echo $row['id'] ?>); } return false;">Remove</a>
</div>
</td>
<td class="tableData"> <a class="f12" href="/contact/view/<?php echo $row['id']; ?>"><?php echo $row['full_name'] ?></a> </td>
<td class="tableData"> <?php echo $row['address'] ?> </td>
<td class="tableData"> <?php echo $row['phone'] ?> </td>
<td class="tableData" nowrap> <?php echo $row['email'] ?> </td>
<td class="tableData" nowrap> <?php echo $row['relationship'] ?> </td>
<!--<td class="tableData" id="rls_<?php // echo $row['id'];?>" nowrap><span id="relationship_<?php // echo $row['id'];?>" style="color:red" onclick="alterValue(<?php // echo $row['id'];?>);"> <?php // echo $row['relationship'] ?> </span></td>-->
<!--<td class="tableData" nowrap><span ><?php // echo CHtml::dropdownlist('relationship','relationship',  DropDown::getSelfDefineOptions('contact', 'relationship'), array('onblur'=>'saveSelectedValue('.$contact_id.');')); ?></span></td>-->
<td class="tableData" nowrap> <?php echo $row['note'] ?> </td>
</tr>
<?php
}
?>

</tbody></table>
</td></tr>
<tr class="rellistbL"><td align="left"><div id="ra">
<a href="/contact/create?customerid=<?php echo $model->id; ?>" class="rellistNew" class="noajax">New contact</a></div></td> 
<td align="right">&nbsp;<div align="right" class="listNav"></div></td></tr> <!-- Field Data goes here --></tbody></table>
</div>
<!--Contacts --------end-->


<br/><br/>
<!--Notes-->
<?php
require_once('protected/controllers/NotesController.php');
$notesController = new NotesController('notes');
echo $notesController->actionAjaxlist('customer', $model->id, 1);
?>
<!--Notes --------end-->


<br/>
<!--Tasks-->
<?php
require_once('protected/controllers/TaskController.php');
$taskController = new TaskController('task');
echo $taskController->actionAjaxlist('customer', $model->id, 1);
?>
<!--Tasks --------end-->

</div>
  <div class="cb"></div>
 </div>
</div>

  
<!--Begin: change email address-->
<div id="document-change-emailaddr" style="display: none;"><br/>
  <input type="hidden" name="document_id" id="document_id" value=""/>
  New email address: <input type="text" name="new_doc_email" id="new_doc_email" value=""/>
  <input type="button" class="button" value="Save" onclick="documentChangeEmailAddrSave();" />
</div>
<!--End: change email address-->


<!--Begin: send email-->
<div id="document-send-emailaddr" style="display: none;"><br/>
  <form method="post" action="/decedent/documentsendemailconfirm" id="documentsendemailconfirm-form" enctype="multipart/form-data">
  <input type="hidden" name="document_id" id="sendmail_document_id" value=""/>
  <b>Email address: </b><br/>
  <input type="text" name="email_addr" id="email_addr" value=""/><br/>
  <b>Email text: </b><br/><textarea name="email_text" id="email_text" style="width:400px;height:150px;"></textarea><br/>
<!--  <b>Add attachment:</b>(Upload attachment max sizes is 20000bytes.)<br/>
  <input type="file" name="myAttachment[]" id="myAttachment"/><br/>-->
  <input type="button" class="button" value="Send" onclick="documentSendEmailConfirm();" style="float: right;margin-top: 8px;" />
  
  </form>
</div>
<!--End: send email-->



<script type="text/javascript">
  var editLinkCurrent;
  function documentChangeEmailAddr(document_id, editLink){
    editLinkCurrent = editLink;
    var email = $(editLink).prev().html();
    $('#document_id').val(document_id);
    $('#new_doc_email').val(email);
    
    $("#document-change-emailaddr").dialog({
      title: 'Edit email address',
      width: 400,
      height: 100,
      modal: true
    });
  }
  
  function documentChangeEmailAddrSave(){
    var document_id = $('#document_id').val();
    var new_doc_email = $('#new_doc_email').val();
    
    $.ajax({
      async:false,
      cache:false,
      url: '/decedent/documentchangeemailaddr/'+document_id,
      type: 'POST',
      data: {email_address_alt:new_doc_email},
      dataType: 'html',
      timeout: 15000,
      beforeSend:function(){
      },
      success: function(data){
        if (data == 'Saved.') {
          $(editLinkCurrent).prev().html(new_doc_email);
          $("#document-change-emailaddr").dialog('close');
        } else {
          showTip('Failed, please try again.');
        }
      }
    });
  }
  
  function documentSendEmail(document_id){
    $("#sendmail_document_id").val(document_id);
  
    $.ajax({
      async:false,
      cache:false,
      url: '/decedent/documentsendemail/'+document_id,
      type: 'GET',
      data: {},
      dataType: 'json',
      timeout: 15000,
      beforeSend:function(){
      },
      success: function(data){
        $("#email_addr").val(data.email_addr);
        $("#email_text").val(data.email_text);
        
        $("#document-send-emailaddr").dialog({
          title: 'Send email',
          width: 430,
          height: 330,
          modal: true
        });
      }
    });
  }
  
  function documentSendEmailConfirm(){
  var document_id = $('#sendmail_document_id').val();
  var email_data = $('#documentsendemailconfirm-form').serialize();
    $.ajax({
      async:false,
      cache:false,
      url: '/decedent/documentsendemailconfirm/'+document_id, 
      type: 'POST',
      data: email_data,
      dataType: 'html',
      timeout: 15000,
      beforeSend:function(){
      },
      success: function(data){
        if (data == 'Send.') {
          $("#document-send-emailaddr").dialog('close');
        } else {
          showTip('Failed, please try again.');
        }
      }
    });
  }
  
  function dialogAddProduct(){
    $('#add_product').dialog('close');
    $('#add_product').dialog({'title':'Add product', width: 400});
  }
  
  function dialogAddPackage(){
    $('#add_package').dialog('close');
    $('#add_package').dialog({'title':'Add package', width: 400});
  }
  
  function alterInput(product_id, customer_id){
    var data = $('#product_retail1_'+product_id).html().replace(/ /g, '');
    $('#product_retail1_'+product_id).remove();
    $('#retail_'+product_id).html('$<input type="text" name="product_retail" id="product_retail2_'+product_id+'" style="width:60px;" value="'+data+'" onblur="saveInputValue('+product_id+','+customer_id+');" onkeyup="value=value.replace(/[^\\d\\.]/g,\'\')" onbeforepaste="clipboardData.setData(\'text\',clipboardData.getData(\'text\').replace(/[^\\d\\.]/g,\'\'))">');
    $('#product_retail2_'+product_id).focus();   
  }
 
   function saveInputValue(product_id, customer_id){
     var value = $('#product_retail2_'+product_id).val();
     
     if(value == ''){
       alert('Please input a valid value!');
       return;
     }
     
      $.ajax({
        async:true,
        cache:false,
        url: '/decedent/editretail/product_id/'+product_id+'/customer_id/'+customer_id,
        type: 'POST',
        data: {data:$('#product_retail2_'+product_id).val()},
        dataType: 'json',
        timeout: 15000,
        beforeSend:function(){
        },
        success: function(data){
          for(key in data){
            var tax = data['tax'];
            var totalPrices = data['totalPrices'];
          }
         if(tax == null){
           tax = 0;
         }
          var value = $('#product_retail2_'+product_id).val();
          $('#retail_'+product_id).html('$<span id="product_retail1_'+product_id+'" style="color:red;width:70px;" onclick="alterInput('+product_id+','+customer_id+');">'+value+'</span>');
          $('#span_tax').html(tax);
          $('#span_total_price').html(totalPrices);
          
        }
      });
   }
   
   function refreshProductList(){
     $.ajax({
        async:true,
        cache:false,
        url: '/decedent/refreshProductList/<?php echo $model->id; ?>',
        type: 'GET',
        data: {},
        dataType: 'html',
        timeout: 15000,
        beforeSend:function(){
        },
        success: function(data){
          $('#productlist-wrapper').html(data);
        }
      });
   }
   
//   function alterValue(contact_id){
//     $('#relationship_'+contact_id).remove();
//     $('#rls_'+contact_id).html('<?php // echo str_replace("\n","\\n",CHtml::dropdownlist('relationship_'.$contact_id, 'relationship',  DropDown::getSelfDefineOptions('contact', 'relationship'), array('onblur'=>'saveSelectedValue('.$contact_id.');')));?>');
//     $('#rls_'+contact_id).html('<?php // echo str_replace("\n","\\n",CHtml::dropdownlist('relationship_'.$contact_id, 'relationship',  DropDown::getSelfDefineOptions('contact', 'relationship'), array('onblur'=>'saveSelectedValue('.$contact_id.');')));?>');
//   }
   
//   function saveSelectedValue(contact_id){
//    var value = $('#relationship_'+contact_id).val();
//    $.ajax({
//        async:false,
//        cache:false,
//        url: '/contact/saverelationship/contact_id/'+contact_id,
//        type: 'POST',
//        data: {data:value},
//        dataType: 'html',
//        timeout: 15000,
//        beforeSend:function(){
//        },
//        success: function(data){
////          $('#rls_'+contact_id).html('<span id="relationship_'+contact_id+'" style="color:red" onclick="alterValue('+contact_id+');">'+data+'</span>');
//          $('#rls_'+contact_id).html('<span id="relationship_'+contact_id+'" style="color:red" onclick="alterValue('+<?php // echo $contact_id; ?>+');">'+data+'</span>');
//        }
//      });
//  }
</script>

<!--Begin: add product-->
<div id="add_product" style="display: none;">
  <form method="post" action="/decedent/addproduct/<?php echo $model->id;?>" id="addproduct-form" class="noajax">
    <?php 
     $i=10001;

     $serviceCNT = $merCNT = $cashCNT = 0;
     
     //Services
     $services = Inventory::getAllForCustomer($model->id, false, 'Services');
     $servicesChkBoxes = '<ul>';
     foreach ($services as $id=>$name) {
       $i++;
       $serviceCNT++;
       $servicesChkBoxes .= '<li><input value="'.$id.'" id="products_'.$i.'" type="checkbox" name="products[]"> <label for="products_'.$i.'">'.$name.'</label></li>';
     }
     $servicesChkBoxes .= '</ul>';

     //Merchandise
     $Merchandise = Inventory::getAllForCustomer($model->id, false, 'Merchandise');
     $MerchandiseChkBoxes = '<ul>';
     foreach ($Merchandise as $id=>$name) {
       $i++;
       $merCNT++;
       $MerchandiseChkBoxes .= '<li><input value="'.$id.'" id="products_'.$i.'" type="checkbox" name="products[]"> <label for="products_'.$i.'">'.$name.'</label></li>';
     }
     $MerchandiseChkBoxes .= '</ul>';

     //Cash Advances
     $CashAdvances = Inventory::getAllForCustomer($model->id, false, 'Cash Advances');
     $CashAdvancesChkBoxes = '<ul>';
     foreach ($CashAdvances as $id=>$name) {
       $i++;
       $cashCNT++;
       $CashAdvancesChkBoxes .= '<li><input value="'.$id.'" id="products_'.$i.'" type="checkbox" name="products[]"> <label for="products_'.$i.'">'.$name.'</label></li>';
     }
     $CashAdvancesChkBoxes .= '</ul>';
   ?>

    <!--tree view-->
    <ul id="black" class="treeview-black">
      <li class="closed">
        <span>Services (<?php echo $serviceCNT; ?>)</span>
        <?php echo $servicesChkBoxes; ?>
      </li>
      <li class="closed">
        <span>Merchandise (<?php echo $merCNT; ?>)</span>
        <?php echo $MerchandiseChkBoxes; ?>
      </li>
      <li class="closed">
        <span>Cash Advances (<?php echo $cashCNT; ?>)</span>
        <?php echo $CashAdvancesChkBoxes; ?>
      </li>
    </ul>


    <input type="submit" class="button" value="Save" style="float: right;margin-top: 8px;" />
 </form>
</div>

<link rel="stylesheet" href="/assets/treeview/jquery.treeview.css" />
<script src="/assets/treeview/lib/jquery.cookie.js" type="text/javascript"></script>
<script src="/assets/treeview/jquery.treeview.js" type="text/javascript"></script>
<script>
$(document).ready(function(){
	// fourth example
	$("#black").treeview({
		control: "#treecontrol"
//		persist: "cookie",
//		cookieId: "treeview-black"
	});

});

</script>
<!--End: add product-->

<!--Begin: add package-->
<div id="add_package" style="display: none;"><br/>
  <form method="post" action="/decedent/addpackage/<?php echo $model->id;?>" id="addpackage-form" class="noajax">
    <b>Select package: </b><br/>
  <?php 
   echo CHtml::checkBoxList('package[]', array(), Package::getAll());
  ?>
    <br/>
    <input type="submit" class="button" value="Save" style="float: right;margin-top: 8px;" />
 </form>
</div>
<!--End: add package-->


<div id="payment_receipt" style="display: none;">
  <table border="0">
    <tbody>
      <tr>
        <td colspan="2">
          <span id="company_name" name="company_name"></span><br/>
          <span id="company_address" name="company_address"></span><br/>
          <span id="company_city" name="company_city"></span> <span id="company_state" name="company_state"></span> <span id="company_zip" name="company_zip"></span><br/>
        </td>
        <td rowspan="2" style="text-align: left;">
          <img id="img_logo" width="80px" height="60px"/>
        </td>
        <td rowspan="2"> <span style="text-align: right;"><button type="button" class="type" id="printReceiptButton" name="printReceiptButton" value="">Print</button></span></td>
      </tr>
      
      <tr>
         <td colspan="2"><br/><br/></td>
      </tr>
      
      <tr>
         <td colspan="2">Payer:</td>
      </tr>
      <tr>
         <td colspan="4">
          <br/>
          <span id="payment_name" name="payment_name"></span><br/>
          <span id="payment_address" name="payment_address"></span><br/>
          <span id="payment_city" name="payment_city"></span> <span id="payment_state" name="payment_state"></span> <span id="payment_zip" name="payment_zip"></span><br/>
         </td>
      </tr>
      <tr>
        <td colspan="4"><br/></td>
      </tr>
    </tbody>
    
    <tbody>
      <tr><td colspan="4">-----------------------------------------------------------------------------------------------------------------------------------------------------------------</td></tr>
      <tr><td colspan="4"><br/><br/></td></tr>
      <tr>
        <td colspan="4" style="font-size: 12px; font-weight: bold; nowrap: nowrap; text-align: center;">
          Receipt of payment for the services of <span id="customer_name" name="customer_name"></span>
        </td>
      </tr>
    </tbody>
    
    <tbody class="tbody">
      <tr>
        <td colspan="4">
          <table class="tab" style="width:650px;" cellpadding="0">
            <tr>
              <td width="30%" style="font-size: 12px; font-weight: bold; nowrap: nowrap; text-align: right;">Payment Date:</td><td width="20%"><span id="payment_date" name="payment_date"></span></td>
              <td width="30%" style="font-size: 12px; font-weight: bold; nowrap: nowrap; text-align: right;">Balance due before payment:</td><td width="20%"><span id="balance_before_payment" name="balance_before_payment"></span></td>
            </tr>
            <tr>
              <td width="30%" style="font-size: 12px; font-weight: bold; nowrap: nowrap; text-align: right;">Method:</td><td width="20%"><span id="method" name="method"></span></td> 
              <td id="payment_discount" width="30%" style="font-size: 12px; font-weight: bold; nowrap: nowrap; text-align: right;">Payment:</td><td width="20%"><span id="amount_discount" name="amount_discount"></span></td>
            </tr>
            <tr>
              <td width="30%" style="font-size: 12px; font-weight: bold; nowrap: nowrap; text-align: right;">Check No.</td><td width="20%"><span id="check_number" name="check_number"></span></td>
              <td width="30%" style="font-size: 12px; font-weight: bold; nowrap: nowrap; text-align: right;">Balance due on account:</td><td width="20%"><span id="balance_due_account" name="balance_due_account"></span></td>
            </tr>
          </table>
      </td>
    </tr>
    </tbody>
    
    <tbody>
    <tr><td colspan="4"><br/></td></tr>
    <tr><td colspan="4" style="text-align: center;">Thank you</td></tr>
    <tr><td colspan="4"><br/></td></tr>
    </tbody>
  </table>
</div>

<style type="text/css">
/*.tab{
    border-top:1px solid #000000;
    border-left:1px solid #000000;
  }
.tab td{
    border-right:1px solid #000000;
    border-bottom:1px solid #000000;
  }*/
  </style>
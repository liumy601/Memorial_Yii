<?php $this->renderFile('protected/views/layouts/header.php'); ?>

<?php
if ($model->id > 0) {
  $title = 'Update Template';
} else {
  $title = 'Create Template';
}

$this->pageTitle = Yii::app()->name . ' - ' . $title;
?>



<?php if(Yii::app()->user->hasFlash('msg')): ?>

<div class="flash-success">
	<?php echo Yii::app()->user->getFlash('msg'); ?>
</div>

<?php else: ?>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
    'htmlOptions' => array(
        'name'=>'EditView', 
        'id'=>'EditView', 
        'enctype'=>'multipart/form-data', 
        'class'=>'noajax',
//        'onsubmit'=>"return(check_form('EditView'));"
        'onsubmit'=>"return check_form_template();"
  ))); ?>
  
  <table width="100%" border="0" cellspacing="0" cellpadding="0"><tbody><tr><td class="Leads-titleIcon">&nbsp;</td> 
  <td height="30" class="title hline" style="padding-left:10px"> <?php echo $title; ?> </td></tr></tbody></table>
  <p></p>
  <div class="bodyText mandatory"></div>

  <table width="95%" border="0" cellpadding="0" cellspacing="0"><tbody><tr><td align="center"><div style="float:right;" class="bodyText mandatory">* Required Field(s)</div>
    <input title="Save" class="button" onclick="" type="submit" name="button" value="Save"/>
    <!--<input title="Cancel" class="button buttonurl" url="/template" pageTitle="Template" type="button" name="button" value="Cancel"/>-->
    <!--<input title="Cancel" class="button buttonurl" url="/template/view/<?php // echo $model->id;?>" pageTitle="Template" type="button" name="button" value="Cancel"/>-->
    <input title="Cancel" class="button buttonurl" url="/template" pageTitle="Template" type="button" name="button" value="Cancel"/>
  </td></tr></tbody></table>

  <div id="Information">
    <table id="secHeadLead Information" width="95%" cellspacing="0" cellpadding="0"><tbody width="100%"><tr><td class="secHead">Information</td></tr></tbody></table>
    <table width="100%" border="0" cellspacing="1" cellpadding="0" class="tabForm">
      <tbody>
       <tr>
        <td width="10%" class="label" style="color: #f00"><?php echo $form->labelEx($model,'name'); ?></td>
        <td width="40%" class="element">
          <?php echo $form->textField($model,'name',array('size'=>50,'maxlength'=>50)); ?>
          <br/><span class="warning" style="display:none; color:#f00;">Warning: Template name must be filled in and it's not blank!!!</span>
        </td>
        <td width="10%" class="label"><?php echo $form->labelEx($model,'case_number'); ?></td>
        <td width="40%" class="element"><?php echo $form->textField($model,'case_number',array('size'=>50,'maxlength'=>50)); ?></td>
       
       </tr>

       <tr>
        <td width="10%" class="label"><?php echo $form->labelEx($model,'email_address'); ?></td>
        <td width="40%" class="element"><?php echo $form->textField($model,'email_address',array('size'=>50,'maxlength'=>50)); ?></td>
        <td width="10%" class="label"><?php echo $form->labelEx($model,'email_text'); ?></td>
        <td width="40%" class="element"><?php echo $form->textArea($model,'email_text',array('rows'=>5,'cols'=>50)); ?></td>
       </tr>
		
		<?php if (Yii::app()->user->type == 'super admin'): ?>
			<tr>
				<td width="10%" class="label">Active</td>
				<td width="40%" class="element"><?php echo $form->checkBox($model,'active'); ?></td>
		   </tr>
	  <?php else: ?>
			<tr>
				<td width="10%" class="label">Load Sample</td>
				<td width="40%" class="element"><?php echo CHtml::dropDownList('sample_templates', '', $sample_templates, array('onchange'=>'loadSampleTemplate(this.value)')); ?></td>
		   </tr>
	  <?php endif; ?>
		
		<?php if(0) : ?>
		   <tr>
			<td width="10%" class="label">Default for all customer</td>
			<td width="40%" class="element"><?php echo $form->checkBox($model,'default_check'); ?>&nbsp;(if selected, the template will be visibled for all customer.)</td>
		   </tr>
	   <?php endif; ?>
       
       <tr>
        <td width="10%" class="label"><?php echo $form->labelEx($model,'templates'); ?></td>
        <td width="40%" class="element" colspan="3"><?php echo $form->textArea($model,'templates',array('rols'=>5,'cols'=>50)); ?></td>
       </tr>
       
       <tr>
        <td width="10%" class="label"> </td>
        <td width="40%" class="element" colspan="3" align="center">
          <input title="Save" class="button" onclick="" type="submit" name="button" value="Save"/>
          <!--<input title="Cancel" class="button buttonurl" url="/template" pageTitle="Template" type="button" name="button" value="Cancel"/>-->
          <!--<input title="Cancel" class="button buttonurl" url="/template/view/<?php // echo $model->id;?>" pageTitle="Template" type="button" name="button" value="Cancel"/>-->
          <input title="Cancel" class="button buttonurl" url="/template" pageTitle="Template" type="button" name="button" value="Cancel"/>
        </td>
       </tr>
       
       <tr>
        <td width="10%" class="label"> </td>
        <td width="40%" class="element" colspan="3">
          <table border="0">
	<tr>
             <td width="220"><b>Logo</b></td>
Add a comment to this line
             <td>%Logo%</td>
            </tr>
            <tr>
             <td width="220"><b>Case Number</b></td>
             <td>%Case Number%</td>
            </tr>
            <tr>
             <td width="220"><b>Memorials can be made to</b></td>
             <td>%Memorials%</td>
            </tr>
            <tr>
             <td><b>Church Membership</b></td>
             <td>%Church Membership%</td>
            </tr>
            <tr>
             <td><b>Fathers Name</b></td>
             <td>%Fathers Name%</td>
            </tr>
            <tr>
             <td><b>Mothers Name</b></td>
             <td>%Mothers Name%</td>
            </tr>
            <tr>
             <td><b>Occupation</b></td>
             <td>%Occupation%</td>
            </tr>
            <tr>
             <td><b>Biography</b></td>
             <td>%Biography%</td>
            </tr>
            <tr>
             <td><b>Veteran status</b></td>
             <td>%Veteran status%</td>
            </tr>
            <tr>
             <td><b>Branch</b></td>
             <td>%Branch%</td>
            </tr>
            <tr>
             <td><b>Full Military Rites</b></td>
             <td>%Full Military Rites%</td>
            </tr>
            <tr>
             <td><b>Marital Status</b></td>
             <td>%Marital Status% </td>
            </tr>
            <tr>
             <td><b>Spouse</b></td>
             <td>%Spouse%</td>
            </tr>
            <tr>
             <td><b>Date of Marriage</b></td>
             <td>%Date Of Marriage%</td>
            </tr>
            <tr>
             <td><b>Place of Marriage</b></td>
             <td>%Place Of Marriage%</td>
            </tr>
            <tr>
             <td><b>Vet</b></td>
             <td>%Military Veteran%</td>
            </tr>
            <tr>
             <td><b>Spouse Date of Death</b></td>
             <td>%Spouse Date of Death%</td>
            </tr>
            <tr>
             <td><b>Survived</b></td>
             <td>%Survived By%</td>
            </tr>
            <tr>
             <td><b>Preceded in Death by</b></td>
             <td>%Preceded in Death By%</td>
            </tr>
            <tr>
             <td><b>Full Name for Death Certificate</b></td>
             <td>%Full Legal Name%</td>
           </tr>
            <tr>
             <td><b>Name to be used on Obit/Memo</b></td>
             <td>%Full Legal Name%</td>
            </tr>
            <tr>
             <td><b>Name For Obituary</b></td>
             <td>%Name For Obituary%</td>
           </tr>
            <tr>
             <td><b>Address</b></td>
             <td>%Address%</td>
            </tr>
            <tr>
             <td><b>Doctor</b></td>
             <td>%Doctor%</td>
            </tr>
            <tr>
             <td><b>SSN</b></td>
             <td>%SSN%</td>
            </tr>
            <tr>
             <td><b>Highest Level of Education</b></td>
             <td>%Highest Level of Education%</td>
            </tr>
            <tr>
             <td><b>Funeral Service</b></td>
             <td>%Funeral Service Date and Time% at %Location of Funeral Service%</td>
            </tr>
            <tr>
             <td><b>Visitation</b></td>
             <!--<td>%Visitation Time%, %Date of Visitation%, at %Location of Visitation%</td>-->
             <td>%Date of Visitation%, %Visitation Time% to %Date of Visitation End%, %Visitation Time End% at %Location of Visitation%</td>
            </tr>
            <tr>
             <td><b>Date & Time of Burial</b></td>
             <td>%Date and Time of Burial% at %Burial%</td>
            </tr>
            <tr>
             <td><b>Officiant/Minister</b></td>
             <td>%Officiant%</td>
            </tr>
            <tr>
             <td><b>Officiant2</b></td>
             <td>%Officiant2%</td>
            </tr>
            <tr>
             <td><b>Special Rites:</b></td>
             <td>%Special Rites%</td>
            </tr>
            <tr>
             <td><b>Church Membership</b></td>
             <td>%Church Membership%</td>
            </tr>
            <tr>
             <td><b>Informant</b></td>
             <td>%Informant Name & Address%</td>
            </tr>
            <tr>
             <td><b>Informant Phone</b></td>
             <td>%Informant Phone%</td>
            </tr>
<!--            <tr>
             <td><b>Olney Daily Mail ($55)</b></td>
             <td>_____</td>
            </tr>
            <tr>
             <td><b>Decatur Herald</b></td>
             <td>_____</td>
            </tr>
            <tr>
             <td><b>Olney Radio/WVLN </b></td>
             <td>_____</td>
            </tr>-->
            
            <tr>
             <td><b>Interment City</b></td>
             <td>%Interment City%</td>
            </tr>
            <tr>
             <td><b>Interment Country</b></td>
             <td>%Interment Country%</td>
            </tr>
            <tr>
             <td><b>Interment State</b></td>
             <td>%Interment State%</td>
            </tr>
            
            
            <tr>
             <td><b>Music Selection 1</b></td>
             <td>%Music Selection 1%</td>
            </tr>
            <tr>
             <td><b>Music Selection 2</b></td>
             <td>%Music Selection 2%</td>
            </tr>
            <tr>
             <td><b>Music Selection 3</b></td>
             <td>%Music Selection 3%</td>
            </tr>
            <tr>
             <td><b>Music Selection 4</b></td>
             <td>%Music Selection 4%</td>
            </tr>
            <tr>
             <td><b>Music Selection 5</b></td>
             <td>%Music Selection 5%</td>
            </tr>
            
            <tr>
             <td><b>Pallbearer 1</b></td>
             <td>%Pallbearers%</td>
            </tr>
            <tr>
             <td><b>Pallbearer 2</b></td>
             <td>%Pallbearer 2%</td>
            </tr>
            <tr>
             <td><b>Pallbearer 3</b></td>
             <td>%Pallbearer 3%</td>
            </tr>
            <tr>
             <td><b>Pallbearer 4</b></td>
             <td>%Pallbearer 4%</td>
            </tr>
            <tr>
             <td><b>Pallbearer 5</b></td>
             <td>%Pallbearer 5%</td>
            </tr>
            <tr>
             <td><b>Pallbearer 6</b></td>
             <td>%Pallbearer 6%</td>
            </tr>
            <tr>
             <td><b>Pallbearer 7</b></td>
             <td>%Pallbearer 7%</td>
            </tr>
            <tr>
             <td><b>Pallbearer 8</b></td>
             <td>%Pallbearer 8%</td>
            </tr>
            
            <tr>
             <td><b>Special Music</b></td>
             <td>%Special Music%</td>
            </tr>
<!--            <tr>
             <td><b>Vault</b></td>
             <td>__________</td>
            </tr>
            <tr>
             <td><b>Casket</b></td>
             <td>__________</td>
            </tr>
            <tr>
             <td><b>Cemetery</b></td>
             <td>Chatham Cemetery</td>
            </tr>
            <tr>
             <td><b>Organist</b></td>
             <td>__________</td>
            </tr>
            <tr>
             <td><b>Soloist</b></td>
             <td>__________</td>
            </tr><tr>
             <td><b>Hairdresser</b></td>
             <td>__________</td>
            </tr>
            <tr>
             <td><b>Florist</b></td>
             <td>__________</td>
            </tr>
            <tr>
             <td><b>Name Tapes</b></td>
             <td>__________</td>
            </tr>
            <tr>
             <td><b>Clothing/Jewelry/Glasses</b></td>
             <td>______________</td>
            </tr>
            <tr>
             <td><b>Memo Verse</b></td>
             <td>________</td>
            </tr>
            <tr>
             <td><b>w/Pic</b></td>
             <td>____</td>
            </tr>
            <tr>
             <td><b>Grave Marker </b></td>
             <td>____</td>
            </tr>
            <tr>
             <td><b>Stone Letter </b></td>
             <td>YES/NO</td>
            </tr>
            <tr>
             <td><b>Easels</b></td>
             <td>____</td>
            </tr>
            <tr>
             <td><b>Tables</b></td>
             <td>____</td>
            </tr>
            </tr>
            <tr>
             <td><b>Flag</b></td>
             <td>YES/NO</td>
            </tr>
            <tr>
             <td><b>VA Marker  </b></td>
             <td>YES/NO</td>
            </tr>
            <tr>
             <td><b>Military Notified</b></td>
             <td>____</td>
            </tr>
            <tr>
             <td><b>Copies of Death Certificate</b></td>
             <td>____</td>
            </tr>-->
            <tr>
             <td><b>Contacts</b></td>
             <td>%Contacts%</td>
            </tr>
            <tr>
             <td><b>Full Legal Name</b></td>
             <td>%Full Legal Name%</td>
            </tr>
            <tr>
             <td><b>Case Number</b></td>
             <td>%Case Number% </td>
            </tr>
            <tr>
             <td><b>Products (with internal notes)</b></td>
             <td>%Products (with internal notes)%</td>
            </tr>
            <tr>
             <td><b>Products (invoice notes only)</b></td>
             <td>%Products (invoice notes only)%</td>
            </tr>
             <tr>
             <td><b>Products (Services)</b></td>
             <td>%Products (Services)%</td>
            </tr>
            <tr>
             <td><b>Products (Merchandise)</b></td>
             <td>%Products (Merchandise)%</td>
            </tr>
            <tr>
             <td><b>Products (Cash Advances)</b></td>
             <td>%Products (Cash Advances)%</td>
            </tr>
             <td><b>Payments</b></td>
             <td>%Payments%</td>
            </tr>
            <tr>
             <td><b>Total Funeral Charges</b></td>
             <td>%Total Funeral Charges%</td>
            </tr>
            <tr>
             <td><b>Sales Tax</b></td>
             <td>%Sales Tax%</td>
            </tr>
            <tr>
             <td><b>Total Cash Advances</b></td>
             <td>%Total Cash Advances%</td>
            </tr>
            <tr>
             <td><b>Complete Total</b></td>
             <td>%Complete Total%</td>
            </tr>
            <tr>
             <td><b>Total Payments Received</b></td>
             <td>%Total Payments Received%</td>
            </tr>
<!--            <tr>
             <td><b>Credit Amount</b></td>
             <td>%Credit Amount%</td>
            </tr>-->
            <tr>
             <td><b>Discount</b></td>
             <td>%Discount%</td>
            </tr>
            <tr>
             <td><b>Balance Due</b></td>
             <td>%Balance Due%</td>
            </tr>
            <tr>
             <td><b>City</b></td>
             <td>%City%</td>
            </tr>
            <tr>
             <td><b>Age</b></td>
             <td>%Age%</td>
            </tr>
            <tr>
              <td><b>Funeral Home</b></td>
              <td>%Funeral Home%</td>
            </tr>
            <tr>
              <td><b>Date Of Death</b></td>
              <td>%Date Of Death%</td>
            </tr>
            <tr>
              <td><b>Time Of Death</b></td>
              <td>%Time Of Death%'</td>
            </tr>
            <tr>
              <td><b>Place of Death</b></td>
              <td>%Place of Death%</td>
            </tr>
            <tr>
              <td><b>Place of Birth</b></td>
              <td>%Place of Birth%</td>
            </tr>
            <tr>
             <td><b>Formerly of</b></td>
             <td>%Formerly of%</td>
            </tr>
            <tr>
             <td><b>Date of Birth</b></td>
             <td>%Date of Birth%</td>
            </tr>
            <tr>
             <td><b>Newspaper/Radio 1</b></td>
             <td>%Newspaper/Radio 1%</td>
            </tr>
            <tr>
             <td><b>Newspaper/Radio 2</b></td>
             <td>%Newspaper/Radio 2%</td>
            </tr>
            <tr>
             <td><b>Newspaper/Radio 3</b></td>
             <td>%Newspaper/Radio 3%</td>
            </tr>
            <tr>
             <td><b>Newspaper/Radio 4</b></td>
             <td>%Newspaper/Radio 4%</td>
            </tr>
            <tr>
             <td><b>Newspaper/Radio 5</b></td>
             <td>%Newspaper/Radio 5%</td>
            </tr>
            <tr>
             <td><b>Newspaper/Radio 6</b></td>
             <td>%Newspaper/Radio 6%</td>
            </tr>
            <tr>
             <td><b>Submit w/ Pic:</b></td>
             <td>%Submit Pic with Obit%</td>
            </tr>
            <tr>
             <td><b>Statement Date</b></td>
             <td>%Statement Date%</td>
            </tr>
            <tr>
             <td><b>Disposition Type</b></td>
             <td>%Disposition Type%</td>
            </tr>
            <tr>
             <td><b>Disposition Date</b></td>
             <td>%Disposition Date%</td>
            </tr>
            <tr>
             <td><b>Disposition Place</b></td>
             <td>%Disposition Place%</td>
            </tr>
            <tr>
             <td><b>Notes</b></td>
             <td>%Notes%</td>
            </tr>
         </table>
        </td>
       </tr>
       
      </tbody>  
    </table>
  </div>
  
  
 
 
  
<?php $this->endWidget(); ?>
<?php endif; ?>
</div><!-- form -->

<script type="text/javascript" src="../../assets/ckeditor/ckeditor.js"></script>

<script type="text/javascript">
var editor;

if ( editor )
  editor.destroy( true );

// Create the editor again, with the appropriate settings.
var config = {
  enterMode		: CKEDITOR.ENTER_BR,
  shiftEnterMode	: CKEDITOR.ENTER_P
}
config.toolbar = 'Full';
config.toolbar_Full =
[
    ['Source','-','Save'],
    ['Cut','Copy','Paste','PasteText','PasteFromWord','-','Print', 'SpellChecker', 'Scayt'],
    ['Undo','Redo','-','Find','Replace'],
    ['NumberedList','BulletedList','-','Outdent','Indent','Blockquote'],
    ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
    ['Link','Unlink','Anchor'],
    ['Image','Table','HorizontalRule','SpecialChar','PageBreak'],
    '/',
    ['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
    ['Format','Font','FontSize'],
    ['TextColor','BGColor']
];
config.fontSize_sizes ='8/8pt;9/9pt;10/10pt;11/11pt;12/12pt;14/14pt;16/16pt;18/18pt;20/20pt;22/22pt;24/24pt;26/26pt;28/28pt;36/36pt;48/48pt;72/72pt';

editor = CKEDITOR.replace( 'Template_templates', config);
</script>

<script type="text/javascript">
 function check_form_template(){
   if(document.getElementById('Template_name').value == ''){
     $('#Template_name').css('backgroundColor', '#f00');
     $('.warning').show();
     setTimeout(deleteBgcolor, '1500');
     return false;
   }
   return true;
 }
 
 function deleteBgcolor(){
    $('#Template_name').focus();
    $('#Template_name').css('backgroundColor', '#fff');
    clearTimeout();
 }

 function loadSampleTemplate(templates) {
	 if(templates != '') {
		CKEDITOR.instances['Template_templates'].setData(templates);
	 }
 }
</script>

<?php $this->renderFile('protected/views/layouts/footer.php'); ?>



<div class="bodycontainer"><div id="BodyContent">
<table width="100%" cellspacing="0" cellpadding="0"><tbody><tr><td> 
<div id="leftPanel">
  
<!--buttons-->
<div id="detailViewButtonLayerDiv" class="detailViewButtonLayerDiv" style="z-index: 19; width: 1240px; box-shadow: none; "><table cellpadding="5" cellspacing="0" width="100%" class="dvbtnlayer"><tbody><tr><td align="center" nowrap="" class="pL15"> <a href="javascript:void(0);" onclick="history.back();return false;"><img src="/images/spacer.gif" class="backtoIcon" border="0"></a> </td><td nowrap="" class="pL10 dvmo"> 
<input class="dveditBtn dvcbtn" type="button" value="Edit" name="Edit" onclick="document.location.href='/template/update/<?php echo $model->id; ?>'" pagetitle="Edit contact" id="editview_student_<?php echo $model->id; ?>"/>
<input class="dveditBtn dvcbtn" type="button" value="Delete" name="Delete" onclick="if(confirm('Are you sure delete this template')){document.location.href='/template/delete/<?php echo $model->id; ?>'}else{return false;}" pagetitle="Delete contact" id="deleteview_student_<?php echo $model->id; ?>"/>
&nbsp;
</td>
<td width="100" class="dvmo"> </td>
<td> </td>
<td width="90%"> <div align="right">

</td>
</tr></tbody></table></div>
  
  
<!--view object-->
<div class="p15 mt5">
<div>
<div class="maininfo"><span id="cutomizebc"></span> 
<span id="headervalue_Account Name" class="dvTitle"><?php echo CHtml::encode($model->name); ?></span> 
<span id="headervalue_Website" class="dvSubTitle"><span id="subvalue1_Website"></span></span> <br>



<table width="100%" style="display:block"><!--detail-->
  <tbody>
    <tr>
        <td width="15%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('name')); ?></td>
        <td width="35%" class="dvValueTxt"><?php echo CHtml::encode($model->name); ?></td>
        <td width="15%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('case_number')); ?></td>
        <td width="35%" class="dvValueTxt"><?php echo CHtml::encode($model->case_number); ?></td>
    </tr>

    <tr>
        <td width="15%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('email_address')); ?></td>
        <td width="35%" class="dvValueTxt"><?php echo CHtml::encode($model->email_address); ?></td>
        <td width="15%" class="dvgrayTxt" valign="top"><?php echo CHtml::encode($model->getAttributeLabel('email_text')); ?></td>
        <td width="35%" class="dvValueTxt"><?php echo nl2br($model->email_text); ?></td>
    </tr>

    
    <tr>
      <td width="15%" class="dvgrayTxt">Default for all customer</td>
      <td width="35%" class="element"><?php echo $model->default_check ? 'Yes' : 'No'; ?></td>
    </tr>
       
    <tr>
        <td width="15%" class="dvgrayTxt" valign="top"><?php echo CHtml::encode($model->getAttributeLabel('templates')); ?></td>
        <td width="35%" class="dvValueTxt" colspan="3"><?php echo $model->templates; ?></td>
    </tr>
 </tbody>
</table>


</div><div class="cb"></div>

</div>
</div>

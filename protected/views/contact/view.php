<div class="bodycontainer"><div id="BodyContent">
<table width="100%" cellspacing="0" cellpadding="0"><tbody><tr><td> 
<div id="leftPanel">
  
<!--buttons-->
<div id="detailViewButtonLayerDiv" class="detailViewButtonLayerDiv" style="z-index: 19; width: 1240px; box-shadow: none; "><table cellpadding="5" cellspacing="0" width="100%" class="dvbtnlayer"><tbody><tr><td align="center" nowrap="" class="pL15"> <a href="javascript:void(0);" onclick="history.back();return false;"><img src="/images/spacer.gif" class="backtoIcon" border="0"></a> </td><td nowrap="" class="pL10 dvmo"> 
<input class="dveditBtn dvcbtn buttonurl" type="button" value="Edit" name="Edit" url="/contact/update/<?php echo $model->id; ?>" pagetitle="Edit contact" id="editview_student_<?php echo $model->id; ?>"/>
<input name="Delete2" class="dvdelBtn dvcbtn" type="button" value="Delete" url="/contact/delete/<?php echo $model->id ?>" onclick="if(confirm('Are you sure delete this record?')){ ajaxRequest(this); }">
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
<span id="headervalue_Account Name" class="dvTitle">Contact: <?php echo CHtml::encode($model->full_name); ?></span> 
<span id="headervalue_Website" class="dvSubTitle"><span id="subvalue1_Website"></span></span> <br>

<?php
 if (!Yii::app()->params['print']) {
?>
<div style="float:right">
  <a href="javascript:void window.open('/contact/view/id/<?php echo $model->id ?>/print/true','printwin','menubar=1,status=0,resizable=1,scrollbars=1,toolbar=0,location=1')" class="utilsLink"><img src="/themes/Sugar/images/print.gif" width="13" height="13" alt="Print" border="0" align="absmiddle"></a>
  <a href="javascript:void window.open('/contact/view/id/<?php echo $model->id ?>/print/true','printwin','menubar=1,status=0,resizable=1,scrollbars=1,toolbar=0,location=1')" class="utilsLink">Print</a>
</div>
<?php
 }
?>

<table width="100%" style="display:block"><!--detail-->
  <tbody>
    <tr>
        <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('customer')); ?></td>
        <td width="30%" class="dvValueTxt"><?php echo CHtml::link($model->customer, '/customer/' . $model->customerid); ?></td>
        <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('full_name')); ?></td>
        <td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->full_name); ?></td>
    </tr>

    <tr>
        <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('address')); ?></td>
        <td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->address); ?></td>
        <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('phone')); ?></td>
        <td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->phone); ?></td>
    </tr>
        
    <tr>
        <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('email')); ?></td>
        <td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->email); ?></td>
        <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('relationship')); ?></td>
        <td width="30%" class="dvValueTxt"><?php echo nl2br($model->relationship); ?></td>
    </tr>
        
    <tr>
        <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('note')); ?></td>
        <td width="30%" class="dvValueTxt"><?php echo nl2br($model->note); ?></td>
    </tr>
        
 </tbody>
</table>

</div>
  <div class="cb"></div>
</div>
</div>


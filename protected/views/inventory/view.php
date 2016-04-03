<div class="bodycontainer"><div id="BodyContent">
<table width="100%" cellspacing="0" cellpadding="0"><tbody><tr><td> 
<div id="leftPanel">
  
<!--buttons-->
<div id="detailViewButtonLayerDiv" class="detailViewButtonLayerDiv" style="z-index: 19; width: 1240px; box-shadow: none; "><table cellpadding="5" cellspacing="0" width="100%" class="dvbtnlayer"><tbody><tr><td align="center" nowrap="" class="pL15"> <a href="javascript:void(0);" onclick="history.back();return false;"><img src="/images/spacer.gif" class="backtoIcon" border="0"></a> </td><td nowrap="" class="pL10 dvmo"> 
<input class="dveditBtn dvcbtn buttonurl" type="button" value="Edit" name="Edit" url="/inventory/update/<?php echo $model->id; ?>" pagetitle="Edit inventory" id="editview_student_<?php echo $model->id; ?>"/>
<input name="Delete2" class="dvdelBtn dvcbtn" type="button" value="Delete" url="/inventory/delete/<?php echo $model->id ?>" onclick="if(confirm('Are you sure delete this record?')){ ajaxRequest(this); }">
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
<span id="headervalue_Account Name" class="dvTitle">Inventory: <?php echo CHtml::encode($model->name); ?></span> 
<span id="headervalue_Website" class="dvSubTitle"><span id="subvalue1_Website"></span></span> <br>

<?php
 if (!Yii::app()->params['print']) {
?>
<div style="float:right">
  <a href="javascript:void window.open('/inventory/view/id/<?php echo $model->id ?>/print/true','printwin','menubar=1,status=0,resizable=1,scrollbars=1,toolbar=0,location=1')" class="utilsLink"><img src="/themes/Sugar/images/print.gif" width="13" height="13" alt="Print" border="0" align="absmiddle"></a>
  <a href="javascript:void window.open('/inventory/view/id/<?php echo $model->id ?>/print/true','printwin','menubar=1,status=0,resizable=1,scrollbars=1,toolbar=0,location=1')" class="utilsLink">Print</a>
</div>
<?php
 }
 
 
  if ($model->template_id) {
    $template = Template::model()->findByPk($model->template_id);
  }
?>

<table width="100%" style="display:block"><!--detail-->
  <tbody>
    <tr>
        <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('name')); ?></td>
        <td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->name); ?></td>
        <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('vendor')); ?></td>
        <td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->vendor); ?></td>
    </tr>

    <tr>
        <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('sku')); ?></td>
        <td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->sku); ?></td>
        <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('retail')); ?></td>
        <td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->retail); ?></td>
    </tr>

    <tr>
        <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('cost')); ?></td>
        <td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->cost); ?></td>
        <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('template_id')); ?></td>
        <td width="30%" class="dvValueTxt"><?php if($template->id) echo CHtml::link($template->name, '/template/'.$template->id); ?></td>
    </tr>

    <tr>
        <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('taxable')); ?></td>
        <td width="30%" class="dvValueTxt"><?php echo CHtml::checkBox('taxable', $model->taxable, array('disabled'=>true))?></td>
        <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('category')); ?></td>
        <td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->category); ?></td>
    </tr>
    
    <tr>
        <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('internal_notes')); ?></td>
        <td width="30%" class="dvValueTxt"><?php echo nl2br(CHtml::encode($model->internal_notes)); ?></td>
        <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('invoice_notes')); ?></td>
        <td width="30%" class="dvValueTxt"><?php echo nl2br(CHtml::encode($model->invoice_notes)); ?></td>
    </tr>
 </tbody>
</table>

</div>
  <div class="cb"></div>
</div>
</div>


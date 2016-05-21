<div class="bodycontainer"><div id="BodyContent">
<table width="100%" cellspacing="0" cellpadding="0"><tbody><tr><td> 
<div id="leftPanel">
          
<!--buttons-->
<div id="detailViewButtonLayerDiv" class="detailViewButtonLayerDiv" style="z-index: 19; width: 1240px; box-shadow: none; "><table cellpadding="5" cellspacing="0" width="100%" class="dvbtnlayer"><tbody><tr><td align="center" nowrap="" class="pL15"> <a href="javascript:void(0);" onclick="history.back();return false;"><img src="/images/spacer.gif" class="backtoIcon" border="0"></a> </td><td nowrap="" class="pL10 dvmo"> 
<input class="dveditBtn dvcbtn buttonurl" type="button" value="Edit" name="Edit" url="/task/update/<?php echo $model->id; ?>" pagetitle="Edit contact" id="editview_student_<?php echo $model->id; ?>"/>
<input name="Delete2" class="dvdelBtn dvcbtn" type="button" value="Delete" url="/task/delete/<?php echo $model->id ?>" onclick="if(confirm('Are you sure delete this record?')){ ajaxRequest(this); }">
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
<span id="headervalue_Account Name" class="dvTitle">Task: <?php echo CHtml::encode($model->subject); ?></span> 
<span id="headervalue_Website" class="dvSubTitle"><span id="subvalue1_Website"></span></span> <br>


<?php
if ($model->assigned_to) {
  $assignedToUser = Users::model()->findByPk($model->assigned_to);
}
?>

<table width="100%" style="display:block"><!--detail-->
  <tbody>
    <tr>
      <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('subject')); ?></td><td width="5" class="dvgrayTxt">:</td> 
      <td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->subject); ?></td>
      <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('status')); ?></td><td width="5" class="dvgrayTxt">:</td> 
      <td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->status); ?></td>
    </tr>
    <tr>
      <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('date_due')); ?></td><td width="5" class="dvgrayTxt">:</td> 
      <td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->date_due); ?></td>
      <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('date_start')); ?></td><td width="5" class="dvgrayTxt">:</td> 
      <td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->date_start); ?></td>
    </tr>
    <tr>
      <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('priority')); ?></td><td width="5" class="dvgrayTxt">:</td> 
      <td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->priority); ?></td>
      <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('assigned_to')); ?></td><td width="5" class="dvgrayTxt">:</td> 
      <td width="30%" class="dvValueTxt"><?php echo CHtml::encode($assignedToUser->username); ?></td>
    </tr>
    <tr>
      <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('description')); ?></td><td width="5" class="dvgrayTxt">:</td> 
      <td width="30%" class="dvValueTxt"><?php echo nl2br(CHtml::encode($model->description)); ?></td>
      <td width="20%" class="dvgrayTxt">Relate to:</td><td width="5" class="dvgrayTxt">:</td> 
      <td width="30%" class="dvValueTxt">
      <?php
      if($model->parent_id){
        $parentModule = ucfirst($model->parent_type);
        echo $parentModule .': '. CHtml::link('View', '/'.$model->parent_type.'/view/'.$model->parent_id);
      }
      ?></td>
    </tr>
    <tr>
      <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('enteredby')); ?></td><td width="5" class="dvgrayTxt">:</td> 
      <td width="30%" class="dvValueTxt"><?php echo CHtml::encode($model->enteredby); ?></td>
      <td width="20%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('timestamp')); ?></td><td width="5" class="dvgrayTxt">:</td> 
      <td width="30%" class="dvValueTxt"><?php echo $model->timestamp ? date('m/d/Y H:i', $model->timestamp) : ''; ?></td>
    </tr>
  </tbody>
</table>

<?php if($model->status != 'Closed'){ ?>
<div id="checkout-button-wrapper">
  <input type="button" class="button" value="Close" onclick="closeTask(<?php echo $model->id; ?>);"/>
</div>
<?php } ?>

</div><div class="cb"></div>

</div>
</div>

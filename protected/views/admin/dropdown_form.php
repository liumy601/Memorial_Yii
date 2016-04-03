<?php $this->renderFile('protected/views/layouts/header_subpanel.php', array('title'=>'Control Panel')); ?>


<?php

$dropDownContent = '';
if ($dropDown) {
  foreach ($dropDown as $key=>$value) {
    if ($key == '') {
      continue;
    }
    
    $dropDownContent .= $value . "\n";
  }
}

$dropDownDefaultContent = '';
if ($dropDownDefault) {
  foreach ($dropDownDefault as $key=>$value) {
    if ($key == '') {
      continue;
    }
    
    $dropDownDefaultContent .= $value . "\n";
  }
}
?>

<?php if(Yii::app()->user->hasFlash('form')){ ?>
<div class="flash-success">
	<?php echo Yii::app()->user->getFlash('form'); ?>
</div>
<?php } ?>

<!--<h1>Define dropdown: <?php // echo ucfirst($module) . ' ' . ucfirst($dropdownname); ?></h1>-->
<!--<h1>Define dropdown: <?php // echo ucfirst($module) . ' ' . ($dropdownname!=skfhfuneralhome ? ucfirst($dropdownname) : 'Location') ; ?></h1>-->
<h1>Define dropdown: <?php echo ucfirst($module) . ' ' . str_replace('####', '/', urldecode($label)) ; ?></h1>

<?php $form=$this->beginWidget('CActiveForm', array('htmlOptions' => array('name'=>'EditView', 'id'=>'EditView'))); ?>

<?php
  echo CHtml::textArea('newdropdown', $dropDownContent, array('rows'=>8, 'cols'=>50));
  echo CHtml::textArea('defaultdropdown', $dropDownDefaultContent, array('rows'=>8, 'cols'=>50, 'style'=>'display:none'));
?><br/>

<input title="Search" class="button" type="submit" name="button" value="Save">

<?php if (strpos($dropdownname, 'newspaperradio') === false) { ?>
<input title="Reset" class="button" type="button" name="button" value="Reset to default" onclick="setDefaultdropdown();">
<?php } ?>

<?php $this->endWidget(); ?>


<?php $this->renderFile('protected/views/layouts/footer_subpanel.php'); ?>



<script type="text/javascript">
function setDefaultdropdown(){
  $("#newdropdown").val($("#defaultdropdown").val());
}
</script>
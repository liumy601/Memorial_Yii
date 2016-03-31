<?php $this->renderFile('protected/views/layouts/header_subpanel.php', array('title'=>'Control Panel')); ?>


<?php
$this->pageTitle=Yii::app()->name . ' - logo';
?>

<h1><span appapptagid="37"></span>Logo</h1>

<?php if(Yii::app()->user->hasFlash('createlogo')): ?>

<div class="flash-success">
	<?php echo Yii::app()->user->getFlash('createlogo'); ?>
</div>

<?php else: ?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
    'htmlOptions' => array(
      'class' => 'noajax',
      'enctype'=>'multipart/form-data',//can't upload by ajax
      'target' => 'hiddenifm'
    )
)); ?>

	<?php echo $form->errorSummary($model); ?>

  <table border="0">
    <tr>
      <td class="element"><?php echo $form->hiddenField($model,'id');?></td>
    </tr>
    <tr>
      <td class="label"><?php echo $form->labelEx($model,'logo'); ?></td>
      <td class="element"><?php echo $form->fileField($model,'logo'); ?><br/>
      <?php
      if($model->logo){
        echo '<br/>' . Chtml::link('<img src="/'. $model->logo .'" width="100px;"/>', '/' . $model->logo, array('target'=>'_blank'));
      }
    ?>
      </td>
    </tr>
    <tr>
      <td class="label"> </td>
      <td class="element"><?php echo CHtml::submitButton('Submit'); ?></td>
    </tr>
  </table>	
  
<?php $this->endWidget(); ?>

</div><!-- form -->

<?php endif; ?>

<iframe src="" name="hiddenifm" id="hiddenifm" style="display:none;"></iframe>



<?php $this->renderFile('protected/views/layouts/footer_subpanel.php'); ?>
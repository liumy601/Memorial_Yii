<?php $this->renderFile('protected/views/layouts/header_subpanel.php', array('title'=>'Control Panel')); ?>


<?php
$this->pageTitle=Yii::app()->name . ' - Create user';

if ($model->id > 0) {
  $title = 'Update user';
} else {
  $title = 'Create user';
}
?>

<h1><?php echo $title; ?></h1>

<?php if(Yii::app()->user->hasFlash('createuser')): ?>

<div class="flash-success">
	<?php echo Yii::app()->user->getFlash('createuser'); ?>
</div>

<?php else: ?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm'
//        array('htmlOptions' => 
//              array(
//                  'name'=>'EditView', 'id'=>'EditView', 
//                  'class'=>'noajax', 
//                  'onsubmit'=>'return check_form_users();'
//              ))
        ); ?>

	<?php echo $form->errorSummary($model); ?>

  <table border="0">
    <tr>
      <td class="label"><?php echo $form->labelEx($model,'username'); ?></td>
      <td class="element"><?php echo $form->textField($model,'username'); ?></td>
    </tr>
    <tr>
      <td class="label"><?php echo $form->labelEx($model,'password'); ?></td>
      <td class="element"><?php echo $form->passwordField($model,'password'); ?></td>
    </tr>
    <tr>
      <td class="label"><?php echo $form->labelEx($model,'email'); ?></td>
      <td class="element">
        <?php echo $form->textField($model,'email', array('onblur'=>'return validateEmail(this)', 'onfocus'=>'showWarning();')); ?>
        <span name="warning" style="display: none; color: #f00;">Warning:Email is not blank. Please input correct format email value.</span>
        <span name="error" style="display: none; color: #f00;">Error:Your input format is incorrect !!!</span>
        <span name="correct" style="display: none; color: #f00; ">Congratulate you, Your inputed email format is correct !</span>
      </td>
    </tr>
    <tr>
      <td class="label"><?php echo $form->labelEx($model,'firstname'); ?></td>
      <td class="element"><?php echo $form->textField($model,'firstname'); ?></td>
    </tr>
    <tr>
      <td class="label"><?php echo $form->labelEx($model,'lastname'); ?></td>
      <td class="element"><?php echo $form->textField($model,'lastname'); ?></td>
    </tr>
    
    <?php
     if (Yii::app()->user->type == 'super admin'){ 
   ?>
    
    <tr>
      <td class="label"><?php echo $form->labelEx($model,'company_id'); ?></td>
      <td class="element"><?php echo $form->dropDownList($model,'company_id', Company::getAll()); ?></td>
    </tr>
    
    <?php } ?>
  </table>
	
	<div class="row buttons">
		<?php echo CHtml::submitButton('Submit'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->

<?php endif; ?>
<script type="text/javascript">
 function check_form_users(){
   var email_value = $('#Users_email').val();
   
   if(email_value !== ''){
      var regObj = new RegExp(/^([a-zA-Z0-9_\.-])+@([a-zA-Z0-9_-])+((\.[a-zA-Z0-9_-]{2,3}){1,2})$/);

      if(regObj.test(email_value)){
        return true;
      }else{
        $('#Users_email').focus();
        $('#Users_email').css('background-color', '#f00');

        $('#Users_email').next().hide();
        $('#Users_email').next().next().next().hide();
        $('#Users_email').next().next().show();
        
        
        setTimeout(deleteCss1, 3000);
        return false;
      }
   }else{
      $('#Users_email').next().hide();
      $('#Users_email').next().next().hide();
      $('#Users_email').next().next().next().hide();
      
      $('#Users_email').focus();
      $('#Users_email').css('background-color', '#f00');
      
      setTimeout(deleteCss2, 3000);
      return false;
   }
 }
  
  function deleteCss1(){
   clearTimeout();
   
   $('#Users_email').css('background-color', '#fff');
   
   $('#Users_email').next().hide();
   $('#Users_email').next().next().next().hide();
   $('#Users_email').next().next().show();
 }
  
  function deleteCss2(){
   clearTimeout();
   
   $('#Users_email').next().hide();
   $('#Users_email').next().next().hide();
   $('#Users_email').next().next().next().hide();
   
   $('#Users_email').css('background-color', '#fff');
   $('#Users_email').focus();
 }
  
 function showWarning(){
    $('#Users_email').next().next().hide();
    $('#Users_email').next().next().next().hide();
    $('#Users_email').next().show();
 }
 
 function validateEmail(obj){
    var regObj = new RegExp(/^([a-zA-Z0-9_\.-])+@([a-zA-Z0-9_-])+((\.[a-zA-Z0-9_-]{2,3}){1,2})$/);
   
    var email_value = obj.value;

    if(email_value !== ''){
      if(!regObj.test(email_value)){
        $('#Users_email').next().hide();
        $('#Users_email').next().next().next().hide();
        $('#Users_email').next().next().show();
      }else{
        $('#Users_email').next().hide();
        $('#Users_email').next().next().hide();
        $('#Users_email').next().next().next().show();
      }
    }else{
        $('#Users_email').next().hide();
        $('#Users_email').next().next().hide();
        $('#Users_email').next().next().next().hide();
    }
   
 }
 
</script>

<?php $this->renderFile('protected/views/layouts/footer_subpanel.php'); ?>
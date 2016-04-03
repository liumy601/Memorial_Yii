<?php
$this->breadcrumbs=array(
  'Setup' => array('/site/setup'),
  'Users' => array('/users'),
	'Edit User',
);
?>
<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
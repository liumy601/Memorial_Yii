<?php 
if(Yii::app()->user->name != 'Guest' ){
	$company = Company::model()->findByPk(Yii::app()->user->company_id);
	$company_name = !empty($company) ? $company->name : '';
?>
<link rel="stylesheet" type="text/css" href="//fast.appcues.com/appcues.min.css">
<script src="//fast.appcues.com/17271.js"></script>
<script>
    Appcues.identify('<?php echo Yii::app()->user->uid; ?>', {
        email: '<?php echo Yii::app()->user->email; ?>',
        name: '<?php echo Yii::app()->user->firstname.' '.Yii::app()->user->lastname; ?>',
        // Additional user properties
    });
</script>
<!-- App Cues -->

<?php
}
?>
<!--white area, workflow-->

<div class="bodycontainer"><div id="BodyContent">
<table width="100%" cellspacing="0" cellpadding="0"><tbody><tr><td> 
<div id="leftPanel">
              
<!-- title, submenu -->
<div class="p15 mt5">
<div>
<div class="maininfo"><span id="cutomizebc"></span> 

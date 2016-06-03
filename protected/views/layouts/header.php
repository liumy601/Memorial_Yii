<?php 
if(Yii::app()->user->name != 'Guest' ){
	$company = Company::model()->findByPk(Yii::app()->user->company_id);
	$company_name = !empty($company) ? $company->name : '';
?>
<script>
  window.intercomSettings = {
    app_id: "k1tws933",
    name: "<?php echo Yii::app()->user->firstname.' '.Yii::app()->user->lastname; ?>", // Full name
    email: "<?php echo Yii::app()->user->email; ?>", // Email address
    created_at: 1312182000, // Signup date as a Unix timestamp
	company: "<?php echo $company_name; ?>"
  };
</script>
<script>(function(){var w=window;var ic=w.Intercom;if(typeof ic==="function"){ic('reattach_activator');ic('update',intercomSettings);}else{var d=document;var i=function(){i.c(arguments)};i.q=[];i.c=function(args){i.q.push(args)};w.Intercom=i;function l(){var s=d.createElement('script');s.type='text/javascript';s.async=true;s.src='https://widget.intercom.io/widget/k1tws933';var x=d.getElementsByTagName('script')[0];x.parentNode.insertBefore(s,x);}if(w.attachEvent){w.attachEvent('onload',l);}else{w.addEventListener('load',l,false);}}})()</script>

<!-- App cues -->
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
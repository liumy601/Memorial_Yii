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
	company: "<?php echo $company_name; ?>",
    "site": "app.memorialdirector.com"
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
} else {
?>
<!--white area, workflow-->
<script>
(function(i,s,o,g,r,a,m)
Unknown macro: {i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){ (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o), m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m) }
)(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
ga('create', 'UA-46600175-2', 'auto');
ga('send', 'pageview');
</script>
<script type='text/javascript'>
window.__wtw_lucky_site_id = 27428;

        (function() {
                var wa = document.createElement('script'); wa.type = 'text/javascript'; wa.async = true;
                wa.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://cdn') + '.luckyorange.com/w.js';
                var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(wa, s);
          })();
        </script>
<?php
}
?>
<div class="bodycontainer"><div id="BodyContent">
<table width="100%" cellspacing="0" cellpadding="0"><tbody><tr><td> 
<div id="leftPanel">
              
<!-- title, submenu -->
<div class="p15 mt5">
<div>
<div class="maininfo"><span id="cutomizebc"></span> 

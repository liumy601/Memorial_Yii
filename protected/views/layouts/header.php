<?php 
if(Yii::app()->user->name != 'Guest' ){
?>
<script>
  window.intercomSettings = {
    app_id: "k1tws933",
    name: "Jane Doe", // Full name
    email: "customer@example.com", // Email address
    created_at: 1312182000 // Signup date as a Unix timestamp
  };
</script>
<script>(function(){var w=window;var ic=w.Intercom;if(typeof ic==="function"){ic('reattach_activator');ic('update',intercomSettings);}else{var d=document;var i=function(){i.c(arguments)};i.q=[];i.c=function(args){i.q.push(args)};w.Intercom=i;function l(){var s=d.createElement('script');s.type='text/javascript';s.async=true;s.src='https://widget.intercom.io/widget/k1tws933';var x=d.getElementsByTagName('script')[0];x.parentNode.insertBefore(s,x);}if(w.attachEvent){w.attachEvent('onload',l);}else{w.addEventListener('load',l,false);}}})()</script>

<!-- App cues -->
<link rel="stylesheet" type="text/css" href="//fast.appcues.com/appcues.min.css">
<script src="//fast.appcues.com/17271.js"></script>
<script>
    Appcues.identify('1', {
        email: 'support@preferati.com',
        name: 'Support',
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

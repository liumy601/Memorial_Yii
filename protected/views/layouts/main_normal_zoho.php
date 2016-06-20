<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html><head><title><?php echo Yii::app()->name; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link rel="prefetch" href="/images/spacer.gif">
<link rel="shortcut icon" href="/images/favicon.ico" type="image/vnd.microsoft.icon">
<link rel="icon" href="/images/favicon.ico" type="image/vnd.microsoft.icon">
<link href="/css/crmcontemporarysecure.css<?php echo $GLOBALS['query_string']; ?>" rel="stylesheet" type="text/css">
<style type="text/css">
.scheTit {padding-right:10px;padding-left:5px;line-height:40px;color:#5cd3ff;font:bold 18px Arial, Helvetica, sans-serif}
.scheCon {
font-family:Arial, Helvetica, sans-serif;
font-size:13px;
color:#000;
padding-left:10px;padding-right:10px;
}
.scheCon b{
font-size: 14px;
}
.annoLink {
color:#5cd3ff;
font-family:Arial, Helvetica, sans-serif;
text-decoration:underline;
font-size:13px;  
}
a.annoLink:hover {
color:#5cd3ff;
cursor:pointer;
text-decoration:none;
}
</style>
<link rel="stylesheet" type="text/css" href="/assets/jquery/jui/css/base/jquery-ui.css" />
<link rel="stylesheet" type="text/css" href="/css/global.css<?php echo $GLOBALS['query_string']; ?>" />
<script type="text/javascript" src="/assets/jquery/jquery.js"></script>
<script type="text/javascript" src="/assets/jquery/jquery.form.js"></script>
<script type="text/javascript" src="/assets/jquery/jquery.history.js"></script>
<script type="text/javascript" src="/assets/jquery/jui/js/jquery-ui.min.js"></script>
<script type="text/javascript" src="/assets/jquery/jui/jquery.corner.js"></script>
<script type="text/javascript" src="/assets/jquery/picup.2.js"></script>
<!--[if lt IE 9]><script src="/assets/jquery/signature/build/flashcanvas.js<?php echo $GLOBALS['query_string']; ?>"></script><![endif]-->
<script type="text/javascript" src="/assets/jquery/signature/build/jquery.signaturepad.min.js<?php echo $GLOBALS['query_string']; ?>"></script>
<script type="text/javascript" src="/assets/jquery/signature/build/json2.min.js<?php echo $GLOBALS['query_string']; ?>"></script>
<link rel="stylesheet" href="/assets/jquery/signature/build/jquery.signaturepad.css<?php echo $GLOBALS['query_string']; ?>">

<?php if(Yii::app()->user->name != 'Guest' ): ?>
<?php
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
    "site":"app.memorialdirector.com"

  };
</script>
<script>(function(){var w=window;var ic=w.Intercom;if(typeof ic==="function"){ic('reattach_activator');ic('update',intercomSettings);}else{var d=document;var i=function(){i.c(arguments)};i.q=[];i.c=function(args){i.q.push(args)};w.Intercom=i;function l(){var s=d.createElement('script');s.type='text/javascript';s.async=true;s.src='https://widget.intercom.io/widget/k1tws933';var x=d.getElementsByTagName('script')[0];x.parentNode.insertBefore(s,x);}if(w.attachEvent){w.attachEvent('onload',l);}else{w.addEventListener('load',l,false);}}})()</script>

<!-- App cues -->
<script type='text/javascript'>
window.__wtw_lucky_site_id = 27428;

	(function() {
		var wa = document.createElement('script'); wa.type = 'text/javascript'; wa.async = true;
		wa.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://cdn') + '.luckyorange.com/w.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(wa, s);
	  })();
	</script>
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
<?php endif; ?>

<script type="text/javascript" src="<?php echo Yii::app()->params['appappSiteURL']; ?>/misc/appapp.js?v=1.5"></script>
<?php
if ($flashMsg = Yii::app()->user->getFlash('')) {
 echo '<script language="javascript">
   $(document).ready(
    function(){
     showTip("'. str_replace('"', '\"', $flashMsg).'");
   });
   </script>';
}
?>
<script type="text/javascript" src="/themes/classic/js/global.js<?php echo $GLOBALS['query_string']; ?>"></script>
<?php
echo '<script language="javascript">
  var siteUser = \''. Yii::app()->user->name .'\';
  var isIOS = '. (preg_match( '/(iPod|iPhone|iPad)/', $_SERVER[ 'HTTP_USER_AGENT' ]) ? 'true' : 'false') .';</script>';
?>
</head>

<body>
<div id="ztinotifier" style="position:absolute;right:0px;bottom:0px;width:300px;display:block;z-index:1000"></div>
<div id="basic" onclick="hideMenu(event)">
  <div class="topdivbg1" id="topdivbg">
    <div class="topdivcontainer">
      
      <table width="100%" border="0" cellspacing="0" cellpadding="0" class="topBg"><tr>
      <td rowspan="2" class="logoLayer" id="logoTD"> 
<div class="logo">
  <?php 
  if(Yii::app()->user->name != 'Guest'){
      $connection = Yii::app()->db;
      
      //get company id
//      $command = $connection->createCommand("select company_id from users where username= :username");
//      $command->bindParam(':username', Yii::app()->user->id);
//      $company_id = $command->queryScalar();

      //get logo
      $command = $connection->createCommand("select logo from company where id=:id");
      $command->bindParam(':id', Yii::app()->user->company_id);
      $logo = $command->queryScalar();
      
      if($logo != '' && file_exists($logo)){
        echo '<img src="/'.$logo.'" height="37">';
//        echo '<img src="/'.$logo.'" height="100">';
      }else{
        echo '<img src="/files/memorial_logo.png" height="37">';
      }
  }else{
      echo '<img src="/files/memorial_logo.png" height="37">';
  }
  ?>
</div> 
</td>

<td class="topLinkLayer" valign="top">
<style type="text/css">
#cvTable .select, .bigSelect,#detailrelatedMainlinks{background-color:#F3F0EB !important}
body{
background-color: #F3F0EB  !important;
}
.newMenuTable{
background-color: #222222 !important;
}
.newMenuTable .sel{
background-color: #222222  !important;
color: #FFFFFF !important;
}
.newMenuTable .menuOn,.newMenuTable .menuOff{
color: #FFFFFF  !important;
}
</style>
<div id="tmC">
 
<div class="floatR">
  <table cellspacing="0" cellpadding="0" border="0">
  <tr>
    <?php
     if(Yii::app()->user->name == 'Guest'){//Guest
   ?>
      <td valign="top" nowrap class="welcome" id="welcome" style="">
        <a href="/site/login" class='noajax topLink'>Sign In</a>
      </td>
    <?php } else {//logged user ?>
      <td class="myArea">
        <!--<a href="#" class="myAreaLink" ></a>-->
        <?php 
        $trialExpire = false;
        
        if(Yii::app()->user->type == 'super admin'){ 
          echo ' <a href="/admin/company" class="topLink"><img src="/images/control_panel2.png" alt="Control panel" title="Control panel"/></a>'; 
        } else if (Yii::app()->user->type == 'admin' || Yii::app()->user->type == 'staff'){ 
          $connection = Yii::app()->db;
          $trial = $connection->createCommand("select trial, trial_end from users where id=" . Yii::app()->user->uid)->queryRow();
          if ($trial['trial'] == 1) {
            $now = time();
            
            if ($trial['trial_end'] > 0 && $now < $trial['trial_end']) {
              echo '<span style="margin-right:30px;">' . (ceil(($trial['trial_end'] - $now)/86400)) .' days remaining on trial.</span>';
            } else {
              $trialExpire = true;
              echo '<span style="margin-right:30px;color:red">Trial Expired (<a class=noajax" href="/site/updateprofile">Renew</a>)</span>';
            }
          } else {
            //get plan
			/*
            $current_plan = $connection->createCommand("select plan_title from user_subscription where uid=" . Yii::app()->user->uid ." order by id desc limit 1")->queryScalar();
            echo '<span style="margin-right:30px;">Current plan: <b>' . $current_plan .'</b></span>';
			*/
          }
//          echo '<a href="/site/appappplans">Try other plans</a> ';
          echo '<a href="/admin/user" class="topLink"><img src="/images/control_panel2.png" alt="Control panel" title="Control panel"/></a>'; 
        }
     ?>
        <?php if(0) : ?>
			<?php if (Yii::app()->user->name != 'Guest' && (Yii::app()->user->type == 'admin' || Yii::app()->user->type == 'staff')){  ?>
			  <div style="text-align: right;margin-top:5px;"><a class="noajax" href="/site/updateprofile">Account and Billing</a></div>
			<?php } ?>
		<?php endif; ?>
      </td>
      
      <td valign="top" nowrap class="welcome" id="welcome">
      Logged as <strong><?php echo Yii::app()->user->name; ?></strong> [ <a href='/site/logout'  class='noajax'>Logout</a> ]
      </td>
    <?php } ?>
  </tr>
</table>
</div>
</div>
<div class="clearR"></div>
<div id="tmCT">&nbsp;</div>


</td>
</tr>
</table>
      
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="newMenuTable"><tr><td class="menuLayer" id="menuTD" nowrap><div id="menuinhome">
<input type="hidden" name="skintype" value="contemporary">

<div id="menuContent" class="menu"><div>
<div class="newmenu" id="change_menu">
<?php
if(Yii::app()->user->name != 'Guest' && !$trialExpire){
  if (Yii::app()->user->type == 'super admin'){ 
    $headMenus = array(
      'Home' => '/',
      'Company' => '/admin/company',
      'Company Admin' => '/admin/user',
      'Company Staff' => '/admin/companystaff',
	  'Templates' => '/template',
      'Config' => '/admin/taxconfig',
    );
  } else if (Yii::app()->user->type == 'admin' || Yii::app()->user->type == 'staff'){ 
    $headMenus = array(
      'Home' => '/',
      'Decedents' => '/decedent',
      'Tasks' => '/task',
      'Inventory' => '/inventory',
      'Templates' => '/template',
      'My Account' => '/users/account',
    );
  }
} else {//guest
  $headMenus = array(
    'Home' => 'http://memorialdirector.com',
  );
}
  
  foreach ($headMenus as $hmenu=>$hmenuLink) {
    //tab access
    $permOP = str_replace(array('&', ' '), array('', '_'), $hmenu);
    
//    if(Yii::app()->user->name == 'Guest') continue;
    
    
    if (Yii::app()->controller->id == 'site') {
      $controllerId = 'Home';
    } else {
      $controllerId = Yii::app()->controller->id .'s';
    }
    
    if(is_array($hmenuLink)){
      $subMenu = $hmenuLink['subMenu'];
      $hmenuLink = $hmenuLink['hmenuLink'];
    } else {
      $subMenu = null;
    }
    
    if(Yii::app()->user->name == 'Guest'){
      $noajaxLink = ' noajax';//when guest, can't use ajax link, or it will redirect to login, and show duplicate header
    } else {
      $noajaxLink = '';
    }
    
    $hmenuText = strtolower(str_replace(array(' ', '_', '&'), '', $hmenu));
    if ($hmenuText == strtolower($controllerId)) {
    ?>
      <a onclick="tabClicked(this);<?php if(is_array($subMenu)){ echo ''; } ?>" href="<?php echo $hmenuLink; ?>" 
         class="sel topNaveLink<?php echo $noajaxLink; ?>" id="tab_<?php echo $hmenuText; ?>" pagetitle="<?php echo $hmenu; ?>"><nobr> <?php echo $hmenu; ?></nobr></a> 
    <?php
    } else {
    ?>
      <a onclick="tabClicked(this);<?php if(is_array($subMenu)){ echo ''; } ?>" href="<?php echo $hmenuLink; ?>" class="topNaveLink<?php echo $noajaxLink; ?>" id="tab_<?php echo $hmenuText; ?>" pagetitle="<?php echo $hmenu; ?>"><nobr> <?php echo $hmenu; ?></nobr></a>
    <?php
    }
    
    //submenu
    if(is_array($subMenu)){
      echo '<ul class="submenu">';
      foreach($subMenu as $subMenuTitle=>$subMenuLink){
        echo '<li><a href="'.$subMenuLink.'">'.$subMenuTitle.'</a></li>';
      }
      echo '</ul>';
    }
    
}
?>


<?php


?>

</div>
</div>
<div style="clear:both;"></div></div></div>
</td>

<td width="270">
<?php if(Yii::app()->user->name != 'Guest'){ ?>
<table cellpadding="0" cellspacing="0">
  <tr>
    <td width="30" nowrap="nowrap"> 
      <div class="qicon"><img onclick="showHideSubMenu(event)" class="createNewIcon" src="/images/spacer.gif"  id="createNew" title="New"></div>
      <div id="createsubmenu" class="ddMenus">
        <div class="menuDiv">
          <table cellspacing="5" class="menuTable"><tr><td valign="top"> 
            <a id="submenu_Customers" onclick="tabClicked(document.getElementById('tab_customers'));" href="/decedent/create" class="subMenuLink">New Customer</a>
            <a id="submenu_Task" onclick="tabClicked(document.getElementById('tab_tasks'));" href="/task/create" class="subMenuLink">New Task</a>
            <a id="submenu_Template" onclick="tabClicked(document.getElementById('tab_templates'));" href="/template/create" class="subMenuLink">New Template</a>
            </td>
            <td valign="top"> 
            <a id="submenu_Inventory" onclick="tabClicked(document.getElementById('tab_inventory'));" href="/inventory/create" class="subMenuLink">New Inventory</a>
            <a id="submenu_Package" onclick="tabClicked(document.getElementById('tab_inventory'));" href="/package/create" class="subMenuLink">New Package</a>
            </td></tr>
          </table>
        </div>
      </div>
    </td>
    <td nowrap="nowrap"> 
      <div class="newsearchouterdiv" id="newsearchouterdiv" onclick="this.className='newsearchouterdivsel',sE(event)">
        <form name="searchForm" id="searchForm" style="float:left;" method="post" action="/site/search">
          <div class="newsearchimg" onclick="togglesearchmenu(event)"><img id="newsearchoption" width="18" height="13" src="/images/spacer.gif" class="searchall"/></div>
          
          <input class="newsearchbox" id="searchword" name="searchword" maxlength="250" type="text" value="" onfocus="togglesearchmenu(event,'hide')" />
          <div id="newsearchcatagory" class="newsearchcatagory" style="display:none;" onclick="stopEvent(event);">
            <a href="javascript:;"><label><input type="checkbox" class="pL5" name="sModule[]" value="AllEntities" onclick="checkAllSearchform(this)"><span class="pL10" onclick="">All</span></label></a> 
            <a href="javascript:;"><label><input type="checkbox" class="pL5" name="sModule[]" value="Customer"/><span class="pL10">Customer</span></label></a> 
            <a href="javascript:;"><label><input type="checkbox" class="pL5" name="sModule[]" value="Task"/><span class="pL10">Task</span></label></a> 
            <a href="javascript:;"><label><input type="checkbox" class="pL5" name="sModule[]" value="Inventory"/><span class="pL10">Inventory</span></label></a> 
            <a href="javascript:;"><label><input type="checkbox" class="pL5" name="sModule[]" value="Package"/><span class="pL10">Package</span></label></a> 
            <a href="javascript:;"><label><input type="checkbox" class="pL5" name="sModule[]" value="Template"/><span class="pL10">Template</span></label></a> 
          </div>
        </form>
      </div>
      <img id="newSearchArrow" onclick="togglesearchmenu()" width="18" height="18" src="/images/spacer.gif" class="sort_desc" /> 
    </td>
  </tr>
</table>
<?php } ?>
</td>
</tr>
</table>
</div><!--topdivcontainer-->
</div><!--topdivbg-->

  
<div id='show' class="bodycontainer">
  <?php echo $content; ?>
  
  
</div><!--show-->

<div class="footernewClass">
    <div style=" font-size:10px; text-align:center; padding-top:5px;">
    <a href="/privacy.html" class="link" target="_blank">Privacy Policy</a><span class="sep">|</span><a href="/terms.html" class="link" target="_blank">Terms of Service</a><span class="sep">|</span><span class="small">&copy; 2012 NW Management &nbsp; All rights reserved. </span></div>
</div><!-- footernewClass -->
  
</div><!--basic-->

<div id="topmsg" class="loadingsplash"></div>
</body>
</html>

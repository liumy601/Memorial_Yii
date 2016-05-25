<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title><?php echo CHtml::encode($this->pageTitle); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta name="language" content="en"/>
<?php $query_string = '?K'; ?>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7"/>
<link rel="prefetch" href="/images/spacer.gif">
<link rel="shortcut icon" href="/images/favicon.ico" type="image/vnd.microsoft.icon">
<link rel="icon" href="/images/favicon.ico" type="image/vnd.microsoft.icon">
<link href="/css/crmcontemporarysecure.css<?php echo $GLOBALS['query_string']; ?>" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="/assets/jquery/jui/css/base/jquery-ui.css" />
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/global.css<?php echo $GLOBALS['query_string']; ?>" />
<script type="text/javascript" src="/assets/jquery/jquery.js"></script>
<script type="text/javascript" src="/assets/jquery/jquery.form.js"></script>
<script type="text/javascript" src="/assets/jquery/jquery.history.js"></script>
<script type="text/javascript" src="/assets/jquery/jui/js/jquery-ui.min.js"></script>
<script type="text/javascript" src="/assets/jquery/jui/jquery.corner.js"></script>
<script type="text/javascript" src="/assets/jquery/picup.2.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->baseUrl . '/themes/classic/js/global.js'; ?><?php echo $GLOBALS['query_string']; ?>"></script>
<!--[if lt IE 9]><script src="/assets/jquery/signature/build/flashcanvas.js<?php echo $GLOBALS['query_string']; ?>"></script><![endif]-->
<script type="text/javascript" src="/assets/jquery/signature/build/jquery.signaturepad.min.js<?php echo $GLOBALS['query_string']; ?>"></script>
<script type="text/javascript" src="/assets/jquery/signature/build/json2.min.js<?php echo $GLOBALS['query_string']; ?>"></script>
<link rel="stylesheet" href="/assets/jquery/signature/build/jquery.signaturepad.css<?php echo $GLOBALS['query_string']; ?>">
<script type="text/javascript" src="<?php echo Yii::app()->params['appappSiteURL']; ?>/misc/appapp.js?v=1.2"></script>
</head>

  
  <body<?php if (!Yii::app()->params['noPrintOnLoad']) { ?> onload="javascript:window.print();"<?php } ?>>
    <!--shootcut, content-->
    <table cellpadding="0" cellspacing="0" border="0" width="100%" >
      <tr>
        <td id="main">
          <!--Main content end-->
          <?php echo $content; ?>
          <!--Main content end-->
        </td>
      </tr>
    </table>
  </body>
</html>
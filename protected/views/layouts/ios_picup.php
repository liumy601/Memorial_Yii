<?php
if( preg_match( '/(iPod|iPhone|iPad)/', $_SERVER[ 'HTTP_USER_AGENT' ] )) {
  $form_uniqid = uniqid('', true);
?>
ccc
<script type="text/javascript">
  //give the filefield Picup2
  $(document).ready(function () {
    if(isIOS){
      $('#<?php echo $form ?>').prepend('<input type="hidden" name="form_uniqid" id="form_uniqid" value="<?php echo $form_uniqid; ?>"/>');
      
      $('#<?php echo $form ?> input[type=file]').each(
        function(){
          var postFileParamName = $(this).attr('name').replace('[]', '');
          postFileParamName = postFileParamName.replace('[', '');
          postFileParamName = postFileParamName.replace(']', '');
          
          var currentParams = {
            'postFileParamName' : postFileParamName,
            'postURL' : 'http%3A//<?php echo str_replace('http://', '', Yii::app()->params['siteURL']); ?>/iphone_upload.php',
            'postValues' : 'form_uniqid%3D<?php echo $form_uniqid; ?>',
            'callbackURL' 		: 'http://<?php echo str_replace('http://', '', Yii::app()->params['siteURL']); ?>/iphone_callback.php',				
            'referrername' 		: escape('Northwest Management'),
            'referrerfavicon' 	: escape('http://<?php echo str_replace('http://', '', Yii::app()->params['siteURL']); ?>/images/favicon.ico'),
            'purpose'               : escape('Upload file'),
            'debug' 		: 'false',
            'returnThumbnailDataURL': 'true',
            'thumbnailSize'         : '80'
          };
          Picup2.convertFileInput($(this).attr('id'), currentParams);
        }
      );
    }
  });
</script>
<?php
}
?>
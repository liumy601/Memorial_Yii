
<?php $form=$this->beginWidget('CActiveForm', array('htmlOptions' => 
    array('name'=>'EditView', 'id'=>'EditView', 
        'class'=>'noajax', 
        'enctype'=>'multipart/form-data',
        'onsubmit'=>"return(check_form('EditView'));"
    ))); 

if ($model->id > 0) {
  $title = 'Update action';
} else {
  $title = 'Create action';
}

list($usec, $sec) = split(' ', microtime());
$iPhoneImgCacheID = substr($sec+$usec, 0, 14);

?>

  <input type="hidden" name="iPhoneImgCacheID" value="<?php echo $iPhoneImgCacheID; ?>"/>

   <table>
        <tr>
          <td width="12.5%" class="label">photo:</td>
          <td width="35%" class="element">
            <input id="ytAction_photo" type="hidden" value="" name="Action[photo]">
            <input name="Action[photo]" id="Action_photo" type="file">
            <input name="Action[image]" id="Action_image" type="file">
            <input name="idcard" id="idcard" type="file">
          </td>
        </tr>
      </tbody>
    </table>

	<table width="95%" border="0" cellpadding="0" cellspacing="0"><tbody><tr><td align="center"></div>
    <input title="Save" class="button" onclick="" type="submit" name="button" value="Save"/>
  </td></tr></tbody></table>
  
  
<?php $this->endWidget(); ?>
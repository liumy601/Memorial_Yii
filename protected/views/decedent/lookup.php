<div id="ShowCustomViewDetails">
  
<!--list-->
<div id="idForCV" class="idForCVpadding">
  <div class="listviewBorder">
      
<?php
$fields = array(
//    'full_legal_name_f'=>'20%',
    'full_legal_name'=>'20%',
    'date_of_death'=>'20%',
    'assigned_to'=>'20%',
    'enteredtm'=>'20%',
);
?>
<!--Search-->
<?php $form=$this->beginWidget('CActiveForm', array('htmlOptions' => array('name'=>'EditViewLookup', 'class'=>'noajax lookup_filter_form', 'id'=>'filter_form'))); ?>

<div class="filters">
<table width="100%" cellspacing="0" cellpadding="0" border="0" class="tabForm">
  <tbody>
    <tr>
      <td class="dataLabel" nowrap="nowrap" width="120">Full Legal Name</td>
      <!--<td class="dataField" nowrap="nowrap" width=""><?php // echo $form->textField($customer, 'full_legal_name_f'); ?></td>-->
      <td class="dataField" nowrap="nowrap" width=""><?php echo $form->textField($customer, 'full_legal_name'); ?></td>
    </tr>
  </tbody>
</table>
<input class="button" type="hidden" name="pageSize" id="pageSize" value="10">
<input class="button" type="hidden" name="page" id="page" value="1">
<input class="button" type="hidden" name="clear" id="clear" value="0">
<input title="Search" class="button" type="submit" name="button" value="Search" id="search_form_submit" onclick="$('#clear').val(0);">
<input title="Clear" class="button" type="submit" name="button" value="Clear" onclick="$('#clear').val(1);">
</div>

<?php $this->endWidget(); ?>

<br/>
<!--List-->
<table width="100%" border="0" cellpadding="0" cellspacing="0" id="listViewTable" class="listview">
  <tbody>
    <tr height="20" class="tableHeadBg" id="listviewHeaderRow">
      <td width="20"> <div class="indIcon" style="visibility:hidden; width:23px;"><a class="link" style="padding-left:6px;" href="javascript:void(0);"><img src="/images/spacer.gif" height="19" width="29" border="0"></a></div></td>
      <?php
      foreach ($fields as $field=>$width) {
     ?>
      
      <td scope="col" width="<?php echo $width; ?>" class="listViewThS1" nowrap="">
        <div style="white-space: nowrap;" width="100%" align="left">
          <?php echo CHtml::encode($customer->getAttributeLabel($field)); ?>&nbsp;&nbsp;
        </div>
      </td>
      
      <?php
      }
     ?>
    </tr>
  </tbody>
    
    <tbody id="lvTred">
    <?php
      while($data = $dataProvider->read()){
        $data = (object)$data;
    ?>
    
    <tr height="20" id="student_<?php echo $data->id; ?>">
      <td width="20"> 
        <div align="center" id="divstudent_<?php echo $data->id; ?>" class="indIcon" style="visibility: hidden; ">
          <!--<a class="link noajax" href="#" itemText="<?php // echo $data->full_legal_name_f . ' '. $data->full_legal_name_m . ' '. $data->full_legal_name_l; ?>" itemID="<?php echo $data->id; ?>" onclick="lookupSelectItem(this);return false;">Select</a>&nbsp;&nbsp;&nbsp;-->
          <a class="link noajax" href="#" itemText="<?php echo $data->full_legal_name; ?>" itemID="<?php echo $data->id; ?>" onclick="lookupSelectItem(this);return false;">Select</a>&nbsp;&nbsp;&nbsp;
        </div>
      </td>
<?php
$i = 0;

foreach ($fields as $field => $width) {
  $i++;
//  if ($field == 'full_legal_name_f') {
//    $data->$field = $data->full_legal_name_f . ' '. $data->full_legal_name_m . ' '. $data->full_legal_name_l;
//  } else if ($field == 'date_of_death') {
  if ($field == 'full_legal_name') {
    $data->$field = $data->full_legal_name;
  } else if ($field == 'date_of_death') {
    $data->$field = $data->time_of_death_h . ':'. $data->time_of_death_m .' '. $data->time_of_death_z .' on ' . date('F d, Y', strtotime($data->$field));
  } else if ($field == 'enteredtm') {
    $data->$field = date('m/d/Y H:i', $data->$field);
  } else if ($field == 'assigned_to') {
    $assignedToUser = Users::model()->findByPk($data->assigned_to);
    $data->$field = $assignedToUser->username;
  }
?>

  <td scope="row" align="left" valign="top" class="<?php echo ($i % 2) ? 'oddListRowS1' : 'evenListRowS1'; ?>" bgcolor="#ffffff">
    <?php // if($field == 'full_legal_name_f') { ?>
    <?php if($field == 'full_legal_name') { ?>
    <a style="display:block;" id="detailview_action_<?php echo $data->id; ?>" pagetitle="View Customer" href="/customer/view/<?php echo $data->id; ?>" class="listViewTdLinkS1 noajax" target="_blank"><?php echo CHtml::encode($data->$field); ?></a>
    <?php } else { ?>
    <?php echo CHtml::encode($data->$field); ?>
    <?php } ?>
  </td>

<?php } ?>
</tr>
<?php } ?>
</tbody></table><!--end of List view-->
  



<!--pager-->
<div class="listviewBorder123"><div class="listviewButtonLayer"><table width="100%"><tbody><tr><td id="lvBottomButtonTD">  </td><td> <div align="right"> 
<input name="fromIndex" id="fromIndex" type="hidden" class="textfieldsmall" value="1" size="3" maxlength="3"><input name="toIndex" id="toIndex" type="hidden" class="textfieldsmall" value="1" size="3" maxlength="3"><table width="100%" border="0" cellspacing="0" cellpadding="0"><tbody><tr><td> <table height="25" border="0" cellpadding="0" cellspacing="1" align="right"><tbody><tr><td nowrap="nowrap" align="center"> <div class="bodyText"><input type="hidden" name="OnSelect" value="false" class="selectsmall">
<select name="currentOption" id="currentOption" onchange="return changePageSize(this)">
<option value="10">10 &nbsp; Records per page</option>
<option value="20">20 &nbsp; Records per page</option></select>
</div></td>
<td align="right" nowrap=""> <div id="tcDiv">&nbsp;&nbsp;<nobr>Total: <?php echo $GLOBALS['itemCount']; ?></nobr>&nbsp;&nbsp;</div></td>
</tr></tbody></table></td>
<td width="110"> <table height="25" border="0" cellpadding="0" cellspacing="1"><tbody><tr>
<td class="viewbg" align="right"> 
  <?php if($GLOBALS['page'] == 1){ ?>
  <div class="previousDisabled"></div>
  <?php } else { ?>
  <div class="previous" onclick="changePage(<?php echo $GLOBALS['page']-1; ?>)"></div>
  <?php } ?>
</td>
<td id="displayCount" nowrap=""> <b><?php echo $GLOBALS['itemStart']; ?></b> to <b><?php echo $GLOBALS['itemEnd']; ?></b> </td>
<td>  
  <?php if($GLOBALS['page'] == $GLOBALS['pageCount']){ ?>
  <div class="nextDisabled"></div>
  <?php } else { ?>
  <div class="next" onclick="changePage(<?php echo $GLOBALS['page']+1; ?>)"></div>
  <?php } ?>
</td>
</tr></tbody></table></td></tr></tbody></table>
</div></td></tr></tbody></table></div>
</div><!--listviewBorder123-->

</div><!--listviewBorder-->
<br/>

  </div><!--idForCV-->
</div><!--ShowCustomViewDetails-->

<script type="text/javascript">
  bindEventListView();
  $('#pageSize').val(<?php echo $pageSize; ?>);
  $('#currentOption').val(<?php echo $pageSize; ?>);
</script>
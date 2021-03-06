<div id="ShowCustomViewDetails">
  
<!-- title, submenu -->
<div class="cvpadding">
  <table id="cvTable" width="100%" border="0" cellspacing="0" cellpadding="1">
    <tbody>
      <tr>
        <td> 
          <div class="floatL"><h1>Inventory</h1></div>
          <span class="pL65" id="newcreateparent">  </span>
        </td>
        <td>
          
        </td> 
        <td align="right"> 
          <!--shotcut buttons-->
          <?php echo CommonRender::shotCuts(); ?>
        </td>
      </tr>
    </tbody>
  </table>
</div><!--cvpadding-->


<!--list-->
<div id="idForCV" class="idForCVpadding">
  <div class="listviewBorder">
      <div class="listviewButtonLayer" id="listviewButtonLayerDiv">
        <div class="pwie">
          <table width="100%" border="0" cellpadding="0" cellspacing="0" class="lvblayerTable secContentbb">
            <tbody>
              <tr><td class="bT"> </td></tr>
              <tr><td></td></tr>
              <tr>
               <td>
                    <!--Delete, Refresh-->
                    <table border="0" cellspacing="0" cellpadding="0" align="left">
                      <tbody><tr><td class="pL10"> 
                      </td></tr></tbody>
                    </table>
                    <img src="/images/spacer.gif" id="fetchdate_title" title="" class="refbtn" onclick="refreshListView();"><span appapptagid="25"></span>
                </td>
                <td align="right"> <span id="placePushToDB"></span> </td>
                <td align="right" class="printTitle"></td>   
                <td width="10"> <div align="right" id="topNewNavigation" style="display:none;"></div></td></tr></tbody></table>
        </div><!--pwie-->
      </div><!--listviewButtonLayer-->
            
            <?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$fields = array(
    'name'=>'90%',
);

?>
<?php $form=$this->beginWidget('CActiveForm', array('action'=>'/inventory','htmlOptions' => array('name'=>'EditView', 'id'=>'filter_form'))); ?>

<div class="filters">
<table width="100%" cellspacing="0" cellpadding="0" border="0" class="tabForm">
  <tbody>
    <tr>
      <td class="dataLabel" nowrap="nowrap" width="120"><?php echo CHtml::encode($inventory->getAttributeLabel('vendor')); ?></td>
      <td class="dataField" nowrap="nowrap" width=""><?php echo $form->textField($inventory, 'vendor'); ?></td>
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
      <td scope="col" width="" class="listViewThS1" nowrap=""><div style="white-space: nowrap;" width="100%" align="left">Name</div></td>
      <td scope="col" width="" class="listViewThS1" nowrap=""><div style="white-space: nowrap;" width="100%" align="left">Vendor</div></td>
      <td scope="col" width="" class="listViewThS1" nowrap=""><div style="white-space: nowrap;" width="100%" align="left">SKU</div></td>
      <td scope="col" width="" class="listViewThS1" nowrap=""><div style="white-space: nowrap;" width="100%" align="left">Retail</div></td>
      <td scope="col" width="" class="listViewThS1" nowrap=""><div style="white-space: nowrap;" width="100%" align="left">Cost</div></td>
      <!--<td scope="col" width="" class="listViewThS1" nowrap=""><div style="white-space: nowrap;" width="100%" align="left">Type</div></td>-->
      <td scope="col" width="" class="listViewThS1" nowrap=""><div style="white-space: nowrap;" width="100%" align="left">Category</div></td>
      <!--<td scope="col" width="" class="listViewThS1" nowrap=""><div style="white-space: nowrap;" width="100%" align="left">Assigned</div></td>-->
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
    
    
<tr height="20" id="action_<?php echo $data->id; ?>">
  <td width="20"> 
    <div align="center" id="divaction_<?php echo $data->id; ?>" class="indIcon" style="visibility: hidden; ">
      <a class="link" id="listViewEdit_action_<?php echo $data->id; ?>" href="/inventory/update/<?php echo $data->id; ?>">Edit</a>&nbsp;&nbsp;&nbsp;
      <a class="link" id="listViewDelete_action_<?php echo $data->id; ?>" href="#" url="/inventory/delete/<?php echo $data->id; ?>" onclick="return(deleteRecord(this));">Delete</a>
    </div>
  </td>
<?php
//$i = 0;
//foreach ($fields as $field => $width) {
//  $i++;
?>

  <td scope="row" align="left" valign="top" class="<?php echo ($i % 2) ? 'oddListRowS1' : 'evenListRowS1'; ?>" bgcolor="#ffffff">
    <a style="display:block;" id="detailview_action_<?php echo $data->id; ?>" pagetitle="View Inventory" href="/inventory/view/<?php echo $data->id; ?>" class="listViewTdLinkS1">
      <?php echo CHtml::encode($data->name); ?>
    </a>
  </td>
  <td>  <?php echo CHtml::encode($data->vendor); ?></td>
  <td>  <?php echo CHtml::encode($data->sku); ?></td>
   <td> <?php echo CHtml::encode($data->retail); ?></td>
   <td> $<?php echo CHtml::encode($data->cost); ?></td>
   <!--<td> <?php // echo CHtml::encode($data->type); ?></td>-->
   <td> <?php echo CHtml::encode($data->category); ?></td>

<?php // } ?>
</tr>
<?php } ?>
</tbody></table>


<!--pager-->
<div class="listviewBorder123"><div class="listviewButtonLayer"><table width="100%"><tbody><tr><td id="lvBottomButtonTD">  </td><td> <div align="right"> 
<input name="fromIndex" id="fromIndex" type="hidden" class="textfieldsmall" value="1" size="3" maxlength="3"><input name="toIndex" id="toIndex" type="hidden" class="textfieldsmall" value="1" size="3" maxlength="3"><table width="100%" border="0" cellspacing="0" cellpadding="0"><tbody><tr><td> <table height="25" border="0" cellpadding="0" cellspacing="1" align="right"><tbody><tr><td nowrap="nowrap" align="center"> <div class="bodyText"><input type="hidden" name="OnSelect" value="false" class="selectsmall">
<select name="currentOption" id="currentOption" onchange="return changePageSize(this)">
<option value="10">10 &nbsp; Records per page</option>
<option value="20">20 &nbsp; Records per page</option>
<option value="30">30 &nbsp; Records per page</option>
<option value="40">40 &nbsp; Records per page</option>
<option value="50">50 &nbsp; Records per page</option></select>
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

<h1 style="font-size: 16px;">Packages <span appapptagid="26"></span></h1>
<div id="listViewPackage" style="background-color: white;">
<!--Package-->
<table border="0" width="95%" align="center" cellspacing="0" cellpadding="0" class="relCenterBg"><tbody><tr height="25">
<td width="140" nowrap=""> </td>
<td align="right"> <div id="ract_" name="raction"> </td>
</tr></tbody>
</table>
<div id="contact_" style="display:block">
<table border="0" width="95%" cellspacing="0" cellpadding="0" align="center">
<tbody><tr><td colspan="2"> 
<table border="0" width="100%" cellspacing="0" cellpadding="0" class="rellist">
<tbody><tr class="tableHeadBg"><td>&nbsp;</td> 
<td nowrap>Name</td>
<td>Products</td>
<td nowrap>Total Prices</td>
</tr>
<?php
while ($row = $packageDataProvider->read()) {
?>
<tr>
<td nowrap="" width="12%" class="tableData"> <div align="left">&nbsp;
  <a class="sl" href="/package/update/<?php echo $row['id']; ?>">Edit</a>
  <span class="sep">|</span> 
  <a href="#" class="listViewTdToolsS1" url="/package/delete/<?php echo $row['id']; ?>" onclick="if(confirm('Are you sure delete this record?')){ ajaxRequest(this); } return false;">Remove</a>
</div>
</td>
<td class="tableData"> <a class="f12" href="/package/view/<?php echo $row['id']; ?>"><?php echo $row['name'] ?></a> </td>
<td class="tableData"> <?php echo Package::getProductNames($row['id']) ?> </td>
<td class="tableData"> $<?php echo Package::getProductPrices($row['id']) ?> </td>
</tr>
<?php
}
?>

</tbody></table>
</td></tr>
<tr class="rellistbL"><td align="left"><div id="ra">
<a href="/package/create" class="rellistNew" class="noajax">New package</a></div></td> 
<td align="right">&nbsp;<div align="right" class="listNav"></div></td></tr> <!-- Field Data goes here --></tbody></table>
</div><br>
<!--Package --------end-->
</div>


  </div><!--idForCV-->
</div><!--ShowCustomViewDetails-->
<br/>
<br/>
<br/>

<script type="text/javascript">
  bindEventListView();
  $('#pageSize').val(<?php echo $pageSize; ?>);
  $('#currentOption').val(<?php echo $pageSize; ?>);
</script>
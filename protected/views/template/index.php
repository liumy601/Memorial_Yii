<div id="ShowCustomViewDetails">
  
<!-- title, submenu -->
<div class="cvpadding">
  <table id="cvTable" width="100%" border="0" cellspacing="0" cellpadding="1">
    <tbody>
      <tr>
        <td> 
          <div class="floatL"><h1>Templates</h1></div>
          <span class="pL65" id="newcreateparent">  </span>
        </td>
        <td>
          
        </td> 
        <td align="right"> 
          <!--shotcut buttons-->
          <input id="New_Template" type="button" class="btn createNewBtn createNewBtn" value="New Template" pagetitle="New Template" onclick="document.location.href='/template/create'">
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
                    <img src="/images/spacer.gif" id="fetchdate_title" title="" class="refbtn" onclick="refreshListView();">
                </td>
                <td align="right"> <span id="placePushToDB"></span> </td>
                <td align="right" class="printTitle"></td>   
                <td width="10"> <div align="right" id="topNewNavigation" style="display:none;"></div></td></tr></tbody></table>
        </div><!--pwie-->
      </div><!--listviewButtonLayer-->
            
          

<!--List-->
<table width="100%" border="0" cellpadding="0" cellspacing="0" id="listViewTable" class="listview">
  <tbody>
    <tr height="20" class="tableHeadBg" id="listviewHeaderRow">
      <td width="20"> <div class="indIcon" style="visibility:hidden; width:23px;"><a class="link" style="padding-left:6px;" href="javascript:void(0);"><img src="/images/spacer.gif" height="19" width="29" border="0"></a></div></td>
      <td scope="col" width="<?php echo $width; ?>" class="listViewThS1" nowrap="">
        <div style="white-space: nowrap;" width="100%" align="left">Name</div>
      </td>
      <td scope="col" width="<?php echo $width; ?>" class="listViewThS1" nowrap="">
        <div style="white-space: nowrap;" width="100%" align="left">Email Address</div>
      </td>
	  <?php if (Yii::app()->user->type == 'super admin'): ?>
		<td scope="col" width="<?php echo $width; ?>" class="listViewThS1" nowrap="">
			<div style="white-space: nowrap;" width="100%" align="left">Active</div>
		</td>
	  <?php endif; ?>
    </tr>
  </tbody>
    
    <tbody id="lvTred">

    <?php
      while ($row = $dr->read()) {
        $data = (object)$row;
    ?>
    
    <tr height="20" id="template_<?php echo $data->id; ?>">
      <td width="20"> 
        <div align="center" id="divtemplate_<?php echo $data->id; ?>" class="indIcon" style="visibility: hidden; ">
          <a class="link noajax" id="listViewEdit_template_<?php echo $data->id; ?>" href="/template/update/<?php echo $data->id; ?>">Edit</a>&nbsp;&nbsp;&nbsp;
          <a class="link noajax" id="listViewEdit_template_<?php echo $data->id; ?>" href="/template/delete/<?php echo $data->id; ?>" onclick="deleteTemplate();return false;">Delete</a>&nbsp;&nbsp;&nbsp;
        </div>
      </td>

      <td scope="row" align="left" valign="top" class="<?php echo ($i % 2) ? 'oddListRowS1' : 'evenListRowS1'; ?>" bgcolor="#ffffff">
        <a style="display:block;" id="detailview_template_<?php echo $data->id; ?>" pagetitle="View template" href="/template/view/<?php echo $data->id; ?>" class="listViewTdLinkS1"><?php echo CHtml::encode($data->name); ?></a>
      </td>
      <td scope="row" align="left" valign="top" class="<?php echo ($i % 2) ? 'oddListRowS1' : 'evenListRowS1'; ?>" bgcolor="#ffffff">
        <?php echo CHtml::encode($data->email_address); ?>
      </td>
	  <?php if (Yii::app()->user->type == 'super admin'): ?>
		<td scope="row" align="left" valign="top" class="<?php echo ($i % 2) ? 'oddListRowS1' : 'evenListRowS1'; ?>" bgcolor="#ffffff">
			<?php echo CHtml::encode($data->active ? 'Yes' : 'No'); ?>
		</td>
	  <?php endif; ?>
  </tr>
<?php } ?>
</tbody></table>


</div><!--listviewBorder-->
<br/>

  </div><!--idForCV-->
</div><!--ShowCustomViewDetails-->

<script type="text/javascript">
  bindEventListView();
  function deleteTemplate(){
    if(confirm('Are you sure delete this template')){
      ajaxRequest(this);
//      return true;
    }
//    else{
//      return false;
//    }
  }
</script>
$(function() {
    $('#definefield_dialog').length ? $('#definefield_dialog') : $( "<div id='definefield_dialog'/>" ).appendTo( $('body') );
    
		// there's the toolbar and the workpanel
		var $toolbar = $( "#toolbar" ),
			$workpanel = $( "#workpanel" ),
			$trash = $( "#trash" ),
			$recycle = $( "#recycle" ),
			$studentbar = $( "#studentbar" ),
			$room_panel = $( "#room_panel" );

		// let the toolbar items be draggable
		$( "li", $toolbar ).draggable({
			cancel: "a.ui-icon", // clicking an icon won't initiate dragging
			revert: "invalid", // when not dropped, the item will revert back to its initial position
			containment: $( "#demo-frame" ).length ? "#demo-frame" : "document", // stick to demo-frame if present
			helper: "clone",
			cursor: "move"
		});
    
		// let the workpanel be droppable, accepting the toolbar items
		$workpanel.droppable({
			accept: "#toolbar > li, #recycle_fields > li",
			activeClass: "ui-state-highlight",
			drop: function( event, ui ) {
				defineField( ui.draggable );
        
        //if drag from recycle, see: function recycleField( $item ) {
       if(ui.draggable.hasClass('in_recycle')){
         ui.draggable.fadeOut(function(){
           $(this).remove();
         });
       }

			}
		});
    
    $( "li", $recycle ).draggable({
			cancel: "a.ui-icon", // clicking an icon won't initiate dragging
			revert: "invalid", // when not dropped, the item will revert back to its initial position
			containment: $( "#demo-frame" ).length ? "#demo-frame" : "document", // stick to demo-frame if present
			helper: "clone",
			cursor: "move"
		});

		// let the toolbar be droppable as well, accepting items from the workpanel
		$trash.droppable({
			accept: "#workpanel li",
			activeClass: "ui-state-highlight",
			drop: function( event, ui ) {
				recycleField( ui.draggable );
			}
		});
    
    $( "#created_fields" ).sortable({
      revert: true
    });
    
    
		// drop component to workflow, here popup dialog to define field
		function defineField( $item ) {
      var $list = $( "ul", $workpanel ).length ?
					$( "ul", $workpanel ) :
					$( "<ul id='created_fields' class=''/>" ).appendTo( $workpanel );
        
      var opIcons = ''; var itemClone;
      if($("a", $item).length == 0){//drag from toobar to workflow
        opIcons = '<span class="right" style="float:right;">\
          <a href="javascript:void(0)" onclick="editField(this);"><img src="/themes/Sugar/images/edit_inline.gif" width="12" height="12"></a>\
          <a class="workflow_trash" href="javascript:void(0)" onclick="$(this).parent().parent().remove();"><img src="/themes/Sugar/images/delete_inline.gif" width="12" height="12"></a>\
          </span>';
        itemClone = $item.clone();
        itemClone.append(opIcons).appendTo( $list );
        popupDialog(itemClone);
      } else {//drag from [recycle fields] to workflow, it's icons is hide.
        opIcons = '<span class="right" style="float:right;">\
          <a href="javascript:void(0)" onclick="editField(this);"><img src="/themes/Sugar/images/edit_inline.gif" width="12" height="12"></a>\
          <a class="workflow_trash" href="javascript:void(0)" onclick="deleteField(this);"><img src="/themes/Sugar/images/delete_inline.gif" width="12" height="12"></a>\
          </span>';
        itemClone = $item.clone();
        itemClone.append(opIcons).appendTo( $list );
        //popupDialog(itemClone);
      }
		}
    
    
    

		// resolve the icons behavior with event delegation
		$( "ul.toolbar > li" ).click(function( event ) {
			var $item = $( this ),
				$target = $( event.target );

			if ( $target.is( "a.ui-icon-workpanel" ) ) {
				deleteImage( $item );
			} else if ( $target.is( "a.ui-icon-zoomin" ) ) {
				viewLargerImage( $target );
			} else if ( $target.is( "a.ui-icon-refresh" ) ) {
				recycleImage( $item );
			}

			return false;
		});
    
    
    // close tab icon: removing the tab on click
		// note: closable tabs gonna be an option in the future - see http://dev.jqueryui.com/ticket/3924
		$( "#tabs span.ui-icon-close" ).live( "click", function() {
			var index = $( "li", $tabs ).index( $( this ).parent() );
			$tabs.tabs( "remove", index );
		});
    
    
    
    /********** room assignment *********************/
    
    // let the studentbar items be draggable
		$( "li", $studentbar ).draggable({
			cancel: "a.ui-icon", // clicking an icon won't initiate dragging
			revert: "invalid", // when not dropped, the item will revert back to its initial position
			containment: $( "#demo-frame" ).length ? "#demo-frame" : "document", // stick to demo-frame if present
			helper: "clone",
			cursor: "move"
		});
    
//    $(".room_spot:not(.room_spot_has_stu)", $room_panel).droppable({
    $(".room_spot", $room_panel).droppable({
      accept : '#studentbar > li',
      activeClass: "ui-state-highlight",
      hoverClass: "ui-state-hover",
      drop: function( event, ui ) {
        $item = ui.draggable;
        $(this).addClass('room_spot_has_stu').html($item.html());
        $(ui.draggable).fadeOut();
        $(this).droppable({disabled : true});
      }
    });
    
   
   $(".room_spot", $room_panel).draggable({
			cancel: "a.ui-icon", // clicking an icon won't initiate dragging
			revert: "invalid", // when not dropped, the item will revert back to its initial position
			containment: $( "#demo-frame" ).length ? "#demo-frame" : "document", // stick to demo-frame if present
			helper: "clone",
			cursor: "move"
		});
   $(".room_spot_has_stu", $room_panel).droppable({disabled : true});
    
    $("#studentbar").droppable({
      accept : 'li.room_spot_has_stu',
      activeClass: "ui-state-highlight",
      hoverClass: "ui-state-hover",
      drop: function( event, ui ) {
        $item = ui.draggable;
        $('<li class="ui-widget-content"></li>').html(ui.draggable.html()).appendTo($('#studentbar')).draggable({
          cancel: "a.ui-icon", // clicking an icon won't initiate dragging
          revert: "invalid", // when not dropped, the item will revert back to its initial position
          containment: $( "#demo-frame" ).length ? "#demo-frame" : "document", // stick to demo-frame if present
          helper: "clone",
          cursor: "move"
        });
        
        $item.removeClass('room_spot_has_stu');
        $item.html($item.attr('spot_text'));
        $item.droppable({disabled : false});
      }
    });
    
    
    
    
});//end of $(function() {
  
  
function saveRoomAssignment(commit){
//  if(commit){
//    if(!confirm('Are you sure commit changes?')){
//      return false;
//    }
//  }
  
  var room_id, spot_id, student_id;
  var datas = [];
  var rooms = [];//use to collect current workflow room,because there maybe rooms are filtered, we only process these rooms
  
  $("#room_spots li.room_row").each(
    function(){
      room_id = $(this).attr('room_id');
      rooms.push(room_id);
      
      //spot
      $("li.room_spot_has_stu", $(this)).each(
        function(){
          spot_id = $(this).attr('spot_id');
          student_id = $('span', $(this)).attr('student_id');
          
          if(typeof student_id != 'undefined'){
            datas.push(room_id + '_' + spot_id + '_' + student_id);
          }
        }
      );
      
      
    }
  );
    
  $.ajax({
    async:true,
    cache:true,
    url: '/customers/roomassignment',
    type: 'POST',
    data: {commit : commit, rooms : rooms, datas : datas},
    dataType: 'html',
    timeout:15000,
    beforeSend:function(){
      showTip('Loading...');
    },
    success: function(data){
      showTip(data, 1);
    }
  });
  
  return false;
}


function popupDialog($item){
  $item.fadeIn(function() {
    $(this).addClass('currentComp');
    var comType = $(this).find('.left').attr('comtype');
    var field_id = $(this).attr('field_id');
    if(!field_id) { field_id = '0'; }

    $.ajax({
      async:true,
      cache:true,
      url: '/customers/editfield/type/'+ comType +'/id/' + field_id,
      type: 'GET',
      data: '',
      dataType: 'html',
      timeout:15000,
      beforeSend:function(){
        showTip('Loading...');
      },
      success: function(data){
        $("#definefield_dialog").html(data);
        $('#definefield_dialog').dialog({
          title: 'Add ' + comType,
          width: 600,
          modal: true,
          close: function(event, ui) { 
            $(".currentComp").removeClass("currentComp");
          }
        });
        hideTip();
      }
    });
  });
}


function editField(tsrc){
  var $item = $(tsrc).parent().parent();
  popupDialog($item);
}

function deleteField(tsrc){
  var $item = $(tsrc).parent().parent();
  recycleField($item);
}

// click trash of field, move field to Recycle area
function recycleField( $item ) {
  var cloneItem = $item.clone();
  cloneItem.find('.right').hide();//hide icon
  cloneItem.addClass("in_recycle").appendTo($("#recycle_fields")).draggable({
			cancel: "a.ui-icon", // clicking an icon won't initiate dragging
			revert: "invalid", // when not dropped, the item will revert back to its initial position
			containment: $( "#demo-frame" ).length ? "#demo-frame" : "document", // stick to demo-frame if present
			helper: "clone",
			cursor: "move"
		});
    
  $item.remove();
}

function addFieldInputFieldName(){
  var field_name_id = $('#field_name_id').val();
  var newFieldNameVal = field_name_id.toLowerCase().replace(/[^a-z0-9_]/g, '');
  
  $('#field_name_id').val(newFieldNameVal);
  
  newFieldNameVal = newFieldNameVal.replace(/_/,' ');
  
  var strLen = newFieldNameVal.length;
  var tmpChar = newFieldNameVal.substring(0,1).toUpperCase(); 
  var postString = newFieldNameVal.substring(1,strLen); 
  var tmpStr = tmpChar + postString; 
  $('#label_value_id').val(tmpStr);
}
  
function changeDropDown(sel){
  $.ajax({
    async:true,
    cache:true,
    url: '/customers/getdropdownfirstval/listname/'+$(sel).val(),
    type: 'GET',
    data: '',
    dataType: 'html',
    timeout:15000,
    beforeSend:function(){
      showTip('Loading...');
    },
    success: function(data){
      $("#default").replaceWith(data);
      hideTip();
    }
  });
}

function saveForm(){
  if($("#name").val() == ""){
    alert('Please fill in form name.');
    $("#name").focus();
    return false;
  }
  return true;
}

function saveFormFields(){
  var fields = [];
  
  $("#created_fields li").each(
    function(){
      if($(this).attr("field_id") != ""){
        fields.push($(this).attr("field_id"));
      }
    }
  );
    
  if(fields.length == 0){
    alert('No valid fields.');
    return false;
  }
  
  var postData = {fields : fields};
  postData.assigned_users = $("#assigned_users").val();
  postData.assigned_depart = $("#assigned_depart").val();
  postData.visible_users = $("#visible_users").val();
  postData.visible_depart = $("#visible_depart").val();
  postData.due_date = $("#due_date").val();
  postData.recurring = $("#recurring").val();
    
  $.ajax({
    async:true,
    cache:true,
    url: '/customers/saveform',
    type: 'POST',
    data: postData,
    dataType: 'html',
    timeout:15000,
    beforeSend:function(){
      showTip('Loading...');
    },
    success: function(data){
      showTip('Saved.', 1);
    }
  });
  
  return false;
  
}

function showStudentDetail(student_id){
  $.ajax({
    async:true,
    cache:true,
    url: '/student/popupinfo',
    type: 'GET',
    data: {student_id : student_id},
    dataType: 'json',
    timeout:15000,
    beforeSend:function(){
      showTip('Loading...');
    },
    success: function(data){
      var $student_profile_dialog = $('#student_profile_dialog').length ?
					$('#student_profile_dialog') :
					$( "<div id='student_profile_dialog' class=''/>" ).appendTo( $('body') );
        
      $student_profile_dialog.html(data.info);

      $student_profile_dialog.dialog({
        title: 'Student: ' + data.title,
        width: 700,
        modal: true,
        close: function(event, ui) { 

        }
      });
      hideTip();
    }
  });
  
  return false;
}

function showRoomDetail(room_id){
  $.ajax({
    async:true,
    cache:true,
    url: '/room/popupinfo',
    type: 'GET',
    data: {room_id : room_id},
    dataType: 'json',
    timeout:15000,
    beforeSend:function(){
      showTip('Loading...');
    },
    success: function(data){
      var $student_profile_dialog = $('#student_profile_dialog').length ?
					$('#student_profile_dialog') :
					$( "<div id='student_profile_dialog' class=''/>" ).appendTo( $('body') );
        
      $student_profile_dialog.html(data.info);

      $student_profile_dialog.dialog({
        title: 'Room',
        width: 500,
        modal: true,
        close: function(event, ui) { 

        }
      });
      hideTip();
    }
  });
  
  return false;
}


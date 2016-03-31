function rewardSYS(){
    var self = this;
    this.tables = $("#household_list");
    this.dialog = $('#dialog');
    this.anchors = $("a");
    this.editElement = null;
    this.editElementParent = null;
    this.ajax = function(url,type,data,dataType,beforeSendCallback,successCallback){
        //one ajax call
        $.ajax({
            async:true,
            cache:true,
            url: url,
            type: type,
            data: data,
            dataType: dataType,
            timeout:15000,
            beforeSend:function(){
                if(typeof beforeSendCallback == "function"){
                    beforeSendCallback.call()
                }
            },
            success: function(data){
                var thisObj = this;
                if(typeof successCallback == "function"){
                    successCallback.call(thisObj,data);
                }
            }
        });//$ ajax method

    }//end of ajax method

    this.bindDialog = function(link){
        dialogwidth = parseInt($(link).attr("dialogwidth"));
        self.ajax ($(link).attr("href"),'GET', {}, 'json',
            function(){
                self.dialog.dialog({
                    width: dialogwidth,
                    modal:true
                });
                self.dialog.html('Loading......');
                self.dialog.dialog('open');
            },
            function(data){
                $('#ui-dialog-title-dialog').html(data.title);
                self.dialog.html(data.content);
            }
        );
        return false;
    }

    this.refreshDialogContent = function(content) {
        self.dialog.html(content);
    }
    this.closeDialog = function() {
        self.dialog.html("");
        self.dialog.dialog('close');
    }

    this.bindDatepicker = function(input){
        $(input).datepicker({
            changeMonth: true,
            changeYear: true,
            yearRange: '1901:'+new Date().getFullYear(),
            onClose: function(dateText, inst) {
            }
        });
        
        $(input).datepicker("show");
    }
    
    this.household_load_household = function(type, household_id, page){
        url = '/household/ajax/load/child/'+type+'/'+household_id+'/'+page,
        self.ajax(url,"GET",{},"json",
            function(){
                $('#'+type+'_table').find('tbody').html('<img src="/sites/all/themes/leifs/images/loading.gif"/>');
            },function(data){
            $('#'+type+'_table').find('tbody').html(data.content);
            $('#household_pager').html(data.pagerLink);
        });
    }

    this.household_load_child = function(type, household_id, page){
        url = '/household/ajax/load/child/'+type+'/'+household_id+'/'+page,
        self.ajax(url,"GET",{},"html","",function(data){
            $('#'+type+'_table').find('tbody').html(data);

            if(type == 'vehicle'){
                self.dragDropService();
            }
        });
    }

    this.household_individual_event = function(link, household_id, individual_id){//after load invididual data, attach float event.
        self.ajax ('/household/individual/event/'+household_id+'/'+individual_id,'GET', {}, 'json',
            function(){
                self.dialog.dialog({
                    width: 445,
                    modal:true
                });
                self.dialog.html('Loading......');
                self.dialog.dialog('open');
            },
            function(data){
                $('#ui-dialog-title-dialog').html(data.title);
                self.dialog.html(data.content);
            }
        );
    }

    this.household_load_wi = function(household_id, vehicle_id){//load a vehicle's all WI
        url = '/household/ajax/load/work_item/'+household_id+'/'+vehicle_id,
        self.ajax(url,"GET",{},"html","",function(data){
            $('#wi_'+vehicle_id+'_table').html(data);
            $('#wi_'+vehicle_id+'_table li span.editable').each(
                function(i){
                    if($(this).html() == ""){
                        $(this).html("&nbsp;&nbsp;");
                    }
                }
            );

            self.dragDropService();
        });
    }

    this.household_load_service = function(work_item_id){
        url = '/household/ajax/load/service/'+work_item_id,
        self.ajax(url,"GET",{},"html","",function(data){
            $('#service_'+work_item_id+'_list').html(data);

            self.dragDropService();
        });
    }

    this.household_remove_child = function (type, household_id, child_id, clicklink) {
      
        if(!confirm("Are you sure remove this "+type+" ?")){
            return false;
        }

        self.ajax('/household/ajax/remove/child/'+type+'/'+household_id+'/'+child_id, 'GET', {}, 'html', '',
            function(data){
                if(data == 'Removed'){
                    if(type == 'individual'){//2 rows
                        $(clicklink).parent().parent().next().remove();
                    }

                    if(type == 'service'){
                        self.refreshWI(household_id);
                    }

                    if(type == 'household' && document.location.href.lastIndexOf("household/show") >= 0){
                        $("#household_list").slideUp(1500, function(){
                          $(this).html('<div style="color:red;">The household is deleted.</div>').show();
                        });
                        return;
                    }
                    
                    $(clicklink).parent().parent().remove();
                }else{
                    alert(data);
                }
            }
        );
        return false;
    }

    this.household_move_child = function (type, household_id, child_id, clicklink) {
        if(type != 'service'){
            if(!confirm("Are you sure move this "+type+" to this household ?")){
                return false;
            }
        }
        self.ajax('/household/ajax/move/child/'+type+'/'+household_id+'/'+child_id, 'GET', {}, 'html', '',
            function(data){
                alert(data);
                if(data == 'Moved' && type != 'service'){
                    $(clicklink).parent().parent().parent().remove();
                    rewardsys.household_load_child.call(this, type, household_id);
                    return;
                }
            }
        );
        return false;
    }

    //collapseButton toggle
    this.collapseButtonOnClick = function(imglink) {
        $(imglink).blur();

        $(imglink).parent().parent().next().toggle();
        if($(imglink).find('img').attr("src").indexOf('open') > -1){
            $(imglink).find('img').attr("src","/sites/all/themes/leifs/images/close.gif");
        }else{
            $(imglink).find('img').attr("src","/sites/all/themes/leifs/images/open.gif");
        }

        return false;
    }

    this.tableInstanceFilter = function (input, filterFields) {
        var colIndexTR, inputVal, parentTable, colIndex;
        parentTable = $(input).parent().parent().parent().parent();
        colIndexTR = parentTable.find("tbody tr");

        $.each(colIndexTR, function(i){
            allMatches = true;
            for(var k=0;k<filterFields.length; k++){
                colIndex = filterFields[k];

                //input value
                inputVal = parentTable.find("thead th").eq(colIndex).find("input,select").val().toLowerCase();

                //same column content
                var tdContent = $(this).find("td").eq(colIndex).html().toLowerCase();
                if(inputVal != "" && tdContent.indexOf(inputVal) == -1){
                    allMatches = false;
                }
            }

            if(allMatches){
                $(this).show();
            }else{
                $(this).hide();
            }
        });
    }

    this.thead_click_collapse = function (collapse_icon) {
        $(collapse_icon).parent().parent().parent().next().toggle();
        if($(collapse_icon).find('img').attr("src").indexOf('down') > -1){
            $(collapse_icon).find('img').attr("src","/sites/all/themes/leifs/images/right_arrow.gif");
        }else{
            $(collapse_icon).find('img').attr("src","/sites/all/themes/leifs/images/down_arrow.gif");
        }
    }

    this.thead_click_collapse_2 = function (collapse_icon) {
        $(collapse_icon).parent().parent().next().toggle();
        if($(collapse_icon).find('img').attr("src").indexOf('down') > -1){
            $(collapse_icon).find('img').attr("src","/sites/all/themes/leifs/images/right_arrow.gif");
        }else{
            $(collapse_icon).find('img').attr("src","/sites/all/themes/leifs/images/down_arrow.gif");
        }
    }

    this.addSelectRadioClick = function (radio) {
        $(radio).parent().parent().parent().find('.addSelectPopupDiv').hide();
        $(radio).parent().parent().find('.addSelectPopupDiv').show();
    }

    this.addSelectRadioClickCreateHousehold = function () {
        if(!confirm("Are you sure you want to leave the Create Household screen and go to the Household selected?")){
            return false;
        }

        return true;
    }
    
    this.textEditable = function (editObject, householdId, objKeyVal, field, fieldWrapper) {
        var editElementNewVal;
        if(self.editElement != null){
            editElementNewVal = self.editElementParent.find("input,select").val();
            if (editElementNewVal == self.editElement.html()) {
                self.editElementParent.html(self.editElement);
            }else{
                //
            }
        }

        var fieldVal = $(fieldWrapper).html().replace(/^&nbsp;/g, '').replace(/&nbsp;$/g, '');
        //if edit no email, it's a image
        if(fieldVal.lastIndexOf('noemail.gif') >= 0){
          fieldVal = '';
        }

        $(fieldWrapper).html(fieldVal);

        self.ajax('/household/text/editable/'+editObject+'/'+householdId+'/'+objKeyVal+'/'+field, 'POST', {fieldVal:fieldVal}, 'html',
            function(){
                self.editElement = $(fieldWrapper).clone();
                self.editElementParent = $(fieldWrapper).parent();
                
                $(fieldWrapper).html('<img src="/sites/all/themes/leifs/images/loading.gif" alt="Loading..." title="Loading..." />');
            },
            function(data){
                $(fieldWrapper).replaceWith(data);
                self.editElementParent.find("input,select").focus();
            }
        );
    }

    this.textEditableSubmit = function (editObject, householdId, objKeyVal, field, fieldVal, newFormElement) {
        var fieldNewVal = $(newFormElement).val();
        
        //WI Delivered
        if (field == 'Status' && fieldNewVal == 'Delivered') {
            var msg = '';
            if($(newFormElement).attr('ptscuststatus') == 'Active'){
                msg = "Once set to 'Delivered' this SO will be locked and cannot be edited. \nDo you want to continue?";
            }else{
                msg = "This Member is Inactive. Once set to 'Delivered' points will be set to zero and this SO will be locked and cannot be edited. \nDo you want to continue?";
            }
            if(!confirm(msg)){
                return;
            }
        }
        
        var showNewVal = fieldNewVal;
        if(newFormElement.nodeName.toLowerCase() == 'select'){
            showNewVal = newFormElement.options[newFormElement.selectedIndex].text;
        }
        if(field == 'quoted_price'){
            showNewVal = '$'+showNewVal;
        }
        if (showNewVal == "") {
            showNewVal = "&nbsp;";
        }

        self.ajax('/household/text/editable_submit/'+editObject+'/'+householdId+'/'+objKeyVal+'/'+field, 'POST', {fieldNewVal:fieldNewVal}, 'html', '',
            function(data){
                if(data == 'Saved'){
                    $(newFormElement).parent().html('<span class="editable" onclick="rewardsys.textEditable(\''+editObject+'\', '+householdId+', '+objKeyVal+', \''+field+'\', this)">'+showNewVal+'</span>');
                    self.editElement = null;

                    if(editObject == 'work_item' &&  field == 'Status' && fieldNewVal == 'Delivered'){
                        self.household_load_wi(rewardsys.household, householdId);
                        self.household_load_child('individual', rewardsys.household);

                        //refresh points transaction list      objKeyVal is WI ID
                        var points_customer = $('#vehicle_table #wi_'+objKeyVal+'_points_customer').attr("points_customer");
                        $("#pt_individual_id").val(parseInt(points_customer)).change();
                    }
                    if(editObject == 'work_item' && (field == 'points_used' || field == 'Status')){
                        self.refreshWI(objKeyVal);
                    }

                    if(editObject == 'individual'){
                        if(field == 'primary_email' || field == 'status'){
                          self.household_load_child('individual', rewardsys.household);
                        } else if(field == 'ofl'){
                          if (fieldVal == 'No' && fieldNewVal == 'Yes') {//change ofl from No to Yes
                            self.oflYesLoadVehicles(householdId, objKeyVal);
                          } else if (fieldVal == 'Yes' && fieldNewVal == 'No') {//change ofl from Yes to No
                            $("#ofl_vehicles").html('');
                          }
                        } else {
                          
                        }
                    }
                    if(editObject == 'address' && field == 'primary' && fieldNewVal == 'Yes'){
                        self.household_load_child('address', rewardsys.household);
                    }
                }else{
                    alert(data);
                    $(newFormElement).focus();
                }
            }
        );
    }

    this.oflYesLoadVehicles = function (householdId, individual_id) {
      self.ajax('/household/ajax/ofl_yes_load_vehicles/' + householdId + '/' + individual_id, 'GET', {}, 'html',
          function(){
          },
          function(data){
            $("#ofl_vehicles").html(data);
          }
      );
    }

    this.selectCategoryListOnChange = function (category) {
        if(category == ""){
            $("#dialog #service_list_id").html("");
            return;
        }

        self.ajax('/household/ajax/getinfo/service_category/'+category, 'GET', {}, 'html',
            function(){
                $("#dialog #service_list_id").html("");
            },
            function(data){
                $("#dialog #service_list_id").html(data);
                $("#dialog #service_list_id").change();
            }
        );
    }
    
    this.selectListOnChange = function (service_list_id) {
        if(service_list_id == ""){
            $("#s_category").html("");
            $("#s_service_name").html("");
            $("#s_base_price").html("");
            return;
        }

        self.ajax('/household/ajax/getinfo/service_list/'+service_list_id, 'GET', {}, 'json',
            function(){
                $("#s_category").html("");
                $("#s_service_name").html("");
                $("#s_base_price").html("");
            },
            function(data){
                $("#s_category").html(data.category);
                $("#s_service_name").html(data.service_name);
                $("#s_base_price").html(data.base_price);
            }
        );
    }

    this.pointsTransactionSelectInd = function (individual_id, household_id) {
        url = '/household/ajax/load/pointsTransaction/'+household_id+'/'+individual_id,
        self.ajax (url,'GET', {}, 'html',
            function(){},
            function(data){
                $('#household_list #pointstransaction_table').find('tbody').html(data);
            }
        );
    }

    this.draggable = function(){
        $("#vehicle_table .draggable").draggable({
            handle: '.dragicon', //handler icon
            revert: 'invalid', //revert if don't drag to a  droppable container
            axis: 'y', //vertical drag
            helper:'clone',
            start:function(event, ui){
                $(this).css('background-color', '#ccc');
                ui.helper.css({'background-color':'#00697E', 'color':'#fff'});
            },
            stop:function(event, ui){
                $(this).css('background-color', '');
            }
        });
    }

    this.dragDropService = function(){//drag and drop service
        self.draggable();

        $("#vehicle_table .droppable").droppable({
            //activeClass: 'ui-droppable-highlight',
            //hoverClass: 'ui-droppable-highlight',
            over: function(event, ui) {
                var from_wi = ui.draggable.attr('wi');
                if(typeof from_wi == 'undefined'){//ie: dialog was dragged
                    return;
                }
                $(this).addClass('ui-droppable-highlight');
            },
            out: function(event, ui) {
                var from_wi = ui.draggable.attr('wi');
                if(typeof from_wi == 'undefined'){//ie: dialog was dragged
                    return;
                }
                $(this).removeClass('ui-droppable-highlight');
            },
            drop: function(event, ui) {
                var from_wi = ui.draggable.attr('wi');
                if(typeof from_wi == 'undefined'){//ie: dialog was dragged
                    return;
                }
                var to_wi = $(this).attr('wi');
                if(from_wi == to_wi){//still in original droppable container
                    //
                }else{//drag and drop to a new droppable container
                    $(this).append($(ui.draggable).clone().attr("wi", to_wi).css('background-color', ''));

                    self.ajax('/household/ajax/move/child/service/'+to_wi+'/'+ui.draggable.attr('service_id'), 'GET', {}, 'html', '',
                        function(data){
                            self.refreshWI(from_wi);
                            self.refreshWI(to_wi);
                            //alert(data);
                        }
                    );

                    ui.helper.remove();
                    ui.draggable.hide();//remove() cause some warning.

                    self.draggable();
                }
                $(this).removeClass('ui-droppable-highlight');
            }
        });
    }

    this.validateAddWorkItemForm = function(form){
        if(form.Date.value == ""){
            $(form.Date).addClass('inputError').focus();
            alert('Date is required.');
            return false;
        }else{
            $(form.Date).removeClass('inputError');
        }

        if(form.Status.value == ""){
            $(form.Status).addClass('inputError').focus();
            alert('Status is required.');
            return false;
        }else{
            $(form.Status).removeClass('inputError');
        }

        if(form.location.value == ""){
            $(form.location).addClass('inputError').focus();
            alert('location is required.');
            return false;
        }else{
            $(form.location).removeClass('inputError');
        }

        if(form.uid.value == ""){
            $(form.uid).addClass('inputError').focus();
            alert('Salesperson is required.');
            return false;
        }else{
            $(form.uid).removeClass('inputError');
        }

        if(form.individual.value == ""){
            $(form.individual).addClass('inputError').focus();
            alert('PTs Cust is required.');
            return false;
        }else{
            $(form.individual).removeClass('inputError');
        }

        return true;
    }

    this.onlyNumbers = function(evt){
        var k=window.event?evt.keyCode:evt.which;
        if(((k<=57) && (k>=48) || k==8 || k==13)){//8 is BackSpace 13 is enter
            return true;
        }else{
            return false;
        }
    }

    this.onlyFloat = function(evt){
        var k=window.event?evt.keyCode:evt.which;

        if(  (k<=57 && k>=48) || k==8 || k==46 || k==13){//8 is BackSpace, 46 is . 13 is enter
            return true;
        }else{
            return false;
        }
    }

    this.validateYear = function(evt, year){
        if(year < 1900 || year > 2010){
            //alert("Your input year is invalid.");
            return false;
        }
        return true;
    }

    this.addIndvidualFormSubmit = function(form){
        var status = form.status.value;

        if(form.First.value == ""){
            alert("Please fill in First name.");
            form.First.focus();
            return false;
        }
        if(form.Last.value == ""){
            alert("Please fill in Last name.");
            form.Last.focus();
            return false;
        }
        if(form.Gender.value == ""){
            alert("Please select Gender.");
            form.Gender.focus();
            return false;
        }

        if(status == 'Inactive - No TC'){
//            var inputYear = form.DOB_Year.value;
//            if(inputYear != ""){
//                if(inputYear < 1900 || inputYear > 2010){
//                    alert("Your input year is invalid.");
//                    form.DOB_Year.focus();
//                    return false;
//                }
//            }
            if(form.primary_email.value != "" && !self.ismail(form.primary_email.value)){
                alert("Please fill in a valid email.");
                form.primary_email.focus();
                return false;
            }
        }

        return true;
    }

    this.ismail = function(mail){
      return(new RegExp(/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/).test(mail));
    }

    this.validatePhoneNumLength = function(evt, phoneNum){
        var k=window.event?evt.keyCode:evt.which;
        if(((k<=57) && (k>=48) || k==0 || k==8)){//only number and backspace is allowed.   8 is BackSpace
            if(phoneNum.length>=12 && k != 0 && k != 8){//can not be more than 10 + 2'-'
                return false;
            }else{
                return true;
            }
        }else{
            return false;
        }
    }

    this.adjustPhoneNum = function(evt, input){
        var k=window.event?evt.keyCode:evt.which;
        if(k==8){
            return;
        }
        
        var phoneNum = input.value;
        if (phoneNum.length == 3 || phoneNum.length == 7) {
            $(input).val(phoneNum+'-');
        }
    }

    this.addPhone = function(fm){
        fm.phone_num.value = fm.phone_num.value.replace(/[^\w\d-]/g, '');
        if(fm.phone_num.value.length != 12){
            alert('Please enter a valid phone number.');
            return false;
        }

        return true;
    }

    this.agree_terms_checked = function(checkbox){
        if($(checkbox).attr("checked")){
            $(checkbox).attr("disabled", true);
            checkbox.form.submit();
        }
    }

    this.resend_agree_terms_mail = function(link, individual_id){
        if(!confirm("Do you want to resend the Terms & Conditions?")){
            return false;
        }

        url = '/rewardsys/resend/agree_terms_and_conditions/'+individual_id,
        self.ajax (url,'GET', {}, 'html',
            function(){},
            function(data){
                if (data == 'success') {
                    $(link).remove();
                    alert('Email was resent.');
                }
            }
        );

        return false;
    }

    this.addIndividualAutoSuggest = function(form, household_id){
        url = '/household/add/individual/autosuggest/'+household_id,
        self.ajax (url,'POST',
            {
                First : form.First.value,
                Middle : form.Middle.value,
                Last : form.Last.value,
                //DOB_Month : form.DOB_Month.value,
                //DOB_Day : form.DOB_Day.value,
                //DOB_Year : form.DOB_Year.value,
                Gender : form.Gender.value,
                //points_balance : form.points_balance.value,
                rewards_num : form.rewards_num.value,
                primary_email : form.primary_email.value,
                status : form.status.value
            },
            'html',
            function(){},
            function(data){
                $("#add_select_individual").find("tbody").html(data);
            }
        );
    }

    this.addPhoneAutoSuggest = function(form, household_id){
        url = '/household/add/phone/autosuggest/'+household_id,
        self.ajax (url,'POST',
            {
                phone_num : form.phone_num.value,
                phone_type : form.phone_type.value,
                sms : form.sms.value,
                time : form.time.value,
                phone_note : form.phone_note.value
            },
            'html',
            function(){},
            function(data){
                $("#add_select_phone").find("tbody").html(data);
            }
        );
    }

    this.addAddressAutoSuggest = function(form, household_id){
        url = '/household/add/address/autosuggest/'+household_id,
        self.ajax (url,'POST',
            {
                street1 : form.street1.value,
                street2 : form.street2.value,
                city : form.city.value,
                state : form.state.value,
                zip : form.zip.value,
                address_type : form.address_type.value
            },
            'html',
            function(){},
            function(data){
                $("#add_select_address").find("tbody").html(data);
            }
        );
    }

    this.addVehicleAutoSuggest = function(form, household_id){
        url = '/household/add/vehicle/autosuggest/'+household_id,
        self.ajax (url,'POST',
            {
                Make : form.Make.value,
                Model : form.Model.value,
                year : form.year.value,
                color : form.color.value,
                vin : form.vin.value
            },
            'html',
            function(){},
            function(data){
                $("#add_select_vehicle").find("tbody").html(data);
            }
        );
    }

    this.flashHeaderSetting = function(selectlist){
        var fm = selectlist.form;
        
        url = '/admin/settings/flashheader/load/'+$(selectlist).val(),
        self.ajax (url,'GET', {}, 'json',
            function(){
                $(selectlist).nextAll().remove();
                $(selectlist).after('<span class="throbber">&nbsp;</span>');

                fm.type.disabled = true;
                $("input[name=visibility]").attr("disabled", true);
                fm.pages.disabled = true;
            },
            function(data){
                $(selectlist).nextAll().remove();
                
                $("input[name=visibility]").attr("disabled", false);
                $("input[name=visibility]").attr("checked", false);
                if (typeof data.visibility != 'undefined') {
                    $("input[name=visibility][value="+ data.visibility +"]").attr("checked", true);
                }

                if (typeof data.pages != 'undefined') {
                    fm.pages.value = data.pages;
                }else{
                    fm.pages.value = '';
                }
                
                fm.type.disabled = false;
                fm.visibility.disabled = false;
                fm.pages.disabled = false;
            }
        );
    }

    this.refreshWI = function(work_item_id){
        url = '/household/wi/'+work_item_id,
        
        self.ajax (url,'GET', {}, 'html',
            function(){
            },
            function(data){
                $("#vehicle_table #wi_"+work_item_id).html(data);

                $("#vehicle_table #wi_"+work_item_id+' li span.editable').each(
                    function(i){
                        if($(this).html() == ""){
                            $(this).html("&nbsp;&nbsp;");
                        }
                    }
                );
            }
        );

        return false;
    }

    this.addHousehold = function(form){
        var result = self.addIndvidualFormSubmit(form);
        if(!result){
            return false;
        }

        if(!confirm("Are you finished? You would like to add the "+form.Last.value+" household?")){
            return false;
        }

        return true;
    }

    this.validateAddVehicle = function(form){
        if(!confirm("Are you finished?")){
            return false;
        }

        return true;
    }

    this.genFinanMailingList = function(btn){
        if(!confirm("Are you sure you want to generate the final list?\nThis action will log a record for every individual.")){
            return;
        }
        btn.form.demo.value = 0;

        btn.form.submit();
    }
    
    this.addService = function(form){
        if(form.quoted_price.value == ""){
            form.quoted_price.focus();
            alert('Please fill in the Quoted Price.');
            return false;
        }
        if(form.category.value == ""){
            form.category.focus();
            alert('Please select a Service Category.');
            return false;
        }
        if(form.service_list_id.value == ""){
            form.service_list_id.focus();
            alert('Please select a Service Type.');
            return false;
        }

        return true;
    }

    this.cardResponse = function(card_number_input){
        if (card_number_input.value.length == 11 && card_number_input.value.substring(0, 4) == '0009') {
            self.cardLookUpCardNumberFormSubmit(card_number_input.form);
        }
    }

    this.cardLookUpCardNumberFormSubmit = function(form){
        var card_number = $("#card_number").val();
        self.ajax ('/rewardsys/card/response' , 'POST', {card_number : card_number}, 'html',
            function(){
                self.cardLookUpSubmitResetTRBackground();
            },
            function(data){
                $("#card_number").val('');
                if (data.lastIndexOf('No individual') >= 0) {
                    alert(data);
                } else {
                    //already searched this one before, this is a repeat search
                    if($("#search_response tbody tr[cardnum_or_rewardnum="+ card_number +"]").size() > 0){
                        $("#search_response tbody tr[cardnum_or_rewardnum="+ card_number +"]").remove();
                    }

                    $("#search_response tbody").prepend(data);
                    self.cardLookUpSubmitResetTRBackground();
                }
            }
        );
    }

    this.cardLookUpSubmit = function(form){
        var rewards_num = $("#rewards_num").val();
        self.ajax ('/rewardsys/card/response' , 'POST', {rewards_num : rewards_num}, 'html',
            function(){
                self.cardLookUpSubmitResetTRBackground();
            },
            function(data){
				$("#rewards_num").val('');
                if (data.lastIndexOf('No individual') >= 0) {
                    alert(data);
                } else {
                    //already searched this one before, this is a repeat search
                    if($("#search_response tbody tr[cardnum_or_rewardnum="+ rewards_num +"]").size() > 0){
                        $("#search_response tbody tr[cardnum_or_rewardnum="+ rewards_num +"]").remove();
                    }
                    
                    $("#search_response tbody").prepend(data);
                    self.cardLookUpSubmitResetTRBackground();
                }
            }
        );
    }

    this.cardLookUpSubmitFuel = function(form){
        var purchase = form.purchase.value;
        var individual_id = form.individual_id.value;
//        if (purchase == '') {
//            alert('Please fill in a valid fuel purchase amount.');
//            return;
//        }
        self.ajax ('/rewardsys/card/response/log_fuel', 'POST', {purchase : purchase, individual_id:individual_id}, 'html',
            function(){},
            function(data){
				$("#card_number").focus(); //this has to put here 
                if (data == 'success') {
                    $(form).parent().parent().remove();
                    self.cardLookUpSubmitResetTRBackground();
                } else {
                    alert(data);
					
                }
				//$("#rewards_num").focus(); <- Cant put here in order to have it work correctly
            }
			
        );
    }

    
    
    this.cardLookUpRemoveResult = function(okbtn){
        $(okbtn).parent().parent().remove();
        self.cardLookUpSubmitResetTRBackground();
    }

    this.cardLookUpSubmitResetTRBackground = function(){
        $("#search_response tbody tr").each(
            function(i){
                if (i%2) {
                    $(this).css('background', '#fff');
                } else {
                    $(this).css('background', '#ddd');
                }
            }
        );
    }

    this.chkCardTrackStage = function(chkbox, card_track_id, stage){
        $(chkbox).attr('checked', true);
        self.ajax ('/rewardsys/card_tracking/stage/comment/'+card_track_id+'/'+stage,'GET', {}, 'json',
            function(){
                self.dialog.dialog({
                    width: 445,
                    modal:true
                });
                self.dialog.html('Loading......');
                self.dialog.dialog('open');
            },
            function(data){
                $('#ui-dialog-title-dialog').html(data.title);
                self.dialog.html(data.content);
            }
        );
        return false;
    }

    this.collapseWrapperHeightLoad = function(collIcon){
      if($(collIcon).parent().parent().css('overflow') == 'hidden'){
        $(collIcon).parent().parent().css('overflow','visible').css('width', $(collIcon).parent().prev().width());
        $(collIcon).css('background-position', '0px 0px');
      } else {
        $(collIcon).parent().parent().css('overflow','hidden').css('width', '100%');
        $(collIcon).css('background-position', '-21px 0px');
      }
    }
	
    //move cursor after fuel amount submited
    this.mouseMove = function() {
      $("#rewards_num").focus();
    }
    
    this.checkAll = function(checkAllBox){
      if($(checkAllBox).attr('checked') == true){
        $(checkAllBox).parent().parent().parent().parent().find(".checkAllChildren").attr('checked', true);
      } else {
        $(checkAllBox).parent().parent().parent().parent().find(".checkAllChildren").attr('checked', false);
      }
    }

}

//initialize a new reward class
var rewardsys = new rewardSYS();
$(function() {
    $("#img_search").after('<div id="header_face_twitter_komen"><a href="http://www.facebook.com/leifsautocenters" target="_blank"><img src="/sites/all/themes/leifs/images/facebook_logo.png" alt="facebook" width="20" height="20"/></a>&nbsp;&nbsp;<a href="http://www.twitter.com/leifsauto" target="_blank"><img src="/sites/all/themes/leifs/images/twitter_logo.png" alt="twitter" width="20" height="20"/></a>&nbsp;&nbsp;<a href="http://www.komenoregon.org" target="_blank"><img src="/sites/all/themes/leifs/images/komen_logo.png" alt="komen" width="20" height="20"/></a></div>');
    $("body").append('<div id="dialog" title="" style="display: none"></div>');
    //recollect this tables wrapper div
    rewardsys.tables = $("#household_list");
    rewardsys.dialog = $('#dialog');

    if(document.location.href.match('user/register')){
      $("#edit-promo-code-wrapper label").append('(optional)');
      
      $("#edit-profile-phone").keypress(
        function(event){
            return rewardsys.validatePhoneNumLength(event, this.value);
        }
      ).keyup(
        function(event){
            return rewardsys.adjustPhoneNum(event, this);
        }
      );
    }

    
});
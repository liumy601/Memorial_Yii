if(document.all){
  var browser_ie=true;
}else{
  if(document.layers){
    var browser_nn4=true;
  }else{
    if(document.layers||(!document.all&&document.getElementById)){
      var browser_nn6=true;
    }
  }
}
if(window.navigator.userAgent.toUpperCase().indexOf("OPERA")>=0){
  var browser_opera=true;
}

function setPageUrl(requestUri) {
	var matches = location.href.match(/http:\/\/([^\/]+)/i);
	var host = matches[0];
	var url = host+requestUri;
	history.pushState({}, document.title, url);
}

$(document).ready(
  function(){
    $('.topdivbg1 .menu a.topNaveLink').mouseover(function(){
      //hide all
      $('.topdivbg1 .menu .submenu').hide();
      //show this
      var left = $(this).position().left;
      var top = $(this).position().top;
      $(this).next('.submenu').css({'display':'block', 'left':left+'px', 'top':top+37+'px'});
    })

    //datepicker
    $( ".datepicker" ).live('focus', function(){
      $(this).datepicker({
        changeMonth : true,
        changeYear : true
      });
    });

    //check all checkbox
    $(".checkAll").click(
      function(){
        if($(this).attr('checked') == true){
          $(this).parent().parent().next().find('input').attr('checked', true);
        } else {
          $(this).parent().parent().next().find('input').attr('checked', false);
        }
      }
    );
      
    //integer textfield
    $('input.integer').live('keyup', 
      function(){
        this.value=this.value.replace(/[^\d]/g,'');
      }
    ).live('onbeforepaste', 
      function(){
        clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''));
      }
    );
      
    
      
      
    //ajax history
    function load(num){
      if(num!=1){
        num=num.replace(/bck/,"");
        if(globalId==num){
          return;
        }
        if(urlCache[num]){
          var url=urlCache[num];
          ajaxNew(url);
          globalId=num;
          
          if(num.indexOf('tab_') == 0){
            tabClicked(document.getElementById(num));
          }
          return;
        }
      }
    }
    $.history.init(function(url){
      load(url==""?"1":url);
    });
    
    var hash = document.location.hash.replace(/#/, '');
    if(hash){
      $.history.load(hash);
    }
    
    //ajax
    $("a:not(.noajax, .ui-state-default)").live('click',
      function(){
        var href=$(this).attr('href');

        if(href == '#' || href.substr(0,1) == '#' || href.substr(0,-1) == '#' || href.substr(0,7) == '/files/' || href.indexOf('javascript:') >= 0){
          return true;
        }

        if($(this).attr('id')){
          putInHistory($(this).attr('id'), href);
        } else {
          putInHistory($(this).attr('href').replace(/[^\d\w]/g, ''), href);
        }
        if($(this).attr('pagetitle')){
          document.title = $(this).attr('pagetitle');
        } else {
          if($(this).html().indexOf('<img') >= 0 || $(this).html().indexOf('<IMG') >= 0){
            document.title = $(this).find('img').attr('alt');
          } else {
            document.title = $(this).html();
          }
        }
		
		setPageUrl($(this).attr('href'));

        $.ajax({
          async:true,
          cache:true,
          url: href,
          type: 'GET',
          data: {ajaxRequest:1},
          dataType: 'html',
          timeout:15000,
          beforeSend:function(){
            showTip('Loading...');
          },
          success: function(data){
            hideTip();//this line should be above to .html(data), because data may contains showTip().
            $('#show').html(data);
            $("#searchword").focus().blur();
            appAppAddIcon();
          },
          error: function(data){
            showTip('Request failed.');
          }
        });

        return false;
      }
    );

    //if don't want form use ajax submit, give the form a class: noajax
    $('form:not(.noajax)').live('submit',
      function(){
        putInHistory($(this).attr('id'), $(this).attr('url'));
        var pagetitle = $(this).attr('pagetitle');
        if(typeof pagetitle == 'undefined'){
          document.title = 'NorthWest Management HOA';
        } else {
          document.title = $(this).attr('pagetitle');
        }
        
        var data = $(this).serializeArray();
        data.push({name:'ajaxRequest', value:1});
        
        if(!check_form('EditView')){
          return false;
        }
        
        $.ajax({
          async:true,
          cache:true,
          url: $(this).attr('action'),
          type: $(this).attr('method'),
          data: data,
          dataType: 'html',
          timeout:15000,
          beforeSend:function(){
            showTip('Loading...');
          },
          success: function(data){
            hideTip();
            $('#show').html(data);
            $("#searchword").focus().blur();
            appAppAddIcon();
            return false;
          },
          error: function(data){
            showTip('Request failed.');
          }
        });

        return false;
      }
    );
      
    //lookup filter form
    $('form.lookup_filter_form').live('submit',
      function(){
        var data = $(this).serializeArray();
        data.push({name:'ajaxRequest', value:1});

        $.ajax({
          async:true,
          cache:true,
          url: $(this).attr('action'),
          type: $(this).attr('method'),
          data: data,
          dataType: 'html',
          timeout:15000,
          beforeSend:function(){
            showTip('Loading...');
          },
          success: function(data){
            hideTip();
            $('#lookup-dialog').html(data);
            appAppAddIcon();
            return false;
          },
          error: function(data){
            showTip('Request failed.');
          }
        });

        return false;
      }
    );

    $('.buttonurl').live('click',
      function(){
        if($(this).val() == 'Homepage'){
          document.location.href = $(this).attr('url');
          return false;
        }
        
        putInHistory($(this).attr('id'), $(this).attr('url'));
        document.title = $(this).attr('pagetitle');
		
		setPageUrl($(this).attr('url'));

        $.ajax({
          async:true,
          cache:true,
          url: $(this).attr('url'),
          type: 'GET',
          data: {ajaxRequest:1},
          dataType: 'html',
          timeout:15000,
          beforeSend:function(){
            showTip('Loading...');
          },
          success: function(data){
            $('#show').html(data);
            $("#searchword").focus().blur();
            hideTip();
            appAppAddIcon();
          },
          error: function(data){
            showTip('Request failed.');
          }
        });

        return false;
      }
    );

  }
);
  
function ajaxRequest(obj){
  $.ajax({
    async:true,
    cache:true,
    url: $(obj).attr('url'),
    type: 'GET',
    data: {ajaxRequest:1},
    dataType: 'html',
    timeout:15000,
    beforeSend:function(){
    },
    success: function(data){
      $("#searchword").focus().blur();
      $('#show').html(data);
      appAppAddIcon();
    },
    error: function(data){
      showTip('Request failed.');
    }
  });
}
  
function ajaxNew(url){
  $.ajax({
    async:true,
    cache:true,
    url: url,
    type: 'GET',
    data: {ajaxRequest:1},
    dataType: 'html',
    timeout:15000,
    beforeSend:function(){
    },
    success: function(data){
      $("#searchword").focus().blur();
      $('#show').html(data);
      appAppAddIcon();
    },
    error: function(data){
      showTip('Request failed.');
    }
  });
}

function tabClicked(tab){
  $(tab).parent().find('a').removeClass('sel');
  $(tab).addClass('sel');
  Appcues.start();
}
  
function showInLine(_f71){
  var id=document.getElementById(_f71);
  id.style.display="inline";
}
function show(_f73,_f74){
  var id=document.getElementById(_f73);
  if(_f74==undefined){
    id.style.display="block";
  }else{
    id.style.display=_f74;
  }
}
function showHide(_f76,_f77){
  show(_f76);
  hide(_f77);
}
function hide(_f78,hide){
  var id=document.getElementById(_f78);
  if(id!=null){
    id.style.display="none";
  }
  if(_f78!="crmspanid"&&(hide==undefined||hide=="")){
    if(document.getElementById("FreezeLayer")){
      document.body.removeChild(document.getElementById("FreezeLayer"));
    }
  }
}
function trimRight(str){
  var _1e4=str.replace(/[\s| ]*$/,"");
  return (_1e4);
}
function trimBoth(str){
  var _1e6="";
  if(typeof str=="string"){
    _1e6=trimRight(trimLeft(str));
  }else{
    _1e6=str;
  }
  return (_1e6);
}
function trimLeft(str){
  var _1e8=str.replace(/[\s| ]*/,"");
  return (_1e8);
}

function getObj(n,d){
  var p,i,x;
  if(!d){
    d=document;
  }
  if((p=n.indexOf("?"))>0&&parent.frames.length){
    if(n.substring(p+1)!=""&&n.substring(p+1)!=null&&parent.frames[n.substring(p+1)]){
      d=parent.frames[n.substring(p+1)].document;
      n=n.substring(0,p);
    }
  }
  if(!(x=d[n])&&d.all){
    x=d.all[n];
  }
  for(i=0;!x&&i<d.forms.length;i++){
    x=d.forms[i][n];
  }
  for(i=0;!x&&d.layers&&i<d.layers.length;i++){
    x=getObj(n,d.layers[i].document);
  }
  if(!x&&d.getElementById){
    x=d.getElementById(n);
  }
  return x;
}


function set_cookie(_d3,_d4,_d5,_d6,_d7,_d8,_d9,_da){
  var _db=_d3+"="+escape(_d4);
  if(_d5){
    var _dc=new Date(_d5,_d6,_d7);
    _db+="; expires="+_dc.toGMTString();
  }
  if(_d8){
    _db+="; path="+escape(_d8);
  }
  if(_d9){
    _db+="; domain="+escape(_d9);
  }
  if(_da){
    _db+="; secure";
  }
  document.cookie=_db;
}
function get_cookie(_dd){
  var _de=document.cookie.match(_dd+"=(.*?)(;|$)");
  if(_de){
    return (unescape(_de[1]));
  }else{
    return null;
  }
}
function delete_cookie(_df){
  var _e0=new Date();
  _e0.setTime(_e0.getTime()-1);
  document.cookie=_df+="=; expires="+_e0.toGMTString();
}

function showTip(tip, timeout){ 
  $("#topmsg").html(tip);
  $( '#topmsg' ).show().corner('bottom 6px'); 
  var windowWidth = $(window).width(); 
  var left = (( windowWidth / 2 ) - ($( '#topmsg' ).width() / 2 ) - 20) + 'px';
  $( '#topmsg' ).css({'left' : left});
  $( '#topmsg' ).css({'top' : $(document).scrollTop() + 'px'});
  
  if(timeout){
//  setTimeout( function(){$( '#topmsg' ).slideUp();}, 3000 ); 
    setTimeout( function(){$( '#topmsg' ).hide();}, 3000 ); 
  }
}

function hideTip(){ 
  $('#topmsg').hide();
}

function bindEventListView(){
  if(document.getElementById("listViewTable")!=null){
    var table=document.getElementById("listViewTable");
    var tr=table.getElementsByTagName("tr");
    var trlen=tr.length;
    for(var i=1;i<trlen;i++){
      tr[i].onmouseover=function(){
        dropRofn(this,this.id);
      };
      
      tr[i].onmouseout=function(){
        dropRoutfn(this,this.id);
      };
    
    }
  }
}
function dropRofn(rEle,nId){
  rEle.className="tdhover";
  if(document.getElementById("div"+nId)){
    document.getElementById("div"+nId).style.visibility="visible";
  }
}
function dropRoutfn(rEle,nId){
  rEle.className="tdout";
  if(document.getElementById("div"+nId)){
    document.getElementById("div"+nId).style.visibility="hidden";
    if($("#dropnoteDiv")){
      hide($("#dropnoteDiv"));
      $("#dropnoteDiv").onmouseover=function(){
        rEle.className="tdhover";
        document.getElementById("div"+nId).style.visibility="visible";
        show($("#dropnoteDiv"));
      };
      
      $("#dropnoteDiv").onmouseout=function(){
        rEle.className="tdout";
        document.getElementById("div"+nId).style.visibility="hidden";
        hide($("#dropnoteDiv"));
      };
    }
  }
}

function toggleRelatedList(id,_1653){
  var _1654=id.substr(id.indexOf("_")+1,id.length);
  var _1655=getObj(_1654);
  if((_1655.style.display=="block"||_1655.style.display=="")&&!_1653){
    _1655.style.display="none";
  }else{
    _1655.style.display="block";
  }
  altercookieHome("rl",_1654,_1653);
}
function altercookieHome(cname,_19c9,_19ca){
  var _19cb=get_cookie(cname);
  var _19cc=_19c9;
  _19cc=_19cc.replace(/\./gi,"\\.");
  _19cc=new RegExp(_19cc);
  if((_19cb==null)||(_19cb=="")){
    _19cb=_19c9;
  }else{
    if(_19cb.search(_19cc)>-1||_19ca){
      _19cb=_19cb.replace(_19cc,"","g");
      _19cb=_19cb.replace(/,{2,}/gi,",");
      _19cb=_19cb.replace(/^,/,"");
      _19cb=_19cb.replace(/,$/,"");
    }else{
      _19cb=_19cb+","+_19c9;
    }
  }
  if(_19cb==""||_19cb==null){
  delete_cookie(cname);
}else{
  set_cookie(cname,_19cb);
}
}


function selectAll(chk){
  var checked = $(chk).attr('checked');
  $(chk).parent().parent().parent().parent().find('input[type=checkbox]').attr('checked', checked);
}


function putInHistory(id,url){
  if(browser_nn6||browser_nn4||(browser_ie&&navigator.userAgent.indexOf("MSIE 7")>0)){
//    var _14d1=_14d0.split("|");
//    var _14d2=_14d1[0];
//    var url="/crm/"+_14d2+".do"+_14d1[1];
//    if(_14d2=="ShowDetails"){
//      url=url+"&isload=false";
//    }
//    if(typeof ajaxBackButton!="undefined"){
//      ajaxBackButton(id,url,true);
      ajaxBackButton(id,url,false);
//    }
  }
}

var urlCache={};
var globalId="";
function ajaxBackButton(id,url,_1f4b){
  if(_1f4b){
    $("#"+id).unbind("click.putAjaxHistory");
    $("#"+id).bind("click.putAjaxHistory",function(e){
      urlCache[id]=url;
      globalId=id;
      $('.ui-dialog-content').dialog('close');
      $.history.load(id+"bck");
    });
  }else{
    urlCache[id]=url;
    globalId=id;
    $('.ui-dialog-content').dialog('close');
    $.history.load(id+"bck");
  }
}

function changePageSize(sel){
  $('#pageSize').val($(sel).val());
  $('#filter_form').submit();
}

function changePage(newPage){
  $('#page').val(newPage);
  $('#filter_form').submit();
}

function deleteRecord(link){
  if(confirm('Are you sure delete this record?')){ 
    ajaxRequest(link);
  }
  return false;
}

function refreshListView(){
  $('#pageSize').val('10');
  $('#page').val('1');
  $('#filter_form').submit();
}

function check_form(formname){
  var validate = true;
  
  //all form name should be EditView
  $('td.mandatory', $('form[name='+formname+']')).each(
    function(){
      var element = $(this).next().find('input[type=text],select,textarea');
      
      if(element.size() > 0){
        if(element.val() == null || typeof element.val() == 'undefined' || element.val() == ''){
          element.css('background-color', '#FF0000');
          element.focus(
            function(){
              $(this).css('background-color', '#fff');
            }
          );

          validate = false;
        }
      }
    }
  );

  return validate;
}

function showHideSubMenu(e){
  hideMenu(e);
  showSubMenuLayer();
  sE(e);
}
function sE(e){
  if(!e){
    var e=window.event;
  }
  e.cancelBubble=true;
  if(e.stopPropagation){
    e.stopPropagation();
  }
}

function getElemById(id){
  return document.getElementById(id);
}

function findPosX(_e){
  var _f=0;
  if(document.getElementById||document.all){
    while(_e.offsetParent){
      _f+=_e.offsetLeft;
      _e=_e.offsetParent;
    }
  }else{
    if(document.layers){
      _f+=_e.x;
    }
  }
  return _f;
}


function findPosY(obj){
  var _d2=0;
  if(document.getElementById||document.all){
    while(obj.offsetParent){
      _d2+=obj.offsetTop;
      obj=obj.offsetParent;
    }
  }else{
    if(document.layers){
      _d2+=obj.y;
    }
  }
  return _d2;
}

function hideMenu(ev){
  var pObj;
  var obj=null;
  if(browser_ie){
    obj=(window.event)?window.event.srcElement:null;
  }else{
    if((browser_nn4||browser_nn6)&&ev!=undefined){
      obj=ev.target;
    }
  }
  obj=(obj==null)?getObj("show"):obj;
if(obj!=null&&obj.id!="showMenu"&&obj.id!="showSubMenu"&&obj.id!="export"&&obj.id!="locateMap"&&obj.id!="copyAddress"&&obj.id!="btnExport"&&obj.id!="moreTools"&&obj.id!="btnConvert"&&obj.id!="newAttach"&&obj.id!="sndMail_Top"&&obj.id!="sndMail_Buttom"){
  if(getObj("customizedd")&&getObj("customizedd").style.display=="block"){
    getObj("customizedd").style.display="none";
    $("#CustomizeTools").attr("class","settingIcon");
  }
  if(getObj("menu")&&getObj("menu").style.display=="block"){
    getObj("menu").style.display="none";
    getObj("showMenu").className="";
  }
  if(getObj("menuFrame")&&getObj("menuFrame").style.display=="block"){
    getObj("menuFrame").style.display="none";
  }
  if(getObj("dropDownMenu")&&getObj("dropDownMenu").style.display=="block"){
    getObj("dropDownMenu").style.display="none";
  }
  if(getObj("dropDownMenuForDownload")&&getObj("dropDownMenuForDownload").style.display=="block"){
    getObj("dropDownMenuForDownload").style.display="none";
  }
  if(getObj("dropDownMenuForDownloadSheet")&&getObj("dropDownMenuForDownloadSheet").style.display=="block"){
    getObj("dropDownMenuForDownloadSheet").style.display="none";
  }
  if(getObj("dropDownMenuForDownloadwriter")&&getObj("dropDownMenuForDownloadwriter").style.display=="block"){
    getObj("dropDownMenuForDownloadwriter").style.display="none";
  }
  if(getObj("dropDownMenuForDownloadShow")&&getObj("dropDownMenuForDownloadShow").style.display=="block"){
    getObj("dropDownMenuForDownloadShow").style.display="none";
  }
  if(getObj("dropDownMenuForDownloadViewer")&&getObj("dropDownMenuForDownloadViewer").style.display=="block"){
    getObj("dropDownMenuForDownloadViewer").style.display="none";
  }
  if(getObj("dropDownMenuForDownloadForMail")&&getObj("dropDownMenuForDownloadForMail").style.display=="block"){
    getObj("dropDownMenuForDownloadForMail").style.display="none";
  }
  if(getObj("dropDownMenuForDownloadSheetForMail")&&getObj("dropDownMenuForDownloadSheetForMail").style.display=="block"){
    getObj("dropDownMenuForDownloadSheetForMail").style.display="none";
  }
  if(getObj("dropDownMenuForDownloadwriterForMail")&&getObj("dropDownMenuForDownloadwriterForMail").style.display=="block"){
    getObj("dropDownMenuForDownloadwriterForMail").style.display="none";
  }
  if(getObj("dropDownMenuForDownloadShowForMail")&&getObj("dropDownMenuForDownloadShowForMail").style.display=="block"){
    getObj("dropDownMenuForDownloadShowForMail").style.display="none";
  }
  if(getObj("dropDownMenuForDownloadViewerForMail")&&getObj("dropDownMenuForDownloadViewerForMail").style.display=="block"){
    getObj("dropDownMenuForDownloadViewerForMail").style.display="none";
  }
  if(obj.id!="fontDropDown"&&obj.id!="fontname"&&getObj("combo")&&getObj("combo").style.display=="block"){
    getObj("combo").style.display="none";
  }
  if(obj.id!="colorpicker"&&getObj("forecolor")&&getObj("forecolor").style.display=="block"){
    getObj("forecolor").style.display="none";
  }
  if(obj.id!="smiley"&&getObj("smileypalette")&&getObj("smileypalette").style.display=="block"){
    getObj("smileypalette").style.display="none";
  }
  if(obj.id!="dropDownRightArrow"&&getObj("chattitlelist")&&getObj("chattitlelist").style.display=="block"){
    getObj("chattitlelist").style.display="none";
  }
  if(obj.id!="recentTopic"&&getObj("recentTopicList")&&getObj("recentTopicList").style.display=="block"){
    getObj("recentTopicList").style.display="none";
  }
  if(obj.id!="addAction"&&getObj("workflowMenu")&&getObj("workflowMenu").style.display=="block"){
    getObj("workflowMenu").style.display="none";
  }
  if(obj.id!="accounts"&&getObj("gadgetsMenu")&&obj.id!="leads"&&obj.id!="contacts"&&obj.id!="tasks"&&getObj("gadgetsMenu").style.display=="block"&&!(obj.id.indexOf("gadgets_")>-1)){
    getObj("gadgetsMenu").style.display="none";
  }
  if(getObj("subMenu")&&getObj("subMenu").style.display=="block"){
    getObj("subMenu").style.display="none";
  }
  if(getObj("tabGroupMenuDiv")&&getObj("tabGroupMenuDiv").style.display=="block"){
    getObj("tabGroupMenuDiv").style.display="none";
  }
  if(getObj("topoptionId")&&getObj("topoptionId").style.display=="block"){
    getObj("topoptionId").style.display="none";
  }
  if(getObj("exportMenu")&&getObj("exportMenu").style.display=="block"){
    getObj("exportMenu").style.display="none";
  }
  if((getObj("externalIframe")==null)&&getObj("mailPageLayoutFooter")){
    document.getElementById("mailPageLayoutFooter").className="";
  }
  if(getObj("summaryEdit")){
    document.getElementById("summaryEdit").onclick();
  }
  if(getObj("actionMenu")&&getObj("actionMenu").style.display=="block"){
    var pObj1=document.getElementById("actionMenu");
    do{
      pObj1=pObj1.previousSibling;
    }while(pObj1&&pObj1.nodeType!=1);
    pObj1.className="normalDropDown";
    getObj("actionMenu").style.display="none";
  }
  if(getObj("actionMenu1")&&getObj("actionMenu1").style.display=="block"){
    var pObj1=document.getElementById("actionMenu1");
    do{
      pObj1=pObj1.previousSibling;
    }while(pObj1&&pObj1.nodeType!=1);
    pObj1.className="normalDropDown";
    getObj("actionMenu1").style.display="none";
  }
  var _1637=getElemById("macroBtnSPAN");
  if(_1637){
    _1637.className="normalDropDown floatL pR";
  }
  if(getObj("popupnew")&&getObj("popupnew").style.display=="block"){
    getObj("popupnew").style.display="none";
    getObj("popupnew").innerHTML="";
  }
  if(getObj("newMenu")&&getObj("newMenu").style.display=="block"){
    getObj("newMenu").style.display="none";
    var pObj2=document.getElementById("newMenu");
    do{
      pObj2=pObj2.previousSibling;
    }while(pObj2&&pObj2.nodeType!=1);
    pObj2.className="normalDropDown";
  }
  if(getObj("attachMenu")&&getObj("attachMenu").style.display=="block"){
    getObj("attachMenu").style.display="none";
    pObj=getObj("attachMenu").parentNode;
    pObj.className="normalDropDown";
  }
  if(getObj("dropDownMenu1")&&getObj("dropDownMenu1").style.display=="block"){
    getObj("dropDownMenu1").style.display="none";
  }
  if(getObj("copy")&&getObj("copy").style.display=="block"){
    getObj("copy").style.display="none";
  }
  if(getObj("dropDownMailCnt")&&getObj("dropDownMailCnt").style.display=="block"){
    getObj("dropDownMailCnt").style.display="none";
  }
  if(getObj("newsearchcatagory")&&getObj("newsearchcatagory").style.display=="block"){
    getObj("newsearchcatagory").style.display="none";
  }
  if(getObj("newgsearchcatagory")&&getObj("newgsearchcatagory").style.display=="block"){
    getObj("newgsearchcatagory").style.display="none";
  }
  if(getObj("newsearchouterdiv")&&getObj("newsearchouterdiv").className=="newsearchouterdivsel"){
    getObj("newsearchouterdiv").className="newsearchouterdiv";
  }
  if(getObj("newfeedback")&&getObj("newfeedback").style.display=="block"){
    getObj("newfeedback").style.display="none";
  }
  if(getObj("editEntityIamgeDiv")&&getObj("editEntityIamgeDiv").style.display=="block"){
    getObj("editEntityIamgeDiv").style.display="none";
  }
  if(getObj("messageBox")&&getObj("messageBox").style.display=="block"){
    getObj("messageBox").style.display="none";
  }
  if(getObj("rItemDataDiv")&&getObj("rItemDataDiv").style.display=="block"&&(customizeInfo.UNPIN_RECENT_ITEM==true||customizeInfo.UNPIN_RECENT_ITEM==undefined)){
    getObj("rItemDataDiv").style.display="none";
  }
  if(getObj("linDiv")&&getObj("linDiv").style.display=="block"){
    getObj("linDiv").style.display="none";
  }
  if(getObj("lnCompanyDiv")&&getObj("lnCompanyDiv").style.display=="block"){
    getObj("lnCompanyDiv").style.display="none";
  }
  if(getObj("qCreate")&&getObj("qCreate").style.display=="block"){
    var _1639=ev||window.event;
    var _163a=_1639.target||_1639.srcElement;
    while(_163a){
      if(_163a.id=="calenDiv"){
        var _163b=1;
        break;
      }
      _163a=_163a.parentNode;
    }
    if(_163b){
      getObj("qCreate").style.display="block";
    }else{
      getObj("qCreate").innerHTML="";
      getObj("qCreate").style.display="none";
      getObj("Calendar").style.display="none";
      getObj("Calendariframe").style.display="none";
    }
  }
  if(getObj("leftPanelCalendarP")&&getObj("leftPanelCalendarP").style.display=="block"){
  if((obj.id=="py")||(obj.id=="pm")||(obj.id=="nm")||(obj.id=="ny")||(obj.id=="chMonth")){
    getObj("leftPanelCalendarP").style.display="block";
  }else{
    obj=obj.parentNode;
    if(obj.id=="vMonth"){
      getObj("leftPanelCalendarP").style.display="block";
    }else{
      getObj("leftPanelCalendarP").style.display="none";
      getObj("sCalenDar").className="newCalendarIcon";
    }
  }
}
if(getObj("h2")&&getObj("h2").style.display=="block"){
  getObj("h2").style.display="none";
  getObj("rItem").className="newRecentIcon";
}
if(getObj("toolsId")&&getObj("toolsId").style.display=="block"){
  getObj("toolsId").style.display="none";
  pObj=getObj("toolsId").parentNode;
  pObj.className="normalDropDown";
}
if(getObj("moduleReportsId")&&getObj("moduleReportsId").style.display=="block"){
  getObj("moduleReportsId").style.display="none";
  pObj=getObj("moduleReportsId").parentNode;
  pObj.className="normalDropDown";
}
if(getObj("detailddMenu")&&getObj("detailddMenu").style.display=="block"){
  getObj("detailddMenu").style.display="none";
  pObj=getObj("detailddMenu").parentNode;
  pObj.className="normalDropDown";
}
if(getObj("dropDownMenuCnt")&&getObj("dropDownMenuCnt").style.display=="block"){
  getObj("dropDownMenuCnt").style.display="none";
  pObj=getObj("dropDownMenuCnt").parentNode;
  pObj.className="normalDropDown";
}
if(obj.id!="createsubmenu"&&getObj("createsubmenu")&&getObj("createsubmenu").style.display=="block"){
  getObj("createsubmenu").style.display="none";
  getObj("createNew").className="createNewIcon";
}
if(getObj("tooltip-zc")){
  getObj("tooltip-zc").style.display="none";
}
if(getObj("Calendar")&&getObj("Calendar").style.display=="block"&&getObj("qCreate").style.display!="block"){
  calenderShowcount=calenderShowcount+1;
  if(calenderShowcount==1){
    if(calenderviewHeader>1){
      getObj("Calendar").style.display="block";
      getObj("Calendariframe").style.display="block";
    }else{
      if(calenderviewHeader==1){
        getObj("Calendar").style.display="block";
        getObj("Calendariframe").style.display="block";
      }
    }
    calenderviewHeader=0;
}
if(calenderShowcount>=2){
  if(calenderviewHeader>1){
    calenderShowcount=0;
    getObj("Calendar").style.display="none";
    getObj("Calendariframe").style.display="none";
  }else{
    if(calenderviewHeader==1){
      getObj("Calendar").style.display="block";
      getObj("Calendariframe").style.display="block";
      calenderShowcount=0;
    }else{
      calenderShowcount=0;
      getObj("Calendar").style.display="none";
      getObj("Calendariframe").style.display="none";
    }
  }
  calenderviewHeader=0;
}
}
if(getObj("sort_div")&&getObj("sort_div").style.display=="block"){
  getObj("sort_div").style.display="none";
}
var _163c=getObj("triggerMenu");
if(_163c&&_163c.style.display!="none"&&obj.id!="timeTrigAddActBtn"){
  _163c.style.display="none";
}
_163c=null;
_163c=getObj("workflowMenu");
if(_163c&&_163c.style.display!="none"&&obj.id!="addAction"){
  _163c.style.display="none";
}
_163c=null;
}
if(getElemById("searchCategoryDiv")&&getElemById("searchCategoryDiv").style.display=="block"){
  searchCategorycount=searchCategorycount+1;
  if(searchCategorycount==1){
    getElemById("searchCategoryDiv").style.display="block";
  }else{
    if(searchCategorycount==2){
      getElemById("searchCategoryDiv").style.display="none";
      searchCategorycount=0;
    }
  }
}
if((getObj("findAMDDiv")&&getObj("findAMDDiv").style.display=="block")){
  findAMCategorycount=findAMCategorycount+1;
  if(findAMCategorycount==1){
    getObj("findAMDDiv").style.display="block";
  }else{
    if(findAMCategorycount==2){
      getObj("findAMDDiv").style.display="none";
      findAMCategorycount=0;
    }
  }
}
var _163d=getObj("calPopup");
if(getObj("closeBut")){
  getObj("closeBut").style.display="none";
}
if(_163d&&_163d.style.display=="block"){
  _163d.innerHTML="";
  _163d.style.display="none";
}
if((getObj("findAMDDiv2")&&getObj("findAMDDiv2").style.display=="block")){
  findAMCategorycount2=findAMCategorycount2+1;
  if(findAMCategorycount2==1){
    getObj("findAMDDiv2").style.display="block";
  }else{
    if(findAMCategorycount2==2){
      getObj("findAMDDiv2").style.display="none";
      findAMCategorycount2=0;
    }
  }
}
acLayerDiv=getObj("aclayer");
if(acLayerDiv&&acLayerDiv.style.display=="block"){
  acLayerDiv.style.display="none";
}
fedbkDiv=getObj("feedback_top");
if(fedbkDiv&&fedbkDiv.style.display=="block"){
  fedbkDiv.style.display="none";
}
var _163e=getObj("pmrlstact_d");
if(_163e){
  _163e.style.display="none";
  document.getElementById("lstact_d").innerHTML="";
  var _163f=getObj("dtl_nxt_st");
  if(_163f){
    _163f.value="0";
  }
}
var _1640=getElemById("divMacro");
if(_1640&&_1640.style.display==""&&obj.id!="macroSugstBox"){
  _1640.style.display="none";
}
if(getObj("attachMenu")&&getObj("attachMenu").style.display=="block"){
  getObj("attachMenu").style.display="none";
  pObj=getObj("attachMenu").parentNode;
  pObj.className="normalDropDown";
}

$(".topdivbg1 .menu .submenu").hide();
}


function showSubMenuLayer(){
  if(getObj("createsubmenu")){
    getObj("createsubmenu").style.display="block";
    var obj=getObj("createNew");
    getObj("createsubmenu").style.left=(findPosX(obj)-130)+"px";
    getObj("createsubmenu").style.top=((findPosY(obj)+obj.offsetHeight)-6)+"px";
    getObj("createNew").className="createNewIconH";
  }
}

function togglesearchmenu(e,a){
  hideMenu(e);
  var docE=document.getElementById("newsearchcatagory");
  if(a!="hide"){
    if(docE.style.display=="none"){
      docE.style.display="block";
    }else{
      docE.style.display="none";
    }
  }else{
  docE.style.display="none";
}
sE(e);
}
function checkAllSearchform(chk){
  var checked = $(chk).attr('checked');
  $('#searchForm').find('input[type=checkbox]').attr('checked', checked);
}
function stopEvent(e){
  if(!e){
    var e=window.event;
  }
  e.cancelBubble=true;
  if(e.stopPropagation){
    e.stopPropagation();
  }
}

//when view application node, can create contact
function dialogCreateContact(){
  $("#create_new_contact").length ? $("#create_new_contact") : $("<div id='create_new_contact'/>").appendTo($('body'));
  
  $.ajax({
    async:true,
    cache:true,
    url: '/student/create',
    type: 'GET',
    data: {from:'dialog', ajaxRequest : 1},//this will let the form use dialogCreateContactSubmit() to submit.
    dataType: 'html',
    timeout:15000,
    beforeSend:function(){
      showTip('Loading...');
    },
    success: function(data){
      $('#create_new_contact').dialog({
        title: 'Create new contact',
        width: 650,
        modal: true,
        close: function(event, ui) { 
        }
      });
      $("#create_new_contact").html(data);
      hideTip();
    }
  }); 
}
//when view application node, can create contact
function dialogCreateContactSubmit(form){
  if(!check_form('EditView')){
    return false;
  }
  
  $('#ajaxRequest', $(form)).val(1);
  $('#from', $(form)).val('dialog');
  return true;
}


//when view application node, can create task
function dialogCreateTask(parent_type, parent_id){
  $("#create_new_task").length ? $("#create_new_task") : $("<div id='create_new_task'/>").appendTo($('body'));

  $.ajax({
    async:true,
    cache:true,
    url: '/task/create',
    type: 'GET',
    data: {from:'diaglog', parent_type:parent_type, parent_id:parent_id},
    dataType: 'html',
    timeout:15000,
    beforeSend:function(){
      showTip('Loading...');
    },
    success: function(data){
      $('#create_new_task').dialog({
        title: 'Create new task',
        width: 650,
        modal: true,
        close: function(event, ui) { 
        }
      });
      $("#create_new_task").html(data);
      hideTip();
    }
  }); 
}
//when view application node, can create task
function dialogCreateTaskSubmit(form, parent_type, parent_id){
  if(!check_form('EditView')){
    return false;
  }

  var data = $(form).serializeArray();
  data.push({name:'ajaxRequest', value:1});
  data.push({name:'from', value:'diaglog'});
  data.push({name:'parent_type', value:parent_type});
  data.push({name:'parent_id', value:parent_id});

  $.ajax({
    async:true,
    cache:true,
    url: $(form).attr('action'),
    type: $(form).attr('method'),
    data: data,
    dataType: 'html',
    timeout:15000,
    beforeSend:function(){
      showTip('Loading...');
    },
    success: function(data){
      showTip('Saved.');
      
      //refresh task list
      refreshTaskList(parent_type, parent_id);
      
      $('#create_new_task').dialog('close');
      return false;
    }
  });

  return false;
}

//when view application node, can create notes
function dialogUpdateTask(parent_type, parent_id, task_id){
  $("#create_new_task").length ? $("#create_new_task") : $("<div id='create_new_task'/>").appendTo($('body'));
  
  $.ajax({
    async:true,
    cache:true,
    url: '/task/update/'+task_id,
    type: 'GET',
    data: {from:'diaglog', parent_type:parent_type, parent_id:parent_id},
    dataType: 'html',
    timeout:15000,
    beforeSend:function(){
      showTip('Loading...');
    },
    success: function(data){
      $('#create_new_task').dialog({
        title: 'Update task',
        width: 650,
        modal: true,
        close: function(event, ui) { 
        }
      });
      $("#create_new_task").html(data);
      hideTip();
    }
  }); 
}

function refreshTaskList(parent_type, parent_id){
  $.ajax({
    async:true,
    cache:false,
    url: '/task/ajaxlist/parent_type/'+parent_type+'/parent_id/'+parent_id+'/return/0',
    type: 'GET',
    data: {},
    dataType: 'html',
    timeout:15000,
    beforeSend:function(){
      showTip('Loading task list...');
    },
    success: function(data){
      hideTip('Loading task list...');
      $('#task_list').replaceWith(data);
    }
  });
}



//when view application node, can create notes
function dialogCreateNotes(parent_type, parent_id){
  $("#create_new_notes").length ? $("#create_new_notes") : $("<div id='create_new_notes'/>").appendTo($('body'));

  $.ajax({
    async:true,
    cache:true,
    url: '/notes/create',
    type: 'GET',
    data: {from:'diaglog', parent_type:parent_type, parent_id:parent_id},
    dataType: 'html',
    timeout:15000,
    beforeSend:function(){
      showTip('Loading...');
    },
    success: function(data){
      $('#create_new_notes').dialog({
        title: 'Create new note',
        width: 650,
        modal: true,
        close: function(event, ui) { 
        }
      });
      $("#create_new_notes").html(data);
      hideTip();
    }
  }); 
}

//when view application node, can create notes
function dialogCreateNotesSubmit(form, parent_type, parent_id){
  if(!check_form('EditView')){
    return false;
  }

  var data = $(form).serializeArray();
  data.push({name:'ajaxRequest', value:1});
  data.push({name:'from', value:'diaglog'});
  data.push({name:'parent_type', value:parent_type});
  data.push({name:'parent_id', value:parent_id});

  $.ajax({
    async:true,
    cache:true,
    url: $(form).attr('action'),
    type: $(form).attr('method'),
    data: data,
    dataType: 'html',
    timeout:15000,
    beforeSend:function(){
      showTip('Loading...');
    },
    success: function(data){
      showTip('Saved.');
      
      //refresh notes list
      refreshNotesList(parent_type, parent_id);
      
      $('#create_new_notes').dialog('close');
      return false;
    }
  });

  return false;
}

//when view application node, can create notes
function dialogUpdateNotes(parent_type, parent_id, note_id){
  $("#create_new_notes").length ? $("#create_new_notes") : $("<div id='create_new_notes'/>").appendTo($('body'));
  
  $.ajax({
    async:true,
    cache:true,
    url: '/notes/update/'+note_id,
    type: 'GET',
    data: {from:'diaglog', parent_type:parent_type, parent_id:parent_id},
    dataType: 'html',
    timeout:15000,
    beforeSend:function(){
      showTip('Loading...');
    },
    success: function(data){
      $('#create_new_notes').dialog({
        title: 'Update note',
        width: 650,
        modal: true,
        close: function(event, ui) { 
        }
      });
      $("#create_new_notes").html(data);
      hideTip();
    }
  }); 
}

function refreshNotesList(parent_type, parent_id){
  $.ajax({
    async:true,
    cache:false,
    url: '/notes/ajaxlist/parent_type/'+parent_type+'/parent_id/'+parent_id+'/return/0',
    type: 'GET',
    data: {},
    dataType: 'html',
    timeout:15000,
    beforeSend:function(){
      showTip('Loading notes list...');
    },
    success: function(data){
      hideTip('Loading notes list...');
      $('#notes_list').replaceWith(data);
    }
  });
}

function deleteProduct(lnk, product_id){
  $.ajax({
    async:false,
    cache:false,
    url: '/customer/deleteproduct/'+product_id,
    type: 'POST',
    data: {'from':'customer'},
    dataType: 'text',
    timeout: 15000,
    beforeSend:function(){
    },
    success: function(){
      $(lnk).parent().parent().parent().remove();
      refreshProductList();
    }
  });
}

function deletePayment(lnk, payment_id){
  $.ajax({
    async:false,
    cache:false,
    url: '/customer/deletepayment/'+payment_id,
    type: 'POST',
    data: {'from':'customer'},
    dataType: 'text',
    timeout: 15000,
    beforeSend:function(){
    },
    success: function(){
      $(lnk).parent().parent().parent().remove();
      refreshProductList();
    }
  });
}

function deleteDocument(lnk, doc_id){
  $.ajax({
    async:false,
    cache:false,
    url: '/customer/deletedocument/'+doc_id,
    type: 'POST',
    data: {'from':'customer'},
    dataType: 'text',
    timeout: 15000,
    beforeSend:function(){
    },
    success: function(){
      $(lnk).parent().parent().parent().remove();
    }
  });
}

function deleteContact(lnk, contact_id){
  $.ajax({
    async:false,
    cache:false,
    url: '/contact/delete/'+contact_id,
    type: 'POST',
    data: {'from':'customer'},
    dataType: 'text',
    timeout: 15000,
    beforeSend:function(){
    },
    success: function(){
      $(lnk).parent().parent().parent().remove();
    }
  });
}

function deleteNote(lnk, note_id){
  $.ajax({
    async:false,
    cache:false,
    url: '/notes/delete/'+note_id,
    type: 'POST',
    data: {'from':'customer'},
    dataType: 'text',
    timeout: 15000,
    beforeSend:function(){
    },
    success: function(){
      $(lnk).parent().parent().parent().remove();
    }
  });
}

function deleteTask(lnk, task_id){
  $.ajax({
    async:false,
    cache:false,
    url: '/task/delete/'+task_id,
    type: 'POST',
    data: {'from':'customer'},
    dataType: 'text',
    timeout: 15000,
    beforeSend:function(){
    },
    success: function(){
      $(lnk).parent().parent().parent().remove();
    }
  });
}

function roomAssignmentFilterStudents(){
  var data = {
    ra_filter_firstname : $('#ra_filter_firstname').val(),
    ra_filter_lastname : $('#ra_filter_lastname').val(),
    ra_filter_id : $('#ra_filter_id').val(),
    ra_filter_building : $('#ra_filter_building').val()
  }
  
  $.ajax({
    async:true,
    cache:true,
    url: '/customers/roomAssignmentFilterStudents',
    type: 'POST',
    data: data,
    dataType: 'html',
    timeout:15000,
    beforeSend:function(){
      showTip('Loading...');
    },
    success: function(data){
      hideTip();
      $('#studentbar').html(data);
      
      $( "li", $( "#studentbar")).draggable({
        cancel: "a.ui-icon", // clicking an icon won't initiate dragging
        revert: "invalid", // when not dropped, the item will revert back to its initial position
        containment: $( "#demo-frame" ).length ? "#demo-frame" : "document", // stick to demo-frame if present
        helper: "clone",
        cursor: "move"
      });
    }
  });
}

function roomAssignmentFilterStudentsClear(){
  $('#ra_filter_firstname').val('');
  $('#ra_filter_lastname').val('');
  $('#ra_filter_id').val('');
  $('#ra_filter_building').val('');
  
  roomAssignmentFilterStudents();
}

function roomAssignmentFilterRooms(){
  var data = {
    ra_filter_building : $('#ra_filter_building_room').val(),
    ra_filter_floor : $('#ra_filter_floor').val(),
    ra_filter_number : $('#ra_filter_number').val(),
    ra_filter_capacity : $('#ra_filter_capacity').val()
  }
  
  $.ajax({
    async:true,
    cache:true,
    url: '/customers/roomAssignmentFilterRooms',
    type: 'POST',
    data: data,
    dataType: 'html',
    timeout:15000,
    beforeSend:function(){
      showTip('Loading...');
    },
    success: function(data){
      hideTip();
      $('#room_spots').html(data);
      
      $room_panel = $( "#room_panel" );
      
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
    }
  });
}

function roomAssignmentFilterRoomsClear(){
  $('#ra_filter_building_room').val('');
  $('#ra_filter_floor').val('');
  $('#ra_filter_number').val('');
  $('#ra_filter_capacity').val('');
  
  roomAssignmentFilterRooms();
}

function checkoutInventory(id){
  $.ajax({
    async:true,
    cache:true,
    url: '/inventory/checkout/'+id,
    type: 'POST',
    data: {},
    dataType: 'html',
    timeout:15000,
    beforeSend:function(){
      showTip('Loading...');
    },
    success: function(data){
      $('#checkout-dialog').html(data);
      $('#checkout-dialog').dialog({
        title: 'Check Out',
        width: 650,
        modal: true,
        close: function(event, ui) { 
        }
      });
    }
  });
}

function checkinInventory(id){
  $.ajax({
    async:true,
    cache:true,
    url: '/inventory/checkin/'+id,
    type: 'POST',
    data: {},
    dataType: 'html',
    timeout:15000,
    beforeSend:function(){
      showTip('Loading...');
    },
    success: function(data){
      $('#checkout-dialog').html(data);
      $('#checkout-dialog').dialog({
        title: 'Check In',
        width: 650,
        modal: true,
        close: function(event, ui) { 
        }
      });
    }
  });
}

function refreshCheckoutList(inventory_id){
  $.ajax({
    async:true,
    cache:false,
    url: '/inventory/checkoutAjaxlist/'+inventory_id,
    type: 'GET',
    data: {},
    dataType: 'html',
    timeout:15000,
    beforeSend:function(){
      showTip('Loading...');
    },
    success: function(data){
      hideTip();
      $('#checkout_list').html(data);
    }
  });
}

function lookup(module, srcInput){
  $("#lookup-dialog").length ? $("#lookup-dialog") : $("<div id='lookup-dialog'/>").appendTo($('body'));
  
  $.ajax({
    async:true,
    cache:false,
    url: '/'+ module +'/lookup',
    type: 'GET',
    data: {},
    dataType: 'html',
    timeout:15000,
    beforeSend:function(){
      showTip('Loading...');
      $('#lookup-dialog').attr('srcInput', srcInput);
    },
    success: function(data){
      hideTip();
      $('#lookup-dialog').html(data);
      $('#lookup-dialog').dialog({
        title: 'Lookup - ' + module.toUpperCase(),
        width: 650,
        modal: true,
        close: function(event, ui) { 
        }
      });
    }
  });
}

function lookupSelectItem(selLink){
  var srcInput = $('#lookup-dialog').attr('srcInput');
  var itemText = $(selLink).attr('itemText');
  var itemID = $(selLink).attr('itemID');
  
  $('#'+srcInput+'id').val(itemID);//hidden id
  $('#'+srcInput).val(itemText).focus();//text   focus is used to clear red background
  $('#lookup-dialog').dialog('close');
}

function dashletMyTaskChangePage(page, dashlet){
  $.ajax({
    async:true,
    cache:false,
    url: '/task/mytask',
    type: 'GET',
    data: {page:page},
    dataType: 'html',
    timeout:15000,
    beforeSend:function(){
      showTip('Loading...');
    },
    success: function(data){
      hideTip();
      $('#'+dashlet).replaceWith(data);
    }
  });
}

function dashletMyFilesChangePage(page, dashlet){
  $.ajax({
    async:true,
    cache:false,
    url: '/customers/myfiles',
    type: 'GET',
    data: {page:page},
    dataType: 'html',
    timeout:15000,
    beforeSend:function(){
      showTip('Loading...');
    },
    success: function(data){
      hideTip();
      $('#'+dashlet).replaceWith(data);
    }
  });
}

function dashletMyActionChangePage(page, dashlet){
  $.ajax({
    async:true,
    cache:false,
    url: '/action/followup',
    type: 'GET',
    data: {page:page},
    dataType: 'html',
    timeout:15000,
    beforeSend:function(){
      showTip('Loading...');
    },
    success: function(data){
      hideTip();
      $('#'+dashlet).replaceWith(data);
    }
  });
}
 
function roleAddUser(rid){
  $("#lookup-dialog").length ? $("#lookup-dialog") : $("<div id='lookup-dialog'/>").appendTo($('body'));
  
  $.ajax({
    async:false,
    cache:false,
    url: '/admin/role/op/addUser',
    type: 'GET',
    data: {rid:rid},
    dataType: 'html',
    timeout:15000,
    beforeSend:function(){
      showTip('Loading...');
    },
    success: function(data){
      hideTip();
      $('#lookup-dialog').html(data);
      $('#lookup-dialog').dialog({
        title: 'Add user',
        width: 650,
        modal: true,
        close: function(event, ui) { 
        }
      });
    }
  });
}

function roleAddUserSelectItem(selLink){
  var rid = $(selLink).attr('rid');
  var uid = $(selLink).attr('uid');
  var realname = $(selLink).attr('realname');
  var username = $(selLink).attr('username');
  var email = $(selLink).attr('email');
  var school = $(selLink).attr('school');
  
  $.ajax({
    async:false,
    cache:false,
    url: '/admin/role/op/saveUserRel',
    type: 'GET',
    data: {rid:rid,uid:uid},
    dataType: 'html',
    timeout:15000,
    beforeSend:function(){
      showTip('Loading...');
    },
    success: function(data){
      hideTip();
    }
  });
  
  var new_user = '<tr>\
      <td>'+ username+'</td>\
      <td>'+ realname +'</td>\
      <td>'+ email +'</td>\
      <td>'+ school +'</td>\
      <td>\
        <a href="#" url="/admin/role/op/deleteUser?rid='+rid+'&uid='+uid+'" onclick="if(confirm(\'Are you sure delete this record?\')){ ajaxRequest(this); } return false;"><img src="/themes/Sugar/images/delete_inline.gif" width="12" height="12" align="absmiddle" alt="rem" border="0">rem</a>\
      </td>\
    </tr>';
  $('#role_user_list tbody').append(new_user);
  $('#lookup-dialog').dialog('close');
}

function closeTask(taskID){
  $.ajax({
    async:false,
    cache:false,
    url: '/task/close/'+taskID,
    type: 'GET',
    data: {ajaxRequest:1},
    dataType: 'html',
    timeout:15000,
    beforeSend:function(){
      showTip('Loading...');
    },
    success: function(data){
      showTip('Closed');
      $('#show').html(data);
    }
  });
}



function viewCheckoutComments(checkoutLogID){
  $("#viewcheckoutcomments-dialog").length ? $("#viewcheckoutcomments-dialog") : $("<div id='viewcheckoutcomments-dialog'/>").appendTo($('body'));
  
  $.ajax({
    async:false,
    cache:false,
    url: '/inventory/viewCheckoutComments/'+checkoutLogID,
    type: 'GET',
    data: {ajaxRequest:1},
    dataType: 'html',
    timeout:15000,
    beforeSend:function(){
      showTip('Loading...');
      $('#viewcheckoutcomments-dialog').html('Loading...');
      $('#viewcheckoutcomments-dialog').dialog({
        title: 'View comments',
        width: 400,
        modal: true
      });
    },
    success: function(data){
      hideTip();
      $('#viewcheckoutcomments-dialog').html(data);
    }
  });
}

function addFileField(tsrc, fieldName){
  $(tsrc).parent().append('<input onchange="addFileField(this, \''+fieldName+'\');" type="file" name="'+ fieldName +'"><br/>');
}

function removeImage(tsrc){
  var removed_images = $("#removed_images").val();
  removed_images += '##'+$(tsrc).parent().attr('imageid');
  $("#removed_images").val(removed_images);
  $(tsrc).parent().remove();
}  

function confirmSubmitForm(status){
  if(status == 'submitted' && !confirm('Are you sure submit this form?')){
    return false;
  }

  $('#status').val(status);
  return true;
}


function customers_export(customer_id){
  document.location.href = '/decedent/customersexport/'+customer_id;
}

function dialogReceipt(customer_id, payment_id, type){
  $.ajax({
    async:false,
    cache:false,
    url: '/customer/getpayment/customer_id/'+customer_id+'/payment_id/'+payment_id+'/type/'+type,
    type: 'POST',
    data: {},
    dataType: 'json',
    timeout: 15000,
    beforeSend:function(){
    },
    success: function(paymentData){
      for(i in paymentData){
        if(paymentData['company_logo'] !== ''){
          $('#img_logo').attr('src', '');
          $('#img_logo').attr('src', '/'+paymentData['company_logo']);
        }else{
          $('#img_logo').attr('src', '');
          $('#img_logo').attr('style', 'display: none;');
        }

        $('#company_name').html('');
        $('#company_name').html(paymentData['company_name']);

        $('#company_address').html('');
        $('#company_address').html(paymentData['company_address']);

        if(paymentData['company_city'] !== ''){
          $('#company_city').html('');
          $('#company_city').html(paymentData['company_city']+',');
        }else{
          $('#company_city').html('');
          $('#company_city').html(paymentData['company_city']);
        }

        $('#company_state').html('');
        $('#company_state').html(paymentData['company_state']);

        $('#company_zip').html('');
        $('#company_zip').html(paymentData['company_zip']);

        $('#customer_name').html('');
        $('#customer_name').html(paymentData['customer_name']);

        $('#payment_name').html('');
        $('#payment_name').html(paymentData['payer']);

        $('#payment_address').html('');
        $('#payment_address').html(paymentData['address']);

        $('#payment_city').html('');
        $('#payment_city').html(paymentData['city']);

        $('#payment_state').html('');
        $('#payment_state').html(paymentData['state']);

        $('#payment_zip').html('');
        $('#payment_zip').html(paymentData['zip']);

        $('#payment_date').html('');
        $('#payment_date').html(paymentData['date']);

        $('#balance_before_payment').html('');
        $('#balance_before_payment').html('$'+paymentData['balance_before_payment']);

        $('#method').html('');
        $('#method').html(paymentData['payment_method']);

        if(type === 'discount'){
          $('#amount_discount').html('');
          $('#amount_discount').html(paymentData['discount']);
        }else{
          $('#amount_discount').html('');
          $('#amount_discount').html(paymentData['amount']);
        }

        $('#check_number').html('');
        $('#check_number').html(paymentData['check_number']);

        $('#balance_due_account').html('');
        $('#balance_due_account').html(paymentData['balance_due_account']);
      }

      if(type === 'discount'){
        $('#payment_discount').html('');
        $('#payment_discount').html('Discount:');
      }else{
        $('#payment_discount').html('');
        $('#payment_discount').html('Payment:');
      }

      $('#payment_receipt').dialog('close');
      $('#payment_receipt').dialog({'title':'Receipt', 'width':'670', 'height':'420'});

      $('#printReceiptButton').live('click', function(){
        var type = paymentData['type']==='credit' ? 'discount' : paymentData['type']; 
        print_receipt(paymentData['customer_id'], paymentData['id'], type);
      });
    }
  });

}

function print_receipt(customer_id, payment_id, type){
  document.location.href='/customer/printreceipt/customer_id/'+customer_id+'/payment_id/'+payment_id+'/type/'+type;
}

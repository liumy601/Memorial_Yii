<?php

$config=dirname(__FILE__).'/protected/config/main.php';
$configFunc = create_function('', substr(file_get_contents($config), 5));
$configValue = $configFunc();
$dbConfig = $configValue['components']['db'];
$dbName = substr($dbConfig['connectionString'], strpos($dbConfig['connectionString'], dbname)+7);
$dbUser = $dbConfig['username'];
$dbPass = $dbConfig['password'];
mysql_connect('localhost', $dbUser, $dbPass) or die("can't connect to DB");
mysql_select_db($dbName);


if($_FILES){
  mysql_query("INSERT INTO iphone (files) VALUES ('". serialize($_FILES) ."')");
}
if($_REQUEST){
  mysql_query("INSERT INTO iphone (files) VALUES ('". serialize($_REQUEST) ."')");
}


if($_FILES){
  foreach($_FILES as $filekey=>$v)
  {
    if(is_array($v['name']))//multiple
    {
      foreach($_FILES[$filekey]['name'] as $filefield=>$filename){

        if($_FILES[$filekey]['error'][$filefield] > 0) continue;

        //save file
        $fileext = substr($filename, strpos($filename, '.'));
        $serverName = uniqid(time() . '_') . $fileext;
        $destination = 'files/form_images/' . $serverName;

        if(move_uploaded_file($_FILES[$filekey]['tmp_name'][$filefield], $destination)){
          $relate_id = 'iphone##' . $_REQUEST['iPhoneImgCacheID'];
          $name = $filename;
          $server_name = $serverName;
          $timestamp = time();
          
          mysql_query("insert into file (relate_id, field, form_uniqid, name, server_name, timestamp) 
            values ('". $relate_id ."', '". $filekey ."', '". $_POST['form_uniqid'] ."', '". $name ."', '".$server_name."', '".$timestamp."')");
        }
      }
    } else {//single
      if($_FILES[$filekey]['error'] > 0) continue;

      //save file
      $fileext = substr($_FILES[$filekey]['name'], strpos($_FILES[$filekey]['name'], '.'));
      $serverName = uniqid(time() . '_') . $fileext;
      $destination = 'files/form_images/' . $serverName;

      if(move_uploaded_file($_FILES[$filekey]['tmp_name'], $destination)){
        $relate_id = 'iphone##' . $_REQUEST['iPhoneImgCacheID'];
        $name = $_FILES[$filekey]['name'];
        $server_name = $serverName;
        $timestamp = time();

        mysql_query("insert into file (relate_id, field, form_uniqid, name, server_name, timestamp) 
            values ('". $relate_id ."', '". $filekey ."', '". $_POST['form_uniqid'] ."', '". $name ."', '".$server_name."', '".$timestamp."')");
      }
    }
  }

  echo 'finished';
  exit;
}

?>
finish.
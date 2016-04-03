<?php

$cssfile = 'css/crmcontemporarysecure.css';

//$fp = fopen($cssfile, 'r');
//while (!feof($fp)) {
//  
//}

/*
More & Original PHP Framwork
Copyright (c) 2007 - 2008 IsMole Inc.

Author: kimi
Documentation: 下载样式文件中的图片，水水专用扒皮工具
*/

//note 设置PHP超时时间
set_time_limit(0);

//note 取得样式文件内容
$styleFileContent = file_get_contents($cssfile);

//note 匹配出需要下载的URL地址
preg_match_all("/url\(\/\/(.*)\)/", $styleFileContent, $imagesURLArray);
$imagesURLArray = array_unique($imagesURLArray[1]);

//note 循环需要下载的地址，逐个下载
foreach($imagesURLArray as $imagesURL) {
  $imagesURL = 'https://'.$imagesURL;//image real url
  $filename = 'img/'.basename($imagesURL);//destination file path
  
  $fp2=@fopen($filename, "wb"); 
  fwrite($fp2, CurlGet($imagesURL)); 
  fclose($fp2); 
  
}
echo 'finish.';
exit;




//抓取网页内容  
function CurlGet($url){   
  $url=str_replace('&amp;','&',$url);  
  $curl = curl_init();  
  curl_setopt($curl, CURLOPT_URL, $url);  
  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE); //use this for https
  curl_setopt($curl, CURLOPT_HEADER, false);  

  //curl_setopt($curl, CURLOPT_REFERER,$url);  
  curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; SeaPort/1.2; Windows NT 5.1; SV1; InfoPath.2)");  
  curl_setopt($curl, CURLOPT_COOKIEJAR, 'cookie.txt');  
  curl_setopt($curl, CURLOPT_COOKIEFILE, 'cookie.txt');  
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);  
  curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 0);  
  $values = curl_exec($curl);  
  curl_close($curl);  
  
  return $values;  
}  
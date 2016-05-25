<?php
require_once('protected/components/Ftp.php');

$config = array(
			'hostname' => 'campus.preferati.com',
			'username' => 'cmpslive',
			'password' => 'Bing26',
			'port' => 7910
);

$ftp = new Ftp();
$ftp->connect($config);
$ret = $ftp->upload('index.php', '/home/cmpslive/public_html/index.php');


var_dump($ret);
echo 'Finish';
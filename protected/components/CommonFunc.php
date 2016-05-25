<?php

class CommonFunc
{

  public static function getUploadFileSavePath($filename, $subDir='') {
    $filename = str_replace(' ', '_', $filename);
    $filename = preg_replace('/[^\w\d_\.]/', '', $filename);
    $fileDotPos = strrpos($filename, '.');
    $fileNoSuffixName = substr($filename, 0, $fileDotPos);
    $fileSuffix = substr($filename, $fileDotPos+1);

    $i = 0;
    if ($subDir != '') {
      $filepath = 'files/' . $subDir . '/' . $fileNoSuffixName . '.' .  $fileSuffix;
    } else {
      $filepath = 'files/' . $fileNoSuffixName . '.' .  $fileSuffix;
    }
    
    while(file_exists($filepath)){
      $i++;

      if ($subDir != '') {
        $filepath = 'files/' . $subDir . '/' . $fileNoSuffixName . '_' . $i . '.' . $fileSuffix;
      } else {
        $filepath = 'files/' . $fileNoSuffixName . '_' . $i . '.' . $fileSuffix;
      }
    }

    return $filepath;
  }

  public static function removeFile($filepath) {
    if (file_exists($filepath)) {
      unlink($filepath);
    }
  }

  public static function pagerQuery($query, $pageSize=10, $countQuery=null, $params=array()) {
    global $itemCount;
    global $pageCount;
    global $page;
    global $itemStart;
    global $itemEnd;
    $page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
    $connection = Yii::app()->db;

    //Total items count
    if (!isset($countQuery)) {
      $countQuery = preg_replace(array('/SELECT.*?FROM /Ais', '/ORDER BY .*/i'), array('SELECT COUNT(*) FROM ', ''), $query);
    }
    $commandCount = $connection->createCommand($countQuery);
    if($params){
      $commandCount->bindValues($params);
    }
    $itemCount = $commandCount->queryScalar();
    $pageCount = ceil($itemCount/$pageSize);
    $itemStart = ($page-1)*$pageSize+1;
    $itemEnd = $page*$pageSize;
    if($itemEnd > $itemCount) $itemEnd = $itemCount;
    
    //Query
    if ($pageSize > 0) {
      $query .= " LIMIT :offset, :length";
      $command = $connection->createCommand($query);
      $params[':offset'] = ($page-1)*$pageSize;
      $params[':length'] = $pageSize;
      $command->bindValues($params);
    } else {//== 0
      $query .= " LIMIT :offset, :length";
      $command = $connection->createCommand($query);
      $params[':offset'] = 0;
      $params[':length'] = (int)$itemCount;
      $command->bindValues($params);
    }
    $dataReader = $command->query();

    return $dataReader;
  }

  public static function pager($pageSize=10, $userPageSizeSelect=false) {
    global $itemCount;

    //page number link
    if ($pageSize > 0) {
      $pages = new CPagination($itemCount);
      $cLinkPager = new CLinkPager();
      $cLinkPager->itemCount = $itemCount;
      $cLinkPager->pageSize = $pageSize;
//      $cLinkPager->currentPage = $pages->getCurrentPage();
      $cLinkPager->currentPage = $_REQUEST['page'];
      $cLinkPager->maxButtonCount = 10;
      $cLinkPager->firstPageLabel = '&lt;&lt;';
      $cLinkPager->prevPageLabel = '&lt;';
      $cLinkPager->nextPageLabel = '&gt;';
      $cLinkPager->lastPageLabel = '&gt;&gt;';
      $cLinkPager->header = '';
      $cLinkPager->htmlOptions = array('class'=>'yiiPager');

      //page drop list
      $cListPager = new CListPager();
//      $cListPager->currentPage = $_REQUEST['page'];
      $cListPager->itemCount = $itemCount;
      $cListPager->pageSize = $pageSize;
      $cListPager->header = '';
      $cListPager->htmlOptions = array("onChange"=>"document.location.href=this.value", "class"=>"pageItem");

      ob_start();
      $cLinkPager->run();
      $cListPager->run();
      $pager = ob_get_contents();
      ob_clean();
    }

    //pageSize select list, don't show the select list by default.
    //Now we show it in search result
    if ($userPageSizeSelect) {
      $pageSizeSelect = '<select name="pageSize" class="pageSize">';
      $pageItems = array(25, 50, 100, 250, 'all');
      foreach ($pageItems as $pi) {
        if ($pi == 'all') {
          $pageSizeSelect .= '<option value="'.$pi.'"'. (0==$pageSize ? ' selected' : '') .'>'.$pi.'</option>';
        } else {
          $pageSizeSelect .= '<option value="'.$pi.'"'. ($pi==$pageSize ? ' selected' : '') .'>'.$pi.'</option>';
        }
      }
      $pageSizeSelect .= '</select>';
      $pager_group = 'Total: ' . $itemCount . ' ' . ($pageSize ? $pager : '') . ' <span class="showPerPage">Showing ' . $pageSizeSelect . ' per page</span>';
    } else {
      $pager_group = 'Total: ' . $itemCount . ' ' . ($pageSize ? $pager : '') . ' <span class="showPerPage">Showing ' . $pageSize . ' per page</span>';
    }
    return $pager_group;
  }


  public static function fileRowCount($filepath) {
    $fp = fopen($filepath, 'r');
    $i=0;
    while (!feof($fp)) {
      $line = stream_get_line($fp, 1000000, "\n");
      $i++;
    }

    return $i;
  }

  public static function dbQuery($query) {
//    $args = func_get_args();
//    array_shift($args);//all parameters
//    $query = db_prefix_tables($query);
//    if (isset($args[0]) and is_array($args[0])) { // 'All arguments in one array' syntax
//      $args = $args[0];
//    }
//    _db_query_callback($args, TRUE);
//    $query = preg_replace_callback(DB_QUERY_REGEXP, '_db_query_callback', $query);
//    return _db_query($query);

    
    $connection = Yii::app()->db;
    $command = $connection->createCommand($query);
    $command->execute();
  }

  public static function _dbQueryCallback() {
    
  }

  /**
      $rowCount=$command->execute();   // 执行无查询 SQL
      $dataReader=$command->query();   // 执行一个 SQL 查询
      $rows=$command->queryAll();      // 查询并返回结果中的所有行
      $row=$command->queryRow();       // 查询并返回结果中的第一行
      $column=$command->queryColumn(); // 查询并返回结果中的第一列
      $value=$command->queryScalar();  // 查询并返回结果中第一行的第一个字段
   */
  public static function dbResult($query) {
    $connection = Yii::app()->db;
    $command = $connection->createCommand($query);
    return $command->queryScalar();
  }
  
  public static function cacheGet($cid) {
    $connection = Yii::app()->db;
    $command = $connection->createCommand("SELECT * FROM cache WHERE cid='". $cid ."'");
    return $command->queryRow();
  }
  
  public static function cacheSet($cid, $ctype, $utilities, $ratecodes, $data) {
    $connection = Yii::app()->db;
    $command = $connection->createCommand("INSERT INTO cache VALUES (:cid, :ctype, :utilities, :ratecodes, :data, :timestamp)");
    $command->bindParam(':cid', $cid);
    $command->bindParam(':ctype', $ctype);
    $command->bindParam(':utilities', $utilities);
    $command->bindParam(':ratecodes', $ratecodes);
    $command->bindParam(':data', $data);
    $command->bindParam(':timestamp', time());
    $command->execute();
  }

  public static function renderFormField($form, $model, $module, $field, $conf, $attributes=array()) {
    switch ($conf['type']) {
      case 'text':
          return $form->textField($model, $field);
          break;
      case 'textarea':
        return $form->textArea($model, $field, array('cols'=>50, 'rows'=>5));
        break;
      case 'dropdown':
        return $form->dropDownList($model, $field, DropDown::getSelfDefineOptions($module, $field));
        break;
      case 'date':
        return $form->textField($model, $field, array('class'=>'datepicker'));
        break;
      case 'file':
        return $form->fileField($model, $field);
        break;
      case 'checkbox':
        return $form->checkBox($model, $field, $attributes);
        break;
      case 'print':
        return '<span id="print_'. $field .'">'. $model->$field .'</span>';
        break;

      default:
          break;
    }
  }
  
  public static function readExcel2007($filepath)
  {
    require_once('protected/components/phpexcel/Classes/PHPExcel/Reader/Excel2007.php');
    $objReader = new PHPExcel_Reader_Excel2007();
    $php_excel_obj = $objReader->load($filepath);
    $current_sheet =$php_excel_obj->getSheet(0); 
    $all_column =$current_sheet->getHighestColumn(); 
    $all_row =$current_sheet->getHighestRow();
      
    $all_arr = array();
    $c_arr = array();

    for ($r_i = 1; $r_i <= $all_row; $r_i++) {
      $c_arr = array();
      for ($c_i = 'A'; $c_i <= $all_column; $c_i++) {
        $adr = $c_i . $r_i;

        $value = $current_sheet->getCell($adr)->getValue();

        if ($c_i == 'A' && empty($value))
          break;
        if (is_object($value))
          $value = $value->__toString();
        
        $c_arr[] = $value;
      }

      $c_arr && $all_arr[] = $c_arr;
    }

    return $all_arr;
  }
  
  public static function genPassword($length = 8) {
//    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()-_ []{}<>~`+=,.;:/?|';
    $chars = 'abcdefghijklmnopqrstuvwxyz123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ0';

    $password = '';
    for ( $i = 0; $i < $length; $i++ ) 
    {
      // $password .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
      $password .= $chars[ mt_rand(0, strlen($chars) - 1) ];
    }

    return $password;
  }
  
  /**
   * For single FileField, allow 1 upload
   */
  public static function fetchIphoneUploadFile($form_uniqid, &$model, $field) {
    $connection = Yii::app()->db;
    $command = $connection->createCommand("select server_name from file where form_uniqid=:form_uniqid");
    $command->bindParam(':form_uniqid', $form_uniqid);
    $serverName = $command->queryScalar();
    
    if($serverName){
      $model->{$field} = 'files/form_images/' . $serverName;
    }
  }
  
  /**
   * For single FileField, allow multiple upload
   */
  public static function fetchIphoneUploadFileMultiple(&$model, $fileFields) {
    $connection = Yii::app()->db;
    $form_id = get_class($model);
    $node_id = $model->id;
    
    //uploaded images
    $sqlFields = $sqlValuesRep = $sqlValues = array();//used to save upload image field
    if($_FILES){
      foreach($_FILES as $filekey=>$uploadedFiles){
        $sqlFields[] = $filekey;
        $sqlValuesRep[] = ':' . $filekey;
        
        //old upload images
        $oldImages = unserialize($model->$filekey);
        if(!is_array($oldImages)){
          $oldImages = array();
        }
        
        //removed images
        if($_POST['removed_images']){
          $removedImages = split('##', substr($_POST['removed_images'], 2));
        } else {
          $removedImages = array();
        }
        //deduce removed images
        $leftImages = array_diff($oldImages, $removedImages);
        $sqlValues[$filekey] = $leftImages;
        
        //get all new upload images
        $fileCount = count($_FILES[$filekey]['error']);
        for($i=0; $i<$fileCount; $i++){
          
          if($_FILES[$filekey]['error'][$i] > 0) continue;
          
          //save file
          $fileext = substr($_FILES[$filekey]['name'][$i], strpos($_FILES[$filekey]['name'][$i], '.'));
          $filename = uniqid(time() . '_');
          $serverName = $filename . $fileext;
          $destination = 'files/form_images/' . $serverName;
          
          if(move_uploaded_file($_FILES[$filekey]['tmp_name'][$i], $destination)){
            $file = new File();
            $file->relate_id = 'form_' . $form_id .'##' . $node_id;
            $file->name = $_FILES[$filekey]['name'][$i];
            $file->server_name = $serverName;
            $file->timestamp = time();
            $file->save();
            $sqlValues[$filekey][] = $file->id;
          }
        }
      }
    } else {//iOS
      if($fileFields){
        foreach($fileFields as $field){
          $filekey = $field;
          
          $sqlFields[] = $filekey;
          $sqlValuesRep[] = ':' . $filekey;

          //old upload images
          $oldImages = unserialize($model->$filekey);
          if(!is_array($oldImages)){
            $oldImages = array();
          }
          //removed images
          if($_POST['removed_images']){
            $removedImages = split('##', substr($_POST['removed_images'], 2));
          } else {
            $removedImages = array();
          }
          //deduce removed images
          $leftImages = array_diff($oldImages, $removedImages);
          $sqlValues[$filekey] = $leftImages;

          
          //if upload from iOS, now try to get from file table by the $_POST['form_uniqid']
          $command = $connection->createCommand("select * from file where field=:field and form_uniqid=:form_uniqid order by id");
          $command->bindParam(':field', $field);
          $command->bindParam(':form_uniqid', $_POST['form_uniqid']);
          $drIOS = $command->query();
          while($rowIOS = $drIOS->read()){
            //get all new upload images
            $sqlValues[$filekey][] = $rowIOS['id'];
          }
        }
      }
      
      //update file: [relate_id] for all iOS upload
      $relate_id = 'form_'.$form_id.'##'.$node_id;
      $connection->createCommand("update file set relate_id='". $relate_id ."' where form_uniqid='". $_POST['form_uniqid'] ."'")->execute();
    }
    
    
    if($sqlFields){
      $sql = 'update ' . strtolower($form_id) .' set  ';
      foreach($sqlFields as $field){
        $sql .= "$field=:$field,";
      }
      $sql = substr($sql, 0, -1) . " where id=". $node_id;

      $command = $connection->createCommand($sql);
      foreach($sqlFields as $field){
        $command->bindParam(':' . $field, serialize($sqlValues[$field]));
      }
      $command->execute();
    }
  }
  
  /**
   * For campus forms
   */
  public static function processFormUpload($formNode) {
    $connection = Yii::app()->db;
    $form_id = $formNode->form_id;
    $node_id = $formNode->id;
    
    //uploaded images
    $sqlFields = $sqlValuesRep = $sqlValues = array();//used to save upload image field
    if($_FILES){
      foreach($_FILES as $filekey=>$uploadedFiles){
        $sqlFields[] = $filekey;
        $sqlValuesRep[] = ':' . $filekey;
        
        //old upload images
        $oldImages = unserialize($formNode->$filekey);
        if(!is_array($oldImages)){
          $oldImages = array();
        }
        
        //removed images
        if($_POST['removed_images']){
          $removedImages = split('##', substr($_POST['removed_images'], 2));
        } else {
          $removedImages = array();
        }
        //deduce removed images
        $leftImages = array_diff($oldImages, $removedImages);
        $sqlValues[$filekey] = $leftImages;
        
        //get all new upload images
        $fileCount = count($_FILES[$filekey]['error']);
        for($i=0; $i<$fileCount; $i++){
          
          if($_FILES[$filekey]['error'][$i] > 0) continue;
          
          //save file
          $fileext = substr($_FILES[$filekey]['name'][$i], strpos($_FILES[$filekey]['name'][$i], '.'));
          $filename = uniqid(time() . '_');
          $serverName = $filename . $fileext;
          $destination = 'files/form_images/' . $serverName;
          
          if(move_uploaded_file($_FILES[$filekey]['tmp_name'][$i], $destination)){
            $file = new File();
            $file->relate_id = 'form_' . $form_id .'##' . $node_id;
            $file->name = $_FILES[$filekey]['name'][$i];
            $file->server_name = $serverName;
            $file->timestamp = time();
            $file->save();
            $sqlValues[$filekey][] = $file->id;
          }
        }
      }
    } else {//iOS
      //because iOs didn't supply $_FILES, now need to get all image fields
      $fileFields = array();
      $form = Form::model()->findByPk($formNode->form_id);
      $fields = unserialize($form->fields);
      if(is_array($fields) && $fields){
        foreach($fields as $field_id){
          $field = Field::model()->findByPk($field_id);
          if ($field->type == 'Images') {
            $fileFields[] = $field->name;
          }
        }
      }
      
      if($fileFields){
        foreach($fileFields as $field){
          $filekey = $field;
          
          $sqlFields[] = $filekey;
          $sqlValuesRep[] = ':' . $filekey;

          //old upload images
          $oldImages = unserialize($formNode->$filekey);
          if(!is_array($oldImages)){
            $oldImages = array();
          }
          //removed images
          if($_POST['removed_images']){
            $removedImages = split('##', substr($_POST['removed_images'], 2));
          } else {
            $removedImages = array();
          }
          //deduce removed images
          $leftImages = array_diff($oldImages, $removedImages);
          $sqlValues[$filekey] = $leftImages;

          
          //if upload from iOS, now try to get from file table by the $_POST['form_uniqid']
          $command = $connection->createCommand("select * from file where field=:field and form_uniqid=:form_uniqid order by id");
          $command->bindParam(':field', $field);
          $command->bindParam(':form_uniqid', $_POST['form_uniqid']);
          $drIOS = $command->query();
          while($rowIOS = $drIOS->read()){
            //get all new upload images
            $sqlValues[$filekey][] = $rowIOS['id'];
          }
        }
      }
      
      //update file: [relate_id] for all iOS upload
      $relate_id = 'form_'.$form_id.'##'.$node_id;
      $connection->createCommand("update file set relate_id='". $relate_id ."' where form_uniqid='". $_POST['form_uniqid'] ."'")->execute();
    }
    
    if($sqlFields){
      $sql = 'update form_' . $form_id .' set  ';
      foreach($sqlFields as $field){
        $sql .= "$field=:$field,";
      }
      $sql = substr($sql, 0, -1) . " where id=". $node_id;

      $command = $connection->createCommand($sql);
      foreach($sqlFields as $field){
        $command->bindParam(':' . $field, serialize($sqlValues[$field]));
      }
      $command->execute();
    }
  }
  
  
  /**
   *  Signature to Image: A supplemental script for Signature Pad that
   *  generates an image of the signature’s JSON output server-side using PHP.
   *
   *  @project ca.thomasjbradley.applications.signaturetoimage
   *  @author Thomas J Bradley <hey@thomasjbradley.ca>
   *  @link http://thomasjbradley.ca/lab/signature-to-image
   *  @link http://github.com/thomasjbradley/signature-to-image
   *  @copyright Copyright MMXI–, Thomas J Bradley
   *  @license New BSD License
   *  @version 1.1.0
   */

  /**
   *  Accepts a signature created by signature pad in Json format
   *  Converts it to an image resource
   *  The image resource can then be changed into png, jpg whatever PHP GD supports
   *
   *  To create a nicely anti-aliased graphic the signature is drawn 12 times it's original size then shrunken
   *
   *  @param string|array $json
   *  @param array $options OPTIONAL; the options for image creation
   *    imageSize => array(width, height)
   *    bgColour => array(red, green, blue) | transparent
   *    penWidth => int
   *    penColour => array(red, green, blue)
   *    drawMultiplier => int
   *
   *  @return object
   */
  public static function sigJsonToImage ($json, $options = array()) {
    $defaultOptions = array(
      'imageSize' => array(298, 80)
      ,'bgColour' => array(0xff, 0xff, 0xff)
      ,'penWidth' => 2
      ,'penColour' => array(0x14, 0x53, 0x94)
      ,'drawMultiplier'=> 12
    );

    $options = array_merge($defaultOptions, $options);

    $img = imagecreatetruecolor($options['imageSize'][0] * $options['drawMultiplier'], $options['imageSize'][1] * $options['drawMultiplier']);

    if ($options['bgColour'] == 'transparent') {
      imagesavealpha($img, true);
      $bg = imagecolorallocatealpha($img, 0, 0, 0, 127);
    } else {
      $bg = imagecolorallocate($img, $options['bgColour'][0], $options['bgColour'][1], $options['bgColour'][2]);
    }

    $pen = imagecolorallocate($img, $options['penColour'][0], $options['penColour'][1], $options['penColour'][2]);
    imagefill($img, 0, 0, $bg);

    if (is_string($json))
      $json = json_decode(stripslashes($json));

    foreach ($json as $v)
      self::drawThickLine($img, $v->lx * $options['drawMultiplier'], $v->ly * $options['drawMultiplier'], $v->mx * $options['drawMultiplier'], $v->my * $options['drawMultiplier'], $pen, $options['penWidth'] * ($options['drawMultiplier'] / 2));

    $imgDest = imagecreatetruecolor($options['imageSize'][0], $options['imageSize'][1]);

    if ($options['bgColour'] == 'transparent') {
      imagealphablending($imgDest, false);
      imagesavealpha($imgDest, true);
    }

    imagecopyresampled($imgDest, $img, 0, 0, 0, 0, $options['imageSize'][0], $options['imageSize'][0], $options['imageSize'][0] * $options['drawMultiplier'], $options['imageSize'][0] * $options['drawMultiplier']);
    imagedestroy($img);

    return $imgDest;
  }

  /**
   *  Draws a thick line
   *  Changing the thickness of a line using imagesetthickness doesn't produce as nice of result
   *
   *  @param object $img
   *  @param int $startX
   *  @param int $startY
   *  @param int $endX
   *  @param int $endY
   *  @param object $colour
   *  @param int $thickness
   *
   *  @return void
   */
  public static function drawThickLine ($img, $startX, $startY, $endX, $endY, $colour, $thickness) {
    $angle = (atan2(($startY - $endY), ($endX - $startX)));

    $dist_x = $thickness * (sin($angle));
    $dist_y = $thickness * (cos($angle));

    $p1x = ceil(($startX + $dist_x));
    $p1y = ceil(($startY + $dist_y));
    $p2x = ceil(($endX + $dist_x));
    $p2y = ceil(($endY + $dist_y));
    $p3x = ceil(($endX - $dist_x));
    $p3y = ceil(($endY - $dist_y));
    $p4x = ceil(($startX - $dist_x));
    $p4y = ceil(($startY - $dist_y));

    $array = array(0=>$p1x, $p1y, $p2x, $p2y, $p3x, $p3y, $p4x, $p4y);
    imagefilledpolygon($img, $array, (count($array)/2), $colour);
  }
  
  
  
  
  
  public static function countries($key=null) {
    $countries = array('' => 'Select One','us' => 'United States','al' => 'Albania','dz' => 'Algeria','as' => 'American Samoa','ao' => 'Angola','ai' => 'Anguilla','ag' => 'Antigua','ar' => 'Argentina','am' => 'Armenia','aw' => 'Aruba','au' => 'Australia','at' => 'Austria','az' => 'Azerbaijan','bs' => 'Bahamas','bh' => 'Bahrain','bd' => 'Bangladesh','bb' => 'Barbados','by' => 'Belarus','be' => 'Belgium','bz' => 'Belize','bj' => 'Benin','bm' => 'Bermuda','bt' => 'Bhutan','bo' => 'Bolivia','bl' => 'Bonaire','ba' => 'Bosnia Herzegovina','bw' => 'Botswana','br' => 'Brazil','vg' => 'Virgin Gorda','bn' => 'Brunei','bg' => 'Bulgaria','bf' => 'Burkina Faso','bi' => 'Burundi','kh' => 'Cambodia','cm' => 'Cameroon','ca' => 'Canada','cv' => 'Cape Verde','ky' => 'Cayman Islands','td' => 'Chad','cl' => 'Chile','cn' => 'China','co' => 'Colombia','cg' => 'Congo','ck' => 'Cook Islands','cr' => 'Costa Rica','hr' => 'Croatia','cb' => 'Curacao','cy' => 'Cyprus','cz' => 'Czech Republic','dk' => 'Denmark','dj' => 'Djibouti','dm' => 'Dominica','do' => 'Dominican Republic','ec' => 'Ecuador','eg' => 'Egypt','sv' => 'El Salvador','ee' => 'Estonia','et' => 'Ethiopia','fj' => 'Fiji','fi' => 'Finland','fr' => 'France','gf' => 'French Guiana','pf' => 'French Polynesia','ga' => 'Gabon','gm' => 'Gambia','ge' => 'Georgia','de' => 'Germany','gh' => 'Ghana','gi' => 'Gibraltar','gr' => 'Greece','gd' => 'Grenada','gp' => 'Guadeloupe','gu' => 'Guam','gt' => 'Guatemala','gn' => 'Guinea','gw' => 'Guinea Bissau','gy' => 'Guyana','ht' => 'Haiti','hn' => 'Honduras','hk' => 'Hong Kong','hu' => 'Hungary','is' => 'Iceland','in' => 'India','id' => 'Indonesia','ie' => 'Ireland (Republic of)','il' => 'Israel','it' => 'Italy','ci' => 'Ivory Coast','jm' => 'Jamaica','jp' => 'Japan','jo' => 'Jordan','kz' => 'Kazakhstan','ke' => 'Kenya','ki' => 'Kiribati','xk' => 'Kosovo','xe' => 'Kosrae Island','kw' => 'Kuwait','kg' => 'Kyrgyzstan','la' => 'Laos','lv' => 'Latvia','lb' => 'Lebanon','ls' => 'Lesotho','lt' => 'Lithuania','lu' => 'Luxembourg','mk' => 'Macedonia','mg' => 'Madagascar','mw' => 'Malawi','my' => 'Malaysia','mv' => 'Maldives','ml' => 'Mali','mt' => 'Malta','mh' => 'Marshall Islands','mq' => 'Martinique','mr' => 'Mauritania','mu' => 'Mauritius','mx' => 'Mexico','md' => 'Moldova','mn' => 'Mongolia','ms' => 'Montserrat','ma' => 'Morocco','mz' => 'Mozambique','np' => 'Nepal','nl' => 'Netherlands','nc' => 'New Caledonia','nz' => 'New Zealand','ni' => 'Nicaragua','ne' => 'Niger','ng' => 'Nigeria','mp' => 'Northern Mariana Islands','no' => 'Norway','om' => 'Oman','pk' => 'Pakistan','pw' => 'Palau','pa' => 'Panama','pg' => 'Papua New Guinea','py' => 'Paraguay','pe' => 'Peru','ph' => 'Philippines','pl' => 'Poland','xp' => 'Ponape','pt' => 'Portugal','pr' => 'Puerto Rico','qa' => 'Qatar','re' => 'Reunion','ro' => 'Romania','xc' => 'Rota','ru' => 'Russia','rw' => 'Rwanda','xs' => 'Saipan','sa' => 'Saudi Arabia','sn' => 'Senegal','cs' => 'Serbia and Montenegro','sc' => 'Seychelles','sg' => 'Singapore','sk' => 'Slovakia','si' => 'Slovenia','sb' => 'Solomon Islands','za' => 'South Africa','kr' => 'South Korea','es' => 'Spain','lk' => 'Sri Lanka','nt' => 'St. Barthelemy','vi' => 'US Virgin Islands','eu' => 'St. Eustatius','kn' => 'St. Kitts and Nevis','lc' => 'St. Lucia','mb' => 'St. Maarten','vc' => 'Union Island','sr' => 'Suriname','sz' => 'Swaziland','se' => 'Sweden','ch' => 'Switzerland','sy' => 'Syria','tj' => 'Tadjikistan','tw' => 'Taiwan','tz' => 'Tanzania','th' => 'Thailand','xn' => 'Tinian','tg' => 'Togo','to' => 'Tonga','tt' => 'Trinidad and Tobago','xa' => 'Truk','tn' => 'Tunisia','tr' => 'Turkey','tm' => 'Turkmenistan','tc' => 'Turks and Caicos','tv' => 'Tuvalu','ug' => 'Uganda','ua' => 'Ukraine','ae' => 'United Arab Emirates','gb' => 'United Kingdom','uy' => 'Uruguay','uz' => 'Uzbekistan','vu' => 'Vanuatu','ve' => 'Venezuela','vn' => 'Vietnam','wf' => 'Wallis and Futuna','ws' => 'Western Samoa','xy' => 'Yap','ye' => 'Yemen','zm' => 'Zambia','zw' => 'Zimbabwe');

    if (!is_null($key)) {
//      array_shift($countries);
      return $countries[$key];
    } else {
      return $countries;
    }
  }
  
  public static function states($key=null) {
//    $states = array('' => 'Select One','AL' => 'Alabama','AK' => 'Alaska','AZ' => 'Arizona','AR' => 'Arkansas','CA' => 'California','CO' => 'Colorado','CT' => 'Connecticut','DC' => 'Washington DC','DE' => 'Delaware','FL' => 'Florida','GA' => 'Georgia','HI' => 'Hawaii','ID' => 'Idaho','IL' => 'Illinois','IN' => 'Indiana','IA' => 'Iowa','KS' => 'Kansas','KY' => 'Kentucky','LA' => 'Louisiana','ME' => 'Maine','MD' => 'Maryland','MA' => 'Massachusetts','MI' => 'Michigan','MN' => 'Minnesota','MS' => 'Mississippi','MO' => 'Missouri','MT' => 'Montana','NE' => 'Nebraska','NV' => 'Nevada','NH' => 'New Hampshire','NJ' => 'New Jersey','NM' => 'New Mexico','NY' => 'New York','NC' => 'North Carolina','ND' => 'North Dakota','OH' => 'Ohio','OK' => 'Oklahoma','OR' => 'Oregon','PA' => 'Pennsylvania','PR' => 'Puerto Rico','RI' => 'Rhode Island','SC' => 'South Carolina','SD' => 'South Dakota','TN' => 'Tennessee','TX' => 'Texas','UT' => 'Utah','VT' => 'Vermont','VA' => 'Virginia','WA' => 'Washington','WV' => 'West Virginia','WI' => 'Wisconsin','WY' => 'Wyoming','AB' => 'Alberta','BC' => 'British Columbia','MB' => 'Manitoba','NB' => 'New Brunswick','NF' => 'Newfoundland','NS' => 'Nova Scotia','NT' => 'Northwest Territories','NU' => 'Nunavit','ON' => 'Ontario','PE' => 'Prince Edward Island','QC' => 'Quebec','SK' => 'Saskatchewan','YT' => 'Yukon');
    $states = array('' => 'Select One', 'Alabama' => 'Alabama', 'Alaska' => 'Alaska', 'Arizona' => 'Arizona', 'Arkansas' => 'Arkansas', 'California' => 'California', 'Colorado' => 'Colorado', 'Connecticut' => 'Connecticut', 'Washington DC' => 'Washington DC', 'Delaware' => 'Delaware', 'Florida' => 'Florida', 'Georgia' => 'Georgia', 'Hawaii' => 'Hawaii', 'Idaho' => 'Idaho', 'Illinois' => 'Illinois', 'Indiana' => 'Indiana', 'Iowa' => 'Iowa', 'Kansas' => 'Kansas', 'Kentucky' => 'Kentucky', 'Louisiana' => 'Louisiana', 'Maine' => 'Maine', 'Maryland' => 'Maryland', 'Massachusetts' => 'Massachusetts', 'Michigan' => 'Michigan', 'Minnesota' => 'Minnesota', 'Mississippi' => 'Mississippi', 'Missouri' => 'Missouri', 'Montana' => 'Montana', 'Nebraska' => 'Nebraska', 'Nevada' => 'Nevada', 'New Hampshire' => 'New Hampshire', 'New Jersey' => 'New Jersey', 'New Mexico' => 'New Mexico', 'New York' => 'New York', 'North Carolina' => 'North Carolina', 'North Dakota' => 'North Dakota', 'Ohio' => 'Ohio', 'Oklahoma' => 'Oklahoma', 'Oregon' => 'Oregon', 'Pennsylvania' => 'Pennsylvania', 'Puerto Rico' => 'Puerto Rico', 'Rhode Island' => 'Rhode Island', 'South Carolina' => 'South Carolina', 'South Dakota' => 'South Dakota', 'Tennessee' => 'Tennessee', 'Texas' => 'Texas', 'Utah' => 'Utah', 'Vermont' => 'Vermont', 'Virginia' => 'Virginia', 'Washington' => 'Washington', 'West Virginia' => 'West Virginia', 'Wisconsin' => 'Wisconsin', 'Wyoming' => 'Wyoming', 'Alberta' => 'Alberta', 'British Columbia' => 'British Columbia', 'Manitoba' => 'Manitoba', 'New Brunswick' => 'New Brunswick', 'Newfoundland' => 'Newfoundland', 'Nova Scotia' => 'Nova Scotia', 'Northwest Territories' => 'Northwest Territories', 'Nunavit' => 'Nunavit', 'Ontario' => 'Ontario', 'Prince Edward Island' => 'Prince Edward Island', 'Quebec' => 'Quebec', 'Saskatchewan' => 'Saskatchewan', 'Yukon' => 'Yukon');

    if (!is_null($key)) {
      array_shift($states);
      return $states[$key];
    } else {
      return $states;
    }
  }
  
  public static function downloadFile($fileName) {
    header('Content-Type: application/force-download');
    header('Content-Disposition: attachment; filename='. basename($fileName));
    readfile($fileName);
    exit;
  }
  
//  public static function hours() {
//    $hours = array(''=>'');
//    for($i=1;$i<=12;$i++){
//      if ($i<10) {
//        $hours['0' . $i] = '0' . $i;
//      } else {
//        $hours[$i] = $i;
//      }
//    }
//    
//    return $hours;
//  }
  
  public static function hours() {
    $hours = array(''=>'');
    for($i=1;$i<=12;$i++){
      $hours[$i] = $i;
    }
    
    return $hours;
  }
  
  public static function minutes() {
    $minutes = array(''=>'');
    for($i=0;$i<=59;$i++){
      if ($i<10) {
        $minutes['0' . $i] = '0' . $i;
      } else {
        $minutes[$i] = $i;
      }
    }
    
    return $minutes;
  }
  
  public static function noon() {
    return array(''=>'','AM'=>'AM', 'PM'=>'PM');
  }
  
  public static function zones() {
    return array(
        'GMT-1200' => 'GMT-1200',
        'GMT-1100' => 'GMT-1100',
        'GMT-1000' => 'GMT-1000',
        'GMT-0900' => 'GMT-0900',
        'GMT-0800' => 'GMT-0800',
        'GMT-0700' => 'GMT-0700',
        'GMT-0600' => 'GMT-0600',
        'GMT-0500' => 'GMT-0500',
        'GMT-0400' => 'GMT-0400',
        'GMT-0300' => 'GMT-0300',
        'GMT-0200' => 'GMT-0200',
        'GMT-0100' => 'GMT-0100',
        'GMT 0000' => 'GMT 0000',
        'GMT+0100' => 'GMT+0100',
        'GMT+0200' => 'GMT+0200',
        'GMT+0300' => 'GMT+0300',
        'GMT+0400' => 'GMT+0400',
        'GMT+0500' => 'GMT+0500',
        'GMT+0600' => 'GMT+0600',
        'GMT+0700' => 'GMT+0700',
        'GMT+0800' => 'GMT+0800',
        'GMT+0900' => 'GMT+0900',
        'GMT+1000' => 'GMT+1000',
        'GMT+1100' => 'GMT+1100',
        'GMT+1200' => 'GMT+1200',
    );
  }

}
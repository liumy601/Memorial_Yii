<?php

/*
 * normal
 */

class DropDown {
  public static $nonDefineDropDowns = array('state', 'state1', 'state2', 'assignedto');
  
  public static function getSelfDefineOptions($module, $name) {
    if(!in_array($name, self::$nonDefineDropDowns)){
      $name = str_replace('_', '', $name);
      $connection = Yii::app()->db;
      $command = $connection->createCommand("SELECT options FROM dropdown WHERE module=:module AND name=:name AND company_id=:company_id");
      $command->bindParam(':name', $name);
      $command->bindParam(':module', $module);
      $command->bindParam(':company_id', Yii::app()->user->company_id);
      $dr = $command->query();

      $options = array('' => '');
      while ($row = $dr->read()) {
        $rowSplit = explode("\n", $row['options']);
        if($rowSplit){
          foreach ($rowSplit as $opt) {
            $opt = trim($opt);
            $options[$opt] = $opt;
          }
        }
      }

      if (count($options) > 1) {//self define options
        return $options;
      } else {//no self define, use default
        $defaultFunction = 'default' . $module . $name;
        return self::$defaultFunction();
      }
    } else {//if State, assigned to...., we use default, this is non self define drop downs.
      if ($name == 'state1' || $name == 'state2') {
        $name = 'state';
      }
      $defaultFunction = 'default' . $name;
      return self::$defaultFunction();
    }
  }
  
  public static function defaultAssignedTo($key='id') {//key can be username or others
    $assignedTo = array('' => '');
    
    $connection = Yii::app()->db;
    $command = $connection->createCommand("SELECT * FROM users WHERE (type='staff' OR type='customer') AND company_id=:company_id ORDER BY id");
    $command->bindParam(':company_id', Yii::app()->user->company_id);
    $dr = $command->query();
    while ($row = $dr->read()) {
      $assignedTo[$row[$key]] = $row['username'];
    }
    
    return $assignedTo;
  }
  
  public static function defaultState() {
    return array(
        'Alabama' => 'Alabama',
        'Alaska' => 'Alaska',
        'Arizona' => 'Arizona',
        'Arkansas' => 'Arkansas',
        'California' => 'California',
        'Colorado' => 'Colorado',
        'Connecticut' => 'Connecticut',
        'Delaware' => 'Delaware',
        'Florida' => 'Florida',
        'Georgia' => 'Georgia',
        'Hawaii' => 'Hawaii',
        'Idaho' => 'Idaho',
        'Illinois' => 'Illinois',
        'Indiana' => 'Indiana',
        'Iowa' => 'Iowa',
        'Kansas' => 'Kansas',
        'Kentucky' => 'Kentucky',
        'Louisiana' => 'Louisiana',
        'Maine' => 'Maine',
        'Maryland' => 'Maryland',
        'Massachusetts' => 'Massachusetts',
        'Michigan' => 'Michigan',
        'Minnesota' => 'Minnesota',
        'Mississippi' => 'Mississippi',
        'Missouri' => 'Missouri',
        'Montana' => 'Montana',
        'Nebraska' => 'Nebraska',
        'Nevada' => 'Nevada',
        'New Hampshire' => 'New Hampshire',
        'New Jersey' => 'New Jersey',
        'New Mexico' => 'New Mexico',
        'New York' => 'New York',
        'North Carolina' => 'North Carolina',
        'North Dakota' => 'North Dakota',
        'Ohio' => 'Ohio',
        'Oklahoma' => 'Oklahoma',
        'Oregon' => 'Oregon',
        'Pennsylvania' => 'Pennsylvania',
        'Rhode Island' => 'Rhode Island',
        'South Carolina' => 'South Carolina',
        'South Dakota' => 'South Dakota',
        'Tennessee' => 'Tennessee',
        'Texas' => 'Texas',
        'Utah' => 'Utah',
        'Vermont' => 'Vermont',
        'Virginia' => 'Virginia',
        'Washington' => 'Washington',
        'West Virginia' => 'West Virginia',
        'Wisconsin' => 'Wisconsin',
        'Wyoming' => 'Wyoming',
    );
  }

  
  
  // <editor-fold defaultstate="collapsed" desc="action">
  public static function defaultActionDepartment() {
    return array(
        '' => '',
        'department 1' => 'department 1',
        'department 2' => 'department 2',
        'department 3' => 'department 3',
        'department 4' => 'department 4',
        'department 5' => 'department 5',
    );
  }

  public static function defaultActionType() {
    return array(
        '' => '',
        'type 1' => 'type 1',
        'type 2' => 'type 2',
        'type 3' => 'type 3',
    );
  }
  //</editor-fold>
  
  // <editor-fold defaultstate="collapsed" desc="student">
  public static function defaultStudentBuilding() {
    return array(
        '' => '',
        'building 1' => 'building 1',
        'building 2' => 'building 2',
        'building 3' => 'building 3',
        'building 4' => 'building 4',
    );
  }
  
  public static function defaultStudentType() {
    return array(
        '' => '',
        'type 1' => 'type 1',
        'type 2' => 'type 2',
        'type 3' => 'type 3',
    );
  }
  //</editor-fold>
  
  // <editor-fold defaultstate="collapsed" desc="Inventory">
  public static function defaultInventoryType() {
    return array(
        '' => '',
        'type 1' => 'type 1',
        'type 2' => 'type 2',
        'type 3' => 'type 3',
    );
  }
  
  public static function defaultInventoryLocation() {
    return array(
        '' => '',
        'location 1' => 'location 1',
        'location 2' => 'location 2',
        'location 3' => 'location 3',
    );
  }

  public static function defaultInventoryPermission() {
    return array(
        '' => '',
        'permission 1' => 'permission 1',
        'permission 2' => 'permission 2',
        'permission 3' => 'permission 3',
        'permission 4' => 'permission 4',
    );
  }

  public static function defaultInventoryCondition() {
    return array(
        '' => '',
        'condition 1' => 'condition 1',
        'condition 2' => 'condition 2',
        'condition 3' => 'condition 3',
        'condition 4' => 'condition 4',
    );
  }

  public static function defaultInventoryStatus() {
    return array(
        '' => '',
        'status 1' => 'status 1',
        'status 2' => 'status 2',
        'status 3' => 'status 3',
        'status 4' => 'status 4',
    );
  }
  //</editor-fold>

  // <editor-fold defaultstate="collapsed" desc="Ticket">
  public static function defaultParkingTicketStat() {
    return array(
        '' => '',
        'Ticket state 1' => 'Ticket state 1',
        'Ticket state 2' => 'Ticket state 2',
        'Ticket state 3' => 'Ticket state 3',
        'Ticket state 4' => 'Ticket state 4',
    );
  }

  public static function defaultParkingTicketMake() {
    return array(
        '' => '',
        'Make 1' => 'Make 1',
        'Make 2' => 'Make 2',
        'Make 3' => 'Make 3',
        'Make 4' => 'Make 4',
    );
  }

  public static function defaultParkingTicketModel() {
    return array(
        '' => '',
        'Model 1' => 'Model 1',
        'Model 2' => 'Model 2',
        'Model 3' => 'Model 3',
        'Model 4' => 'Model 4',
    );
  }
  
  public static function defaultParkingTicketLocation() {
    return array(
        '' => '',
        'location 1' => 'location 1',
        'location 2' => 'location 2',
        'location 3' => 'location 3',
        'location 4' => 'location 4',
    );
  }
  
  public static function defaultParkingTicketTicketStatus() {
    return array(
        '' => '',
        'ticket status 1' => 'ticket status 1',
        'ticket status 2' => 'ticket status 2',
        'ticket status 3' => 'ticket status 3',
        'ticket status 4' => 'ticket status 4',
    );
  }
  
  public static function defaultParkingTicketAppealStatus() {
    return array(
        '' => '',
        'Appeal status 1' => 'Appeal status 1',
        'Appeal status 2' => 'Appeal status 2',
        'Appeal status 3' => 'Appeal status 3',
        'Appeal status 4' => 'Appeal status 4',
    );
  }
  //</editor-fold>
  
  //room building
  public static function defaultRoomBuilding() {
    return array(
        '' => '',
        'Building 1' => 'Building 1',
        'Building 2' => 'Building 2',
        'Building 3' => 'Building 3',
        'Building 4' => 'Building 4',
    );
  }
  
  
  public static function getRooms() {
    $connection = Yii::app()->db;
    $command = $connection->createCommand("SELECT * FROM room WHERE company_id=:company_id");
    $command->bindParam(':company_id', Yii::app()->user->company_id);
    $dr = $command->query();

    $options = array();
    while ($row = $dr->read()) {
      $options[$row['id']] = $row['building'] . '-'.$row['floor'] . '-'.$row['number'];
    }
    return $options;
  }
  
  public static function getStudents($type='') {
    $connection = Yii::app()->db;
    if ($type == 'Student' || $type == 'Contact') {
      $typeSQL = " AND type='".$type."' ";
    }
    $command = $connection->createCommand("SELECT * FROM student WHERE company_id=:company_id" . $typeSQL);
    $command->bindParam(':company_id', Yii::app()->user->company_id);
    $dr = $command->query();

    $options = array(''=>'');
    while ($row = $dr->read()) {
      $options[$row['id']] = $row['firstname'] . ' '.$row['lastname'];
    }
    return $options;
  }
  
  
  public static function defaultContactRelationship() {
    return array(
        '' => '',
        'Spouse' => 'Spouse',
        'Child' => 'Child',
        'Parent' => 'Parent',
        'Friend' => 'Friend',
        'Minister' => 'Minister',
    );
  }
  
  public static function defaultTaskStatus() {
    return array(
        'Not Started'=>'Not Started', 
        'In Progress'=>'In Progress', 
        'Completed'=>'Completed', 
        'Pending Input'=>'Pending Input', 
        'Deferred'=>'Deferred', 'Closed'=>'Closed'
    );
  }
  
  public static function defaultCustomerSkfhFuneralHome() {
    return array(
        ''=>'', 
        'default'=>'default', 
    );
  }
  
  public static function defaultCustomerLocationFuneralService() {
    return array(
        ''=>'', 
        'default'=>'default',
        'Other'=>'Other',
    );
  }
  
  public static function defaultCustomerLocationVisitation() {
    return array(
        ''=>'', 
        'default'=>'default', 
        'Other'=>'Other', 
    );
  }
  
  public static function defaultCustomerSpecialRites() {
    return array(
        'No'=>'No', 
        'Full Military Rites at the Graveside'=>'Full Military Rites at the Graveside', 
        'Full Military Rites at the Funeral Home'=>'Full Military Rites at the Funeral Home', 
        'Mass of Christian Burial'=>'Mass of Christian Burial', 
        'Masonic Services'=>'Masonic Services', 
        'Elks Service'=>'Elks Service', 
        '_allowOther'=>'Other', 
    );
  }
  
  public static function defaultCustomerHighestLevelEducation() {
    return array(
        ''=>'', 
        '8th Grade or less'=>'8th Grade or less', 
        'Some High School or GED'=>'Some High School or GED', 
        'High School'=>'High School', 
        'Some College but no Degree'=>'Some College but no Degree', 
        'Associates Degree'=>'Associates Degree', 
        'Bachelors Degree'=>'Bachelors Degree', 
        'Masters Degree'=>'Masters Degree', 
        'Doctarate '=>'Doctarate ', 
        '_allowOther'=>'Other', 
    );
  }
  
  public static function defaultCustomerMaritalStatus() {
    return array(
        ''=>'', 
        'Never Married'=>'Never Married', 
        'Married'=>'Married', 
        'Widowed'=>'Widowed', 
        'Divorced'=>'Divorced', 
    );
  }
  
  public static function defaultCustomerNewspaperRadio1() {
    return array(
        'Olney Daily Mail ($60) _____'=>'Olney Daily Mail ($60) _____', 
        'Decatur Herald _____'=>'Decatur Herald _____', 
        'Flora/HJ _____'=>'Flora/HJ _____', 
        'Newton Press Mentor _____'=>'Newton Press Mentor _____', 
        'Olney Radio/WVLN _____'=>'Olney Radio/WVLN _____', 
        'Flora Radio _____'=>'Flora Radio _____', 
        'Fairfield Radio _____'=>'Fairfield Radio _____', 
        'Sumner Press _____'=>'Sumner Press _____', 
        'Robinson _____'=>'Robinson _____', 
        'Lawrenceville _____'=>'Lawrenceville _____', 
        'Fairfield _____'=>'Fairfield _____', 
        'Champaign News Gazette _____'=>'Champaign News Gazette _____', 
        'Bridgeport Paper _____'=>'Bridgeport Paper _____', 
        'Effingham Daily News _____'=>'Effingham Daily News _____', 
        'Evansville Courier _____'=>'Evansville Courier _____', 
        'Other'=>'Other', 
    );
  }
  
  public static function defaultCustomerNewspaperRadio2() {
    return array(
        'Olney Daily Mail ($55) _____'=>'Olney Daily Mail ($55) _____', 
        'Decatur Herald _____'=>'Decatur Herald _____', 
        'Flora/HJ _____'=>'Flora/HJ _____', 
        'Newton Press Mentor _____'=>'Newton Press Mentor _____', 
        'Olney Radio/WVLN _____'=>'Olney Radio/WVLN _____', 
        'Flora Radio _____'=>'Flora Radio _____', 
        'Fairfield Radio _____'=>'Fairfield Radio _____', 
        'Sumner Press _____'=>'Sumner Press _____', 
        'Robinson _____'=>'Robinson _____', 
        'Lawrenceville _____'=>'Lawrenceville _____', 
        'Fairfield _____'=>'Fairfield _____', 
        'Champaign News Gazette _____'=>'Champaign News Gazette _____', 
        'Bridgeport Paper _____'=>'Bridgeport Paper _____', 
        'Effingham Daily News _____'=>'Effingham Daily News _____', 
        'Evansville Courier _____'=>'Evansville Courier _____', 
        'Other'=>'Other', 
    );
  }
  
  public static function defaultCustomerNewspaperRadio3() {
    return array(
        'Olney Daily Mail ($55) _____'=>'Olney Daily Mail ($55) _____', 
        'Decatur Herald _____'=>'Decatur Herald _____', 
        'Flora/HJ _____'=>'Flora/HJ _____', 
        'Newton Press Mentor _____'=>'Newton Press Mentor _____', 
        'Olney Radio/WVLN _____'=>'Olney Radio/WVLN _____', 
        'Flora Radio _____'=>'Flora Radio _____', 
        'Fairfield Radio _____'=>'Fairfield Radio _____', 
        'Sumner Press _____'=>'Sumner Press _____', 
        'Robinson _____'=>'Robinson _____', 
        'Lawrenceville _____'=>'Lawrenceville _____', 
        'Fairfield _____'=>'Fairfield _____', 
        'Champaign News Gazette _____'=>'Champaign News Gazette _____', 
        'Bridgeport Paper _____'=>'Bridgeport Paper _____', 
        'Effingham Daily News _____'=>'Effingham Daily News _____', 
        'Evansville Courier _____'=>'Evansville Courier _____', 
        'Other'=>'Other', 
    );
  }
  
  public static function defaultCustomerNewspaperRadio4() {
    return array(
        'Olney Daily Mail ($55) _____'=>'Olney Daily Mail ($55) _____', 
        'Decatur Herald _____'=>'Decatur Herald _____', 
        'Flora/HJ _____'=>'Flora/HJ _____', 
        'Newton Press Mentor _____'=>'Newton Press Mentor _____', 
        'Olney Radio/WVLN _____'=>'Olney Radio/WVLN _____', 
        'Flora Radio _____'=>'Flora Radio _____', 
        'Fairfield Radio _____'=>'Fairfield Radio _____', 
        'Sumner Press _____'=>'Sumner Press _____', 
        'Robinson _____'=>'Robinson _____', 
        'Lawrenceville _____'=>'Lawrenceville _____', 
        'Fairfield _____'=>'Fairfield _____', 
        'Champaign News Gazette _____'=>'Champaign News Gazette _____', 
        'Bridgeport Paper _____'=>'Bridgeport Paper _____', 
        'Effingham Daily News _____'=>'Effingham Daily News _____', 
        'Evansville Courier _____'=>'Evansville Courier _____', 
        'Other'=>'Other', 
    );
  }
  
  public static function defaultCustomerNewspaperRadio5() {
    return array(
        'Olney Daily Mail ($55) _____'=>'Olney Daily Mail ($55) _____', 
        'Decatur Herald _____'=>'Decatur Herald _____', 
        'Flora/HJ _____'=>'Flora/HJ _____', 
        'Newton Press Mentor _____'=>'Newton Press Mentor _____', 
        'Olney Radio/WVLN _____'=>'Olney Radio/WVLN _____', 
        'Flora Radio _____'=>'Flora Radio _____', 
        'Fairfield Radio _____'=>'Fairfield Radio _____', 
        'Sumner Press _____'=>'Sumner Press _____', 
        'Robinson _____'=>'Robinson _____', 
        'Lawrenceville _____'=>'Lawrenceville _____', 
        'Fairfield _____'=>'Fairfield _____', 
        'Champaign News Gazette _____'=>'Champaign News Gazette _____', 
        'Bridgeport Paper _____'=>'Bridgeport Paper _____', 
        'Effingham Daily News _____'=>'Effingham Daily News _____', 
        'Evansville Courier _____'=>'Evansville Courier _____', 
        'Other'=>'Other', 
    );
  }
  
  public static function defaultCustomerNewspaperRadio6() {
    return array(
        'Olney Daily Mail ($55) _____'=>'Olney Daily Mail ($55) _____', 
        'Decatur Herald _____'=>'Decatur Herald _____', 
        'Flora/HJ _____'=>'Flora/HJ _____', 
        'Newton Press Mentor _____'=>'Newton Press Mentor _____', 
        'Olney Radio/WVLN _____'=>'Olney Radio/WVLN _____', 
        'Flora Radio _____'=>'Flora Radio _____', 
        'Fairfield Radio _____'=>'Fairfield Radio _____', 
        'Sumner Press _____'=>'Sumner Press _____', 
        'Robinson _____'=>'Robinson _____', 
        'Lawrenceville _____'=>'Lawrenceville _____', 
        'Fairfield _____'=>'Fairfield _____', 
        'Champaign News Gazette _____'=>'Champaign News Gazette _____', 
        'Bridgeport Paper _____'=>'Bridgeport Paper _____', 
        'Effingham Daily News _____'=>'Effingham Daily News _____', 
        'Evansville Courier _____'=>'Evansville Courier _____', 
        'Other'=>'Other', 
    );
  }
  
  public static function defaultCustomerSubmitPicWithObit() {
    return array(
        'Yes'=>'Yes',
        'No'=>'No',
        'Other'=>'Other',
    );
  }
  
  public static function defaultCustomerMilitaryVeteran() {
    return array(
        'No'=>'No',
        'Army'=>'Army',
        'Navy'=>'Navy',
        'Air Force'=>'Air Force',
        'Marine'=>'Marine',
        'National Guard/Coast Guard'=>'National Guard/Coast Guard',
    );
  }
  
  public static function defaultCustomerAssigned() {
    return array(
        ''=>'',
        'staff1'=>'staff1',
        'staff2'=>'staff2',
    );
  }
  
  public static function defaultCustomerStatus() {
    return array(
        'Active'=>'Active',
        'Pending'=>'Pending',
        'Completed'=>'Completed',
    );
  }
  
  public static function getNoNullValueArray($arr = array()){
    if(is_array($arr)){
      foreach($arr as $k=>$v){
        if($k==$v && $k=='')
          unset($arr[$k]);
      }
      
      return $arr;
    }
  }
}

?>

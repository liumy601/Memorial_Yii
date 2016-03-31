<?php


class HOAReportController extends Controller
{
  public function filters()
  {
      return array(
          'accessControl'
      );
  }
  
	public function accessRules()
	{
		return array(
      array('allow',
				'actions'=>array('index', 'create', 'update', 'view', 'delete', 'csvexport'),
				'roles'=>array('admin', 'staff'),
			),
			array('deny',
				'users'=>array('*'),
			),
		);
	}
  
  private function _buildShortcuts()
	{
//    $subMenu = array();
//    $subMenu[] = array('text'=>'New Letter', 'url'=>'/letter/create');
//    Yii::app()->params['subMenu'] = $subMenu;
	}
  
	public function actionIndex()
	{
    if ($_POST['hoa']) {
      $hoa = HOA::model()->findByPk($_POST['hoa']);
      $connection = Yii::app()->db;
      $command = $connection->createCommand("select h.name, p.id as propertyID, p.address1, v.enteredtm, v.resolution, v.hoa_description, v.notice, v.letter_sent
          from hoa h, property p, violation v
          where v.propertyid=p.id and p.hoaid=h.id and h.id=".$_POST['hoa'] .' order by p.address1, v.enteredtm');
      $dr = $command->query();
      
      $reportData = array();
      while ($row = $dr->read()) {
        $reportData[$row['propertyID']][] = $row;
      }
      
      $this->renderPartial('reports', array('hoa'=>$hoa, 'reportData'=>$reportData));
      return;
    }
    
    $this->render('selectHOA');
	}
  
  public function actionCSVExport($id)
  {
    $hoa = HOA::model()->findByPk($id);
    $connection = Yii::app()->db;
    $command = $connection->createCommand("select h.name, p.id as propertyID, p.address1, v.enteredtm, v.resolution, v.hoa_description, v.notice, v.letter_sent
        from hoa h, property p, violation v
        where v.propertyid=p.id and p.hoaid=h.id and h.id=". $id .' order by p.address1, v.enteredtm');
    $dr = $command->query();

    $reportData = array();
    while ($row = $dr->read()) {
      $reportData[$row['propertyID']][] = $row;
    }

    $this->renderPartial('csvexport', array('hoa'=>$hoa, 'reportData'=>$reportData));
    return;
  }
  
}


?>

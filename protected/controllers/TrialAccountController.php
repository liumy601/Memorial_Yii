<?php

class TrialAccountController extends Controller
{
	public function actionCreate()
	{
		$userName = $_REQUEST['user-name'];
		$yourName = $_REQUEST['your-name'];
		$yourCompany = $_REQUEST['your-company'];
		$yourEmail = $_REQUEST['your-email'];
		$yourPhone = $_REQUEST['your-phone'];

		$currentCompany = Company::model()->find('name="Your Funeral Home"');

		//create new company
		$newCompany = new Company();
		$newCompany->name = !empty($yourCompany) ? $yourCompany : $yourName;
		$newCompany->save(false);
		$company_id = $newCompany->id;

		//copy all records to the new company
		if(!empty($currentCompany)) {
			//copy all templates
			$templates = Template::model()->findAll('company_id='. $currentCompany->id);
			foreach($templates as $tpl) {
				$newTpl = new Template();
				foreach($tpl->metaData->columns as $col) {
					$colName = $col->name;
					if(!in_array($colName, array('id','company_id')))
						$newTpl->{$colName} = $tpl->{$colName};
				}
				$newTpl->company_id = $company_id;
				$newTpl->save(false);
			}

			//copy all decedents
			$customers = Customer::model()->findAll('company_id='. $currentCompany->id);
			foreach($customers as $cust) {
				$newCust = new Customer();
				foreach($cust->metaData->columns as $col) {
					$colName = $col->name;
					if(!in_array($colName, array('id','company_id')))
							$newCust->{$colName} = $cust->{$colName};
				}
				$newCust->company_id = $company_id;
				$newCust->save(false);
			}

			//copy all inventory
			$inventories = Inventory::model()->findAll('company_id='. $currentCompany->id);
			foreach($inventories as $invt) {
				$newInvt = new Inventory();
				foreach($invt->metaData->columns as $col) {
					$colName = $col->name;
					if(!in_array($colName, array('id','company_id')))
							$newInvt->{$colName} = $invt->{$colName};
				}
				$newInvt->company_id = $company_id;
				$newInvt->save(false);
			}

			//copy all packages
			$packages = Package::model()->findAll('company_id='. $currentCompany->id);
			foreach($packages as $pkg) {
				$newPkg = new Package();
				foreach($pkg->metaData->columns as $col) {
					$colName = $col->name;
					if(!in_array($colName, array('id','company_id')))
							$newPkg->{$colName} = $pkg->{$colName};
				}
				$newPkg->company_id = $company_id;
				$newPkg->save(false);
				//bypass the Save function in Package model
				$newPkg->saveAttributes(array('company_id'=>$company_id));

				//copy package products
				$products = PackageProduct::model()->findAll('package_id='. $pkg->id);
				foreach($products as $prod) {
					$newProd = new PackageProduct();
					$newProd->package_id = $newPkg->id;
					$newProd->inventory_id = $prod->inventory_id;
					$newProd->save(false);
				}
			}

			//copy all tasks
			$tasks = Task::model()->findAll('company_id='. $currentCompany->id);
			foreach($tasks as $task) {
				$newTask = new Task();
				foreach($task->metaData->columns as $col) {
					$colName = $col->name;
					if(!in_array($colName, array('id','company_id')))
							$newTask->{$colName} = $task->{$colName};
				}
				$newTask->company_id = $company_id;
				$newTask->save(false);
			}
		}

		//create company admin
		$admin = new Users();
		$admin->username = !empty($userName) ? $userName : $yourName;
		$admin->password = md5('changeme');
		$admin->type = 'admin';
		$admin->email = $yourEmail;
		$admin->company_id = $company_id;
		$names = explode(' ', $yourName);
		if(count($names) == 2) {
			$admin->firstname = $names[0];
			$admin->lastname = $names[1];
		}
		$admin->save(false);

		//send email
		$mail = new PHPMailer();
		$mail->IsMail(); 
		$mail->CharSet = "utf-8";
		$mail->Encoding = "base64"; 
		//$mail->SetFrom($emailConfig->from_address, $emailConfig->from_name);
		$mail->AddAddress($yourEmail);
		$mail->Subject = 'Your login details';
		$mail->Body = "login url is ". Yii::app()->params['siteURL']. '<br/>username: '. $admin->email. '<br/>password: changeme'; 
		$mail->IsHTML(true);
		$ret = $mail->Send();
	}

}
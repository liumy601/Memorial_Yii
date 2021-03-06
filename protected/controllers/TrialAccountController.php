<?php

class TrialAccountController extends Controller
{

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new TrialAccount;
		$created = false;

		if(isset($_POST['TrialAccount']))
		{
			$model->attributes=$_POST['TrialAccount'];
			$valid = $model->validate();

			if($valid) {
				$userName = $model->username;
				$yourName = $model->firstname. ' ' . $model->lastname;
				$yourCompany = $model->company_name;
				$yourEmail = $model->email;
				$yourPhone = $model->phone;

				$currentCompany = Company::model()->find('name="Your Funeral Home"');

				//create new company
				$newCompany = new Company();
				$newCompany->name = !empty($yourCompany) ? $yourCompany : $yourName;
				$newCompany->phone = $yourPhone;
				$newCompany->save(false);
				$company_id = $newCompany->id;

				//copy all records to the new company
				if(!empty($currentCompany)) {
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

						//autopopulate case_number
						$command = Yii::app()->db->createCommand("select distinct case_number_seq from customer where company_id=". $company_id ."  order by case_number_seq");
						$records = $command->queryAll();
						//search next available case_number starting from 1000
						$case_number_seq_list = array();
						if(!empty($records)) {
							foreach($records as $record) {
								$case_number_seq_list[] = $record['case_number_seq'];
							}
							$case_number_seq_list = array_unique($case_number_seq_list);
						}
						$next_case_num = 1000;
						while(in_array($next_case_num, $case_number_seq_list)) {
							$next_case_num++;
						}
						$newCust->case_number = $next_case_num;
						$newCust->case_number_seq = $next_case_num;
						$newCust->save(false);
						
						$newCustId = $newCust->id;
						
						//copy products
						$products = Product::model()->findAll('company_id='. $currentCompany->id .' and customer_id='. $cust->id);
						foreach($products as $prod) {
							$newProd = new Product();
							foreach($prod->metaData->columns as $col) {
								$colName = $col->name;
								if(!in_array($colName, array('id','customer_id','company_id')))
										$newProd->{$colName} = $prod->{$colName};
							}
							$newProd->customer_id = $newCustId;
							$newProd->company_id = $company_id;
							$newProd->save(false);
						}

						//copy payments
						$payments = Payment::model()->findAll('customer_id='. $cust->id);
						foreach($payments as $pmt) {
							$newPmt = new Payment();
							foreach($pmt->metaData->columns as $col) {
								$colName = $col->name;
								if(!in_array($colName, array('id','customer_id')))
										$newPmt->{$colName} = $pmt->{$colName};
							}
							$newPmt->customer_id = $newCustId;
							$newPmt->save(false);
						}
						
						//copy documents
						$documents = Document::model()->findAll('customer_id='. $cust->id);
						foreach($documents as $doc) {
							$newDoc = new Document();
							foreach($doc->metaData->columns as $col) {
								$colName = $col->name;
								if(!in_array($colName, array('id','customer_id')))
										$newDoc->{$colName} = $doc->{$colName};
							}
							$newDoc->customer_id = $newCustId;
							$newDoc->save(false);
						}

						//copy contacts
						$contacts = Contact::model()->findAll('company_id='. $currentCompany->id .' and customerid='. $cust->id);
						foreach($contacts as $cnt) {
							$newCnt = new Contact();
							foreach($cnt->metaData->columns as $col) {
								$colName = $col->name;
								if(!in_array($colName, array('id','customerid','company_id')))
										$newCnt->{$colName} = $cnt->{$colName};
							}
							$newCnt->customerid = $newCustId;
							$newCnt->company_id = $company_id;
							$newCnt->save(false);
						}

						//copy notes
						$notes = Notes::model()->findAll('parent_type="customer" and parent_id='. $cust->id);
						foreach($notes as $note) {
							$newNotes = new Notes();
							foreach($note->metaData->columns as $col) {
								$colName = $col->name;
								if(!in_array($colName, array('id')))
										$newNotes->{$colName} = $note->{$colName};
							}
							$newNotes->parent_id = $newCustId;
							$newNotes->save(false);
						}
					}

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
						
						//update template_id in templates
						Document::model()->updateAll(array('template_id'=>$newTpl->id), 'template_id='. $tpl->id);
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

						//update inventory_id in products
						Product::model()->updateAll(array('inventory_id'=>$newInvt->id), 'inventory_id='. $invt->id .' and company_id=' .$company_id);
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
				$admin->firstname = $model->firstname;
				$admin->lastname = $model->lastname;
				$admin->save(false);
				//assign admin role
				$command = Yii::app()->db->createCommand("insert into pre_auth_assignment set itemname='admin', userid=:userid");
				$command->bindParam(':userid', $admin->username);
				$command->execute();

				//send email
				$mail = new PHPMailer();
				$mail->IsMail(); 
				$mail->CharSet = "utf-8";
				$mail->Encoding = "base64"; 
				//$mail->SetFrom($emailConfig->from_address, $emailConfig->from_name);
				$mail->AddAddress($yourEmail);
				$mail->Subject = 'Your login details';
				$mail->Body = '<html><body>';
				$mail->Body .= 'Hi '. $admin->firstname . '<br/><br/>';
				$mail->Body .= 'Thanks for singing up!<br/><br/>';
				$mail->Body .= "We're thrilled that you've decided to give Memorial Director a try and want to let you know that you can contact us anytime by emailing ". Yii::app()->params['adminEmail'] ." or when logged in by clicking the blue button in the bottom right.<br/><br/>";
				$mail->Body .= 'Please login at http://funeral.preferati.com/ with the following credentials: <br/>';
				$mail->Body .= 'Username: '. $admin->email .'<br/>';
				$mail->Body .= 'Password: changeme<br/><br/>';
				$mail->Body .= 'The best way to get started is by viewing our guide here: http://memorialdirector.com/introduction-to-memorial-director/<br/><br/>';
				$mail->Body .= 'Have a great day, <br/>';
				$mail->Body .= 'The Memorial Director App Team<br/>';
				$mail->Body .= '<a href="http://memorialdirector.com/">http://memorialdirector.com/</a><br/>';
				$mail->Body .= '</body></html>';
				$mail->IsHTML(true);
				$mail->Send();
			}

			if($valid) {
				//empty fields and display new form
				$model=new TrialAccount;
				$created = true;
			}
		}

		$this->renderPartial('create',array(
			'model'=>$model,
			'created'=>$created,
		));
	}
}
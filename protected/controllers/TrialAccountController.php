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
			$userCreated = false;

			if($valid) {
				$yourName = $model->firstname. ' ' . $model->lastname;
				$userName = $yourName;
				$yourCompany = $model->company_name;
				$yourEmail = $model->email;
				$yourPhone = $model->phone;

				//create company admin
				$admin = new Users();
				$admin->username = $model->firstname. '_' . $model->lastname;
				$admin->password = md5('changeme');
				$admin->type = 'admin';
				$admin->email = $yourEmail;
				$admin->firstname = $model->firstname;
				$admin->lastname = $model->lastname;
				$userCreated = $admin->save(false);

				if($userCreated) {
					//assign admin role
					$command = Yii::app()->db->createCommand("insert into pre_auth_assignment set itemname='admin', userid=:userid");
					$command->bindParam(':userid', $admin->username);
					$command->execute();

					//send email
					$mail = new PHPMailer();
					//$mail->IsMail(); 
					$mail->IsSMTP();
					$mail->Port = 587;
					$mail->SMTPSecure = 'tls';
					$mail->Host = 'smtp.sendgrid.net';
					$mail->SMTPAuth = true;
					$mail->Username = 'funeralappmail'; 
					$mail->Password = 'memorial1@#';
					$mail->CharSet = "utf-8";
					$mail->Encoding = "base64"; 
					$mail->SetFrom('Success@memorialdirector.com', 'Memorial Director');
					$mail->AddAddress($yourEmail);
					$mail->AddBCC('ives.matthew@gmail.com');
					$mail->Subject = 'Your Memorial Director Trial';
					$mail->Body = '<html><body>';
					$mail->Body .= 'Hi '. $admin->firstname . '<br/><br/>';
					$mail->Body .= 'Thanks for signing up!<br/><br/>';
					$mail->Body .= "We're thrilled that you've decided to give Memorial Director a try and want to let you know that you can contact us anytime by emailing Success@memorialdirector.com or by clicking the blue button in the bottom right when logged in.<br/><br/>";
					$mail->Body .= 'Your username is: '. $admin->username .'<br/>';
					$params = array();
					$params[] = $yourName;
					$params[] = $yourCompany;
					$params[] = $yourEmail;
					$params[] = $yourPhone;
					$params[] = $admin->id;
					$token = base64_encode(json_encode($params));
					$setPwdLink = 'http://app.memorialdirector.com/site/setpwd/token/'. $token;
					$mail->Body .= 'To complete your Memorial Director Trial setup, please click <a href="'.$setPwdLink.'">here</a> to set your password and login.<br/>';
					$mail->Body .= 'After setting your password, you can login from anywhere with an internet connection by going to: <a href="http://app.memorialdirector.com">http://app.memorialdirector.com</a><br/>';
					$mail->Body .= "After you've set your password, the best way to get started is by viewing our guide here: <a href='http://memorialdirector.com/introduction-to-memorial-director/'>http://memorialdirector.com/introduction-to-memorial-director/</a><br/>";
					$mail->Body .= 'Have a great day, <br/>';
					$mail->Body .= 'The Memorial Director App Team<br/>';
					$mail->Body .= '<a href="http://memorialdirector.com/">http://memorialdirector.com/</a><br/>';
					$mail->Body .= '</body></html>';
					$mail->IsHTML(true);
					$mail->Send();
				}
			}

			if($valid && $userCreated) {
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

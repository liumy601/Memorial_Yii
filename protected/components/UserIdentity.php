<?php
/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
  private $_id;
   
  /**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate()
	{
    $user=Users::model()->find('LOWER(email)=?',array(strtolower($this->username)));
    if (!$user) {
      $this->errorCode = self::ERROR_USERNAME_INVALID;
    } else if (Users::hashPassword($this->password) != $user->password) {
      $this->errorCode=self::ERROR_PASSWORD_INVALID;
    } else {
      $this->_id=$user->username;//same to default, username
      $this->setState('uid', $user->id);
      $this->setState('email', $user->email);
      $this->setState('firstname', $user->firstname);
      $this->setState('lastname', $user->lastname);
      $this->setState('type', $user->type);
      $this->setState('department', $user->department);
      $this->setState('status', $user->status);
      $this->setState('access', $user->access);
      $this->setState('company_id', $user->company_id);
      $this->setState('appapp_uid', $user->appapp_uid);
      $this->setState('trial', $user->trial);
      $this->setState('trial_start', $user->trial_start);
      $this->setState('trial_end', $user->trial_end);
      
      $this->errorCode=self::ERROR_NONE;
      
    }

    return !$this->errorCode;
	}
  
  public function getId()
  {
      return $this->_id;
  }
}
<?php
// A class to help work with Sessions
// In our case, primarily to manage logging users in and out

// Keep in mind when working with sessions that it is generally 
// inadvisable to store DB-related objects in sessions

class Session {
	
	private $logged_in = false;
	public $id;
	
	function __construct() {
		session_start();
		$this->check_login();
	}
	
	private function check_login() {

    if(isset($_SESSION['uid'])) {
		
      $this->id = $_SESSION['uid'];
      $this->logged_in = true;

    } else {

      unset($this->id);
      $this->logged_in = false;

    }
  }

	public function is_logged_in() {
      return $this->logged_in;
  	}

	public function login($user) {
		
     $_SESSION['uid'] = $user->id;
     $this->logged_in = true;
   }
  
  public function logout() {
    unset($_SESSION['uid']);
    $this->logged_in = false;

  }

  
}

$session = new Session();

?>
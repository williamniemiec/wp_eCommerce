<?php
namespace controllers;

use \core\Controller;
use \models\User;


/**
 * Responsible for login view behavior.
 */
class LoginController extends Controller 
{
    //-----------------------------------------------------------------------
    //        Methods
    //-----------------------------------------------------------------------
    /*
      @Override
    */
	public function index()
	{
	    $error = false;
	    
	    // Checks if login form was sent
	    if (isset($_POST['email']) && !empty($_POST['email'])) {
	        $user = new User();
	        $email = addslashes($_POST['email']);
	        $pass = $_POST['pass'];
	        
	        // Checks if email and password are correct
	        if ($user->login($email, $pass)) {
	            header("Location: ".BASE_URL);
	            exit;
	        }
            
	        $error = true;
	    }
	    
	    $data = array(
	        'title' => "E-commerce - Login",
	        'error' => $error
	    );
	    
		$this->loadTemplate('login', $data);
	}

	/**
	 * Logouts current user.
	 */
	public function logout()
	{
		unset($_SESSION['userID']);
    	header('Location: '.BASE_URL);
    	exit;
	}
}
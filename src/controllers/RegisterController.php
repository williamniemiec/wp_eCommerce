<?php
namespace controllers;

use \core\Controller;
use \models\User;


/**
 * Responsible for register view behavior.
 */
class RegisterController extends Controller 
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
	    $error_msg = "";
	    $registered = false;
	    
	    // Checks if form was sent
	    if (isset($_POST['name']) && !empty($_POST['name'])) {
	        if (empty($_POST['age']) || empty($_POST['email']) || empty($_POST['pass']) || empty($_POST['phone'])) {
	            $error = true;
	            $error_msg = "Not all required fields have been filled";
	        } else {
	            $user = new User();
	            
	            if (!$user->register($_POST['name'], $_POST['email'], $_POST['pass'], $_POST['phone'])) {
	                $error = true;
	                $error_msg = "The user is already registered!";
	                $registered = true;
	            } else {
	                $_SESSION['user_successfully_registered'] = true;
	                header("Location: ".BASE_URL);
	            }
	        }
	    }
	    
		$data = array(
			'title' => 'E-commerce - Register',
		    'error' => $error,
		    'error_msg' => $error_msg,
		    'registered' => $registered
		);

		$this->loadTemplate('register', $data);
	}
}
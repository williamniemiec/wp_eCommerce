<?php
namespace controllers;

use \core\Controller;
use \models\User;


/**
 * It will be responsible for site's page not found behavior.
 */
class NotFoundController extends Controller 
{
    //-----------------------------------------------------------------------
    //        Methods
    //-----------------------------------------------------------------------
    /*
      @Override
    */
	public function index()
	{
        $user = new User();

        if (isset($_SESSION['userID']) && !empty($_SESSION['userID'])) {
            $name = $user->getName($_SESSION['userID']);
        } else {
            $name = '';
        }

        $params = array(
            'title' => 'E-commerce - Page not found',
            'name' => $name
        );

		$this->loadTemplate('404', $params);
	}
}

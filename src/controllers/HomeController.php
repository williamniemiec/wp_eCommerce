<?php
namespace controllers;

use \core\Controller;
use \models\Ad;
use \models\User;
use \models\Category;


/**
 * Main controller. It will be responsible for site's main page behavior.
 */
class HomeController extends Controller 
{
    //-----------------------------------------------------------------------
    //        Methods
    //-----------------------------------------------------------------------
    /*
      @Override
    */
	public function index ()
	{
		$ad = new Ad();
		$user = new User();
		$category = new Category();
		
		$filters = array(
		    'category' => '',
		    'price' => '',
		    'state' => ''
		);

		if(isset($_GET['filter'])){
		    $filters = $_GET['filter'];
		}

		$totFilteredProd = $ad->countAds($filters);
		$totProd = $ad->countAds();

		// Pagination
		$page = 1;
		if (isset($_GET['p']) && !empty($_GET['p'])) {
		    $page = addslashes($_GET['p']);
		}

		if (isset($_SESSION['userID']) && !empty($_SESSION['userID'])) {
			$name = $user->getName($_SESSION['userID']);
		} else {
			$name = '';
		}

		$params = array(
			'title' => 'E-commerce - Home',
			'name' => $name,
			'filters' => $filters,
			'page' => $page,
			'totProd' => $totProd,
			'totUsers' => $user->countUsers(),
			'categories' => $category->getCategories(),
			'ads' => $ad->getAds($page, 2, $filters),
			'totPages' => $ad->totPages(2, $totFilteredProd)
		);

		$this->loadTemplate("home", $params);
	}
	
	/**
	 * Checks if the user was successfully registered.
	 * 
	 * @return boolean If the user has been successfully registered
	 */
	public function wasSuccessfullyRegistered()
	{
	    if (!isset($_SESSION['user_successfully_registered']))
	        return false;
	    
        unset($_SESSION['user_successfully_registered']);
        
        return true;
	}
}

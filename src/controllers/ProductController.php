<?php
namespace controllers;

use \core\Controller;
use \models\Ad;
use \models\User;


/**
 * Responsible for product view behavior.
 */
class ProductController extends Controller 
{
    //-----------------------------------------------------------------------
    //        Methods
    //-----------------------------------------------------------------------
    /*
      @Override
    */
	public function index() { }

	/**
	 * Opens the product with the specified id.
	 * 
	 * @param int $id Id of the product
	 */
	public function open($id)
	{
		$ad = new Ad();
		$user = new User();

		if (!empty($id)) {
		    $id = addslashes($id);
		} else {
			header("Location: ".BASE_URL);
			exit;
		}

		if (isset($_SESSION['userID']) && !empty($_SESSION['userID'])) {
			$name = $user->getName($_SESSION['userID']);
		} else {
			$name = '';
		}

		$adInfo = $ad->getAd($id);
		
		$firstTime = true;

		$data = array(
		    'title' => $adInfo['title'].' - E-commerce',
		    'images' => $ad->getImages($id),
			'name' => $name,
		    'ad_title' => $adInfo['title'],
		    'ad_category' => $adInfo['category'],
		    'ad_description' => $adInfo['description'],
		    'ad_state' => $adInfo['state'] == 1 ? "New" : "Used",
		    'ad_price' => $adInfo['price'],
		    'user_phone' => $user->getPhone($adInfo['id_user']),
		    'ad_id' => $adInfo['id'],
		    'isOwner' => isset($_SESSION['userID']) && $adInfo['id_user'] == $_SESSION['userID'],
			'firstTime' => $firstTime
		);
		
		$this->loadTemplate("product", $data);
	}
}
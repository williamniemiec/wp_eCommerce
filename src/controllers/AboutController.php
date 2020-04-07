<?php
namespace controllers;

use \core\Controller;
use \models\User;


class AboutController extends Controller {
	public function index(){
		$u = new User();
		
		if(isset($_SESSION['userID']) && !empty($_SESSION['userID'])){
			$name = $u->getName($_SESSION['userID']);
		} else {
			$name = '';
		}

		$data = array(
			'title' => 'E-commerce - About',
			'name' => $name
		);
		$this->loadTemplate('about', $data);
	}
}
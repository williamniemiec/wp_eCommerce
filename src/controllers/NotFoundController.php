<?php
namespace controllers;

use \core\Controller;


/**
 * It will be responsible for site's page not found behavior.
 */
class NotFoundController extends Controller 
{
    #-----------------------------------------------------------------------
    #        Methods
    #-----------------------------------------------------------------------
	public function index()
	{
		$this->loadView('404');
	}
}

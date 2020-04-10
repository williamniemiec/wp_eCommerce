<?php
namespace controllers;

use core\Controller;
use models\Ad;
use models\User;
use models\Category;


/**
 * Responsible for my ads view behavior.
 */
class MyAdsController extends Controller
{
    //-----------------------------------------------------------------------
    //        Methods
    //-----------------------------------------------------------------------
    /*
      @Override
    */
    public function index()
    {
        if (empty($_SESSION['userID'])) {
            header("Location: " . BASE_URL);
            exit();
        }

        $ad = new Ad();
        $user = new User();

        if (isset($_SESSION['userID']) && ! empty($_SESSION['userID'])) {
            $name = $user->getName($_SESSION['userID']);
        } else {
            $name = '';
        }

        $data = array(
            'title' => 'E-commerce - My ads',
            'ads' => $ad->getMyAds(),
            'name' => $name,
            'wasAdSuccessfullyAdded' => $this->wasAdSuccessfullyAdded(),
            'wasAdSuccessfullyEdited' => $this->wasAdSuccessfullyEdited()
        );

        $this->loadTemplate('myAds', $data);
    }

    /**
     * Adds an ad.
     */
    public function add()
    {
        $ad = new Ad();
        $user = new User();

        // Form sent
        if (isset($_POST['title']) && ! empty($_POST['title']) && isset($_POST['price']) && ! empty($_POST['price'])) {
            $id_user = intval($_SESSION['userID']);
            $title = addslashes($_POST['title']);
            $description = addslashes($_POST['description']);
            $price = str_replace(',', '', $_POST['price']);
            $price = floatval($price);
            $state = intval($_POST['state']);
            $id_category = intval($_POST['category']);
            $imgs = $_FILES['img'];

            try {
                $_SESSION['ad_add_success'] = $ad->add($id_user, $title, $description, $price, $state, $id_category, $imgs);
                
                if ($_SESSION['ad_add_success'] == true) {
                    header("Location: " . BASE_URL."myAds");
                    exit();
                }
            } catch (\Exception $e) {}
        }

        $c = new Category();
        $categories = $c->getCategories();

        $data = array(
            "title" => "My ads - New ad",
            "name" => $user->getName($_SESSION['userID']),
            "categories" => $categories
        );

        $this->loadTemplate('addAd', $data);
    }

    /**
     * Deletes ad with the specified id.
     * 
     * @param int $id
     */
    public function delete($id)
    {
        $ad = new Ad();

        try {
            $ad->delete($id);
        } catch (\Exception $e) {}

        header('Location: ' . BASE_URL);
    }

    /**
     * Edits ad with the specified id.
     *
     * @param int $id_ad
     */
    public function edit($id_ad)
    {
        if (empty($id_ad) || empty($_SESSION['userID'])) {
            header('Location: ' . BASE_URL);
        }
        
        $error = false;
        $error_msg = "";
        
        // Checks it the form was sent
        if (isset($_POST['title']) && ! empty($_POST['title']) && isset($_POST['price']) && ! empty($_POST['price'])) {
            try {
                $this->saveEdition($id_ad);
                $_SESSION['ad_edit_success'] = true;
                header('Location: '.BASE_URL . "myAds");
                exit;
            } catch(\Exception $e) {
                $error = true;
                $error_msg = $e->getMessage();
            }
        }

        $ad = new Ad();
        $c = new Category();
        $categories = $c->getCategories();
        $user = new User();

        if (isset($_SESSION['userID']) && ! empty($_SESSION['userID'])) {
            $name = $user->getName($_SESSION['userID']);
        } else {
            $name = '';
        }

        $data = array(
            'title' => 'E-commerce - Edit',
            'name' => $name,
            'adInfo' => $ad->getAd($id_ad),
            'categories' => $categories,
            'id_ad' => $id_ad,
            'ad' => $ad,
            'error' => $error,
            'error_msg' => $error_msg
        );

        $this->loadTemplate('editAd', $data);
    }

    /**
	 * Checks if the add was successfully registered.
	 * 
	 * @return boolean If the ad was successfully registered
	 */
    private function wasAdSuccessfullyAdded()
    {
        if (!isset($_SESSION['ad_add_success']))
            return false;
            
        unset($_SESSION['ad_add_success']);
        
        return true;
    }
    
    /**
     * Checks if the add was successfully edited.
     *
     * @return boolean If the ad was successfully edited
     */
    private function wasAdSuccessfullyEdited()
    {
        if (!isset($_SESSION['ad_edit_success']))
            return false;
        
        unset($_SESSION['ad_edit_success']);
        
        return true;
    }
    
    /**
     * Saves edited ad.
     * 
     * @param int $id_ad Id of the edited ad
     * @throws Exception If an error occurs while saving ad
     */
    private function saveEdition($id_ad)
    {
        if (!isset($_POST['title']) || empty($_POST['title']) || !isset($_POST['price']) || empty($_POST['price'])) {
            return;
        }
        
        $ad = new Ad();
        $title = addslashes($_POST['title']);
        $description = addslashes($_POST['description']);
        $price = str_replace(',', '.', $_POST['price']);
        $price = floatval($price);
        $id_category = intval($_POST['category']);
        $state = intval($_POST['state']);
        
        if ($ad->getState($id_ad) == - 1) {
            throw new \Exception('Error getting status of the ad');
        }
        
        // Verifica se houve erro no envio de algum arquivo
        if (isset($_FILES['img']['tmp_name']) && !empty($_FILES['img']['tmp_name']) && 
            isset($_FILES['img']['name'][0]) && !empty($_FILES['img']['name'][0])) {
            foreach ($_FILES['img']['tmp_name'] as $path) {
                if (empty($path)) {
                    throw new \Exception('Error sending images - size exceeded!');
                }
            }                
        }

        $ad->savePhotos($_FILES['img'], $id_ad);
        $ad->edit($id_ad, $title, $description, $price, $id_category, $state);
    }
}
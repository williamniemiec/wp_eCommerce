<?php
namespace models;

use core\Model;


/**
 * Responsible for manipulating the database ads table (and ads_images).
 */
class Ad extends Model
{
    //-----------------------------------------------------------------------
    //        Constructor
    //-----------------------------------------------------------------------
    public function __construct()
    {
        parent::__construct();
    }

    
    //-----------------------------------------------------------------------
    //        Methods
    //-----------------------------------------------------------------------
    /**
     * Returns all ads belonging to the logged user.
     * 
     * @return array Ads of the logged user or null if the user has not registered ads
     */
    public function getMyAds()
    {
        $sql = $this->db->query("
            SELECT *,
            (select ads_images.url from ads_images where ads_images.id_ad = ads.id limit 1) as url 
            FROM ads 
            WHERE id_user = " . $_SESSION['userID']
        );

        return $sql->fetchAll();
    }

    /**
     * Returns registered ads according to the specified parameters.
     * 
     * @apiNote Used for pagination
     * @param int $page Current page
     * @param int $totAdsDisplayedPerPage Number of ads displayed in the page
     * @param array $filters Filters that the ads will be submitted
     * @return array Registered ads with the specifications or null if there are not ads
     * with the specified parameters
     */
    public function getAds($page, $totAdsDisplayedPerPage, $filters)
    {
        $response = array();
        $arrayFilters = array(
            '1=1'
        );

        // Pagination
        $offset = ($page - 1) * $totAdsDisplayedPerPage;    // -1 because pagination starts in zero, but visually in 1

        // Verifies filters
        if (! empty($filters['category'])) {
            $arrayFilters[] = "ads.id_category = :id_category";
        }

        if (!empty($filters['price'])) {
            if ($filters['price'] == '500+') {
                $arrayFilters[] = "ads.price >= :price";
            } else {
                $arrayFilters[] = "ads.price BETWEEN :price1 AND :price2";
            }
        }

        if ($filters['state'] != '') {
            $arrayFilters[] = "ads.state = :state";
        }

        // Gets ads
        $sql = $this->db->prepare("SELECT *, 
            (select ads_images.url from ads_images where ads_images.id_ad = ads.id limit 1) as url, 
            (select categories.name from categories where categories.id = ads.id_category) as category 
            FROM ads 
            WHERE " . implode(' AND ', $arrayFilters) . " 
            ORDER BY id DESC 
            LIMIT $offset, $totAdsDisplayedPerPage");

        if (!empty($filters['category'])) {
            $sql->bindValue(':id_category', $filters['category']);
        }

        if (!empty($filters['price'])) {
            if ($filters['price'] == '500+') {
                $sql->bindValue(':price', 500);
            } else {
                $tmp = explode('-', $filters['price']);
                $sql->bindValue(':price1', $tmp[0]);
                $sql->bindValue(':price2', $tmp[1]);
            }
        }

        if (($filters['state']) != '') {
            $sql->bindValue(':state', $filters['state']);
        }

        $sql->execute();

        if ($sql->rowCount() > 0) {
            $response = $sql->fetchAll();
        }

        return $response;
    }

    /**
     * Returns the total number of pages that must exist to display ads 
     * with a fixed number of ads per page.
     * 
     * @param int $totAdsDisplayedPerPage Number of ads displayed per page
     * @param int $totAds Total of ads
     * @return int Total of pages
     */
    public function totPages($totAdsDisplayedPerPage, $totAds)
    {
        return ceil($totAds / $totAdsDisplayedPerPage);
    }

    /**
     * Returns ad with the specified id.
     * 
     * @param int $id_ad Id of the ad
     * @return array Ad with the specified if or null if it not exist
     */
    public function getAd($id_ad)
    {
        $response = array();

        $sql = $this->db->prepare('SELECT *, 
            (select categories.name from categories where categories.id = ads.id_category) as category 
            FROM ads 
            WHERE id = :id_ad');
        $sql->bindValue(':id_ad', $id_ad);
        $sql->execute();

        if ($sql->rowCount() > 0) {
            $response = $sql->fetch();
        }

        return $response;
    }

    /**
     * Registers a new ad.
     * 
     * @param int $id_user Id of the user
     * @param string $title Title of the ad
     * @param string $description Description of the ad
     * @param float $price Price of the ad
     * @param int $state State of the ad
     * @param int $id_category Category of the ad
     * @param array $imgs Images of the ad
     * @return boolean If ad was successfully registered in database
     */
    public function add($id_user, $title, $description, $price, $state, $id_category, $imgs)
    {
        $sql = $this->db->prepare('INSERT INTO ads SET id_user = :userId, id_category = :id_category, title = :title, description = :description, price = :price, state = :state');
        $sql->bindValue(':userId', $id_user);
        $sql->bindValue(':id_category', $id_category);
        $sql->bindValue(':title', $title);
        $sql->bindValue(':description', $description);
        $sql->bindValue(':price', $price);
        $sql->bindValue(':state', $state);
        $sql->execute();

        $id_ad = $this->db->lastInsertId();

        $this->savePhotos($imgs, $id_ad);

        return $sql->rowCount() > 0;
    }

    // Recebe array do form imgs e retorna array com paths das imagens
    /**
     * Saves ad photos in database and in the server.
     * 
     * @param array $arrayImgs Images of the ad
     * @param int $id_ad Id of the ad
     * @return boolean If the images were successfully added
     */
    public function savePhotos($arrayImgs, $id_ad)
    {
        $totFiles = count($arrayImgs['tmp_name']);

        for ($i = 0; $i < $totFiles; $i ++) {
            // Checks if there is an invalid photo
            if ( empty($arrayImgs['tmp_name'][$i]) || 
                !($arrayImgs['type'][$i] == 'image/jpeg' || $arrayImgs['type'][$i] == 'image/png') ) {
                return false;
            }
            
            $filename = md5(time() . rand(0, 100));
            $extension = explode('/', $arrayImgs['type'][$i])[1];
            
            $filepath = 'assets/images/ads/' . $filename . '.jpg';
            move_uploaded_file($arrayImgs['tmp_name'][$i], $filepath);

            // Resize image if too large to 500x500 and save as .jpg
            imageToJpg(500, 500, $extension, $filepath);

            // Insert image into the database
            $sql = $this->db->prepare("INSERT INTO ads_images SET id_ad = :id_ad, url = :url");
            $sql->bindValue(':id_ad', $id_ad);
            $sql->bindValue(':url', $filepath);
            $sql->execute(); 
        }

        return true;
    }

    /**
     * Returns all images of an ad with the the specified id.
     * 
     * @param int $id_ad Id of the ad
     * @return array Images of the ad or null if there are not registered images
     * of this ad
     */
    public function getImages($id_ad)
    {
        $response = array();

        $sql = $this->db->query("SELECT * FROM ads_images WHERE id_ad = $id_ad");

        if ($sql->rowCount() > 0) {
            $response = $sql->fetchAll();
        }

        return $response;
    }

    /**
     * Returns title of an ad with the the specified id.
     * 
     * @param int $id_ad Id of the ad
     * @return string Title of the ad of empty string if the ad does not exist
     */
    public function getTitle($id_ad)
    {
        $title = '';

        $sql = $this->db->query("SELECT title FROM ads WHERE id = " . $id_ad);

        if ($sql->rowCount() > 0) {
            $title = $sql->fetch()['title'];
        }

        return $title;
    }

    /**
     * Returns description of an ad with the the specified id.
     * 
     * @param int $id_ad Id of the ad
     * @return string Description of the ad of empty string if the ad does not exist
     */
    public function getDescription($id_ad)
    {
        $desc = '';

        $sql = $this->db->query("SELECT description FROM ads WHERE id = " . $id_ad);

        if ($sql->rowCount() > 0) {
            $desc = $sql->fetch()['description'];
        }

        return $desc;
    }

    /**
     * Returns price of an ad with the the specified id.
     *
     * @param int $id_ad Id of the ad
     * @return float Price of the ad of 0 if the ad does not exist
     */
    public function getPrice($id_ad)
    {
        $price = 0;

        $sql = $this->db->query("SELECT price FROM ads WHERE id = " . $id_ad);

        if ($sql->rowCount() > 0) {
            $price = $sql->fetch()['price'];
        }

        return $price;
    }

    /**
     * Returns state of an ad with the the specified id.
     *
     * @param int $id_ad Id of the ad
     * @return int State of the ad of -1 if the ad does not exist
     */
    public function getState($id_ad)
    {
        $state = - 1;

        $sql = $this->db->query("SELECT state FROM ads WHERE id = " . $id_ad);
        if ($sql->rowCount() > 0) {
            $state = $sql->fetch()['state'];
        }

        return $state;
    }

    /**
     * Edits an ad.
     * 
     * @param int $id_ad Id of the ad
     * @param string $title Title of the ad
     * @param string $description Description of the ad
     * @param float $price Price of the ad
     * @param int $id_category Category of the ad
     * @param int $state State of the ad
     */
    public function edit($id_ad, $title, $description, $price, $id_category, $state)
    {
        $sql = $this->db->prepare("UPDATE ads SET title = :title, description = :description, price = :price, state = :state, id_category = :id_category WHERE id = :id_ad");

        $sql->bindValue(':title', $title);
        $sql->bindValue(':description', $description);
        $sql->bindValue(':price', $price);
        $sql->bindValue(':state', $state);
        $sql->bindValue(':id_category', $id_category);
        $sql->bindValue(':id_ad', $id_ad);
        $sql->execute();
    }

    /**
     * Delete an image with the specified id.
     * 
     * @param int $id_img Id of the image
     * @return int Id of the deleted image or 0 if it does not exist
     */
    public function deleteImg($id_img)
    {
        $id_ad = 0;

        // Pega url para remover fisicamente o arquivo
        $sql = $this->db->prepare("SELECT id_ad,url FROM ads_images WHERE id = :id_img");
        $sql->bindValue(':id_img', $id_img);
        $sql->execute();

        if ($sql->rowCount() > 0) {
            $sql = $sql->fetch();
            $url = $sql['url'];
            $id_ad = $sql['id_ad'];
            unlink($url);
        }

        // Exclui da tabela ads_images
        $sql = $this->db->prepare("DELETE FROM ads_images WHERE id = :id_img");
        $sql->bindValue(':id_img', $id_img);
        $sql->execute();

        return $id_ad;
    }

    /**
     * Deletes an ad.
     * 
     * @param int $id_ad Id of the ad
     */
    public function delete($id_ad)
    {
        $sql = $this->db->prepare("DELETE FROM ads_images WHERE id_ad = :id_ad");
        $sql->bindValue(':id_ad', $id_ad);
        $sql->execute();

        $sql = $this->db->prepare("DELETE FROM ads WHERE id = :id_ad");
        $sql->bindValue(':id_ad', $id_ad);
        $sql->execute();
    }

    /**
     * Returns id of the ad with the specified id.
     * 
     * @param int $id_ad Id of the ad
     * @return int Id of the category or -1 it the ad does not exist
     */
    public function getCategory($id_ad)
    {
        $response = -1;
        $sql = $this->db->query("SELECT id_category FROM ads WHERE id = " . $id_ad);
        $Ad = $sql->fetch();
        
        if ($Ad != null)
            $response = $Ad['id_category'];
        
        return $response;
    }

    /**
     * Returns total of registered ads with the specified filters.
     * 
     * @param array $filters Filters that will filter ads
     * @return int Number of registered ads with the specified filters
     */
    public function countAds($filters = array(
        'category' => '',
        'price' => '',
        'state' => ''
    ))
    {
        $arrayFilters = array(
            '1=1'
        );

        // Verifica filters
        if (! empty($filters['category'])) {
            $arrayFilters[] = "ads.id_category = :id_category";
        }

        if (! empty($filters['price'])) {
            if ($filters['price'] == '500+') {
                $arrayFilters[] = "ads.price >= :price";
            } else {
                $arrayFilters[] = "ads.price BETWEEN :price1 AND :price2";
            }
        }

        if ($filters['state'] != '') {
            $arrayFilters[] = "ads.state = :state";
        }

        $sql = $this->db->prepare("SELECT id FROM ads WHERE " . implode(' AND ', $arrayFilters));

        if (! empty($filters['category'])) {
            $sql->bindValue(':id_category', $filters['category']);
        }

        if (! empty($filters['price'])) {
            if ($filters['price'] == '500+') {
                $sql->bindValue(':price', 500);
            } else {
                $tmp = explode('-', $filters['price']);
                $sql->bindValue(':price1', $tmp[0]);
                $sql->bindValue(':price2', $tmp[1]);
            }
        }

        if (($filters['state']) != '') {
            $sql->bindValue(':state', $filters['state']);
        }

        $sql->execute();

        return $sql->rowCount();
    }
    
    /**
     * Converts an image to jpeg and resizes it.
     * 
     * @param int $maxWidth Max width of the image
     * @param int $maxHeight Max height of the image
     * @param string $originalExtension Extension of the source image
     * @param string $filepath Path where the image will be saved
     */
    private function imageToJpg($maxWidth, $maxHeight, $originalExtension, $filepath)
    {
        list ($width_orig, $height_orig) = getimagesize($filepath);
        $ratio = $width_orig / $height_orig;
        
        if ($maxWidth / $maxHeight > $ratio) { // Increases width
            $maxWidth = $maxHeight * $ratio;
        } else { // Increases height
            $maxHeight = $maxWidth / $ratio;
        }
        
        // Resizes image
        $img = imagecreatetruecolor($maxWidth, $maxHeight);
        if ($originalExtension == 'jpeg') {
            $original = imagecreatefromjpeg($filepath);
        } else {
            $original = imagecreatefrompng($filepath);
        }
        
        imagecopyresampled($img, $original, 0, 0, 0, 0, $maxWidth, $maxHeight, $width_orig, $height_orig);
        
        imagejpeg($img, $filepath, 80);
    }
}
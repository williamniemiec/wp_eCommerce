<?php
namespace models;

use core\Model;

function imageToJpg($maxWidth, $maxHeight, $originalExtension, $filepath)
{
    list ($width_orig, $height_orig) = getimagesize($filepath);
    $ratio = $width_orig / $height_orig;

    if ($maxWidth / $maxHeight > $ratio) { // Aumenta largura
        $maxWidth = $maxHeight * $ratio;
    } else { // Aumenta altura
        $maxHeight = $maxWidth / $ratio;
    }

    // Redimenciona imagem
    $img = imagecreatetruecolor($maxWidth, $maxHeight);
    if ($originalExtension == 'jpeg') {
        $original = imagecreatefromjpeg($filepath);
    } else {
        $original = imagecreatefrompng($filepath);
    }

    imagecopyresampled($img, $original, 0, 0, 0, 0, $maxWidth, $maxHeight, $width_orig, $height_orig);

    imagejpeg($img, $filepath, 80);
}

class Ad extends Model
{

    public function getMyAds()
    {
        $sql = $this->db->query("
            SELECT *,
            (select ads_images.url from ads_images where ads_images.id_ad = ads.id limit 1) as url 
            FROM ads 
            WHERE id_user = " . $_SESSION['userID']);

        return $sql->fetchAll();
    }

    public function getAds($page, $totAdsDisplayedPerPage, $filters)
    {
        $response = array();
        $arrayFilters = array(
            '1=1'
        );

        // Paginação
        // -1 pq paginação começa em zero, mas visualmente em 1
        $offset = ($page - 1) * $totAdsDisplayedPerPage;

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

        $sql = $this->db->prepare("SELECT *, 
            (select ads_images.url from ads_images where ads_images.id_ad = ads.id limit 1) as url, 
            (select categories.name from categories where categories.id = ads.id_category) as category 
            FROM ads 
            WHERE " . implode(' AND ', $arrayFilters) . " 
            ORDER BY id DESC 
            LIMIT $offset, $totAdsDisplayedPerPage");

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

        if ($sql->rowCount() > 0) {
            $response = $sql->fetchAll();
        }

        return $response;
    }

    public function totPages($totAdsDisplayedPerPage, $totAds)
    {
        return ceil($totAds / $totAdsDisplayedPerPage);
    }

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
    public function savePhotos($arrayImgs, $id_ad)
    {
        $totFiles = count($arrayImgs['tmp_name']);
        $imgs = array();

        for ($i = 0; $i < $totFiles; $i ++) {
            if (! empty($arrayImgs['tmp_name'][$i]) && ($arrayImgs['type'][$i] == 'image/jpeg' || $arrayImgs['type'][$i] == 'image/png')) {
                $filename = md5(time() . rand(0, 100));
                $extension = explode('/', $arrayImgs['type'][$i])[1];
                // $filepath = 'assets/images/ads/'.$filename.'.'.$extension;
                $filepath = 'assets/images/ads/' . $filename . '.jpg';
                move_uploaded_file($arrayImgs['tmp_name'][$i], $filepath);
                array_push($imgs, $filepath); // $imgs = $filepath

                // Redimenciona imagem se for muito grande para 500x500 e salva como .jpg
                imageToJpg(500, 500, $extension, $filepath);

                // Insere imagem no banco de dados
                $sql = $this->db->prepare("INSERT INTO ads_images SET id_ad = :id_ad, url = :url");
                $sql->bindValue(':id_ad', $id_ad);
                $sql->bindValue(':url', $filepath);
                $sql->execute();
            } else {
                return null;
            }
        }

        return $imgs;
    }

    public function getImages($id_ad)
    {
        $response = array();

        $sql = $this->db->query("SELECT * FROM ads_images WHERE id_ad = $id_ad");

        if ($sql->rowCount() > 0) {
            $response = $sql->fetchAll();
        }

        return $response;
    }

    public function getTitle($id_ad)
    {
        $title = '';

        $sql = $this->db->query("SELECT title FROM ads WHERE id = " . $id_ad);

        if ($sql->rowCount() > 0) {
            $title = $sql->fetch()['title'];
        }

        return $title;
    }

    public function getDescription($id_ad)
    {
        $desc = '';

        $sql = $this->db->query("SELECT description FROM ads WHERE id = " . $id_ad);

        if ($sql->rowCount() > 0) {
            $desc = $sql->fetch()['description'];
        }

        return $desc;
    }

    public function getPrice($id_ad)
    {
        $price = 0;

        $sql = $this->db->query("SELECT price FROM ads WHERE id = " . $id_ad);

        if ($sql->rowCount() > 0) {
            $price = $sql->fetch()['price'];
        }

        return $price;
    }

    public function getState($id_ad)
    {
        $state = - 1;

        $sql = $this->db->query("SELECT state FROM ads WHERE id = " . $id_ad);
        if ($sql->rowCount() > 0) {
            $state = $sql->fetch()['state'];
        }

        return $state;
    }

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

    public function delete($id_ad)
    {
        $sql = $this->db->prepare("DELETE FROM ads_images WHERE id_ad = :id_ad");
        $sql->bindValue(':id_ad', $id_ad);
        $sql->execute();

        $sql = $this->db->prepare("DELETE FROM ads WHERE id = :id_ad");
        $sql->bindValue(':id_ad', $id_ad);
        $sql->execute();
    }

    public function getCategory($id_ad)
    {
        $sql = $this->db->query("SELECT id_category FROM ads WHERE id = " . $id_ad);
        $Ad = $sql->fetch();

        return $Ad['id_category'];
    }

    
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
}
?>
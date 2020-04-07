<div class='container'>
    <h1>Ad - Edit</h1>
    <?php if ($error): ?>
     	<div class='alert alert-danger'>
        	<h3>Error!</h3>
        	<p><?php echo $error_msg; ?></p>
        </div>
    <?php endif; ?>
    <form id='ad_form' method='POST' enctype='multipart/form-data'>
        <!-- Ad's title -->
        <div class='form-group'>
            <label for='title'>Title</label>
            <input id='title' type='text' name='title' class='form-control' value="<?php echo $adInfo['title'] ?>" />
        </div>

		<!-- Ad's description -->
        <div class='form-group'>
            <label for='description'>Description</label>
            <textarea id='description' name='description' class='form-control'><?php echo $adInfo['description'] ?></textarea>
        </div>

		<!-- Ad's category -->
        <div class='form-group'>
            <label for='category'>Category</label>
            <select name='category' id='category' class='custom-select'>
                <?php foreach ($categories as $category): ?>
                	<?php if ($category['id'] == $ad->getCategory($id_ad)): ?>
                        <option value="<?php echo $category['id']; ?>" selected='selected'> <?php echo $category['name']; ?> </option>
                	<?php else: ?>
                        <option value="<?php echo $category['id']; ?>"> <?php echo $category['name']; ?> </option>
                	<?php endif; ?>
                <?php endforeach; ?>
            </select>
        </div>

		<!-- Ad's price -->
        <div class='form-group'>
            <label for='price'>Price</label>
            <div class='input-group'>
                <div class='input-group-prepend'>
                    <span class='input-group-text'>$</span>
                </div>
                <input id='price' type='text' name='price' class='form-control' value="<?php echo $adInfo['price'] ?>" />
            </div>
        </div>

		<!-- Ad's state -->
        <div class='form-group'>
            <label for='state'>State</label>
            <select name='state' id='state' class='custom-select'>
                <?php if($adInfo['state'] == '1'): ?>
                    <option value='1' selected='selected'>New</option>
                    <option value='0'>Used</option>
                <?php else: ?>
                    <option value='1'>New</option>
                    <option value='0' selected='selected'>Used</option>
                <?php endif; ?>
            </select>
        </div>

		<!-- Ad's images -->
        <div class='images'>
            <label>Images</label><br />
            <a href='' class='btn btn-primary' data-toggle='modal' data-target='#addImg'>Add photos</a>
            <hr />
            <div class='card '>
                <div class='card-header bg-dark text-light'>
                    <a href='' class='btn btn-link text-light' data-toggle='collapse' data-target='#gallery_body'>Gallery</a>
                </div>
                <div id='gallery_body' class='collapse show'>
                    <div class='card-body d-flex flex-wrap justify-content-around'>
                        <?php foreach ($ad->getImages($id_ad) as $img): ?>
                            <div class='gallery img-thumbnail d-flex flex-column justify-content-between align-items-center'>
                                <img src=<?php echo BASE_URL.$img['url']; ?> />
                                <a href="img_delete.php?id=<?php echo $img['id']; ?>" class='btn btn-danger btn-block'>Delete</a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div id='addImg' class='modal fade'>
                <div class='modal-dialog modal-centered'>
                    <div class='modal-content'>
                        <div class='modal-header'>
                            <h3 class='modal-title'>Add photos</h3>
                            <button class='close' data-dismiss='modal' aria-label='Close'>&times;</button>
                        </div>
                        <div class='modal-body'>
                            <input name='img[]' type='file' accept='.jpg, .png' data-max-size='2000000' class='file upload-file' multiple data-show-upload='true' data-show-caption='true' />
                            <br />
                            <p class='text-center'>Size: <span class='upload-file-size'>0 MB</span> / 2 MB</p>
                        </div>
                        <div class='modal-footer'>
                            <button class='btn btn-danger' data-dismiss='modal' aria-label='Send'>Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <br/>
        
        <!-- Form submission button -->
        <div class='form-group'>
            <input type='submit' value='Save' class='btn btn-outline-primary' />
        </div>
    </form>
</div>
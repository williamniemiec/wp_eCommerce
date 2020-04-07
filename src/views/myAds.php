<div class='container'>
    <h1>My ads</h1>
    
    <?php if ($wasAdSuccessfullyAdded) { ?>
    	<div class='alert alert-success fade show' role='alert'>
        	Ad successfully registered!
        	<button class='close' data-dismiss='alert' aria-label='Close'>
        		<span aria-hidden='true'>&times;</span>
        	</button>
        </div>
   <?php } else if ($wasAdSuccessfullyEdited) { ?>
   		<div class='alert alert-success fade show' role='alert'>
        	Ad successfully edited!
        	<button class='close' data-dismiss='alert' aria-label='Close'>
        		<span aria-hidden='true'>&times;</span>
        	</button>
        </div>
   <?php } ?>     
	
	<!-- Add ad button -->
    <a href='<?php echo BASE_URL;?>myAds/add' class='btn btn-outline-primary'>Add ad</a>
    
    <!-- My ads -->
    <table class='table table-striped'>
        <thead>
            <tr>
                <th>Photo</th>
                <th>Title</th>
                <th>Price</th>
                <th>Actions</th>
            </tr>
        </thead>
        
        <tbody>
        <?php foreach($ads as $ad): ?>
            <tr class='ad-row'>
                <td>
                	<?php $ad['url'] = empty($ad['url']) ? '<?php BASE_URL; ?>assets/images/noImage.png' : $ad['url']; ?>
                    <img src="<?php echo $ad['url']; ?>" border='0' class='ad-img' />
                </td>
                <td><?php echo $ad['title']; ?></td>
                <td>$ <?php echo number_format($ad['price'], 2); ?></td>
                <td>
                    <a href="<?php echo BASE_URL.'myAds/edit/'.$ad['id']; ?>" class='btn btn-outline-warning'>Edit</a>
                    <a href="<?php echo BASE_URL.'myAds/delete/'.$ad['id']; ?>" class='btn btn-outline-danger'>Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
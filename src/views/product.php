<div class='container-fluid'>
    <div class=row>
    	<!-- Ad photo gallery -->
        <div class='col-5'>
            <div id='slideshow' class='slide carousel' data-ride='carousel'>
                <div class='carousel-inner' role='listbox'>
                    <?php foreach($images as $key => $url): ?>
                        <div class='carousel-item <?php echo ($key == '0')?('active'):('') ?>'>
                            <img src="<?php echo BASE_URL.$url['url']; ?>" class='d-block img-responsive' />
                            <?php $firstTime = false; ?>
                        </div>
                    <?php endforeach; ?>
                </div>

                <a class='carousel-control-prev' href='#slideshow' data-slide='prev'>
                    <span class='carousel-control-prev-icon'></span>
                </a>
                <a class='carousel-control-next' href='#slideshow' data-slide='next'>
                    <span class='carousel-control-next-icon'></span>
                </a>
                
            </div>
        </div>
        
        <!-- Ad info -->
        <div class='col'>
            <h1><?php echo $ad_title; ?></h1>
            <h4><?php echo $ad_category; ?></h4>
            <p><?php echo $ad_description; ?></p>
            <p><?php echo $ad_state; ?></p>
            <hr />
            <h3>$ <?php echo $ad_price; ?></h3>
            <h4>Phone: <?php echo $user_phone; ?></h4>
            <hr />
            <?php if ($isOwner): ?>
            	<a class='btn btn-outline-primary' href='<?php echo BASE_URL."myAds/edit/".$ad_id; ?>'>Edit</a>
            <?php endif; ?>
        </div>
    </div>
</div>

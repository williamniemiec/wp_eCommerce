<div class='container-fluid'>
	<?php if ($this->wasSuccessfullyRegistered()): ?>
		<div class='alert alert-success'>
           <h3>Success!</h3>
           <p>User has been successfully registered! <a href='<?php echo BASE_URL; ?>login'>Login</a></p>
        </div>
	<?php endif; ?>

	<!-- Header -->
    <div class='jumbotron'>
        <h3>Today we have <?php echo $totProd; ?> <?php echo ($totProd == 1)?('product'):('products') ?></h3>
        <p>And <?php echo $totUsers; ?> <?php echo ($totUsers == 1)?('registered user'):('registered users') ?>.</p>
    </div>

	<!-- Content -->
    <div class=row>
    	<!-- Products search -->
        <div class='col-3'>
            <h5>Advanced Search</h5>
            <form method='GET'>
            	<!-- Category filter -->
                <div class='form-group'>
                    <label for='category'>Category</label>
                    <select id='category' name='filter[category]' class='form-control'>
                        <option></option>
                        <?php foreach($categories as $category): ?>
                        <option value='<?php echo $category["id"] ?>'  <?php echo ($filters['category'] == $category['id'])?'selected=selected':''; ?> >   <?php echo $category['name'] ?>    </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <!-- Price filter -->
                <div class='form-group'>
                    <label for='price'>Price</label>
                    <select id='price' name='filter[price]' class='form-control'>
                        <option></option>
                        <option value='0-50'    <?php echo ($filters['price'] == '0-50')?'selected=selected':'' ?> >	$ 0 - 50       </option>
                        <option value='51-100'  <?php echo ($filters['price'] == '51-100')?'selected=selected':'' ?> >	$ 51 - 100     </option>
                        <option value='101-200' <?php echo ($filters['price'] == '101-200')?'selected=selected':'' ?> >	$ 101 - 200    </option>
                        <option value='201-500' <?php echo ($filters['price'] == '201-500')?'selected=selected':'' ?> >	$ 201 - 500    </option>
                        <option value='500+'    <?php echo ($filters['price'] == '500+')?'selected=selected':'' ?> >	&gt; $ 500     </option>
                    </select>
                </div>
                
                <!-- State filter -->
                <div class='form-group'>
                    <label for='state'>State</label>
                    <select id='state' name='filter[state]' class='form-control'>
                        <option></option>
                        <option value=1 <?php echo ($filters['state'] == '1')?'selected=selected':'' ?> >    New    </option>
                        <option value=0 <?php echo ($filters['state'] == '0')?'selected=selected':'' ?> >    Used   </option>
                    </select>
                </div>
                
                <!-- Form search button -->
                <div class='form-group'>
                    <input type='submit' value='Search' class='btn btn-outline-primary' />
                </div>
            </form>
        </div>
        
        <!-- Last ads -->
        <div class='col'>
            <h5>Last ads</h5>
       		
            <!-- Ads -->
            <table class='table table-striped'>
                <tbody>
                    <?php foreach($ads as $ad): ?>
                        <tr class=''>
                            <td class=''>
                                <?php $ad['url'] = empty($ad['url']) ? '<?php BASE_URL; ?>assets/images/noImage.png' : $ad['url']; ?>
                                <img src="<?php BASE_URL; ?><?php echo $ad['url']; ?>" border='0' class='last-ad-img' />
                            </td>
                            <td class=''>
                                <a href="<?php BASE_URL; ?>product/open/<?php echo $ad['id']; ?>"><?php echo $ad['title']; ?></a><br />
                                <?php echo $ad['category'] ?>
                            </td>
                            <td>
                            	$ <?php echo number_format($ad['price'], 2); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <!-- Pagination -->
            <form method='GET'>
                <ul class='pagination justify-content-center'>
                    <!-- Previous button -->
                    <li class='page-item <?php echo ($page == 1)?('disabled'):('') ?>'>
                        <?php $_GET["p"] = $page-1; ?>
                        <a class='page-link' href='<?php echo BASE_URL."?".http_build_query($_GET); ?>'>Previous</a>
                    </li>
                    
                    <!-- Pages button -->
                    <?php for($i=1; $i <= $totPages; $i++): ?>
                        <li class='page-item <?php echo ($page == $i)?('active'):('')?>'>
                            <?php $_GET["p"] = $i; ?>
                            <a class='page-link' href='<?php echo BASE_URL."?".http_build_query($_GET); ?>'><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>
                    
                    <!-- Next button -->
                    <li class='page-item <?php echo ($page >= $totPages)?('disabled'):('') ?>'>
                        <?php $_GET["p"] = $page+1; ?>
                        <a class='page-link' href='<?php echo BASE_URL."?".http_build_query($_GET); ?>'>Next</a>
                    </li>
                </ul>
            </form>
        </div>
    </div>
</div>
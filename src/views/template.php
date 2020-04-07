<!doctype html>
<html>
    <head>
        <title><?php echo $title; ?></title>
        <meta name='viewport' content='width=device-width, initial-scale=1, shrink-to-fit=no' />
        <link rel='stylesheet' href='<?php echo BASE_URL; ?>assets/css/bootstrap.min.css' />
        <link rel="stylesheet" href='<?php echo BASE_URL; ?>assets/css/font-awesome-4.7.0/css/font-awesome.min.css' />
        <link rel='stylesheet' href='<?php echo BASE_URL; ?>assets/css/style.css' />
        <link rel='stylesheet' href='<?php echo BASE_URL; ?>assets/css/ps_style.css' />
    </head>

    <body>
    	<!-- Top menu -->
        <nav class='navbar navbar-expand-lg bg-dark navbar-dark navbar-fixed-top'>
            <div class='container-fluid'>
                <!-- Top menu - title -->
                <a class='navbar-brand' href='<?php echo BASE_URL; ?>'>E-commerce</a>

                <!-- Top menu - compact version -->
                <button class='navbar-toggler' data-toggle='collapse' data-target='#menu'>
                    <span class='navbar-toggler-icon'></span>
                </button>

                <!-- Top menu - itens -->
                <div id='menu' class='navbar-collapse collapse'>
                    <ul class='navbar-nav'>
                        <li class='nav-item'><a href='<?php echo BASE_URL; ?>home' class='nav-link'>Home</a></li>
                        <li class='nav-item'><a href='<?php echo BASE_URL; ?>about' class='nav-link'>About</a></li>
                    </ul>
                </div>

                <!-- Top menu - actions -->
                <div class='nav navbar-nav navbar-right'>
                    <?php if(isset($_SESSION['userID']) && !empty($_SESSION['userID'])): ?>
                        <li class='navbar-text text-info' style='margin-right:20px;'>
                            <?php echo $name; ?>
                        </li>
                        <li class='nav-item'><a href='<?php echo BASE_URL; ?>myAds' class='nav-link'>My ads</a></li>
                        <li class='nav-item'><a href='<?php echo BASE_URL; ?>login/logout' class='nav-link'>Logout</a></li>
                    <?php else: ?>
                        <li class='nav-item'><a href='<?php echo BASE_URL; ?>register' class='nav-link'>Register</a></li>
                        <li class='nav-item'><a href='<?php echo BASE_URL; ?>login' class='nav-link'>Login</a></li>
                    <?php endif ?>
                </div>
            </div>
        </nav>
        
        <!-- Scripts -->
        <script src='<?php echo BASE_URL; ?>assets/js/jquery-3.4.1.min.js'></script>
        <script src='<?php echo BASE_URL; ?>assets/js/bootstrap.bundle.min.js'></script>
        <script src='<?php echo BASE_URL; ?>assets/js/jquery.mask.js'></script>
        <script src='<?php echo BASE_URL; ?>assets/js/ps_script.js'></script>
        <script src='<?php echo BASE_URL; ?>assets/js/script.js'></script>

        <?php $this->loadView($viewName, $viewData); ?>
       	
       	<footer>
    		Copyright &copy; - All rights reserved
    	</footer>
    </body>
</html>
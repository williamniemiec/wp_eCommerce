<div class='container'>
    <h1>Register</h1>
    
    <?php if ($error): ?>
		<div class='alert alert-danger'>
    		<h3>Error!</h3>
    		<p><?php echo $error_msg; ?><?php echo $registered? "<a href='<?php echo BASE_URL; ?>login'>" : ""; ?></p>
        </div>
    <?php endif; ?>
    
    <form method='POST'>
    	<!-- Name and age fields -->
        <div class='row'>
            <!-- Name field -->
            <div class='col'>
                <div class='form-group'>
                    <label for='name'>Name: </label>
                    <input id='name' type='text' name='name' class='form-control' pattern='[A-Za-z\s]{4,}' required />
                </div>
            </div>
	
			<!-- Age field -->
            <div class='col-2'>
                <div class='form-group'>
                    <label for='age'>Age: </label>
                    <input id='age' type='number' name='age' class='form-control' required />
                </div>    
            </div>
        </div>
		
		<!-- Phone field -->
        <div class='form-group'>
            <label for='phone'>Phone</label>
            <input id='phone' type='phone' name='phone' class='form-control' required />
        </div>

		<!-- Email field -->
        <div class='form-group'>
            <label for='email'>Email: </label>
            <input id='email' type='text' name='email' class='form-control' pattern='^[A-z0-9\_\-]{3,}\@[A-Za-z0-9]{2,}\.[A-Za-z0-9]{2,}(\.[A-Za-z0-9]{2,})?$' required />
        </div>

		<!-- Password field -->
        <div class='form-group'>
            <label for='password'>Passowrd: </label>
            <input  type='password' name='pass' class='form-control pass_input' required autocomplete='on' />
            <div id='pass_strength_box'>
                <h5 class='pass_strength_header'>Password strength</h5>
                <div class='progress'>
                    <div class='pass_strength_bar'></div>
                </div>
                <ul class='pass_strength'>
                    <li id='pass_length' data-length='8'>
                        Password length (at least 8 characters)
                        <span class='pass_strength_icon'></span>
                    </li>
                    <li id='pass_numCharact'>
                        Numbers and characters
                        <span class='pass_strength_icon'></span>
                    </li>
                    <li id='pass_specCharact'>
                        Special characters
                        <span class='pass_strength_icon'></span>
                    </li>
                    <li id='pass_ulCharact'>
                        Uppercase and lowercase letters
                        <span class='pass_strength_icon'></span>
                    </li>
                </ul>
            </div>
        </div>

		<!-- Form submission button -->
        <div class='form-group'>
            <input type='submit' value='Register' class='btn btn-outline-primary submit' />
        </div>
    </form>
</div>

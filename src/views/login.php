<div class='container'>
    <h1>Login</h1>
    
    <?php if ($error): ?>
    	<div class='alert alert-danger'>
            <h3>Error!</h3>
            <p>Incorrect user and / or pass</p>
        </div>
    <?php endif; ?>
    
    <form method='POST'>
    	<!-- Email field -->
        <div class='form-group'>
            <label for='email'>Email: </label>
            <input id='email' type='text' name='email' class='form-control' />
        </div>

		<!-- Password field -->
        <div class='form-group'>
            <label for='password'>Password: </label>
            <input id='password' type='password' name='pass' class='form-control' />
        </div>
        
        <!-- Form submission button -->
        <div class='form-group'>
            <input type='submit' value='Login' class='btn btn-outline-primary' />
        </div>
    </form>
</div>
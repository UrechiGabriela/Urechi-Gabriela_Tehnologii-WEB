<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles/authentification.css">
    </head>
<body>

<div class="brand">Momento</div>
<div class="container">
    <h2>Login</h2>
    
    <?php if (!empty($mesaj)): ?>
        <div class="mesaj"><?php echo $mesaj; ?></div>
    <?php endif; ?>

    <form method="POST" action="login.php">
        <div class="form-group">
            <label for="email">Email Address:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        
        <div class="checkbox-group">
            <input type="checkbox" id="remember_me" name="remember_me">
            <label for="remember_me" class="clean-style-1">Remember me (30 days)</label>
        </div>

        <button type="submit">Log In</button>
    </form>
    
    <div class="register-link">
        Don't have an account? <a href="register.php">Sign up here</a>
    </div>
</div>

</body>
</html>
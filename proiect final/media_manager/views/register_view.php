<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="styles/authentification.css">
</head>
<body>

<div class="brand">Momento</div>
<div class="container">
    <h2>Create Account</h2>
    
    <?php if (!empty($mesaj)): ?>
        <div class="mesaj <?php echo $mesaj_tip; ?>"><?php echo $mesaj; ?></div>
    <?php endif; ?>

    <form method="POST" action="register.php" novalidate>
        <div class="form-group">
            <label for="email">Email Address:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div class="form-group">
            <label for="confirm_password">Confirm Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
        </div>
        <button type="submit">Sign Up</button>
    </form>
    
    <div class="login-link">
        Already have an account? <a href="login.php">Log in here</a>
    </div>
</div>

</body>
</html>
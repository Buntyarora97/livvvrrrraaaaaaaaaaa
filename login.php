<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/models/User.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    $user = User::authenticate($email, $password);
    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        header('Location: index.php');
        exit;
    } else {
        $error = 'Invalid email or password';
    }
}

$pageTitle = 'Login';
require_once __DIR__ . '/includes/header.php';
?>
<section class="section">
    <div class="container">
        <div style="max-width: 400px; margin: 0 auto;">
            <h2 class="section-title">Login</h2>
            <?php if ($error): ?><div style="color: red;"><?php echo $error; ?></div><?php endif; ?>
            <form method="POST">
                <div style="margin-bottom: 15px;">
                    <label>Email</label>
                    <input type="email" name="email" required style="width: 100%; padding: 8px;">
                </div>
                <div style="margin-bottom: 15px;">
                    <label>Password</label>
                    <input type="password" name="password" required style="width: 100%; padding: 8px;">
                </div>
                <button type="submit" class="shop-now-btn">Login</button>
            </form>
            <p>Don't have an account? <a href="register.php">Register here</a></p>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
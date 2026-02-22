<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/models/User.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = [
        'name' => $_POST['name'] ?? '',
        'email' => $_POST['email'] ?? '',
        'password' => $_POST['password'] ?? '',
        'phone' => $_POST['phone'] ?? ''
    ];
    
    try {
        if (User::create($data)) {
            header('Location: login.php?registered=1');
            exit;
        }
    } catch (Exception $e) {
        $error = 'Registration failed. Email might already be in use.';
    }
}

$pageTitle = 'Register';
require_once __DIR__ . '/includes/header.php';
?>
<section class="section">
    <div class="container">
        <div style="max-width: 400px; margin: 0 auto;">
            <h2 class="section-title">Register</h2>
            <?php if ($error): ?><div style="color: red;"><?php echo $error; ?></div><?php endif; ?>
            <form method="POST">
                <div style="margin-bottom: 15px;">
                    <label>Full Name</label>
                    <input type="text" name="name" required style="width: 100%; padding: 8px;">
                </div>
                <div style="margin-bottom: 15px;">
                    <label>Email</label>
                    <input type="email" name="email" required style="width: 100%; padding: 8px;">
                </div>
                <div style="margin-bottom: 15px;">
                    <label>Phone</label>
                    <input type="text" name="phone" required style="width: 100%; padding: 8px;">
                </div>
                <div style="margin-bottom: 15px;">
                    <label>Password</label>
                    <input type="password" name="password" required style="width: 100%; padding: 8px;">
                </div>
                <button type="submit" class="shop-now-btn">Register</button>
            </form>
            <p>Already have an account? <a href="login.php">Login here</a></p>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
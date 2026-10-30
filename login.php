<?php
require_once 'config.php';
if (!empty($_SESSION['user_id'])) { header('Location: dashboard.php'); exit; }
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $email = trim($_POST['email'] ?? ''); $pass = $_POST['password'] ?? '';
    $st = db_prepare($conn, 'SELECT id,name,password FROM users WHERE email=?');
    $st->bind_param('s', $email); $st->execute();
    $r = $st->get_result()->fetch_assoc();
    if ($r && password_verify($pass, $r['password'])) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = (int)$r['id']; $_SESSION['user_name'] = $r['name'];
        header('Location: dashboard.php'); exit;
    }
    $error = 'Invalid email or password.';
}
$page_title = 'Log in'; include 'includes/header.php'; ?>
<div class="auth">
  <div class="card form-card">
    <h2>Welcome back</h2><p class="muted">Log in to your meeting dashboard.</p>
    <?php if ($error): ?><div class="alert err"><?= h($error) ?></div><?php endif; ?>
    <form method="post">
      <?= csrf_field() ?>
      <div class="form-group"><label>Email</label><input type="email" name="email" required autofocus></div>
      <div class="form-group"><label>Password</label><input type="password" name="password" required></div>
      <button class="btn block">Log in</button>
    </form>
    <p class="muted small center">Demo: demo@example.com / password</p>
    <p class="center">New here? <a href="register.php">Create an account</a></p>
  </div>
</div>
<?php include 'includes/footer.php'; ?>

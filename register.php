<?php
require_once 'config.php';
if (!empty($_SESSION['user_id'])) { header('Location: dashboard.php'); exit; }
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $name = trim($_POST['name'] ?? ''); $email = trim($_POST['email'] ?? ''); $pass = $_POST['password'] ?? '';
    if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($pass) < 6) {
        $error = 'Enter a valid name, email and a password of at least 6 characters.';
    } else {
        $st = db_prepare($conn, 'INSERT INTO users(name,email,password) VALUES(?,?,?)');
        $hash = password_hash($pass, PASSWORD_DEFAULT);
        $st->bind_param('sss', $name, $email, $hash);
        if ($st->execute()) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $st->insert_id; $_SESSION['user_name'] = $name;
            header('Location: dashboard.php'); exit;
        }
        $error = 'That email is already registered.';
    }
}
$page_title = 'Create account'; include 'includes/header.php'; ?>
<div class="auth">
  <div class="card form-card">
    <h2>Create your account</h2><p class="muted">Start turning meetings into minutes.</p>
    <?php if ($error): ?><div class="alert err"><?= h($error) ?></div><?php endif; ?>
    <form method="post">
      <?= csrf_field() ?>
      <div class="form-group"><label>Full name</label><input name="name" required autofocus></div>
      <div class="form-group"><label>Email</label><input type="email" name="email" required></div>
      <div class="form-group"><label>Password</label><input type="password" name="password" minlength="6" required><small>At least 6 characters</small></div>
      <button class="btn block">Create account</button>
    </form>
    <p class="center">Already registered? <a href="login.php">Log in</a></p>
  </div>
</div>
<?php include 'includes/footer.php'; ?>

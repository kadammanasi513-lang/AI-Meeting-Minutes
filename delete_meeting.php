<?php
require_once 'config.php'; require_login();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: dashboard.php'); exit; }
csrf_check();
$id = (int)($_POST['id'] ?? 0);
$m = fetch_meeting($conn, $id);
if ($m) {
    if (!empty($m['stored_file']) && preg_match('/^[a-f0-9]+\.[a-z0-9]+$/', $m['stored_file'])) @unlink(__DIR__ . '/uploads/' . $m['stored_file']);
    $uid = (int)$_SESSION['user_id'];
    $st = db_prepare($conn, 'DELETE FROM meetings WHERE id=? AND user_id=?');
    $st->bind_param('ii', $id, $uid); $st->execute();
    flash('Meeting deleted.');
}
header('Location: dashboard.php');

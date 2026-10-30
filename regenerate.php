<?php
require_once 'config.php'; require_login();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: dashboard.php'); exit; }
csrf_check();
$id = (int)($_POST['id'] ?? 0);
$m = fetch_meeting($conn, $id);
if (!$m) { header('Location: dashboard.php'); exit; }

$r = ai_generate_minutes($m['title'], $m['language'], $m['transcript'], '');
$c = minutes_to_columns($r);
$uid = (int)$_SESSION['user_id'];
$st = db_prepare($conn, 'UPDATE meetings SET summary=?,discussion_points=?,decisions=?,action_items=?,speakers=?,ai_summary=?,minutes_json=?,engine=? WHERE id=? AND user_id=?');
$st->bind_param('ssssssssii', $c['summary'], $c['discussion_points'], $c['decisions'], $c['action_items'], $c['speakers'], $c['ai_summary'], $c['minutes_json'], $c['engine'], $id, $uid);
$st->execute();
if ($r['_engine'] !== 'local') flash('Minutes regenerated with ' . ai_engine_label($r['_engine']) . '.');
else flash($r['_error'] === 'no_key' ? 'No AI API key configured — see config.local.example.php.' : 'AI request failed: ' . $r['_error'], 'warn');
header('Location: view_meeting.php?id=' . $id);

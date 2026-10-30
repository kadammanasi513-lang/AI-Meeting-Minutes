<?php
require_once 'config.php'; require_login();
$m = fetch_meeting($conn, (int)($_GET['id'] ?? 0));
if (!$m) { header('Location: dashboard.php'); exit; }
$r = meeting_minutes($m);

$o  = "MEETING MINUTES\n===============\n";
$o .= "Title: {$m['title']}\nDate: {$m['meeting_date']}\n";
if ($m['duration']) $o .= "Duration: {$m['duration']}\n";
$o .= 'Participants: ' . ($r['speakers'] ? implode(', ', $r['speakers']) : ($m['speakers'] ?: 'n/a')) . "\n\n";
$o .= "SUMMARY\n-------\n{$r['summary']}\n\n";
$sec = function (string $h, array $items) use (&$o) {
    $o .= "$h\n" . str_repeat('-', strlen($h)) . "\n";
    $o .= $items ? implode("\n", array_map(fn($i) => "- $i", $items)) : '(none)';
    $o .= "\n\n";
};
$sec('KEY DISCUSSION POINTS', $r['key_points']);
$sec('DECISIONS', $r['decisions']);
$sec('ACTION ITEMS', array_map(fn($a) => "{$a['task']} | Owner: {$a['owner']} | Due: {$a['due']} | Priority: {$a['priority']}", $r['action_items']));
if ($r['follow_ups']) $sec('FOLLOW-UPS', $r['follow_ups']);

$fn = preg_replace('/[^A-Za-z0-9_-]+/', '_', $m['title']) ?: 'meeting';
header('Content-Type: text/plain; charset=utf-8');
header('Content-Disposition: attachment; filename="minutes_' . $fn . '.txt"');
echo $o;

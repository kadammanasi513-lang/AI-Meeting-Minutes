<?php
require_once 'config.php'; require_login();
$error = '';
$langs = ['English' => 'en-IN', 'Hindi' => 'hi-IN', 'Marathi' => 'mr-IN', 'Spanish' => 'es-ES', 'French' => 'fr-FR', 'German' => 'de-DE', 'Other' => 'en-US'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $title = trim($_POST['title'] ?? ''); $date = $_POST['meeting_date'] ?? '';
    $duration = trim($_POST['duration'] ?? ''); $language = $_POST['language'] ?? 'English';
    $transcript = trim($_POST['transcript'] ?? ''); $speakers = trim($_POST['speakers'] ?? '');
    if (!isset($langs[$language])) $language = 'English';

    if ($title === '' || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date) || $transcript === '') {
        $error = 'Please fill in the title, date and transcript.';
    } elseif (mb_strlen($transcript) < 30) {
        $error = 'The transcript is too short to summarize. Add more of the conversation.';
    } else {
        $fileName = ''; $stored = '';
        if (!empty($_FILES['audio_file']['name']) && $_FILES['audio_file']['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['audio_file']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['mp3', 'wav', 'm4a', 'webm', 'ogg'], true) && $_FILES['audio_file']['size'] < 20 * 1024 * 1024) {
                $stored = bin2hex(random_bytes(8)) . '.' . $ext;
                if (move_uploaded_file($_FILES['audio_file']['tmp_name'], __DIR__ . '/uploads/' . $stored)) {
                    $fileName = mb_substr(basename($_FILES['audio_file']['name']), 0, 200);
                } else { $stored = ''; }
            } else { $error = 'Audio must be mp3, wav, m4a, webm or ogg and under 20 MB.'; }
        }
        if (!$error) {
            $r = ai_generate_minutes($title, $language, $transcript, $speakers);
            $c = minutes_to_columns($r);
            if ($speakers !== '') $c['speakers'] = $speakers;
            $uid = (int)$_SESSION['user_id'];
            $st = db_prepare($conn, 'INSERT INTO meetings(user_id,title,meeting_date,duration,transcript,summary,discussion_points,decisions,action_items,speakers,language,ai_summary,file_name,stored_file,minutes_json,engine) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
            $st->bind_param('isssssssssssssss', $uid, $title, $date, $duration, $transcript, $c['summary'], $c['discussion_points'], $c['decisions'], $c['action_items'], $c['speakers'], $language, $c['ai_summary'], $fileName, $stored, $c['minutes_json'], $c['engine']);
            $st->execute();
            if ($r['_engine'] === 'local') {
                flash($r['_error'] === 'no_key'
                    ? 'Minutes created with the basic offline engine. Add a free Gemini API key in config.local.php for full AI summaries.'
                    : 'AI service was unavailable (' . $r['_error'] . '). Basic offline minutes were created — use “Regenerate with AI” later.', 'warn');
            } else { flash('AI minutes generated successfully.'); }
            header('Location: view_meeting.php?id=' . $st->insert_id); exit;
        }
    }
}
$page_title = 'New meeting'; include 'includes/header.php'; ?>
<div class="card form-card wide">
  <span class="eyebrow">New meeting</span><h2>Create AI meeting minutes</h2>
  <p class="muted">Paste or dictate the transcript. Use speaker labels like <b>Rahul:</b> so the AI can assign action items to the right person.</p>
  <?php if ($error): ?><div class="alert err"><?= h($error) ?></div><?php endif; ?>
  <form method="post" enctype="multipart/form-data" id="meetingForm">
    <?= csrf_field() ?>
    <div class="form-group"><label>Meeting title</label><input name="title" value="<?= h($_POST['title'] ?? '') ?>" placeholder="e.g. Project review meeting" required></div>
    <div class="grid3">
      <div class="form-group"><label>Date</label><input type="date" name="meeting_date" value="<?= h($_POST['meeting_date'] ?? date('Y-m-d')) ?>" required></div>
      <div class="form-group"><label>Duration</label><input name="duration" value="<?= h($_POST['duration'] ?? '') ?>" placeholder="e.g. 45 minutes"></div>
      <div class="form-group"><label>Language</label>
        <select name="language" id="language"><?php foreach ($langs as $n => $code): ?><option value="<?= h($n) ?>" data-code="<?= h($code) ?>" <?= ($_POST['language'] ?? '') === $n ? 'selected' : '' ?>><?= h($n) ?></option><?php endforeach; ?></select></div>
    </div>
    <div class="grid2">
      <div class="form-group"><label>Participants <span class="opt">(optional)</span></label><input name="speakers" value="<?= h($_POST['speakers'] ?? '') ?>" placeholder="Rahul, Priya, Amit"></div>
      <div class="form-group"><label>Meeting audio <span class="opt">(optional, attached to the minutes)</span></label><input id="audio_file" type="file" name="audio_file" accept=".mp3,.wav,.m4a,.webm,.ogg"><small id="file_name">No file selected</small></div>
    </div>
    <div class="form-group">
      <div class="label-row"><label>Transcript</label>
        <div class="tools">
          <button type="button" class="btn ghost small" id="micBtn">🎙️ Dictate</button>
          <label class="btn ghost small" for="txtFile">📄 Load .txt</label><input type="file" id="txtFile" accept=".txt,text/plain" hidden>
        </div></div>
      <textarea name="transcript" id="transcript" rows="12" placeholder="Rahul: Welcome everyone.&#10;Priya: We completed the database module. I will update the docs by Friday.&#10;Amit: Let's go with the November launch." required><?= h($_POST['transcript'] ?? '') ?></textarea>
      <small id="micStatus" class="muted"></small>
    </div>
    <div class="form-actions">
      <button class="btn big" id="submitBtn">✨ Generate minutes</button>
      <a class="btn ghost big" href="dashboard.php">Cancel</a>
      <span class="muted small" id="loading" hidden>AI is reading your meeting… this takes 5–20 seconds.</span>
    </div>
  </form>
</div>
<?php include 'includes/footer.php'; ?>

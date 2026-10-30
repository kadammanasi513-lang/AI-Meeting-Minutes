<?php $page_title = 'AI meeting minutes'; include 'includes/header.php';
$cta = !empty($_SESSION['user_id']) ? 'new_meeting.php' : 'register.php'; ?>
<section class="hero">
  <div>
    <span class="pill">✦ Powered by AI</span>
    <h1>Turn every meeting into <span class="grad">clear, actionable minutes</span>.</h1>
    <p class="lead">Paste a transcript or dictate live. In seconds you get an executive summary, key decisions, and action items with owners and due dates — ready to share or print.</p>
    <div class="actions">
      <a class="btn big" href="<?= $cta ?>">Create minutes — it's free</a>
      <a class="btn ghost big" href="#how">See how it works</a>
    </div>
    <div class="trust"><span>✓ Action items with owners</span><span>✓ Hindi, Marathi &amp; more</span><span>✓ Private per-user dashboard</span></div>
  </div>
  <div class="mock card">
    <div class="mock-top"><i></i><i></i><i></i></div>
    <h3>Product Review · 12 Oct</h3>
    <p class="muted small">Summary</p>
    <p>The team confirmed the beta launch for November and agreed to freeze new features this week.</p>
    <p class="muted small">Action items</p>
    <div class="mock-row"><span>Finalize test plan</span><b class="prio High">High</b></div>
    <div class="mock-row"><span>Priya · Update docs</span><b class="prio Medium">Medium</b></div>
    <div class="mock-row"><span>Rahul · Demo script</span><b class="prio Low">Low</b></div>
  </div>
</section>

<section id="features">
  <div class="section-head"><h2>Everything you need after the meeting</h2><p class="muted">Stop typing notes. Start following up.</p></div>
  <div class="feature-grid">
    <div class="card feature"><div class="icon">✨</div><h3>AI summaries</h3><p>AI reads the full transcript and writes a concise executive summary with the key points.</p></div>
    <div class="card feature"><div class="icon">✅</div><h3>Decisions &amp; actions</h3><p>Decisions are separated from tasks, and every task gets an owner, due date and priority.</p></div>
    <div class="card feature"><div class="icon">🎙️</div><h3>Live dictation</h3><p>Speak into your microphone and watch the transcript appear — or upload a .txt transcript.</p></div>
    <div class="card feature"><div class="icon">🌐</div><h3>Multilingual</h3><p>English, Hindi, Marathi, Spanish, French, German — minutes are written in the meeting language.</p></div>
    <div class="card feature"><div class="icon">🖨️</div><h3>Share &amp; export</h3><p>Print or save as PDF, copy to clipboard, or download as a text file.</p></div>
    <div class="card feature"><div class="icon">🔐</div><h3>Private &amp; secure</h3><p>Your meetings belong to your account only, with CSRF protection and hashed passwords.</p></div>
  </div>
</section>

<section id="how">
  <div class="section-head"><h2>How it works</h2></div>
  <div class="steps">
    <div class="step"><b>1</b><h3>Add your meeting</h3><p>Type or dictate the transcript with speaker labels like <code>Rahul:</code>.</p></div>
    <div class="step"><b>2</b><h3>AI generates minutes</h3><p>Summary, decisions, action items, topics and follow-ups in one click.</p></div>
    <div class="step"><b>3</b><h3>Share the result</h3><p>Print, export or revisit it any time from your dashboard.</p></div>
  </div>
</section>
<?php include 'includes/footer.php'; ?>

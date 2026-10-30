# MinutesAI — AI Meeting Minutes (PHP + MySQL + Claude)

Turn a meeting transcript into an executive summary, key points, decisions, and action items
(with owner, due date and priority) using the Claude API. Works offline with a basic fallback engine.

## Features
- Professional landing page, login/registration, per-user dashboard with search and live stats
- **Real AI minutes** via Anthropic Claude API (summary, decisions, action items, topics, tone, follow-ups)
- Offline fallback engine when no API key is set or the API is unreachable
- Live voice dictation in the browser (Chrome/Edge) in English, Hindi, Marathi, Spanish, French, German
- Load a `.txt` transcript; attach an audio recording (playable on the minutes page)
- Speaker detection from `Name:` labels; participants list
- Copy, download `.txt`, Print / Save as PDF; regenerate with AI; delete meeting
- Security: CSRF tokens, prepared statements, hashed passwords, session ID regeneration, output escaping

## Setup (XAMPP)
1. Copy this folder into `C:\xampp\htdocs\`.
2. Start Apache and MySQL. Make sure `extension=curl` is enabled in `php.ini` (default in XAMPP).
3. In phpMyAdmin, import `database.sql`.
   (Already have the older version's database? Import `upgrade_from_v2.sql` instead.)
4. Copy `config.local.example.php` to `config.local.php` and paste an API key:
   - **Free:** Google Gemini key from https://aistudio.google.com/apikey (`GEMINI_API_KEY`)
   - **Paid:** Anthropic Claude key from https://console.anthropic.com (`AI_API_KEY`)
   Gemini is tried first, then Claude, then the basic offline engine if neither works.
5. Open `http://localhost/ai_meeting_minutes_project/` — log in with `demo@example.com` / `password` or register.

## Notes
- The AI reads the **transcript**; it does not transcribe uploaded audio files. Use the 🎙️ Dictate button
  (browser speech recognition) or paste a transcript from a tool such as Google Meet / Zoom / Teams.
- Transcripts are sent to the Anthropic API when an API key is configured. Don't use it for confidential
  meetings unless that is acceptable to you.
- Keep `config.local.php` private; never share your API key.

## File map
| File | Purpose |
|---|---|
| `config.php` | DB connection, session, settings (overridden by `config.local.php`) |
| `includes/functions.php` | CSRF, helpers, Claude API call, fallback engine |
| `new_meeting.php` | Create meeting + generate minutes |
| `view_meeting.php` | Minutes page (print-ready) |
| `regenerate.php`, `delete_meeting.php`, `export.php` | Actions |
| `assets/` | CSS and JavaScript |

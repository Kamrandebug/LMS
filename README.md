# LearnUp — LAT Preparation Platform

A free, gamified MCQ practice platform focused on the **LAT (Law Admission Test)**. Built with Laravel 11, Livewire 3, Tailwind CSS, and Alpine.js.

> **Project location:** This codebase lives at `K:\LMS` (previously referred to as `learnup-fresh`). It is the same LearnUp platform — all paths below are relative to `K:\LMS`.

---

## Current State (August 2026)

The platform is positioned as a **LAT preparation** site — the live product focuses exclusively on LAT (Law Admission Test) MCQ practice and Mock Tests.

**Authentication is now fully implemented** with a custom flip-card UI (login/register on one card), styled password-reset pages, and auth links in the nav. See [Features → 10. Authentication](#10-authentication-custom-flip-card-ui) below.

Behind the scenes, the project also contains a **restored content pipeline** from the original static HTML backup:

- **6 subject MCQ data files** in `storage/mcq-data/` and `storage/mcq-data/subjects/` — Computer Science, Everyday Science, General Knowledge, Islamic Studies, Pakistan Studies, English.
- **5 mock exam JSON files** in `storage/mcq-data/mock-exams/` — 2× FIA, 3× Deputy Accountant (PPSC). The JSON files and the `MockExamSeeder` use `exam_type` values `fia` and `ppsc` directly (see [Models & Schema → Mock Exams](#mock-exams)).
- **One-shot extraction pipeline**: `php artisan backup:extract` (`app/Console/Commands/ExtractFromBackup.php`) converts the original static HTML backup (`Backup/`) → JSON (`storage/mcq-data/`) → database in three phases. Standalone `storage/scripts/` PHP scripts (`extract_subjects.php`, `extract_mock_exams.php`) were the original extractors and remain as reference.

> ⚠️ **Data status:** The database is currently **empty**. The JSON data exists in `storage/mcq-data/` but has not yet been imported. See [Setup & Data Import](#setup--data-import) below.

The non-LAT mock exams (FIA, PPSC Deputy Accountant) are **imported data only** — they are not linked from the live LAT-only navigation. Playlists and Past Papers models/seeders remain in the codebase for data integrity, but their routes/views were removed during the LAT refocus.

---

## Tech Stack

| Layer | Technology |
|-------|-----------|
| **Backend** | PHP 8.2+, Laravel 11 |
| **Frontend** | Blade, Livewire 3, Alpine.js 3, Tailwind CSS 3 |
| **Build** | Vite 5, PostCSS, Autoprefixer |
| **Database** | SQLite (default) / MySQL / MariaDB |
| **Auth** | Laravel Breeze auth (controllers + views), **custom flip-card auth UI** (login/register/password reset) |
| **SEO** | `artesaos/seotools` |
| **Sitemap** | Hand-rolled `sitemap:generate` command (SimpleXML) — `spatie/laravel-sitemap` is installed but unused |
| **Permissions / Activity Log** | `spatie/laravel-permission`, `spatie/laravel-activitylog` (installed, not exercised by routes) |
| **Debugging** | `laravel/telescope` |
| **Analytics** | Google Analytics 4 (tag manager) |
| **Ads** | Google AdSense |

---

## Directory Structure

```
app/
├── Console/Commands/
│   ├── GenerateSitemap.php           # Generates sitemap.xml (SimpleXML, no package)
│   ├── ImportMcqsFromJson.php        # Imports MCQs from JSON files in storage/mcq-data/
│   ├── ImportMockExamsFromJson.php   # `mock:import` — mock exams from storage/mcq-data/mock-exams/
│   └── ExtractFromBackup.php         # `backup:extract` — HTML backup → JSON → DB pipeline
├── Http/Controllers/Web/
│   ├── HomeController.php         # Landing page with subject grid
│   ├── SubjectController.php      # Subject detail → topic listing
│   ├── TopicController.php        # Topic detail → set listing
│   ├── QuestionSetController.php  # Quiz engine for a set
│   ├── MockExamController.php     # Timed mock test listing & detail
│   └── PageController.php         # Static pages (privacy, terms)
├── Http/Controllers/Api/
│   ├── DailyFuelController.php    # Daily Fuel quote API
│   ├── ProgressController.php     # Progress sync API
│   └── QuestionReportController.php # Question report API
├── Http/Middleware/
│   ├── SetThemeFromCookie.php     # Reads `theme` cookie → session/views (alias: theme.cookie)
│   └── TrackPageView.php          # Increments a daily per-path view counter in cache
├── Http/Requests/
│   ├── StoreQuestionReportRequest.php # Validates the /api/v1/report payload
│   └── TrackProgressRequest.php       # Validates the /api/v1/progress/sync payload
├── Livewire/
│   ├── QuizEngine.php             # Core quiz — answers, progress, redemption, streak
│   ├── MockExamEngine.php         # Timed mock exam with timer & negative marking
│   ├── DailyFuelBanner.php        # Daily motivational quote banner
│   ├── StreakCounter.php          # Nav streak badge (🔥 N)
│   └── ThemeToggle.php            # Dark/light mode toggle
├── Models/
│   ├── Subject.php                # Subject (English, Math, etc.)
│   ├── Topic.php                  # Topic within a subject
│   ├── QuestionSet.php            # Set of 20 questions
│   ├── Question.php               # Single MCQ with 4 options + explanation
│   ├── MockExam.php               # Timed exam config
│   ├── MockExamQuestion.php       # Questions for mock exams
│   ├── Playlist.php               # (unused — kept for data integrity)
│   ├── PlaylistModule.php         # (unused — kept for data integrity)
│   ├── PastPaper.php              # (unused — kept for data integrity)
│   ├── DailyQuote.php             # Motivational quotes for Daily Fuel
│   ├── QuestionReport.php         # User-submitted issue reports
│   └── User.php                   # Laravel auth user
├── Http/Controllers/Auth/
│   ├── AuthenticatedSessionController.php # Login/logout
│   ├── RegisteredUserController.php       # Registration
│   ├── PasswordResetLinkController.php    # Forgot password
│   ├── NewPasswordController.php          # Reset password
│   ├── ConfirmablePasswordController.php  # Password confirmation
│   ├── PasswordController.php             # Update password
│   ├── EmailVerificationPromptController.php / EmailVerificationNotificationController.php / VerifyEmailController.php
├── Http/Requests/Auth/
│   ├── LoginRequest.php       # Login validation + rate limiting + authenticate
│   └── StoreUserRequest.php   # Registration validation (name, email, password confirmed)
├── Observers/
│   └── QuestionReportObserver.php # Dispatches job when a report is submitted
├── Jobs/
│   ├── NotifyAdminOfReport.php    # Email notification on new question report
│   └── SyncUserProgress.php       # Syncs progress data
├── Providers/
│   ├── AppServiceProvider.php     # Registers QuestionReportObserver, Telescope (local), Schema default length
│   └── ViewServiceProvider.php    # Shares navSubjects (cached 1h) + contactInfo globally
├── Services/
│   ├── StreakService.php          # Session-based streak tracking
│   ├── QuizProgressService.php    # Save/resume quiz state per session
│   ├── DailyFuelService.php       # Daily quote logic (Islamic on Fridays)
│   ├── AdInjectionService.php     # Ad placement with intent-based targeting
│   └── WhatsAppShareService.php   # Share results on WhatsApp
├── Repositories/
│   ├── SubjectRepository.php      # Active subjects + topic question-set counts
│   └── QuestionRepository.php     # Questions by set / qid / random
database/migrations/
├── 0001_01_01_*                   # Users, cache, jobs
├── 2024_01_01_*                   # Subjects, topics, question_sets, questions
├── 2024_01_01_*                   # Playlists, playlist_modules (legacy)
├── 2024_01_01_*                   # Mock exams, mock exam questions
├── 2024_01_01_*                   # Past papers (legacy)
├── 2024_01_01_*                   # Daily quotes, question reports
database/seeders/
├── SubjectSeeder.php              # 6 core subjects
├── TopicSeeder.php / QuestionSetSeeder.php / QuestionSeeder.php
├── DailyQuoteSeeder.php           # Motivational quotes
├── MockExamSeeder.php             # 5 mock exams (FIA + PPSC Deputy Accountant)
└── PlaylistSeeder.php             # Legacy data
resources/views/
├── layouts/
│   └── auth.blade.php             # Standalone auth layout: 3D flip card + hero panel
├── components/
│   ├── layouts/
│   │   ├── app.blade.php          # Main layout (nav, footer, GA, AdSense, Livewire)
│   │   └── quiz.blade.php         # Alternate quiz layout (no nav, minimal)
│   ├── auth-input.blade.php       # Reusable labeled input with inline @error + password toggle
│   ├── nav.blade.php              # Sticky nav (Exams, Mock Tests, auth links)
│   ├── footer.blade.php           # Footer with contact + social info
│   ├── subject-card.blade.php     # Hero card for each subject on home
│   ├── topic-card.blade.php       # Topic card with grid of set cards
│   ├── set-card.blade.php         # Individual question set card
│   ├── mock-exam-card.blade.php   # Mock test card with stats
│   ├── ad-slot.blade.php          # AdSense slot with intent-based fallback
│   ├── progress-badge.blade.php   # Completion % badge
│   ├── streak-badge.blade.php     # Streak display component
│   └── seo-head.blade.php         # SEO comment reference
├── auth/
│   ├── login.blade.php            # Login page (flip-card front face)
│   ├── register.blade.php         # Registration page (flip-card back face)
│   ├── forgot-password.blade.php  # Password reset link request
│   ├── reset-password.blade.php   # Set new password (from emailed token link)
│   ├── confirm-password.blade.php # Password confirmation screen
│   ├── verify-email.blade.php     # Email verification prompt
│   └── partials/
│       ├── login-form.blade.php   # Login form (POST /login)
│       ├── register-form.blade.php# Registration form (POST /register)
│       └── hero-panel.blade.php   # LAT hero message (right panel, both sides)
├── livewire/
│   ├── quiz-engine.blade.php      # Quiz UI: question, options, feedback, results
│   ├── mock-exam-engine.blade.php # Mock exam UI: timer, navigation, scoring
│   ├── daily-fuel-banner.blade.php# Top banner with daily quote
│   ├── streak-counter.blade.php   # Inline streak number
│   └── theme-toggle.blade.php     # Dark mode toggle button
└── pages/
    ├── home.blade.php             # Landing with hero + subject grid + quick links
    ├── subject.blade.php          # Subject detail with topic list
    ├── topic.blade.php            # Topic with question set grid
    ├── question-set.blade.php     # Full quiz interface
    ├── mock-exams.blade.php       # Mock test listing
    ├── mock-exam-show.blade.php   # Mock exam interface (wraps Livewire)
    ├── privacy-policy.blade.php   # Privacy policy
    ├── terms-of-service.blade.php # Terms of service
    └── errors/
        ├── 404.blade.php          # Custom 404 page
        └── 500.blade.php          # Custom 500 page

routes/
├── web.php                        # All web routes (see below)
├── api.php                        # /api/v1 routes (daily-fuel, report, progress/sync)
├── auth.php                       # Auth routes: login, register, logout, password reset, email verification
├── channels.php                   # Broadcast channels (default scaffold)
└── console.php                    # Scheduled commands (default scaffold)
config/
├── app.php                        # App config + aliases (SEOTools)
├── learnup.php                    # Custom config: contact info, ads, quiz settings
└── ...                            # Other Laravel config files
storage/
├── mcq-data/                      # Import-ready JSON
│   ├── *.json                     # Subject MCQ files (e.g. english.json)
│   ├── subjects/                  # Per-subject JSON (with icon_svg + color_class)
│   ├── mock-exams/                # Mock exam JSON (exam_type, duration, negative marking)
│   └── legacy/                    # Legacy seed JSON (daily-quotes.json, playlists.json)
├── scripts/                       # Original standalone extraction PHP scripts (reference)
resources/css/
├── app.css                        # Tailwind directives + global component classes
└── auth.css                       # Auth-only CSS: 3D flip effect + network background
resources/js/
├── app.js                         # Alpine.js, theme manager, confetti, event wiring
└── bootstrap.js                   # Axios + Echo scaffold (default)
lang/en/
├── auth.php                       # Auth translation lines (login failed/throttle)
└── passwords.php                  # Password reset translation lines
Backup/                            # Original static HTML site backup
```

---

## Routes

### Web routes (`routes/web.php`)

| Method | URI | Name | Controller | Auth |
|--------|-----|------|------------|------|
| GET | `/` | `home` | `HomeController@index` | public |
| GET | `/mock-tests` | `mock-exams.index` | `MockExamController@index` | public |
| GET | `/privacy-policy` | `privacy` | `PageController@privacy` | public |
| GET | `/terms-of-service` | `terms` | `PageController@terms` | public |
| GET | `/subjects/{subject:slug}` | `subjects.show` | `SubjectController@show` | **auth** |
| GET | `/subjects/{subject:slug}/{topic:slug}` | `topics.show` | `TopicController@show` | **auth** |
| GET | `/subjects/{subject:slug}/{topic:slug}/set-{setNumber}` | `sets.show` | `QuestionSetController@show` | **auth** |
| GET | `/mock-tests/{mockExam:slug}` | `mock-exams.show` | `MockExamController@show` | **auth** |

> Subjects, topics, sets, and taking a mock exam require login (they sit inside a `Route::middleware('auth')` group). The landing page, mock-test listing, and static pages stay public.

### API routes (`routes/api.php`)

| Method | URI | Name | Controller |
|--------|-----|------|------------|
| GET | `/api/v1/daily-fuel` | — | `Api\DailyFuelController@index` |
| POST | `/api/v1/report` | — | `Api\QuestionReportController@store` |
| POST | `/api/v1/progress/sync` | — | `Api\ProgressController@sync` |

> The API routes use `Route::prefix('v1')->middleware('throttle:60,1')`. **Note:** `routes/api.php` is currently **not loaded** — `bootstrap/app.php` only registers `web.php` via `withRouting(web: ...)`, so these endpoints are not reachable until `api: ...` is added to `withRouting`.

### Auth routes (`routes/auth.php`)

| Method | URI | Name | Controller |
|--------|-----|------|------------|
| GET/POST | `/login` | `login` | `Auth\AuthenticatedSessionController@create/store` |
| GET/POST | `/register` | `register` | `Auth\RegisteredUserController@create/store` |
| POST | `/logout` | `logout` | `Auth\AuthenticatedSessionController@destroy` |
| GET/POST | `/forgot-password` | `password.request` / `password.email` | `Auth\PasswordResetLinkController@create/store` |
| GET/POST | `/reset-password/{token}` | `password.reset` / `password.store` | `Auth\NewPasswordController@create/store` |
| GET/POST | `/confirm-password` | `password.confirm` | `Auth\ConfirmablePasswordController@show/store` |
| PUT | `/password` | `password.update` | `Auth\PasswordController@update` |
| GET | `/verify-email` | `verification.notice` | `Auth\EmailVerificationPromptController` |
| GET | `/verify-email/{id}/{hash}` | `verification.verify` | `Auth\VerifyEmailController` (signed + throttled) |
| POST | `/email/verification-notification` | `verification.send` | `Auth\EmailVerificationNotificationController@store` |

Login redirects to `redirect()->intended(route('home'))` (the originally-requested page when protected, else home); registration and logout go to **`/` (home)**. The `login` / `register` routes are wrapped in the `guest` middleware; the rest are behind `auth`.

### Custom middleware

Two aliases are registered in `routes/channels.php` (a quirk worth noting — aliases usually live in `bootstrap/app.php`):

| Alias | Middleware | Purpose |
|-------|-----------|---------|
| `theme.cookie` | `SetThemeFromCookie` | Reads the `theme` cookie → shares `theme` with views + session |
| `track.pageview` | `TrackPageView` | Increments a daily per-path counter in the cache for non-AJAX GETs |

Neither is applied globally in `bootstrap/app.php` — `track.pageview` is not currently attached to any route, and `theme.cookie` is not wired into the web group.

---

## Features

### 1. Subject-Based Quiz System
- **Subjects** → **Topics** → **Question Sets** (20 MCQs each)
- Each question has 4 options (A/B/C/D) with a correct answer and explanation
- Instant feedback after each answer (green = correct, red = wrong with wiggle animation)
- **Redemption mode**: retry only the questions you got wrong
- **Resume** capability: progress saved to `localStorage`, prompts to continue on return
- **Break screen** every 10 questions with a motivational quote
- **Streak tracking**: 🔥 counter increments per correct set
- Quiz completion tracked via `localStorage.completed_sets` — checkmarks on set cards
- **WhatsApp sharing**: "I scored X/Y on Just LearnUp!"
- **Confetti** on 100% score using canvas-confetti

### 2. Timed Mock Tests (`/mock-tests`)
- Full exam simulation with countdown timer
- **Exam Mode**: timed with configurable negative marking
- **Practice Mode**: timer runs but no negative marking
- Auto-submit when time expires
- Score breakdown: correct, wrong, skipped
- Active exam list cached for 30 min (`MockExamController@index`); `attempt_count` is incremented on **each view** of an exam's detail page (`MockExamController@show`)

### 3. Daily Fuel Banner
- An inspirational quote in a top banner, refreshed daily
- **Islamic quotes** on Fridays (`DailyFuelService`)
- Uses `DailyQuote` model with type categories: `islamic`, `fact`, `motivation`
- Dismissable with animation

### 4. Streak System
- Session-based streak via `StreakService`
- Increments on quiz/mock exam completion
- Displayed inline in nav as 🔥 N

### 5. Dark / Light Mode
- Persisted via `localStorage` + cookie
- Toggle in nav (desktop + mobile)
- Respects `prefers-color-scheme` on first visit

### 6. Question Reporting
- Flag button on each question
- Report reasons: Wrong Answer Key, Typo/Grammar, Wrong Set, Confusing Explanation
- Report stored in `question_reports` table via API endpoint
- Observer dispatches `NotifyAdminOfReport` job

### 7. SEO
- SEO meta tags set per-page via `artesaos/seotools` (title, description, OpenGraph)
- Sitemap generation via `php artisan sitemap:generate`
- Automatic sitemap includes subjects, topics, question sets

### 8. Analytics & Ads
- Google Analytics 4 with anonymized IP
- Google AdSense integration (`ad-slot.blade.php`)
- Intent-based ad targeting via `AdInjectionService`

### 9. Global View Data
- **Navigation subjects**: cached for 1 hour, shared globally via `ViewServiceProvider`
- **Contact info**: from `config/learnup.php.contact`, available in all views

### 10. Authentication (Custom Flip-Card UI)
- **Login / Register on one 3D flip card** — `/login` shows the login face; `/register` starts flipped to the registration face. Toggling "Sign Up" / "Sign In" flips the card (Alpine.js + CSS `transform` with `perspective`).
- **LAT hero panel** on the right of both faces: *"LAT Preparation LMS"* with the quote *"Empowering future lawyers with smart practice. LearnUp."*
- **Login form**: email, password, "Remember me", "Forgot Password?" link, "Sign In" button → redirects to `/`.
- **Registration form**: full name, email, password, confirm password, "Sign Up" button → creates user, auto-logs-in, redirects to `/`.
- **Inline validation** — `@error` messages under each field; login failures show "These credentials do not match our records" in the form.
- **Password visibility toggle** (eye icon) on every password field via Alpine.js.
- **Password reset**: styled `/forgot-password` (email → reset link) and `/reset-password/{token}` (set new password) pages using the same auth layout with a simple centered card.
- **Email verification** — implemented (routes + views) but **not enforced** by default (users can log in without verifying).
- **Design**: brand colors `#020b30` (dark blue), `#1c41a8` (primary blue), `#6a63e7` (accent purple). Custom CSS is kept minimal (`resources/css/auth.css` — flip + network background only); everything else uses Tailwind utilities. Responsive: panels stack on mobile.
- **Auth layout** lives at `resources/views/layouts/auth.blade.php`; it does **not** include the main nav/footer (only a "Back to home" link).

---

## Models & Schema

### Core Quiz Data

```
Subject (id, name, slug, description, icon_svg, color_class, sort_order, is_active)
  └── Topic (id, subject_id, name, slug, description, sort_order, is_active)
        └── QuestionSet (id, topic_id, name, slug, set_number, question_count, is_active)
              └── Question (id, question_set_id, qid, question, option_a/b/c/d, correct_option, explanation, sort_order, is_active)
```

### Mock Exams

```
MockExam (id, name, slug, exam_type, total_questions, duration_minutes, negative_marking_value, has_negative_marking, attempt_count, is_active)
  └── MockExamQuestion (id, mock_exam_id, question_text, option_a/b/c/d, correct_option, explanation, sort_order)
```

`exam_type` values used by the imported data: `fia` and `ppsc` — the two FIA JSON files use `fia`; the three Deputy Accountant JSON files use `ppsc` (both `mock:import` and `MockExamSeeder` write the type straight through). LAT-focused exams use `lat`.

### Supporting

```
DailyQuote (id, quote, author, type [islamic|fact|motivation], is_friday_special, is_active)
QuestionReport (id, question_id, mock_exam_question_id, question_set_id, report_type, description, reporter_ip, status, admin_notes)
User (id, name, email, email_verified_at, password, remember_token, intent [lat|jobs|browsing], theme_preference, total_streak, last_activity_date, timestamps)

The extended columns (`intent`, `theme_preference`, `total_streak`, `last_activity_date`) are now defined directly in the base `0001_01_01_000000_create_users_table` migration **and** in `2026_08_01_000001_add_extended_columns_to_users_table` (idempotent via `hasColumn` guards), so `php artisan migrate` runs cleanly. The old conflicting migration `2024_01_01_000012_create_users_table` was removed. Registration writes `name`, `email`, and `password`; streak/theme data persists through the extended columns.
```

---

## Services

| Service | Purpose |
|---------|---------|
| `StreakService` | Session-based streak increment/get/reset |
| `QuizProgressService` | Session-based save/resume quiz state |
| `DailyFuelService` | Daily quote selection (cached, Islamic on Fridays) |
| `AdInjectionService` | Ad slot config + intent-based custom ads |
| `WhatsAppShareService` | Build share URLs for quiz results |

---

## Console Commands

| Command | Description |
|---------|-------------|
| `php artisan mcq:import` | Import MCQs from JSON files in `storage/mcq-data/` |
| `php artisan mcq:import --fresh` | Truncate and re-import all data |
| `php artisan mock:import` | Import mock exams from JSON files in `storage/mcq-data/mock-exams/` |
| `php artisan mock:import --fresh` | Truncate and re-import all mock exam data |
| `php artisan backup:extract` | Full pipeline: extract HTML backup → JSON → run `mcq:import` + `mock:import` |
| `php artisan backup:extract --fresh` | Wipe existing JSON + DB data before extracting |
| `php artisan sitemap:generate` | Generate `public/sitemap.xml` (hand-rolled SimpleXML) |

`mcq:import` reads the **top-level** `storage/mcq-data/*.json` files (the per-subject rich files in `subjects/` include `icon_svg` + `color_class` for the home cards, which `mcq:import` does not write — seed the subject `icon_svg`/`color_class` via `SubjectSeeder` or the `subjects/` JSON when needed).

Subject MCQ JSON format (`storage/mcq-data/*.json` or `storage/mcq-data/subjects/*.json`):
```json
{
  "subject": { "name": "English", "slug": "english", "icon_svg": "🧠", "color_class": "from-violet-500 via-purple-500 to-pink-500" },
  "topics": [
    {
      "name": "Synonyms",
      "slug": "synonyms",
      "sets": [
        {
          "set_number": 1,
          "questions": [
            {
              "qid": "eng-syn-001",
              "question": "...",
              "option_a": "...",
              "option_b": "...",
              "option_c": "...",
              "option_d": "...",
              "correct_option": "a",
              "explanation": "..."
            }
          ]
        }
      ]
    }
  ]
}
```

Mock exam JSON format (`storage/mcq-data/mock-exams/*.json`):
```json
{
  "slug": "fia-mock-exam-1",
  "name": "Deputy Accountant Mock Exam 1",
  "exam_type": "fia",
  "total_questions": 100,
  "duration_minutes": 90,
  "negative_marking_value": 0.25,
  "has_negative_marking": true,
  "questions": [
    {
      "question_text": "...",
      "option_a": "...",
      "option_b": "...",
      "option_c": "...",
      "option_d": "...",
      "correct_option": "c",
      "explanation": "...",
      "sort_order": 1
    }
  ]
}
```

---

## Configuration (`config/learnup.php`)

```php
'adsense' => [ 'client_id', 'slots' ]
'whatsapp' => [ 'share_base_url' ]
'quiz' => [ 'questions_per_set' => 20, 'break_after_questions' => 10 ]
'admin' => [ 'report_notification_email' ]
'contact' => [ 'address', 'phone_primary', 'phone_secondary', 'email', 'website', 'facebook', 'instagram', 'youtube_main', 'youtube_undergraduate' ]
```

---

## Setup & Data Import

```bash
composer install
npm install
cp .env.example .env          # configure database, APP_URL, etc.
php artisan key:generate
php artisan migrate
php artisan db:seed            # seeds subjects, quotes, mock exams (see seeders/)
php artisan mcq:import         # import full MCQ sets from storage/mcq-data/
npm run dev                    # Vite dev server (or `npm run build` for production)
php artisan serve              # Laravel dev server
```

> **Auth note:** The auth pages depend on the Vite build. In development run `npm run dev`; for production run `npm run build`. The custom auth CSS is compiled from `resources/css/auth.css` (registered as a Vite input in `vite.config.js`), and `resources/views/layouts/auth.blade.php` loads it via `@vite([...])`.

### Restoring content from the backup

The database starts empty. Content comes from the extracted backup JSON:

1. **One-shot pipeline** — run `php artisan backup:extract` to go straight from the `Backup/` HTML to a populated database (extract → JSON → `mcq:import` → `mock:import`). Use `--fresh` to wipe existing JSON + DB data first.
2. **Subjects / MCQs** — run `php artisan mcq:import` (reads `storage/mcq-data/*.json`). The `subjects/` subfolder files include `icon_svg` + `color_class` for the home-page cards.
3. **Mock exams** — run `php artisan mock:import` or `php artisan db:seed --class=MockExamSeeder` (5 exams: 2× FIA, 3× PPSC Deputy Accountant).
4. **Verify** — `php artisan tinker --execute="echo \App\Models\Subject::count();"` etc., then clear caches.

> The original static HTML backup lives in `Backup/` (at `Backup/mcqs.learnuppakistan.com/`). `backup:extract` reads it directly; the older standalone extraction scripts that originally produced the JSON are in `storage/scripts/`.

---

## Deleted/Removed Features

The following were removed during the LAT-only refocus of the **live site**:
- **Job Playlists** (`/playlists`) — routes and views removed; models/seeders kept for data integrity
- **Past Papers** (`/past-papers`) — routes and views removed; model kept
- **Roadmap** (`/roadmap`) — removed
- PPSC, FPSC, CSS, PMS, FIA **links** removed from the LAT-only navigation — though FIA/PPSC mock-exam data is still importable from `storage/mcq-data/mock-exams/`

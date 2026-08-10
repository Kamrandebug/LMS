# LearnUp — LAT Preparation Platform

A free, gamified MCQ practice platform focused on the **LAT (Law Admission Test)**. Built with Laravel 11, Livewire 3, Tailwind CSS, and Alpine.js.

> **Project location:** This codebase lives at `K:\LMS` (previously referred to as `learnup-fresh`). It is the same LearnUp platform — all paths below are relative to `K:\LMS`.

---

## Current State (August 2026)

The platform is positioned as a **LAT preparation** site — the live product focuses exclusively on LAT (Law Admission Test) MCQ practice and Mock Tests.

**Authentication is fully implemented** with a custom flip-card UI (login/register on one card, Alpine.js 3D flip), styled password-reset pages, and auth links in the nav. See [Features → 10. Authentication](#10-authentication-custom-flip-card-ui) below.

**Subject Resource Hub (added August 2026):** Each subject page now features a three-card hub — **Resource Material** (study notes, PDFs), **Practice Tests** (MCQ sets by topic), and **Recorded Lectures** (embedded YouTube videos). Topics are always shown for Resource Material and Recorded Lectures, with per-topic badge counts (or "No materials yet" / "No lectures yet" when a topic has none). See [Features → 12. Subject Resource Hub](#12-subject-resource-hub) below.

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
│   ├── ImportMcqsFromJson.php        # `mcq:import` — Imports MCQs from JSON files in storage/mcq-data/
│   ├── ImportMockExamsFromJson.php   # `mock:import` — mock exams from storage/mcq-data/mock-exams/ (uses MockExam model)
│   ├── ImportMockTestsFromJson.php   # `mock:import` (duplicate signature!) — same command but uses MockExam model directly
│   └── ExtractFromBackup.php         # `backup:extract` — HTML backup → JSON → DB pipeline
├── Http/Controllers/Web/
│   ├── HomeController.php            # Landing page with subject grid
│   ├── SubjectController.php         # Subject detail → 3-card hub (Resources, Practice, Lectures)
│   ├── TopicController.php           # Topic detail → set listing
│   ├── QuestionSetController.php     # Quiz engine for a set
│   ├── PracticeController.php        # Practice Tests topic listing (topics with active question sets)
│   ├── ResourceMaterialController.php # Resource Material topic listing + per-topic materials
│   ├── RecordedLectureController.php  # Recorded Lecture topic listing + per-topic lectures with YouTube embeds
│   ├── MockExamController.php        # Mock test listing & detail (uses MockExam model)
│   ├── MockTestController.php        # Mock test listing & detail (uses MockTest model, same views, not wired to routes)
│   └── PageController.php            # Static pages (privacy, terms)
├── Http/Controllers/Api/
│   ├── DailyFuelController.php    # Daily Fuel quote API
│   ├── ProgressController.php     # Progress sync API
│   └── QuestionReportController.php # Question report API
├── Http/Controllers/Admin/
│   ├── DashboardController.php    # Admin dashboard with stats
│   ├── SubjectController.php      # Subjects CRUD (resource)
│   ├── TopicController.php        # Topics CRUD (resource)
│   ├── QuestionSetController.php  # Question sets CRUD (resource)
│   ├── QuestionController.php     # Questions CRUD (resource)
│   ├── MockExamController.php     # Mock exams CRUD (wired to routes)
│   ├── MockExamQuestionController.php # Questions mgr nested under mock exams
│   ├── MockTestController.php     # Mock tests CRUD using MockTest model (not routed)
│   ├── MockTestQuestionController.php # Mock test questions mgr (not routed)
│   └── QuestionReportController.php   # Report viewer: resolve/dismiss/reopen/bulk
├── Http/Middleware/
│   ├── SetThemeFromCookie.php     # Reads `theme` cookie → session/views (alias: theme.cookie)
│   ├── TrackPageView.php          # Increments a daily per-path view counter in cache
│   └── IsAdmin.php                # Admin-only gate (403 if not authenticated or is_admin=false)
├── Http/Requests/
│   ├── StoreQuestionReportRequest.php # Validates the /api/v1/report payload
│   ├── TrackProgressRequest.php       # Validates the /api/v1/progress/sync payload
│   └── Admin/
│       ├── SubjectRequest.php       # Validates subject create/update
│       ├── TopicRequest.php         # Validates topic create/update
│       ├── QuestionSetRequest.php   # Validates question set create/update
│       ├── QuestionRequest.php      # Validates question create/update
│       ├── MockExamRequest.php      # Validates mock exam create/update (wired)
│       └── MockTestRequest.php      # Validates mock test create/update (unused via routes)
├── Livewire/
│   ├── QuizEngine.php             # Core quiz — answers, progress, redemption, streak
│   ├── MockExamEngine.php         # Timed mock exam with timer & negative marking (uses MockExam model)
│   ├── MockTestEngine.php         # Timed mock test with timer & negative marking (uses MockTest model)
│   ├── DailyFuelBanner.php        # Daily motivational quote banner
│   ├── StreakCounter.php          # Nav streak badge (🔥 N)
│   └── ThemeToggle.php            # Dark/light mode toggle
├── Models/
│   ├── Subject.php                # Subject (English, Math, etc.) with topics, resourceMaterials, recordedLectures relations
│   ├── Topic.php                  # Topic within a subject → has questionSets, resourceMaterials, recordedLectures
│   ├── QuestionSet.php            # Set of 20 questions
│   ├── Question.php               # Single MCQ with 4 options + explanation
│   ├── ResourceMaterial.php       # Study material (PDF, DOCX, etc.) per topic (table: resource_materials)
│   ├── RecordedLecture.php        # Video lecture (YouTube embed) per topic (table: recorded_lectures)
│   ├── MockExam.php               # Timed exam config (table: mock_exams)
│   ├── MockExamQuestion.php       # Questions for mock exams (table: mock_exam_questions)
│   ├── MockTest.php               # Same table as MockExam (mock_exams); new model for rename-in-progress
│   ├── MockTestQuestion.php       # Same table as MockExamQuestion (mock_exam_questions); new model for rename-in-progress
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
├── 2026_08_01_000001               # Extended user columns (intent, theme_preference, total_streak, last_activity_date)
├── 2026_08_04_000001               # is_admin column on users table
├── 2026_08_10_000001               # resource_materials table (topic_id, title, file_url, file_type, sort_order)
├── 2026_08_10_000002               # recorded_lectures table (topic_id, title, video_url, platform, duration_minutes)
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
│   ├── mock-test-card.blade.php   # Mock test card (same content, rendered by MockTestController)
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
│   ├── mock-test-engine.blade.php # Mock test UI (rendered by MockTestEngine)
│   ├── daily-fuel-banner.blade.php# Top banner with daily quote
│   ├── streak-counter.blade.php   # Inline streak number
│   └── theme-toggle.blade.php     # Dark mode toggle button
└── pages/
    ├── home.blade.php              # Landing with hero + subject grid + quick links
    ├── subject.blade.php           # Subject detail with 3-card hub (Resources, Practice, Lectures)
    ├── topic.blade.php             # Topic with question set grid
    ├── question-set.blade.php      # Full quiz interface
    ├── resource-topics.blade.php   # Resource Material topic list with per-topic badge counts
    ├── resource-materials.blade.php # Per-topic study materials (PDF/DOCX cards with "Open" button)
    ├── practice-topics.blade.php   # Practice Tests topic list (topics with active question sets)
    ├── lecture-topics.blade.php    # Recorded Lectures topic list with per-topic badge counts
    ├── recorded-lectures.blade.php # Per-topic video lectures with YouTube embeds
    ├── mock-tests.blade.php        # Mock test listing
    ├── mock-test-show.blade.php    # Mock test interface (wraps Livewire)
    ├── privacy-policy.blade.php    # Privacy policy
    ├── terms-of-service.blade.php  # Terms of service
    └── errors/
        ├── 404.blade.php           # Custom 404 page
        └── 500.blade.php           # Custom 500 page
└── admin/
    ├── layouts/
    │   └── app.blade.php          # AdminLTE layout: sidebar, navbar, content wrapper
    ├── dashboard.blade.php        # Dashboard with 8 stat cards + quick actions
    ├── subjects/                  # index, create, edit
    ├── topics/                    # index (DataTables), create, edit
    ├── question-sets/             # index, create, edit (+ _form partial)
    ├── questions/                 # index, create, edit (+ _form partial)
    ├── mock-tests/                # index, create, edit (+ _form partial)
    ├── mock-test-questions/       # index (reorder, inline add)
    └── reports/                   # index, show (resolve/dismiss/reopen)

routes/
├── web.php                        # All web routes (see below)
├── api.php                        # /api/v1 routes (daily-fuel, report, progress/sync)
├── auth.php                       # Auth routes: login, register, logout, password reset, email verification
├── admin.php                      # Admin panel routes: /admin/* (auth + is_admin middleware)
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
adminltev3/                        # AdminLTE v3.2.0 distribution (726 files: CSS, JS, plugins, example pages)
                                   #   Served via public/adminlte/ symlink; used by the admin panel at /admin
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
| GET | `/subjects/{subject:slug}` | `subjects.show` | `SubjectController@show` | public |
| GET | `/subjects/{subject:slug}/resources` | `subjects.resources.topics` | `ResourceMaterialController@topics` | **auth** |
| GET | `/subjects/{subject:slug}/resources/{topic:slug}` | `subjects.resources.show` | `ResourceMaterialController@show` | **auth** |
| GET | `/subjects/{subject:slug}/practice` | `subjects.practice.topics` | `PracticeController@topics` | **auth** |
| GET | `/subjects/{subject:slug}/lectures` | `subjects.lectures.topics` | `RecordedLectureController@topics` | **auth** |
| GET | `/subjects/{subject:slug}/lectures/{topic:slug}` | `subjects.lectures.show` | `RecordedLectureController@show` | **auth** |
| GET | `/subjects/{subject:slug}/{topic:slug}` | `topics.show` | `TopicController@show` | **auth** |
| GET | `/subjects/{subject:slug}/{topic:slug}/set-{setNumber}` | `sets.show` | `QuestionSetController@show` | **auth** |
| GET | `/mock-tests/{mockExam:slug}` | `mock-exams.show` | `MockExamController@show` | **auth** |

> Subjects, topics, sets, resource materials, recorded lectures, and taking a mock test require login (they sit inside a `Route::middleware('auth')` group). The landing page, mock-test listing, and static pages stay public.
>
> **Subject page is now public** — users can browse the 3-card hub (Resource Material, Practice Tests, Recorded Lectures) without logging in, but clicking into any of them redirects to login.
>
> **Mock test duality:** There are two parallel controller/model/Livewire stacks for mock exams — the original `MockExam`/`MockExamEngine` (wired to web routes) and a newer `MockTest`/`MockTestEngine` (not yet wired to web routes). Both target the same database tables (`mock_exams`/`mock_exam_questions`) via `$table` overrides. The active routes use `MockExamController` (views: `pages.mock-tests` / `pages.mock-test-show`).

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
- Active exam list cached for 30 min; `attempt_count` is incremented on **each view** of a test's detail page
- **Two parallel implementations**: `MockExamController`/`MockExamEngine` (active, wired to routes) and `MockTestController`/`MockTestEngine` (newer, same tables, not yet routed)
- Views: `pages.mock-tests` (listing) and `pages.mock-test-show` (detail, wraps the Livewire engine)

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

### 11. Admin Panel (AdminLTE v3)

A full admin dashboard at `/admin` built on **AdminLTE v3.2.0** (Bootstrap 4 + jQuery). Protected by `auth` + `is_admin` middleware — only users with `is_admin = true` can access it.

#### Route summary (`routes/admin.php`)

| Method | URI | Name | Purpose |
|--------|-----|------|---------|
| GET | `/admin` | `admin.dashboard` | Dashboard with stats cards |
| GET | `/admin/dashboard` | `admin.dashboard.alt` | Dashboard alias |
| — | `admin/subjects` | (resource) | Subjects CRUD (index, create, store, edit, update, destroy) |
| — | `admin/topics` | (resource) | Topics CRUD (index, create, store, edit, update, destroy) |
| — | `admin/question-sets` | (resource) | Question Sets CRUD |
| — | `admin/questions` | (resource) | Questions CRUD |
| — | `admin/mock-exams` | (resource) | Mock Exams CRUD |
| GET | `/admin/mock-exams/{mockExam}/questions` | `admin.mock-exam-questions.index` | List questions for a mock exam |
| POST | `/admin/mock-exams/{mockExam}/questions` | `admin.mock-exam-questions.store` | Create a question |
| POST | `/admin/mock-exams/{mockExam}/questions/bulk` | `admin.mock-exam-questions.bulk-store` | Bulk-add questions |
| DELETE | `/admin/mock-exams/{mockExam}/questions/{mockExamQuestion}` | `admin.mock-exam-questions.destroy` | Delete a question |
| PATCH | `/admin/mock-exams/{mockExam}/questions/{mockExamQuestion}/order` | `admin.mock-exam-questions.update-order` | Reorder a question |
| GET | `/admin/reports` | `admin.reports.index` | View all question reports |
| GET | `/admin/reports/{report}` | `admin.reports.show` | View a single report detail |
| PATCH | `/admin/reports/{report}/resolve` | `admin.reports.resolve` | Mark report as resolved |
| PATCH | `/admin/reports/{report}/dismiss` | `admin.reports.dismiss` | Dismiss report |
| PATCH | `/admin/reports/{report}/reopen` | `admin.reports.reopen` | Reopen a resolved/dismissed report |
| POST | `/admin/reports/bulk-resolve` | `admin.reports.bulk-resolve` | Bulk-resolve selected reports |
| POST | `/admin/reports/bulk-dismiss` | `admin.reports.bulk-dismiss` | Bulk-dismiss selected reports |

> **Note:** There is also an `Admin\MockTestController` that uses the `MockTest` model (same table `mock_exams`), but only `Admin\MockExamController` is wired in `routes/admin.php`. The admin mock-exam views are in `resources/views/admin/mock-tests/` (renamed directory).

#### Admin controllers

```
app/Http/Controllers/Admin/
├── DashboardController.php            # Stats: subjects, topics, question_sets, questions, mock_exams, mock_questions, pending_reports, total_users
├── SubjectController.php              # CRUD for subjects (resource)
├── TopicController.php                # CRUD for topics (resource)
├── QuestionSetController.php          # CRUD for question sets (resource)
├── QuestionController.php             # CRUD for questions (resource)
├── MockExamController.php             # CRUD for mock exams (resource, wired in routes)
├── MockExamQuestionController.php     # Questions manager nested under mock-exams (index, store, bulkStore, destroy, updateOrder)
├── MockTestController.php             # CRUD for mock tests using MockTest model (not wired to routes)
├── MockTestQuestionController.php     # Questions manager using MockTestQuestion (not wired to routes)
└── QuestionReportController.php       # Report viewer: index, show, resolve, dismiss, reopen, bulkResolve, bulkDismiss
```

#### Admin form requests

```
app/Http/Requests/Admin/
├── SubjectRequest.php       # Validates subject CRUD
├── TopicRequest.php         # Validates topic CRUD
├── QuestionSetRequest.php   # Validates question set CRUD
├── QuestionRequest.php      # Validates question CRUD
├── MockExamRequest.php      # Validates mock exam CRUD
└── MockTestRequest.php      # Validates mock test CRUD (unused via routes)
```

#### Admin views

```
resources/views/admin/
├── layouts/
│   └── app.blade.php              # AdminLTE layout: top navbar, sidebar tree, content wrapper, footer, JS/CSS includes
├── dashboard.blade.php            # Dashboard: 8 stat cards (subjects, topics, question_sets, questions, mock_exams, mock_questions, pending_reports, total_users) + Quick Actions card
├── subjects/                      # index.blade.php, create.blade.php, edit.blade.php
├── topics/                        # index.blade.php, create.blade.php, edit.blade.php (DataTables + Select2)
├── question-sets/                 # index.blade.php, create.blade.php, edit.blade.php (+ _form.blade.php partial)
├── questions/                     # index.blade.php, create.blade.php, edit.blade.php (+ _form.blade.php partial)
├── mock-tests/                    # index.blade.php, create.blade.php, edit.blade.php (+ _form.blade.php partial)
├── mock-test-questions/           # index.blade.php (DataTables listing, reorder, inline add)
└── reports/                       # index.blade.php, show.blade.php (status badge, resolve/dismiss/reopen buttons)
```

#### AdminLTE v3 folder (`adminltev3/`)

The root folder `adminltev3/` contains the AdminLTE v3.2.0 distribution (726 files: CSS, JS, plugins, example pages). Key details:

- **Source:** `adminltev3/dist/css/adminlte.min2167.css` (1.4MB, custom-numbered build) and `adminltev3/dist/js/adminlte.min2167.js`
- **Plugins used by the admin panel:** Font Awesome 5, jQuery 3, Bootstrap 4 Bundle, overlayScrollbars, Toastr, DataTables (BS4 + Responsive), Select2 (BS4 theme), SweetAlert2
- **Assets served via symlink:** `public/adminlte/` → `adminltev3/` (so `asset('adminlte/...')` resolves cleanly)
- **Layout:** `hold-transition sidebar-mini layout-fixed layout-navbar-fixed dark-mode` — sidebar with collapsible tree menus, top navbar with "View Site" link, error/success flash alerts with Toastr via `@if(session('toast_success'))`
- **Sidebar menu sections:** Dashboard, Content Management (Subjects, Topics, Question Sets, Questions, Mock Exams with dropdown trees), Reports & Users (Question Reports, All Users)
- The `adminltev3/pages/` directory contains ~85 reference HTML pages for various AdminLTE components (charts, forms, tables, UI elements, layout variants) — none are used directly by the Laravel app
- `adminltev3/admin.md` contains full documentation of the AdminLTE distribution structure

#### Admin middleware & access

- **`IsAdmin` middleware** (`app/Http/Middleware/IsAdmin.php`): Checks `auth()->user()->is_admin` — aborts with 403 if not an admin
- Applied to the entire `/admin` prefix group in `routes/admin.php` via `middleware(['web', 'auth', 'is_admin'])`
- The `is_admin` column was added to the users table via `2026_08_04_000001_add_is_admin_to_users_table.php` — defaults to `false`

### 12. Subject Resource Hub (August 2026)

Each subject detail page (`subjects/{slug}`) now presents a **three-card hub**:

| Card | Route | Description |
|------|-------|-------------|
| **Resource Material** | `subjects/{subject}/resources` | Browse study notes, PDFs, DOCX files organized by topic |
| **Practice Tests** | `subjects/{subject}/practice` | MCQ sets by topic — the original quiz experience |
| **Recorded Lectures** | `subjects/{subject}/lectures` | Watch video lectures (YouTube embeds) by topic |

The subject page itself is **public** (no auth required to view the hub), but all three cards redirect to login when clicked by unauthenticated users. Each card has a distinct gradient hover effect (blue, green, purple) with iconography.

#### Resource Material flow
1. Click "Resource Material" on subject page → `subjects.resources.topics`
2. **Always shows ALL topics** of the subject (no `whereHas` filter)
3. Each topic card shows a badge: `"{count} material(s)"` (blue) if resources exist, or `"No materials yet"` (gray) if empty
4. Click any topic → `subjects.resources.show` — lists individual materials with file type badges and "Open" buttons that open the file URL in a new tab

#### Recorded Lectures flow
1. Click "Recorded Lectures" on subject page → `subjects.lectures.topics`
2. **Always shows ALL topics** of the subject (same "no filtering" behavior as Resource Material)
3. Each topic card shows a badge: `"{count} lecture(s)"` (purple) or `"No lectures yet"` (gray)
4. Click any topic → `subjects.lectures.show` — lists individual lectures with YouTube embeds (responsive 16:9 iframe via `getEmbedUrl()`), platform badges, duration, and "Watch on YouTube" links

#### Practice Tests flow
1. Click "Practice Tests" on subject page → `subjects.practice.topics`
2. Shows only topics that have at least one active question set (`whereHas`)
3. Each topic card shows `"{count} set(s) available"` (green badge)
4. Click any topic → standard `topics.show` page with the question set grid

#### New models
- **`ResourceMaterial`** (`resource_materials` table): `topic_id`, `title`, `description`, `file_url`, `file_type` (pdf, docx, etc.), `sort_order`, `is_active`
- **`RecordedLecture`** (`recorded_lectures` table): `topic_id`, `title`, `description`, `video_url`, `platform` (youtube, etc.), `duration_minutes`, `sort_order`, `is_active`. Has `getEmbedUrl()` method that converts YouTube watch URLs to embeddable `https://www.youtube.com/embed/{id}` format.

#### New controllers
- **`ResourceMaterialController`**: `topics(Subject)` — lists all active topics with `resource_materials_count`; `show(Subject, Topic)` — lists active materials for the topic ordered by `sort_order`
- **`RecordedLectureController`**: `topics(Subject)` — lists all active topics with `recorded_lectures_count`; `show(Subject, Topic)` — lists active lectures for the topic ordered by `sort_order`
- **`PracticeController`**: `topics(Subject)` — lists topics that have at least one active question set with `question_sets_count`

### 13. Auth Page Fixes (August 2026)

- **Zoom/clipping fix**: Face panels changed from `h-full` to `min-h-0` with `sticky top-0` hero panels so the login/register card never clips at 100% browser zoom; left form panels scroll independently
- **Flip card Alpine.js fix**: `app.js` now imports Alpine directly and starts it as a fallback on non-Livewire pages (auth layout) after a 100ms grace period. Previously, Alpine directives (`x-data`, `:class`, `@click`) were inert on auth pages because Livewire's `livewire:init` event never fired there, so `Alpine.start()` was never called. The fix uses a `let alpineStarted = false` guard to ensure only one instance starts — Livewire's bundled Alpine on Livewire pages, or the imported Alpine on static/auth pages

---

## Models & Schema

### Core Quiz Data

```
Subject (id, name, slug, description, icon_svg, color_class, sort_order, is_active)
  └── Topic (id, subject_id, name, slug, description, sort_order, is_active)
        ├── QuestionSet (id, topic_id, name, slug, set_number, question_count, is_active)
        │     └── Question (id, question_set_id, qid, question, option_a/b/c/d, correct_option, explanation, sort_order, is_active)
        ├── ResourceMaterial (id, topic_id, title, description, file_url, file_type, sort_order, is_active)
        └── RecordedLecture (id, topic_id, title, description, video_url, platform, duration_minutes, sort_order, is_active)
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
User (id, name, email, email_verified_at, password, remember_token, intent [lat|jobs|browsing], theme_preference, total_streak, last_activity_date, is_admin [boolean, default false], timestamps)

The extended columns (`intent`, `theme_preference`, `total_streak`, `last_activity_date`) are now defined directly in the base `0001_01_01_000000_create_users_table` migration **and** in `2026_08_01_000001_add_extended_columns_to_users_table` (idempotent via `hasColumn` guards), so `php artisan migrate` runs cleanly. The `is_admin` boolean column was added by `2026_08_04_000001_add_is_admin_to_users_table.php` (defaults to `false`). The old conflicting migration `2024_01_01_000012_create_users_table` was removed. Registration writes `name`, `email`, and `password`; streak/theme data persists through the extended columns.
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
'contact' => [ 'address', 'phone_primary', 'phone_secondary', 'email', 'whatsapp', 'youtube' ]
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
php artisan storage:link       # create public/storage → storage/app/public symlink
npm run dev                    # Vite dev server (or `npm run build` for production)
php artisan serve              # Laravel dev server
```

> **AdminLTE assets:** The admin panel at `/admin` depends on the `public/adminlte/` symlink. If it doesn't exist, create it:
> ```bash
> # Windows (PowerShell, as Administrator):
> New-Item -ItemType SymbolicLink -Path "public/adminlte" -Target "..\adminltev3"
> # Linux/macOS:
> ln -s ../adminltev3 public/adminlte
> ```
> The admin panel also requires setting a user's `is_admin` to `true` in the database, e.g. via `php artisan tinker`:
> ```php
> \App\Models\User::where('email', 'your@email.com')->update(['is_admin' => true]);
> ```

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

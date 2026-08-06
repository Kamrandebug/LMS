# AdminLTE v3.2.0 - Complete Documentation

## Overview
AdminLTE is a popular open-source admin dashboard template built on **Bootstrap 4**, jQuery, and modern web technologies. This folder (`K:\LMS\adminltev3\`) contains a complete, production-ready implementation of AdminLTE v3.2.0 with all components, plugins, and example pages.

**Version**: 3.2.0  
**License**: MIT (Colorlib)  
**Base Framework**: Bootstrap 4.6  
**JavaScript**: jQuery 3.x + Vanilla JS modules  
**CSS**: Custom AdminLTE theme (1.4MB minified)

---

## Folder Structure

```
K:\LMS\adminltev3\
├── dist/                      # Distribution files (production-ready)
│   ├── css/
│   │   └── adminlte.min2167.css    # Main compiled CSS (1.4MB)
│   ├── img/                    # Images, logos, avatars, placeholders
│   │   ├── AdminLTELogo.png
│   │   ├── avatar*.png
│   │   ├── user*-128x128.jpg
│   │   ├── default-150x150.png
│   │   ├── photo*.jpg
│   │   ├── prod-*.jpg
│   │   └── credit/             # Payment icons (visa, mastercard, paypal, amex)
│   └── js/
│       ├── adminlte.min2167.js   # Minified core JS (46KB)
│       ├── adminlte2167.js       # Unminified core JS (104KB) - readable source
│       ├── demo.js               # Demo-specific JS
│       └── pages/                # Page-specific JS
│           ├── dashboard.js
│           ├── dashboard2.js
│           └── dashboard3.js
├── pages/                       # All HTML example pages (55+ pages)
│   ├── index3.html              # Alternative dashboard
│   ├── calendar.html            # FullCalendar integration
│   ├── gallery.html             # Image gallery with Filterizr
│   ├── kanban.html              # Kanban board (bs-stepper)
│   ├── widgets.html             # Dashboard widgets showcase
│   ├── charts/                  # Charting examples (4 pages)
│   │   ├── chartjs.html         # Chart.js examples
│   │   ├── flot.html            # Flot charts
│   │   ├── inline.html          # Inline charts (sparklines)
│   │   └── uplot.html           # uPlot fast charts
│   ├── examples/                # Real-world page templates (26 pages)
│   │   ├── 404.html, 500.html   # Error pages
│   │   ├── blank.html           # Starter blank page
│   │   ├── contacts.html        # Contact list
│   │   ├── contact-us.html      # Contact form
│   │   ├── e-commerce.html      # E-commerce dashboard
│   │   ├── faq.html             # FAQ accordion
│   │   ├── invoice.html         # Invoice view
│   │   ├── invoice-print.html   # Print-optimized invoice
│   │   ├── profile.html         # User profile
│   │   ├── projects.html        # Project management
│   │   ├── project-add.html     # Add project form
│   │   ├── project-edit.html    # Edit project form
│   │   ├── project-detail.html  # Project detail view
│   │   ├── lockscreen.html      # Lock screen
│   │   ├── pace.html            # Pace loader demo
│   │   ├── login.html, login-v2.html       # Login forms (2 variants)
│   │   ├── register.html, register-v2.html # Registration forms (2 variants)
│   │   ├── forgot-password.html, forgot-password-v2.html
│   │   ├── recover-password.html, recover-password-v2.html
│   │   ├── language-menu.html   # Language selector
│   │   └── legacy-user-menu.html # Legacy user dropdown
│   ├── forms/                   # Form examples (4 pages)
│   │   ├── general.html         # Basic form elements
│   │   ├── advanced.html        # Advanced elements (select2, datepicker, etc.)
│   │   ├── editors.html         # WYSIWYG editors (Summernote, CodeMirror, SimpleMDE)
│   │   └── validation.html      # jQuery Validation examples
│   ├── layout/                  # Layout variations (9 pages)
│   │   ├── boxed.html           # Boxed layout
│   │   ├── collapsed-sidebar.html
│   │   ├── fixed-footer.html
│   │   ├── fixed-sidebar.html
│   │   ├── fixed-sidebar-custom.html
│   │   ├── fixed-topnav.html
│   │   ├── top-nav.html         # Top navigation only
│   │   ├── top-nav-sidebar.html # Top nav + sidebar
│   │   └── index3.html          # Dashboard v3
│   ├── mailbox/                 # Email app (3 pages)
│   │   ├── mailbox.html         # Inbox list
│   │   ├── compose.html         # Compose email
│   │   └── read-mail.html       # Read email
│   ├── search/                  # Search pages (2 pages)
│   │   ├── simple.html
│   │   └── enhanced.html
│   ├── tables/                  # Table examples (3 pages)
│   │   ├── simple.html          # Basic tables
│   │   ├── data.html            # DataTables (sorting, paging, search)
│   │   └── jsgrid.html          # jsGrid editable grid
│   └── UI/                      # UI Components (8 pages)
│       ├── general.html         # Typography, alerts, badges, cards
│       ├── icons.html           # Font Awesome icons reference
│       ├── buttons.html         # Button variants, groups, dropdowns
│       ├── modals.html          # Modals, sweetalert2, toastr
│       ├── navbar.html          # Navbar, tabs, pills
│       ├── ribbons.html         # Corner ribbons
│       ├── sliders.html         # Ion.RangeSlider, bootstrap-slider
│       └── timeline.html        # Vertical/horizontal timelines
└── plugins/                     # Third-party libraries (48 plugins)
    ├── bootstrap/               # Bootstrap 4.6 bundle
    ├── bootstrap4-duallistbox/  # Dual listbox
    ├── bootstrap-colorpicker/   # Color picker
    ├── bootstrap-slider/        # Range slider
    ├── bootstrap-switch/        # Toggle switches
    ├── bs-custom-file-input/    # Custom file input
    ├── bs-stepper/              # Stepper/wizard component
    ├── chart.js/                # Chart.js v3+
    ├── codemirror/              # Code editor
    ├── datatables/              # DataTables core
    ├── datatables-bs4/          # DataTables BS4 styling
    ├── datatables-buttons/      # Export buttons (Excel, PDF, etc.)
    ├── datatables-responsive/   # Responsive tables
    ├── daterangepicker/         # Date range picker
    ├── dropzone/                # Drag-drop file upload
    ├── ekko-lightbox/           # Lightbox gallery
    ├── filterizr/               # Filterable gallery
    ├── flag-icon-css/           # Country flags
    ├── flot/                    # Flot charts
    ├── fontawesome-free/        # Font Awesome 5 Free
    ├── fullcalendar/            # FullCalendar scheduler
    ├── icheck-bootstrap/        # iCheck styled checkboxes/radio
    ├── inputmask/               # Input masking
    ├── ion-rangeslider/         # Range slider
    ├── jquery/                  # jQuery 3.x
    ├── jquery-knob/             # Knob/dial inputs
    ├── jquery-mapael/           # Vector maps
    ├── jquery-mousewheel/       # Mouse wheel support
    ├── jquery-ui/               # jQuery UI widgets
    ├── jquery-validation/       # Form validation
    ├── jqvmap/                  # jQuery Vector Maps
    ├── jsgrid/                  # jsGrid data grid
    ├── jszip/                   # JSZip for exports
    ├── moment/                  # Date/time parsing
    ├── overlayScrollbars/       # Custom scrollbars
    ├── pace-progress/           # Page load progress bar
    ├── pdfmake/                 # PDF generation
    ├── raphael/                 # Vector graphics (for maps)
    ├── select2/                 # Enhanced select boxes
    ├── select2-bootstrap4-theme/# Select2 BS4 theme
    ├── simplemde/               # Markdown editor
    ├── sparklines/              # Inline sparkline charts
    ├── summernote/              # WYSIWYG editor
    ├── sweetalert2/             # Beautiful alerts
    ├── sweetalert2-theme-bootstrap-4/
    ├── tempusdominus-bootstrap-4/# Date/time picker
    ├── toastr/                  # Toast notifications
    └── uplot/                   # uPlot fast charts
```

---

## Core Features

### 1. **Layout System**
- **Fixed/Static Sidebar**: Collapsible, fixed, or static positioning
- **Top Navigation**: Fixed or static top navbar
- **Boxed Layout**: Content constrained to max-width
- **Dark Mode**: Built-in `dark-mode` class support
- **Control Sidebar**: Right-side sliding panel for settings
- **Responsive**: Mobile-first, sidebar collapses on small screens

**Body Classes for Layout Control:**
```html
<!-- Sidebar variants -->
<body class="sidebar-mini">              <!-- Mini sidebar (icons only) -->
<body class="sidebar-collapse">          <!-- Collapsed sidebar -->
<body class="layout-fixed">              <!-- Fixed sidebar + navbar -->
<body class="layout-navbar-fixed">       <!-- Fixed navbar -->
<body class="layout-footer-fixed">       <!-- Fixed footer -->
<body class="layout-top-nav">            <!-- Top nav only (no sidebar) -->
<body class="layout-boxed">              <!-- Boxed container -->
<body class="dark-mode">                 <!-- Dark theme -->
```

### 2. **Navigation & Menu System**
- **Multi-level Sidebar Menu**: Unlimited nesting with treeview
- **Accordion/Collapse**: Auto-collapse other branches
- **Badges/Labels**: New, count, status indicators
- **Search in Sidebar**: Real-time menu filtering
- **User Panel**: Avatar, name, status in sidebar header

### 3. **Card System (Core Component)**
AdminLTE's primary content container with built-in tools:
```html
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Title</h3>
    <div class="card-tools">
      <!-- Buttons: collapse, remove, maximize, custom -->
      <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
      <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
      <button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i></button>
    </div>
  </div>
  <div class="card-body">Content</div>
  <div class="card-footer">Footer</div>
</div>
```

**Card Widgets (JS API):**
- `collapse` - Toggle card body visibility
- `remove` - Remove card from DOM
- `maximize` - Fullscreen card
- `card-refresh` - AJAX reload card content

### 4. **UI Components**
| Component | Description | Page |
|-----------|-------------|------|
| **Buttons** | 12 styles, sizes, groups, dropdowns, loading states | `UI/buttons.html` |
| **Modals** | Standard, large, small, sweetalert2, toastr toasts | `UI/modals.html` |
| **Navbars/Tabs** | Top nav, vertical tabs, pills, justified | `UI/navbar.html` |
| **Timeline** | Vertical, horizontal, centered variations | `UI/timeline.html` |
| **Ribbons** | Corner ribbons (12 positions, 8 colors) | `UI/ribbons.html` |
| **Sliders** | Ion.RangeSlider, bootstrap-slider | `UI/sliders.html` |
| **Icons** | Font Awesome 5 (1000+ icons) | `UI/icons.html` |
| **Typography** | Headings, text utilities, blockquotes, code | `UI/general.html` |

### 5. **Form System**
**Basic Elements** (`forms/general.html`):
- Input groups, checkboxes, radios, switches (bootstrap-switch)
- Select2 (searchable, multi-select, tags, remote data)
- Date/time pickers (tempusdominus, daterangepicker)
- Color picker (bootstrap-colorpicker)
- Input masks (phone, currency, date, custom)
- File upload (bs-custom-file-input, dropzone)

**Advanced** (`forms/advanced.html`):
- Dual listbox (bootstrap4-duallistbox)
- Stepper/wizard (bs-stepper)
- Range sliders (ion-rangeslider, bootstrap-slider)
- jQuery Knob (dial inputs)
- Tags input

**WYSIWYG Editors** (`forms/editors.html`):
- **Summernote** - Full-featured editor with toolbar
- **CodeMirror** - Code editor with syntax highlighting
- **SimpleMDE** - Markdown editor with live preview

**Validation** (`forms/validation.html`):
- jQuery Validation plugin
- Custom rules, messages, error placement
- Server-side validation simulation
- Icon feedback (success/error states)

### 6. **Table System**
| Type | Features | Page |
|------|----------|------|
| **Simple** | Bootstrap tables, striped, bordered, hover | `tables/simple.html` |
| **DataTables** | Sort, filter, paginate, export (PDF/Excel/CSV), responsive, fixed columns, AJAX source | `tables/data.html` |
| **jsGrid** | Inline editing, sorting, filtering, paging, custom renderers | `tables/jsgrid.html` |

**DataTables Extensions Included:**
- Buttons (copy, CSV, Excel, PDF, print, colvis)
- Responsive (column hiding on mobile)
- BS4 styling integration
- Row grouping, child rows

### 7. **Charts & Visualization**
Four charting libraries integrated:

| Library | Use Case | Page |
|---------|----------|------|
| **Chart.js** | General purpose, responsive, animated | `charts/chartjs.html` |
| **Flot** | Legacy, interactive, plot plugins | `charts/flot.html` |
| **Inline/Sparklines** | Tiny inline charts in cards/tables | `charts/inline.html` |
| **uPlot** | High-performance, large datasets | `charts/uplot.html` |

**Chart Types Available:**
- Line, Bar, Pie, Doughnut, Radar, Polar Area
- Scatter, Bubble, Mixed charts
- Real-time updates, annotations
- Export to image

### 8. **Complete Application Pages**

#### Dashboard Variants (3 versions):
- **Dashboard v1** (`index2.html`) - Info boxes, charts, direct chat, orders, products
- **Dashboard v2** - Sales charts, world map, browser stats, recent members
- **Dashboard v3** (`pages/layout/index3.html`) - Alternative layout

#### Mailbox App (3 pages):
- Inbox with pagination, star, checkbox actions
- Compose with Summernote editor, attachments
- Read view with thread, reply/forward

#### Calendar (`calendar.html`):
- FullCalendar integration
- Month/Week/Day views
- Event drag-drop, click to edit
- Color-coded event categories

#### Kanban Board (`kanban.html`):
- Drag-drop columns (bs-stepper)
- Task cards with labels, due dates, assignees
- Add/edit/delete tasks

#### E-Commerce (`examples/e-commerce.html`):
- Revenue stats, recent orders, top products
- Sales chart, visitor map
- Product grid with actions

#### Project Management (`examples/projects.html`):
- Project cards with progress, team, deadline
- Project detail, add, edit pages
- Kanban-style task boards

#### Authentication Pages (8 variants):
| Page | v1 | v2 |
|------|-----|-----|
| Login | `login.html` | `login-v2.html` |
| Register | `register.html` | `register-v2.html` |
| Forgot Password | `forgot-password.html` | `forgot-password-v2.html` |
| Recover Password | `recover-password.html` | `recover-password-v2.html` |
| Lockscreen | `lockscreen.html` | - |

#### Other Pages:
- **Invoice** (`invoice.html`) + **Print** (`invoice-print.html`)
- **Profile** (`profile.html`) - Tabs: timeline, settings, activity
- **Contacts** (`contacts.html`) - Searchable, filterable cards
- **FAQ** (`faq.html`) - Accordion style
- **404/500** - Error pages with illustrations
- **Blank** (`blank.html`) - Starter template
- **Pace** (`pace.html`) - Page load progress demo
- **Gallery** (`gallery.html`) - Filterizr masonry gallery

### 9. **JavaScript Core Modules** (`dist/js/adminlte2167.js`)

Each module follows jQuery plugin pattern with data-api:

| Module | Selector | Features |
|--------|----------|----------|
| **CardRefresh** | `[data-card-widget="card-refresh"]` | AJAX reload, overlay, error handling |
| **CardWidget** | `[data-card-widget="collapse/remove/maximize"]` | Collapse, remove, fullscreen cards |
| **ControlSidebar** | `[data-widget="control-sidebar"]` | Slide-in right panel, toggle/fix |
| **DirectChat** | `[data-widget="chat-pane-toggle"]` | Chat widget with contacts pane |
| **Dropdown** | - | Enhanced Bootstrap dropdowns |
| **ExpandableTable** | `.expandable-table` | Row expansion with detail content |
| **Fullscreen** | `[data-widget="fullscreen"]` | Browser fullscreen API |
| **IFrame** | `[data-widget="iframe"]` | Tabbed iframe navigation |
| **Layout** | `body` | Sidebar toggle, pushmenu, fix layout |
| **NavbarSearch** | `[data-widget="navbar-search"]` | Expanding search in navbar |
| **PushMenu** | `[data-widget="pushmenu"]` | Sidebar open/close, overlay on mobile |
| **SidebarSearch** | `[data-widget="sidebar-search"]` | Real-time menu filtering |
| **TodoList** | `[data-widget="todo-list"]` | Checkable todo with progress |

**Initialization** (auto via data-api or manual):
```javascript
// Auto-init on DOM ready (in demo.js)
$(function () {
  $('[data-widget="pushmenu"]').PushMenu()
  $('[data-widget="control-sidebar"]').ControlSidebar()
  $('[data-widget="card-refresh"]').CardRefresh()
  // ... etc
})

// Manual
$('#myCard').CardWidget({ animationSpeed: 300 })
$('#sidebar').PushMenu('toggle')
```

### 10. **Utility Classes**

**Spacing** (Bootstrap 4 utilities + AdminLTE extras):
- `.m-0` to `.m-5`, `.p-0` to `.p-5` (margin/padding)
- `.mt-`, `.mb-`, `.ml-`, `.mr-`, `.mx-`, `.my-`
- Responsive: `.mt-md-3`, `.p-lg-4`

**Colors** (CSS variables + classes):
```css
:root {
  --primary: #007bff;
  --secondary: #6c757d;
  --success: #28a745;
  --info: #17a2b8;
  --warning: #ffc107;
  --danger: #dc3545;
  --light: #f8f9fa;
  --dark: #343a40;
  --sidebar-dark: #222d32;
}
```
- Text: `.text-primary`, `.text-success`, etc.
- Background: `.bg-primary`, `.bg-gradient-primary`, etc.
- Borders: `.border-primary`, `.border-left-primary` (thick left border)

**Elevation/Shadows**: `.elevation-0` to `.elevation-4`

**Image Helpers**: `.img-circle`, `.img-fluid`, `.img-size-50`, `.img-size-64`, etc.

**Typography**: `.text-sm`, `.text-bold`, `.text-uppercase`, `.font-weight-light`

---

## Required Dependencies (Load Order)

```html
<!-- 1. CSS -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700">
<link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
<link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
<link rel="stylesheet" href="dist/css/adminlte.min2167.css">

<!-- 2. JS (before </body>) -->
<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<script src="dist/js/adminlte2167.js"></script>

<!-- 3. Page-specific plugins (as needed) -->
<!-- ChartJS -->
<script src="plugins/chart.js/Chart.min.js"></script>
<!-- DataTables -->
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<!-- FullCalendar -->
<script src="plugins/fullcalendar/main.js"></script>
<!-- etc. -->
```

---

## Key Files to Understand

| File | Purpose |
|------|---------|
| `dist/js/adminlte2167.js` | **Read this** - All core modules, well-commented, 104KB |
| `dist/css/adminlte.min2167.css` | Compiled styles (use dev tools to inspect) |
| `index2.html` | Reference implementation - shows all components together |
| `pages/examples/blank.html` | Minimal starter template |
| `dist/js/demo.js` | Demo initialization, chart configs, sample data |

---

## Customization Guide

### 1. **CSS Variables** (in `adminlte.min.css` or override):
```css
:root {
  --primary: #your-brand-color;
  --sidebar-width: 250px;
  --sidebar-mini-width: 4.6rem;
  --navbar-height: 3.5rem;
  --footer-height: 3rem;
}
```

### 2. **Layout Classes** (apply to `<body>`):
```html
<!-- Most common production setup -->
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed dark-mode">
```

### 3. **Sidebar Menu** - Edit the `<nav class="mt-2">` in `aside.main-sidebar`

### 4. **Card Tools** - Customize in card header:
```html
<div class="card-tools">
  <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
    <i class="fas fa-minus"></i>
  </button>
  <button type="button" class="btn btn-tool" data-card-widget="remove" data-toggle="tooltip" title="Remove">
    <i class="fas fa-times"></i>
  </button>
</div>
```

---

## Browser Support
- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- IE 11+ (with polyfills)

---

## Integration Notes for LMS

Since this is in `K:\LMS\adminltev3\`, you can:

1. **Use as Reference**: Copy components to your LMS views
2. **Direct Integration**: Point your server to serve these static files
3. **Asset Pipeline**: Copy `dist/` to your public assets, reference in layouts
4. **Plugin Selection**: Only include plugins you need (see `plugins/` folder)

**Recommended Minimal Setup for LMS:**
```
Your LMS/
├── public/
│   ├── css/
│   │   ├── adminlte.min.css      (from dist/css)
│   │   └── your-custom.css
│   ├── js/
│   │   ├── adminlte.min.js       (from dist/js)
│   │   └── your-app.js
│   ├── plugins/                   (only needed plugins)
│   │   ├── fontawesome-free/
│   │   ├── overlayScrollbars/
│   │   ├── jquery/
│   │   └── bootstrap/
│   └── img/                       (from dist/img)
└── views/
    └── layouts/
        └── admin.blade.php        (or .php/.twig/.cshtml)
```

---

## License & Credits

- **AdminLTE**: MIT License - Copyright 2014-2022 Colorlib
- **Bootstrap**: MIT License
- **jQuery**: MIT License
- **Font Awesome**: CC BY 4.0 / SIL OFL 1.1
- **All Plugins**: Respective licenses (mostly MIT/GPL)

**Official Site**: https://adminlte.io  
**Documentation**: https://adminlte.io/docs/3.1/  
**GitHub**: https://github.com/ColorlibHQ/AdminLTE

---

*This documentation covers the complete `K:\LMS\adminltev3\` folder as of v3.2.0. Use this as a reference for understanding available components, pages, and integration patterns for your LMS project.*
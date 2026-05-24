# SE Portfolio

A production-ready, one-page developer portfolio plugin for WordPress with a terminal / GitHub dark aesthetic.

**Version:** 1.1.0 | **Requires WordPress:** 6.4+ | **Requires PHP:** 8.1+

---

## Table of Contents

1. [Installation](#installation)
2. [Quick Start](#quick-start)
3. [Admin Configuration](#admin-configuration)
   - [About Me](#about-me-settings)
   - [Projects](#projects)
   - [Skills](#skills)
   - [Experience](#experience)
   - [Education](#education)
   - [Certificates](#certificates)
   - [Style Settings](#style-settings)
4. [Shortcodes](#shortcodes)
5. [Customizing the Style](#customizing-the-style)
   - [Style Settings (GUI)](#style-settings-gui)
   - [CSS Custom Properties](#css-custom-properties)
   - [Custom CSS Field](#custom-css-field)
   - [Typography](#typography)
6. [REST API](#rest-api)
7. [Uninstallation](#uninstallation)

---

## Installation

### Via WordPress Admin

1. Download the `se-portfolio` folder.
2. In your WordPress admin go to **Plugins → Add New → Upload Plugin**.
3. Upload the zip file and click **Install Now**.
4. Click **Activate Plugin**.

### Manual (FTP / SSH)

1. Copy the `se-portfolio` folder into `wp-content/plugins/`.
2. In WordPress admin go to **Plugins** and activate **SE Portfolio**.

### Via WP-CLI

```bash
wp plugin install /path/to/se-portfolio.zip --activate
```

---

## Quick Start

After activation, the plugin adds a **SE Portfolio** menu item to the WordPress admin sidebar.

**Minimal setup in 3 steps:**

1. Go to **SE Portfolio → About Me** and fill in your name, job title, and bio.
2. Go to **SE Portfolio → Projects** and add at least one project.
3. Create a new WordPress Page (e.g. *Portfolio*), set it as the site's front page if desired, and add the shortcode:

```
[sep_portfolio]
```

That single shortcode renders the entire one-page portfolio including navigation, hero, about, skills, experience, projects, education, certificates, contact, and footer.

> The plugin automatically hides the active theme's header, footer, and page title on any page that contains a portfolio shortcode, so the portfolio renders full-width with no theme interference.

---

## Admin Configuration

### About Me Settings

**SE Portfolio → About Me** controls the hero, about, and contact sections.

#### Profile

| Field | Description |
|---|---|
| Profile Photo | Select an image from the Media Library. Displayed in the hero and About sections. |
| Full Name | Your name. Shown in the hero, about, nav logo, and footer. |
| Job Title | Your role/title. Shown in the hero with a blinking cursor animation. |
| Short Bio | Brief HTML bio shown in the hero terminal window. Supports basic HTML. |
| Long Bio | Full bio shown in the About section. Supports basic HTML. |
| Location | City / country. Shown in the About stats and contact cards. |
| Years of Experience | Integer. Powers the "Years Experience" stat card. |
| Available for Work | Checkbox. Shows a pulsing green "Available for opportunities" badge. |

#### Contact & Links

| Field | Description |
|---|---|
| Email | Used in the hero "Contact Me" button, the contact cards, and footer Connect column. |
| Phone | Optional. Shown as a contact card with a `tel:` link. |
| GitHub URL | Full URL (e.g. `https://github.com/yourusername`). Shown in About and contact cards. |
| LinkedIn URL | Full URL. Shown in About and contact cards. |
| CV / Resume URL | Link to your CV file or hosted resume. |

#### Display Options

| Field | Description |
|---|---|
| Show "Hire Me" button | Adds a highlighted CTA button to the top navigation bar. |
| Show "Download CV" button | Shows the Download CV button in the hero and footer. |
| Show Contact section | Enables/disables the full Contact section at the bottom of the page. |

#### Contact Section

| Field | Description |
|---|---|
| Contact Heading | Override the default "Get In Touch" heading. |
| Contact Intro Text | Paragraph of text shown above the contact cards. |

---

### Projects

**SE Portfolio → Projects** — each project is a custom post type entry.

**Title field** = Project name.

**Meta box fields:**

| Field | Description |
|---|---|
| Short Description | One-line summary shown on the project card. |
| Technologies | Comma-separated list (e.g. `React, Node.js, PostgreSQL`). Rendered as tag chips. |
| Project URL | Link to the live demo. |
| GitHub URL | Link to the source repository. |
| Status | `In Progress`, `Completed`, or `Archived`. Used by the filter tabs on the projects section. |
| Start Date | `YYYY-MM-DD` format. |
| End Date | `YYYY-MM-DD` format. Leave blank if ongoing. |
| Featured | Checkbox. Allows filtering by featured projects via the `[sep_projects featured="true"]` shortcode. |
| Featured Image | Set via the standard WordPress Featured Image panel. Shown as a card thumbnail. |

---

### Skills

**SE Portfolio → Skills** — each skill is a custom post type entry.

**Title field** = Skill name (e.g. `TypeScript`, `Docker`).

**Meta box fields:**

| Field | Description |
|---|---|
| Category | Groups skills together. Options: `Frontend`, `Backend`, `DevOps`, `Database`, `Tools`, `Soft Skills`. |
| Proficiency (0–100) | Used only to determine the chip colour — not shown as a number. `≥ 80` → green, `≥ 60` → blue, `< 60` → muted. |
| Icon | Text icon or emoji (optional, not currently rendered in the default template). |
| Years of Experience | Exposed via the REST API; not shown in the default layout. |
| Display Order | Integer. Lower numbers appear first within a category. |

---

### Experience

**SE Portfolio → Experience** — each entry is a job/role.

**Title field** = Job title (e.g. `Senior Backend Engineer`).

**Meta box fields:**

| Field | Description |
|---|---|
| Company | Employer name. |
| Company URL | Optional link wrapping the company name. |
| Employment Type | `Full-time`, `Part-time`, `Contract`, `Freelance`, or `Internship`. |
| Start Date | `YYYY-MM-DD` format. |
| End Date | `YYYY-MM-DD` format. |
| Currently Working Here | Checkbox. Shows "Present" instead of an end date. |
| Location | City, country, or "Remote". |
| Technologies Used | Comma-separated list. Rendered as tag chips. |
| Display Order | Integer. Lower numbers appear first. |

**Post content** = Job description / responsibilities (rendered below the meta).

---

### Education

**SE Portfolio → Education** — each entry is a degree or course.

**Title field** = Institution name (e.g. `Cairo University`).

**Meta box fields:**

| Field | Description |
|---|---|
| Degree | e.g. `Bachelor of Science`. |
| Field of Study | e.g. `Computer Science`. |
| Institution URL | Optional link wrapping the institution name. |
| Start Year | Four-digit year. |
| End Year | Four-digit year. |
| Currently Studying | Checkbox. Shows "Present" instead of an end year. |
| Grade / GPA | Optional. Shown inline with the dates. |
| Description | Short paragraph about the degree or relevant coursework. |
| Display Order | Integer. Lower numbers appear first. |

---

### Certificates

**SE Portfolio → Certificates** — each entry is a certification.

**Title field** = Certificate name (e.g. `AWS Solutions Architect`).

**Meta box fields:**

| Field | Description |
|---|---|
| Issuing Organisation | e.g. `Amazon Web Services`. |
| Issue Date | `YYYY-MM-DD` format. |
| Expiry Date | `YYYY-MM-DD` format. Leave blank if no expiry. |
| No Expiry Date | Checkbox. Hides the expiry field and shows a "No Expiry" badge. |
| Credential ID | Optional. Shown on the certificate card. |
| Credential URL | Link to verify the certificate online. Rendered as a "Verify Credential" button. |
| Certificate Image | Media Library image. Shown at the top of the certificate card. |
| Skills Covered | Comma-separated list. Rendered as tag chips. |
| Display Order | Integer. Lower numbers appear first. |

---

### Style Settings

**SE Portfolio → Style Settings** controls the entire visual design of the portfolio — no CSS knowledge required.

#### Global Colors

10 color pickers for the full palette:

| Field | CSS Variable | Description |
|---|---|---|
| Background | `--sep-bg` | Page background |
| Surface | `--sep-surface` | Alternate section background |
| Surface 2 | `--sep-surface2` | Card and stat background |
| Border | `--sep-border` | All borders and dividers |
| Accent | `--sep-accent` | Buttons, active links, section headings |
| Green | `--sep-green` | Skill chip and badge colour |
| Text | `--sep-text` | Primary body text |
| Muted | `--sep-muted` | Secondary / helper text |
| Prompt | `--sep-prompt` | Terminal prompt `$` colour |
| Warning | `--sep-warning` | "In Progress" status badge |

Use the **Preset** dropdown at the top of the page to apply a full palette in one click:

| Preset | Character |
|---|---|
| GitHub Dark | Deep navy, green accent — the default |
| Ocean Blue | Dark navy, blue accent |
| Dracula | Dark purple, lavender accent |
| Nord | Arctic blue-grey, cyan accent |
| Purple Haze | Deep purple, violet accent |
| Solarized Dark | Classic solarized palette |

#### Typography

| Field | CSS Variable | Default |
|---|---|---|
| Monospace font | `--sep-font-mono` | JetBrains Mono, Fira Code, Cascadia Code |
| Body font | `--sep-font-body` | JetBrains Mono, Fira Code, Cascadia Code |
| Base font size | `--sep-base-size` | `15px` |
| Hero name size | `--sep-hero-name-size` | `clamp(1.6rem, 4vw, 2.4rem)` |

#### Spacing & Layout

| Field | CSS Variable | Default |
|---|---|---|
| Section vertical padding | `--sep-section-py` | `80px` |
| Card padding | `--sep-card-pad` | `16px` |
| Hero top padding | `--sep-hero-pt` | `160px` |
| Container max-width | `--sep-container-max` | `1100px` |
| Border radius | `--sep-radius` | `3px` |
| Transition speed | `--sep-transition` | `0.15s` |

#### Effects & Card Style

| Toggle | What it controls |
|---|---|
| Glow Effects | Box-shadow glows on card hover |
| Scanlines | CRT scanline overlay on the hero section |
| Animations | All CSS keyframe animations (skill bar fill, fade-ins) |
| Cursor Blink | The terminal blinking cursor on the hero job title |

**Card Style:**
- **Terminal** — card has a chrome header bar (macOS-style traffic-light dots) above the content.
- **Flat** — clean card with no chrome bar; a simple top border instead.

#### Component Overrides

Each of the 9 portfolio sections (Hero, About, Skills, Projects, Experience, Education, Certificates, Contact, Footer) has 6 optional color pickers: Background, Surface, Accent, Border, Text, Muted. Leave a field empty to inherit the global value. Sections with at least one override are expanded automatically.

#### Custom CSS

A freeform CSS textarea injected after all plugin styles — your rules always win. A built-in **CSS Reference** collapsible panel (no JS required) lists:
- Every `--sep-*` variable with its current live value
- CSS selectors for each portfolio section
- 5 copy-paste example snippets

#### Reset to Defaults

The **Reset to Defaults** button at the bottom of the page restores all settings to the GitHub Dark preset values. A confirmation dialog prevents accidental resets.

---

## Shortcodes

### `[sep_portfolio]` — Full one-page portfolio

Renders the complete portfolio: top nav, section dot nav, hero, about, skills, experience, projects, education, certificates, contact (if enabled), and footer. **This is the only shortcode most users need.**

---

### `[sep_about]`

Renders just the About Me section (bio, stats, social buttons).

---

### `[sep_projects]`

Renders the projects grid with filter tabs.

| Attribute | Default | Description |
|---|---|---|
| `limit` | `12` | Maximum number of projects to show. |
| `status` | *(all)* | Filter by status: `completed`, `in-progress`, or `archived`. |
| `featured` | *(all)* | Set to `true` to show only featured projects. |

```
[sep_projects limit="6" status="completed"]
[sep_projects featured="true"]
```

---

### `[sep_skills]`

Renders the skills chip grid.

| Attribute | Default | Description |
|---|---|---|
| `category` | *(all)* | Filter by category: `frontend`, `backend`, `devops`, `database`, `tools`, or `soft-skills`. |

```
[sep_skills category="frontend"]
```

---

### `[sep_experience]`

Renders the experience timeline.

| Attribute | Default | Description |
|---|---|---|
| `limit` | `20` | Maximum number of entries. |

---

### `[sep_education]`

Renders the education timeline.

| Attribute | Default | Description |
|---|---|---|
| `limit` | `20` | Maximum number of entries. |

---

### `[sep_certificates]`

Renders the certificates card grid.

| Attribute | Default | Description |
|---|---|---|
| `limit` | `20` | Maximum number of entries. |

---

## Customizing the Style

### Style Settings (GUI)

The easiest way to customize the portfolio is **SE Portfolio → Style Settings** — a full admin page with color pickers, preset themes, typography controls, spacing fields, effect toggles, and a freeform Custom CSS textarea. See [Style Settings](#style-settings) in the Admin Configuration section above for the complete field reference.

---

### CSS Custom Properties

All visual values are driven by CSS custom properties. The Style Settings page writes these to the page automatically, but you can override them manually too — useful for programmatic theming or child-theme integration.

The full set of variables injected by the plugin:

| Variable | Default | Controls |
|---|---|---|
| `--sep-bg` | `#0a0c10` | Page background |
| `--sep-surface` | `#0f1318` | Alternate section background |
| `--sep-surface2` | `#141c24` | Card and stat background |
| `--sep-border` | `#1d2535` | All borders and dividers |
| `--sep-accent` | `#00d26a` | Buttons, active links, section headings, timeline dots |
| `--sep-green` | `#00d26a` | Skill chip and badge colour (alias of accent) |
| `--sep-prompt` | `#79c0ff` | Terminal prompt `$` colour, blue skill chips |
| `--sep-text` | `#cdd9e5` | Primary body text |
| `--sep-muted` | `#5c6773` | Secondary / helper text |
| `--sep-warning` | `#f0b429` | "In Progress" status badge |
| `--sep-radius` | `3px` | Border radius on all rounded elements |
| `--sep-font-mono` | JetBrains Mono | Monospace font stack |
| `--sep-font-body` | JetBrains Mono | Body font |
| `--sep-base-size` | `15px` | Base font size for the portfolio |
| `--sep-hero-name-size` | `clamp(1.6rem, 4vw, 2.4rem)` | Hero name responsive size |
| `--sep-section-py` | `80px` | Section top/bottom padding |
| `--sep-card-pad` | `16px` | Card body padding |
| `--sep-hero-pt` | `160px` | Hero section top padding |
| `--sep-container-max` | `1100px` | Max content width |
| `--sep-transition` | `0.15s` | CSS transition duration |

To hard-code a value and bypass the admin settings, scope the variable to the portfolio wrapper:

```css
/* Force a specific accent regardless of the saved preset */
.sep-portfolio { --sep-accent: #58a6ff; }
```

---

### Custom CSS Field

The cleanest way to add one-off CSS rules is the **Custom CSS** field at the bottom of **SE Portfolio → Style Settings**. Rules entered there are injected after all plugin styles, so they always take priority.

Click **CSS Reference** (collapsible, no page reload) directly above the textarea to see every available variable with its current value, all section selectors, and ready-to-copy examples.

Common selectors:

| Target | Selector |
|---|---|
| Whole portfolio | `.sep-portfolio` |
| Hero section | `[data-sep-section="hero"]` |
| About section | `[data-sep-section="about"]` |
| Skills section | `[data-sep-section="skills"]` |
| Projects section | `[data-sep-section="projects"]` |
| Experience section | `[data-sep-section="experience"]` |
| Education section | `[data-sep-section="education"]` |
| Certificates section | `[data-sep-section="certificates"]` |
| Contact section | `[data-sep-section="contact"]` |
| Footer | `.sep-footer` |
| Any card | `.sep-portfolio .sep-card` |
| Top navigation | `.sep-portfolio .sep-topnav` |

Example snippets:

```css
/* Change the hero section background independently */
[data-sep-section="hero"] { --sep-bg: #0d1b2a; }

/* Make the hero name larger */
.sep-portfolio .sep-hero-name { font-size: 3rem; }

/* Add a stronger glow on card hover */
.sep-portfolio .sep-card:hover {
    box-shadow: 0 0 24px rgba(0, 210, 106, 0.35);
}

/* Hide the availability badge */
.sep-portfolio .sep-badge-available { display: none; }
```

---

### Typography

The plugin loads **JetBrains Mono** from Google Fonts automatically. Change the font stack via **SE Portfolio → Style Settings → Typography** or, to dequeue the Google Fonts request entirely:

```php
// In your theme's functions.php
add_action( 'wp_enqueue_scripts', function () {
    wp_dequeue_style( 'sep-google-fonts' );
}, 20 );
```

Then enter your preferred font family in the **Monospace Font** and **Body Font** fields in Style Settings, or override via CSS:

```css
.sep-portfolio { --sep-font-mono: 'Fira Code', monospace; }
```

---

## REST API

The plugin exposes a read-only REST API under the `sep/v1` namespace. All endpoints are public (they mirror content already visible on the portfolio page).

**Base URL:** `https://yoursite.com/wp-json/sep/v1/`

| Endpoint | Method | Description |
|---|---|---|
| `/about` | GET | Returns About Me data (name, bio, URLs, photo). |
| `/projects` | GET | Returns published projects. |
| `/projects/{id}` | GET | Returns a single project by ID. |
| `/skills` | GET | Returns all skills, ordered by display order. |
| `/experience` | GET | Returns experience entries. |
| `/education` | GET | Returns education entries. |
| `/certificates` | GET | Returns certificates. |

**Common query parameters** (supported on list endpoints):

| Parameter | Description | Default |
|---|---|---|
| `limit` | Number of items to return (max 100). | Varies by endpoint |
| `offset` | Skip N items (for pagination). | `0` |

**Filter parameters:**

| Endpoint | Parameter | Valid values |
|---|---|---|
| `/projects` | `status` | `completed`, `in-progress`, `archived` |
| `/skills` | `category` | `frontend`, `backend`, `devops`, `database`, `tools`, `soft-skills` |

**Response envelope:**

```json
{
    "success": true,
    "data": [ ... ],
    "count": 12
}
```

**Example requests:**

```bash
# All projects
curl https://yoursite.com/wp-json/sep/v1/projects

# Completed projects only
curl https://yoursite.com/wp-json/sep/v1/projects?status=completed

# Frontend skills
curl https://yoursite.com/wp-json/sep/v1/skills?category=frontend

# Paginate: second page of projects (10 per page)
curl https://yoursite.com/wp-json/sep/v1/projects?limit=10&offset=10
```

---

## Uninstallation

Go to **Plugins → Installed Plugins**, deactivate **SE Portfolio**, then click **Delete**.

The `uninstall.php` file will automatically:

- Delete the `sep_about` and `sep_style` options records.
- Delete all published and draft posts of types: `sep_project`, `sep_skill`, `sep_experience`, `sep_education`, `sep_certificate` — along with all their post meta.
- Delete any plugin transients.

> **Warning:** Uninstalling the plugin permanently deletes all portfolio content. Export or back up your data before uninstalling if you may need it later.

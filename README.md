# SE Portfolio

A production-ready, one-page developer portfolio plugin for WordPress with a terminal / GitHub dark aesthetic.

**Version:** 1.0.0 | **Requires WordPress:** 6.4+ | **Requires PHP:** 8.1+

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
4. [Shortcodes](#shortcodes)
5. [Customizing the Style](#customizing-the-style)
   - [CSS Custom Properties](#css-custom-properties)
   - [Adding Your Own CSS](#adding-your-own-css)
   - [Changing the Color Scheme](#changing-the-color-scheme)
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

### CSS Custom Properties

All colours, fonts, and radii are defined as CSS custom properties on `:root` inside `public/css/portfolio.css`. Override any of them in your child theme's stylesheet or via **Appearance → Customize → Additional CSS**.

```css
/* Example: paste in Appearance → Customize → Additional CSS */
:root {
    --sep-bg:      #0d1117;   /* Page background */
    --sep-surface: #161b22;   /* Card / section surface */
    --sep-accent:  #58a6ff;   /* Primary accent (currently green — change to blue) */
    --sep-prompt:  #79c0ff;   /* Terminal prompt colour */
    --sep-text:    #e6edf3;   /* Body text */
    --sep-muted:   #8b949e;   /* Muted / secondary text */
    --sep-border:  #30363d;   /* Borders and dividers */
    --sep-warning: #f0b429;   /* Warning / in-progress colour */
    --sep-radius:  4px;       /* Border radius for cards and buttons */
}
```

| Variable | Default | Controls |
|---|---|---|
| `--sep-bg` | `#0a0c10` | Page background colour |
| `--sep-surface` | `#0f1318` | Section alternate background |
| `--sep-surface2` | `#141c24` | Card and stat background |
| `--sep-border` | `#1d2535` | All borders and dividers |
| `--sep-accent` | `#00d26a` | Buttons, active links, section headings, timeline dots |
| `--sep-green` | `#00d26a` | Same as accent (alias) |
| `--sep-prompt` | `#79c0ff` | Terminal prompt `$` colour, blue skill chips |
| `--sep-text` | `#cdd9e5` | Primary body text |
| `--sep-muted` | `#5c6773` | Secondary / helper text |
| `--sep-warning` | `#f0b429` | "In Progress" status badge |
| `--sep-radius` | `3px` | Border radius on all rounded elements |
| `--sep-font-mono` | JetBrains Mono | Monospace font stack |
| `--sep-font-body` | JetBrains Mono | Body font (same as mono for the terminal aesthetic) |

---

### Adding Your Own CSS

The safest way to add styles without editing plugin files is **Appearance → Customize → Additional CSS**. All plugin selectors are prefixed with `.sep-portfolio` so your rules need to be specific enough to override them.

```css
/* Make section headings blue instead of default green */
.sep-section-heading::before {
    color: #58a6ff;
}

/* Increase the hero name font size */
.sep-hero-name {
    font-size: clamp(2rem, 5vw, 3rem);
}

/* Round card corners more */
.sep-portfolio {
    --sep-radius: 8px;
}
```

If you prefer to edit the plugin file directly, all front-end styles are in:

```
plugins/se-portfolio/public/css/portfolio.css
```

The file is organized into labelled sections with comments (e.g. `/* TOP NAVIGATION */`, `/* HERO */`, `/* SKILLS */`) so you can jump to the section you want to change.

---

### Changing the Color Scheme

#### Classic GitHub Dark (blue accent)

```css
:root {
    --sep-bg:      #0d1117;
    --sep-surface: #161b22;
    --sep-surface2: #21262d;
    --sep-border:  #30363d;
    --sep-accent:  #58a6ff;
    --sep-green:   #3fb950;
    --sep-prompt:  #79c0ff;
    --sep-text:    #e6edf3;
    --sep-muted:   #8b949e;
}
```

#### Dracula (purple accent)

```css
:root {
    --sep-bg:      #282a36;
    --sep-surface: #1e1f29;
    --sep-surface2: #313341;
    --sep-border:  #44475a;
    --sep-accent:  #bd93f9;
    --sep-prompt:  #8be9fd;
    --sep-text:    #f8f8f2;
    --sep-muted:   #6272a4;
}
```

#### Solarized Dark

```css
:root {
    --sep-bg:      #002b36;
    --sep-surface: #073642;
    --sep-surface2: #094555;
    --sep-border:  #0d5260;
    --sep-accent:  #859900;
    --sep-prompt:  #268bd2;
    --sep-text:    #839496;
    --sep-muted:   #586e75;
}
```

---

### Typography

The plugin loads **JetBrains Mono** from Google Fonts automatically. To switch to a different font, override the CSS variables and remove the Google Fonts dependency via a filter:

```php
// In your theme's functions.php
add_action( 'wp_enqueue_scripts', function () {
    wp_dequeue_style( 'sep-google-fonts' );
}, 20 );
```

Then set your own font in CSS:

```css
:root {
    --sep-font-mono: 'Fira Code', monospace;
    --sep-font-body: 'Fira Code', monospace;
}
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

- Delete the `sep_about` options record.
- Delete all published and draft posts of types: `sep_project`, `sep_skill`, `sep_experience`, `sep_education`, `sep_certificate` — along with all their post meta.
- Delete any plugin transients.

> **Warning:** Uninstalling the plugin permanently deletes all portfolio content. Export or back up your data before uninstalling if you may need it later.

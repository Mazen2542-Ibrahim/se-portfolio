=== SE Portfolio ===
Contributors: mazen
Tags: portfolio, developer, support engineer, dark theme, shortcode
Requires at least: 6.4
Tested up to: 6.7
Stable tag: 1.1.0
Requires PHP: 8.1
License: GPL v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A production-ready, dark-themed portfolio plugin for Support Engineers and Programmers.

== Description ==

SE Portfolio lets you build a complete, one-page developer portfolio inside WordPress. It features a GitHub-dark aesthetic with terminal-inspired styling and is designed for Support Engineers and Programmers.

**Features:**

* 5 Custom Post Types: Projects, Skills, Experience, Education, Certificates
* About Me settings page with profile photo, bio, and contact links
* 7 shortcodes for embedding individual sections or the full portfolio
* REST API endpoints under the `sep/v1` namespace
* Animated skill progress bars (IntersectionObserver — no jQuery)
* Project filter tabs (All / Completed / In Progress) — no page reload
* Sticky navigation on desktop
* Terminal blinking cursor on the hero job title
* Full WCAG accessibility attributes on progress bars
* GitHub-dark color scheme with CSS custom properties
* **Style Settings** admin page — change colors, fonts, spacing, and effects without writing code
* 6 built-in color presets: GitHub Dark, Ocean Blue, Dracula, Nord, Purple Haze, Solarized Dark
* Per-section color overrides for every portfolio section
* Typography controls: font family, base size, hero name size
* Spacing controls: section padding, card padding, hero top padding, container max-width
* Visual effect toggles: glow effects, scanlines, animations, cursor blink
* Card style toggle: terminal (with chrome header bar) or flat
* Custom CSS textarea with a built-in reference panel (variables, selectors, examples)

**Shortcodes:**

* `[sep_portfolio]` — Full one-page portfolio
* `[sep_about]` — About Me section
* `[sep_projects limit="6" status="completed"]` — Projects grid
* `[sep_skills category="backend"]` — Skill bars by category
* `[sep_experience limit="3"]` — Work history timeline
* `[sep_education]` — Education timeline
* `[sep_certificates]` — Certificate cards

== Installation ==

1. Upload the `se-portfolio` folder to `/wp-content/plugins/`.
2. Activate the plugin from **Plugins > Installed Plugins**.
3. Go to **SE Portfolio > About Me** to fill in your profile.
4. Add projects, skills, experience, education, and certificates via the sub-menus.
5. Create a page and add `[sep_portfolio]` to render the full portfolio.

== Frequently Asked Questions ==

= How do I change the color scheme? =

Go to **SE Portfolio › Style Settings** and pick one of the 6 built-in presets, or use the color pickers to set any palette you like. Changes take effect on save — no CSS needed.

For advanced overrides, use the **Custom CSS** field at the bottom of the same page. A built-in reference panel lists every available `--sep-*` variable with its current value, all section selectors, and copy-paste example snippets.

= Can I override styles for just one section? =

Yes. Under **SE Portfolio › Style Settings › Component Overrides** you can set independent colors (background, surface, accent, border, text, muted) for each section (Hero, About, Skills, etc.) without affecting the rest of the page.

= Can I use individual shortcodes separately? =

Yes. Each section has its own shortcode. See the Shortcodes section above.

= Are the REST API endpoints public? =

Yes. All endpoints under `sep/v1` are read-only and public — they return portfolio data only.

== Changelog ==

= 1.1.0 =
* Added Style Settings admin page with full color, font, spacing, and effect controls.
* Added 6 built-in color presets (GitHub Dark, Ocean Blue, Dracula, Nord, Purple Haze, Solarized Dark).
* Added per-section component color overrides for all 9 portfolio sections.
* Added typography size controls (base size, hero name size).
* Added spacing controls (section padding, card padding, hero padding, container max-width).
* Added visual effect toggles: glow effects, scanlines, animations, cursor blink.
* Added card style toggle: terminal or flat.
* Added Custom CSS textarea with inline reference panel (CSS variables, selectors, snippets).
* Updated portfolio.css layout values to use CSS custom properties.

= 1.0.0 =
* Initial release.

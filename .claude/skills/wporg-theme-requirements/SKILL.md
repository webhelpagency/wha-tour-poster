---
name: wporg-theme-requirements
description: Distilled WordPress.org theme directory review requirements (licensing, code, plugin territory, accessibility, files, style.css/readme headers, classic theme checklist) plus project conventions for the WHA Tour Poster theme. Load before writing or reviewing any theme/plugin code.
---

# WordPress.org theme requirements (distilled, Sept 2026)

Source: https://make.wordpress.org/themes/handbook/review/required/ — re-fetch if in doubt.

## Licensing
- 100% GPL-compatible: code, CSS, JS, fonts, images. Theme license: GPLv2 or later.
- `readme.txt` lists EVERY bundled resource with source URL and license (fonts, icons, screenshot images).
- Fonts: bundle locally as woff2 (Oswald and Manrope are SIL OFL 1.1 — allowed). No Google Fonts CDN calls.
- Images: only CC0/GPL images in the theme and screenshot. No client photos, no recognisable children's faces.
- Screenshot: `screenshot.png`, 1200×900 (4:3), shows the real theme, no advertising.

## Plugin territory (NOT allowed inside a theme)
- Custom post types, taxonomies, meta boxes, shortcodes, custom blocks, contact-form handling, SEO, analytics, social buttons, demo import, user roles.
- → All of that lives in the companion plugin `wha-tours-core`. The theme must work without it.
- Theme may only *recommend* plugins hosted on WordPress.org (via `admin_notices`, dismissible, no auto-install).

## Code
- No PHP/JS errors, warnings, notices. No deprecated functions.
- Prefix all public items with a unique ≥4-letter prefix: `wha_tour_poster_` / `WHA_TOUR_POSTER_` / `wha-tour-poster-` (handles). Exceptions: 3rd-party asset handles, menu/sidebar IDs.
- Escape late (`esc_html__`, `esc_attr`, `esc_url`, `wp_kses_post`), sanitize early. Customizer settings need `sanitize_callback`.
- Enqueue all assets with `wp_enqueue_script/style`; bundle originals next to minified files; use WP-bundled jQuery if needed (prefer vanilla JS).
- All strings translatable, text domain = theme slug (`wha-tour-poster`).
- Options: one prefixed option (array) via Settings/Options API, or `theme_mod`s via Customizer.
- No admin-bar removal, no activation redirects, no removal of non-presentational hooks.

## Classic theme checklist
- DOCTYPE, `language_attributes()`, `bloginfo('charset')`, `wp_head()`, `wp_body_open()`, `body_class()`, `post_class()`, `wp_footer()`, `wp_link_pages()`.
- `add_theme_support`: `title-tag`, `automatic-feed-links`, `post-thumbnails`, `html5`, `custom-logo`, `responsive-embeds`, `wp-block-styles`/`align-wide` (as applicable).
- Use `get_template_part()` for partials. Respect front-page/blog-page settings (`home.php`/`front-page.php`).
- `header.php`, `footer.php`, `sidebar.php`, `searchform.php`, `comments.php` use the standard WP functions (`get_header()`, `get_search_form()`, `comments_template()`).
- Capability checks: `edit_theme_options`.
- Skip link is the first focusable element and visible on focus. Every control reachable by keyboard with visible focus. Mobile menu and tabs keyboard-operable with ARIA (`aria-expanded`, `role=tab/tabpanel`, `aria-selected`).
- Include `theme.json` (palette, font families, spacing) even in a classic theme so the block editor matches the front end.

## Files
- Required `style.css` headers: Theme Name, Theme URI (optional), Author, Author URI (optional), Description, Version (X.X.X), Requires at least, Tested up to, Requires PHP, License, License URI, Text Domain, Tags.
- Required `readme.txt` (standard format: contributors, requires, tested up to, license, description, installation, FAQ, changelog, resources/credits).
- Forbidden in the zip: `.git`, `.svn`, hidden files, `node_modules`, zip files, shell scripts, `favicon.ico`, `thumbs.db`, `.DS_Store`, SQL/log files, project/config files (`composer.json`, `package.json`, `phpcs.xml` should be stripped by the build script).
- Theme name must not contain "WordPress", "Theme", or "Twenty".
- Spell "WordPress" with capital W and P everywhere.
- Max one front-end credit link (footer) + optional link to WordPress.org.

## Project conventions
- Theme slug/dir: `wha-tour-poster`; prefix `wha_tour_poster_`; text domain `wha-tour-poster`.
- Companion plugin: `wha-tours-core`; prefix `wha_tours_core_`; text domain `wha-tours-core`. Registers CPT `tour`, taxonomies `tour_category`, `tour_season`, `tour_country`, meta: days, transport, price, price note, departure dates, route stops, hit flag.
- Reference design: the poster-variant homepage HTML in `reference/` + `tokens.css`, `base.css`, `components.css`, `theme-c.css`. Port markup and CSS as-is; only rename hooks/paths.
- Lint: `composer run lint` (phpcs with WPCS). Theme Check plugin on the dev site before packaging.

# Elita Tour — WordPress theme for the WordPress.org directory

## Goal
Classic WordPress theme `elita-tour` (Underscores-based) that reproduces the reference homepage
`reference/Elita Tour — Варіант C «Постер».html` and passes WordPress.org theme review,
plus a companion plugin `elita-tour-core` that holds all "plugin territory" features (tour CPT, taxonomies, meta, lead form).

## Model policy
- Planning, architecture, task breakdown, reviews of plans: **Fable** (`wp-theme-architect` agent or the main session).
- All implementation (PHP, CSS, JS, theme.json, plugin): **Opus** via the `wp-theme-coder` agent.
- Compliance review before milestones/packaging: `wp-theme-reviewer` agent (Opus).
- Always load the project skill `wporg-theme-requirements` before writing or reviewing theme code.

## Principles
1. Reuse before writing: `_s` scaffold, reference CSS/markup, WordPress core APIs, GPL libraries. Cite the source of reused code in commit messages.
2. Theme must work without the companion plugin (falls back to posts/categories).
3. Prefix `elita_tour_`, text domain `elita-tour`; escape output, sanitize input, no remote assets, bundled OFL fonts.
4. Keep the reference design intact: port, don't redesign.

## Layout
- `reference/` — design reference (never shipped).
- `dev/` — local WordPress 7.1.1 + SQLite install (gitignored). Start/stop: `dev/serve.sh` / `dev/stop.sh` (http://127.0.0.1:8085, admin/admin). wp-cli: `dev/wp-cli <cmd>`. Seed: `dev/wp-cli eval-file dev/seed.php`.
- `wp-content/themes/elita-tour/` — the theme (symlinked into `dev/`).
- `wp-content/plugins/elita-tour-core/` — companion plugin.
- `build/` — packaged zips produced by the build script.

## Tooling
- PHP 8.5 CLI (`/usr/local/bin/php`), Composer, Node 22.
- wp-cli: `dev/wp-cli` (wrapper around `dev/wp-cli.phar`, system PHP 8.5).
- Local.app is installed; sites live in `~/Local Sites/<site>/app/public`.
- Lint: `composer run lint` (phpcs + WPCS). Theme Check: `dev/wp-cli theme-check run elita-tour --format=json`. Build zips: `bin/build.sh` → `build/`.
- Installed Claude plugins: php-lsp (Intelephense), context7 (docs lookup), build-with-wordpress (block theme references, block-fixer), frontend-design, ui-ux-pro-max.

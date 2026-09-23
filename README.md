# Elita Tour

Classic WordPress theme for children's tour operators, built for the WordPress.org theme directory, plus the companion plugin **Elita Tour Core**.

- `wp-content/themes/elita-tour` — the theme (Underscores-based, GPLv2+).
- `wp-content/plugins/elita-tour-core` — tour post type, taxonomies, meta fields (CMB2), lead request form, demo importer.
- `bin/build.sh` — builds distributable zips into `build/`.

## Development

```
composer install          # phpcs + WordPress Coding Standards
composer run lint
```

A local WordPress + SQLite site can be created under `dev/` (see `CLAUDE.md`); it is not part of the repository.

## License

GPLv2 or later. Bundled fonts (Oswald, Manrope) are SIL OFL 1.1; screenshot and demo photos are CC0 from Wikimedia Commons (credited in each `readme.txt`).

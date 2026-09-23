=== Elita Tour Core ===
Contributors: webhelpagency
Tags: tours, travel, custom post type, lead form, block
Requires at least: 6.4
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Companion plugin for the Elita Tour theme: the Tour post type, its taxonomies and meta fields, and the lead request form.

== Description ==

Elita Tour Core keeps the content structure of a tour operator site in a plugin, so it survives a theme switch, as required by the WordPress.org theme guidelines.

The plugin registers:

* The **Tour** post type (archive at `/tours/`, single tours at `/tour/<slug>/`), with title, editor, excerpt, featured image and revisions, available in the block editor and the REST API.
* Three taxonomies: **Tour Categories** (hierarchical), **Seasons** and **Countries**.
* Tour fields: days, transport, price, route stops, departure dates with availability status, and a "season hit" flag. The next departure date is computed automatically and used for sorting.
* Category fields: image, short description and a colour key. Seasons have a sort order.
* A **demo content importer** under Tours → Import demo content (and `wp elita-tour demo import`), which fills a fresh site with a sample catalogue: four categories, four seasons, eleven countries, twelve tours with CC0 photographs, four pages, two menus, the Customizer settings of the Elita Tour theme and the Reading settings. It never overwrites existing content and creates no duplicates when it is run twice.
* A **lead form** as the `[elita_tour_lead_form]` shortcode and as the *Lead form* block. Submissions are checked against a nonce, a honeypot field and a one-minute-per-IP rate limit, emailed to the site administrator and stored as private **Leads** entries in the admin.

Template helpers for theme authors: `elita_tour_core_get_meta()`, `elita_tour_core_get_tours()`, `elita_tour_core_get_term_image_id()`, `elita_tour_core_next_date()`, `elita_tour_core_format_dates()`, `elita_tour_core_transport_label()`, `elita_tour_core_transport_icon()`, `elita_tour_core_category_color()` and `elita_tour_core_price_label()`.

== Installation ==

1. Upload the `elita-tour-core` folder to `/wp-content/plugins/`, or install the plugin through the Plugins screen.
2. Activate the plugin through the Plugins screen. The permalinks are refreshed automatically on activation.
3. On a fresh site, go to **Tours → Import demo content** to fill it with the sample catalogue, or run `wp elita-tour demo import`.
4. Add tours under **Tours**, and place the *Lead form* block or the `[elita_tour_lead_form]` shortcode on your contact page.

== Frequently Asked Questions ==

= Where do the submitted requests go? =

Each request is emailed to the address in Settings → General (filterable with `elita_tour_core_lead_recipient`) and stored as a private entry under Tours → Leads, visible to administrators only.

= Does uninstalling delete my tours? =

No. Uninstalling removes only the options and transients created by the plugin. Tours, terms, tour meta and stored leads are kept.

= What does the demo import change on a site that is already in use? =

Nothing that exists. Every tour, term, page and image it creates is tagged with the `_elita_tour_core_demo` meta key and skipped on the next run. Menus are only created for theme locations that have no menu yet, Customizer settings are only written when the Elita Tour theme is active and the setting is still empty, and the Reading settings are only changed while the front page still shows the latest posts.

= Which filters and actions are available? =

`elita_tour_core_get_tours_args` filters the query arguments, `elita_tour_core_lead_recipient` filters the notification address and `elita_tour_core_lead_submitted` fires after a request was stored.

== Developer notes ==

* **Meta key prefix.** All tour and term meta registered by the plugin uses the `_elita_tour_` prefix
  (`_elita_tour_days`, `_elita_tour_transport`, `_elita_tour_price`, `_elita_tour_route`,
  `_elita_tour_dates`, `_elita_tour_hit`, `_elita_tour_next_departure`, `_elita_tour_image`,
  `_elita_tour_image_id`,
  `_elita_tour_short_desc`, `_elita_tour_color`, `_elita_tour_order`). That prefix is shared with the
  Elita Tour theme, which reads the same keys directly when it renders poster cards and season tabs, so
  renaming a key here breaks the theme templates. The lead entries use `_elita_tour_core_lead_*`
  instead, because nothing outside this plugin reads them.
* **Lead post type.** Submitted requests are stored in the `elita_lead` post type. WordPress limits a
  post type name to 20 characters, so the name cannot carry the full `elita_tour_core_` prefix;
  `elita_lead` is the prefixed short form and is registered private, with no public archive.

== Screenshots ==

1. The tour edit screen with the tour details fields.
2. The lead form on the front end.

== Changelog ==

= 1.0.0 =
* Initial release: Tour post type, tour taxonomies and meta, lead form shortcode and block, demo content importer.

== Upgrade Notice ==

= 1.0.0 =
Initial release.

== Resources ==

* CMB2 — https://github.com/CMB2/CMB2 — GPL-2.0-or-later, bundled in `vendor/cmb2/cmb2`.

Demo content photographs, bundled in `demo/images/` and imported by Tours → Import demo content.
All of them come from Wikimedia Commons and are released under CC0 1.0 Universal (public domain
dedication), https://creativecommons.org/publicdomain/zero/1.0/. They were re-encoded as JPEG with
a long edge of at most 1600 pixels; the same list is repeated in `demo/images/CREDITS.txt`.

* `demo/images/beach-deckchair.jpg` — File:Liegestuhl Strand Uwe (183891951).jpeg — https://commons.wikimedia.org/wiki/File:Liegestuhl_Strand_Uwe_(183891951).jpeg — Uwe Jelting — CC0 1.0
* `demo/images/prague-castle.jpg` — File:Prague Castle at Night viewed from Charles Bridge.jpg — https://commons.wikimedia.org/wiki/File:Prague_Castle_at_Night_viewed_from_Charles_Bridge.jpg — Lucas Garron — CC0 1.0
* `demo/images/carpathians.jpg` — File:Українські карпати.jpg — https://commons.wikimedia.org/wiki/File:%D0%A3%D0%BA%D1%80%D0%B0%D1%97%D0%BD%D1%81%D1%8C%D0%BA%D1%96_%D0%BA%D0%B0%D1%80%D0%BF%D0%B0%D1%82%D0%B8.jpg — Slaydoggy — CC0 1.0
* `demo/images/lake-bled.jpg` — File:Lake Bled, Slovenia (28439609952).jpg — https://commons.wikimedia.org/wiki/File:Lake_Bled,_Slovenia_(28439609952).jpg — Dimitry Anikin — CC0 1.0
* `demo/images/eiffel-tower.jpg` — File:Eiffel Tower, Paris 12 June 2023.jpg — https://commons.wikimedia.org/wiki/File:Eiffel_Tower,_Paris_12_June_2023.jpg — Pierre Blaché — CC0 1.0
* `demo/images/budapest-chain-bridge.jpg` — File:Széchenyi Chain Bridge in Budapest at night.jpg — https://commons.wikimedia.org/wiki/File:Sz%C3%A9chenyi_Chain_Bridge_in_Budapest_at_night.jpg — Wilfredor — CC0 1.0
* `demo/images/synevyr-lake.jpg` — File:Transkarpaten Ukraine Synevyr - 2.jpg — https://commons.wikimedia.org/wiki/File:Transkarpaten_Ukraine_Synevyr_-_2.jpg — Bikerjimi — CC0 1.0
* `demo/images/danube-budapest.jpg` — File:Danube, Budapest 20250628 160606.jpg — https://commons.wikimedia.org/wiki/File:Danube,_Budapest_20250628_160606.jpg — Vauia Rex — CC0 1.0
* `demo/images/krakow-old-town.jpg` — File:Horse carriages in Krakow Old Town Square.JPG — https://commons.wikimedia.org/wiki/File:Horse_carriages_in_Krakow_Old_Town_Square.JPG — Rudolph.A.Furtado — CC0 1.0
* `demo/images/dresden.jpg` — File:Dresden, Ernemannturm (Juni 2026) 4.jpg — https://commons.wikimedia.org/wiki/File:Dresden,_Ernemannturm_(Juni_2026)_4.jpg — Romzig — CC0 1.0
* `demo/images/vienna.jpg` — File:Statue Eugenio of Savoy Heldenplatz Vienna Austria.jpg — https://commons.wikimedia.org/wiki/File:Statue_Eugenio_of_Savoy_Heldenplatz_Vienna_Austria.jpg — Jebulon — CC0 1.0
* `demo/images/kamianets-podilskyi-castle.jpg` — File:Bridge to Kamyanets Podilsky Castle.jpg — https://commons.wikimedia.org/wiki/File:Bridge_to_Kamyanets_Podilsky_Castle.jpg — Brian Dell — CC0 1.0
* `demo/images/bran-castle.jpg` — File:Bran Castle - Castelul Bran.JPG — https://commons.wikimedia.org/wiki/File:Bran_Castle_-_Castelul_Bran.JPG — Calatorinlume — CC0 1.0
* `demo/images/monaco.jpg` — File:Spring time in Monte Carlo, Monaco (Unsplash).jpg — https://commons.wikimedia.org/wiki/File:Spring_time_in_Monte_Carlo,_Monaco_(Unsplash).jpg — Nick Karvounis — CC0 1.0

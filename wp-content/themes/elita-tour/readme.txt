=== Elita Tour ===

Contributors: webhelpagency
Requires at least: 6.6
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 1.0.0
License: GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Tags: custom-logo, custom-menu, featured-images, threaded-comments, translation-ready, one-column, right-sidebar, footer-widgets, sticky-post, theme-options, wide-blocks, block-styles, editor-style

A poster styled theme for a children's tour operator.

== Description ==

Elita Tour is a poster-styled theme for a children's tour operator: the front page leads with
full-bleed category panels, programmes are presented as poster cards over photography, seasonal
departures are grouped into keyboard-accessible tabs, and the footer keeps contacts and social
links within reach. It bundles the Oswald and Manrope webfonts with full Cyrillic coverage and
ships a matching block editor stylesheet.

Designed for tour operators; when a tour post type with route, dates and price data is available
(such as the Elita Tour Core plugin), it renders poster cards and seasonal departures; otherwise
it falls back to standard posts and categories.

== Installation ==

1. In your WordPress admin, go to Appearance > Themes and click Add New.
2. Click Upload Theme and choose the elita-tour.zip file, then click Install Now.
3. Click Activate to use the theme.
4. Create a page (e.g. Home), set Settings > Reading > A static page > Front page to it; the landing
   sections then render on it. Pick a second page as the Posts page for the blog listing. While
   Settings > Reading is left on "Your latest posts", the front page shows the blog listing instead.
5. Go to Appearance > Customize > Elita Tour to set the phone number, the call to action and
   the social links, and to Appearance > Menus to assign the Primary Menu and the Footer Legal Menu.

== Frequently Asked Questions ==

= Does the theme need a plugin? =

No. The theme renders standard posts, pages and categories on its own. When a tour post type with
route, dates and price data is available (such as the one added by the Elita Tour Core plugin), the
poster cards, the season tabs and the enquiry form use it instead.

= What does the front page show without any tour data? =

Every landing section degrades on its own. The category panels fall back to the four largest
standard categories, the search bar falls back to the normal search form, the poster cards fall
back to the latest posts with their featured images, and the seasonal departures section, which has
no equivalent among standard posts, is left out entirely. The hero text, the facts, the about block
and the enquiry contacts are Customizer settings, so they are shown either way. The result is a
complete page built from posts, pages and categories alone.

= Where are the styles? =

style.css carries the theme header and is enqueued last, so a child theme can override
everything. The styles themselves are enqueued from assets/css/ (tokens.css, main.css) and the
block editor uses assets/css/editor.css. The @font-face rules for the bundled fonts are generated
by WordPress from the fontFace entries in theme.json.

== Changelog ==

= 1.0.0 =
* Initial release.

== Copyright ==

Elita Tour WordPress Theme, (C) 2026 Web Help Agency
Elita Tour is distributed under the terms of the GNU GPL v2 or later.

== Resources ==

Underscores (_s) starter theme
  https://underscores.me/
  (C) 2012-2020 Automattic, Inc.
  License: GNU General Public License v2 or later
  License URI: http://www.gnu.org/licenses/gpl-2.0.html

Oswald (assets/fonts/oswald/oswald-variable.woff2)
  https://github.com/google/fonts/tree/main/ofl/oswald
  Copyright 2016 The Oswald Project Authors
  License: SIL Open Font License, 1.1
  License URI: http://scripts.sil.org/OFL
  Full licence text: assets/fonts/oswald/OFL.txt

Manrope (assets/fonts/manrope/manrope-variable.woff2)
  https://github.com/google/fonts/tree/main/ofl/manrope
  Copyright 2018 The Manrope Project Authors
  License: SIL Open Font License, 1.1
  License URI: http://scripts.sil.org/OFL
  Full licence text: assets/fonts/manrope/OFL.txt

Icon sprite (assets/images/icons.svg)
  Original work created for this theme by Web Help Agency.
  License: GNU General Public License v2 or later
  License URI: http://www.gnu.org/licenses/gpl-2.0.html

Screenshot photographs (screenshot.png)
  The screenshot shows the theme's front page. All photographs in it are CC0 1.0 Universal
  (public domain dedication) files from Wikimedia Commons. None of them is bundled with the
  theme; they appear inside screenshot.png only.

  Liegestuhl Strand Uwe (183891951).jpeg
    https://commons.wikimedia.org/wiki/File:Liegestuhl_Strand_Uwe_(183891951).jpeg
    Author: Uwe Jelting
    License: CC0 1.0 Universal (Public Domain Dedication)
    License URI: https://creativecommons.org/publicdomain/zero/1.0/

  Prague Castle at Night viewed from Charles Bridge.jpg
    https://commons.wikimedia.org/wiki/File:Prague_Castle_at_Night_viewed_from_Charles_Bridge.jpg
    Author: Lucas Garron
    License: CC0 1.0 Universal (Public Domain Dedication)
    License URI: https://creativecommons.org/publicdomain/zero/1.0/

  Українські карпати.jpg
    https://commons.wikimedia.org/wiki/File:%D0%A3%D0%BA%D1%80%D0%B0%D1%97%D0%BD%D1%81%D1%8C%D0%BA%D1%96_%D0%BA%D0%B0%D1%80%D0%BF%D0%B0%D1%82%D0%B8.jpg
    Author: Slaydoggy
    License: CC0 1.0 Universal (Public Domain Dedication)
    License URI: https://creativecommons.org/publicdomain/zero/1.0/

  Lake Bled, Slovenia (28439609952).jpg
    https://commons.wikimedia.org/wiki/File:Lake_Bled,_Slovenia_(28439609952).jpg
    Author: Dimitry Anikin
    License: CC0 1.0 Universal (Public Domain Dedication)
    License URI: https://creativecommons.org/publicdomain/zero/1.0/

  Eiffel Tower, Paris 12 June 2023.jpg
    https://commons.wikimedia.org/wiki/File:Eiffel_Tower,_Paris_12_June_2023.jpg
    Author: Pierre Blache
    License: CC0 1.0 Universal (Public Domain Dedication)
    License URI: https://creativecommons.org/publicdomain/zero/1.0/

  Széchenyi Chain Bridge in Budapest at night.jpg
    https://commons.wikimedia.org/wiki/File:Sz%C3%A9chenyi_Chain_Bridge_in_Budapest_at_night.jpg
    Author: Wilfredor
    License: CC0 1.0 Universal (Public Domain Dedication)
    License URI: https://creativecommons.org/publicdomain/zero/1.0/

  Transkarpaten Ukraine Synevyr - 2.jpg
    https://commons.wikimedia.org/wiki/File:Transkarpaten_Ukraine_Synevyr_-_2.jpg
    Author: Bikerjimi
    License: CC0 1.0 Universal (Public Domain Dedication)
    License URI: https://creativecommons.org/publicdomain/zero/1.0/

  Danube, Budapest 20250628 160606.jpg
    https://commons.wikimedia.org/wiki/File:Danube,_Budapest_20250628_160606.jpg
    Author: Vauia Rex
    License: CC0 1.0 Universal (Public Domain Dedication)
    License URI: https://creativecommons.org/publicdomain/zero/1.0/

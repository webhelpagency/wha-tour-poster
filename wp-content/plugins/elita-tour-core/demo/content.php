<?php
/**
 * Demo content definition.
 *
 * The file returns the demo data set imported by Tours → Import demo content
 * (see includes/demo-import.php). It contains data only: no output, no hooks.
 *
 * The editorial texts are deliberately plain Ukrainian strings and are NOT run
 * through translation functions. They are sample content, not user interface
 * strings: a translator would have nothing to translate here, and wrapping them
 * would put demo copy into the plugin language files.
 *
 * Every photograph referenced by `image` lives in demo/images/ and is CC0 1.0
 * Universal; see demo/images/CREDITS.txt for the full list of sources.
 *
 * @package Elita_Tour_Core
 */

defined( 'ABSPATH' ) || exit;

return array(

	/*
	 * Tour categories: `color` is the palette key used by the theme, `image`
	 * the panel photograph on the front page.
	 */
	'categories' => array(
		array(
			'key'        => 'camps',
			'name'       => 'Табори',
			'short_desc' => 'Море, Болгарія, зміни 13 днів',
			'color'      => 'camps',
			'image'      => 'beach-deckchair.jpg',
		),
		array(
			'key'        => 'europe',
			'name'       => 'Європа',
			'short_desc' => 'Автобусні тури містами',
			'color'      => 'europe',
			'image'      => 'prague-castle.jpg',
		),
		array(
			'key'        => 'ukraine',
			'name'       => 'Україна',
			'short_desc' => 'Карпати, Львів, замки Поділля',
			'color'      => 'ukraine',
			'image'      => 'carpathians.jpg',
		),
		array(
			'key'        => 'adventure',
			'name'       => 'Пригоди',
			'short_desc' => 'Румунія, Словаччина, Словенія, Греція',
			'color'      => 'adventure',
			'image'      => 'bran-castle.jpg',
		),
	),

	// Seasons, in the order the front page tabs use.
	'seasons'    => array(
		array(
			'key'   => 'autumn',
			'name'  => 'Осінь',
			'order' => 1,
		),
		array(
			'key'   => 'winter',
			'name'  => 'Зима',
			'order' => 2,
		),
		array(
			'key'   => 'spring',
			'name'  => 'Весна',
			'order' => 3,
		),
		array(
			'key'   => 'summer',
			'name'  => 'Літо',
			'order' => 4,
		),
	),

	'countries'  => array(
		array(
			'key'  => 'ukrayina',
			'name' => 'Україна',
		),
		array(
			'key'  => 'bolhariya',
			'name' => 'Болгарія',
		),
		array(
			'key'  => 'chekhiya',
			'name' => 'Чехія',
		),
		array(
			'key'  => 'polshcha',
			'name' => 'Польща',
		),
		array(
			'key'  => 'avstriya',
			'name' => 'Австрія',
		),
		array(
			'key'  => 'frantsiya',
			'name' => 'Франція',
		),
		array(
			'key'  => 'italiya',
			'name' => 'Італія',
		),
		array(
			'key'  => 'rumuniya',
			'name' => 'Румунія',
		),
		array(
			'key'  => 'sloveniya',
			'name' => 'Словенія',
		),
		array(
			'key'  => 'monako',
			'name' => 'Монако',
		),
		array(
			'key'  => 'nimechchyna',
			'name' => 'Німеччина',
		),
	),

	/*
	 * Tours. `key` doubles as the post slug, `meta` holds the tour fields of
	 * the plugin; the next departure date is computed on import.
	 */
	'tours'      => array(
		array(
			'key'       => 'osinni-kanikuly-praha-i-drezden',
			'title'     => 'Осінні канікули: Прага і Дрезден',
			'excerpt'   => 'Класичний автобусний маршрут осінніх канікул: Краків, Прага і Дрезден за шість днів.',
			'content'   => "<!-- wp:paragraph -->\n<p>Класичний автобусний маршрут осінніх канікул: Краків, Прага і Дрезден за шість днів.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p>Маршрут: Київ — Краків — Прага — Дрезден. Керівник групи супроводжує дітей від виїзду до повернення.</p>\n<!-- /wp:paragraph -->",
			'category'  => 'europe',
			'seasons'   => array( 'autumn' ),
			'countries' => array( 'ukrayina', 'polshcha', 'chekhiya', 'nimechchyna' ),
			'hit'       => true,
			'image'     => 'dresden.jpg',
			'meta'      => array(
				'days'      => 6,
				'transport' => 'bus',
				'price'     => 'від 455 €',
				'route'     => array( 'Київ', 'Краків', 'Прага', 'Дрезден' ),
				'dates'     => array(
					array(
						'start'  => '2026-10-24',
						'end'    => '2026-10-29',
						'status' => 'ok',
					),
					array(
						'start'  => '2026-11-01',
						'end'    => '2026-11-06',
						'status' => 'few',
					),
				),
			),
		),
		array(
			'key'       => 'paryzh-disneyland',
			'title'     => 'Париж + Діснейленд – Подорож, що надихає на успіх',
			'excerpt'   => 'Вісім днів дорогою до Парижа з цілим днем у Діснейленді.',
			'content'   => "<!-- wp:paragraph -->\n<p>Вісім днів дорогою до Парижа з цілим днем у Діснейленді.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p>Маршрут: Краків — Париж — Діснейленд. Керівник групи супроводжує дітей від виїзду до повернення.</p>\n<!-- /wp:paragraph -->",
			'category'  => 'europe',
			'seasons'   => array( 'spring', 'summer' ),
			'countries' => array( 'polshcha', 'frantsiya' ),
			'hit'       => true,
			'image'     => 'eiffel-tower.jpg',
			'meta'      => array(
				'days'      => 8,
				'transport' => 'bus',
				'price'     => '',
				'route'     => array( 'Краків', 'Париж', 'Діснейленд' ),
				'dates'     => array(
					array(
						'start'  => '2027-03-21',
						'end'    => '2027-03-28',
						'status' => 'ok',
					),
					array(
						'start'  => '2027-06-14',
						'end'    => '2027-06-21',
						'status' => 'few',
					),
				),
			),
		),
		array(
			'key'       => 'chernivci-kamyanec-podilskyi-hotyn',
			'title'     => 'Чернівці, Кам’янець – Подільський, Хотин',
			'excerpt'   => 'Чотири дні замками Поділля та Буковини.',
			'content'   => "<!-- wp:paragraph -->\n<p>Чотири дні замками Поділля та Буковини.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p>Маршрут: Київ — Чернівці — Кам’янець — Хотин. Керівник групи супроводжує дітей від виїзду до повернення.</p>\n<!-- /wp:paragraph -->",
			'category'  => 'ukraine',
			'seasons'   => array( 'autumn', 'winter' ),
			'countries' => array( 'ukrayina' ),
			'hit'       => true,
			'image'     => 'kamianets-podilskyi-castle.jpg',
			'meta'      => array(
				'days'      => 4,
				'transport' => 'bus',
				'price'     => '',
				'route'     => array( 'Київ', 'Чернівці', 'Кам’янець', 'Хотин' ),
				'dates'     => array(
					array(
						'start'  => '2026-10-26',
						'end'    => '2026-10-29',
						'status' => 'ok',
					),
					array(
						'start'  => '2027-01-03',
						'end'    => '2027-01-06',
						'status' => 'few',
					),
				),
			),
		),
		array(
			'key'       => 'shlyah-do-monako',
			'title'     => 'Шлях до Монако: Європейська феєрія',
			'excerpt'   => 'Лазурний берег, Монако і Мілан за вісім днів.',
			'content'   => "<!-- wp:paragraph -->\n<p>Лазурний берег, Монако і Мілан за вісім днів.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p>Маршрут: Будапешт — Ніцца — Монако — Мілан. Керівник групи супроводжує дітей від виїзду до повернення.</p>\n<!-- /wp:paragraph -->",
			'category'  => 'europe',
			'seasons'   => array( 'summer' ),
			'countries' => array( 'frantsiya', 'monako', 'italiya' ),
			'hit'       => true,
			'image'     => 'monaco.jpg',
			'meta'      => array(
				'days'      => 8,
				'transport' => 'bus',
				'price'     => '',
				'route'     => array( 'Будапешт', 'Ніцца', 'Монако', 'Мілан' ),
				'dates'     => array(
					array(
						'start'  => '2027-06-13',
						'end'    => '2027-06-20',
						'status' => 'ok',
					),
					array(
						'start'  => '2027-06-27',
						'end'    => '2027-07-04',
						'status' => 'closed',
					),
				),
			),
		),
		array(
			'key'       => 'prygody-v-rumuniyi',
			'title'     => 'Незвичайні пригоди в Румунії. Від замку Дракули до Соляної шахти.',
			'excerpt'   => 'Синая, Бран і Брашов — сім днів легенд Трансільванії.',
			'content'   => "<!-- wp:paragraph -->\n<p>Синая, Бран і Брашов — сім днів легенд Трансільванії.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p>Маршрут: Синая — Бран — Брашов. Керівник групи супроводжує дітей від виїзду до повернення.</p>\n<!-- /wp:paragraph -->",
			'category'  => 'adventure',
			'seasons'   => array( 'autumn' ),
			'countries' => array( 'rumuniya' ),
			'hit'       => true,
			'image'     => 'bran-castle.jpg',
			'meta'      => array(
				'days'      => 7,
				'transport' => 'bus',
				'price'     => 'від 405 €',
				'route'     => array( 'Синая', 'Бран', 'Брашов' ),
				'dates'     => array(
					array(
						'start'  => '2026-10-25',
						'end'    => '2026-10-30',
						'status' => 'ok',
					),
				),
			),
		),
		array(
			'key'       => 'evropeiski-perlyny',
			'title'     => 'Європейські перлини: Краків, Прага, Дрезден, Вроцлав',
			'excerpt'   => 'Сім днів і чотири міста — найповніший автобусний маршрут каталогу.',
			'content'   => "<!-- wp:paragraph -->\n<p>Сім днів і чотири міста — найповніший автобусний маршрут каталогу.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p>Маршрут: Краків — Прага — Дрезден — Вроцлав. Керівник групи супроводжує дітей від виїзду до повернення.</p>\n<!-- /wp:paragraph -->",
			'category'  => 'europe',
			'seasons'   => array( 'autumn' ),
			'countries' => array( 'polshcha', 'chekhiya', 'nimechchyna' ),
			'hit'       => true,
			'image'     => 'krakow-old-town.jpg',
			'meta'      => array(
				'days'      => 7,
				'transport' => 'bus',
				'price'     => '',
				'route'     => array( 'Краків', 'Прага', 'Дрезден', 'Вроцлав' ),
				'dates'     => array(
					array(
						'start'  => '2026-10-25',
						'end'    => '2026-10-31',
						'status' => 'ok',
					),
					array(
						'start'  => '2027-10-24',
						'end'    => '2027-10-30',
						'status' => 'ok',
					),
				),
			),
		),
		array(
			'key'       => 'volyn-klyche',
			'title'     => 'Волинь Кличе: Дубно, замок Острозьких, Тараканівський форт',
			'excerpt'   => 'Три дні Волинню: Дубно, Острог, Луцьк і Колодяжне.',
			'content'   => "<!-- wp:paragraph -->\n<p>Три дні Волинню: Дубно, Острог, Луцьк і Колодяжне.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p>Маршрут: Дубно — Острог — Луцьк — Колодяжне. Керівник групи супроводжує дітей від виїзду до повернення.</p>\n<!-- /wp:paragraph -->",
			'category'  => 'ukraine',
			'seasons'   => array( 'winter' ),
			'countries' => array( 'ukrayina' ),
			'hit'       => false,
			'image'     => 'kamianets-podilskyi-castle.jpg',
			'meta'      => array(
				'days'      => 3,
				'transport' => 'bus',
				'price'     => '',
				'route'     => array( 'Дубно', 'Острог', 'Луцьк', 'Колодяжне' ),
				'dates'     => array(
					array(
						'start'  => '2027-01-03',
						'end'    => '2027-01-05',
						'status' => 'ok',
					),
				),
			),
		),
		array(
			'key'       => 'sloveniya-i-taemnytsi-drakona',
			'title'     => 'Словенія і таємниці Дракона',
			'excerpt'   => 'Любляна, Блед і Постойнська печера — вісім днів альпійської Словенії.',
			'content'   => "<!-- wp:paragraph -->\n<p>Любляна, Блед і Постойнська печера — вісім днів альпійської Словенії.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p>Маршрут: Любляна — Блед — Постойна. Керівник групи супроводжує дітей від виїзду до повернення.</p>\n<!-- /wp:paragraph -->",
			'category'  => 'adventure',
			'seasons'   => array( 'spring' ),
			'countries' => array( 'sloveniya', 'avstriya' ),
			'hit'       => false,
			'image'     => 'lake-bled.jpg',
			'meta'      => array(
				'days'      => 8,
				'transport' => 'bus',
				'price'     => 'від 455 €',
				'route'     => array( 'Любляна', 'Блед', 'Постойна' ),
				'dates'     => array(
					array(
						'start'  => '2027-03-22',
						'end'    => '2027-03-27',
						'status' => 'ok',
					),
				),
			),
		),
		array(
			'key'       => 'riviera-camp-bulgaria',
			'title'     => 'Табір «RIVIERA CAMP 5*» Болгарія',
			'excerpt'   => 'Власний табір на Золотих Пісках: 15 днів моря, вихователів і програми Elita Tour.',
			'content'   => "<!-- wp:paragraph -->\n<p>Власний табір на Золотих Пісках: 15 днів моря, вихователів і програми Elita Tour.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p>Маршрут: Золоті Піски, Болгарія. Керівник групи супроводжує дітей від виїзду до повернення.</p>\n<!-- /wp:paragraph -->",
			'category'  => 'camps',
			'seasons'   => array( 'summer' ),
			'countries' => array( 'bolhariya' ),
			'hit'       => true,
			'image'     => 'beach-deckchair.jpg',
			'meta'      => array(
				'days'      => 15,
				'transport' => 'plane',
				'price'     => '',
				'route'     => array( 'Золоті Піски, Болгарія' ),
				'dates'     => array(
					array(
						'start'  => '2027-06-14',
						'end'    => '2027-06-28',
						'status' => 'ok',
					),
					array(
						'start'  => '2027-07-01',
						'end'    => '2027-07-15',
						'status' => 'few',
					),
					array(
						'start'  => '2027-07-18',
						'end'    => '2027-08-01',
						'status' => 'closed',
					),
				),
			),
		),
		array(
			'key'       => 'vitapark-polyana',
			'title'     => 'Осінні канікули в VitaPark Поляна 4* (Закарпаття)',
			'excerpt'   => 'Тиждень у Карпатах: мінеральні води Поляни й замок у Мукачеві.',
			'content'   => "<!-- wp:paragraph -->\n<p>Тиждень у Карпатах: мінеральні води Поляни й замок у Мукачеві.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p>Маршрут: Поляна — Мукачево. Керівник групи супроводжує дітей від виїзду до повернення.</p>\n<!-- /wp:paragraph -->",
			'category'  => 'ukraine',
			'seasons'   => array( 'autumn' ),
			'countries' => array( 'ukrayina' ),
			'hit'       => false,
			'image'     => 'synevyr-lake.jpg',
			'meta'      => array(
				'days'      => 7,
				'transport' => 'bus',
				'price'     => 'від 16300 ₴',
				'route'     => array( 'Поляна', 'Мукачево' ),
				'dates'     => array(
					array(
						'start'  => '2026-10-25',
						'end'    => '2026-10-31',
						'status' => 'ok',
					),
				),
			),
		),
		array(
			'key'       => 'krokers-camp',
			'title'     => 'Krokers camp',
			'excerpt'   => 'Активна зміна в Шешорах: Карпати, ватра, скелелазіння і сплав.',
			'content'   => "<!-- wp:paragraph -->\n<p>Активна зміна в Шешорах: Карпати, ватра, скелелазіння і сплав.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p>Маршрут: Шешори, Карпати. Керівник групи супроводжує дітей від виїзду до повернення.</p>\n<!-- /wp:paragraph -->",
			'category'  => 'camps',
			'seasons'   => array( 'winter', 'summer' ),
			'countries' => array( 'ukrayina' ),
			'hit'       => true,
			'image'     => 'carpathians.jpg',
			'meta'      => array(
				'days'      => 7,
				'transport' => 'bus',
				'price'     => 'від 18 000 ₴',
				'route'     => array( 'Шешори, Карпати' ),
				'dates'     => array(
					array(
						'start'  => '2027-01-04',
						'end'    => '2027-01-10',
						'status' => 'few',
					),
					array(
						'start'  => '2027-06-21',
						'end'    => '2027-06-27',
						'status' => 'ok',
					),
				),
			),
		),
		array(
			'key'       => 'charivnyi-svit-praha-i-viden',
			'title'     => 'Чарівний світ: Прага та Відень',
			'excerpt'   => 'Шість днів між двома столицями — різдвяні ярмарки або весняні парки.',
			'content'   => "<!-- wp:paragraph -->\n<p>Шість днів між двома столицями — різдвяні ярмарки або весняні парки.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p>Маршрут: Краків — Прага — Відень. Керівник групи супроводжує дітей від виїзду до повернення.</p>\n<!-- /wp:paragraph -->",
			'category'  => 'europe',
			'seasons'   => array( 'winter', 'spring' ),
			'countries' => array( 'chekhiya', 'avstriya' ),
			'hit'       => false,
			'image'     => 'vienna.jpg',
			'meta'      => array(
				'days'      => 6,
				'transport' => 'bus',
				'price'     => 'від 420 €',
				'route'     => array( 'Краків', 'Прага', 'Відень' ),
				'dates'     => array(
					array(
						'start'  => '2026-12-27',
						'end'    => '2027-01-01',
						'status' => 'ok',
					),
					array(
						'start'  => '2027-03-23',
						'end'    => '2027-03-28',
						'status' => 'few',
					),
				),
			),
		),
	),

	/*
	 * Pages. `holovna` becomes the static front page and `blog` the posts page;
	 * both are left without content, because the theme renders the landing
	 * sections on the front page and the post list on the blog page.
	 */
	'pages'      => array(
		array(
			'key'     => 'holovna',
			'title'   => 'Головна',
			'content' => '',
			'image'   => '',
		),
		array(
			'key'     => 'blog',
			'title'   => 'Блог',
			'content' => '',
			'image'   => '',
		),
		array(
			'key'     => 'pro-nas',
			'title'   => 'Про нас',
			'content' => "<!-- wp:paragraph -->\n<p>Elita Tour — київський туроператор дитячого й молодіжного відпочинку. Автобусні тури Європою, екскурсії Україною, літні зміни у власних таборах на морі. Кожна програма складена так, щоб дитина повернулась із враженнями, а вчитель чи батьки — без жодної зайвої турботи.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p>Працюємо з 1998 року. Знаємо, як провести групу через кордон, готель і музей без стресу. Elite Camp і Riviera Camp на Золотих Пісках — наші вихователі, наша програма, наш контроль.</p>\n<!-- /wp:paragraph -->",
			'image'   => 'budapest-chain-bridge.jpg',
		),
		array(
			'key'     => 'kontakty',
			'title'   => 'Контакти',
			'content' => "<!-- wp:paragraph -->\n<p>Туроператор дитячого та молодіжного відпочинку. Київ, з 1998 року.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:list -->\n<ul><li>Телефон: +38 067 431 89 24</li><li>E-mail: info@elitatour.com.ua</li><li>Пн–Пт 9:00–18:00</li></ul>\n<!-- /wp:list -->",
			'image'   => 'danube-budapest.jpg',
		),
	),

	/*
	 * Menus, keyed by the theme location they are assigned to. Items are
	 * resolved on import: `tour_category` and `page` items point at the demo
	 * term or page with that key, `custom` items at a path below the site URL.
	 */
	'menus'      => array(
		array(
			'location' => 'primary',
			'name'     => 'Головне меню',
			'items'    => array(
				array(
					'type'   => 'tour_category',
					'object' => 'camps',
				),
				array(
					'type'   => 'tour_category',
					'object' => 'europe',
				),
				array(
					'type'   => 'tour_category',
					'object' => 'ukraine',
				),
				array(
					'type'   => 'tour_category',
					'object' => 'adventure',
				),
				array(
					'type'   => 'page',
					'object' => 'pro-nas',
				),
				array(
					'type'   => 'page',
					'object' => 'kontakty',
				),
			),
		),
		array(
			'location' => 'footer-legal',
			'name'     => 'Footer legal',
			'items'    => array(
				array(
					'type'  => 'custom',
					'title' => 'Договір публічної оферти',
					'path'  => '/oferta/',
				),
			),
		),
	),

	/*
	 * Customizer settings of the Elita Tour theme. They are only applied when
	 * that theme is active and the setting has not been given a value yet.
	 * `elita_tour_about_page` is filled with the ID of the "Про нас" page.
	 */
	'theme_mods' => array(
		'elita_tour_phone'         => '+38 067 431 89 24',
		'elita_tour_cta_text'      => 'Підібрати програму',
		'elita_tour_cta_url'       => '#contact',
		'elita_tour_hero_eyebrow'  => 'Дитячий туроператор · з 1998',
		'elita_tour_hero_title'    => 'Куди їдемо',
		'elita_tour_hero_accent'   => 'на канікули?',
		'elita_tour_hero_lead'     => 'Табори на морі, автобусні тури Європою, екскурсії Україною та пригодницькі маршрути для шкільних груп і сімей. Оберіть напрям, решту організуємо ми.',
		'elita_tour_fact_1_number' => '28',
		'elita_tour_fact_1_accent' => '.',
		'elita_tour_fact_1_title'  => 'років дитячого туризму',
		'elita_tour_fact_1_text'   => 'Працюємо з 1998 року. Знаємо, як провести групу через кордон, готель і музей без стресу.',
		'elita_tour_fact_2_number' => '1',
		'elita_tour_fact_2_accent' => ':1',
		'elita_tour_fact_2_title'  => 'керівник групи від Elita Tour',
		'elita_tour_fact_2_text'   => 'Супроводжує від виїзду до повернення і щодня на зв’язку з батьками у спільному чаті.',
		'elita_tour_fact_3_number' => '2018',
		'elita_tour_fact_3_accent' => '',
		'elita_tour_fact_3_title'  => '«Вибір року» — золота медаль',
		'elita_tour_fact_3_text'   => 'У категорії дитячого відпочинку. Оцінка батьків, а не реклами.',
		'elita_tour_fact_4_number' => '2',
		'elita_tour_fact_4_accent' => '×',
		'elita_tour_fact_4_title'  => 'власні табори в Болгарії',
		'elita_tour_fact_4_text'   => 'Elite Camp і Riviera Camp на Золотих Пісках — наші вихователі, наша програма, наш контроль.',
		'elita_tour_stat_1_number' => '1998',
		'elita_tour_stat_1_label'  => 'рік заснування',
		'elita_tour_stat_2_number' => '39',
		'elita_tour_stat_2_label'  => 'програм у каталозі',
		'elita_tour_stat_3_number' => '12',
		'elita_tour_stat_3_label'  => 'країн Європи',
		'elita_tour_lead_heading'  => 'Розкажіть про групу — підберемо програму за день',
		'elita_tour_lead_text'     => 'Скільки дітей, якого віку, коли канікули і який бюджет. Решту ми зробимо самі.',
		'elita_tour_telegram'      => 'https://example.com/',
		'elita_tour_viber'         => 'https://example.com/',
		'elita_tour_facebook'      => 'https://example.com/',
		'elita_tour_instagram'     => 'https://example.com/',
	),

	// Settings → Reading: which demo page becomes the front page and the blog.
	'reading'    => array(
		'front_page' => 'holovna',
		'blog_page'  => 'blog',
	),
);

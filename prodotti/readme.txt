=== Prodotti ===
Contributors: RSW Studio, Matteo Testoni
Donate link: https://www.rswstudio.it
Tags: prodotti, catalogo, woocommerce, custom-post-type, metabox
Requires at least: 7.0
Tested up to: 7.2
Requires PHP: 8.3
Requires Plugins: meta-box
Stable tag: 2.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Plugin per la gestione del catalogo prodotti per WordPress, compatibile con gli slug dell'ultima versione di WooCommerce, tassonomie personalizzate, gallerie e allegati PDF.

== Description ==

ENG
Plugin for the management of products compatible with the latest WooCommerce slugs (`product`, `shop`, `product-category`, `product-tag`, and `pa_*` product attributes). Allows the management of categories, tags, colors, sizes, technologies, features, galleries, PDF attachments, and YouTube videos. Fully updated for PHP 8.3 and WordPress 7.0 - 7.2.

ITA
Plugin per la gestione dei prodotti compatibile con gli slug dell'ultima versione di WooCommerce (`product`, `shop`, `product-category`, `product-tag` e attributi `pa_*`). Permette la gestione di categorie, tag, colori, taglie, tecnologie, caratteristiche, gallerie fotografiche, allegati PDF e video YouTube. Aggiornato per PHP 8.3 e WordPress 7.0 - 7.2.

### URL del plugin: https://www.rswstudio.it/wordpress/plugin-prodotti-per-wordpress/

### Plugin Prodotti by https://www.rswstudio.it

== Installation ==

= ENG =
1. Upload the `prodotti` directory to `/wp-content/plugins/` or install the `.zip` archive via `Plugins > Add New`.
2. Activate the plugin through the `Plugins` menu in WordPress.
3. Install and activate **Meta Box** (automatically prompted by WordPress native Plugin Dependencies).

= ITA =
1. Carica la cartella `prodotti` via FTP in `/wp-content/plugins/` oppure carica il file `.zip` da `Plugin > Aggiungi nuovo`.
2. Attiva il plugin nella sezione `Plugin` della bacheca di WordPress.
3. Installa e attiva **Meta Box** (richiesto nativamente da WordPress tramite `Requires Plugins`).

== Frequently Asked Questions ==

= Quali versioni di WordPress e PHP sono supportate? =
Il plugin richiede almeno WordPress 7.0 e PHP 8.3, ed è testato fino a WordPress 7.2 (inclusa la piena compatibilità con WordPress 7.1 e l'Editor a Blocchi).

= Come vengono gestiti i plugin richiesti? =
Il plugin non utilizza più `TGM_Plugin_Activation`: sfrutta l'header nativo `Requires Plugins: meta-box` di WordPress e funzioni standard del core (`get_plugins()`, `is_plugin_active()`, `wp_admin_notice()`, `wp_nonce_url()`).

= Quali slug WooCommerce sono utilizzati? =
Custom Post Type `product` con permalink singolo `/product/` e archivio `/shop/`, tassonomie `product_cat` (`/product-category/`) e `product_tag` (`/product-tag/`), e attributi con prefisso `pa_` (`pa_colore`, `pa_tecnologia`, `pa_caratteristica`, `pa_taglia`), protetti da controlli `post_type_exists()` e `taxonomy_exists()` per evitare conflitti quando WooCommerce è attivo.

= Come si usa lo shortcode degli ultimi prodotti? =
Inserisci `[prodotti_ultimiinseriti]` in una pagina o in un blocco Shortcode. Puoi anche specificare `limit` e `categoria`, ad esempio `[prodotti_ultimiinseriti limit="6" categoria="novita"]`.

== Screenshots ==
1. screenshot-1.jpg

== Changelog ==

= 2.0.0 =
* Requisiti aggiornati: versione minima WordPress 7.0, testato fino a WordPress 7.2, compatibilità completa con WordPress 7.1 e PHP 8.3.
* Riorganizzata la struttura delle cartelle del plugin WordPress (`includes/`, `languages/`, `assets/img/`, `uninstall.php`).
* Rimossa la libreria `TGM_Plugin_Activation` e sostituita con l'header nativo `Requires Plugins: meta-box` e controlli standard WordPress (`get_plugins()`, `is_plugin_active()`, `wp_admin_notice()`, Thickbox e URL nonce nativi).
* Aggiornati gli slug per la compatibilità con l'ultima versione di WooCommerce: permalink singolo `product`, archivio `shop`, tassonomie `product_cat` (`product-category`) e `product_tag` (`product-tag`), e attributi `pa_colore`, `pa_tecnologia`, `pa_caratteristica`, `pa_taglia` con protezione `post_type_exists()` / `taxonomy_exists()`.
* Risolto errore fatale PHP 8.3 nel widget (`create_function()` sostituito con funzione nominata), rimosso `extract()`, aggiornata la tassonomia di ricerca a `product_cat` e aggiunto escaping completo.
* Risolti bug critici nello shortcode `[prodotti_ultimiinseriti]`: corretto il post type da `prodotti` a `product`, corretto l'accesso agli oggetti `WP_Post`, risolta la variabile di ritorno non definita `$strReturn` e aggiunti attributi `limit` e `categoria`.
* Risolto `post_updated_messages` (chiave corretta `product` invece di `prodotti` e controlli null-safe per PHP 8.3).
* Sostituito il filtro deprecato `contextual_help` con le schede `WP_Screen::add_help_tab()` su `current_screen`.
* Spostato `load_plugin_textdomain()` sull'hook `init` per evitare avvisi JIT di caricamento traduzioni in WordPress 7.x.
* Abilitato `show_in_rest => true` sul Custom Post Type `product` e su tutte le tassonomie per il supporto nativo all'Editor a Blocchi.
* Aggiornate le impostazioni in `prodotti-opzioni.php` con `sanitize_callback`, controllo permessi `manage_options`, escaping sicuro e correzione della chiave `prodotti_googleplus`.
* Aggiunti `uninstall.php`, `.svnignore` e workflow GitHub Actions (`.github/workflows/wordpress-plugin.yml`) per verifica sintassi PHP 8.3, pacchetto `.zip` e deploy SVN verso WordPress.org.

= 1.0.0 =
* Versione iniziale.

== Upgrade Notice ==

= 2.0.0 =
Aggiornamento obbligatorio per ambienti PHP 8.3 e WordPress 7.0–7.2. Rimuove TGM_Plugin_Activation, aggiorna gli slug a WooCommerce recente e risolve errori fatali nel widget e nello shortcode.

== Caratteristiche del plugin / Features of the plugin ==

= ENG =
1. Products Custom Post Type (`product`) compatible with latest WooCommerce slugs
2. Taxonomies: `product_cat`, `product_tag`, `pa_colore`, `pa_taglia`, `pa_tecnologia`, `pa_caratteristica`
3. Product Meta Boxes (code, showcase flag, image gallery, PDF attachments, YouTube video)
4. `[prodotti_ultimiinseriti]` shortcode and category search widget

= ITA =
1. Custom Post Type Prodotti (`product`) compatibile con gli slug dell'ultima versione di WooCommerce
2. Tassonomie: `product_cat`, `product_tag`, `pa_colore`, `pa_taglia`, `pa_tecnologia`, `pa_caratteristica`
3. Meta Box prodotto (codice, in vetrina, galleria immagini, allegati PDF, video YouTube)
4. Shortcode `[prodotti_ultimiinseriti]` e widget di ricerca per categoria

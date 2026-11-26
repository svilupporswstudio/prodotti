import prodottiMain from '../../prodotti/prodotti.php?raw';
import prodottiUninstall from '../../prodotti/uninstall.php?raw';
import prodottiReadme from '../../prodotti/readme.txt?raw';
import prodottiMetabox from '../../prodotti/includes/prodotti-metabox.php?raw';
import prodottiOpzioni from '../../prodotti/includes/prodotti-opzioni.php?raw';
import prodottiTassonomie from '../../prodotti/includes/prodotti-tassonomie.php?raw';
import prodottiShortcode from '../../prodotti/includes/prodotti-shortcode.php?raw';
import prodottiWidget from '../../prodotti/includes/prodotti-widget.php?raw';
import prodottiPluginAggiuntivi from '../../prodotti/includes/prodotti-pluginaggiuntivi.php?raw';
import githubWorkflow from '../../.github/workflows/wordpress-plugin.yml?raw';
import svnIgnore from '../../.svnignore?raw';
import repoReadme from '../../README.md?raw';

export interface PluginFileEntry {
  id: string;
  path: string;
  zipPath: string;
  folder: string;
  name: string;
  language: 'php' | 'txt' | 'yaml' | 'ignore';
  summary: string;
  content: string;
  inPluginZip: boolean;
}

export interface AuditChange {
  id: string;
  file: string;
  severity: 'Critico' | 'Compatibilità' | 'Sicurezza' | 'Struttura';
  issue: string;
  resolution: string;
}

export const PLUGIN_FILES: PluginFileEntry[] = [
  {
    id: 'prodotti-main',
    path: 'prodotti/prodotti.php',
    zipPath: 'prodotti/prodotti.php',
    folder: 'prodotti',
    name: 'prodotti.php',
    language: 'php',
    summary: 'Bootstrap principale, header WP 7.0–7.2 / PHP 8.3 / Requires Plugins: meta-box, CPT product (/product/ & /shop/).',
    content: prodottiMain,
    inPluginZip: true,
  },
  {
    id: 'readme',
    path: 'prodotti/readme.txt',
    zipPath: 'prodotti/readme.txt',
    folder: 'prodotti',
    name: 'readme.txt',
    language: 'txt',
    summary: 'Readme WordPress.org aggiornato (Requires at least 7.0, Tested up to 7.2, PHP 8.3, Requires Plugins, Changelog 2.0.0).',
    content: prodottiReadme,
    inPluginZip: true,
  },
  {
    id: 'uninstall',
    path: 'prodotti/uninstall.php',
    zipPath: 'prodotti/uninstall.php',
    folder: 'prodotti',
    name: 'uninstall.php',
    language: 'php',
    summary: 'Pulizia delle opzioni salvate al momento della disinstallazione del plugin.',
    content: prodottiUninstall,
    inPluginZip: true,
  },
  {
    id: 'metabox',
    path: 'prodotti/includes/prodotti-metabox.php',
    zipPath: 'prodotti/includes/prodotti-metabox.php',
    folder: 'prodotti/includes',
    name: 'prodotti-metabox.php',
    language: 'php',
    summary: 'Campi Meta Box per codice, vetrina, galleria immagini, allegati PDF e video YouTube.',
    content: prodottiMetabox,
    inPluginZip: true,
  },
  {
    id: 'opzioni',
    path: 'prodotti/includes/prodotti-opzioni.php',
    zipPath: 'prodotti/includes/prodotti-opzioni.php',
    folder: 'prodotti/includes',
    name: 'prodotti-opzioni.php',
    language: 'php',
    summary: 'Pagina impostazioni con sanitizzazione, controlli permessi ed escaping per PHP 8.3.',
    content: prodottiOpzioni,
    inPluginZip: true,
  },
  {
    id: 'tassonomie',
    path: 'prodotti/includes/prodotti-tassonomie.php',
    zipPath: 'prodotti/includes/prodotti-tassonomie.php',
    folder: 'prodotti/includes',
    name: 'prodotti-tassonomie.php',
    language: 'php',
    summary: 'Tassonomie product_cat (product-category), product_tag (product-tag) e attributi pa_* compatibili WooCommerce.',
    content: prodottiTassonomie,
    inPluginZip: true,
  },
  {
    id: 'shortcode',
    path: 'prodotti/includes/prodotti-shortcode.php',
    zipPath: 'prodotti/includes/prodotti-shortcode.php',
    folder: 'prodotti/includes',
    name: 'prodotti-shortcode.php',
    language: 'php',
    summary: 'Shortcode [prodotti_ultimiinseriti] corretto per WP_Post e post type product.',
    content: prodottiShortcode,
    inPluginZip: true,
  },
  {
    id: 'widget',
    path: 'prodotti/includes/prodotti-widget.php',
    zipPath: 'prodotti/includes/prodotti-widget.php',
    folder: 'prodotti/includes',
    name: 'prodotti-widget.php',
    language: 'php',
    summary: 'Widget di ricerca per categoria product_cat senza create_function() né extract().',
    content: prodottiWidget,
    inPluginZip: true,
  },
  {
    id: 'pluginaggiuntivi',
    path: 'prodotti/includes/prodotti-pluginaggiuntivi.php',
    zipPath: 'prodotti/includes/prodotti-pluginaggiuntivi.php',
    folder: 'prodotti/includes',
    name: 'prodotti-pluginaggiuntivi.php',
    language: 'php',
    summary: 'Gestione dipendenze plugin con funzioni standard WordPress (get_plugins, is_plugin_active, wp_admin_notice).',
    content: prodottiPluginAggiuntivi,
    inPluginZip: true,
  },
  {
    id: 'repo-readme',
    path: 'README.md',
    zipPath: 'README.md',
    folder: 'root',
    name: 'README.md',
    language: 'txt',
    summary: 'Documentazione repository GitHub con riepilogo versione 2.0.0 e istruzioni per la release.',
    content: repoReadme,
    inPluginZip: false,
  },
  {
    id: 'svnignore',
    path: '.svnignore',
    zipPath: '.svnignore',
    folder: 'root',
    name: '.svnignore',
    language: 'ignore',
    summary: 'Esclusioni per il deploy verso WordPress.org Plugin Directory (SVN) e la creazione dell\'archivio ZIP.',
    content: svnIgnore,
    inPluginZip: false,
  },
  {
    id: 'github-workflow',
    path: '.github/workflows/wordpress-plugin.yml',
    zipPath: '.github/workflows/wordpress-plugin.yml',
    folder: '.github/workflows',
    name: 'wordpress-plugin.yml',
    language: 'yaml',
    summary: 'Workflow GitHub Actions: lint PHP 8.3, verifica header WP 7.0–7.2, ZIP con .svnignore e deploy SVN.',
    content: githubWorkflow,
    inPluginZip: false,
  },
];

export const AUDIT_CHANGES: AuditChange[] = [
  {
    id: 'remove-tgmpa',
    file: 'prodotti.php · includes/prodotti-pluginaggiuntivi.php',
    severity: 'Struttura',
    issue: 'Dipendenza dalla libreria esterna obsoleta TGM_Plugin_Activation (v2.4.0 del 2014 con errori PHP 8.x e metodi deprecati).',
    resolution: 'Rimossa class-tgm-plugin-activation.php. Dichiarato header nativo "Requires Plugins: meta-box" e implementati controlli standard WordPress (get_plugins, is_plugin_active, wp_admin_notice, wp_nonce_url e Thickbox).',
  },
  {
    id: 'woocommerce-slugs',
    file: 'prodotti.php · includes/prodotti-tassonomie.php',
    severity: 'Compatibilità',
    issue: 'Slug non allineati a WooCommerce recente (rewrite "prodotti", assenza di rewrite su product_cat/product_tag, attributi senza prefisso pa_*).',
    resolution: 'Aggiornati slug a WooCommerce recente: product (/product/ e archivio /shop/), product_cat (product-category), product_tag (product-tag) e attributi pa_colore, pa_tecnologia, pa_caratteristica, pa_taglia con guardie post_type_exists() e taxonomy_exists().',
  },
  {
    id: 'create-function',
    file: 'includes/prodotti-widget.php',
    severity: 'Critico',
    issue: 'Uso di create_function() in widgets_init, funzione rimossa da PHP 8.0 (errore fatale in PHP 8.3).',
    resolution: 'Sostituita con funzione nominata prodotti_register_widgets() e register_widget( Prodotti_Widget::class ).',
  },
  {
    id: 'shortcode-bugs',
    file: 'includes/prodotti-shortcode.php',
    severity: 'Critico',
    issue: 'Post type errato ("prodotti" invece di "product"), accesso come array su oggetto WP_Post ($prodotto["ID"]) e variabile di ritorno $strReturn non definita.',
    resolution: 'Corretto post_type => "product", accesso alle proprietà WP_Post, return $output con escaping e aggiunti attributi limit/categoria.',
  },
  {
    id: 'widget-taxonomy',
    file: 'includes/prodotti-widget.php',
    severity: 'Critico',
    issue: 'Il widget interrogava get_terms("categoria") anziché "product_cat", usava extract() e non gestiva chiavi mancanti in $instance.',
    resolution: 'Aggiornato a get_terms( array( "taxonomy" => "product_cat" ) ), rimosso extract(), aggiornato fallback archivio a /shop/ e aggiunto sanitizing completo.',
  },
  {
    id: 'wp7-textdomain',
    file: 'prodotti.php',
    severity: 'Compatibilità',
    issue: 'load_plugin_textdomain() eseguita al caricamento del file causa avvisi _load_textdomain_just_in_time in WordPress 6.7 / 7.x.',
    resolution: 'Spostato il caricamento delle traduzioni su add_action( "init", "prodotti_load_textdomain", 1 ).',
  },
  {
    id: 'contextual-help',
    file: 'prodotti.php',
    severity: 'Compatibilità',
    issue: 'Hook deprecato contextual_help con confronto errato ($screen->id == "prodotti" invece di "product").',
    resolution: 'Sostituito con current_screen + $screen->add_help_tab() e set_help_sidebar() per il post type "product".',
  },
  {
    id: 'updated-messages',
    file: 'prodotti.php',
    severity: 'Compatibilità',
    issue: '$messages["prodotti"] non corrispondeva a "product" e provocava Warning su $post o $post_type_object nulli in PHP 8.3.',
    resolution: 'Corretta la chiave in $messages["product"] e aggiunti controlli instanceof WP_Post / WP_Post_Type.',
  },
  {
    id: 'rest-api-support',
    file: 'prodotti.php · includes/prodotti-tassonomie.php',
    severity: 'Compatibilità',
    issue: 'Mancanza di show_in_rest su CPT e tassonomie per Editor a Blocchi / REST API WordPress 7.x.',
    resolution: 'Abilitato show_in_rest => true su product e su tutte le 6 tassonomie.',
  },
  {
    id: 'options-security',
    file: 'includes/prodotti-opzioni.php',
    severity: 'Sicurezza',
    issue: 'Refuso prodotti_agoogleplus in register_setting(), assenza di sanitize_callback, password in chiaro e output non escapato.',
    resolution: 'Allineata la chiave prodotti_googleplus, aggiunti sanitize_callback (esc_url_raw / sanitize_text_field), input type="password" ed esc_attr().',
  },
  {
    id: 'svnignore-workflow',
    file: '.svnignore · .github/workflows/wordpress-plugin.yml',
    severity: 'Struttura',
    issue: 'Assenza di file .svnignore e workflow CI/CD per WordPress.org Plugin Directory.',
    resolution: 'Creato .svnignore per escludere file di sviluppo e configurato workflow GitHub Actions con lint PHP 8.3, build ZIP via .svnignore e deploy SVN.',
  },
];

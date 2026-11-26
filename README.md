# Prodotti — Plugin WordPress (v2.0.0)

Plugin WordPress per la gestione del catalogo prodotti compatibile con gli slug dell'ultima versione di WooCommerce, categorie, tag, attributi (`pa_*`), gallerie fotografiche, allegati PDF e video YouTube.

- **Versione plugin:** `2.0.0`
- **WordPress minimo:** `7.0`
- **Testato fino a WordPress:** `7.2` (compatibile con WordPress `7.1` e Editor a Blocchi)
- **PHP richiesto:** `8.3+`
- **Plugin richiesto:** `meta-box` (dichiarato tramite header nativo `Requires Plugins`)
- **Licenza:** GPLv2 o successiva

---

## Modifiche principali della versione 2.0.0

1. **Struttura standard WordPress & `.svnignore`**
   - Organizzazione modulare in `prodotti/` (`prodotti.php`, `readme.txt`, `uninstall.php`, `includes/`, `languages/`, `assets/img/`).
   - Aggiunto `.svnignore` per escludere i file di sviluppo e configurazione dai deploy verso la WordPress.org Plugin Directory (SVN) e dai pacchetti `.zip`.
2. **Compatibilità PHP 8.3 e WordPress 7.0 – 7.2**
   - Risolto errore fatale nel widget sostituendo `create_function()` (rimossa da PHP 8.0) con funzione nominata ed eliminato `extract()`.
   - Risolti bug nello shortcode `[prodotti_ultimiinseriti]` (corretto `post_type => 'product'`, accesso alle proprietà `WP_Post`, variabile di ritorno definita e attributi `limit`/`categoria`).
   - Spostato `load_plugin_textdomain()` sull'hook `init` (compatibilità caricamento traduzioni JIT WordPress 7.x).
   - Sostituito il filtro deprecato `contextual_help` con `WP_Screen::add_help_tab()` su `current_screen`.
   - Abilitato `show_in_rest => true` su Custom Post Type `product` e su tutte le tassonomie.
   - Aggiunti controlli null-safe, sanitizzazione (`sanitize_text_field`, `esc_url_raw`) ed escaping completo in tutte le viste e nelle impostazioni.
3. **Rimozione di `TGM_Plugin_Activation`**
   - Eliminata la libreria esterna `TGM_Plugin_Activation`.
   - Sostituita con l'header nativo `Requires Plugins: meta-box` e controlli basati esclusivamente su funzioni standard WordPress (`get_plugins()`, `is_plugin_active()`, `wp_admin_notice()`, `wp_nonce_url()`, Thickbox).
4. **Allineamento slug WooCommerce**
   - CPT `product` con permalink singolo `/product/` e archivio `/shop/` (protetto con `post_type_exists('product')`).
   - Tassonomie `product_cat` (`product-category`), `product_tag` (`product-tag`) e attributi `pa_colore`, `pa_tecnologia`, `pa_caratteristica`, `pa_taglia` (protetti con `taxonomy_exists()`).
5. **CI/CD GitHub Actions**
   - Aggiunto `.github/workflows/wordpress-plugin.yml` per il controllo sintattico PHP 8.3, la verifica dei metadati WordPress 7.0–7.2, la generazione di `prodotti.zip` (con `.svnignore`) e la pubblicazione automatica su GitHub Release alla creazione di un tag `v*`.

---

## Pubblicazione su GitHub e creazione Release 2.0

Inizializza il repository locale (se non ancora collegato), pubblica sul tuo repository GitHub e crea il tag `v2.0.0` per attivare automaticamente il workflow di release:

```bash
git init
git add .
git commit -m "Release 2.0.0: PHP 8.3, WordPress 7.0-7.2, WooCommerce slugs, native plugin deps"
git branch -M main
git remote add origin https://github.com/<tuo-utente>/<tuo-repository>.git
git push -u origin main

git tag -a v2.0.0 -m "Release 2.0.0"
git push origin v2.0.0
```

Al push del tag `v2.0.0`, GitHub Actions eseguirà il controllo PHP 8.3 e pubblicherà automaticamente la GitHub Release `v2.0.0` con il pacchetto `prodotti.zip` pronto per l'installazione in WordPress.

# Puike Links Hub

Eén publieke pagina op je eigen WordPress-site met een avatar, bio, thema en
een lijst klikbare links — inclusief geplande links, social-iconen en
click-analytics. Ideaal als centrale landingspagina voor bijvoorbeeld je
bio-link op social media. Geen externe diensten, geen zware dependencies,
gewoon een WordPress-plugin.

## Features

- Eigen link-pagina op `/links/{slug}`, los van je thema
- Avatar (featured image), bio en kleurthema (licht/donker/aangepast met eigen accentkleur)
- Optionele achtergrondafbeelding via de mediabibliotheek, met automatisch leesbare kaart erbovenop
- Drag-and-drop links-builder in het WP-admin, geen page builder nodig
- Ingebouwde iconenset (social + algemeen) om per link te kiezen via een dropdown met live preview
- Links en social-iconen, elk optioneel te plannen (start-/einddatum) — ideaal voor tijdelijke acties
- Click-tracking per link, met een overzicht per link in de admin
- Werkt zonder JavaScript op de publieke pagina (redirect-gebaseerde tracking)

## Vereisten

- PHP 8.3 of hoger
- WordPress met een normale rewrite/permalink-structuur (niet "Gewoon")

## Installatie

Via Composer (met [composer/installers](https://github.com/composer/installers)):

```bash
composer require puikepixels/links-hub
```

Of download de repository en plaats de map in `wp-content/plugins/`.

Activeer daarna de plugin en ga naar **Instellingen → Permalinks** om de
rewrite-regels te verversen (dit gebeurt automatisch bij activeren, maar een
handmatige "Wijzigingen opslaan" kan geen kwaad als `/links/...` een 404 geeft).

## Gebruik

1. Ga naar **Links Hub → Nieuwe links pagina** in het WP-admin.
2. Geef de pagina een titel — dit wordt ook de URL-slug (`/links/{slug}`) en de
   titel op de publieke pagina.
3. Stel via de **Uiterlijk**-metabox de bio, het thema (licht/donker/aangepast)
   en de accentkleur in. De avatar stel je in via de featured image.
4. Voeg via de **Links**-metabox links en/of social-iconen toe:
   - Sleep aan het handvat (☰) om de volgorde te wijzigen.
   - Kies een icoon uit de dropdown — de preview ernaast toont direct hoe het
     eruitziet.
   - Vink "Actief" uit om een link tijdelijk te verbergen zonder 'm te
     verwijderen.
   - Vul "Zichtbaar vanaf" / "Zichtbaar tot" in om een link automatisch te
     laten verschijnen/verdwijnen (bijvoorbeeld voor een actie of livestream).
5. Publiceer de pagina. De **Statistieken**-metabox laat per link zien hoe
   vaak erop geklikt is.

Elke klik op een link gaat via `/links/{slug}/go/{link-id}`, wordt gelogd in
een eigen database-tabel, en stuurt vervolgens door naar de echte URL.

## Ontwikkelen / lokaal testen

Deze package staat los van een WordPress-installatie. Om lokaal te testen:

```bash
ln -s /pad/naar/wp-puikepixels-links-hub wp-content/plugins/wp-puikepixels-links-hub
wp plugin activate wp-puikepixels-links-hub
wp rewrite flush
```

Alle PHP-bestanden zijn lint-baar met `php -l`. Er is geen build-stap nodig:
de admin- en frontend-assets zijn losse, dependency-vrije CSS/JS-bestanden.

## Templates overschrijven

De publieke pagina is volledig te overschrijven vanuit je thema, op twee niveaus:

**1. De hele pagina** — kopieer `templates/single-pp_link_page.php` naar
`yourtheme/puike-links-hub/single-pp_link_page.php` voor volledige controle
over de HTML-structuur (bijvoorbeeld om een heel andere lay-out te bouwen).

**2. Losse onderdelen** — kopieer alleen het stukje dat je wilt aanpassen naar
`yourtheme/puike-links-hub/parts/{naam}.php`, de rest blijft van de plugin
komen:

| Part | Bestand | Variabelen |
|---|---|---|
| Avatar | `parts/avatar.php` | `$post_id` |
| Bio | `parts/bio.php` | `$bio` |
| Social-iconen | `parts/social-links.php` | `$social`, `$permalink` |
| Links-lijst | `parts/links-list.php` | `$regular`, `$permalink` |

Zo kun je bijvoorbeeld alleen `parts/links-list.php` overschrijven om de
knopstijl aan te passen, zonder de hele pagina (inclusief `wp_head()`/
`wp_footer()`-integratie) opnieuw te hoeven bouwen.

De submap-naam (`puike-links-hub/`) is aan te passen via het filter
`pp_links_hub_template_path`, en het uiteindelijk gekozen bestand via
`pp_links_hub_locate_template` — handig voor child-plugins die per site een
andere template-set willen laden.

## Datamodel

- Custom post type `pp_link_page` (titel = paginanaam/slug, featured image = avatar)
- Postmeta: `_pp_bio`, `_pp_theme_preset`, `_pp_theme_accent`, `_pp_background_id` (attachment-ID), `_pp_links` (JSON)
- Eigen tabel `{prefix}pp_links_hub_clicks` voor click-analytics (aangemaakt bij activatie, opgeruimd bij verwijderen van de plugin)

## Ontwikkeld door

[Puike Pixels](https://puikepixels.com)

## Licentie

GPL-2.0-or-later — zie [LICENSE](LICENSE).

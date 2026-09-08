# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.1] - 2026-09-08

### Fixed

- Resolved WordPress Plugin Check / PHPCS findings: literal (non-constant) text domains for i18n calls, escaped/sanitized `$_POST` and database access, escaped template output, and renamed an internal `get_links()` helper that collided with a deprecated core function name.
- Completed the `readme.md` plugin header block (Tested up to, License, Stable tag) and trimmed the short description to fit the 150-character limit.

## [1.0.0] - 2026-09-07

### Added

- Public link page at `/links/{slug}`, independent of the active theme.
- Avatar (featured image), bio and color theme (light/dark/custom accent color).
- Optional background image via the media library.
- Drag-and-drop links builder in the WP admin.
- Built-in icon set (social + general) with live preview.
- Scheduling (start/end date) for individual links and social icons.
- Click tracking per link with an overview in the admin.
- Template overrides at both the full-page and part level via the theme.

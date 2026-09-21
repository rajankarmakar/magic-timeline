# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What this is

A single-purpose Elementor widget plugin (no Elementor Pro dependency): a vertical timeline built from a manual repeater (icon badge + connecting line, date pill, title with optional status badge, description, optional button). Built for marketplace distribution (e.g. CodeCanyon), so escaping, prefixing, and PHP-notice cleanliness are load-bearing, not optional polish.

## Known pitfalls — do not reintroduce

**Never let CSS default any `.mtl-*` content to hidden (`opacity: 0`, `visibility: hidden`, `display: none`, etc.) pending a JS action to reveal it — even for the entrance animation.** A prior version of `assets/css/timeline.css` did exactly this for the scroll-in animation (items started at `opacity: 0`, only a JS-added `.mtl-in-view` class revealed them), and it made every timeline item permanently invisible inside Elementor's own editor preview iframe, because the widget's AJAX-injected markup didn't reliably fire `timeline.js`'s init triggers there — while the PHP render and CSS were both completely correct, so it looked like a phantom rendering bug. If IntersectionObserver, or any future JS-driven visual feature, needs a "before" state, implement it the way the fix does now: JS adds an opt-in class (e.g. `.mtl-js-animating`) to the wrapper *immediately before* it starts acting on an element, and only *that* class's presence — never the plain base state — triggers the hidden/pre-animation CSS. If the script never runs for any reason, content must fall through to fully visible by default. See the `assets/js/timeline.js` entry under Architecture below for the full mechanism.

**`load_plugin_textdomain()` in `magic-timeline.php` must stay, despite Plugin Check flagging it as discouraged (suppressed there with an inline `phpcs:ignore` and a comment explaining why).** Verified empirically, not just by reading docs: WordPress core's automatic just-in-time translation loading (`WP_Textdomain_Registry::get_paths_for_domain()`) only ever checks `wp-content/languages/plugins/` (where WordPress.org's own translation system deposits files) — a plugin's own bundled `languages/` folder is *only* discoverable if `load_plugin_textdomain()` registers it as a custom path. Removing the call would silently break the bundled `.pot`/future `.mo` files for anyone installing via GitHub releases rather than WordPress.org. Don't trust a test of this on `tastegallery.test` (see below) — test it on an isolated instance if it's ever in question again. WordPress.org's own manual reviewers flagged this same call during the initial submission review, suggesting it be removed entirely (true for WP.org-only distribution, since 4.6's JIT loading covers that case) or, if still needed, hooked on `init` rather than called inline during `plugins_loaded` (per WP core's [6.7 i18n improvements](https://make.wordpress.org/core/2024/10/21/i18n-improvements-6-7/)) — the call was kept (for the GitHub-release reason above) and moved to `init` via a dedicated `Magic_Timeline_Loader::load_textdomain()` method, which satisfies both concerns without dropping the GitHub-only translation path.

**Never test i18n/text-domain-loading behavior on `tastegallery.test`, and never bulk-deactivate its plugins for any test.** That site has both Loco Translate and Polylang active, both of which patch directly into WordPress core's translation-loading filters (`lang_dir_for_domain`, `pre_get_language_files_from_path`) to add their own fallback discovery — so any experiment involving `load_plugin_textdomain()`, `__()`, or gettext domains will give a false result there that doesn't reflect what an ordinary end user's site does. Separately, and more importantly: **the active theme (Electrolux) hard-depends on Polylang's `pll_register_string()` at load time** — deactivating Polylang there (even briefly, even just to isolate a test variable) fatals the entire site immediately, for every single page and every `wp` command, since the theme loads unconditionally during WordPress's core bootstrap. This already happened once. If a test genuinely needs a plugin's original active-plugin state changed, capture the exact list first (`wp plugin list --status=active --field=name`) so it can be restored — and if the site ends up broken this way, `wp plugin activate` cannot fix it (the fatal happens before WP-CLI's command logic even runs); the only way back in is a direct `wp db query` write to the `active_plugins` option (table prefix on this install is `elx_`, not `wp_`) using a re-serialized full plugin list, bypassing the broken bootstrap entirely.

For anything that genuinely needs a clean-room WordPress (no third-party plugins, no shared-site side effects) — like the i18n test above — build a fully disposable instance instead of touching `tastegallery.test`:
```bash
mysql -u root -prajan8382 -e "CREATE DATABASE IF NOT EXISTS some_throwaway_name;"
wp core download --version=6.8.1 --path=/path/to/scratch/dir
wp config create --path=/path/to/scratch/dir --dbname=some_throwaway_name --dbuser=root --dbpass=rajan8382 --dbhost=localhost --skip-check
wp core install --path=/path/to/scratch/dir --url="http://test.local" --title="Test" --admin_user=admin --admin_password=admin --admin_email=test@example.com --skip-email
# copy the plugin folder in, `wp plugin activate <slug> --path=...`, test, then drop the database and delete the directory
```
(DB credentials here are this machine's local dev MySQL root password from `tastegallery`'s own `wp-config.php` — safe to reuse for a same-machine throwaway database, not a real secret.)

## Commands

Run these from the plugin root (`wp-content/plugins/magic-timeline`).

```bash
# PHP syntax check
php -l widgets/class-timeline-widget.php

# WordPress coding standards (the project standard is WordPress-Extra)
phpcs --standard=WordPress-Extra --extensions=php --ignore=vendor,node_modules --exclude=WordPress.Files.FileName .

# Auto-fix fixable coding-standard violations
phpcbf --standard=WordPress-Extra --extensions=php --ignore=vendor,node_modules --exclude=WordPress.Files.FileName .

# Check the code doesn't use PHP syntax newer than the declared 7.4 floor
phpcs --standard=PHPCompatibilityWP --runtime-set testVersion 7.4- --extensions=php --ignore=vendor,node_modules,dist .

# Regenerate the translation template after adding/changing any __()/_e() string
wp i18n make-pot . languages/magic-timeline-for-elementor.pot --domain=magic-timeline-for-elementor --slug=magic-timeline-for-elementor

# Check version numbers are consistent and the changelog documents them (also run by CI)
bin/check-versions.sh

# Regenerate CHANGELOG.md from readme.txt (run after editing readme.txt's Changelog section)
bin/generate-changelog.sh

# Package a buyer-ready release zip at dist/magic-timeline-for-elementor-<version>.zip
bin/build-release.sh
```

There is no build step, bundler, or PHP test suite in this plugin — it's plain PHP/CSS/JS enqueued directly by WordPress. `bin/build-release.sh` is packaging, not a build: it copies a fixed allow-list of shippable paths (`magic-timeline.php`, `includes/`, `widgets/`, `assets/`, `demo-data/`, `documentation/`, `languages/`, `readme.txt`, `license.txt`, `CHANGELOG.md`) into `dist/magic-timeline/` and zips it — nothing is compiled or transformed. `CLAUDE.md`, `store-assets/`, `bin/`, `.gitignore`, and `dist/` itself are excluded simply by not being on that list; if a new top-level file/folder is ever added to the plugin that buyers should receive, it must be added to the `INCLUDE` array in the script or it will silently be left out of the zip.

Before it packages anything, the script also refuses to run unless the release is actually documented:
- The plugin header's `Version:`, the `MAGIC_TIMELINE_VERSION` constant, and `readme.txt`'s `Stable tag` must all match — this guards against exactly the kind of stale-version mismatch that shipped once already.
- `readme.txt`'s `== Changelog ==` section must have a `= <version> =` entry for the version being released, or the script exits with an error rather than packaging an undocumented release.

**`readme.txt` is the single source of truth for the changelog — `CHANGELOG.md` is a generated file, not hand-edited.** `bin/generate-changelog.sh` regenerates it from `readme.txt`'s `== Changelog ==` section (converting `= X.Y.Z =` headings to `## X.Y.Z` and `* ` bullets to `- `); `bin/build-release.sh` calls it as one step of packaging. Never edit `CHANGELOG.md` directly — CI (`.github/workflows/ci.yml`) regenerates it into a temp path and diffs against the committed copy, failing the build if they don't match, and the next release run would overwrite manual edits anyway. To add a changelog entry for a new release: write it once in `readme.txt`'s `== Changelog ==` section (and its `== Upgrade Notice ==` section, by existing convention), then run `bin/generate-changelog.sh` (or the full `bin/build-release.sh`) locally and commit the result before opening a PR — CI's diff check exists specifically to catch forgetting this step.

**`bin/check-versions.sh`** holds the version-consistency + changelog-entry-exists checks (shared by `bin/build-release.sh` and CI) — it's the single place that logic lives; don't re-inline it elsewhere.

**CI/CD** (`.github/workflows/`): `ci.yml` runs on every push/PR — PHP syntax across a small version matrix, `phpcs --standard=WordPress-Extra` (with `WordPress.Files.FileName` excluded — see below), a `PHPCompatibilityWP` check pinned to the 7.4 floor, `bin/check-versions.sh`, and the `CHANGELOG.md`-is-in-sync diff check described above. `main` is protected: all of those must pass before a PR can merge, and direct pushes (including from admins) are blocked. `release.yml` runs on every push to `main`; if `magic-timeline.php`'s version is newer than the latest git tag it re-validates with `bin/check-versions.sh`, builds the zip with `bin/build-release.sh`, tags `v<version>`, and publishes a GitHub Release with that zip attached and the matching `CHANGELOG.md` section as release notes — if the version didn't change, the workflow is a no-op. This means **a version bump merged into `main` immediately and automatically publishes a public release** — don't bump the version in a PR until it's actually ready to ship.

### Releasing a change

`main` is protected (no direct pushes, `enforce_admins` on), so every change — including a version bump — goes through a branch and PR:

```bash
git checkout main && git pull --ff-only origin main
git checkout -b feature/short-name          # or fix/short-name
# ... make the change; for a release, also bump Version everywhere (see above) and add a readme.txt changelog entry ...
bin/build-release.sh                        # sanity-checks versions, regenerates CHANGELOG.md, builds a test zip
git add -A && git commit -m "..."
git push -u origin feature/short-name
gh pr create --base main --head feature/short-name --title "..." --body "..."
gh pr checks <number>                       # repeat until PHP Syntax (7.4/8.3), Code Standards, Versions & Changelog all show "pass"
gh pr merge <number> --squash --delete-branch
git checkout main && git pull --ff-only origin main
```

If the merged PR bumped the version, `release.yml` takes it from there automatically (tags `v<version>`, builds the zip, publishes the GitHub Release) — there is no manual release step. A PR that doesn't bump the version is just a normal merge; nothing else happens.

**Security review is a local step, not a CI gate.** There was a `security-review.yml` workflow using Anthropic's `claude-code-security-review` GitHub Action, deliberately removed because it needs an `ANTHROPIC_API_KEY` repo secret, which this project doesn't want to provision. Instead, run Claude Code's own `/security-review` slash command locally against your changes before opening a PR. If CI/CD requirements ever change and an automated gate becomes worth the API key, the removed workflow's design (including the `run-every-commit: true` fix — the action caches "already scanned this PR" by PR number, not by commit, and silently skips re-scanning on later pushes without it) is preserved in git history (`security-review.yml`, added then removed shortly after in the same PR that first set up this repo's CI/CD).

**`WordPress.Files.FileName` is excluded from the WordPress-Extra check everywhere** (`ci.yml` and the commands above) because it flags `magic-timeline.php` for containing `Magic_Timeline_Loader` instead of living in a matching `class-magic-timeline-loader.php` — deliberate, per the Load order note above. An inline `// phpcs:ignore` comment does **not** work for this specific sniff: it always reports on line 1 of the file regardless of which line the offending class is actually declared on, so a line-anchored ignore comment can never target it. The CLI-level `--exclude` is the only fix that actually works; don't reintroduce a per-line ignore comment expecting it to suppress this one.

### Manual verification against a live WordPress install

This machine has a local WordPress copy at `/Users/rajan/Documents/www/tastegallery` (served at `http://tastegallery.test`, DB `tastegallery`) with Elementor and Elementor Pro installed — use it to sanity-check changes end to end instead of reasoning about Elementor's control/render pipeline in the abstract.

`wp-cli`'s default 128M memory limit is exhausted by this particular install's full plugin set (Elementor Pro + WooCommerce + everything else) — always pass `-d memory_limit=512M` to `wp` or commands fail with an unrelated-looking fatal:

```bash
cd /Users/rajan/Documents/www/tastegallery
php -d memory_limit=512M /usr/local/bin/wp plugin activate magic-timeline
php -d memory_limit=512M /usr/local/bin/wp post create --post_title="QA Test" --post_status=publish --post_type=page --porcelain
# then set _elementor_data (JSON string of an Elementor "content" array — see demo-data/sample-timeline.json's
# "content" key for the shape), _elementor_edit_mode=builder, _elementor_template_type=wp-page,
# _wp_page_template=elementor_canvas via `wp post meta update`, and `wp elementor flush-css`
```

Check `wp-content/debug.log` after loading the page (`WP_DEBUG`/`WP_DEBUG_LOG` are on in that install's `wp-config.php`) — any PHP notice/warning attributable to this plugin is a bug, since marketplace review checks for exactly that. Delete any test page/post you create there when done (`wp post delete <id> --force`); don't leave scratch content in that database's real page list. That database is a synced copy of a real client site's content — don't touch users, orders, or any existing page/post while using it for QA.

## Architecture

**Load order**: `magic-timeline.php` defines constants and a `Magic_Timeline_Loader` singleton that runs Elementor/PHP version gating on `plugins_loaded` before requiring anything else. Only if `is_compatible()` passes does it load `includes/class-plugin.php` and instantiate `Magic_Timeline\Plugin`. Never move widget or asset registration into the top-level plugin file — it must stay behind the compatibility gate so the plugin fails soft (an admin notice) on sites without Elementor, instead of a fatal error.

**`Magic_Timeline\Plugin`** (`includes/class-plugin.php`) is where all Elementor integration hooks live: registers the `magic-timeline` widget category, registers `Magic_Timeline\Widgets\Timeline_Widget` on `elementor/widgets/register`, and *registers* (not enqueues) the CSS/JS handles on `elementor/frontend/after_register_styles` / `after_register_scripts`. The widget declares `get_style_depends()` / `get_script_depends()` returning those handle names, which is what actually causes Elementor to enqueue them — only when the widget is used on a page. Don't switch this to an unconditional `wp_enqueue_*` on every page load.

**`Magic_Timeline\Widgets\Timeline_Widget`** (`widgets/class-timeline-widget.php`) is the entire widget. `register_controls()` fans out to one private method per control section (`register_content_layout_controls`, `register_style_icon_controls`, etc.) purely to keep the file navigable — there's no other significance to the split, and a new style section should follow the same one-method-per-section pattern rather than being folded into an existing method.

**Style controls bridge to CSS via custom properties, not direct selectors, for anything the base CSS needs to compute from (sizes that other rules depend on).** For example the icon-size slider control writes `--mtl-icon-size` onto the wrapper (`'{{WRAPPER}} .mtl-timeline' => '--mtl-icon-size: {{SIZE}}{{UNIT}};'`), and `assets/css/timeline.css`'s connecting-line `::before` and marker column both read that variable to stay aligned. When adding a new size/color control that other CSS rules need to reference (not just apply to one element), follow this custom-property pattern rather than duplicating the value across multiple `selectors` entries — it's the only way the line stays centered on the icon regardless of the icon-size setting. Purely local, single-element style controls (e.g. title color) just target the element's selector directly, no variable needed.

**The alternating (zig-zag) and horizontal layouts are pure CSS, not JS.** Both are gated behind the same `@media (min-width: 992px)` breakpoint in `assets/css/timeline.css` and fall back to the base single-column flex layout below it — there's no JS-driven breakpoint logic to replicate this in `assets/js/timeline.js`, and any new layout mode should follow the same "collapse to single-column below the breakpoint" contract rather than trying to make a complex layout also work on a phone-width screen.
- Alternating: a `grid-template-columns: 1fr auto 1fr` layout where the two `1fr` columns are guaranteed equal width, which is what keeps the marker column — and the line's `left: 50%` — centered regardless of alternating content widths. Even-indexed items get their content pushed to the left `1fr` column via `:nth-child(even)`.
- Horizontal: `.mtl-timeline` becomes a `display: flex; flex-direction: row; overflow-x: auto;` row of fixed-width cards (width set via the `--mtl-horizontal-item-width` custom property, which the "Item Width" style control writes). The connecting line is **not** the usual absolutely-positioned `::before` on `.mtl-timeline` — that element's own box is the unscrolled container width, so a line positioned relative to it would stay fixed while the cards scrolled underneath it. Instead each item (except the first) draws its own horizontal segment via `::before`, sized to exactly fill the flex `gap` to its left — so the line is made of scrolling per-item pieces rather than one fixed line, and stays visually continuous with the icons regardless of scroll position. Follow this same per-item-segment technique for any future layout that needs a connector line inside a scrolling container.

**`assets/js/timeline.js` is progressive enhancement only** (IntersectionObserver-driven entrance animation). It must degrade to fully visible, non-broken content if JS fails to load or `IntersectionObserver` is unavailable — never make layout or content visibility depend on JS running. This isn't just a design goal: the CSS enforces it structurally. `.mtl-item` has no default `opacity`/`transform` hiding it; the pre-animation hidden state only applies under `.mtl-timeline.mtl-js-animating .mtl-item:not(.mtl-in-view)`, and `.mtl-js-animating` is a class the JS adds itself right before it starts observing an item — never present in the base CSS. So if the script never runs at all (blocked, errors out, or — as happened once — never gets a chance to fire because a host environment injects the widget's markup after `DOMContentLoaded` in a way that skips both of `timeline.js`'s init triggers), items simply stay at their default visible state instead of being stuck at `opacity: 0` forever. Do not "simplify" this back to a CSS rule that defaults items to hidden and waits for JS to reveal them — that inverts the fail-safe and reintroduces exactly this bug. (Concretely: this bit Elementor's own editor preview iframe, where AJAX-injected widget markup didn't reliably trigger `frontend/element_ready/magic-timeline.default` — `init()` is idempotent and is also re-run on `window.load` as a bounded fallback, but the CSS-level fail-safe is what actually guarantees correctness regardless of which init trigger fires or fails.)

**Rendering (`Timeline_Widget::render()` / `render_item()`)**: every field is escaped at output (`esc_html`, `esc_url`, `esc_attr`, `wp_kses_post`), and icons are rendered exclusively through `\Elementor\Icons_Manager::render_icon()`, never by hand-assembling an `<i>` tag from the icon control's array. When adding a new repeater field, follow the existing isset/empty-check-then-escape pattern in `render_item()` — this codebase does not use Elementor's `content_template()` JS-templating path (the widget re-renders server-side via AJAX in the editor preview instead), so there's no parallel JS template to keep in sync with PHP changes.

**Text domain is `magic-timeline-for-elementor`** everywhere — every user-facing string must be wrapped in `__()`/`_e()` with that exact domain, then swept into `languages/magic-timeline-for-elementor.pot` via the `wp i18n make-pot` command above before committing. (The display name is "Magic Timeline for Elementor" — this differs from the internal `magic-timeline` slug used for the widget's `get_name()`/category/style/script handles and the plugin's own folder/main-file name, which were deliberately left unchanged; only the public-facing name and the text domain were renamed to satisfy WordPress.org's naming-distinctiveness review. If/when WordPress.org confirms the `magic-timeline-for-elementor` slug reservation, the plugin folder and main file should eventually be renamed to match — not done yet since that would break the active-plugin path on `tastegallery.test` mid-review.)

**`demo-data/sample-timeline.json`** is a full Elementor template-import file (the format Elementor's own "Import Template" screen expects — top-level `content`/`page_settings`/`version`/`title`/`type` keys), not a plugin-internal format. Its `settings.timeline_items` values must stay in sync with the widget's repeater control names in `class-timeline-widget.php` — if a repeater field is renamed, update this file too or the imported template will silently drop that field's value.

**`store-assets/`** holds marketplace/listing images (icon, banner, screenshots) — it is marketing material, not part of the plugin, and must never be included in a packaged release zip. The `.html`/`.svg` sources are kept alongside the generated `.png` files so the look can be regenerated exactly rather than re-designed from scratch. They were built and rasterized without any GUI design tool, entirely from this shell session, in case the same approach is needed again:
- `icon.svg` / `icon.html` and `banner.html` are plain HTML/SVG using the widget's own default brand colors (`#4F46E5`-family indigo, white). `icon.html` renders full-viewport (`100vw`/`100vh` with `preserveAspectRatio="xMidYMid slice"`) rather than a fixed-size box — a fixed-size box positioned inside the Browser pane's actual (larger, non-negotiable) viewport rendered top-left-anchored instead of centered, since the pane won't reliably shrink to arbitrary small custom sizes.
- The PNGs were produced with real headless Chrome (`"/Applications/Google Chrome.app/Contents/MacOS/Google Chrome" --headless --disable-gpu --hide-scrollbars --screenshot=out.png --window-size=W,H http://localhost:PORT/file.html`), not the Browser-pane tool's own `save_to_disk` (that option renders correctly on-screen but its saved file wasn't discoverable on disk from this shell) and not ImageMagick's own SVG rasterizer (its built-in `MSVG` delegate — used automatically when `rsvg-convert` isn't installed, which it isn't here — silently drops gradients and desaturates everything to grayscale; don't rasterize this project's SVGs with plain `magick file.svg out.png` without first confirming `rsvg-convert` is on `PATH`). A throwaway `php -S localhost:PORT -t store-assets` server (via a temporary `.claude/launch.json`, removed afterward) served the HTML files so headless Chrome had a real URL to load.
- `screenshot-1.png`/`screenshot-2.png` are genuine renders of the widget on the `tastegallery.test` install (alternating and horizontal layouts respectively) using the same headless-Chrome technique against a real (then deleted) QA page, cropped with `magick -crop` to trim surrounding whitespace — not mockups.
- Exact sizes: `icon-128x128.png`/`icon-256x256.png` (from a 800×800 headless render, downscaled with `sips -z H W`), `banner-772x250.png`/`banner-1544x500.png` (1544×500 headless render is the retina file as-is; 772×250 is `sips -z 250 772` of the same source, not a separately designed layout).

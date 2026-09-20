=== Magic Timeline ===
Contributors: rajankarmakar
Tags: elementor, timeline, changelog, history, vertical timeline
Requires at least: 5.8
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 1.1.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A customizable vertical timeline widget for Elementor — icon badges, date pills, status badges, buttons, and native Style tab controls.

== Description ==

Magic Timeline adds a single, powerful widget to Elementor's editor: a vertical timeline built for changelogs, roadmaps, company history, process steps, and "what's new" pages.

Every item in the timeline is made up of:

* A circular or square icon badge sitting on a connecting line
* A rounded date pill
* A bold title with an optional small status badge (e.g. "Latest")
* A description paragraph
* An optional button with its own icon and link

**Works with Elementor Free.** No Elementor Pro license is required — the widget is fully self-contained and registers itself in its own "Magic Timeline" category in the widget panel.

= Key Features =

* Manual repeater — add, remove and reorder as many timeline items as you like directly in the Elementor panel
* Single-column, alternating (zig-zag), or horizontal layout — horizontal automatically falls back to single-column on tablet/mobile
* Circle or square icon badges, with your own icon from the Elementor icon library
* Full native Style tab controls: typography, color, background, border, box-shadow, spacing and responsive controls (desktop / tablet / mobile) for every part of the widget
* Optional scroll-in entrance animation, with a switch to disable it on mobile
* One-click demo content: import `demo-data/sample-timeline.json` through Elementor's own "Import Template" screen to get a fully populated section instantly
* Translation-ready (text domain: `magic-timeline`), with a `.pot` file included
* Clean, prefixed, namespaced code with escaped output — safe to run alongside any other plugin or theme

= Requirements =

* WordPress 5.8+
* PHP 7.4+
* Elementor 3.5+ (Free or Pro)

== Installation ==

1. Upload the `magic-timeline` folder to `/wp-content/plugins/`, or install the zip through **Plugins → Add New → Upload Plugin**.
2. Activate the plugin through the **Plugins** menu in WordPress.
3. Make sure Elementor is installed and activated.
4. Edit any page with Elementor and find **Magic Timeline** in the widget panel, under its own "Magic Timeline" category.
5. Drag the widget onto the page and edit the Timeline Items in the Content tab.

= Importing the demo layout =

1. In WordPress, go to **Templates → Saved Templates → Import Templates** (under the Elementor menu).
2. Upload `demo-data/sample-timeline.json` from the plugin folder.
3. Insert the imported template into any page to see the reference layout with sample content already filled in.

== Frequently Asked Questions ==

= Does this require Elementor Pro? =

No. Magic Timeline works with the free version of Elementor.

= Can I use my own icons? =

Yes. Every icon field uses Elementor's native icon picker, so you can pick from the bundled icon library or any custom SVG icon sets you've registered.

= Can items alternate left and right? =

Yes. Set Layout to "Alternating" in the widget's Content → Layout section. On smaller screens it automatically collapses back to a single column so nothing overlaps.

= Will this slow down my site? =

The widget only loads its own small CSS file (and a small JS file if the entrance animation is enabled) when it's actually used on the page.

== Changelog ==

= 1.1.0 =
* New: Horizontal layout option (Content → Layout). Items lay out left-to-right along a horizontal connecting line, with a new "Item Width" style control (Style → Item Spacing). On tablet/mobile it automatically falls back to the single-column layout for readability.

= 1.0.1 =
* Fix: entrance-animation items could stay permanently invisible in some rendering contexts (e.g. Elementor's own editor preview) if the animation script didn't get a chance to run. Content is now visible by default in all cases; the animation is a pure enhancement on top of that.

= 1.0.0 =
* Initial release.

== Upgrade Notice ==

= 1.1.0 =
Adds a Horizontal layout option.

= 1.0.1 =
Fixes a bug where timeline items could appear invisible in Elementor's editor preview.

= 1.0.0 =
Initial release.

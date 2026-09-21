# Magic Timeline for Elementor — Usage Guide

Thanks for using Magic Timeline for Elementor! This guide covers installation, adding the widget, and styling it to match your site.

## 1. Requirements

- WordPress 5.8 or later
- PHP 7.4 or later
- Elementor 3.5 or later (Free or Pro — Pro is not required)

## 2. Installation

1. Go to **Plugins → Add New → Upload Plugin** in your WordPress dashboard.
2. Choose the `magic-timeline-for-elementor.zip` file and click **Install Now**.
3. Click **Activate**.
4. If Elementor isn't already installed, install and activate it from the WordPress plugin directory — Magic Timeline for Elementor will show an admin notice if it's missing.

## 3. Adding the widget

1. Edit any page with Elementor.
2. Open the widget panel and scroll to the **Magic Timeline** category (or search "timeline").
3. Drag the **Magic Timeline** widget onto your page. It arrives pre-filled with three sample items so you can see the design immediately.

## 4. Editing timeline items

Under the **Timeline Items** section in the Content tab:

- Click an item to expand it, or **+ Add Item** to add a new one.
- Drag the handle on the left of each item to reorder it.
- Each item has: Icon, Date, Title, an optional Status Badge (e.g. "Latest"), Description, and an optional Button (text, icon and link).

## 5. Layout options

Under **Layout** in the Content tab:

- **Layout**: choose Single Column, or Alternating to zig-zag items left/right on desktop (it automatically becomes single-column on mobile/tablet).
- **Icon Badge Shape**: Circle or Square.
- **Connecting Line**: toggle the vertical line on or off.
- **Entrance Animation**: fades and slides items in as they scroll into view. Can be disabled on mobile independently.

## 6. Styling

Every visual part of the widget has its own Style tab section:

| Section | What it controls |
|---|---|
| Container | Background, padding, radius, shadow, max width |
| Connecting Line | Color, width, line style, and the "gap" halo around each icon |
| Icon Badge | Size, icon size/color, background, border, radius, spacing to content |
| Date Pill | Typography, colors, border, radius, padding |
| Title | Color, typography, spacing to badge/description |
| Status Badge | Typography, colors, border, radius, padding |
| Description | Color, typography, spacing |
| Button | Typography, normal/hover colors, border, radius, padding, icon spacing |
| Item Spacing | Vertical gap between timeline entries |

All spacing, typography and sizing controls support Elementor's responsive (desktop/tablet/mobile) breakpoints — click the device icon next to a control's label to set a different value per breakpoint.

## 7. Importing the demo layout

If you'd rather start from a filled-in example than the default three items:

1. In the WordPress admin, go to **Templates → Saved Templates → Import Templates** (found under the Elementor menu).
2. Upload `demo-data/sample-timeline.json` (included in the plugin's `demo-data` folder).
3. Open **Saved Templates**, find "Magic Timeline for Elementor Demo", and insert it into any page.

## 8. Troubleshooting

- **The widget doesn't appear in the panel** — confirm Elementor is active and up to date, then reload the editor. Elementor caches its widget list; if it still doesn't show, go to **Elementor → Tools → Regenerate CSS & Data**.
- **Styles look unstyled/plain** — this usually means a caching plugin is serving an old version of a page. Clear your site cache after updating Magic Timeline for Elementor.
- **Icons aren't showing** — pick an icon explicitly in the icon control for each item; a truly empty icon field renders nothing.

## 9. Support

For support requests, please reach out through the marketplace listing you purchased this plugin from, including your WordPress, PHP, and Elementor versions.

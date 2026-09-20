# Changelog

All notable changes to Magic Timeline. Generated from readme.txt by bin/generate-changelog.sh — edit the Changelog section there, not this file directly.

## 1.1.0
- New: Horizontal layout option (Content → Layout). Items lay out left-to-right along a horizontal connecting line, with a new "Item Width" style control (Style → Item Spacing). On tablet/mobile it automatically falls back to the single-column layout for readability.

## 1.0.1
- Fix: entrance-animation items could stay permanently invisible in some rendering contexts (e.g. Elementor's own editor preview) if the animation script didn't get a chance to run. Content is now visible by default in all cases; the animation is a pure enhancement on top of that.

## 1.0.0
- Initial release.

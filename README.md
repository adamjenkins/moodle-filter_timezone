Timezone filter
===============

Converts date/time spans inserted by the companion **Timezone date/time** TinyMCE
plugin (`tiny_timezone`) from the timezone they were authored in to the timezone of
the user currently viewing the page.

This filter looks for markup like:

```html
<span class="filter_timezone" data-timestamp="1782277200" data-timezone="Asia/Tokyo">
  2026-06-24 14:00 (Asia/Tokyo)
</span>
```

and rewrites the visible text to the viewer's own timezone, e.g.
`2026-06-24 17:00 (Pacific/Auckland)` for a viewer in New Zealand. The timezone the
date/time was converted to is always shown alongside it.

If this filter is disabled, the original text (with the source timezone shown in
parentheses) is displayed unchanged — there is no broken or ambiguous fallback.

Requirements
============

- Moodle 5.0 or later (CI-tested on Moodle 5.1 and 5.2)
- PHP 8.2 or later
- The `tiny_timezone` plugin (or any other source) producing the
  `<span class="filter_timezone" data-timestamp="..." data-timezone="...">` markup

Installation
============

**From GitHub:**
Download the ZIP, extract into `filter/timezone/`, then log in as admin and go to
*Site Administration → Notifications* to run the database upgrade.

Setup
=====

1. Go to *Site Administration → Plugins → Filters → Manage filters*.
2. Set **Timezone** to *On*.
3. Use the `tiny_timezone` editor plugin to insert date/time spans, or generate the
   markup directly from another plugin.

Settings
========

*Site Administration → Plugins → Filters → Timezone* lets you choose the date/time
display format used for converted dates. Each option is one of Moodle's own language
pack format strings (e.g. `strftimedatetimeshort`), shown with a live rendered
example so you can see exactly how it will look before saving.

Privacy
=======

This plugin does not store any personal data.

Compatibility
=============

| Moodle | PHP | Status |
|---|---|---|
| 5.2 | 8.3, 8.4 | ✓ CI |
| 5.1 | 8.2, 8.3, 8.4 | ✓ CI |

CI runs on PostgreSQL and MariaDB for all combinations above.

License
=======

GNU GPL v3 or later — see http://www.gnu.org/copyleft/gpl.html

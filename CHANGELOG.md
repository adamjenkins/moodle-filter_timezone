# Changelog

All notable changes to the Timezone filter (filter_timezone) are documented here.
Entries are ordered newest-first.

---

## [2026062401] — 2026-06-24 — Don't eat extra text; security hardening

### Changed
- Default `dateformat` setting changed from `strftimedatetimeshort` to
  `strftimedatetime`.

### Fixed
- `convert()` now checks that a span's content is *exactly* the fallback text
  `tiny_timezone` would have written for its `data-timestamp`/`data-timezone`
  before rewriting it. A span containing anything else (most commonly, extra
  text that ended up inside it because of the `tiny_timezone` caret bug fixed
  the same day) is now left completely untouched instead of having that extra
  text silently discarded.
- Security hardening: `convert()` no longer copies the raw regex-captured
  attribute string into the replacement span. It now rebuilds the span from
  only the attributes it has itself validated (`data-timestamp`,
  `data-timezone`), so any attacker-controlled markup riding along in a
  span's attributes (e.g. an event handler) that slipped past upstream HTML
  cleaning is no longer faithfully reflected to every viewer of the page.

### Added
- PHPUnit test covering the "don't eat extra text" behaviour above.

### Verified
- Ran the full PHPUnit suite (14 tests) and Moodle CodeSniffer — both clean.
- Confirmed via CLI against a real Moodle 5.2 install that legitimate
  (untampered) spans still convert correctly, and that a span with extra text
  inside it is left untouched, both before and after the security fix.

## [2026062400] — 2026-06-24 — Initial release

### Added
- `text_filter` that finds `<span class="filter_timezone" data-timestamp="..."
  data-timezone="...">` markup (as produced by the `tiny_timezone` editor plugin)
  and rewrites its visible text into the current viewer's own timezone, using
  `core_date::get_user_timezone()` and `userdate()`. The conversion is idempotent,
  so re-filtering already-converted output is safe.
- Failsafe behaviour: when the filter is disabled, the original text — including
  the source timezone in parentheses — is displayed unchanged.
- Admin setting (`settings.php`) to choose the date/time display format from
  Moodle's own `langconfig` format strings, with a live rendered example shown
  next to each option.
- PHPUnit tests covering conversion, malformed-span failsafe, and plain-text
  passthrough.
- GitHub Actions CI (Moodle 5.1, 5.2 — PHP 8.2–8.4 as supported per branch;
  PostgreSQL and MariaDB).

### Verified
- Manually tested end-to-end in a browser: inserted a date/time via
  `tiny_timezone`, viewed it converted for a user in a different timezone, and
  confirmed the failsafe text when the filter is disabled.
- Ran phplint, Moodle CodeSniffer, phpmd, and `validate` — all clean (interface
  signature "unused parameter" notices accepted as expected).

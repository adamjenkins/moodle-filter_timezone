# Changes

## v1.1.0 (2026062401)

- Spans whose content is not exactly the fallback text written by
  tiny_timezone are now left untouched, so nothing an author typed can be
  silently discarded.
- Security hardening: the replacement span is rebuilt from validated
  attributes only, instead of reflecting the raw captured attribute string.
- Default date/time format changed to `strftimedatetime` (full date).
- CI now tests Moodle 5.0, 5.1 and 5.2 with compatible PHP versions
  (5.0: 8.2-8.3, 5.1: 8.2-8.4, 5.2: 8.3-8.4).

## v1.0.0 (2026062400)

- Initial release: converts date/times inserted by the tiny_timezone editor
  plugin into each viewer's own timezone, with a configurable display format
  and safe fallback text when the filter is disabled.

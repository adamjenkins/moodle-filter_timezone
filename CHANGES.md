# Changes

## v1.1.4 (2026100400)

- Installing with Composer no longer caps the Moodle version: `composer.json`
  now requires `moodle/moodle` `^5.0` (was `>=5.0 <5.4`).
- Continuous integration now tests against the released Moodle 5.3
  (`MOODLE_503_STABLE`) instead of Moodle's development branch.
- Pushing a release tag now also publishes the release to the camp registry.

# Changes

## v1.1.2 (2026080500)

- The `pluginname` language string is now defined ("Timezone filter"). Filter
  plugins take their displayed name from `filtername`, which was already
  present, but `pluginname` is required of every plugin and is what Moodle
  falls back to on the plugin uninstall page. Flagged by an external review.

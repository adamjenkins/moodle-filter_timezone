<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

namespace filter_timezone;

/**
 * Converts <span class="filter_timezone"> markup (as inserted by the tiny_timezone editor
 * plugin) from the timezone it was authored in to the timezone of the user currently viewing
 * the page.
 *
 * The span's data-timestamp/data-timezone attributes are left untouched, so the conversion is
 * idempotent and safe to re-run on already-converted output (e.g. if a page is filtered more
 * than once for different users).
 *
 * @package    filter_timezone
 * @copyright  2026 PluginDev
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class text_filter extends \core_filters\text_filter {
    /** @var string regex matching a tiny_timezone span and capturing its attributes + content. */
    const SPAN_PATTERN = '/<span\b([^>]*\bclass="[^"]*\bfilter_timezone\b[^"]*"[^>]*)>(.*?)<\/span>/is';

    /** @var string[] langconfig string identifiers usable as the display date/time format. */
    const DATETIME_FORMAT_STRINGS = [
        'strftimedatetime',
        'strftimedatetimeaccurate',
        'strftimedatetimeshort',
        'strftimedatetimeshortaccurate',
        'strftimedaydatetime',
        'strftimedatemonthtimeshort',
        'strftimerecentfull',
    ];

    #[\Override]
    public function filter($text, array $options = []) {
        if (stripos($text, 'filter_timezone') === false) {
            return $text;
        }

        return preg_replace_callback(self::SPAN_PATTERN, [$this, 'convert'], $text);
    }

    /**
     * Build the replacement span for a single regex match, with its text converted to the
     * current user's timezone.
     *
     * @param array $matches [0] => full match, [1] => span attributes, [2] => original content.
     * @return string
     */
    protected function convert(array $matches): string {
        $attributes = $matches[1];

        if (!preg_match('/\bdata-timestamp="(\d+)"/i', $attributes, $timestampmatch)) {
            return $matches[0];
        }
        if (!preg_match('/\bdata-timezone="[^"]+"/i', $attributes)) {
            return $matches[0];
        }

        $timestamp = (int) $timestampmatch[1];
        $usertimezone = \core_date::get_user_timezone();
        $formatstring = get_config('filter_timezone', 'dateformat') ?: 'strftimedatetimeshort';
        if (!in_array($formatstring, self::DATETIME_FORMAT_STRINGS, true)) {
            $formatstring = 'strftimedatetimeshort';
        }
        $formatted = userdate($timestamp, get_string($formatstring, 'langconfig'), $usertimezone);
        $displaytext = get_string('converted', 'filter_timezone', (object) [
            'datetime' => $formatted,
            'timezone' => $usertimezone,
        ]);

        return '<span' . $attributes . '>' . s($displaytext) . '</span>';
    }
}

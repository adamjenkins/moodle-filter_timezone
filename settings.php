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

/**
 * Admin settings for filter_timezone.
 *
 * @package    filter_timezone
 * @copyright  2026 PluginDev
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($ADMIN->fulltree) {
    // Every langconfig string that combines a date and a time, so the admin can pick a
    // display format using the current language pack's own conventions rather than a
    // hardcoded one.
    $dateformatoptions = [];
    foreach (\filter_timezone\text_filter::DATETIME_FORMAT_STRINGS as $formatstring) {
        $example = userdate(time(), get_string($formatstring, 'langconfig'));
        $dateformatoptions[$formatstring] = "{$formatstring} ({$example})";
    }

    $settings->add(new admin_setting_configselect(
        'filter_timezone/dateformat',
        get_string('dateformat', 'filter_timezone'),
        get_string('dateformat_desc', 'filter_timezone'),
        'strftimedatetimeshort',
        $dateformatoptions
    ));
}

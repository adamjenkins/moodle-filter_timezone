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
 * Tests for the filter_timezone text filter.
 *
 * @package    filter_timezone
 * @category   test
 * @copyright  2026 Adam Jenkins <adam@wisecat.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @covers \filter_timezone\text_filter
 */
final class text_filter_test extends \advanced_testcase {
    /**
     * A span with no recognisable data attributes should be left untouched.
     */
    public function test_filter_ignores_text_without_span(): void {
        $this->resetAfterTest();
        $filter = new text_filter(\core\context\system::instance(), []);

        $text = 'Hello, world!';
        $this->assertEquals($text, $filter->filter($text));
    }

    /**
     * A well-formed span is rewritten into the viewing user's timezone.
     */
    public function test_filter_converts_timestamp_to_user_timezone(): void {
        $this->resetAfterTest();

        $user = $this->getDataGenerator()->create_user(['timezone' => 'Pacific/Auckland']);
        $this->setUser($user);
        set_config('dateformat', 'strftimedatetimeshort', 'filter_timezone');

        // 2026-06-24 14:00 Asia/Tokyo (UTC+9) == 2026-06-24 05:00 UTC == 2026-06-24 17:00 Pacific/Auckland (UTC+12).
        $timestamp = 1782277200;
        $text = '<p>Meeting at <span class="filter_timezone" data-timestamp="' . $timestamp . '" ' .
            'data-timezone="Asia/Tokyo">2026-06-24 14:00 (Asia/Tokyo)</span></p>';

        $filter = new text_filter(\core\context\system::instance(), []);
        $result = $filter->filter($text);

        $this->assertStringContainsString('17:00', $result);
        $this->assertStringContainsString('Pacific/Auckland', $result);
        $this->assertStringNotContainsString('14:00', $result);
        $this->assertStringContainsString('data-timestamp="' . $timestamp . '"', $result);
        $this->assertStringContainsString('data-timezone="Asia/Tokyo"', $result);
    }

    /**
     * Spans missing the expected data attributes are left untouched as a failsafe.
     */
    public function test_filter_ignores_malformed_span(): void {
        $this->resetAfterTest();
        $filter = new text_filter(\core\context\system::instance(), []);

        $text = '<span class="filter_timezone">2026-06-24 14:00 (Asia/Tokyo)</span>';
        $this->assertEquals($text, $filter->filter($text));
    }

    /**
     * A span whose content is more than exactly the fallback text the editor wrote (e.g.
     * because a caret left inside the span let extra text get typed into it) is left
     * completely untouched, rather than having that extra text discarded.
     */
    public function test_filter_does_not_eat_extra_text_inside_span(): void {
        $this->resetAfterTest();

        $user = $this->getDataGenerator()->create_user(['timezone' => 'Pacific/Auckland']);
        $this->setUser($user);

        $timestamp = 1782277200;
        $text = '<p>Meeting at <span class="filter_timezone" data-timestamp="' . $timestamp . '" ' .
            'data-timezone="Asia/Tokyo">2026-06-24 14:00 (Asia/Tokyo) and some extra notes</span></p>';

        $filter = new text_filter(\core\context\system::instance(), []);
        $result = $filter->filter($text);

        $this->assertEquals($text, $result);
        $this->assertStringContainsString('and some extra notes', $result);
    }
}

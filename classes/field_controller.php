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
 * @package customfield_number
 * @author Mikko Haiku <mikko.haiku@mediamaisteri.com>
 * @copyright Mediamaisteri Oy 2024
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace customfield_number;

defined('MOODLE_INTERNAL') || die;

class field_controller extends \core_customfield\field_controller {
    /**
     * Type of plugin data
     */
    const TYPE = 'number';

    /**
     * Add fields for editing a date field.
     *
     * @param \MoodleQuickForm $mform
     */
    public function config_form_definition(\MoodleQuickForm $mform) {
        global $CFG;
    }

    /**
     * Does this custom field type support being used as part of the block_myoverview
     * custom field grouping?
     * @return bool
     */
    public function supports_course_grouping(): bool {
        return true;
    }

    /**
     * If this field supports course grouping, then this function needs overriding to
     * return the formatted values for this.
     * @param array $values the used values that need formatting
     * @return array
     */
    public function course_grouping_format_values($values): array {
        $ret = [];
        foreach ($values as $value) {
            if ($value) {
                $ret[$value] = format_time($value);
            }
        }
        if (!$ret) {
            return []; // If the only dates found are 0, then do not show any options.
        }
        $ret[BLOCK_MYOVERVIEW_CUSTOMFIELD_EMPTY] = get_string('nocustomvalue', 'block_myoverview',
            $this->get_formatted_name());
        return $ret;
    }

    /**
     * Convert given value into appropriate timestamp
     *
     * @param string $value
     * @return int
     */
    public function parse_value(string $value) {
        $now = time();
        $timestamp = strtotime($value, $now) - $now;

        // If we have a valid, positive timestamp then return it.
        return $timestamp > 0 ? $timestamp : 0;
    }
}

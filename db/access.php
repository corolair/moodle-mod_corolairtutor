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
 * Capability definitions for the Corolair Tutor plugin.
 *
 * This file contains the capability definitions for the Corolair Tutor plugin.
 * Capabilities are used to control access to various features within the plugin.
 *
 * @package    mod_corolairtutor
 * @copyright  2024 Corolair
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$capabilities = [
    // Capability to view and access tutors within the Corolair Tutor plugin.
    // This capability allows users to create and manage tutors within the Corolair Tutor plugin.
    // @captype      read
    // @contextlevel CONTEXT_MODULE
    // @description  Allows users to create and manage tutors within the Corolair Tutor plugin.
    'mod/corolairtutor:view' => [
        'captype' => 'read',
        'contextlevel' => CONTEXT_MODULE,
        'archetypes' => [
            'teacher' => CAP_ALLOW,
            'editingteacher' => CAP_ALLOW,
            'student' => CAP_PREVENT,
        ],
        'description' => get_string('corolairtutor:view', 'mod_corolairtutor'),
    ],
    // Capability to add Corolair Tutor plugin as an activity.
    // This capability allows users to create an activity using Corolair Tutor plugin.
    // @captype      write
    // @contextlevel CONTEXT_COURSE
    // @description  Allows users to create an activity using Corolair Tutor plugin.
    'mod/corolairtutor:addinstance' => [
        'riskbitmask' => RISK_XSS,
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
        'archetypes' => [
            'editingteacher' => CAP_ALLOW,
            'manager' => CAP_ALLOW,
        ],
        'clonepermissionsfrom' => 'moodle/course:manageactivities',
        'description' => get_string('corolairtutor:addinstance', 'mod_corolairtutor'),
    ],
];

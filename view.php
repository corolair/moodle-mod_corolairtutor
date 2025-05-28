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
 * Activity view page for the mod_corolairtutor plugin.
 *
 * @package    mod_corolairtutor
 * @copyright  2024 Corolair
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require(__DIR__ . '/../../config.php');
$id = required_param('id', PARAM_INT);
$cm = get_coursemodule_from_id('corolairtutor', $id, 0, false, MUST_EXIST);
$course = get_course($cm->course);
$context = context_module::instance($cm->id);
require_login($course, true, $cm);
echo $OUTPUT->header();
if (!is_dir($CFG->dirroot . '/local/corolair')) {
    $output = $PAGE->get_renderer('mod_corolairtutor');
    echo $output->render_local_plugin_not_installed();
    echo $OUTPUT->footer();
    return;
}
require_capability('mod/corolairtutor:view', $context);
require_capability('local/corolair:createtutor', $context);
$courseid = $course->id;
if (empty($courseid) || $courseid === '1') {
        redirect(
        new moodle_url("/local/corolair/trainer.php"),
        '',
        0
    );
} else {
    redirect(
        new moodle_url("/local/corolair/trainer.php", [
            'corolairsourcecourse' => $courseid,
            'corolairplugin' => 'tutorActivity',
        ]),
        '',
        0
    );
}

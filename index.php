<?php
// This file is part of Moodle - http://moodle.org/
// License: GNU GPL v3 or later

/**
 * Activity index for the mod_corolairquiz plugin.
 *
 * @package   mod_corolairquiz
 */

require_once('../../config.php');

$id = required_param('id', PARAM_INT); // Course ID

$course = $DB->get_record('course', ['id' => $id], '*', MUST_EXIST);
require_course_login($course);

$PAGE->set_url('/mod/corolairquiz/index.php', ['id' => $id]);
$PAGE->set_pagelayout('incourse');
$PAGE->set_title(get_string('modulenameplural', 'mod_corolairquiz'));
$PAGE->set_heading($course->fullname);

echo $OUTPUT->header();

echo $OUTPUT->heading(get_string('modulenameplural', 'mod_corolairquiz'));

$modinfo = get_fast_modinfo($course);
$instances = $modinfo->get_instances_of('corolairquiz');

if (empty($instances)) {
    echo get_string('noinstances', 'mod_corolairquiz');
} else {
    echo html_writer::start_tag('ul');
    foreach ($instances as $cm) {
        $url = new moodle_url('/mod/corolairquiz/view.php', ['id' => $cm->id]);
        echo html_writer::tag('li', html_writer::link($url, $cm->name));
    }
    echo html_writer::end_tag('ul');
}

echo $OUTPUT->footer();

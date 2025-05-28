<?php
defined('MOODLE_INTERNAL') || die();

function corolairquiz_supports($feature) {
    switch ($feature) {
        case FEATURE_MOD_ARCHETYPE: return MOD_ARCHETYPE_RESOURCE;
        case FEATURE_COMPLETION_TRACKS_VIEWS: return true;
        default: return null;
    }
}

/**
 * Saves a new instance of the mod_corolair_quiz into the database.
 *
 * @param object $moduleinstance An object from the form (has course, name, intro, etc.)
 * @param moodleform|null $mform The form instance (not used here).
 * @return int The ID of the newly inserted record.
 */
function corolairquiz_add_instance($moduleinstance, $mform = null): int {
    global $DB;

    $moduleinstance->timecreated = time();
    $moduleinstance->timemodified = $moduleinstance->timecreated;

    $id = $DB->insert_record('corolairquiz', $moduleinstance);
    return $id;
}


/**
 * Updates an existing instance of the corolairquiz module.
 *
 * @param object $instancedata Data from the form.
 * @param moodleform $mform The form.
 * @return bool True on success.
 */
function corolairquiz_update_instance($instancedata, $mform): bool {
    global $DB;

    $record = new stdClass();
    $record->id = $instancedata->instance;
    $record->name = $instancedata->name;
    $record->timemodified = time();

    return $DB->update_record('corolairquiz', $record);
}


/**
 * Deletes an instance of the corolairquiz module.
 *
 * @param int $id The ID of the instance to delete.
 * @return bool True on success.
 */
function corolairquiz_delete_instance($id): bool {
    global $DB;

    if (!$instance = $DB->get_record('corolairquiz', ['id' => $id])) {
        return false;
    }

    // Delete the instance record.
    $DB->delete_records('corolairquiz', ['id' => $id]);

    // Optionally: delete related records if your module uses other tables.

    return true;
}



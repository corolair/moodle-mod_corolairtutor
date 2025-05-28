<?php
require_once($CFG->dirroot.'/course/moodleform_mod.php');

class mod_corolairquiz_mod_form extends moodleform_mod {
    public function definition() {
        $mform = $this->_form;

        // Visible name field with default value from get_string.
        $mform->addElement('text', 'name', get_string('name'), array('size' => '64'));
        $mform->setType('name', PARAM_TEXT);
        $mform->setDefault('name', get_string('defaultactivityname', 'mod_corolairquiz'));
        $this->standard_coursemodule_elements();
        $this->add_action_buttons();
    }

    public function data_preprocessing(&$defaultvalues) {
    parent::data_preprocessing($defaultvalues);

    if (empty($this->current->instance)) {
        // Only for new instances — do not override during edit.
        $defaultvalues['visible'] = 0;
    }
}

}


<?php
require(__DIR__ . '/../../config.php');
$id = required_param('id', PARAM_INT); // Course module ID

$cm = get_coursemodule_from_id('corolairquiz', $id, 0, false, MUST_EXIST);
$course = get_course($cm->course);
$context = context_module::instance($cm->id);

require_login($course, true, $cm);
require_capability('mod/corolairquiz:view', $context);

echo $OUTPUT->header();
if (is_dir($CFG->dirroot . '/local/corolair')) {
    // echo "Local Corolair plugin is installed.";
    // You can include or call local_corolair functions here.
} else {
    echo "Local Corolair plugin is NOT installed.";
    $output = $PAGE->get_renderer('mod_corolairquiz');
        echo $output->render_local_plugin_not_installed();
    echo $OUTPUT->footer();
    return;
}





$sitename = $SITE->fullname;
$moodlerooturl = $CFG->wwwroot;
$useremail = $USER->email;
$userfirstname = $USER->firstname;
$userlastname = $USER->lastname;
$enablewebserviceconfigrecord = $DB->get_record('config', ['name' => 'enablewebservices']);
$iswebserviceenabled = false;
if ($enablewebserviceconfigrecord && $enablewebserviceconfigrecord->value == 1) {
    $iswebserviceenabled = true;
}
$webserviceprotocols = $DB->get_record('config', ['name' => 'webserviceprotocols']);
$isrestprotocolenabled = false;
if ($webserviceprotocols && strpos($webserviceprotocols->value, 'rest') !== false) {
    $isrestprotocolenabled = true;
}
$existingservice = $DB->get_record('external_services', ['shortname' => 'corolair_rest']);
$iscorolairserviceexist = false;
$istokenexist = false;
$tokenvalue = '';
if ($existingservice) {
    $iscorolairserviceexist = true;
    $token = $DB->get_record('external_tokens', ['externalserviceid' => $existingservice->id]);
    if ($token) {
        $istokenexist = true;
        $tokenvalue = $token->token;
    }
}

$apikey = get_config('local_corolair', 'apikey');
if (empty($apikey) ||
    strpos($apikey, 'No Corolair Api Key') === 0 ||
    strpos($apikey, 'Aucune Clé API Corolair') === 0 ||
    strpos($apikey, 'No hay clave API de Corolair') === 0
    ) 
    {   
        $output = $PAGE->get_renderer('local_corolair');
        echo $output->render_installation_troubleshoot(
        $moodlerooturl,
        $sitename,
        $iswebserviceenabled,
        $isrestprotocolenabled,
        $iscorolairserviceexist,
        $istokenexist,
        $useremail,
        $userfirstname,
        $userlastname,
        $tokenvalue
    );
    echo $OUTPUT->footer();
    return;
    }
$createtutorwithcapability = get_config('local_corolair', 'createtutorwithcapability') === 'true';
$postdata = json_encode([
    'email' => $USER->email,
    'apiKey' => $apikey,
    'firstname' => $USER->firstname,
    'lastname' => $USER->lastname,
    'moodleUserId' => $USER->id,
    'createTutorWithCapability' => $createtutorwithcapability,
]);
$curl = new curl();
$options = [
    "CURLOPT_RETURNTRANSFER" => true,
    'CURLOPT_HTTPHEADER' => [
        'Content-Type: application/json',
        'Content-Length: ' . strlen($postdata),
    ],
];
$authurl = "https://services.corolair.dev/moodle-integration/auth";
$response = $curl->post($authurl, $postdata , $options);
$errno = $curl->get_errno();
// Handle the response.
if ($response === false || $errno !== 0) {
    $output = $PAGE->get_renderer('local_corolair');
    echo $output->render_installation_troubleshoot(
        $moodlerooturl,
        $sitename,
        $iswebserviceenabled,
        $isrestprotocolenabled,
        $iscorolairserviceexist,
        $istokenexist,
        $useremail,
        $userfirstname,
        $userlastname,
        $tokenvalue
    );
    echo $OUTPUT->footer();
    return;
}
$jsonresponse = json_decode($response, true);
// Validate the response.
if (!isset($jsonresponse['userId'])) {
    throw new moodle_exception('errortoken', 'local_corolair');
}
$userid = $jsonresponse['userId'];

// Use the mod_corolairquiz renderer
$output = $PAGE->get_renderer('mod_corolairquiz');

$courseid = $course->id;

if(empty($courseid) || $courseid === '1') {
        echo $output->render_dashboard($userid);

}else {
    $provider = 'moodle'; 
    echo $output->render_trainer($userid, $provider, $courseid);

}
echo $OUTPUT->footer();


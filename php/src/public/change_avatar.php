<?php

ini_set('display_errors', 'Off');
ini_set('display_startup_errors', 'Off');

require_once('../session.php');
require_once('../utils.php');
if (!is_logged_in()) {
    redirect_to_login_page(true);
    exit();
}

const filename = 'avatar';
if (!$_SERVER['REQUEST_METHOD'] === 'POST') {
    exit();
}
if (!$_FILES) {
    echo json_encode(['success' => false, 'message' => 'No file uploaded']);
    exit();
}
if (!$_FILES[filename]["error"] == UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'message' => 'File upload error: '.$_FILES[filename]["error"]]);
    exit();
}

const avatars_folder = "avatars/";
try {
    $name = avatars_folder . get_user_id() . '.png';
    if (!file_exists(avatars_folder)) {
        mkdir(avatars_folder);
    }
    move_uploaded_file($_FILES[filename]["tmp_name"], $name);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'File upload error: ' . $e->getMessage()]);
    exit();
}

echo json_encode(['success' => true, 'avatar_url' => '/' . $name]);

?>

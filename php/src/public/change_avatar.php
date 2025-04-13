<?php

require_once('../config.php');

ini_set('display_errors', 'Off');
ini_set('display_startup_errors', 'Off');

require_once('../session.php');
require_once('../utils.php');
if (!is_logged_in()) {
    redirect_to_login_page(true);
    exit();
}

const parameter_name = 'image';

try {

    if (!$_SERVER['REQUEST_METHOD'] === 'POST') {
        exit();
    }
    if (!$_FILES) {
        echo json_encode(['success' => false, 'message' => 'No file uploaded']);
        exit();
    }
    if (!$_FILES[parameter_name]["error"] == UPLOAD_ERR_OK) {
        echo json_encode(['success' => false, 'message' => 'File upload error: '.$_FILES[parameter_name]["error"]]);
        exit();
    }

    $name = \Config\avatars_dir . get_user_id() . '.png';
    if (!file_exists(\Config\avatars_dir)) {
        mkdir(\Config\avatars_dir);
    }
    move_uploaded_file($_FILES[parameter_name]["tmp_name"], $name);

} catch (Exception $e) {

    echo json_encode(['success' => false, 'message' => 'File upload error: ' . $e->getMessage()]);
    exit();

}

echo json_encode(['success' => true, 'image_url' => '/' . $name]);

?>

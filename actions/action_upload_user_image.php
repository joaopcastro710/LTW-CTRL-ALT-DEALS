<?php

declare(strict_types=1);

require_once (__DIR__ . '/../database/connection_db.php');
require_once (__DIR__ . '/../database/user_db.php');
require_once (__DIR__ . '/../utils/forms_validator.php');
require_once ("../utils/session.php");
require_once ("../utils/forms_validator.php");

$session = new Session();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SESSION['priv_csrf'] === $_POST['token']) {
    $userId = $_POST['userId'];

    $db = getDatabaseConnection();
    try {
        $db->beginTransaction();
        if ($userId) {

            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $tempFileName = $_FILES['image']['tmp_name'];

                if (!empty($tempFileName)) {
                    $original = @imagecreatefromjpeg($tempFileName);
                    if (!$original) $original = @imagecreatefrompng($tempFileName);
                    if (!$original) $original = @imagecreatefromgif($tempFileName);

                    if (!$original) {
                        throw new Exception('Unknown image format.');
                    }

                    replaceUserImage($db, $userId);
                    $id = $db->lastInsertId();
                    $photoFileName = "../assets/users/$id.jpg";
                    if (!imagejpeg($original, $photoFileName)) {
                        throw new Exception('Failed to save uploaded image.');
                    }
                }
            } else {
                throw new Exception('Invalid image. Please try again.');
            }
            $db->commit();
            $response = array('success' => true);
            echo json_encode($response);
            exit;
        } else {
            throw new Exception('An error occurred while creating the item.');
        }
    } catch (Exception $e) {
        $db->rollBack();

        if (!empty($photoFileName) && file_exists($photoFileName)) {
            unlink($photoFileName);
        }

        $response = array('error' => $e->getMessage());
        echo json_encode($response);
        exit;
    }
} else {
    $response = array('error' => "Error registering. Try again");
    echo json_encode($response);
    exit;
}

?>

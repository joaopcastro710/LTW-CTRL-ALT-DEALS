<?php

declare(strict_types=1);

require_once (__DIR__ . '/../database/connection_db.php');
require_once (__DIR__ . '/../database/item_db.php');
require_once (__DIR__ . '/../utils/forms_validator.php');
require_once ("../utils/session.php");

$session = new Session();

if (!$session->isLoggedIn()) {
    header('Location: /pages/login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SESSION['priv_csrf'] === $_POST['token']) {
    $itemId = $_POST['itemId'];
    $creatingItem = $_POST["creatingItem"];

    $db = getDatabaseConnection();
    $uploadedFiles = []; // Array to store paths of successfully uploaded files
    try {
        $db->beginTransaction();


        if ($itemId) {
            if (isset($_FILES['image'])) {
                $upload_directory = '../assets/items/originals/';
                $thumb_directory = '../assets/items/thumbs_small/';

                // Iterate over each uploaded file
                foreach ($_FILES['image']['tmp_name'] as $key => $tempFileName) {
                    // Check if file was uploaded successfully
                    if ($_FILES['image']['error'][$key] === UPLOAD_ERR_OK) {
                        // Create an image representation of the original image
                        $original = @imagecreatefromjpeg($tempFileName);
                        if (!$original) $original = @imagecreatefrompng($tempFileName);
                        if (!$original) $original = @imagecreatefromgif($tempFileName);

                        if (!$original) {
                            throw new Exception('Invalid image. Please try again.');
                        }
                        addItemImage($db, $itemId);

                        $id = $db->lastInsertId();
                        $originalFileName = $upload_directory . $id . '.jpg';
                        $smallFileName = $thumb_directory . $id . '.jpg';

                        // Save original file as jpeg
                        imagejpeg($original, $originalFileName);

                        // Get dimensions of the original image
                        $width = imagesx($original);
                        $height = imagesy($original);
                        $aspectRatio = $width / $height;

                        // Set the desired width and height for the small image
                        $smallWidth = 400;
                        $smallHeight = 200;

                        // Calculate the new dimensions while maintaining aspect ratio
                        if ($aspectRatio > 1) {
                            $newWidth = $smallWidth;
                            $newHeight = $smallWidth / $aspectRatio;
                        } else {
                            $newWidth = $smallHeight * $aspectRatio;
                            $newHeight = $smallHeight;
                        }

                        // Create a new image with the calculated dimensions
                        $small = imagecreatetruecolor($smallWidth, $smallHeight);

                        // Resize and crop the original image to fit the new dimensions
                        imagecopyresampled($small, $original, 0, 0, 0, 0, $smallWidth, $smallHeight, $width, $height);

                        // Save the small image
                        imagejpeg($small, $smallFileName);
                        // Store the paths of successfully uploaded files
                        $uploadedFiles[] = $originalFileName;
                        $uploadedFiles[] = $smallFileName;
                    } else {
                        throw new Exception('Invalid image. Please try again.');
                    }
                }
                $response = array('success' => true);
                echo json_encode($response);
            } else {
                throw new Exception('Invalid image. Please try again.');
            }
        } else {
            throw new Exception('An error occurred while creating the item.');
        }
        $db->commit();
    } catch (Exception $e) {
        $db->rollBack();

        if ($itemId && $creatingItem) {
            deleteItem($db, $itemId);
        }

        foreach ($uploadedFiles as $file) {
            if (file_exists($file)) {
                unlink($file); // Delete the file
            }
        }
        $response = array('item-image-error' => $e->getMessage());
        echo json_encode($response);
    }
} else {
    $response = array('item-image-error' => 'An error occurred while creating the item.');
    echo json_encode($response);
}
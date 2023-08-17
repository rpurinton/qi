<?php

namespace Rpurinton\qi;

require_once(__DIR__ . '/../../src/Session.php');
$session = new Session(false);

$file_name = explode('/', $_SERVER['REQUEST_URI'])[2];

// Retrieve the file content from the request body
$fileContent = file_get_contents('php://input');

// Specify the directory where the file will be stored
$targetDir = __DIR__ . "/uploads/";

// Create the full path to the destination file
$targetFile = $targetDir . $filename;

// Save the file content to the destination file
if (file_put_contents($targetFile, $fileContent) !== false) {
    // File successfully saved
    $response = [
        'message' => 'File saved successfully',
        'filename' => $filename,
        'filepath' => $targetFile
    ];
    echo json_encode($response);
} else {
    // Error saving the file
    $response = [
        'message' => 'Failed to save file'
    ];
    echo json_encode($response);
}

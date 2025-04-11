<?php

/**
 * This takes input_name, and subdirectory, it assumes you have a directory of 
 * uploads, and the subdirectory is inside uploads,
 * the input_name is the key name of the image to be uploaded e.g 
 * array(1) {
 * array(1) {
 * ["image"]=>
 * array(6) {
 *  ["name"]=>
 * string(11) "Docker.jpeg"
 *["full_path"]=>
 *    string(11) "Docker.jpeg"
 *   ["type"]=>
 *  string(10) "image/jpeg", so the image becomes the input_name

 */

function uploadFile($input_name, $subdirectory = 'users')
{

    if (!isset($_FILES[$input_name])) {
        echo json_encode(['status' => 'error', 'message' => 'No file uploaded']);
        return null;
    }


    // Define upload directory paths
    $upload_dir = 'uploads' . DIRECTORY_SEPARATOR . $subdirectory . DIRECTORY_SEPARATOR;
    $server_upload_dir = __DIR__ . DIRECTORY_SEPARATOR . $upload_dir;


    // Create directory if it doesn't exist
    if (!file_exists($server_upload_dir) && !mkdir($server_upload_dir, 0777, true) && !is_dir($server_upload_dir)) {
        echo json_encode(['status' => 'error', 'message' => 'Failed to create upload directory']);
        return null;
    }


    // Generate a unique file name
    $file_name = basename($_FILES[$input_name]['name']);
    $unique_filename = uniqid() . '_' . $file_name;
    $server_target_file = $server_upload_dir . $unique_filename;
    $db_target_file = $upload_dir . $unique_filename;

    // Move uploaded file to the target directory
    if (move_uploaded_file($_FILES[$input_name]['tmp_name'], $server_target_file)) {
        // Return the file path for database storage (using forward slashes)

        return str_replace(DIRECTORY_SEPARATOR, '/', $db_target_file);
    } else {
        $error = error_get_last();
        echo json_encode([
            'status' => 'error',
            'message' => 'File upload failed',
            'details' => $error ? $error['message'] : 'Unknown error'
        ]);
        return null;
    }
}

function uploadMultipleFiles($files, $type = 'general') {
    // Define allowed file types and their directories
    $allowedTypes = [
        'audio' => ['audio/mpeg', 'audio/mp3', 'audio/wav', 'audio/x-m4a'],
        'image' => ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/jpg'],
        'video' => ['video/mp4', 'video/mpeg', 'video/quicktime']
    ];

    // Initialize results arrays
    $uploadedImages = [];
    $uploadedAudios = [];

    // Check if files are uploaded
    if (!isset($files['name']) || empty($files['name'][0])) {
        return [
            'images' => [],
            'audios' => []
        ];
    }

    // Check if single or multiple files
    if (!is_array($files['name'])) {
        // Convert single file to an array
        $filesArray = [
            'name' => [$files['name']],
            'type' => [$files['type']],
            'tmp_name' => [$files['tmp_name']],
            'error' => [$files['error']],
            'size' => [$files['size']]
        ];
    } else {
        // Use provided multiple file structure
        $filesArray = $files;
    }

    // Process each file
    for ($i = 0; $i < count($filesArray['name']); $i++) {
        $fileType = $filesArray['type'][$i];
        $fileName = $filesArray['name'][$i];
        $fileTmpName = $filesArray['tmp_name'][$i];
        $fileError = $filesArray['error'][$i];

        // Skip if file has an error
        if ($fileError !== 0) {
            continue;
        }

        // Determine the appropriate subdirectory based on file type
        $subdirectory = 'general';
        foreach ($allowedTypes as $category => $mimeTypes) {
            if (in_array($fileType, $mimeTypes)) {
                $subdirectory = $category;
                break;
            }
        }

        // Generate unique filename
        $uniqueFilename = uniqid() . '_' . basename($fileName);

        // Define upload directory paths
        $uploadDir = 'uploads' . DIRECTORY_SEPARATOR . $subdirectory . DIRECTORY_SEPARATOR;
        $serverUploadDir = __DIR__ . DIRECTORY_SEPARATOR . $uploadDir;

        // Create directory if it doesn't exist
        if (!file_exists($serverUploadDir) && !mkdir($serverUploadDir, 0777, true) && !is_dir($serverUploadDir)) {
            continue;
        }

        $serverTargetFile = $serverUploadDir . $uniqueFilename;
        $dbTargetFile = $uploadDir . $uniqueFilename;

        // Move uploaded file
        if (move_uploaded_file($fileTmpName, $serverTargetFile)) {
            $filePath = str_replace(DIRECTORY_SEPARATOR, '/', $dbTargetFile);
            if ($subdirectory === 'image') {
                $uploadedImages[] = $filePath;
            } elseif ($subdirectory === 'audio') {
                $uploadedAudios[] = $filePath;
            }
        }
    }

    return [
        'images' => $uploadedImages,
        'audios' => $uploadedAudios
    ];
}
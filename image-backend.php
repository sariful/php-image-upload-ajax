<?php
header('Content-type: application/json'); // to show all text as json format
$json = [];
if ($_FILES['image']['size'] > 1) { // check if there is any file uploaded
    
    $fileName = $_FILES['image']['name']; // get uploaded file name
    $fileSize = $_FILES['image']['size']; // get uploaded file size
    $fileTmpName = $_FILES['image']['tmp_name']; // get uploaded file temp location
    $fileType = $_FILES['image']['type']; // get uploaded file type
    
    $isImageUploadable = true; // used this variable for validation
    
    
    $uploadDirectory = "./"; // setting the desired upload directory
    $image_store_path = $uploadDirectory . basename($fileName); // full path of the image with directory
    
    $tmp = explode('.', $fileName); // get uploaded file extension text
    $fileExtension = end($tmp); // checking the file extesion

    $fileExtensions = ['jpeg', 'jpg', 'png', 'gif', 'BMP', 'ico']; // uploadable file extension
    if (!in_array($fileExtension, $fileExtensions)) { // check if uploaded file matches with uploadable file extension
        $isImageUploadable = false; // is yes set the validation variable to false for stopping further execution
        $json['status'] = "error"; // store status in an array
        $json['message'] = "Unsupported File. Please upload a JPEG, PNG, GIF or BMP file"; // store message in an array
    }

    if ($fileSize > 15366299) { // check file size more than 12 MB
        $isImageUploadable = false;
        $json['status'] = "error";
        $json['message'] = "This file is more than 12MB. Sorry, it has to be less than or equal to 12MB";
    }

    if ($isImageUploadable) { // checking if the file is valid for uploading
        if ($json['move_image_status'] = move_uploaded_file($fileTmpName, $image_store_path)) { // move the temp file to desired location
            $json['image_path'] = $image_store_path; // store the file path in an array
            $json['message'] = "Successfully Uploaded Image";
            $json['status'] = "success";
        } else {
            $json['status'] = "error";
            $json['message'] = "Error moving file";
            $json['error'] = error_get_last(); // get the last error occurred for debugging
        }
    }
} else {
    $json['status'] = "error";
    $json['message'] = "No image provided";
    $json['image_path'] = '';
}

echo json_encode($json, JSON_PRETTY_PRINT); // lastly status array prints out convering to json
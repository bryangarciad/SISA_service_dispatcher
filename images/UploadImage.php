<?php 
// Header
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers: Content-Type');
/* 
 * Custom function to compress image size and 
 * upload to the server using PHP 
 */ 
function compressImage($source, $destination, $quality) { 
    // Get image info 
    $imgInfo = getimagesize($source);
    $mime = $imgInfo['mime']; 
     
    // Create a new image from file 
    switch($mime){ 
        case 'image/jpeg': 
            $image = imagecreatefromjpeg($source); 
            break; 
        case 'image/png': 
            $image = imagecreatefrompng($source); 
            break; 
        case 'image/gif': 
            $image = imagecreatefromgif($source); 
            break; 
        default: 
            $image = imagecreatefromjpeg($source); 
    } 
     
    // Save image 
    imagejpeg($image, $destination, $quality); 
     
    // Return compressed image 
    return $destination; 
} 
 
 
// File upload path 
$uploadPath = "uploads/"; 
$statusCode = 500;
 
// If file upload form is submitted 
$status = $statusMsg = 'No Files in POST data'; 
if( count($_FILES) > 0 ) { 
    $status = 'error'; 
    if(!empty($_FILES["image"]["name"])) { 
        
        // File info
        $fileType = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION); 
        // $fileName = $_FILES["image"]["name"];
        $fileName = time();
        $imageUploadPath = $uploadPath . $fileName . '.' .$fileType; 
        
         
        // Allow certain file formats 
        $allowTypes = array('jpg','png','jpeg','gif'); 
        if(in_array($fileType, $allowTypes)){ 
            // Image temp source 
            $imageTemp = $_FILES["image"]["tmp_name"]; 
             
            // Compress size and upload image 
            $compressedImage = compressImage($imageTemp, $imageUploadPath, 75); 
             
            if($compressedImage){ 
                $status = 'success'; 
                $statusMsg = "Image compressed and uploaded successfully."; 
                $statusCode = 200;
            }else{ 
                $statusMsg = "Image compress failed!"; 
            } 
        }else{ 
            $statusMsg = 'Sorry, only JPG, JPEG, PNG, & GIF files are allowed to upload.'; 
        } 
    }else{ 
        $statusMsg = 'Please select an image file to upload.'; 
    } 
} 
 
// Display status message 
$url =  "images/" . $imageUploadPath;

echo json_encode( array(
    "status" => $statusMsg,
    "statusCode" => $statusCode,
    "url" => $url
    )
);

?>
<?php
require '../vendor/autoload.php';

use KasI\Fileuploadproject\modules\uploader\providers\ResumableFileUploader;

if (isset($_GET['resumableIdentifier']) && isset($_GET['resumableFilename']) && isset($_GET['resumableChunkNumber'])) {
    $identifier = $_GET['resumableIdentifier'];
    $filename = $_GET['resumableFilename'];
    $chunkNumber = $_GET['resumableChunkNumber'];
    $uploader = new ResumableFileUploader();

    if($uploader->check($filename, $chunkNumber)){
        http_response_code(200);
        echo "Chunk $chunkNumber already uploaded.";
    } else {
        http_response_code(404);
        echo "Chunk $chunkNumber not found.";
    }
} else {
    // Invalid request
    http_response_code(400);
    echo "Invalid request.";
}


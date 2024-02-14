<?php
require '../vendor/autoload.php';

use KasI\Fileuploadproject\modules\uploader\providers\ResumableFileUploader;

if (isset($_POST['resumableIdentifier']) && isset($_POST['resumableFilename']) && isset($_POST['resumableChunkNumber'])) {
    $identifier = $_POST['resumableIdentifier'];
    $filename = $_POST['resumableFilename'];
    $chunkNumber = $_POST['resumableChunkNumber'];

    $uploader = new ResumableFileUploader();
    $uploader->upload($filename, $chunkNumber, $_POST['resumableTotalChunks']);
} else {
    echo "Invalid request.";
}




<?php namespace KasI\Fileuploadproject\modules\uploader\providers;

class ResumableFileUploader
{
    const UPLOAD_FOLDER_PATH = "../uploads/";
    const CHUNKS_FOLDER_PATH = "../chunks/";
    public function check($filename, $chunkNumber)
    {
        $chunkDir = $this->getChunkDirectory();
        $chunkPath = $chunkDir . $filename . '.' . $chunkNumber;
        return file_exists($chunkPath);
    }

    public function upload($filename, $chunkNumber, $resumableTotalChunks)
    {
        $targetDir = $this->getUploadDirectory();
        $chunkDir = $this->getChunkDirectory();

        // Create directory for the chunks if it doesn't exist
        if (!file_exists($chunkDir)) {
            mkdir($chunkDir, 0777, true);
        }

        // Move the uploaded chunk to the chunk directory
        move_uploaded_file($_FILES['file']['tmp_name'], $chunkDir . $filename . '.' . $chunkNumber);

        // Check if all chunks have been uploaded
        $totalChunks = isset($resumableTotalChunks) ? (int)$resumableTotalChunks : 1;

        if ($chunkNumber == $totalChunks) {
            // All chunks have been uploaded, combine them into the final file
            $finalPath = $targetDir . $filename;
            $finalFile = fopen($finalPath, 'wb');

            for ($i = 1; $i <= $totalChunks; $i++) {
                $chunkPath = $chunkDir . $filename . '.' . $i;
                $chunkFile = fopen($chunkPath, 'rb');
                stream_copy_to_stream($chunkFile, $finalFile);
                fclose($chunkFile);
                unlink($chunkPath); // Delete the chunk after it has been merged
            }

            fclose($finalFile);
            echo "File uploaded successfully.";
        } else {
            // Chunk uploaded successfully
            echo "Chunk $chunkNumber uploaded successfully.";
        }

    }

    private function getChunkDirectory()
    {
        return self::CHUNKS_FOLDER_PATH;
    }

    private function getUploadDirectory()
    {
        return self::UPLOAD_FOLDER_PATH;
    }
}
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

        if (!file_exists($chunkDir)) {
            mkdir($chunkDir, 0777, true);
        }

        move_uploaded_file($_FILES['file']['tmp_name'], $chunkDir . $filename . '.' . $chunkNumber);

        $totalChunks = isset($resumableTotalChunks) ? (int)$resumableTotalChunks : 1;

        if ($chunkNumber == $totalChunks) {
            $finalPath = $targetDir . $filename;
            $finalFile = fopen($finalPath, 'wb');

            for ($i = 1; $i <= $totalChunks; $i++) {
                $chunkPath = $chunkDir . $filename . '.' . $i;
                $chunkFile = fopen($chunkPath, 'rb');
                stream_copy_to_stream($chunkFile, $finalFile);
                fclose($chunkFile);
                unlink($chunkPath);
            }

            fclose($finalFile);
            echo "File uploaded successfully.";
        } else {
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
document.addEventListener("DOMContentLoaded", function() {
    var resumable = new Resumable({
        target: 'upload.php', // URL to your upload PHP script
        testTarget: 'check.php',
        chunkSize: 1 * 1024 * 1024, // 1MB chunks
        simultaneousUploads: 3, // Number of simultaneous uploads
        testChunks: true, // Enable chunk testing
    });

    resumable.on('fileAdded', function(file) {
        resumable.upload(); // This triggers the upload process including chunk testing
    });

    resumable.on('progress', function() {
        var progressBar = document.getElementById('progressBar');
        var progress = (resumable.progress() * 100).toFixed(0);
        var progressValue = progress + '%';
        var progressSpan = document.getElementById('percentSpan');
        progressBar.style.width = progressValue;
        progressSpan.textContent = progressValue;
    });

    resumable.on('fileSuccess', function(file, message) {
        var uploadResult = document.getElementById('uploadResult');
        uploadResult.textContent = "File uploaded successfully.";
    });

    resumable.on('fileError', function(file, message) {
        var uploadResult = document.getElementById('uploadResult');
        uploadResult.textContent = "Error uploading file.";
    });

    document.getElementById('uploadButton').addEventListener('click', function() {
        var fileInput = document.getElementById('fileToUpload');
        var files = fileInput.files;
        if (files.length > 0) {
            resumable.addFile(files[0]);
        } else {
            alert('Please select a file to upload.');
        }
    });

    var fileInput = document.getElementById('fileToUpload');

    // Add an event listener for the 'change' event
    fileInput.addEventListener('change', function(event) {
        var progressBar = document.getElementById('progressBar');
        var progressSpan = document.getElementById('percentSpan');
        progressBar.style.width = '0%';
        progressSpan.textContent = '0%';
        var fileSelectedEvent = new Event('fileSelected');
        document.dispatchEvent(fileSelectedEvent);
    });
});

document.getElementById('imageInput').addEventListener('change', function(e) {
    const fileName = e.target.files[0]?.name;
    const fileNameElement = document.getElementById('fileName');
    const fileText = document.querySelector('.file-text');

    if (fileName) {
        fileNameElement.textContent = fileName;
        fileText.textContent = 'File Selected';
    } else {
        fileNameElement.textContent = '';
        fileText.textContent = 'Choose Image File';
    }
});

// Add loading state to form submission
document.getElementById('uploadForm').addEventListener('submit', function(e) {
    const submitBtn = this.querySelector('.submit-btn');
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Uploading...';
    submitBtn.disabled = true;
});

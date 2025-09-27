function previewImage(input) {
    const file = input.files[0];
    const previewContainer = document.getElementById('preview-container');
    const preview = document.getElementById('avatar-preview');
    const label = document.querySelector('.file-label');
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            previewContainer.style.display = 'block';
            label.textContent = 'تم اختيار: ' + file.name;
        };
        reader.readAsDataURL(file);
    } else {
        previewContainer.style.display = 'none';
        label.textContent = 'اختر صورة شخصية أو اسحبها هنا';
    }
}

// إضافة دعم السحب والإفلات
const fileLabel = document.querySelector('.file-label');
const fileInput = document.getElementById('avatar');

['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
    fileLabel.addEventListener(eventName, preventDefaults, false);
});

function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
}

['dragenter', 'dragover'].forEach(eventName => {
    fileLabel.addEventListener(eventName, highlight, false);
});

['dragleave', 'drop'].forEach(eventName => {
    fileLabel.addEventListener(eventName, unhighlight, false);
});

function highlight(e) {
    fileLabel.style.borderColor = '#667eea';
    fileLabel.style.background = '#e8f0fe';
}

function unhighlight(e) {
    fileLabel.style.borderColor = '#dee2e6';
    fileLabel.style.background = '#f8f9fa';
}

fileLabel.addEventListener('drop', handleDrop, false);

function handleDrop(e) {
    const dt = e.dataTransfer;
    const files = dt.files;
    fileInput.files = files;
    previewImage(fileInput);
}

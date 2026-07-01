/**
 * modules/profile/cropper.js
 * Cropper.js integration: register page photo crop, profile page photo crop.
 */

// ==========================================
// REGISTER PAGE CROPPER
// ==========================================
let registerCropperInstance = null;
let registerCropperObjectUrl = null;

export function initRegisterPage() {
    const photoInput = document.getElementById('photo_profile');
    if (!photoInput) return;
    photoInput.addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (file && file.type.startsWith('image/')) {
            window.openRegisterCropperModal(file);
        } else {
            document.getElementById('profile-preview')?.classList.add('hidden');
            document.getElementById('profile-placeholder')?.classList.remove('hidden');
            if (file) alert('Hanya gambar yang diperbolehkan!');
        }
    });
}

window.openRegisterCropperModal = function (file) {
    const modal = document.getElementById('cropper-modal');
    const img = document.getElementById('cropper-image');
    if (!modal || !img) return;
    if (registerCropperObjectUrl) URL.revokeObjectURL(registerCropperObjectUrl);
    registerCropperObjectUrl = URL.createObjectURL(file);
    img.src = registerCropperObjectUrl;
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    img.onload = function () {
        if (registerCropperInstance) registerCropperInstance.destroy();
        registerCropperInstance = new Cropper(img, { aspectRatio: 1, viewMode: 1, autoCropArea: 1, responsive: true, background: false });
        document.getElementById('cropper-modal-content')?.classList.add('scale-100', 'opacity-100');
    };
    const zoomRange = document.getElementById('cropper-zoom-range');
    if (zoomRange) {
        zoomRange.value = 1;
        zoomRange.oninput = function (e) {
            if (registerCropperInstance) registerCropperInstance.zoomTo(parseFloat(e.target.value));
        };
    }
};

window.closeRegisterCropperModal = function () {
    const modal = document.getElementById('cropper-modal');
    if (modal) modal.classList.add('hidden');
    document.body.style.overflow = '';
    if (registerCropperInstance) { registerCropperInstance.destroy(); registerCropperInstance = null; }
    if (registerCropperObjectUrl) { URL.revokeObjectURL(registerCropperObjectUrl); registerCropperObjectUrl = null; }
};

window.cancelCropper = function () { window.closeRegisterCropperModal(); };

window.confirmCrop = function () {
    if (!registerCropperInstance) return window.closeRegisterCropperModal();
    registerCropperInstance.getCroppedCanvas({ width: 800, height: 800, imageSmoothingQuality: 'high' }).toBlob(function (blob) {
        if (!blob) return alert('Gagal memproses gambar');
        const preview = document.getElementById('profile-preview');
        const placeholder = document.getElementById('profile-placeholder');
        const url = URL.createObjectURL(blob);
        if (preview) { preview.src = url; preview.classList.remove('hidden'); }
        if (placeholder) placeholder.classList.add('hidden');
        const croppedFile = new File([blob], 'photo_profile.jpg', { type: blob.type });
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(croppedFile);
        const input = document.getElementById('photo_profile');
        if (input) input.files = dataTransfer.files;
        window.closeRegisterCropperModal();
    }, 'image/jpeg', 0.9);
};

// ==========================================
// PROFILE PAGE CROPPER
// ==========================================
let profileCropperInstance = null;
let profileCropperFile = null;
let profileCropperObjectUrl = null;

export function initProfilePage() {
    const photoProfileInput = document.getElementById('photo_profile_input');
    if (photoProfileInput) {
        photoProfileInput.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (!file) return;
            window.openProfileCropperModal(file);
        });
    }
    const bgInput = document.getElementById('background_input');
    if (bgInput) {
        bgInput.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = function (ev) {
                const coverDiv = document.querySelector('#actual-content .relative.h-48');
                if (coverDiv) {
                    coverDiv.style.backgroundImage = `url('${ev.target.result}')`;
                    coverDiv.classList.add('bg-cover', 'bg-center');
                }
            };
            reader.readAsDataURL(file);
            document.getElementById('save-button-container')?.classList.remove('hidden');
        });
    }
    window.updatePreview?.();
    const skeleton = document.getElementById('skeleton-loading');
    const actualContent = document.getElementById('actual-content');
    if (skeleton && actualContent) { skeleton.classList.add('hidden'); actualContent.style.display = 'block'; }
}

window.openProfileCropperModal = function (file) {
    const modal = document.getElementById('cropper-modal');
    const image = document.getElementById('cropper-image');
    const zoomRange = document.getElementById('cropper-zoom-range');
    if (!modal || !image || !zoomRange) return;
    if (profileCropperInstance) { profileCropperInstance.destroy(); profileCropperInstance = null; }
    profileCropperFile = file;
    if (profileCropperObjectUrl) { URL.revokeObjectURL(profileCropperObjectUrl); profileCropperObjectUrl = null; }
    profileCropperObjectUrl = URL.createObjectURL(file);
    zoomRange.value = '1';
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    image.onload = function () {
        profileCropperInstance = new Cropper(image, {
            aspectRatio: 1, viewMode: 1, movable: true, zoomable: true,
            responsive: true, autoCropArea: 1, background: false, preview: '#cropper-preview-container',
        });
    };
    image.src = profileCropperObjectUrl;
};

window.closeCropperModal = function () {
    const modal = document.getElementById('cropper-modal');
    if (!modal) return;
    modal.classList.add('hidden');
    document.body.style.overflow = '';
    if (profileCropperInstance) { profileCropperInstance.destroy(); profileCropperInstance = null; }
    if (profileCropperObjectUrl) { URL.revokeObjectURL(profileCropperObjectUrl); profileCropperObjectUrl = null; }
};

window.cancelProfileCropper = function () {
    const input = document.getElementById('photo_profile_input');
    if (input) input.value = '';
    window.closeCropperModal();
};

window.cropperZoom = function (amount) {
    if (!profileCropperInstance) return;
    profileCropperInstance.zoom(amount);
    const zoomRange = document.getElementById('cropper-zoom-range');
    if (zoomRange) {
        const current = parseFloat(zoomRange.value) + amount;
        zoomRange.value = Math.min(3, Math.max(0.5, current));
    }
};

document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('cropper-zoom-range')?.addEventListener('input', function (e) {
        if (profileCropperInstance) profileCropperInstance.zoomTo(parseFloat(e.target.value));
    });
});

window.confirmProfileCrop = function () {
    if (!profileCropperInstance || !profileCropperFile) return;
    const outputType = ['image/png', 'image/jpeg'].includes(profileCropperFile.type) ? profileCropperFile.type : 'image/jpeg';
    const outputExt = outputType === 'image/png' ? 'png' : 'jpg';
    profileCropperInstance.getCroppedCanvas({ width: 512, height: 512, imageSmoothingQuality: 'high' }).toBlob(function (blob) {
        if (!blob) return;
        const fileName = profileCropperFile.name.replace(/\.[^/.]+$/, `.${outputExt}`);
        const croppedFile = new File([blob], fileName, { type: outputType });
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(croppedFile);
        const input = document.getElementById('photo_profile_input');
        if (input) input.files = dataTransfer.files;
        const preview = document.getElementById('profile-preview');
        const placeholder = document.getElementById('profile-preview-placeholder');
        const objectUrl = URL.createObjectURL(blob);
        if (preview) {
            preview.src = objectUrl;
        } else if (placeholder) {
            const newImg = document.createElement('img');
            newImg.id = 'profile-preview';
            newImg.className = 'w-full h-full object-cover';
            newImg.src = objectUrl;
            placeholder.replaceWith(newImg);
        }
        document.getElementById('save-button-container')?.classList.remove('hidden');
        window.closeCropperModal();
    }, outputType, 0.92);
};

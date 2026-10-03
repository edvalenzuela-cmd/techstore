<?php
declare(strict_types=1);

/*
 * MODULE: File Upload (Member 2)
 * Flow: $_FILES -> validate_upload() -> save_upload() -> move_uploaded_file()
 */

const UPLOAD_DIR = __DIR__ . '/../uploads/';
const MAX_UPLOAD_BYTES = 2 * 1024 * 1024; // 2 MB
const ALLOWED_IMAGE_TYPES = [
    'image/jpeg' => 'jpg',
    'image/png'  => 'png',
    'image/webp' => 'webp',
];

// Returns a list of error messages (empty = OK). Image is optional.
function validate_upload(array $file): array
{
    $errors = [];

    if (empty($file) || ($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return $errors; // nothing uploaded
    }

    $error = $file['error'];
    if ($error !== UPLOAD_ERR_OK) {
        $errors[] = match ($error) {
            UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => 'Image is too large for the server.',
            UPLOAD_ERR_PARTIAL                        => 'Upload was interrupted. Try again.',
            default                                   => 'Upload failed (error code ' . $error . ').',
        };
        return $errors;
    }

    if ($file['size'] > MAX_UPLOAD_BYTES) {
        $errors[] = 'Image must be 2 MB or smaller.';
    }

    // Check the REAL mime type from file contents, not the client-supplied one
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file['tmp_name']);
    if (!array_key_exists($mime, ALLOWED_IMAGE_TYPES)) {
        $errors[] = 'Only JPG, PNG, or WEBP images are allowed.';
    }

    return $errors;
}

// Moves the validated file into /uploads with a random name. Returns filename or null.
function save_upload(array $file): ?string
{
    if (empty($file) || ($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        return null;
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $ext = ALLOWED_IMAGE_TYPES[$finfo->file($file['tmp_name'])];
    $newName = bin2hex(random_bytes(8)) . '.' . $ext;

    if (!is_dir(UPLOAD_DIR)) {
        mkdir(UPLOAD_DIR, 0755, true);
    }

    if (move_uploaded_file($file['tmp_name'], UPLOAD_DIR . $newName)) {
        return $newName;
    }
    return null;
}

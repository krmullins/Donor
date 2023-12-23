<?php
//Decryption function
function decrypt_data($encrypted_text, $encryption_key) {
    $encrypted_text = base64_decode($encrypted_text);
    $parts = explode('::', $encrypted_text, 2);
    if (count($parts) != 2) {
        return false;
    }
    list($encrypted_text, $iv) = $parts;
    $decrypted_text = openssl_decrypt($encrypted_text, 'aes-256-cbc', $encryption_key, 0, $iv);
    if ($decrypted_text === false) {
        return false;
    }
    return $decrypted_text;
}

$encrypted_data = $_POST['encrypted_data'];
$decrypt_data=decrypt_data($encrypted_data,'XG0CXT4vsXEykreMd8tcaN928u5ozKmd');

echo $decrypt_data;


<?php

define('SECRET_KEY', getenv('SECRET_KEY') ?: 'gizliAnahtar');
define('IV_SIZE', openssl_cipher_iv_length('aes-256-cbc'));

function generateToken($data) {
    $iv = openssl_random_pseudo_bytes(IV_SIZE);
    $encryptedData = openssl_encrypt($data, 'aes-256-cbc', SECRET_KEY, OPENSSL_RAW_DATA, $iv);
    $token = base64_encode($iv . $encryptedData);
    return rtrim(strtr($token, '+/', '-_'), '=');
}

function decryptToken($token) {
    $paddedToken = str_pad(strtr($token, '-_', '+/'), strlen($token) % 4, '=', STR_PAD_RIGHT);
    $decodedToken = base64_decode($paddedToken);
    $iv = substr($decodedToken, 0, IV_SIZE);
    $encryptedData = substr($decodedToken, IV_SIZE);
    $decryptedData = openssl_decrypt($encryptedData, 'aes-256-cbc', SECRET_KEY, OPENSSL_RAW_DATA, $iv);
    return $decryptedData;
}

$originalData = "Bu veri şifrelenecek";
$token = generateToken($originalData);
$decodedData = decryptToken($token);

echo "Orijinal Veri: " . $originalData . "<br>";
echo "Oluşturulan Token: " . $token . "<br>";
echo "Çözülen Veri: " . $decodedData . "<br>";

?>

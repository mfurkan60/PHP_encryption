function generateToken($data) {
    // Anahtar oluştur
    $key = "gizliAnahtar"; // Güvenli bir anahtar kullanmalısınız

    // Veriyi şifrele
    $encryptedData = openssl_encrypt($data, 'aes-256-cbc', $key, 0, $key);

    // Şifrelenmiş veriyi URL güvenli hale getir
    $urlSafeToken = rtrim(strtr(base64_encode($encryptedData), '+/', '-_'), '=');

    return $urlSafeToken;
}

function decryptToken($token) {
    // Anahtar oluştur
    $key = "gizliAnahtar"; // Güvenli bir anahtar kullanmalısınız

    // URL güvenli tokeni orijinal hale getir
    $paddedToken = str_pad(strtr($token, '-_', '+/'), strlen($token) % 4, '=', STR_PAD_RIGHT);

    // Tokeni çöz
    $decryptedData = openssl_decrypt(base64_decode($paddedToken), 'aes-256-cbc', $key, 0, $key);

    return $decryptedData;
}

// Token oluştur
$originalData = "Bu veri şifrelenecek";
$token = generateToken($originalData);

// Tokeni çöz
$decodedData = decryptToken($token);

echo "Orijinal Veri: " . $originalData . "<br>";
echo "Oluşturulan Token: " . $token . "<br>";
echo "Çözülen Veri: " . $decodedData . "<br>";

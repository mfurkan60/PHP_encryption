<?php

 /**
 * Verilen veriyi, RSA özel anahtarıyla imzalar.
 * 
 * Bu fonksiyon, verinin bütünlüğünü ve kaynağını doğrulamak için kullanılır.
 * Verinin imzalanması, yalnızca doğru özel anahtara sahip kişi tarafından yapılabilir.
 * 
 * @param string $data İmzalanacak veri
 * @param string $privateKey RSA özel anahtarı
 * @return string Base64 encode edilmiş dijital imza
 */


function signData($data, $privateKey) {
    // RSA özel anahtarı ile veriyi imzala
    $signature = null;
    $result = openssl_sign($data, $signature, $privateKey, OPENSSL_ALGO_SHA512);
    
    // Eğer imzalama başarısız olduysa, hata mesajı döndürülür
    if (!$result) {
        throw new Exception("Veri imzalanırken bir hata oluştu.");
    }

    // İmzayı Base64 formatına dönüştür
    return base64_encode($signature);
}

 
function verifySignature($data, $signature, $publicKey) {
    // İmza verisini Base64 formatından orijinal haline döndür
    $decodedSignature = base64_decode($signature);

    // RSA genel anahtarı ile imzayı doğrula
    $result = openssl_verify($data, $decodedSignature, $publicKey, OPENSSL_ALGO_SHA512);

    // Eğer doğrulama başarılı ise, result 1 dönecektir
    if ($result === 1) {
        return true; // Geçerli imza
    } elseif ($result === 0) {
        return false; // Geçersiz imza
    } else {
        throw new Exception("İmza doğrulama hatası: " . openssl_error_string());
    }
}
 
function generateRSAKeys() {
    $config = [
        "private_key_bits" => 2048, // Anahtar uzunluğu genel olarak böyle tutulur . 
        "private_key_type" => OPENSSL_KEYTYPE_RSA
    ];

    // RSA anahtar çiftini oluştur
    $res = openssl_pkey_new($config);
    
    if (!$res) {
        throw new Exception("Anahtar çiftinin oluşturulması başarısız oldu.");
    }

    // Özel ve genel anahtarları çıkar
    openssl_pkey_export($res, $privateKey);
    $publicKey = openssl_pkey_get_details($res)["key"];

    return [
        'privateKey' => $privateKey,
        'publicKey' => $publicKey
    ];
}

/**
 * İmzalama ve doğrulama işleminin test edilmesi
 */
function testSigningAndVerification() {
    // RSA anahtarlarını oluştur
    $keys = generateRSAKeys();
    $privateKey = $keys['privateKey'];
    $publicKey = $keys['publicKey'];

    // İmzalanacak veri
    $data = "Bu veri dijital imza ile korunacak.";

    try {
         $signature = signData($data, $privateKey);
        echo "Veri İmzalandı: " . $signature . "<br>";

         $isVerified = verifySignature($data, $signature, $publicKey);

        // Sonuçları ekrana yazdır
        if ($isVerified) {
            echo "İmza Geçerli: Veri doğrulandı.<br>";
        } else {
            echo "İmza Geçersiz: Veri doğrulanamadı.<br>";
        }
    } catch (Exception $e) {
        echo "Hata: " . $e->getMessage() . "<br>";
    }
}

 testSigningAndVerification();

?>

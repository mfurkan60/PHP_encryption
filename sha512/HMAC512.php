<?php

/**
 * HMAC (Hash-based Message Authentication Code) kullanarak mesajı şifreler.
 * 
 * Bu fonksiyon, verilen mesajı, gizli bir anahtar kullanarak SHA512 algoritmasıyla hash'ler.
 * Sonuç olarak, mesajın hem doğruluğunu hem de kaynağını doğrulamak için kullanılabilir.
 * 
 * @param string $message Mesaj
 * @param string $secretKey Gizli anahtar
 * @return string HMAC hash değeri (base64 encode edilmiş)
 */
function generateHMAC($message, $secretKey) {
    // SHA512 algoritmasıyla HMAC hash'ini oluşturuyoruz
    $hmac = hash_hmac('sha512', $message, $secretKey, true);

    // Hash değerini Base64 formatında döndürüyoruz
    return base64_encode($hmac);
}

/**
 * HMAC hash'inin doğruluğunu kontrol eder.
 * 
 * Bu fonksiyon, verilen mesajı ve gizli anahtarı kullanarak HMAC hash'ini tekrar oluşturur
 * ve verilen hash değeriyle karşılaştırır. Eğer eşleşiyorsa, doğrulama başarılıdır.
 * 
 * @param string $message Mesaj
 * @param string $providedHMAC Sağlanan HMAC hash'i
 * @param string $secretKey Gizli anahtar
 * @return bool HMAC doğrulaması başarılıysa true, başarısızsa false döner
 */
function verifyHMAC($message, $providedHMAC, $secretKey) {
    
    $decodedHMAC = base64_decode($providedHMAC);

    
    $generatedHMAC = hash_hmac('sha512', $message, $secretKey, true);

    
    return hash_equals($generatedHMAC, $decodedHMAC);
}

 
function testHMAC() {
    // Gizli anahtar
    $secretKey = "gizliAnahtar123";

    // Test mesajı
    $message = "Bu, HMAC ile şifrelenmiş mesajdır.";

    try {
        // HMAC oluşturma
        $generatedHMAC = generateHMAC($message, $secretKey);
        echo "Oluşturulan HMAC: " . $generatedHMAC . "<br>";

        // HMAC doğrulama
        $isValid = verifyHMAC($message, $generatedHMAC, $secretKey);

        if ($isValid) {
            echo "HMAC doğrulama başarılı: Mesaj doğru.<br>";
        } else {
            echo "HMAC doğrulama başarısız: Mesaj değiştirilmiş olabilir.<br>";
        }

        // Yanlış bir HMAC ile doğrulama
        $invalidHMAC = base64_encode("yanlışHMACdegeri");
        $isValid = verifyHMAC($message, $invalidHMAC, $secretKey);

        if ($isValid) {
            echo "Yanlış HMAC doğrulandı, bir hata olmalı.<br>";
        } else {
            echo "Yanlış HMAC doğrulama başarısız: Mesajın bütünlüğü korunmuş.<br>";
        }
    } catch (Exception $e) {
        echo "Hata: " . $e->getMessage() . "<br>";
    }
}

// HMAC testi
testHMAC();

?>

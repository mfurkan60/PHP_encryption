<?php


/**
 * SHA512 Hash Algoritması ile Veri Hash'leme
 * 
 * Bu fonksiyon, verilen veriyi SHA512 algoritması ile hash'ler ve 
 * döndürülen hash değeri üzerinden verinin doğruluğu kontrol edilebilir.
 * SHA512 algoritması, 512-bit (64-byte) uzunluğunda bir hash değeri döndürür.
 * 
 * @param string $data Veriyi hash'lemek için kullanılan metin
 * @return string Hashlenmiş veri
 */

function generateSHA512Hash($data) {
    $hash = hash('sha512', $data);
    return $hash;
}

function verifySHA512Hash($data, $hash) {
    $generatedHash = hash('sha512', $data);
    return hash_equals($generatedHash, $hash);
}

function hashPassword($password) {
    return generateSHA512Hash($password);
}

function verifyPassword($password, $storedHash) {
    return verifySHA512Hash($password, $storedHash);
}

$password = "gizliParola123";
$hashedPassword = hashPassword($password);

echo "Hashlenmiş Parola: " . $hashedPassword . "<br>";

$isPasswordValid = verifyPassword("gizliParola123", $hashedPassword);
echo "Parola Doğrulama: " . ($isPasswordValid ? "Geçerli" : "Geçersiz") . "<br>";

$isPasswordValid = verifyPassword("yanlisParola", $hashedPassword);
echo "Parola Doğrulama: " . ($isPasswordValid ? "Geçerli" : "Geçersiz") . "<br>";

?>

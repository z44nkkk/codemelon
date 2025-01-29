<?php 

class Encrypt {
    public static $password = "sdqxeacwzhjtnugmyb";


    public static function  encrypt($plaintext) {
        $compressed = gzcompress($plaintext);  // Comprimir el texto
        $method = 'aes-256-cbc';
        $key = substr(hash('sha256', static::$password, true), 0, 32);
        $iv = openssl_random_pseudo_bytes(16);
        $ciphertext = openssl_encrypt($compressed, $method, $key, OPENSSL_RAW_DATA, $iv);
        return base64_encode($iv . $ciphertext);
    }

    public static function  decrypt($ciphertext_base64) {
        $method = 'aes-256-cbc';
        $ciphertext = base64_decode($ciphertext_base64);
        $key = substr(hash('sha256', static::$password, true), 0, 32);
        $iv = substr($ciphertext, 0, 16);
        $ciphertext = substr($ciphertext, 16);
        $compressed = openssl_decrypt($ciphertext, $method, $key, OPENSSL_RAW_DATA, $iv);
        if ($compressed === false) {
            return "";  // Decryption failed
        }
        return gzuncompress($compressed);  // Descomprimir después de desencriptar
    }

}


?>
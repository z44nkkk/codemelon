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
        // Check if the input is base64 encoded
        if (!preg_match('/^[A-Za-z0-9+\/=]+$/', $ciphertext_base64)) {
            return $ciphertext_base64;  // Return original text if not encrypted
        }

        // Try to decode base64
        $ciphertext = @base64_decode($ciphertext_base64, true);
        if ($ciphertext === false || strlen($ciphertext) < 16) {
            return $ciphertext_base64;  // Return original text if not valid base64 or too short
        }

        $method = 'aes-256-cbc';
        $key = substr(hash('sha256', static::$password, true), 0, 32);
        $iv = substr($ciphertext, 0, 16);
        $ciphertext = substr($ciphertext, 16);
        $compressed = @openssl_decrypt($ciphertext, $method, $key, OPENSSL_RAW_DATA, $iv);
        if ($compressed === false) {
            return $ciphertext_base64;  // Return original text if decryption fails
        }

        $decompressed = @gzuncompress($compressed);
        return ($decompressed !== false) ? $decompressed : $ciphertext_base64;
    }

}


?>
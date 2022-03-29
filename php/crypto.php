<?php
class Crypto{

    private $encrypt_method = "AES-256-CBC";
    private $secret_key = 'fds703rvd80v08';
    private $secret_iv = 'werw00erw900';

    private $key;
    private $iv;

    public function __construct(){
        $this->key = hash('sha256', $this->secret_key);
        
        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $this->iv = substr(hash('sha256', $this->secret_iv), 0, 16);
    }


    public function encrypt($string) {
        return base64_encode(openssl_encrypt($string, $this->encrypt_method, $this->key, 0, $this->iv));

    }

    public function decrypt($string) {
        return openssl_decrypt(base64_decode($string), $this->encrypt_method, $this->key, 0, $this->iv);
    }
}
?>
<?php
namespace UniPin;

class Api {

    private static $_FILE_MERCHANT = "../unipin.merchant";
    private static $_ERROR = false;

    private static $_MERCHANT = [];

    public static function Auth($_SECRETKEY){
        if(!file_exists(self::$_FILE_MERCHANT)){
            self::$_ERROR = true;
            Errors::Show(9000);
        }

        if(!self::$_ERROR){
            $_MERCHANT = file_get_contents(self::$_FILE_MERCHANT);
            $_CIPHER = "des-ecb";
            if (in_array($_CIPHER, openssl_get_cipher_methods()))
            {
                $_MERCHANT = openssl_decrypt($_MERCHANT, $_CIPHER, $_SECRETKEY);

                if(empty($_MERCHANT)){
                    self::$_ERROR = true;
                    Errors::Show(9002);
                }
            }
            else {
                self::$_ERROR = true;
                Errors::Show(9001);
            }
        }

        if(!self::$_ERROR){
            self::$_MERCHANT = json_decode($_MERCHANT);
            
            if(!isset(self::$_MERCHANT->ENV)){ self::$_ERROR = true; Errors::Show(9003);}
            if(!isset(self::$_MERCHANT->API_URL)){ self::$_ERROR = true; Errors::Show(9003);}
            if(!isset(self::$_MERCHANT->API_UNIBOX_URL)){ self::$_ERROR = true; Errors::Show(9003);}
            if(!isset(self::$_MERCHANT->IMAGES_URL)){ self::$_ERROR = true; Errors::Show(9003);}
            if(!isset(self::$_MERCHANT->GUID)){ self::$_ERROR = true; Errors::Show(9003);}
            if(!isset(self::$_MERCHANT->SECRET)){ self::$_ERROR = true; Errors::Show(9003);}
        }
    }

}

class Games{
    
    public static function GetList(){
        echo "Games On";
    }

}

class Errors {
    public function Show($_ERROR_CODE){
        if(!isset(self::$_ERROR_CODES[$_ERROR_CODE])){
            $_ERROR_CODE = 9999;
        }
        $_ERROR_MESSAGE = self::$_ERROR_CODES[$_ERROR_CODE];
        echo "\n<br />\n" . "UniPin - Error ($_ERROR_CODE): " . $_ERROR_MESSAGE . "\n<br />\n";
    }

    private static $_ERROR_CODES = [
        9000 => "Merchant credentials file not exists, please generate from CLI",
        9001 => "DES-ECB Encryption not enabled",
        9002 => "Invalid secret key",
        9003 => "Merchant credentials file is corrupted",
        9004 => "",
        9005 => "",
        9006 => "",
        9007 => "",
        9008 => "",
        9009 => "",
        9010 => "",
        9999 => "Unknown Error",
    ];
}


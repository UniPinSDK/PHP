<?php
namespace UniPin;

require "_HELPERS";

class Api {

    private static $_FILE_MERCHANT = __DIR__ . "/../unipin.merchant";
    private static $_ERROR = false;

    private static $_MERCHANT = [];

    public static function Auth($_SECRETKEY){
        if(!file_exists(self::$_FILE_MERCHANT)){
            self::$_ERROR = true;
            API_ERRORS::Show(9000);
        }

        if(!self::$_ERROR){
            $_MERCHANT = file_get_contents(self::$_FILE_MERCHANT);

            $_MERCHANT = DES::Decrypt($_MERCHANT, $_SECRETKEY);

            if (empty($_MERCHANT))
            {
                self::$_ERROR = true;
                API_ERRORS::Show(9002);
            }

            eval($_MERCHANT);
            $_MERCHANT = $_mer;
        }

        if(!self::$_ERROR){
            self::$_MERCHANT = $_MERCHANT;
            
            if(!isset(self::$_MERCHANT->ENV)){ self::$_ERROR = true; API_ERRORS::Show(9003);}
            if(!isset(self::$_MERCHANT->API_URL)){ self::$_ERROR = true; API_ERRORS::Show(9003);}
            if(!isset(self::$_MERCHANT->API_UNIBOX_URL)){ self::$_ERROR = true; API_ERRORS::Show(9003);}
            if(!isset(self::$_MERCHANT->IMAGES_URL)){ self::$_ERROR = true; API_ERRORS::Show(9003);}
            if(!isset(self::$_MERCHANT->GUID)){ self::$_ERROR = true; API_ERRORS::Show(9003);}
            if(!isset(self::$_MERCHANT->SECRET)){ self::$_ERROR = true; API_ERRORS::Show(9003);}
            if(!isset(self::$_MERCHANT->DOMAINS)){ self::$_ERROR = true; API_ERRORS::Show(9003);}
        }
    }

}

class Games{
    
    public static function GetList(){
        echo "Games On";
    }

}


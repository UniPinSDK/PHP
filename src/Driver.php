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

    public function GET_MER_PROP($_PROP) {
        $_VALUE = "";
        switch($_PROP){
            case "API_URL":
            case "GUID":
            case "SECRET":
                if(isset(self::$_MERCHANT->{$_PROP})){
                    $_VALUE = self::$_MERCHANT->{$_PROP};
                }
                break;
            default:
                $_value = "";
            break;
        }
        return $_VALUE;
    }

}

class Games{

    // ----- API Sub Paths
    private static $_API_PATH_GAMELIST = "in-game-topup/list";
    private static $_API_PATH_GAMEDETAIL = "in-game-topup/detail";
    private static $_API_PATH_USER_VALIDATE = "in-game-topup/user/validate";
    private static $_API_PATH_ORDER_CREATE = "in-game-topup/order/create";
    private static $_API_PATH_ORDER_INQUIRY = "in-game-topup/order/inquiry";
   
    public static function GetList(){
        $path = self::$_API_PATH_GAMELIST;
        $URL = Api::GET_MER_PROP("API_URL") . $path;
        $params = [];
        $headers = Helpers::GetHeaders($path);
        $res_json = IO::send($URL, $params, $headers);
        $res = json_decode($res_json);
        return $res;
    }

}

class Helpers {

    public static function GetHeaders($Link) {

        $_PartnerID = Api::GET_MER_PROP("GUID");
        $_SecretKey = DES::Decrypt(Api::GET_MER_PROP("SECRET"), $_PartnerID);
        $timestamp = strtotime(date("Y-m-d H:i:s"));
        $headers = [];
        $headers["partnerid"] = $_PartnerID;
        $headers["timestamp"] = $timestamp;
        $headers["path"] = $Link;
        $headers["auth"] = hash_hmac('sha256', $_PartnerID . $timestamp . $Link, $_SecretKey);

        return $headers;

    }

    public static function GetSignature( $MidString = "") {

        $_PartnerID = Client::GetPartnerID();
        $_SecretKey = Client::GetSecretKey();

        $_Signature = hash('sha256', $_PartnerID . $MidString . $_SecretKey);

        return $_Signature;

    }

}

class IO {

    public static function send($URL, $params, $headers=[], $display=false){

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$URL);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout after 30 seconds
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

        $headers_arr = array();
        array_push($headers_arr, "Content-type: application/json");
        if(count($headers) > 0) {
            foreach($headers as $h => $v){
                array_push($headers_arr, $h . ": " . $v);
            }
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers_arr);

        $params = json_encode($params);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);

        $result=curl_exec ($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);   //get status code
        curl_close ($ch);

        if($display){
            echo( $status_code . "<br>\n" . $result);
            exit();
        }

        if($status_code == 0 || $status_code == 200){
            return $result;
        }
        elseif($status_code == 401){
            return array("status" => 401, "message" => "UNAUTHORIZED");
        }
        elseif($status_code == 403){
            return array("status" => 403, "message" => "UNAUTHORIZED");
        }
        else
        {
            return array("status" => $status_code, "message" => "UNKNOWN ERROR OCCURRED");
        }
    }

}


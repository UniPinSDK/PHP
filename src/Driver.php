<?php

/* ********************************************************************************************
*
*   Developers:         UniPin
*   API Name:           UniPin Games TopUp
*   Dated:              2019-09-17
*   Developed:          Sample code for partner integrations
*   System Requirement: PHP 5.0 or greater
*   Code authorization: This SDK class code is developed for partners to integrate easily into
*                       their systems. Modifications are allowed if there is incompatibility
*                       between partner system coding/framework and SDK class. 
*
*********************************************************************************************** */
namespace UniPin;

define("STAGGING", 0);
define("PRODUCTION", 1);

class API {


    /*  ******************************************************************
    *   NOTICE:
    *   Use API_Status as switch between stagging and production account.
    *   Choose any one value as API status from STAGGING and PRODUCTION.
    *   ****************************************************************** */

    private static $_API_Status = STAGGING; // <------- Use STAGGING or PRODUCTION

    //private static $_API_Status = [[__API_ENV__]]; // <------- Use STAGGING or PRODUCTION

    // ---------------------------------------------------------------------


    
    // ----- API Urls
    //private static $_API_URL_STAGGING = "http://unipinapi/";
    private static $_API_URL_STAGGING = "https://dev-api.unipin.com/";
    private static $_API_URL_PRODUCTION = "https://api.unipin.com/";

    private static $_API_UNIBOX_URL_STAGGING = "https://dev.unipin.com/api/unibox/";
    private static $_API_UNIBOX_URL_PRODUCTION = "https://services.unipin.com/api/";

    // ----- API Assets Links
    private static $_API_ASSETS_IMAGES = "http://unipin.com/";
    
    //// ----- API Urls
    //private static $_API_URL_STAGGING = "[[__API_URL_STAGGING__]]";
    //private static $_API_URL_PRODUCTION = "[[__API_URL_PROD__]]";

    //private static $_API_UNIBOX_URL_STAGGING = "[[__API_UNIBOX_URL_STAGGING__]]";
    //private static $_API_UNIBOX_URL_PRODUCTION = "[[__API_UNIBOX_URL_PROD__]]";

    //// ----- API Assets Links
    //private static $_API_ASSETS_IMAGES = "[[__API_URL_IMAGES__]]";

    public static function GetUrl() {
        return API::$_API_Status == STAGGING? API::$_API_URL_STAGGING: API::$_API_URL_PRODUCTION;
    }

    public static function GetUrlUniBox() {
        return API::$_API_Status == STAGGING? API::$_API_UNIBOX_URL_STAGGING: API::$_API_UNIBOX_URL_PRODUCTION;
    }

    public static function GetUrlAssets() {
        return API::$_API_ASSETS_IMAGES;
    }

}

class Client {

    // ----- Auth Credentials
    //private static $_PartnerID = "324D8BF4-F149-720C-1A4D-60815EEEAE6F";
    //private static $_SecretKey = "B8st9opV5aud2YAv";
    // ----- Auth Credentials
    // private static $_PartnerID = "ac31c674-f1e6-43cf-b90e-589543c624ae";
    // private static $_SecretKey = "zaypbawroqth18ps";
    private static $_PartnerID = "9fe0608a-4a78-4876-bcd0-abd8ca6c0cfb";
    private static $_SecretKey = "w7msrvmm0go3mlyg";

    // ----- Auth Credentials
    //private static $_PartnerID = "[[__API_PARTNER_ID__]]";
    //private static $_SecretKey = "[[__API_PARTNER_SECRETKEY__]]";

    public static function GetPartnerID(){
        return Client::$_PartnerID;
    }

    public static function GetSecretKey(){
        return Client::$_SecretKey;
    }
}

class Games {

    // ----- API Sub Paths
    private static $_API_PATH_GAMELIST = "in-game-topup/list";
    private static $_API_PATH_GAMEDETAIL = "in-game-topup/detail";
    private static $_API_PATH_USER_VALIDATE = "in-game-topup/user/validate";
    private static $_API_PATH_ORDER_CREATE = "in-game-topup/order/create";
    private static $_API_PATH_ORDER_INQUIRY = "in-game-topup/order/inquiry";

    public static function GetList() {

        $path = Games::$_API_PATH_GAMELIST;

        $URL = API::GetUrl() . $path;

        $params = [];

        $headers = Helpers::GetHeaders($path);

        $res_json = IO::send($URL, $params, $headers);
        print_r($res_json); exit();
        $res = json_decode($res_json);
        return $res;

    } 

    public static function GetDetail( $GameCode = "", $description = false) {

        $path = Games::$_API_PATH_GAMEDETAIL;

        if($GameCode == ""){
            return "ERROR: GameCode is required!";
        }

        echo $URL = API::GetUrl() . $path;

        $params = [];
        $params["game_code"] = $GameCode;

        if($description){
            $params["description"] = true;
        }

        $headers = Helpers::GetHeaders($path);

        $res_json = IO::send($URL, $params, $headers);
        $res = json_decode($res_json);

        if($res->help_image_url){
            $res->help_image_url = API::GetUrlAssets() . $res->help_image_url;
        }

        return $res;

    }

    public static function UserValidate( $GameCode = "", $fields = []) {

        $path = Games::$_API_PATH_USER_VALIDATE;

        if($GameCode == ""){
            return "ERROR: GameCode is required!";
        }

        $URL = API::GetUrl() . $path;

        $params = [];
        $params["game_code"] = $GameCode;
        $params["fields"] = $fields;

        $headers = Helpers::GetHeaders($path);

        $res_json = IO::send($URL, $params, $headers);
        $res = json_decode($res_json);

        return $res;

    }

    public static function OrderCreate( $GameCode = "", $ValidationToken = "", $ReferenceNo = "", $DenominationId = "") {

        $path = Games::$_API_PATH_ORDER_CREATE;

        if($GameCode == ""){
            return "ERROR: GameCode is required!";
        }

        if($ValidationToken == ""){
            return "ERROR: Validation Token is required!";
        }

        if($ReferenceNo == ""){
            return "ERROR: Unique Reference No. is required!";
        }

        if($DenominationId == ""){
            return "ERROR: Denomination ID is required!";
        }

        $URL = API::GetUrl() . $path;

        $params = [];
        $params["game_code"] = $GameCode;
        $params["validation_token"] = $ValidationToken;
        $params["reference_no"] = $ReferenceNo;
        $params["denomination_id"] = $DenominationId;

        $headers = Helpers::GetHeaders($path);

        $res_json = IO::send($URL, $params, $headers);
        $res = json_decode($res_json);

        return $res;

    }

    public static function OrderInquiry( $ReferenceNo = "") {

        $path = Games::$_API_PATH_ORDER_INQUIRY;

        if($ReferenceNo == ""){
            return "ERROR: Unique Reference No. is required!";
        }

        $URL = API::GetUrl() . $path;

        $params = [];
        $params["reference_no"] = $ReferenceNo;

        $headers = Helpers::GetHeaders($path);

        $res_json = IO::send($URL, $params, $headers);
        $res = json_decode($res_json);

        return $res;

    }

    public static function GameTopUp( $GameCode = "", $ReferenceNo = "", $DenominationId = "", $UserFields = []) {

        $Response = [];

        $UserValidate = Games::UserValidate( $GameCode, $UserFields);

        if( $UserValidate->status == 1) {
            $ValidationToken = $UserValidate->validation_token;
            $Order = Games::OrderCreate( $GameCode, $ValidationToken, $ReferenceNo, $DenominationId);

            if( $Order->status == 1) {
                $Response = $Order;
            }
            else {
                $Response = [
                    "status" => $Order->status,
                    "error" => $Order->error,
                ];
            }

        }
        else {
            $Response = [
                "status" => $UserValidate->status,
                "error" => $UserValidate->error,
            ];
        }

        return json_decode( json_encode( $Response));

    }

}

class Vouchers {

    // ----- API Sub Paths
    private static $_API_PATH_VOUCHER_LIST = "voucher/list";
    private static $_API_PATH_VOUCHER_DETAILS = "voucher/details";
    private static $_API_PATH_VOUCHER_REQUEST = "voucher/request";
    private static $_API_PATH_VOUCHER_INQUIRY = "voucher/inquiry";
    private static $_API_PATH_VOUCHER_BALANCE = "voucher/balance";

    public static function GetList() {

        $path = Vouchers::$_API_PATH_VOUCHER_LIST;

        $URL = API::GetUrl() . $path;

        $timestamp = strtotime(date("Y-m-d H:i:s"));
        $signature = Helpers::GetSignature($timestamp);

        $params = [];
        $params["partner_guid"] = Client::GetPartnerID();
        $params["logid"] = $timestamp;
        $params["signature"] = $signature;

        $res_json = IO::send($URL, $params);
        $res = json_decode($res_json);

        return $res;

    } 

    public static function GetDetail( $VoucherCode = "") {

        $path = Vouchers::$_API_PATH_VOUCHER_DETAILS;

        if($VoucherCode == ""){
            return "ERROR: VoucherCode is required!";
        }

        $URL = API::GetUrl() . $path;

        $timestamp = strtotime(date("Y-m-d H:i:s"));
        $signature = Helpers::GetSignature($timestamp);

        $params = [];
        $params["voucher_code"] = $VoucherCode;
        $params["partner_guid"] = Client::GetPartnerID();
        $params["logid"] = $timestamp;
        $params["signature"] = $signature;

        $res_json = IO::send($URL, $params);
        $res = json_decode($res_json);

        return $res;

    }

    public static function Request(
            $DenominationCode = "", 
            $Quantity = 0, 
            $ReferenceNo = "", 
            $Remark = "", 
            $Expired_At = ""
    ) {
        $path = Vouchers::$_API_PATH_VOUCHER_REQUEST;

        if($DenominationCode == ""){
            return "ERROR: DenominationId is required!";
        }

        if(intval($Quantity) == 0){
            return "ERROR: Quantity should be more than zero!";
        }

        if($ReferenceNo == ""){
            return "ERROR: Unique Reference No. is required!";
        }

        $URL = API::GetUrl() . $path;

        $signature = Helpers::GetSignature($DenominationCode . $Quantity . $ReferenceNo);

        $params = [];
        $params["partner_guid"] = Client::GetPartnerID();
        $params["denomination_code"] = $DenominationCode;
        $params["quantity"] = $Quantity;
        $params["reference_no"] = $ReferenceNo;
        if( $Remark != "") $params["remark"] = $Remark;
        if( $Expired_At != "") $params["expired_at"] = $Expired_At;
        $params["signature"] = $signature;

        $res_json = IO::send($URL, $params);
        $res = json_decode($res_json);

        return $res;

    }

    public static function Inquiry( $ReferenceNo = "") {

        $path = Vouchers::$_API_PATH_VOUCHER_INQUIRY;

        if($ReferenceNo == ""){
            return "ERROR: Unique Reference No. is required!";
        }

        $URL = API::GetUrl() . $path;

        $signature = Helpers::GetSignature($ReferenceNo);

        $params = [];
        $params["partner_guid"] = Client::GetPartnerID();
        $params["reference_no"] = $ReferenceNo;
        $params["signature"] = $signature;

        $res_json = IO::send($URL, $params);
        $res = json_decode($res_json);

        return $res;

    }

    public static function Balance() {

        $path = Vouchers::$_API_PATH_VOUCHER_BALANCE;

        $URL = API::GetUrl() . $path;

        $signature = Helpers::GetSignature("");

        $params = [];
        $params["partner_guid"] = Client::GetPartnerID();
        $params["signature"] = $signature;

        $res_json = IO::send($URL, $params);
        $res = json_decode($res_json);

        return $res;

    }

}

class UniBox {

    // ----- API Sub Paths
    private static $_API_PATH_REQUEST = "request";
    private static $_API_PATH_INQUIRY = "inquiry";

    public static function Launch( $ReferenceId, $URL_CallBack, $Currency, $Denominations, $URL_Return = "", $PaymentChannel = "", $Remarks = "", $IsMobileGame = "0") {

        $path = UniBox::$_API_PATH_REQUEST;

        if($ReferenceId == ""){
            return "ERROR: ReferenceId is required!";
        }

        if($URL_CallBack == ""){
            return "ERROR: URL_CallBack is required!";
        }

        $Currency = strtoupper($Currency);
        if($Currency == ""){
            return "ERROR: Currency is required!";
        }
        elseif(!preg_match("/^([A-Z][A-Z][A-Z])$/", $Currency)){
            return "ERROR: Invalid Currency!";
        }

        if(!preg_match("/^([0-1])$/", $IsMobileGame)){
            $IsMobileGame = "0";
        }

        $URL = API::GetUrlUniBox() . $path;

        $params = [];
        $params["guid"] = Client::GetPartnerID();
        $params["reference"] = $ReferenceId;
        $params["urlAck"] = $URL_CallBack;

        if($URL_Return != "") $params["urlReturn"] = $URL_Return;
        if(!empty($Denominations)) $params["denominations"] = $Denominations;
        if($PaymentChannel != ""){
            $params["channel"] = $PaymentChannel;
        }
        else {
            $params["channel"] = null;
        }
        $params["currency"] = $Currency;
        if($Remarks != "") $params["remark"] = $Remarks;
        $params["is_mobile_game"] = $IsMobileGame;

        $denominations_sig = "";
        if(!empty($Denominations)){
            foreach($Denominations as $d){
                foreach($d as $k => $v){
                    $denominations_sig .= $v;
                }
            }
        }

        $signature = Helpers::GetSignature($ReferenceId . $URL_CallBack . $Currency . $denominations_sig);
        $params["signature"] = $signature;

        $headers = [];

        $res_json = IO::send($URL, $params, $headers);
        $res = json_decode($res_json);

        return $res;

    }

    public static function Inquiry( $ReferenceId) {

        $path = UniBox::$_API_PATH_INQUIRY;

        if($ReferenceId == ""){
            return "ERROR: ReferenceId is required!";
        }

        $URL = API::GetUrlUniBox() . $path;

        $params = [];
        $params["guid"] = Client::GetPartnerID();
        $params["reference"] = $ReferenceId;

        $signature = Helpers::GetSignature($ReferenceId);
        $params["signature"] = $signature;

        $headers = [];

        $res_json = IO::send($URL, $params, $headers);
        $res = json_decode($res_json);

        return $res;

    }

    public static function CallBack(){

        $_incoming_data = [
            "status" => -1,
            "message" => "NO_INCOMING_DATA",
            "data" => []
        ];

        $P = $_POST;

        if(isset($P["guid"]) && isset($P["name"]) && isset($P["url"]) && isset($P["status"]) && isset($P["amount"]) && isset($P["currency"]) && isset($P["message"]) && isset($P["trxNo"]) && isset($P["time"]) && isset($P["reference"]) && isset($P["item"]) && isset($P["signature_v2"])){
            
            $_guid = $P["guid"];
            $_name = $P["name"];
            $_url = $P["url"];
            $_status = $P["status"];
            $_amount = $P["amount"];
            $_currency = $P["currency"];
            $_message = $P["message"];
            $_trxNo = $P["trxNo"];
            $_time = $P["time"];
            $_reference = $P["reference"];
            $_item = $P["item"];
            $_signature_v2 = $P["signature_v2"];

            //$_signature_calculated = $_guid & $_name & $_url & $_status & $_amount & 

        }
        else {

            $_incoming_data = [
                "status" => -1,
                "message" => "NOT_ALL_VARS_FOUND",
                "data" => []
            ];

        }

        return $_incoming_data;

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

class Helpers {

    public static function GetHeaders($Link) {

        $_PartnerID = Client::GetPartnerID();
        $_SecretKey = Client::GetSecretKey();
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



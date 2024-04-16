<?php

namespace App\GatewayTraits;

trait GatewayTraits
{
    private $apostData;
    private $response;
    private $requestparams;
    private $x_priority;
    private $amount;
    private $upi_id;
    private $seq_no;
    private $txntype;
    private $ifsc;
    private $beni_acc_name;
    private $beni_acc_no;
    private $tranRefNo;
    private $mobile = "7988000014"; //TEST
    private $device_id = "400438400438400438400438"; //TEST
    private $payer_va = "uattesting0014@icici"; //TEST
    private $payee_name = "StagingMID"; //TEST
    private $mcc = "6011"; //TEST
    private $profile_id = "2996304"; //TEST
    //LIVE SERVER DATA
    private static $LIVE_MOBILE = "6930137861";
    private static $LIVE_DEVICE_ID = "6752392675239267523926752392";
    private static $LIVE_PAYER_VA = "prestoplastindia@icici";
    private static $LIVE_PAYEE_NAME = "PRESTO PLAST INDIA";
    private static $LIVE_MCC = "5072";
    private static $LIVE_PROFILE_ID = "247804842";
    //LIVE DATA END
    private static $TEST_PAYMENT_URL = 'https://apibankingonesandbox.icicibank.com/api/v1/composite-payment';
    private static $LIVE_PAYMENT_URL = 'https://apibankingone.icicibank.com/api/v1/composite-payment';
    private static $LIVE_API_KEY = 'eNGSCNZGusgdF1QK8XkNDeVDoG3A0RSA';
    private static $API_KEY = 'K2d3mSt2ryAGepE5TfbU1um7KbxP9fOj';
    private static $payment_url;
    private static $apikey;
    private static $use_mobile;
    private static $use_device_id;
    private static $use_payer_va;
    private static $use_payee_name;
    private static $use_mcc;
    private static $use_profile_id;

    public function __construct()
    {
        self::$payment_url = env('TYPE') == 'test' ? self::$TEST_PAYMENT_URL : self::$LIVE_PAYMENT_URL;
        self::$apikey = env('TYPE') == 'test' ? self::$API_KEY : self::$LIVE_API_KEY;
        self::$use_mobile = env('TYPE') == 'test' ? $this->mobile : self::$LIVE_MOBILE;
        self::$use_device_id = env('TYPE') == 'test' ? $this->device_id : self::$LIVE_DEVICE_ID;
        self::$use_payer_va = env('TYPE') == 'test' ? $this->payer_va : self::$LIVE_PAYER_VA;
        self::$use_payee_name = env('TYPE') == 'test' ? $this->payee_name : self::$LIVE_PAYEE_NAME;
        self::$use_mcc = env('TYPE') == 'test' ? $this->mcc : self::$LIVE_MCC;
        self::$use_profile_id = env('TYPE') == 'test' ? $this->profile_id : self::$LIVE_PROFILE_ID;

    }

    public function upi(array $array)
    {
        $this->x_priority = $array['x_priority'];
        $this->amount = $array['amount'];
        $this->upi_id = $array['payee_va'];
        $this->seq_no = $array['seq_no'];
        $reques_params = array(
            "mobile" => self::$use_mobile,
            "device-id" => self::$use_device_id,
            "account-provider" => "74",
            "payee-va" => $array['payee_va'],
            "payer-va" => self::$use_payer_va,
            "amount" => $array['amount'],
            "pre-approved" => "P",
            "use-default-acc" => "D",
            "default-debit" => "N",
            "default-credit" => "N",
            "payee-name" => self::$use_payee_name,
            "mcc" => self::$use_mcc,
            "merchant-type" => "ENTITY",
            "txn-type" => "merchantToPersonPay",
            "channel-code" => "MICICI",
            "remarks" => "GiftByPresto",
            "seq-no" => $array['seq_no'],
            "profile-id" => self::$use_profile_id
        );
        $this->requestparams = $reques_params;
        return $this->encription();
    }

    public function imps(array $array)
    {
        $this->x_priority = $array['x_priority'];
        $reques_params = array(
            "localTxnDtTime" => date('Ymdhmi'),
            "beneAccNo" => '123456041',
            "beneIFSC" => "NPCI0000001",
            "amount" => "100.00",
            "tranRefNo" => "Testing" . rand(1111111111, 9999999999),
            "paymentRef" => "FTTransferP2A",
            "senderName" => "Pratik Mundhe",
            "mobile" => "9999988888",
            "retailerCode" => "rcode",
            "passCode" => "447c4524c9074b8c97e3a3c40ca7458d",
            "bcID" => "IBCKer00055",
            "aggrId" => "MESCOMP0070",
            "crpId" => "EKoNO8zHaxX3Prf3QsNgHyGTFE88vghA",
            "crpUsr" => "PRESTO"
        );

        $this->requestparams = $reques_params;
        return $this->encription();
    }

    public function neft(array $array)
    {
        $this->x_priority = $array['x_priority'];
        $this->amount = $array['amount'];
        $this->beni_acc_no = $array['beni_acc_no'];
        $this->beni_acc_name = $array['beni_acc_name'];
        $this->ifsc = $array['ifsc'];
        $this->txntype = $array['txntype'];
        $this->tranRefNo =$array['tranRefNo'];

        $request_params = array(
            "tranRefNo" => $array['tranRefNo'],
            "amount" => $array['amount'],
            "senderAcctNo" => "000405002777",
            "beneAccNo" => $array['beni_acc_no'],
            "beneName" => $array['beni_acc_name'],
            "beneIFSC" => $array['ifsc'],
            "narration1" => "NEFT transaction",
            "narration2" => "PritamG",

            "crpId" => "EKoNO8zHaxX3Prf3QsNgHyGTFE88vghA",
            "crpUsr" => "PRESTO",
            "aggrId" => "MESCOMP0070",
            "aggrName" => "PRESTO",
            "urn" => "IBCKer00055",
            "txnType" => $array['txntype'],
            "WORKFLOW_REQD" => "N"
        );

        $this->requestparams = $request_params;
        return $this->encription();
    }


    public function encription()
    {

        $apostData = json_encode($this->requestparams);

        //print_r($apostData);
        $sessionKey = rand(1000000000000000, 9999999999999999);
        //print_r($sessionKey);

        //hash('MD5', time(), true); //16 byte session key
        $fp = fopen(__DIR__ . "/ICICIUATpubliccert.txt", "r");

        $pub_key_string = fread($fp, 8192);

        openssl_get_publickey($pub_key_string);

        openssl_public_encrypt($sessionKey, $encryptedKey, $pub_key_string); // RSA


        $iv = 1234567890123456; //str_repeat("\0", 16);

        $encryptedData = openssl_encrypt($apostData, 'aes-128-cbc', $sessionKey, OPENSSL_PKCS1_PADDING, $iv); // AES

        $request = [

            "requestId" => "req_" . time(),

            "encryptedKey" => base64_encode($encryptedKey),

            "iv" => base64_encode($iv),

            "encryptedData" => base64_encode($encryptedData),

            "oaepHashingAlgorithm" => "RSA/ECB/PKCS1",

            "service" => "",

            "clientInfo" => "",

            "optionalParam" => ""
        ];

        $apostData = json_encode($request);


        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => self::$payment_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $apostData,
            CURLOPT_HTTPHEADER => array(
                'apikey:' . self::$apikey,
                'x-priority:' . $this->x_priority,
                'Content-Type:application/json'
            ),
        ));

        $response = curl_exec($curl);
        $this->response = $response;
        return $this->decryption();
        curl_close($curl);

        //exit();

    }

    public function decryption()
    {
        $decode = json_decode($this->response);
        $encryptedKey = base64_decode($decode->encryptedKey);
        $encryptedData = base64_decode($decode->encryptedData);


        $fp = fopen(__DIR__ . "/privatekey.pem", "r");

        $priv_key = fread($fp, 8192);

        fclose($fp);

        //print_r($priv_key);
        $res = openssl_pkey_get_private($priv_key);

        openssl_private_decrypt($encryptedKey, $key, $res);

        $encData = openssl_decrypt($encryptedData, "aes-128-cbc", $key, OPENSSL_PKCS1_PADDING);

        $newsource = substr($encData, 16);
        return (json_decode($newsource, TRUE));
    }
}

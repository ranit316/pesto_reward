<?php

namespace App\GatewayTraits;

trait StatusTraits
{
    private $apostData;
    private $response;
    private $requestparams;
    private $x_priority;
    private $date;
    private $seq_no;
    private $ori_seq_no;


    public function upistatuscheck(array $array)
    {
        $this->x_priority = $array['x_priority'];
        $this->date = $array['date'];
        $this->seq_no = $array['seq_no'];
        $this->ori_seq_no = $array['ori_seq_no'];
        $reques_params = array(
            "date" => $array['date'],
            "recon360" => "N",
            "seq-no" => $array['seq_no'],
            "channel-code" => "MICICI",
            "ori-seq-no" => $array['ori_seq_no'],
            "mobile" => "7988000014",
            "profile-id" => "2996304",
            "device-id" => "400438400438400438400438"
        );
        $this->requestparams = $reques_params;
        return $this->encription1();
    }

    public function impsstatuscheck(array $array)
    {
        $this->x_priority = $array['x_priority'];
        $reques_params = array(
            "transRefNo" => "Testing1827324729",
            "date" => "16/01/2024",
            "recon360" => "N",
            "passCode" => "447c4524c9074b8c97e3a3c40ca7458d",
            "bcID" => "IBCKer00055",
        );
        $this->requestparams = $reques_params;
        return $this->encription1();
    }

    public function encription1()
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
            CURLOPT_URL => 'https://apibankingonesandbox.icicibank.com/api/v1/composite-status',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $apostData,
            CURLOPT_HTTPHEADER => array(
                'apikey:K2d3mSt2ryAGepE5TfbU1um7KbxP9fOj',
                'x-priority:' . $this->x_priority,
                'Content-Type:application/json'
            ),
        ));

        $response = curl_exec($curl);
        $this->response = $response;
        return $this->decryption1();

        curl_close($curl);
    }

    public function decryption1()
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


        // echo '<pre>';

        // print_r(json_decode($newsource, TRUE));

        // echo '</pre>';
    }
}

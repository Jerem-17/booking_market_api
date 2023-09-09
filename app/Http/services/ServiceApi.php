<?php

namespace App\Http\services;

use Illuminate\Support\Facades\Http;

class ServiceApi
{
    public $number;
    public $message;


    public function __construct($number, $message)
    {
        $this->number = $number;
        $this->message = $message;
    }


    function sendSms()
    {
       // $response =
        Http::withHeaders([
            'APIKEY' => 'kx3jAMVtWm852oArm8QlR3hBR2nI7580',
            'CLIENTID' => '3099',
            'Content-Type' => 'application/json'
        ])->post('https://edok-api.kingsmspro.org/api/v1/sms/send', [
            'from' => "ODON", //l'expediteur
            'to' => '' . $this->number . '', //destination au format international sans "+" ni "00". Ex: 22890443679
            'type' => 1, //type de message text et flash
            'message' => '' . $this->message . '', //le contenu de votre sms
            'dlr' => 1 // 1 pour un retour par contre 0
        ]);

        // if ($response->serverError()) {
        //     echo "an error occured";
        // }
        // if ($response->status() == 201) {
        //     echo "message sent";
        // }
    }

    function sendMessage()
    {
        $url = "https://edok-api.kingsmspro.org/api/v1/sms/send"; //url du serveur
        $apiKey = 'kx3jAMVtWm852oArm8QlR3hBR2nI7580'; //remplacez par votre api key
        $clientId = "3099"; //Remplacez par votre client Id
        $curl = curl_init();
        $smsData   = array(
            'from' => "ODON", //l'expediteur
            'to' => '' . $this->number . '', //destination au format international sans "+" ni "00". Ex: 22890443679
            'type' => 1, //type de message text et flash
            'message' => $this->message, //le contenu de votre sms
            'dlr' => 1 // 1 pour un retour par contre 0

        );

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("APIKEY: " . $apiKey, "CLIENTID:" . $clientId));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,   $smsData);
        curl_exec($ch);
        curl_close($ch);
    }
    function sendVerification()
    {
        $url = "https://edok-api.kingsmspro.org/api/v1/sms/send"; //url du serveur
        $apiKey         = 'kx3jAMVtWm852oArm8QlR3hBR2nI7580'; //remplacez par votre api key
        $clientId = "3099"; //Remplacez par votre client Id
        $curl = curl_init();
        $smsData   = array(
            'from' => "ODON", //l'expediteur
            'to' => '' . $this->number . '', //destination au format international sans "+" ni "00". Ex: 22890443679
            'type' => 1, //type de message text et flash
            'message' => $this->message, //le contenu de votre sms
            'dlr' => 1 // 1 pour un retour par contre 0

        );

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("APIKEY: " . $apiKey, "CLIENTID:" . $clientId));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,   $smsData);
        curl_exec($ch);
        curl_close($ch);
    }
}

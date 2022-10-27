<?php
namespace Desenlike\Sendtudophp;

class SendWhatsappMessage
{
    private $privateKey;
    private $publicKey;

    function __construct($privateKey, $publicKey) {
        $this->privateKey = $privateKey;
        $this->publicKey = $publicKey;
    }

    private function generateToken() {
        //Header Token
        $header = json_encode([
            'typ' => 'JWT',
            'alg' => 'HS256'
        ]);
        $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));

        //Payload - Content
        $payload = json_encode([
            'iat' => time(),
            'sub' => $this->publicKey,
            'exp' => time() + 200,
        ]);
        $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));

        //Sign
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $this->privateKey, true);
        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

        //Token
        $token = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;

        return $token;
    }

    private function send($to, $message = null, $templateId = null, $customParams = null)
    {
        $data["to"] = $to;
        if (is_null($templateId)) {
            $data["message"] = $message;
        } else {
            $data["templateId"] = $templateId;
            if (!is_null($customParams)) {
                $data["customParams"] = $customParams;
            }
        }

        $token = $this->generateToken();

        $header = [
            'Authorization: Bearer '.$token,
            'Content-Type: application/json'
        ];

        $curl = curl_init("https://api.sendtudo.com.br/messages");
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        
        $result = curl_exec($curl);
        $info = curl_getinfo($curl);
        $code = $info["http_code"];
        
        curl_close($curl);

        return $code === 200;
    }

    public function sendMessage($to, $message)
    {
        return $this->send($to, $message);
    }

    public function sendTemplateMessage($to, $templateId, $customParams)
    {
        return $this->send($to, null, $templateId, $customParams);
    }
}
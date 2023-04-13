<?php

namespace App\Classe;


use Mailjet\Client;
use Mailjet\Resources;

class Mail
{
    private $api_key = "eac7fae412744a754db343c5c39b4986";
    private $api_key_secret = '570b0ba3684bd96c3a46011f5cdce89e';


    public function send($to_email, $to_name, $subject, $content)
    {
        $mj = new Client($this->api_key, $this->api_key_secret, true, ['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "noreply@tsioryrakotoarimalala.com",
                        'Name' => "La Boutique francaise"
                    ],
                    'To' => [
                        [
                            'Email' => $to_email,
                            'Name' => $to_name
                        ]
                    ],
                    'Subject' => $subject,
                    'TextPart' => "Dear passenger 1, welcome to Mailjet! May the delivery force be with you!",
                    'HTMLPart' => $content
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success();
        // dd($response->getData());
    }
}

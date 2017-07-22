<?php

namespace App\Models;

class Mail
{
    public static function send($email, $subject, $html) {
        $url = 'http://api.sendcloud.net/apiv2/mail/send';
        $api_user = 'dmnqmn_test_NPnent';
        $api_key = 'hGcjXF8mvJ3ZFXqO';
        $param = [
            'apiUser' => $api_user,
            'apiKey' => $api_key,
            'from' => 'sendcloud@sendcloud.org',
            'fromName' => 'SendCloud',
            'to' => $email,
            'subject' => $subject,
            'html' => $html,
            'respEmailId' => 'true',
        ];
        $data = http_build_query($param);
        $options = [
            'http' => [
                'method' => 'POST',
                'header' => 'Content-Type: application/x-www-form-urlencoded',
                'content' => $data
            ]
        ];
        $context  = stream_context_create($options);
        $result = file_get_contents($url, FILE_TEXT, $context);
        return $result;
    }

}

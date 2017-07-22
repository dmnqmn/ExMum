<?php

namespace App\Models;

class SMS
{
    public static function send($templateId, $phone, $code) {
        $url = 'http://www.sendcloud.net/smsapi/send';
        $smsUser = '';
        $smskey = '';
        $vars = '{"%code%":"' . $code . '"}';
        $param = [
            'smsUser' => $smsUser,
            'templateId' => $templateId,
            'phone' => $phone,
            'vars' => $vars,
        ];
        $sParamStr = "";
        ksort($param);
        foreach ($param as $sKey => $sValue) {
            $sParamStr .= $sKey . '=' . $sValue . '&';
        }
        $sParamStr = trim($sParamStr, '&');
        $sSignature = md5($smskey . '&' . $sParamStr . '&' . $smskey);
        $param = [
            'smsUser' => $smsUser, 
            'templateId' => $templateId,
            'msgType' => '0',
            'phone' => $phone,
            'vars' => $vars,
            'signature' => $sSignature
        ];
        $data = http_build_query($param);
        $options = [
            'http' => [
                'method' => 'POST',
                'header' => 'Content-Type:application/x-www-form-urlencoded',
                'content' => $data
            ]
        ];
        $context  = stream_context_create($options);
        $result = file_get_contents($url, FILE_TEXT, $context);
        return $result;
    }

}

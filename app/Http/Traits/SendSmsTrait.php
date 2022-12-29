<?php 

namespace App\Http\Traits;

trait SendSmsTrait{

	public function sendSms($sms,$number)
	{
        info($sms);
        $api_key="C2001593632a9d8ed9db24.24710771";
        $sender_id="8809601003570";
        $contacts=$number;
        $type="application/json";
        $msg=$sms;
        $fields='api_key='.$api_key.'&type='.$type.'&contacts='.$contacts.'&senderid='.$sender_id.'&msg='.$msg;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,"https://isms.mimsms.com/smsapi");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$fields);
        // In real life you should use something like:
        // curl_setopt($ch, CURLOPT_POSTFIELDS, 
        //          http_build_query(array('postvar1' => 'value1')));
        // Receive server response ...
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec($ch);
        curl_close ($ch);
        // Further processing ...
        return $server_output;
	}

}
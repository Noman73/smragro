<?php 

namespace App\Http\Traits;

trait SendSmsTrait{

	public function sendSms($sms,$number)
	{
        info($sms);
        $api_key="HOB2tfILEQWzSumTbiG8Gozihzaf52Ok";
        $api_token="Sagc1672290805";
        $sender_id="SMR Agro";
        $contacts='88'.$number;
        $type="unicode";
        $msg=$sms;
        $fields='sendsms&apikey='.$api_key.'&apitoken='.$api_token.'&type='.$type.'&from='.$sender_id.'&to='.$contacts.'&text='.$msg;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,"https://app.mimsms.com/smsAPI");
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
        info($server_output);
        return $server_output;
	}

}
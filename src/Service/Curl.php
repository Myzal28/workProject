<?php


namespace App\Service;


class Curl
{
    public function getJson($url){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $exec = curl_exec($curl);

        if ($exec === false) {
            return curl_error($curl);
        }else{
            $data = json_decode($exec,true);
        }
        curl_close($curl);
        return $data;
    }
}
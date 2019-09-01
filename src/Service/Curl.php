<?php


namespace App\Service;


class Curl
{
    public function getJson($url){
        return json_decode($this->simpleCurl($url),true);
    }

    public function getFood($barCode){
        $url = "https://world.openfoodfacts.org/api/v0/product/" . $barCode . ".json";
        $data = $this->getJson($url);
        if ($data['status'] == 1){
            return $data['product'];
        }else{
            return false;
        }
    }

    public function simpleCurl($url){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $exec = curl_exec($curl);

        if ($exec === false) {
            return curl_error($curl);
        }else{
            curl_close($curl);
            return $exec;
        }
    }
}
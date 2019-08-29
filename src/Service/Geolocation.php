<?php


namespace App\Service;


use App\Entity\Warehouses;
use App\Service\Curl;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class Geolocation extends AbstractController
{
    public function closeEnough($address,$city,$zipcode,Curl $curl){
        // On formatte l'adresse de la personne pour pouvoir l'utiliser après dans l'API
        $formattedAddress = str_replace(" ","+",$address);
        $formattedAddress .= "+".str_replace(" ", "+",$city);
        $formattedAddress .= "+".str_replace(" ","+",$zipcode);

        // L'URL avec l'API Key google ressemblera à ça, on l'envoie ensuite à cURL
        $urlWithAddress = "https://maps.googleapis.com/maps/api/geocode/json?address=" . $formattedAddress . "&key=AIzaSyDE9fld3JmAgIk2oZdBeiIf3lFxPIkCTko";

        // On vient chercher les coordonnées de la personne en cURL sur l'API Google Maps
        $geoData = $curl->getJson($urlWithAddress);


        $closeEnough = false;
        if (isset($geoData['status'])){
            switch ($geoData['status']){
                case "OK":
                    $lat = $geoData['results'][0]['geometry']['location']['lat'];
                    $lng = $geoData['results'][0]['geometry']['location']['lng'];

                    $warehouses = $this->getDoctrine()->getRepository(Warehouses::class)->findAll();
                    foreach ($warehouses as $warehouse){
                        $urlTravelTime = "https://maps.googleapis.com/maps/api/distancematrix/json?&origins=".$lat.",".$lng."&destinations=".$warehouse->getLatitude().",".$warehouse->getLongitude()."&key=AIzaSyDE9fld3JmAgIk2oZdBeiIf3lFxPIkCTko";
                        $travelTime = $curl->getJson($urlTravelTime);
                        $total[] = $travelTime;
                        if ($travelTime['rows'][0]['elements'][0]['distance']['value'] < 3600){
                            // si a moins d'une heure de route d'un de nos entrepôts alors on l'accepte
                            $closeEnough = true;
                        }
                    }
                    break;
                default:
                    $closeEnough = false;
                    break;
            }
        }
        return $closeEnough;
    }
}
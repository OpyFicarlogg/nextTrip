<?php

namespace App\Service;

use Symfony\Component\Config\Definition\Exception\Exception;
use App\Dao\OpenWeathermap;

class NextTripService
{


    const SCORE_TEMP = 20;
    const SCORE_HUMIDITY = 15;
    const SCORE_CLOUD = 10;


    const CLOUD = "clouds";
    const HUMIDITY = "humidity";
    const TEMP = "temp";
    const SCORE = "score";
    const NAME = "name";
    

    private $openWeathermapDao;

    
    public function __construct()
    {
        $this->openWeathermapDao =  new OpenWeathermap();
    }



    //TODO: Pouvoir comparer plus d'une ville, et retour ordonné par score
    public function getNextTrip($town1,$town2): Array {

        try{
            return $this->getScore($town1,$town2);
        }
        catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }


    /**
     * Get town datas and return array
    *
    * @param  String $town
    * @return Array
    */
    private function getTownData(String $town): Array{

        //get lat and lon for the getweather
        $getLatLon = $this->openWeathermapDao->getLatLon($town);

        if(!empty($getLatLon)){
            //get weather for the next 7 days 
            $getData = $this->openWeathermapDao->getWeather( $getLatLon[0]['lat'], $getLatLon[0]['lon']);

            return $getData;
        }
        else{
            throw new Exception("Error getTownData for ".$town);
        }
        
        

        
    }


    //Get avg values for the week
    //TODO: ajouter le top 2 des meilleurs et plus mauvais jours (score par j)
    private function getAvgWeek(String $town): Array{
        

        try{
            $datas = $this->getTownData($town);
        }
        catch(Exception $e){
            throw new Exception($e->getMessage());
        }
        

        $days = count($datas['daily']);
        $temp = 0;
        $cloud = 0;
        $humidity = 0;


        foreach($datas['daily'] as $day){

            $temp += $day[NextTripService::TEMP]['max'];
            $cloud += $day[NextTripService::CLOUD];
            $humidity += $day[NextTripService::HUMIDITY];
        }

        return array(
                NextTripService::NAME  => $town,
                NextTripService::CLOUD =>number_format($cloud / $days), 
                NextTripService::HUMIDITY => number_format($humidity / $days), 
                NextTripService::TEMP => number_format($temp/$days),
                "startDt" => $datas['daily'][0]['dt'],
                "endDt" => $datas['daily'][$days-1]['dt'],
                NextTripService::SCORE => 0
            );
    }

    //Get score for both town 
    //TODO: Faire un score par jour, avec total
    private function getScore($town1, $town2): Array{

        try{
            $avgTown1 = $this->getAvgWeek($town1);

            $avgTown2 = $this->getAvgWeek($town2);
        }
        catch(Exception $e){
            throw new Exception($e->getMessage());
        }
        

        //getClosest Temp
        $closest = $this->getClosest($avgTown1[NextTripService::TEMP],$avgTown2[NextTripService::TEMP],27);

        if($avgTown1[NextTripService::TEMP] == $closest){
            $avgTown1[NextTripService::SCORE] = $avgTown1[NextTripService::SCORE]+NextTripService::SCORE_TEMP;
            
        }
        elseif($avgTown2[NextTripService::TEMP] == $closest){
            $avgTown2[NextTripService::SCORE] = $avgTown2[NextTripService::SCORE]+NextTripService::SCORE_TEMP;
        }


        //getClosest humidity
        $closest = $this->getClosest($avgTown1[NextTripService::HUMIDITY],$avgTown2[NextTripService::HUMIDITY],60);

        if($avgTown1[NextTripService::HUMIDITY] == $closest){
            $avgTown1[NextTripService::SCORE] = $avgTown1[NextTripService::SCORE]+NextTripService::SCORE_HUMIDITY;
            
        }
        elseif($avgTown2[NextTripService::HUMIDITY] == $closest){
            $avgTown2[NextTripService::SCORE] = $avgTown2[NextTripService::SCORE]+NextTripService::SCORE_HUMIDITY;
        }
        


        //getClosest cloud
        $closest = $this->getClosest($avgTown1[NextTripService::CLOUD],$avgTown2[NextTripService::CLOUD],15);

        if($avgTown1[NextTripService::CLOUD] == $closest){
            $avgTown1[NextTripService::SCORE] = $avgTown1[NextTripService::SCORE]+NextTripService::SCORE_CLOUD;
            
        }
        elseif($avgTown2[NextTripService::CLOUD] == $closest){
            $avgTown2[NextTripService::SCORE] = $avgTown2[NextTripService::SCORE]+NextTripService::SCORE_CLOUD;
        }



        //Create one array with both town
        /*$result = array();
        array_push($result,$avgTown1);
        array_push($result,$avgTown2);*/

        return $avgTown1[NextTripService::SCORE] > $avgTown2[NextTripService::SCORE] ? $avgTown1 : $avgTown2;
    }


    // get the closest value from ref between 2 values 
    //TODO: mettre un array directement en input? 
    private function getClosest($value1,$value2,$ref) : int{
        
        $first = $ref - $value1;
        $second = $ref - $value2;

        $list = array($first,$second);
        $closest = 0;
    
        foreach($list as $value){
            if ($closest === 0) {
                $closest = $value;
            } else if ($value> 0 && $value <= abs($closest)) {
                $closest = $value;
            } else if ($value < 0 && -$value< abs($closest)) {
                $closest = $value;
            }
        }

        return -($closest-$ref);


    }


        
}

<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class FilterController extends BaseController
{
    //
    public function index()
    {
        /**
     * @OA\Get(
     *     path="/filter",
     *     operationId="filter",
     *     tags={"Filtering Denom"},
     *      summary="Filtering Denom",
     *      description="Filtering Denom",
     *     @OA\Response(
     *         response="200",
     *         description="Returns filtered data",
     *       
     *     )
     * )
     */
       $jsonobj =  $this->http_request("https://gist.githubusercontent.com/Loetfi/fe38a350deeebeb6a92526f6762bd719/raw/9899cf13cc58adac0a65de91642f87c63979960d/filter-data.json");
       $jsonobj =  json_decode($jsonobj) ; 
       
       $new_array = array();
       foreach($jsonobj->data->response->billdetails as $x => $val) {
        $arr = explode(':', $val->body[0]);
        $denomValue = (int)$arr[1];
        if($denomValue >= 100000)
        {
            $new_array[] = $denomValue;
        }
      }
      
       var_dump($new_array);  
    }
    function http_request($url){
        // persiapkan curl
        $ch = curl_init(); 
    
        // set url 
        curl_setopt($ch, CURLOPT_URL, $url);
        
        // set user agent    
        curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
    
        // return the transfer as a string 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
    
        // $output contains the output string 
        $output = curl_exec($ch); 
    
        // tutup curl 
        curl_close($ch);      
    
        // mengembalikan hasil curl
        return $output;
    }
}

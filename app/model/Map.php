<?php

namespace app\model;

use app\component\Model;

class Map extends Model{
    public $apiMapUrl = 'https://maps.googleapis.com/maps/api/staticmap';

    public $apiPlaceUrl = 'https://maps.googleapis.com/maps/api/place/autocomplete/json';

    public $defaultParams = [
        'markers' => 'Minsk',
        'size'   => '450x270',
        'scale'  => '2',
    ];

    private $apiKey = 'AIzaSyB5vfKML7nnc3QwO3hYj6HxmZ_2xC-sioU';

    public function getUrl($search = ''){
        if(!empty($search)){
            $this->defaultParams['markers'] = $search;
        }

        $url = '';
        foreach($this->defaultParams as $key => $value){
            $url .= $key . '=' . $value . '&';
        }
        return $this->apiMapUrl . '?' . $url . 'key=' . $this->apiKey;
    }

    public function cleanParams($data){
        if(is_array($data)){
            foreach($data as $key => $value){
                $data[$this->cleanParams($key)] = $this->cleanParams($value);
            }
        } else{
            $data = strip_tags(htmlspecialchars(trim($data)));
        }
        return $data;
    }

    public function saveRequest(string $request){
        $params = [
            'request' => $request,
            'timecreated' => time()
        ];
        $this->db->query('INSERT INTO map_requests (place, timecreated) VALUES(:request, :timecreated)', $params);
    }

    public function getRequests(){
        $requests = $this->db->row('SELECT id, place as request, timecreated FROM map_requests');
        foreach($requests as $key => $request){
            $requests[$key]['timecreated'] = date("F j, Y, g:i a", $request['timecreated']);
        }
        return $requests;
    }

    public function getAutocompletePlaces($query){
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $this->apiPlaceUrl . '?input=' . $query . '&key=' . $this->apiKey,
            CURLOPT_RETURNTRANSFER => true,
        ]);
        $places = curl_exec($curl);
        curl_close($curl);

        $places = json_decode($places);
        if(empty($places) || $places->status !== 'OK'){
            return '';
        }

        $response = array();
        foreach($places->predictions as $value){
            $response[] = $value->description;
        }

        return json_encode($response);
    }
}
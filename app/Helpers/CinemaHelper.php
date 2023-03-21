<?php

namespace App\Helpers;

class CinemaHelper 
{
    /* 
    *   api url
    */
    private $api_url = 'https://www.smcinema.com/WSVistaWebClient/OData.svc/GetNowShowingScheduledFilms?%24format=json&%24expand=Sessions%2CCast&includeAdvanceSales=true&cinemaId=';
    
    /* 
    *   api key
    */
    private $api_key = 'aa388c22936942d79ee4997c8064c657';

    /* 
    *   cinema id
    */
    public $cinema_id;

    /* 	gen_salt
	*	params
	*	return random string
    */
    public function __construct($cinema_id) {
        $this->cinema_id = $cinema_id;
    }

    /* getSchedules
	*  return $schedules
    */
    public function getSchedules() {
        $request_headers = array(
            "Accept: application/json",
            "connectapitoken:" . $this->api_key
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->api_url.$this->cinema_id);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        $movies = json_decode(curl_exec($ch));

        if (curl_errno($ch)) {
            print "Error: " . curl_error($ch);
            exit();
        }
        curl_close($ch);

        if(count($movies->value)) {
            foreach ($movies->value as $movie) {
                $url = 'https://www.smcinema.com/CDN/media/entity/get/FilmPosterGraphic/h-'.$movie->ScheduledFilmId.'?width=198&amp;height=247';
                $img = public_path().'/uploads/media/cinema/'.$movie->ScheduledFilmId.'.jpg';
                file_put_contents($img, file_get_contents($url));
            }
        }

        return $movies;
    }
}
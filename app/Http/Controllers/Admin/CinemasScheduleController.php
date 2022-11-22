<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Interfaces\CinemasControllerInterface;
use Illuminate\Http\Request;
use App\Helpers\CinemaHelper;

use App\Models\CinemaSite;
use App\Models\CinemaGenre;
use App\Models\CinemaSchedule;
use App\Models\ViewModels\AdminViewModel;
use App\Models\ViewModels\CinemaScheduleViewModel;

class CinemasScheduleController extends AppBaseController implements CinemasControllerInterface
{
    /****************************************
    * 			CINEMAS MANAGEMENT		    *
    ****************************************/
    public function __construct()
    {
        $this->module_id = 41; 
        $this->module_name = 'Schedules';
    }

    public function index()
    {
        return view('admin.cinema_schedules');
    }

    public function list(Request $request)
    {
        try
        {
            $this->permissions = AdminViewModel::find(Auth::user()->id)->getPermissions()->where('modules.id', $this->module_id)->first();

            $movies = CinemaScheduleViewModel::when(request('search'), function($query){
                return $query->where('name', 'LIKE', '%' . request('search') . '%');
            })
            ->latest()
            ->paginate(request('perPage'));
            return $this->responsePaginate($movies, 'Successfully Retreived!', 200);
        }
        catch (\Exception $e)
        {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function store(Request $request)
    {
        try
    	{
            $cinema_site = CinemaSite::find($request->site_id);
            $cinema_id = str_pad($cinema_site->cinema_id, 10, '0', STR_PAD_LEFT);

            $cinema_helper = new CinemaHelper($cinema_id);
            $movies = $cinema_helper->getSchedules();

            // DELETE SCHEDULE PER SITE
            CinemaSchedule::where('site_id', $cinema_site->site_id)->delete();

            if(count($movies->value)) {
                foreach ($movies->value as $movie) {
                    $casting = $this->getCast($movie->Cast);
                    $this->saveSchedule($movie, $movie->Sessions, $casting, $cinema_site->site_id);
                }
            }

            return $this->response(true, 'Successfully Retreived!', 200);            
        }
        catch (\Exception $e) 
        {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function getCast($casts)
    {
        $mcasts = '';
        if(count($casts)) {
            foreach ($casts as $cast) {
                $mcasts = $mcasts .  $cast->FirstName ." ". $cast->LastName .  ", ";		
            }    
        }
        return $mcasts;
    }

    public function saveSchedule($movie, $sessions, $casting, $site_id)
    {
        foreach ($sessions as $time_slot) {
            $data = [
                'site_id' => $site_id,
                'title'=> $movie->Title,
                'synopsis' => addslashes($movie->Synopsis),
                'opening_date' => $movie->OpeningDate,
                'rating' => $movie->Rating,
                'rating_description'=> $movie->RatingDescription,
                'runtime'=> $movie->RunTime,
                'casting'=> $casting, 
                'trailer_url' => addslashes($movie->TrailerUrl),
                'cinema_id_code' => $movie->CinemaId,
                'screen_code'=> $time_slot->ScreenNameAlt,
                'screen_name'=> addslashes($time_slot->ScreenName),
                'film_id' => $movie->ScheduledFilmId,
                'genre'=> $movie->GenreId,
                'genre2'=> $movie->GenreId2,
                'genre3'=> $movie->GenreId3,
                'show_time' => $time_slot->Showtime
            ];

            CinemaSchedule::create($data);

        }
    }

    public function getSiteCodes()
    {
        try
        {
            $cinema_sites = CinemaSite::get();
            return $this->response($cinema_sites, 'Successfully Retreived!', 200);
        }
        catch (\Exception $e)
        {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }
}

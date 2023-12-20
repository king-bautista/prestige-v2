<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Interfaces\CinemasControllerInterface;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Helpers\CinemaHelper;
use App\Exports\Export;
use Storage;

use App\Models\CinemaSite;
use App\Models\CinemaGenre;
use App\Models\CinemaSchedule;
use App\Models\AdminViewModels\CinemaScheduleViewModel;

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
            $cinema_sites = CinemaSchedule::get();
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

    public function downloadCsv()
    {
        try {

            $cinema_schedule_management = CinemaScheduleViewModel::get();
            $reports = [];
            foreach ($cinema_schedule_management as $schedule) {
                $reports[] = [  
                    'id' => $schedule->id,
                    'site_id' => $schedule->site_id,
                    'site_name' => $schedule->site_name,
                    'title' => $schedule->tile,
                    'synopsis' => $schedule->synopsis,
                    'opening_date' => $schedule->opening_date,
                    'rating' => $schedule->rating,
                    'rating_description' => $schedule->rating_description,
                    'runtime' => $schedule->runtime,
                    'casting' => $schedule->casting,
                    'trailer_url' => $schedule->trailer_url,
                    'cinema_id' => $schedule->cinema_id,
                    'cinema_id_code' => $schedule->cinema_id_code,
                    'screen_code' => $schedule->screen_code,
                    'screen_name' => $schedule->screen_name,
                    'film_id' => $schedule->film_id,
                    'genre' => $schedule->genre,
                    'genre2' => $schedule->genre2,
                    'genre3' => $schedule->genre3,
                    'genre_name' => $schedule->genre_name,
                    'show_time' => $schedule->show_time,
                    'created_at' => $schedule->created_at,
                    'updated_at' => $schedule->updated_at,
                ];
            }

            $directory = 'public/export/reports/';
            $files = Storage::files($directory);
            foreach ($files as $file) {
                Storage::delete($file);
            }

            $filename = "cinema-schedule-management.csv";
            // Store on default disk
            Excel::store(new Export($reports), $directory . $filename);

            $data = [
                'filepath' => '/storage/export/reports/' . $filename,
                'filename' => $filename
            ];

            if (Storage::exists($directory . $filename))
                return $this->response($data, 'Successfully Retreived!', 200);

            return $this->response(false, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }
}

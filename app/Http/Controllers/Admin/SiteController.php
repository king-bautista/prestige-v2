<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Interfaces\SiteControllerInterface;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Http\Requests\SiteRequest;
use Illuminate\Support\Str;
use App\Imports\SitesImport;
use App\Exports\Export;
use Storage;
use URL;

use App\Models\Site;
use App\Models\Company;
use App\Models\AdminViewModels\SiteViewModel;

class SiteController extends AppBaseController implements SiteControllerInterface
{
    /************************************
     * 			SITES MANAGEMENT	 	*
     ************************************/
    public function __construct()
    {
        $this->module_id = 13;
        $this->module_name = 'Sites Management';
    }

    public function index()
    {
        return view('admin.sites');
    }

    public function list(Request $request)
    {
        try {
            $sites = SiteViewModel::when(request('search'), function ($query) {
                return $query->where('sites.name', 'LIKE', '%' . request('search') . '%')
                    ->orWhere('sites.descriptions', 'LIKE', '%' . request('search') . '%')
                    ->orWhereRaw('CONCAT(`smsc`.`meta_value`) LIKE \'%' . request('search') . '%\'')
                    ->orWhereRaw('CONCAT(`smm`.`meta_value`) LIKE \'%' . request('search') . '%\'');
                    
            })
                ->leftJoin('sites_meta as smsc', function ($join) {
                    $join->on('sites.id', '=', 'smsc.site_id')
                        ->where('smsc.meta_key', '=', 'site_code');
                })
                ->leftJoin('sites_meta as smm', function ($join) {
                    $join->on('sites.id', '=', 'smm.site_id')
                        ->where('smm.meta_key', '=', 'multilanguage');
                })
                ->select('sites.*', 'smsc.meta_value as short_code', 'smm.meta_value as multilanguage')
                ->when(is_null(request('order')), function ($query) {
                    return $query->orderBy('sites.name', 'ASC');
                })
                ->when(request('order'), function ($query) {
                    $column = $this->checkcolumn(request('order'));
                    switch ($column) {
                        case 'short_code':
                            $field = 'short_code';
                            break;
                            case 'multilanguage':
                                $field = 'multilanguage';
                                break;    
                        default:
                            $field = $column;
                    }
                    return $query->orderBy($column, request('sort'));
                })
                
                ->paginate(request('perPage'));

            return $this->responsePaginate($sites, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function details($id)
    {
        try {
            $site = SiteViewModel::find($id);
            return $this->response($site, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function store(SiteRequest $request)
    {
        try {
            $site_logo = $request->file('site_logo');
            $site_logo_path = '';
            if ($site_logo) {
                $originalname = $site_logo->getClientOriginalName();
                $site_logo_path = $site_logo->move('uploads/media/sites/logos/', str_replace(' ', '-', $originalname));
            }

            $site_banner = $request->file('site_banner');
            $site_banner_path = '';
            if ($site_banner) {
                $originalname = $site_banner->getClientOriginalName();
                $site_banner_path = $site_banner->move('uploads/media/sites/banners/', str_replace(' ', '-', $originalname));
            }

            $site_background = $request->file('site_background');
            $site_background_path = '';
            if ($site_background) {
                $originalname = $site_background->getClientOriginalName();
                $site_background_path = $site_background->move('uploads/media/sites/background/', str_replace(' ', '-', $originalname));
            }

            $site_background_portrait = $request->file('site_background_portrait');
            $site_background_portrait_path = '';
            if ($site_background_portrait) {
                $originalname = $site_background_portrait->getClientOriginalName();
                $site_background_portrait_path = $site_background_portrait->move('uploads/media/sites/background/', str_replace(' ', '-', $originalname));
            }

            if ($request->is_default === 'true') {
                Site::where('is_default', 1)->update(['is_default' => 0]);
            }

            $data = [
                'name' => $request->name,
                'descriptions' => ($request->descriptions) ? $request->descriptions : null,
                'site_logo' => ($site_logo_path) ? str_replace('\\', '/', $site_logo_path) : null,
                'site_banner' => ($site_banner_path) ? str_replace('\\', '/', $site_banner_path) : null,
                'site_background' => ($site_background_path) ? str_replace('\\', '/', $site_background_path) : null,
                'site_background_portrait' => ($site_background_portrait_path) ? str_replace('\\', '/', $site_background_portrait_path) : null,
                'active' => $this->checkBolean($request->active),
                'is_default' => $this->checkBolean($request->is_default),
            ];

            $meta_value = [
                'company_id' => ($request->company_id) ? $request->company_id : null,
                'facebook' => ($request->facebook) ? $request->facebook : null,
                'instagram' => ($request->instagram) ? $request->instagram : null,
                'twitter' => ($request->twitter) ? $request->twitter : null,
                'tiktok' => ($request->tiktok) ? $request->tiktok : null,
                'youtube' => ($request->youtube) ? $request->youtube : null,
                'viber' => ($request->viber) ? $request->viber : null,
                'website' => ($request->website) ? $request->website : null,
                'premiere' => $this->checkBolean($request->is_premiere),
                'multilanguage' => $this->checkBolean($request->multilanguage),
                'site_code' => $request->site_code,
                'map_type' => $request->map_type,
                'schedules' => ($request->operational_hours) ? $request->operational_hours : null,
                'site_theme' => $request->site_theme,
            ];

            $site = Site::create($data);
            $site->serial_number = 'ST-' . Str::padLeft($site->id, 5, '0');
            $site->save();
            $site->saveMeta($meta_value);

            return $this->response($site, 'Successfully Created!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function update(SiteRequest $request)
    {
        if ($request->is_default == 'true') {
            Site::where('is_default', 1)->update(['is_default' => 0]);
        }
        try {
            $site = Site::find($request->id);
            $site->touch();

            $site_logo = $request->file('site_logo');
            $site_logo_path = '';
            if ($site_logo) {
                $originalname = $site_logo->getClientOriginalName();
                $site_logo_path = $site_logo->move('uploads/media/sites/logos', str_replace(' ', '-', $originalname));
            }

            $site_banner = $request->file('site_banner');
            $site_banner_path = '';
            if ($site_banner) {
                $originalname = $site_banner->getClientOriginalName();
                $site_banner_path = $site_banner->move('uploads/media/sites/banners/', str_replace(' ', '-', $originalname));
            }

            $site_background = $request->file('site_background');
            $site_background_path = '';
            if ($site_background) {
                $originalname = $site_background->getClientOriginalName();
                $site_background_path = $site_background->move('uploads/media/sites/background/', str_replace(' ', '-', $originalname));
            }

            $site_background_portrait = $request->file('site_background_portrait');
            $site_background_portrait_path = '';
            if ($site_background_portrait) {
                $originalname = $site_background_portrait->getClientOriginalName();
                $site_background_portrait_path = $site_background_portrait->move('uploads/media/sites/background/', str_replace(' ', '-', $originalname));
            }

            $data = [
                'serial_number' => ($site->serial_number) ? $site->serial_number : 'ST-' . Str::padLeft($site->id, 5, '0'),
                'name' => $request->name,
                'descriptions' => ($request->descriptions) ? $request->descriptions : null,
                'site_logo' => ($site_logo_path) ? str_replace('\\', '/', $site_logo_path) : $site->site_logo,
                'site_banner' => ($site_banner_path) ? str_replace('\\', '/', $site_banner_path) : $site->site_banner,
                'site_background' => ($site_background_path) ? str_replace('\\', '/', $site_background_path) : $site->site_background,
                'site_background_portrait' => ($site_background_portrait_path) ? str_replace('\\', '/', $site_background_portrait_path) : $site->site_background_portrait,
                'active' => $this->checkBolean($request->active),
                'is_default' => $this->checkBolean($request->is_default),
            ];

            $meta_value = [
                'site_code' => $request->site_code,
                'map_type' => $request->map_type,
                'company_id' => ($request->company_id) ? $request->company_id : null,
                'facebook' => ($request->facebook) ? $request->facebook : null,
                'instagram' => ($request->instagram) ? $request->instagram : null,
                'twitter' => ($request->twitter) ? $request->twitter : null,
                'tiktok' => ($request->tiktok) ? $request->tiktok : null,
                'youtube' => ($request->youtube) ? $request->youtube : null,
                'viber' => ($request->viber) ? $request->viber : null,
                'website' => ($request->website) ? $request->website : null,
                'premiere' => $this->checkBolean($request->is_premiere),
                'multilanguage' => $this->checkBolean($request->multilanguage),
                'schedules' => ($request->operational_hours) ? $request->operational_hours : null,
                'site_theme' => $request->site_theme,
            ];

            $site->update($data);
            $site->saveMeta($meta_value);

            return $this->response($site, 'Successfully Modified!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function delete($id)
    {
        try {
            $site = Site::find($id);
            $site->delete();
            return $this->response($site, 'Successfully Deleted!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function getAll()
    {
        try {
            $sites = SiteViewModel::orderBy('name')->get();
            return $this->response($sites, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function setDefault($id)
    {
        try {
            Site::where('is_default', 1)->update(['is_default' => 0]);
            $site = Site::find($id);
            $site->update(['is_default' => 1]);
            return $this->response($site, 'Successfully Modified!', 200);
        } catch (\Exception $e) {
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

            $sites_management = SiteViewModel::get();
            $reports = [];
            foreach ($sites_management as $site) {
                $reports[] = [
                    'id' => $site->id,
                    'serial_number' => $site->serial_number,
                    'name' => $site->name,
                    'descriptions' => $site->descriptions,
                    'site_logo' => ($site->site_logo != "") ? URL::to("/" . $site->site_logo) : " ",
                    'site_banner' => ($site->site_banner != "") ? URL::to("/" . $site->site_banner) : " ",
                    'site_background' => ($site->site_background != "") ? URL::to("/" . $site->site_background) : " ",
                    'site_background_portrait' => ($site->site_background_portrait != "") ? URL::to("/" . $site->site_background_portrait) : " ",
                    'company_id' => $site->details['company_id'],
                    'company_name' => ($site->details['company_id']) ? Company::find($site->details['company_id'])->name : '',
                    'multilanguage' => $site->details['multilanguage'],
                    'facebook' => $site->details['facebook'],
                    'instagram' => $site->details['instagram'],
                    'twitter' => $site->details['twitter'],
                    'tiktok' => $site->details['tiktok'],
                    'youtube' => $site->details['youtube'],
                    'viber' => $site->details['viber'],
                    'website' => $site->details['website'],
                    'schedules' => $site->details['schedules'],
                    //'operational_hours' => $this->getOperationalHour($site->details),
                    //'time_from' => ($site->details['time_from']),
                    //'time_to' => ($site->details['time_to']) ? $site->details['time_to'] : '',
                    'premiere' => $site->details['premiere'],
                    'site_code' => $site->details['site_code'],
                    'active' => $site->active,
                    'is_default' => $site->is_default,
                    'created_at' => $site->created_at,
                    'updated_at' => $site->updated_at,
                    'deleted_at' => $site->deleted_at,
                ];
            }

            $directory = 'public/export/reports/';
            $files = Storage::files($directory);
            foreach ($files as $file) {
                Storage::delete($file);
            }

            $filename = "site.csv";
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

    public function downloadCsvTemplate()
    {
        try {
            $reports = [];
            $reports[] = [
                'id' => '',
                'serial_number' => '',
                'name' => '',
                'descriptions' => '',
                'site_logo' => '',
                'site_banner' => '',
                'site_background' => '',
                'site_background_portrait' => '',
                'company_id' => '',
                'company_name' => '',
                'multilanguage' => '',
                'facebook' => '',
                'instagram' => '',
                'twitter' => '',
                'tiktok' => '',
                'youtube' => '',
                'viber' => '',
                'website' => '',
                'schedules' => '',
                //'time_from' => ($site->details['time_from']) ? $site->details['time_from'] : '',
                //'time_to' => ($site->details['time_to']) ? $site->details['time_to'] : '',
                'premiere' => '',
                'site_code' => '',
                'active' => '',
                'is_default' => '',
                'created_at' => '',
                'updated_at' => '',
                'deleted_at' => '',
            ];

            $directory = 'public/export/reports/';
            $files = Storage::files($directory);
            foreach ($files as $file) {
                Storage::delete($file);
            }

            $filename = "site-template.csv";
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

    public function batchUpload(Request $request)
    {
        try {
            Excel::import(new SitesImport, $request->file('file'));
            return $this->response(true, 'Successfully Uploaded!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function getThemesFolder() {
        try {
            $foler_names = [];
            $i = 0;

            $dir = resource_path().'/views/kiosk/';
            $dirList = scandir($dir);

            foreach ($dirList as $key => $value) {
                if (strpos($value, '.') !== false) {
                }
                else {
                    $foler_names[$i] = $value;
                    $i++;
                }
            }
            
            return $this->response($foler_names, 'Successfully Uploaded!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }

        
    }
    // public function getOperationalHour($operational_hours){
    //     $time =[];
    //     if(count($operational_hours) > 0){
    //         // foreach($operational_hours as $operational_hour){
    //         //     $time[] = $operational_hour['company_id'];
    //         // }
    //         return ($operational_hours['schedules'])?$operational_hours['schedules']:'';
    //     }
    //     return null;
    // }
}

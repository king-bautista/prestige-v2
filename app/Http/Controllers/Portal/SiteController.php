<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Portal\Interfaces\SiteControllerInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Models\Site;
use App\Models\ViewModels\SiteViewModel;
use App\Models\UserSite;

class SiteController extends AppBaseController implements SiteControllerInterface
{
    /************************************
     * 			SITES MANAGEMENT	 	*
     ************************************/
    public function __construct()
    {
        $this->module_id = 53;
        $this->module_name = 'Property Details';
    }

    public function index()
    {
        return view('portal.sites');
    }

    public function list(Request $request)
    {
        try {
            $site_ids = UserSite::where('user_id', Auth::guard('portal')->user()->id)->get()->pluck('site_id');
            $sites = SiteViewModel::when(request('search'), function ($query) {
                return $query->where('name', 'LIKE', '%' . request('search') . '%')
                    ->orWhere('descriptions', 'LIKE', '%' . request('search') . '%');
            })
                ->whereIn('id', $site_ids)
                ->latest()
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

    public function store(Request $request)
    {
        try {
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

            if ($request->is_default == 'true') {
                Site::where('is_default', 1)->update(['is_default' => 0]);
            }

            $data = [
                'name' => $request->name,
                'descriptions' => $request->descriptions,
                'site_logo' => str_replace('\\', '/', $site_logo_path),
                'site_banner' => str_replace('\\', '/', $site_banner_path),
                'site_background' => str_replace('\\', '/', $site_background_path),
            ];

            $meta_value = [
                'facebook' => $request->facebook,
                'instagram' => $request->instagram,
                'twitter' => $request->twitter,
                'time_from' => $request->time_from,
                'time_to' => $request->time_to,
                'website' => $request->website,
            ];

            $site = Site::create($data);
            $site->serial_number = 'ST-' . Str::padLeft($site->id, 5, '0');
            $site->save();

            //UserSite::where('user_id', Auth::guard('portal')->user()->id)->delete();

            UserSite::updateOrCreate(
                [
                    'user_id' => Auth::guard('portal')->user()->id,
                    'site_id' => $site->id
                ]
            );
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

    public function update(Request $request)
    {
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

            if ($request->is_default == 'true') {
                Site::where('is_default', 1)->update(['is_default' => 0]);
            }

            $data = [
                'name' => $request->name,
                'descriptions' => $request->descriptions,
                'site_logo' => ($site_logo_path) ? str_replace('\\', '/', $site_logo_path) : $site->site_logo,
                'site_banner' => ($site_banner_path) ? str_replace('\\', '/', $site_banner_path) : $site->site_banner,
                'site_background' => ($site_background_path) ? str_replace('\\', '/', $site_background_path) : $site->site_background,
            ];

            $meta_value = [
                'facebook' => $request->facebook,
                'instagram' => $request->instagram,
                'twitter' => $request->twitter,
                'time_from' => $request->time_from,
                'time_to' => $request->time_to,
                'website' => $request->website,
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
            $sites = Site::get();
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
}

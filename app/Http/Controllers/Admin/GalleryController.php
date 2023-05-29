<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Interfaces\GalleryControllerInterface;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Http\Requests\GalleryRequest;

use App\Models\Gallery;
use App\Models\ViewModels\GalleryViewModel;
use App\Models\ViewModels\AdminViewModel;
//use App\Exports\Export;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Writer\Ods\Thumbnails;
use Image;
use VideoThumbnail;

class GalleryController extends AppBaseController implements GalleryControllerInterface
{

    /************************************************
     * 			Gallery MANAGEMENT	 	*
     ************************************************/
    public function __construct()
    {
        $this->module_id = 77;
        $this->module_name = 'Gallery';
    }

    public function index()
    {
        return view('admin.gallery');
    }

    public function list(Request $request)
    {
        try {
            $this->permissions = AdminViewModel::find(Auth::user()->id)->getPermissions()->where('modules.id', $this->module_id)->first();
            $gallery = GalleryViewModel::when(request('search'), function ($query) {

                return $query->where('gallery.title', 'LIKE', '%' . request('search') . '%')
                    ->orWhere('gallery.caption', 'LIKE', '%' . request('search') . '%')
                    ->orWhere('gallery.description', 'LIKE', '%' . request('search') . '%')
                    ->orWhere('gallery.thumbnail', 'LIKE', '%' . request('search') . '%')
                    ->orWhere('gallery.image_video_url', 'LIKE', '%' . request('search') . '%')
                    ->orWhere('gallery.file_type', 'LIKE', '%' . request('search') . '%');
            })
                ->latest()
                ->paginate(request('perPage'));
            return $this->responsePaginate($gallery, 'Successfully Retreived!', 200);
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
            $gallery = GalleryViewModel::find($id);
            return $this->response($gallery, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function upload(Request $request)
    {
        if (count($request->images)) {

            foreach ($request->images as $image) {

                $original_name =  str_replace(' ', '-', $image->getClientOriginalName());
                $filename = pathinfo($original_name, PATHINFO_FILENAME);
                $extension = $image->getClientOriginalExtension();
                $mime_type = explode("/", $image->getClientMimeType());
                $file_size = $image->getSize();
                $file_type = $mime_type[0];
                if ($file_type  == 'image') {
                    $image_url = $image->move('uploads/media/gallery/images', $original_name);
                    $image_size = getimagesize($image_url);
                    $width = $image_size[0];
                    $height = $image_size[1];
                    $dimension = $width . ' x ' . $height;

                    $thumbnail = public_path('uploads/media/gallery/images/thumbnails') . '/' . $original_name;

                    $img = Image::make($image_url);
                    $img->resize(150, 150, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save($thumbnail);

                    $data = [
                        'title' => $original_name,
                        'thumbnail' => str_replace(array("\\", "/images"), array("/", "/images/thumbnails"), $image_url),
                        'image_video_url' => str_replace("\\", "/", $image_url),
                        'file_type' => $file_type,
                        'file_size' => $file_size,
                        'dimension' => $dimension,
                        'width' => $width,
                        'height' => $height
                    ];
                } else {
                    $video_url = $image->move('uploads/media/gallery/videos', $original_name);

                    VideoThumbnail::createThumbnail(
                        public_path($video_url),
                        public_path('uploads/media/gallery/videos/thumbnails/'),
                        $filename . '.jpg',
                        20,
                        150,
                        150
                    );

                    $data = [
                        'title' => $original_name,
                        'thumbnail' => str_replace(array("\\", "/videos", ".mp4"), array("/", "/videos/thumbnails", ".jpg"), $video_url),
                        'image_video_url' => str_replace("\\", "/", $video_url),
                        'file_type' => $file_type,
                        'file_size' => $file_size,
                        'dimension' => '',
                        'width' => '',
                        'height' => ''
                    ];
                }
                Gallery::create($data);
            }
        }
        return $this->response('True', 'Successfully Created!', 200);
    }

    public function update(GalleryRequest $request)
    {
        try {
            $gallery = Gallery::find($request->id);

            $data = [
                'caption' => $request->caption,
                'description' => $request->description,
            ];

            $gallery->update($data);
            return $this->response($gallery, 'Successfully Modified!', 200);
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
            $gallery = GalleryViewModel::get();
            return $this->response($gallery, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }
}

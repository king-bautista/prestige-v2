<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Interfaces\GalleryControllerInterface;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

use App\Models\Gallery;
use App\Models\ViewModels\GalleryViewModel;
use App\Models\ViewModels\AdminViewModel;
use App\Exports\Export;
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

    public function upload(Request $request)
    {

        if (count($request->images)) {

            foreach ($request->images as $image) {

                $original_name = $image->getClientOriginalName();
                $filename = pathinfo($original_name, PATHINFO_FILENAME);
                $extension = $image->getClientOriginalExtension();
                $mime_type = explode("/", $image->getClientMimeType());
                $file_size = $image->getSize();
                //$file_path_path = $file_path->move('uploads/media/advertisements/'.strtolower($request->ad_type).'/', str_replace(' ','-', $originalname));
                $file_type = $mime_type[0];
                if ($file_type  == 'image') {
                    $file_path = $image->move('uploads/media/gllery/images', str_replace(' ', '-', $original_name));
                    $image_size = getimagesize($file_path);
                    $width = $image_size[0];
                    $height = $image_size[1];
                    $dimension = $width . ' x ' . $height;
                    $data = [
                        'file_path' => $file_path,
                        'file_type' => $file_type,
                        'file_size' => $file_size,
                        'dimension' => $dimension,
                        'width' => $width,
                        'height' => $height
                    ];

                    $destinationPath = public_path('uploads/media/gllery/images/thumbnails');
                    $img = Image::make($file_path);
                    $img->resize(150, 150, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save($destinationPath . '/' . $original_name);

                } else {
                    $file_path = $image->move('uploads/media/gllery/videos', str_replace(' ', '-', $original_name));
                    $data = [
                        'file_path' => $file_path,
                        'file_type' => $file_type,
                        'file_size' => $file_size,
                        'dimension' => '',
                        'width' => '',
                        'height' => ''
                    ];
                    
                    VideoThumbnail::createThumbnail(
                        public_path($file_path),
                        public_path('uploads/media/gllery/videos/thumbnails/'),
                        $filename.'.jpg',
                        20,
                        150,
                        150
                    );
                }

                Gallery::create($data);
            }
        }

        return $this->response('True', 'Successfully Created!', 200);
    }

    public function getAll()
    {  
        try
    	{
            $gallery = GalleryViewModel::get(); 
            return $this->response($gallery, 'Successfully Retreived!', 200);
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

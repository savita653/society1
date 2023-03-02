<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\User;
use Auth;

class CropImageController extends Controller
{

    public function __construct()
    {
    }

    public function cropImage($guard)
    {
        header('Content-Type: image/jpeg');

        $imgr = new imageResizing();

        if ( request()->get('cp_img_path') != NULL ) {
            $image = public_path("uploads/profile_pic/{$guard}/" . request()->get('cp_img_path'));// $_POST['cp_img_path'] = /assets/uploads/img_name.ext
            $imgr->load($image);
            
            $imgX = intval($_POST['ic_x']);
            $imgY = intval($_POST['ic_y']);
            $imgW = intval($_POST['ic_w']);
            $imgH = intval($_POST['ic_h']);
            
            $imgr->resize($imgW,$imgH,$imgX,$imgY);    
            
            $imgr->save($image);
            $record = auth($guard)->user();
            $record->profile_photo_path = request()->get('cp_img_path');
            $record->save();

            echo '<img src="'. asset("uploads/profile_pic/{$guard}/" . request()->get('cp_img_path') ) .'?t='.time().'"  class="rounded mr-50" alt="profile image" height="80"
            width="80"/>';
        }


    }

    function uploadImage(Request $request, $guard)
	{

		$id = Auth::guard($guard)->user()->id;
		$validator = Validator::make(
			$request->all(),
			['file' => 'required|image|mimes:jpeg,png,jpg,png,ico|max:2048'],
			[
				'file.mimes' => 'Only jpg, jpeg, png files are allowed',
				'file.max' => 'Image size must be less than 2MB.'
			]
		);

		if ($validator->fails()) {
			echo "<div class='text-left font-weight-bold'>You have following errors:</div>";
			echo "<div class='text-left text-danger'>";
			foreach($validator->errors()->all() as $error) {
				echo "<p>" . $error . "</p>";
			}
			echo "</div>";
			exit;
			return response()->json([
				'success' => false,
				'error' => $validator->errors()->all()
			]);
		}

		$fileName = "{$guard}_" . $id . "_" . time() . "." . $request->file('file')->getClientOriginalExtension();
		

		$request->file('file')->move(public_path('uploads/profile_pic/' . $guard), $fileName);
		echo '<div style="height: 50vh;" class="cropping-image-wrap"><img data-filename="' . $fileName . '" src="'.asset("uploads/profile_pic/{$guard}/" . $fileName).'" class="img-thumbnail" id="crop_image"/></div>';
		exit;

	}
}


class imageResizing
{

    var $image;
    var $image_type;
    var $res;

    function load($filename)
    {

        $image_info = getimagesize($filename);

        $this->image_type = $image_info[2];

        if ($this->image_type == IMAGETYPE_JPEG) {
            $this->image = imagecreatefromjpeg($filename);
            $this->res = ".jpg";
        } elseif ($this->image_type == IMAGETYPE_GIF) {
            $this->image = imagecreatefromgif($filename);
            $this->res = ".gif";
        } elseif ($this->image_type == IMAGETYPE_PNG) {
            $this->image = imagecreatefrompng($filename);
            $this->res = ".png";
        }
    }

    function save($filename, $image_type = IMAGETYPE_JPEG, $compression = 100, $permissions = null)
    {

        if ($image_type == IMAGETYPE_JPEG)
            imagejpeg($this->image, $filename, $compression);

        elseif ($image_type == IMAGETYPE_GIF)
            imagegif($this->image, $filename);

        elseif ($image_type == IMAGETYPE_PNG)
            imagepng($this->image, $filename);

        if ($permissions != null)
            chmod($filename, $permissions);
    }

    function output($image_type = IMAGETYPE_JPEG)
    {

        if ($image_type == IMAGETYPE_JPEG)
            imagejpeg($this->image);

        elseif ($image_type == IMAGETYPE_GIF)
            imagegif($this->image);

        elseif ($image_type == IMAGETYPE_PNG)
            imagepng($this->image);
    }

    function getWidth()
    {
        return imagesx($this->image);
    }

    function getHeight()
    {
        return imagesy($this->image);
    }

    function resizeToHeight($height)
    {

        $ratio = $height / $this->getHeight();

        $width = $this->getWidth() * $ratio;

        $this->resize($width, $height);
    }
    function resizeToWidth($width)
    {

        $ratio = $width / $this->getWidth();

        $height = $this->getheight() * $ratio;

        $this->resize($width, $height);
    }
    function scale($scale)
    {

        $width = $this->getWidth() * $scale / 100;

        $height = $this->getheight() * $scale / 100;

        $this->resize($width, $height);
    }
    function resize($width, $height, $x = 0, $y = 0)
    {

        $new_image = imagecreatetruecolor($width, $height);

        //imagecopyresampled($new_image, $this->image, $x, $y, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
        imagecopy($new_image, $this->image, 0, 0, $x, $y, $width, $height);
        /*
       echo $x."<br/>";
       echo $y."<br/>";
       echo $width."<br/>";
       echo $height."<br/>";
       echo $this->getWidth()."<br/>";
       echo $this->getHeight();*/

        $this->image = $new_image;
    }
}

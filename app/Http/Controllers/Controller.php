<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Str;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected function compress($source, $destination, $quality)
    {
        try {
            $path = $source->getPathname();
            $extension = $source->getClientOriginalExtension();

            if (!file_exists($destination)) {
                mkdir($destination, 0777, true);
            }

            $lastSlash = strrpos($destination, '/');
            if ($lastSlash !== strlen($destination) - 1) {
                $destination .= '/';
            }

            $saveName = $this->hashName($extension);
            $destination .= $saveName;

            $imgInfo = getimagesize($path);
            $mime = $imgInfo['mime'];
            switch ($mime) {
                case 'image/jpeg':
                    $image = imagecreatefromjpeg($path);
                    break;
                case 'image/png':
                    $image = imagecreatefrompng($path);
                    break;
                default:
                    $image = imagecreatefromjpeg($path);
            }
            imagejpeg($image, $destination, $quality);

            return $saveName;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function hashName($extension, $path = null)
    {
        if ($path) {
            $path = rtrim($path, '/').'/';
        }

        $hash = Str::random(9);

        $extension = '.'.$extension;

        return $path.$hash.$extension;
    }
}

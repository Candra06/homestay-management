<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

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

    protected function insertLog(string $message, Throwable $th, array $extra = [])
    {
        $errorCode = (!empty($th->getCode()) && $th->getCode() !== 0) ? $th->getCode() : 500;

        Log::error($message, array_merge([
            'error_code'    => $errorCode,
            'error_message' => $th->getMessage(),
            'file'          => $th->getFile(),
            'line'          => $th->getLine(),
            'trace'         => $th->getTraceAsString(),
        ], $extra));
    }

    protected function uploadToS3($sourceFile, $folder, $quality = 50)
    {
        try {
            // 1. Tentukan folder lokal sementara untuk hasil kompresi GD
            $tempDestination = storage_path('app/public/temp_upload');

            // 2. Panggil method compress yang sudah Anda miliki
            // Method ini akan menghasilkan $saveName dan menyimpan file sementara secara lokal
            $saveName = $this->compress($sourceFile, $tempDestination, $quality);

            $localFilePath = $tempDestination . '/' . $saveName;

            // 3. Baca isi file yang sudah dikompresi menjadi string
            $fileContent = file_get_contents($localFilePath);

            // 4. Tentukan path tujuan di S3 CloudKilat (misal: 'ezzy-homestay/facility/nama_file.jpg')
            $s3Path = trim($folder, '/') . '/' . $saveName;

            // 5. Upload ke S3 CloudKilat dengan visibilitas public
            Storage::disk('s3')->put($s3Path, $fileContent, 'public');

            // 6. Hapus file sampah / sisa kompresi di lokal server agar tidak menumpuk
            if (file_exists($localFilePath)) {
                @unlink($localFilePath);
            }

            // 7. Kembalikan data lengkap untuk kebutuhan database
            $url = Storage::disk('s3')->url($s3Path);
            return [
                'file_path'     => $s3Path,
                'file_name'     => $saveName,
                'original_name' => $sourceFile->getClientOriginalName(),
                'file_size'     => Storage::disk('s3')->size($s3Path),
                'mime_type'     => Storage::disk('s3')->mimeType($s3Path),
                'file_url'      => $url,
            ];

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    protected function deleteFile($path) {
        try {
            if (str_contains($path,'https')) {
                $filePath = explode('/ezzy-homestay/', $path)[1];
                if (Storage::disk('s3')->exists($filePath)) {
                    Storage::disk('s3')->delete($filePath);
                }
            } else {
                if (Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            }
            
            return true;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}

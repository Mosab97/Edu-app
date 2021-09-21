<?php
/**
Dev Mosab Irwished
eng.mosabirwished@gmail.com
WhatsApp +970592879186
 */
namespace App\Traits;

use Carbon\Carbon;

trait UploadMedia
{
public function uploadImage($file, $path = '')
    {
        $fileName = $file->getClientOriginalName();
        $file_exe = $file->getClientOriginalExtension();
        $new_name = uniqid() . '.' . $file_exe;
        $directory = 'uploads' . '/' . $path;//.'/'.date("Y").'/'.date("m").'/'.date("d");
        $destienation = public_path($directory);
        $file->move($destienation, $new_name);
        return [
            'path' => $directory . '/' . $new_name,
            'name' => $fileName,
            'extension' => $file_exe,
        ];
    }


}

<?php


namespace App\Services\Admin;


use Intervention\Image\Facades\Image;

class BaseService
{

    /**
     * @param $file
     * @param $path
     * @param $path1
     * @param $width
     * @param $width1
     * @param $height
     * @param $height1
     * @param null $fileName
     * @return mixed
     */
    public function upload($file, $path, $path1, $width, $width1, $height, $height1, $fileName = null)
    {

        $extension = $file->getClientOriginalExtension();

        if ($fileName) {
            $fileName .= '.' . $extension;
        } else {
            $fileName = md5(str_random(20)) . '.' . $extension;
        }

        // File save
        $originalFile = Image::make($file->getRealPath());
        $result = $originalFile
            ->resize($width, $height, function ($c) {
                $c->aspectRatio();
                $c->upsize();
            })
            ->save($path . $fileName);
        if (!empty($path1)) {
            $result1 = $originalFile
                ->resize($width1, $height1, function ($q) {
                    $q->aspectRatio();
                    $q->upsize();
                })
                ->save($path1 . $fileName);

            $if = (bool)$result && isset($result1);
        } else {
            $if = (bool)$result;
        }
        if ($if) {
            //$data['size'] = filesize($file);
            $data['file_name'] = $fileName;
            return $data;
        }
    }


}

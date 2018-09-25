<?php
/**
 * Created by PhpStorm.
 * User: CD RED
 * Date: 29.05.2018
 * Time: 15:27
 */

namespace app\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class ImageUpload extends Model
{
    public $image;

    public function rules()
    {
        return[
            [['image'],'required'],
            [['image'],'file','extensions' => 'jpg,png']
        ];
    }

    public function uploadFile(UploadedFile $file,$currentImage)
    {
        $this->image = $file;

        if($this->validate())
        {
            $this->deleteCurrentImage($currentImage);
            return $this->saveImage();
        }
    }

    private function getFolder()
    {
        return Yii::getAlias("@web") . "uploads/";
    }

    private function generateFileName()
    {
        return strtolower(md5(uniqid($this->image->baseName)) . '.' . $this->image->extension);

    }

    public function deleteCurrentImage($currentImage)
    {
        if($this->fileExists($currentImage))
        {
            unlink($this->getFolder() . $currentImage);
        }
    }

    public function fileExists($currentImage)
    {
        if(!empty($currentImage) && $currentImage != null)
        {
            return file_exists($this->getFolder() . $currentImage);

        }
    }

    public function saveImage()
    {
        $filename = $this->generateFileName();

        $this->image->saveAs($this->getFolder() . $filename);

        return $filename;
    }
}
<?php
use Illuminate\Database\Migrations\Migration;
use App\ImageModel;

class CreateResizeimagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->setMigrationConfig();
    }

    /**
     * Configuration For Resizing Images
     */
    public function setMigrationConfig()
    {
        $prefixes = [
            'square',
            'thumbnail',
            'timeline',
            'post',
            'half_box',
            'slider_image',
            'big_article_image',
            'article_image',
            'featured',
            'featured_index',
            'time_line',
            'related_blogs',
            'thumbnail_sidebar',
        ];
        $imagesTable   = ImageModel::all();
        $imagesFullPath = public_path() . '/images/';
        $separatorBetImageAndPrefix = '-';

        $this->_setImageExtension($prefixes ,$imagesTable ,$imagesFullPath ,$separatorBetImageAndPrefix);

    }

    private function _setImageExtension($prefixes ,$imagesTable ,$imagesFullPath ,$separatorBetImageAndPrefix)
    {
        foreach ($prefixes as $prefix) {

            $this->_fetchFullImageDir($imagesTable ,$imagesFullPath ,$prefix ,$separatorBetImageAndPrefix);
        }
    }

    /**
     * @return array
     */
    private function _fetchFullImageDir($imagesTable ,$imagesFullPath ,$prefix ,$separatorBetImageAndPrefix)
    {
        $width = null;
        foreach ($imagesTable as $thisImage){

            $imageName     = $thisImage['name'];
            $imageDir      = $thisImage['dir'];
            $imageId       = $thisImage['id'];
            $imageSlug     = $thisImage['slug'];

            $fullImagePath = $imagesFullPath . $imageDir . $prefix . $separatorBetImageAndPrefix . $imageSlug; //Param  1
            $imageExtension= $this->getExtension($imageName);       //Png , //Param +4
            $imageNameWithoutExtension = str_replace($imageExtension, '', $fullImagePath);

            $imageWidth          = $this->_setWidth($prefix);           //Param 2
            $thumbImageHeight    = $this->_setHeight($prefix);           //Param 3

            $this->resizeImage(
                $imageNameWithoutExtension. '.' . $imageExtension,
                $imageWidth,
                $thumbImageHeight ,
                public_path() . '/images/' . $imageDir . $prefix . '_' . $imageName
            );
        }

    }

    /**
     * @param $prefix
     * @return int|null
     */
    private function _setWidth($prefix)
    {
        $width = null;

        if ($prefix == 'square')
            $width = 50;

        if ($prefix == 'thumbnail')
            $width = 600;

        if ($prefix == 'timeline')
            $width = 360;

        if ($prefix == 'post')
            $width = 500;

        if ($prefix == 'half_box')
            $width = 200;

        if ($prefix == 'slider_image')
            $width = 700;

        if ($prefix == 'big_article_image')
            $width = 3168;

        if ($prefix == 'article_image')
            $width = 395.75;

        if ($prefix == 'featured')
            $width = 340;

        if ($prefix == 'featured_index')
            $width = 370;

        if ($prefix == 'time_line')
            $width = 392;

        if ($prefix == 'related_blogs')
            $width = 195;

        if ($prefix == 'thumbnail_sidebar')
            $width = 72;

        return $width;
    }

    private function _setHeight($prefix)
    {
        $thumbImageHeight = null;

        if ($prefix == 'square')
            $thumbImageHeight = 50;

        if ($prefix == 'thumbnail')
            $thumbImageHeight = 220;

        if ($prefix == 'timeline')
            $thumbImageHeight = 200;

        if ($prefix == 'post')
            $thumbImageHeight = 440;

        if ($prefix == 'half_box')
            $thumbImageHeight = 320;

        if ($prefix == 'slider_image')
            $thumbImageHeight = 350;

        if ($prefix == 'big_article_image')
            $thumbImageHeight = 500;

        if ($prefix == 'article_image')
            $thumbImageHeight = 246.233;

        if ($prefix == 'featured')
            $thumbImageHeight = 189;

        if ($prefix == 'featured_index')
            $thumbImageHeight = 189;

        if ($prefix == 'time_line')
            $thumbImageHeight = 165;

        if ($prefix == 'related_blogs')
            $thumbImageHeight = 128;

        if ($prefix == 'thumbnail_sidebar')
            $thumbImageHeight = 72;

        return $thumbImageHeight;
    }


    /**
     * @param $imageName
     * @param $thumbImageWidth
     * @param $thumbImageHeight
     * @param $thumbImageTarget
     * @return bool
     */
    private function resizeImage($imageName, $thumbImageWidth, $thumbImageHeight, $thumbImageTarget)
    {
        if (file_exists($imageName)) {

            $imageInfo = finfo_open(FILEINFO_MIME_TYPE); //Create File Info
            $imageType = finfo_file($imageInfo, $imageName); //Return File Info After Check If File Is Valid Or Not
            finfo_close($imageInfo);


            if ($imageType == 'image/jpeg' || $imageType == 'image/jpg') {
                $imgSource = imagecreatefromjpeg($imageName);
            } elseif ($imageType == 'image/png') {
                $imgSource = imagecreatefrompng($imageName);
            } elseif ($imageType == 'image/gif') {
                $imgSource = imagecreatefromgif($imageName);
            } else {
                $imgSource = false;
                return false;
            }

            if ($imgSource) {
                list($width, $height) = getimagesize($imageName);
                $tempThumbImage = imagecreatetruecolor($thumbImageWidth, $thumbImageHeight);// Create a new true color image

                //imagecopyresampled => Copy and resize part of an image with resampling
                if (!imagecopyresampled($tempThumbImage, $imgSource, 0, 0, 0, 0, $thumbImageWidth, $thumbImageHeight, $width, $height)) return false;

                if (!imagejpeg($tempThumbImage, $thumbImageTarget, 100)) return false;//Creates a JPEG file from the given image.

                if (!imagedestroy($imgSource)) return false;//Frees image from memory

                if (!imagedestroy($tempThumbImage)) return false;

                if (!unlink($imageName)) return false; //Deletes a file

                return true;
            }

        }else {
            return false;
        }
    }

    /**
     * @param $file
     * @return bool|mixed|string
     */
    private function getExtension($file)
    {
        $prefix = substr($file, strrpos($file, '/') + 1);
        $prefix = pathinfo($file, PATHINFO_EXTENSION);
        $prefix = strtolower($prefix);
        return $prefix;

    }

    /**
     * Down Function
     */
    public function down()
    {
        //parent::down();
    }
}

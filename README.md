# hamdy_packages-resizeimages

#Tested package by hamdy ahmed.
This Package for resize images without need to uploading them .only you need to specific images dir .

#Features:
-can to set height and width for images you want to resize them
-can to set prefixes to that images as you need and all prefixes 
  will has different size
 -you didnt need to upload images to resize it and that is the diffrence 
  betwwen this package and Imagic package as a example

#Installation
Run this command in your composer
`composer require hamdy_packages/resizeimages:dev-master`

after completed to download package
open your app.php file `config/app.php`
and put this to providers array line

`Hamdy_Packages\Resizeimages\ResizeimagesServiceProvider::class,`

#usage
-You have to create model for your table or array where you save images
-the package need :
  1-image_name
  2-image_slug
  3-image_dir
  4-image_id
  
  -in migration file you must to set configuration as you need.
  -you must to set height and width you need in migration file
  
  -finally run this command 
    `php artisan migrate`

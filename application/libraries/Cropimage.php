<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Cropimage {
    
   
 
    function cropimageP($pathtoupload)
    {
        include_once APPPATH.'third_party/cropimage.php';
         

       return  new CropAvatar(
  isset($_POST['avatar_src']) ? $_POST['avatar_src'] : null,
  isset($_POST['avatar_data']) ? $_POST['avatar_data'] : null,
  isset($_FILES['avatar_file']) ? $_FILES['avatar_file'] : null,
  $pathtoupload
  
);

    }
}
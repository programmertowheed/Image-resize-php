 <?php

    //Image resize function
    function resizeImage($img,$image_width,$image_height,$resizeWidth,$resizeHeight) 
    {
        //Create a background for image layer
        $imageLayer = imagecreatetruecolor($resizeWidth,$resizeHeight);

        imagealphablending($imageLayer, false);
        imagesavealpha($imageLayer,true);
        $transparent = imagecolorallocatealpha($imageLayer, 255, 255, 255, 127);
        imagefilledrectangle($imageLayer, 0, 0, $resizeWidth, $resizeHeight, $transparent);
        //Create image
        imagecopyresampled($imageLayer,$img,0,0,0,0,$resizeWidth,$resizeHeight, $image_width,$image_height);
        return $imageLayer;
    }
 


    if(isset($_POST["submit"])) {
        if(is_array($_FILES)) {

            //Whiche type of file do you want to upload
            $permited   = array('jpg','jpeg','png','gif');

            //Get image orginal name with extension
            $file_name  =$_FILES['upload_image']['name'];
            //Get Image Size
            $file_size  =$_FILES['upload_image']['size'];
            //Get Image Temp name  
            $file_temp  =$_FILES['upload_image']['tmp_name'];

            if(!empty($file_name) && !empty($file_temp) ){ 

                    $source_url_parts = pathinfo($file_name);
                    //Get image name
                    $filename = $source_url_parts['filename'];

                    $div = explode('.', $file_name );
                    //Lowercase extension
                    $fileExt = strtolower(end($div));
                    //Image unique name
                    $unique_image = "thump_".time().".".$fileExt;
                    //Upload path
                    $upload_path = "./uploads/".$unique_image;

                    //Get Image properties (Type, Height, Wight)
                    $sourceProperties = getimagesize($file_temp);
                    //Get Image type (JPEG, PNG, GIF)
                    //If you are Use IMAGETYPE_JPEG,IMAGETYPE_PNG,IMAGETYPE_GIF than need
                    $uploadImageType = $sourceProperties[2];

                    //Image width
                    $sourceImageWidth = $sourceProperties[0];
                    //Image Height
                    $sourceImageHeight = $sourceProperties[1];

                    //Resize width
                    $resizeWidth = 1200;//User can define this width
                    //Resize height
                    $resizeHeight = 900;//User can define this height


                if(in_array($fileExt,$permited) === false){
                    //show an error message if the file extension is not available
                    echo "<span style='color:red;font-size:18px'>You can upload only: ".implode(', ',$permited)."</span>";
                }elseif($file_size >= 5242880){
                    //show an error message if the file size is large (Max file size 5 MB)
                    echo "<span style='color:red;font-size:18px'>Your uploaded image size is too large (".round($file_size/(1024*1024),2)." MB) Max upload size is 5 MB</span>";
                }else{
                    //move_uploaded_file($file_temp,$upload_image);
                    $imageProcess = 0;

                    if ($fileExt == 'jpg' || $fileExt == 'jpeg') {
                        //then return the image as a jpeg image for the next step
                        $img = imagecreatefromjpeg($file_temp);
                        //return resize image
                        $imageLayer = resizeImage($img,$sourceImageWidth,$sourceImageHeight,$resizeWidth,$resizeHeight);
                        //upload image
                        imagejpeg($imageLayer,$upload_path);
                        $imageProcess = 1;
                    } elseif ($fileExt == 'gif') {
                        //then return the image as a gif image for the next step
                        $img = imagecreatefromgif($file_temp); 
                        //return resize image
                        $imageLayer = resizeImage($img,$sourceImageWidth,$sourceImageHeight,$resizeWidth,$resizeHeight);
                        //upload image
                        imagegif($imageLayer,$upload_path);
                        $imageProcess = 1;
                    } elseif ($fileExt == 'png') {
                        //then return the image as a png image for the next step
                        $img = imagecreatefrompng($file_temp); 
                        //return resize image
                        $imageLayer = resizeImage($img,$sourceImageWidth,$sourceImageHeight,$resizeWidth,$resizeHeight);
                        //upload image
                        imagepng($imageLayer,$upload_path);
                        $imageProcess = 1;
                    } else {
                        //show an error message if the file extension is not available
                        echo "<span style='color:red;font-size:18px'>Image extension is not supporting</span>";
                    }

                    if($imageProcess == 1){
                        echo "<span style='color:green;font-size:18px'>Image Resize successfully !!</span>";
                    }else{
                        echo "<span style='color:red;font-size:18px'>Image does not resized !!</span>";
                    }
                    $imageProcess = 0;

                }
            }else{
                echo "<span style='color:red;font-size:18px'>Your uploaded image is empty</span>";
            }

        }
    }
      
?>

<form action="" method="post" enctype="multipart/form-data">
    <input type="file" name="upload_image">
    <input type="submit" name="submit" value="Submit">
</form>
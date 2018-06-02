<?php
function ReSaveFiles($File_Src){
  $Imagesextensions = array('gif','GIF','jpeg','JPEG','jpg','JPG','png','PNG');
  $Filessextensions = array("rar","zip","pdf","doc","docx","mp3","avi","flv");

  $GetImageSize = array("350","165","1024");

  $uploadfolderName = "Folder1";

  $filename = $File_Src;
  $filepath = $File_Src;
  $fileext = substr(strrchr($filename, '.'), 1);
  $FilesName = "File_".date("Ymd_his")."_".rand(10,1000);
  $FilesName = $FilesName.$key.".".$fileext;
  $FolderName = "files/";
  $SendFilePath = "";
  if(in_array($fileext,$Imagesextensions) || in_array($fileext,$Filessextensions)){
    if(in_array($fileext,$Imagesextensions)){
      list($width, $height) = getimagesize($filepath);
      if($width>0){
          $SendFilePath = $FolderName.$uploadfolderName."/".$GetImageSize[0]."/".$FilesName;
          DoFolders($uploadfolderName,$GetImageSize[0],$GetImageSize[0],$GetImageSize[0]);
          resize($GetImageSize[0],$FolderName.$uploadfolderName."/".$GetImageSize[0]."/".$FilesName,$filepath,'w');
          resize($GetImageSize[0],$FolderName.$uploadfolderName."/".$GetImageSize[0]."/".$FilesName,$filepath,'s');
          resize($GetImageSize[0],$FolderName.$uploadfolderName."/".$GetImageSize[0]."/".$FilesName,$filepath,'n');
      }
    }else{
      DoFolders($uploadfolderName);
      $SendFilePath = "files/".$uploadfolderName."/".$FilesName;
      copy($filepath,"files/".$uploadfolderName."/".$FilesName);
    }
    return $SendFilePath;
  }
}








function resize($newWidth, $targetFile, $originalFile,$type) {
  $thumb_width=$newWidth;
  $image_path=$originalFile;

  $info = getimagesize($originalFile);
  $mime = $info['mime'];
  switch ($mime) {
          case 'image/jpeg':
                  $image_create_func = 'imagecreatefromjpeg';
                  $image_save_func = 'imagejpeg';
                  $new_image_ext = 'jpg';
                  break;

          case 'image/png':
                  $image_create_func = 'imagecreatefrompng';
                  $image_save_func = 'imagepng';
                  $new_image_ext = 'png';
                  break;

          case 'image/gif':
                  $image_create_func = 'imagecreatefromgif';
                  $image_save_func = 'imagegif';
                  $new_image_ext = 'gif';
                  break;

          default:
                  throw Exception('Unknown image type.');
  }
  $source_image = $image_create_func($originalFile);
  list($source_width, $source_height) = getimagesize($originalFile);

  if($type=='s')
  {
  	if($source_width>=$source_height)
  	{
  		if($thumb_width>$source_width)
  		{
  			$thumb_width=$source_width;
  			$thumb_height=$source_height;
  		}
  		else
  		{
  			$thumb_height=($source_height/$source_width)*$thumb_width;
  		}
  	}
  	else
  	{
  		$thumb_height=($source_height/$source_width)*$thumb_width;
  		if($thumb_height>$source_height)
  		{
  			$thumb_width=$source_width;
  			$thumb_height=$source_height;
  		}
  	}

    $square=$newWidth;
    if ($thumb_width === "*") {
        $thumb_width = $thumb_height * $source_width / $source_height;
    } else {
        if ($thumb_height === "*") {
           $thumb_height = $thumb_width * $source_height / $source_width;
        } else {
            if (($source_width / $source_height) < ($thumb_width / $thumb_height)) {
                $thumb_width = $thumb_height * $source_width / $source_height;
            } else {
                if (($source_width / $source_height) > ($thumb_width / $thumb_height)) {
                    $thumb_height = $thumb_width * $source_height / $source_width;
                }
            }
        }
    }

    if($source_width>=$source_height)
    {
  	    $target_image = imagecreatetruecolor($square, $square);
    		imagecopyresampled($target_image, $source_image, -($square-$thumb_height), -($square-$thumb_width), 0, 0, ($source_width*$square )/$source_height, $square, $source_width, $source_height);
    }
    if($source_height>$source_width)
    {
  	    $target_image = imagecreatetruecolor($square, $square);
    		imagecopyresampled($target_image, $source_image, -($thumb_height-(($source_height/$source_width)*$square)), -($square-$thumb_width), 0, 0, $square, ($source_height*$square )/$source_width, $source_width, $source_height);
    }
  }
  else if($type=='w')
  {
  	if($source_width>=$source_height)
  	{
  		if($thumb_width>$source_width)
  		{
  			$thumb_width=$source_width;
  			$thumb_height=$source_height;
  		}
  	}
  	else
  	{
  		$thumb_height=($source_height/$source_width)*$thumb_width;
  		if($thumb_height>$source_height)
  		{
  			$thumb_width=$source_width;
  			$thumb_height=$source_height;
  		}
  	}
  	$thumb_height=$thumb_width*($source_height/$source_width);
  	$target_image = imagecreatetruecolor($thumb_width, $thumb_width*0.59);
  	imagecopyresampled($target_image, $source_image, 0,-(($thumb_height-$thumb_width*0.59)/2), 0, 0, $thumb_width, $thumb_height, $source_width, $source_height);
  }
  else
  {
  	if($source_width>$source_height)
  	{
  		if($thumb_width>$source_width)
  		{
  			$thumb_width=$source_width;
  			$thumb_height=$source_height;
  		}
  	}
  	else
  	{
  		$thumb_height=($source_height/$source_width)*$thumb_width;
  		if($thumb_height>$source_height)
  		{
  			$thumb_width=$source_width;
  			$thumb_height=$source_height;
  		}
  	}
  	$thumb_width;
  	$thumb_height=$thumb_width*($source_height/$source_width);
  	$target_image = imagecreatetruecolor($thumb_width, $thumb_height);
  	imagecopyresampled($target_image, $source_image, 0,0, 0, 0, $thumb_width, $thumb_height, $source_width, $source_height);
  }

  if (file_exists($targetFile)) {
      unlink($targetFile);
  }
  $image_save_func($target_image, "$targetFile");
}






function DoFolders($uploadfolderName, $Size1="", $Size2="", $Size3=""){
  $FolderName = "files/";
  if(@!is_dir($FolderName.$uploadfolderName)) {
      @mkdir($FolderName.$uploadfolderName,0755, true);
      $myfile2 = @fopen($FolderName.$uploadfolderName."/.htaccess", "w");
      $txt2 = "Options -Indexes"."\n"."RewriteRule ^.*\.php$ - [F,L,NC]";
      fwrite($myfile2, $txt2);
      fclose($myfile2);
  }
  if($Size1!=""){
    if(@!is_dir($FolderName.$uploadfolderName."/".$Size1)) {
      @mkdir($FolderName.$uploadfolderName."/".$Size1,0755, true);
      $myfile3 = @fopen($FolderName.$uploadfolderName."/".$Size1."/.htaccess", "w");
      $txt3 = "Options -Indexes"."\n"."RewriteRule ^.*\.php$ - [F,L,NC]";
      fwrite($myfile3, $txt3);
      fclose($myfile3);
    }
  }
  if($Size2!=""){
    if(@!is_dir($FolderName.$uploadfolderName."/".$Size2)) {
      @mkdir($FolderName.$uploadfolderName."/".$Size2,0755, true);
      $myfile4 = @fopen($FolderName.$uploadfolderName."/".$Size2."/.htaccess", "w");
      $txt4 = "Options -Indexes"."\n"."RewriteRule ^.*\.php$ - [F,L,NC]";
      fwrite($myfile4, $txt4);
      fclose($myfile4);
    }
  }
  if($Size3!=""){
    if(@!is_dir($FolderName.$uploadfolderName."/".$Size3)) {
      @mkdir($FolderName.$uploadfolderName."/".$Size3,0755, true);
      $myfile5 = @fopen($FolderName.$uploadfolderName."/".$Size3."/.htaccess", "w");
      $txt5 = "Options -Indexes"."\n"."RewriteRule ^.*\.php$ - [F,L,NC]";
      fwrite($myfile5, $txt5);
      fclose($myfile5);
    }
  }
}
?>

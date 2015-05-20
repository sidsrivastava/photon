<?php
/*
  Copyright (C) 2006 Sid Srivastava 
 
  Permission is hereby granted, free of charge, to any person obtaining a
  copy of this software and associated documentation files (the
  "Software"), to deal in the Software without restriction, including
  without limitation the rights to use, copy, modify, merge, publish,
  distribute, sublicense, and/or sell copies of the Software, and to
  permit persons to whom the Software is furnished to do so, subject to
  the following conditions:
 
  The above copyright notice and this permission notice shall be included
  in all copies or substantial portions of the Software.
 
  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS
  OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
  MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
  IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY
  CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT,
  TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE
  SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/
?>
<?php
class photon_thumbnail
{
  var $id;
  var $path;
  var $url;
  var $resize_factor;
  var $jpeg_quality;

  function photon_thumbnail($image_path = '', $resize_factor = .25, $jpeg_quality = 100)
  {
    global $config;
    global $log;

    $this->path = '';
    $this->url = '';
    $this->resize_factor = '';
    $this->jpeg_quality = '';

    if ($image_path != NULL)
    {
      $this->resize_factor = $resize_factor;
      $this->jpeg_quality = $jpeg_quality;
      $this->id = get_image_id_from_path($image_path);

      if ($this->id != -1)
      {
        $this->path = $config['output']['thumbnails'] . "/" . $this->resize_factor . '-'. $this->id . ".jpg";
        $this->url = $config['url']['thumbnails'] . "/" . $this->resize_factor . '-' . $this->id . ".jpg";
      }
      else
      {
        $this->path = $config['output']['thumbnails'] . "/" . $this->resize_factor . '-' . md5_file($image_path) . ".jpg";
        $this->url = $config['url']['thumbnails'] . "/" . $this->resize_factor . '-' . md5_file($image_path) . ".jpg";
      }
      photon_thumbnail::create($image_path);
    }
  }

  function create($image_path)
  {
    global $config;

    $thumbnail_path = $this->path;
    $resize_factor = $this->resize_factor;
    $jpeg_quality = $this->jpeg_quality;

    /* If the thumbnail already, exists, no need to create another thumbnail */
    if (!file_exists($thumbnail_path))
    {
      /* Size of the source image, as a 2-D array */
      $image_size = getimagesize($image_path);
      $image_x = $image_size[0];
      $image_y = $image_size[1];
      
      if ($image_x >= $image_y)
      {
        $thumbnail_x = 160 * ($resize_factor / 0.25);
        $thumbnail_y = ($thumbnail_x / $image_x) * $image_y;
      }
      else
      {
        $thumbnail_x = 120 * ($resize_factor / 0.25);
        $thumbnail_y = ($thumbnail_x / $image_x) * $image_y;
      }
 
      $thumbnail_id = imagecreatetruecolor($thumbnail_x, $thumbnail_y);

      switch (get_file_extension($image_path))
      {
        case "jpg":

          $image_id = imageCreateFromJPEG($image_path);
          $target_image = imagecopyresampled($thumbnail_id, $image_id, 0, 0, 0, 0, $thumbnail_x, $thumbnail_y, $image_x, $image_y);
          imagejpeg($thumbnail_id, $thumbnail_path, $jpeg_quality);
          break;
        case "png":
          $image_id = imageCreateFromPNG($image_path);
          $target_image = imagecopyresampled($thumbnail_id, $image_id, 0, 0, 0, 0, $thumbnail_x, $thumbnail_y, $image_x, $image_y);
          imagepng($thumbnail_id, $thumbnail_path, $jpeg_quality);
          break;
       }
    }
  }
}
?>

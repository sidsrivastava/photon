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
class photon_form
{
  function process_images($posted_images)
  {
    foreach ($posted_images as $posted_image)
    {
      $image = new photon_image();
      $image->grep_array($posted_image);
      $image->commit_to_database();
    }
  }

  function extract_images_from_postvars($http_post_vars) 
  {
    $images = array();
  
    for ($i = 0; $i < count($http_post_vars['path']); $i++)
    {
      $image = array(
        'caption' => $http_post_vars['caption'][$i], 
        'date_taken' => $http_post_vars['date_taken'][$i],
        'persons' => $http_post_vars['persons'][$i], 
        'tags' => $http_post_vars['tags'][$i], 
        'place' => $http_post_vars['place'][$i], 
        'path' => $http_post_vars['path'][$i], 
        'occasion' => $http_post_vars['occasion'][$i], 
        'collection' => $http_post_vars['collection'][$i]);
      $images[] = $image;
    }
    return $images;
  }
}
?>

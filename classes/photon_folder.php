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
class photon_folder
{
  var $path;
  var $url;
  var $icon;
  var $name;
  var $request_url;

  function photon_folder($path)
  {
    global $config;

    if (is_dir($path))
    {
      $this->path = $path;
      $this->url = photon_url::get_folder_url($path);
      $this->icon = $config['images']['folder'];
      $this->name = basename($path);
      $this->request_url = photon_url::get_folder_request_url($this->url);
    }
  }

  function get_contents($path)
  {
    foreach (get_subfolders($path) as $folder)
      $contents[] = new photon_folder($folder);
    foreach (get_subfiles($path) as $file)
      if (photon_permissions::file_viewable($file))
        $contents[] = new photon_file($file);
    return $contents;
  }
}
?>

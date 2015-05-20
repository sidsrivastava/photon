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
class photon_filelist
{
  var $files;

  function photon_filelist($paths = '')
  {
    global $config;

    $this->files = array();

    if ($paths)
    {
      foreach ($paths as $path)
      {
        if (is_file($path))
          photon_filelist::add_file($path);
        if (is_dir($path))
          photon_filelist::add_folder($path);
      }
    }
  }

  /*  
    Recursively adds (viewable) file in a folder to the filelist
      $folder_path -- Path to the folder
      $filelist -- Array of files
      $config -- Configuration variables
  */
  function add_folder($folder_path)
  {
    global $config;

    $subfolders = get_subfolders($folder_path);
    $subfiles = get_subfiles($folder_path);

    for ($i = 0; $i < count($subfolders); $i++)
      photon_filelist::add_folder($subfolders[$i]);
    for ($i = 0; $i < count($subfiles); $i++)
      photon_filelist::add_file($subfiles[$i]);
  }

  /*  
    Adds a file to the filelist
      $file_path -- Path to the file
      $filelist -- Array of files
      $config -- Configuration variables
  */
  function add_file($file_path)
  {
    global $config;

    if (photon_permissions::file_viewable($file_path))
      $this->files[] = $file_path;
  }

  function get_files()
  {
    return $this->files;
  }
}

function get_files($paths)
{
  $filelist = new photon_filelist($paths);
  return $filelist->get_files();
}
?>

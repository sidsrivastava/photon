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
/* array functions */
function array_unique_compact($a)
{
  $temp_array = array_unique($a);
  $i = 0;
  foreach ($temp_array as $v) 
  {
    $new_array[$i] = $v;
    $i++;
  }
  return $new_array;
}

/* date functions */
function get_print_friendly_date($datestamp)
{
  /* Sample $datestamp: 2004:12:27 17:14:23 */
  $year = substr($datestamp, 0, 4);
  $month = substr($datestamp, 5, 2);
  $day = substr($datestamp, 8, 2);
  return date("F d, Y", mktime(0, 0, 0, $month, $day, $year));
}


/*
  Returns, as an array, all the folders in a parent directory
    $folder_path -- Path to the parent directory
*/  
function get_subfolders($folder_path)
{
  $folders = array();
  /* Open parent folder */
  if ($handle = @opendir($folder_path)) {
    /* Loop through all the files */
    while ($file = readdir($handle)) {
      /* Ignore hidden files */
      if (!preg_match("/^\./", $file)) {
         if (@is_dir("$folder_path/$file"))
           $folders[] = "$folder_path/$file";
      }
    }
    /* Close directory */
    closedir($handle);
  }
  sort($folders);
  return $folders;
}

/*
  Returns, as an array, all the files in a parent directory
    $folder_path -- Path to the parent directory
*/
function get_subfiles($folder_path)
{
  $files = array();
  /* Open current directory */
  if ($handle = @opendir($folder_path)) {
    /* Loop through all the files */
    while ($file = readdir($handle))
    {
      /* Ignore hidden files */
      if (!preg_match("/^\./", $file))
      {
        if (!@is_dir("$folder_path/$file"))
          $files[] = "$folder_path/$file";
      }
    }
    /* Close directory */
    closedir($handle);
  }
  sort($files);
  return $files;
}

/*
  Returns the file extension of a file
    $file_path -- Path to file
*/
function get_file_extension($file_path)
{
  if (!is_file($file_path))
    return NULL;
  return substr($file_path, -3);
}

/*
  Returns the name of the file (or folder) represented by a full path.
  For example, if the given path is "/home/sid/junk", the function returns "junk".
    $path -- Path to the file or folder
*/
function get_name_from_path($path)
{
  $path_fragments = explode("/", $path);
  return $path_fragments[count($path_fragments)- 1];
}
 
/*
  Returns the path to the parent directory of a file or folder
    $path -- Path to the file or folder
*/
Function get_parent_folder($path)
{
  /* NEEDS: This entire function is a hack. */
  $fragments = explode("/", $path);
  $parent_folder_path = "";
  for ($i = 0; $i < count($fragments) - 1; $i++) {
    $parent_folder_path .= $fragments[$i] . "/";
  }

  /* Remove the leading forward slash */
  $parent_folder_path = substr($parent_folder_path, 0, strlen($parent_folder_path) - 1);

  return $parent_folder_path;
}

/*
  Writes string contents to file
    $file_path -- Path to the file
    $contents -- String contents to be written
*/
function filedump($file_path, $contents)
{
  $file = fopen($file_path, "wb");
  fwrite($file, $contents, strlen($contents));
}

/* 
   Returns the HTML markup for a link.
*/
function html_link($path, $argument) 
{
  return "<a href=\"" . $path . "\">" . $argument . "</a>";
}

/*
  Returns the HTML markup for an image
*/
function html_img($src, $alt)
{
  return "<img src=\"" . $src . "\" alt=\"" . $alt . "\" />";
}
?>

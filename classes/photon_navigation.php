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
class photon_navigation
{
  function breadcrumb_trail($path)
  {
    global $config;

    $folder_fragments = explode("/", $path);
    $new_path = "";
    $content = "";
    $start_yet = FALSE;

    for ($i = 1; $i < count($folder_fragments); $i++) {
      $new_path .= "/" . $folder_fragments[$i];
      if ($new_path == $config['root'])
        $start_yet = TRUE;
      if ($start_yet)
        $content .= html_link($config['scripts']['index'] . "?path=" .  urlencode(stripslashes(photon_url::get_folder_url($new_path))), $folder_fragments[$i]) . " / ";
    }
    return $content;
  }

  function dropdown_siblings($path)
  {
    global $config;

    if ($path == $config['root'])
      return NULL;

    $siblings = get_subfolders(get_parent_folder($path));
    if (count($siblings) == 0)
      return NULL;

    $content = "";
 
    $content .= "<select name=\"siblings\" onChange=\"javascript:window.location.href='?path=' + options[selectedIndex].value;\">";

    for ($i = 0; $i < count($siblings); $i++) {
      $content .= "<option ";
      if ($siblings[$i] == $path)
        $content .= " selected ";
      $content .= " value=\"" . urlencode(stripslashes(photon_url::get_folder_url($siblings[$i]))) . "\">" . get_name_from_path($siblings[$i]) . "</option>";
    }
    $content .= "</select>";
    return $content;
  }
}
?>

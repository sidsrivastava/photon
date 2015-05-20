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
class photon_path
{
  function get_canonical_path($url)
  {
    global $config;

    $url =  urldecode(stripslashes($url));

    if (substr($url, 0, strlen($config['url']['root'])) != $config['url']['root'])
      return NULL;

    $path = $config['root'] . substr($url, strlen($config['url']['root']), strlen($url));

    if (is_dir($path) || is_file($path))
      return $path;

    return NULL;
  }

  function get_canonical_paths($urls)
  {
    global $config;

    $paths = array();
    if (is_array($urls))
      foreach ($urls as $url)
        $paths[] = photon_path::get_canonical_path($url);
    return $paths;
  }
}

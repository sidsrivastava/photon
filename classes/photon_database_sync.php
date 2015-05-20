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
class photon_database_sync
{
  function expunge_images()
  {
    global $config;
    global $mysql_database;

    $sql_query = "SELECT path FROM images GROUP BY path";
    $result = $mysql_database->query($sql_query);

    while ($row = mysql_fetch_array($result))
    {
      $image_paths[] = $row['path'];
    }

    foreach ($image_paths as $image_path)
    {
      if (!is_file($image_path))
      {
        $sql_query = "DELETE FROM images WHERE path='" . addslashes($image_path) . "'";
        $result = $mysql_database->query($sql_query);
      }
    }
  }

  function cache_hash_values()
  {
    global $mysql_database;

    $sql_query = "SELECT * FROM images";
    $result = $mysql_database->query($sql_query);

    while ($row = mysql_fetch_array($result))
    {
      $hash = md5_file($row['path']) or '';
      if ($hash != '')
        $mysql_database->update("images", "id", $row['id'], array('hash'=>$hash));
    }
  }


  /* update db with new filepaths */
  function sync_database()
  {
    global $config;
    global $mysql_database;

    photon_database_sync::cache_hash_values();

    $files = get_files(array($config['root']));
    foreach ($files as $file) {
      $hashes[] = md5_file($file);
    }
    $hash_table = array_combine($hashes, $files);

    foreach ($hash_table as $hash => $file) {
      $mysql_database->update("images", "hash", $hash, array('path'=>addslashes($file)));
    }
  }

  function sync_files()
  {
    global $config;
    global $mysql_database;
    global $log;

    $sql_query = "SELECT * FROM images";
    $result = $mysql_database->query($sql_query);

    while ($row = mysql_fetch_array($result))
    {
      $file_extension = substr($row['path'], -3);
      $new_path = dirname($row['path']) . '/' . photon_sanitation::sanitize_name($row['caption']) . ".$file_extension";

      if (is_file($row['path']) && $row['caption'] != '' && !is_file($new_path))
      {
        if (rename($row['path'], $new_path))
        {
          $mysql_database->update("images", "id", $row['id'], array('path'=>addslashes($new_path)));
          $log->push('notice','Successfully renamed ' . $row['path'] . ' to ' . $new_path);
        }
      }
    }
  }
}
?>

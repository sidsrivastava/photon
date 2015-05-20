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
class photon_collection
{
  var $name;
  var $output_path;
  var $output_url;

  function photon_collection($name = '')
  {
    global $config;

    $this->name = $name;
    $this->output_path = $config['output']['collections'] . "/" .  photon_sanitation::sanitize_name($this->name) . ".html";
    $this->output_url = $config['url']['collections'] . "/" .  photon_sanitation::sanitize_name($this->name) . ".html";
  }
}


function get_collections()
{
  global $config;
  global $mysql_database;

  $sql_query = "SELECT collection FROM images GROUP BY collection ORDER BY date_taken DESC";
  $result = $mysql_database->query($sql_query);

  while ($row = mysql_fetch_array($result))
  {
    if ($row['collection'] != "" && $row['collection'] != " ")
      $collection[] = new photon_collection($row['collection']);
  }

  return $collection;
}

/*
function get_previous_collection($name)
{
  global $config;
  global $mysql_database;

  $sql_query = "SELECT id FROM images WHERE collection='" . $name . "' GROUP BY collection ORDER BY id ASC";
  $row = mysql_fetch_array($mysql_database->query($sql_query));

  $id = $row['id'];
  $sql_query = "SELECT collection FROM images WHERE id < $id AND collection != '$name' GROUP BY collection ORDER BY id ASC LIMIT 1";
  $row = mysql_fetch_array($mysql_database->query($sql_query));

  if ($row)
    return new  photon_collection($row['collection']);
  return NULL;
}

function get_next_collection($name)
{
  global $config;
  global $mysql_database;

  $sql_query = "SELECT id FROM images WHERE collection='" . $name . "' GROUP BY collection ORDER BY id ASC";
  $row = mysql_fetch_array($mysql_database->query($sql_query));

  $id = $row['id'];
  $sql_query = "SELECT collection FROM images WHERE id > $id AND collection != '$name' GROUP BY collection ORDER BY id ASC LIMIT 1";
  $row = mysql_fetch_array($mysql_database->query($sql_query));

  if ($row)
    return new  photon_collection($row['collection']);
  return NULL;
}
*/

function get_collection_from_occasion($occasion_name)
{
  global $mysql_database;

    $sql_query = "SELECT collection FROM images WHERE occasion='" . addslashes($occasion_name) . "'";
    $row = mysql_fetch_array($mysql_database->query($sql_query));
    return new photon_collection($row['collection']);
} 
?>

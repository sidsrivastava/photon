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
class photon_occasion
{
  var $name;
  var $output_path;
  var $output_url;
  var $collection;

  function photon_occasion($name = '')
  {
    global $config;

    $this->name = $name;
    $this->output_path = $config['output']['occasions'] . "/" . photon_sanitation::sanitize_name($this->name) . ".html";
    $this->output_url = $config['url']['occasions'] . "/" . photon_sanitation::sanitize_name($this->name) . ".html";
    $this->collection = get_collection_from_occasion($this->name);
  }
}

function get_occasions()
{
  global $config;
  global $mysql_database;

  $sql_query = "SELECT occasion FROM images GROUP BY occasion ORDER BY date_taken DESC";
  $result = $mysql_database->query($sql_query);

  while ($row = mysql_fetch_array($result))
    $occasions[] = new photon_occasion($row['occasion']);

  return $occasions;
}

function get_occasions_by_collection($collection_name)
{
  global $config;
  global $mysql_database;

  $sql_query = "SELECT occasion FROM images WHERE collection='" . $collection_name . "' GROUP BY occasion ORDER BY date_taken DESC";
  $result = $mysql_database->query($sql_query);

  while ($row = mysql_fetch_array($result))
    $occasions[] = new photon_occasion($row['occasion']);

  return $occasions;
}
?>

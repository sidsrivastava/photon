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
class photon_tag
{
  var $name;
  var $output_path;
  var $output_url;

  function photon_tag($name = '')
  {
    global $config;

    $this->name = $name;
    $this->output_path = $config['output']['tags'] . "/" . photon_sanitation::sanitize_name($this->name) . ".html";
    $this->output_url = $config['url']['tags'] . "/" . photon_sanitation::sanitize_name($this->name) . ".html";
  }
}

function clean_tags_string($tags_string)
{
  $tags_string = str_replace(", ", ",", $tags_string);
  $tags_string = str_replace(" ,", ",", $tags_string);
  $tags_string = str_replace(" , ", ",", $tags_string);

  /* Strip multiple white spaces in each tag name */
  $tags_string = preg_replace('/\s\s+/', ' ', $tags_string);
  $tags_string = trim($tags_string);
  return $tags_string;
}
  
/* Reference: http://www.snook.ca/archives/000385.php */
function get_tags()
{
  global $config;
  global $mysql_database;

  $tag_names = array();
  $tags = array();
  $sql_query = "SELECT tags FROM images ORDER BY date_taken DESC";
  $result = $mysql_database->query($sql_query);

  while($fetched_tags = mysql_fetch_array($result))
  {
    $tags_string = clean_tags_string($fetched_tags[0]);
    if ($tags_string != "" && $tags_string != " " )
      $tag_names = array_merge($tag_names, explode($config['tags_delimiter'], $tags_string));
  }

  $tag_names = array_unique_compact($tag_names);

  for ($i = 0; $i < count($tag_names); $i++)
    $tags[] = new photon_tag($tag_names[$i]);

  return $tags;
}

function get_new_tags($tags_string = '')
{
  global $config;
  global $mysql_database;

  $tags_string = clean_tags_string($tags_string);
  $tag_names = explode($config['tags_delimiter'], $tags_string);
  $tag_names = array_unique_compact($tag_names);

  for ($i = 0; $i < count($tag_names); $i++)
  {
    if ($tag_names[$i] != "" && $tag_names[$i] != " " )
      $tags[$i] = new photon_tag($tag_names[$i]);
  }
  return $tags;
}
?>

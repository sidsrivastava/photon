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
class photon_person
{
  var $name;
  var $output_path;
  var $output_url;

  function photon_person($name = '')
  {
    global $config;

    $this->name = $name;
    $this->output_path = $config['output']['persons'] . "/" . photon_sanitation::sanitize_name($this->name) . ".html";
    $this->output_url = $config['url']['persons'] . "/" . photon_sanitation::sanitize_name($this->name) . ".html";
  }
}


function clean_persons_string($persons_string)
{
  $persons_string = str_replace(", ", ",", $persons_string);
  $persons_string = str_replace(" ,", ",", $persons_string);
  $persons_string = str_replace(" , ", ",", $persons_string);

  /* Strip multiple white spaces in each person name */
  $persons_string = preg_replace('/\s\s+/', ' ', $persons_string);
  $persons_string = trim($persons_string);
  return $persons_string;
}
  
function get_persons($sort_by_alpha = true)
{
  global $config;
  global $mysql_database;

  $person_names = array();
  $persons = array();
  $sql_query = "SELECT persons FROM images  ORDER BY date_taken DESC";
  $result = $mysql_database->query($sql_query);

  while($fetched_persons = mysql_fetch_array($result))
  {
    $persons_string = clean_persons_string($fetched_persons[0]);
    if ($persons_string != "" && $persons_string != " " )
      $person_names = array_merge($person_names, explode($config['persons_delimiter'], $persons_string));
  }

  $person_names = array_unique_compact($person_names);

  for ($i = 0; $i < count($person_names); $i++) {
    if ($person_names[$i] != $config['unknown_person'] && !in_array($person_names[$i], $config['unlinked_persons']))
      $persons[] = new photon_person($person_names[$i]);
  }

  if ($sort_by_alpha)
    sort($persons);
  return $persons;
}

function get_new_persons($persons_string = '')
{
  global $config;
  global $mysql_database;

  $persons_string = clean_persons_string($persons_string);
  $person_names = explode($config['persons_delimiter'], $persons_string);
  $person_names = array_unique_compact($person_names);

  for ($i = 0; $i < count($person_names); $i++)
  {
    if ($person_names[$i] != "" && $person_names[$i] != " " )
      $persons[$i] = new photon_person($person_names[$i]);
  }
  return $persons;
}
?>

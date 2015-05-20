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
class photon_image
{
  var $id;
  var $caption;
  var $date_taken;
  var $place;

  var $path;
  var $url;
  var $output_path;
  var $output_url;

  var $tags;  
  var $persons;
  var $occasion;
  var $collection;
  var $thumbnail;
  var $snapshot;


  function photon_image($path = '')
  {
    global $config;

    $this->caption = '';
    $this->place = '';

    $this->path = $path;
    $this->url = photon_image::get_image_url();

    $this->tags = get_new_tags();
    $this->persons = get_new_persons();

    $this->occasion = new photon_occasion();
    $this->collection = new photon_collection();
    $this->thumbnail = new photon_thumbnail($path);
    $this->snapshot = new photon_thumbnail($path, .5);

    $this->grep_database();

    $this->output_path = $config['output']['images'] . "/" .  $this->id . ".html";
    $this->output_url = $config['url']['images'] . "/" .  $this->id . ".html";

    if (!$this->date_taken && $path)
    {
      $exif_metadata = get_EXIF_JPEG($path);
      $this->date_taken = $exif_metadata[0][306]['Data'][0];
    }

  }

  function get_tag_names()
  {
    global $config;

    $tag_names = "";
    for ($i = 0; $i < count($this->tags); $i++)
    {
      $tag = $this->tags[$i];
      $tag_names .= $tag->name;
      if ($i < count($this->tags) - 1)
        $tag_names .= $config['tags_delimiter'];
    }
    return $tag_names;
  }

  function get_person_names()
  {
    global $config;

    $person_names = "";
    for ($i = 0; $i < count($this->persons); $i++)
    {
      $person = $this->persons[$i];
      $person_names .= $person->name;
      if ($i < count($this->persons) - 1)
        $person_names .= $config['persons_delimiter'];
    }
    return $person_names;
  }

  function grep_database()
  {
    global $mysql_database;
    global $config;

    $record = mysql_fetch_array($mysql_database->select("images", "path", $this->path));
    if ($record)
    {
      $this->caption = $record['caption'];
      $this->date_taken = $record['date_taken'];
      $this->place = $record['place'];

      $this->tags = get_new_tags($record['tags']);
      $this->persons = get_new_persons($record['persons']);

      $this->occasion = new photon_occasion($record['occasion']);
      $this->collection = new photon_collection($record['collection']);
      $this->id = $record['id'];
    }
  }

  function commit_to_database()
  {
    global $mysql_database;
    $image_array = $this->get_array();

    if (mysql_fetch_array($mysql_database->select("images", "path", stripslashes($image_array['path']))) == NULL)
      $mysql_database->insert("images", $image_array);
    else
      $mysql_database->update("images", "path", $image_array['path'], $image_array);
  }

  function grep_array($image_array)
  {
    $this->caption = $image_array['caption'];
    $this->date_taken = $image_array['date_taken'];
    $this->persons = clean_persons_string($image_array['persons']);
    $this->tags = clean_tags_string($image_array['tags']);
    $this->place = $image_array['place'];
    $this->path = $image_array['path'];
    $this->occasion = $image_array['occasion'];
    $this->collection = $image_array['collection'];
  }

  function get_array()
  {
    $image_array = array();
    $image_array['caption'] = $this->caption;
    $image_array['date_taken'] = $this->date_taken;
    $image_array['persons'] = $this->persons;
    $image_array['tags'] = $this->tags;
    $image_array['place'] = $this->place;
    $image_array['path'] = $this->path;
    $image_array['occasion'] = $this->occasion;
    $image_array['collection'] = $this->collection;
    return $image_array;
  }

  function get_image_url()
  {
    global $config;

    $path = $this->path;

    /* Make sure the path points to an existing file or folder */
    if (!is_dir($path) && !is_file($path))
      return NULL;

    /* Make sure the path falls under the base folder containing the media files */
    if (substr($path, 0, strlen($config['root'])) != $config['root'])
      return NULL;

    $new_path = $config['url']['root'] . substr($path, strlen($config['root']), strlen($path) - 1);

    /* Encode the path */
    return urlencode(stripslashes($new_path));
  }
}

function get_random_image_by_collection($collection_name)
{
  $images = get_images_by_collection($collection_name);
  return $images[rand(0, count($images) - 1)];
}

function get_random_image_by_tag($tag_name)
{
  $images = get_images_by_tag($tag_name);
  return $images[rand(0, count($images) - 1)];
}

function get_images_by_occassion($occasion_name)
{
  global $config;
  global $mysql_database;

  $images = array();

  $sql_query = "SELECT * FROM images WHERE OCCASION = '" . addslashes($occasion_name) . "'  ORDER BY date_taken DESC";
  $result = $mysql_database->query($sql_query);

  while ($row = mysql_fetch_array($result))
    $images[] = new photon_image($row['path']);
  return $images;
}

function get_images_by_collection($collection_name)
{
  global $config;
  global $mysql_database;

  $images = array();

  $sql_query = "SELECT * FROM images  WHERE COLLECTION = '" . addslashes($collection_name) . "'  ORDER BY date_taken DESC";
  $result = $mysql_database->query($sql_query);

  while ($row = mysql_fetch_array($result))
    $images[] = new photon_image($row['path']);
  return $images;
}

function get_limited_images_by_occassion($occasion_name)
{
  global $config;

  $images = get_images_by_occassion($occasion_name);

  if (count($images) < 1)
    return $images;

  $max = ceil(.5 * count($images));
  if ($max > $config['max_images_to_display_per_occasion'])
    $max = $config['max_images_to_display_per_occasion'];
  if (count($images) <= $config['max_images_to_display_per_occasion'])
    $max = count($images);
  
  $chunked_images = array_chunk($images, $max);
  return $chunked_images[0];
}


function get_images_by_tag($tag_name)
{
  global $config;
  global $mysql_database;

  $images = array();
  $sql_query = "SELECT * FROM images WHERE tags LIKE '%" . addslashes($config['tags_delimiter'] . $tag_name . $config['tags_delimiter']) . "%' OR tags LIKE '" . addslashes($tag_name . $config['tags_delimiter']) . "%' OR tags LIKE '%" . addslashes($config['tags_delimiter'] . $tag_name) . "' OR tags = '" . addslashes($tag_name) . "'  ORDER BY date_taken DESC";

  $result = $mysql_database->query($sql_query);

  while ($row = mysql_fetch_array($result))
    $images[] = new photon_image($row['path']);
  return $images;
}

function get_images_by_person($person_name)
{
  global $config;
  global $mysql_database;

  $images = array();
  $sql_query = "SELECT * FROM images WHERE persons LIKE '%" . addslashes($config['persons_delimiter'] . $person_name . $config['persons_delimiter']) . "%' OR persons LIKE '" . addslashes($person_name . $config['persons_delimiter']) . "%' OR persons LIKE '%" . addslashes($config['persons_delimiter'] . $person_name) . "' OR persons = '" . addslashes($person_name) . "'  ORDER BY date_taken DESC";

  $result = $mysql_database->query($sql_query);

  while ($row = mysql_fetch_array($result))
    $images[] = new photon_image($row['path']);
  return $images;
}


function get_images()
{
  global $config;
  global $mysql_database;

  $sql_query = "SELECT path FROM images GROUP BY path  ORDER BY date_taken DESC";
  $result = $mysql_database->query($sql_query);

  while ($row = mysql_fetch_array($result))
    $images[] = new photon_image($row['path']);

  return $images;
}


function get_image_id_from_path($path)
{
  global $mysql_database;
  global $config;

  $record = mysql_fetch_array($mysql_database->select("images", "path", $path));
  if ($record)
  {
    return $record['id'];
  }
  return -1;
}
?>

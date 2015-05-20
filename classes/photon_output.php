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
class photon_output 
{
  function output_occasions()
  {
    global $config;
    global $mysql_database;
    global $log;

    $occasions = get_occasions();

    for ($i = 0; $i < count($occasions); $i++)
    {
      $log->push('notice','Generating occasion ' . $i . ' of ' . count($occasions));

      $occasion = $occasions[$i];
      $previous_occasion = NULL; $next_occasion = NULL;
      if ($i > 0 && $i < count($occasions))
        $previous_occasion = $occasions[$i - 1];
      if ($i >= 0 && $i < count($occasions) - 1)
        $next_occasion = $occasions[$i + 1];

      $images = get_images_by_occassion($occasion->name);

      if ($images)
        $collection = $images[0]->collection;

      $template = new PHPTAL($config['templates']['occasion']);
      $template->set("css", $config['url']['css']);
      $template->set("left", $config['images']['left']);
      $template->set("up", $config['images']['up']);
      $template->set("right", $config['images']['right']);
      $template->set("page", $config['templates']['page']);

      $template->set("previous_occasion", $previous_occasion);
      $template->set("next_occasion", $next_occasion);
      $template->set("occasion", $occasion);
      $template->set("images", $images);
      $res = $template->execute();
      filedump($occasion->output_path, $res);
    }
  }

  function output_collections()
  {
    global $config;
    global $mysql_database;

    $collections = get_collections();

    for ($i = 0; $i < count($collections); $i++)
    {
      $collection = $collections[$i];
      $previous_collection = NULL; $next_collection = NULL;
      if ($i > 0 && $i < count($collections))
        $previous_collection = $collections[$i - 1];
      if ($i >= 0 && $i < count($collections) - 1)
        $next_collection = $collections[$i + 1];
      $occasions = get_occasions_by_collection($collection->name);

      $template = new PHPTAL($config['templates']['collection']);
      $template->set("css", $config['url']['css']);
      $template->set("left", $config['images']['left']);
      $template->set("up", $config['images']['up']);
      $template->set("right", $config['images']['right']);
      $template->set("album_index", $config['url']['album_index']);
      $template->set("page", $config['templates']['page']);
      $template->set("background_colors", $config['background_colors']);
      $template->set("max_images_to_display_per_occasion", $config['max_images_to_display_per_occasion']);
  
      $template->set("collection", $collection);
      $template->set("previous_collection", $previous_collection);
      $template->set("next_collection", $next_collection);
      $template->set("occasions", $occasions);

      $res = $template->execute();

      filedump($collection->output_path, $res);
    }
  }

  function output_tags()
  {
    global $config;
    global $mysql_database;

    $tags = get_tags();

    for ($i = 0; $i < count($tags); $i++)
    {
      $tag = $tags[$i];
      $previous_tag = NULL; $next_tag = NULL;
      if ($i > 0 && $i < count($tags))  
        $previous_tag = $tags[$i - 1];
      if ($i >= 0 && $i < count($tags) - 1)
        $next_tag = $tags[$i + 1];
      $images = get_images_by_tag($tag->name);

      $template = new PHPTAL($config['templates']['tag']);
      $template->set("css", $config['url']['css']);
      $template->set("left", $config['images']['left']);
      $template->set("up", $config['images']['up']);
      $template->set("right", $config['images']['right']);
      $template->set("tags_index", $config['url']['tags_index']);
      $template->set("page", $config['templates']['page']);

      $template->set("tag", $tag);
      $template->set("previous_tag", $previous_tag);
      $template->set("next_tag", $next_tag);
      $template->set("images", $images);

      $res = $template->execute();
      filedump($tag->output_path, $res);
    }
  }


  function output_persons()
  {
    global $config;
    global $mysql_database;

    $persons = get_persons();

    for ($i = 0; $i < count($persons); $i++)
    {
      $person = $persons[$i];
      $previous_person = NULL; $next_person = NULL;
      if ($i > 0 && $i < count($persons))
        $previous_person = $persons[$i - 1];
      if ($i >= 0 && $i < count($persons) - 1)
        $next_person = $persons[$i + 1];
      $images = get_images_by_person($person->name);
  
      $template = new PHPTAL($config['templates']['person']);
      $template->set("css", $config['url']['css']);
      $template->set("left", $config['images']['left']);
      $template->set("up", $config['images']['up']);
      $template->set("right", $config['images']['right']);
      $template->set("persons_index", $config['url']['persons_index']);
      $template->set("page", $config['templates']['page']);

      $template->set("person", $person);
      $template->set("previous_person", $previous_person);
      $template->set("next_person", $next_person);
      $template->set("images", $images);  

      $res = $template->execute();
      filedump($person->output_path, $res);
    }
  }

  function output_images()
  {
    global $config;
    global $mysql_database;
  
    $images = get_images();

    for ($i = 0; $i < count($images); $i++)  
    {  
      $image = $images[$i];
      $previous_image = NULL; $next_image = NULL;
      if ($i > 0 && $i < count($images))
        $previous_image = $images[$i - 1];
      if ($i >= 0 && $i < count($images) - 1)
        $next_image = $images[$i + 1];
  
      $template = new PHPTAL($config['templates']['image']);
  
      $template->set("css", $config['url']['css']);
      $template->set("left", $config['images']['left']);
      $template->set("up", $config['images']['up']);  
      $template->set("right", $config['images']['right']);
      $template->set("unlinked_persons", $config['unlinked_persons']);
      $template->set("unknown_person", $config['unknown_person']);
      $template->set("page", $config['templates']['page']);

      $template->set("image", $image);
      $template->set("previous_image", $previous_image);
      $template->set("next_image", $next_image);

      $res = $template->execute();

      filedump($image->output_path, $res);
    }
  }

  function output_album_index()
  {
    global $config;
    global $mysql_database;

    $collections = get_collections();
    $tags = get_tags();

    $persons = get_persons();

    $template = new PHPTAL($config['templates']['album_index']);
    $template->set("css", $config['url']['css']);
    $template->set("page", $config['templates']['page']);
    $template->set("collections", $collections);
    $template->set("tags_index", $config['url']['tags_index']);
    $template->set("persons_index", $config['url']['persons_index']);
    $template->set("page", $config['templates']['page']);
    $template->set("background_colors", $config['background_colors']);
    $res = $template->execute();

    filedump($config['output']['index'], $res);
  }

  function output_persons_index()
  {
    global $config;
    global $mysql_database;

    $persons = get_persons(true);

    $template = new PHPTAL($config['templates']['persons_index']);
    $template->set("css", $config['url']['css']);
    $template->set("left", $config['images']['left']);
    $template->set("up", $config['images']['up']);
    $template->set("right", $config['images']['right']);
    $template->set("album_index", $config['url']['album_index']);
    $template->set("persons", $persons);
    $template->set("page", $config['templates']['page']);
    $res = $template->execute();

    filedump($config['output']['persons_index'], $res);
  }

  function output_tags_index()
  {
    global $config;
    global $mysql_database;

    $tags = get_tags();

    $template = new PHPTAL($config['templates']['tags_index']);
    $template->set("css", $config['url']['css']);
    $template->set("left", $config['images']['left']);
    $template->set("up", $config['images']['up']);
    $template->set("right", $config['images']['right']);
    $template->set("album_index", $config['url']['album_index']);
    $template->set("background_colors", $config['background_colors']);
    $template->set("tags", $tags);
    $template->set("page", $config['templates']['page']);
    $res = $template->execute();

    filedump($config['output']['tags_index'], $res);
  }


  function output_collections_index()
  {
    global $config;
    global $mysql_database;

    $collections = get_collections();

    $template = new PHPTAL($config['templates']['collections_index']);
    $template->set("css", $config['url']['css']);
    $template->set("left", $config['images']['left']);
    $template->set("up", $config['images']['up']);
    $template->set("right", $config['images']['right']);
    $template->set("album_index", $config['url']['album_index']);
    $template->set("collections", $collections);
    $template->set("page", $config['templates']['page']);
    $res = $template->execute();

    filedump($config['output']['collections_index'], $res);
  }
}
?>

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
  require_once("classes/photon_filelist.php");
  require_once("classes/photon_thumbnail.php");
  require_once("classes/photon_mysql_database.php");
  require_once("classes/photon_image.php");
  require_once("classes/photon_occasion.php");
  require_once("classes/photon_collection.php");
  require_once("classes/photon_person.php");
  require_once("classes/photon_file.php");
  require_once("classes/photon_folder.php");
  require_once("classes/photon_tag.php");
  require_once("classes/photon_log.php");

  require_once("classes/photon_sanitation.php");
  require_once("classes/photon_permissions.php");
  require_once("classes/photon_path.php");
  require_once("classes/photon_url.php");
  require_once("classes/photon_output.php");
  require_once("classes/photon_form.php");
  require_once("classes/photon_navigation.php");
  require_once("classes/photon_database_sync.php");
  require_once("classes/photon_utilities.php");

  require_once("libraries/PHPTAL/PHPTAL.php");
  require_once("libraries/php_jpeg_metadata_toolkit/JPEG.php");
  require_once("libraries/php_jpeg_metadata_toolkit/EXIF.php");
  require_once("libraries/php_jpeg_metadata_toolkit/JFIF.php");
  require_once("libraries/php_jpeg_metadata_toolkit/Photoshop_IRB.php");
  require_once("libraries/php_jpeg_metadata_toolkit/PictureInfo.php");
  require_once("libraries/php_jpeg_metadata_toolkit/XMP.php");

  require_once("config.php");
?>
<?php
  /*  Get vars and post vars */
  $path = photon_path::get_canonical_path($HTTP_GET_VARS['path']) or $path = $config['root'];
  $submit = $HTTP_POST_VARS['submit'];
  $updatedb = $HTTP_POST_VARS['updatedb'];
  $action = $HTTP_GET_VARS['action'];

  /* Global vars and classes */
  $current_path = $path;
  $mysql_database = new photon_mysql_database($config['mysql']['username'], $config['mysql']['password'], $config['mysql']['database_name'], $config['mysql']['server']);
  $log = new photon_log();

  $page_template = new PHPTAL($config['templates']['script_page']);
  $page_template->set("breadcrumb_trail", photon_navigation::breadcrumb_trail($path, $config));
  $page_template->set("dropdown_siblings", photon_navigation::dropdown_siblings($path, $config));
  $page_template->set("script_index", $config['script']);
  $page_template->set("album_index", $config['url']['album_index']);
  $page_template->set("edit_images", "");
  $page_template->set("folder", "");
  $page_template->set("message", "");
?>

<?php
  switch ($action)
  {
    case "output":
    {
      photon_database_sync::expunge_images();
      photon_output::output_album_index();
      photon_output::output_occasions();
      photon_output::output_collections();
      photon_output::output_tags();
      photon_output::output_tags_index();
      photon_output::output_persons();
      photon_output::output_images();
      photon_output::output_persons_index();
      photon_output::output_collections_index();
      break;
    }
    case "sync_files":
    {
      photon_database_sync::sync_files();
      break;
    }
    case "sync_database":
    {
      photon_database_sync::sync_database();
      break;
    }
  }
?>

<?php
  if (!$submit && is_dir($path))
  {
    $folder_template = new PHPTAL($config['templates']['folder']);
    $folder_template->set("contents", photon_folder::get_contents($path));
    $page_template->set("folder", $folder_template->execute());
  }

  if ($submit && !$updatedb)
  {
    $paths = photon_path::get_canonical_paths($HTTP_POST_VARS['path']);
    $file_paths = get_files($paths);

    foreach ($file_paths as $path) {
      $image = new photon_image($path);
      $images[] = $image;
    }

    $edit_images_template = new PHPTAL($config['templates']['edit_images']);
    $edit_images_template->set("images", $images);
    $page_template->set("edit_images", $edit_images_template->execute());   
  }

  if ($submit && $updatedb)
  {
    $posted_images = photon_form::extract_images_from_postvars($HTTP_POST_VARS);
    photon_form::process_images($posted_images, $config, $mysql_database);

    $folder_template = new PHPTAL($config['templates']['folder']);
    $folder_template->set("contents", photon_folder::get_contents($path));
    $page_template->set("folder", $folder_template->execute()); 
  }
?>

<?php
  $page_template_res = $page_template->execute();
  echo $page_template_res;
?>

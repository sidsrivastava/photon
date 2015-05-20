<?php
  $config['root'] = '/home/siddharth/media/photos';
  $config['url']['root'] = '/photos';
  $config['output']['html'] = '/home/siddharth/code/projects/photon/demo/gallery';
  $config['url']['html'] = '/demo/gallery';

  $config['url']['css'] = '/demo/home/css/style.css';
  $config['images']['left'] = "/demo/home/images/left.png";
  $config['images']['up'] = "/demo/home/images/up.png";
  $config['images']['right'] = "/demo/home/images/right.png";

  $config['mysql']['username'] = 'root';
  $config['mysql']['password'] = '';
  $config['mysql']['database_name'] = 'photon'; 
  $config['mysql']['server'] = 'localhost';

  /* editing options */
  $config['allowable_file_types'] = array("jpg");
  $config['unlinked_persons'] = array("Sid");
  $config['max_images_to_display_per_occasion'] = 6;
  $config['background_colors'] = array('#bbccdd','#ccddaa','#ddccdd','#bbbbcc','#bbccbb','#ffff99');
  $config['unknown_person_char'] = "?";
  $config['tags_delimiter'] = ",";
  $config['persons_delimiter'] = ",";
  $config['unknown_person'] = '?';

  /* templates */
  $config['templates']['folder'] = "resources/templates/photon/folder.html";
  $config['templates']['edit_images'] = "resources/templates/photon/edit_images.html";
  $config['templates']['script_page'] = "resources/templates/photon/page.html";
  $config['templates']['page'] = "resources/templates/output/page.html";
  $config['templates']['occasion'] = "resources/templates/output/occasion.html";
  $config['templates']['collection'] = "resources/templates/output/collection.html";
  $config['templates']['collections_index'] = "resources/templates/output/collections_index.html";
  $config['templates']['person'] = "resources/templates/output/person.html";
  $config['templates']['persons_index'] = "resources/templates/output/persons_index.html";
  $config['templates']['tag'] = "resources/templates/output/tag.html";
  $config['templates']['tags_index'] = "resources/templates/output/tags_index.html";
  $config['templates']['image'] = "resources/templates/output/image.html";
  $config['templates']['album_index'] = "resources/templates/output/gallery_index.html";

  /* output paths */
  $config['output']['occasions'] = $config['output']['html'] . '/occasions';
  $config['output']['collections'] = $config['output']['html'] . '/collections';
  $config['output']['collections_index'] = $config['output']['html'] . '/collections/index.html';
  $config['output']['persons'] = $config['output']['html'] . '/people';
  $config['output']['persons_index'] = $config['output']['html'] . '/people/index.html';
  $config['output']['tags'] = $config['output']['html'] . '/tags';
  $config['output']['tags_index'] = $config['output']['html'] . '/tags/index.html';
  $config['output']['images'] = $config['output']['html'] . '/images';
  $config['output']['thumbnails'] = $config['output']['html'] . '/thumbnails';
  $config['output']['index'] = $config['output']['html'] . '/index.html';

  /* URLs */
  $config['url']['album_index'] = $config['url']['html'] . '/index.html';
  $config['url']['occasions'] = $config['url']['html'] . '/occasions';
  $config['url']['collections'] = $config['url']['html'] . '/collections';
  $config['url']['collections_index'] = $config['url']['collections'] . '/index.html';
  $config['url']['persons'] = $config['url']['html'] . '/people';
  $config['url']['persons_index'] = $config['url']['persons'] . '/index.html';
  $config['url']['tags'] = $config['url']['html'] . '/tags';
  $config['url']['tags_index'] = $config['url']['tags'] . '/index.html';
  $config['url']['images'] = $config['url']['html'] . '/images';
  $config['url']['thumbnails'] = $config['url']['html'] . '/thumbnails';

  /* internal script options */
  $config['script'] = 'index.php';
  $config['images']['folder'] = "/photon/resources/images/folder.gif";
  $config['images']['file'] = "/photon/resources/images/file.gif";
?>

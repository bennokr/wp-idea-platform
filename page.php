<div><a href="?page_id=<?php echo the_ID(); ?>">Terug naar overzicht</a></div>
<h1><?php echo $idea->title; ?></h1>
<h3>Door <?php echo $idea->author_name; ?>, een <?php echo $idea->group; ?>,ingezonden op <?php echo $idea->date; ?></h3>
<div>Stemmen: <?php echo $idea->votes; ?></div>
<p><?php echo nl2br($idea->description); ?></p>
<ul>
  <li>Data bron: <?php echo $idea->data_source; ?></li>
  <li>Data open: <?php echo $idea->data_open; ?></li>
  <li>Data locatie: <?php echo $idea->data_location; ?></li>
</ul>

[Reacties]
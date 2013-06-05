<script type="text/javascript">
  jQuery(document).ready(function() {
    // Submit form my ajax
    jQuery("#idpl_vote").on( "submit", function( event ) {
      jQuery(this).hide();
      event.preventDefault();
      jQuery.post(jQuery(this).attr("action"), jQuery(this).serialize(), function(data) {
        jQuery('#idpl_votes').text(data.votes);
      });
      return false;
    });

    jQuery("#idpl_comment").on( "submit", function( event ) {
      jQuery(this).hide();
      event.preventDefault();
      jQuery.post(jQuery(this).attr("action"), jQuery(this).serialize(), function(data) {
        jQuery("#idpl_comments").append(jQuery("<li>Door "+data.author_name+"<br/><p>"+data.description+"</p></li>"));
      });
      return false;
    });
  });
</script>
<div><a href="?page_id=<?php echo the_ID(); ?>">Terug naar overzicht</a></div>
<h1><?php echo $idea->title; ?></h1>
<h3>Door <?php echo $idea->author_name; ?>, een <?php echo $idea->group; ?>, ingezonden op <?php echo $idea->date; ?></h3>
<span id="idpl_votes"><?php echo $idea->votes; ?></span> stemmen,
<form action="<?php echo $this->submit_url;?>" id="idpl_vote">
  <input type="hidden" name="action" value="<?php echo $this->ajax_names['vote']; ?>"/>
  <input type="hidden" name="id" value="<?php echo $idea->id; ?>"/>
  <input type="submit" value="stem">
</form>
<p><?php echo nl2br($idea->description); ?></p>
<ul>
  <li>Data bron: <?php echo $idea->data_source; ?></li>
  <li>Data open: <?php echo $idea->data_open; ?></li>
  <li>Data locatie: <?php echo $idea->data_location; ?></li>
</ul>

<form action="<?php echo $this->submit_url;?>" id="idpl_comment">
  <fieldset>
    <legend>Reageer</legend>
    <input type="hidden" name="action" value="<?php echo $this->ajax_names['comment']; ?>"/>
    <input type="hidden" name="idea_id" value="<?php echo $idea->id; ?>"/>
    <input type="text" name="author_name" placeholder="Naam" required="required"><br/>
    <input type="text" name="author_mail" placeholder="Mailadres" required="required"><br/>
    <select required="required" name="author_group">
      <option disabled selected="yes">Rol</option>
      <option>1</option>
      <option>2</option>
      <option>3</option>
    </select><br />
    <textarea name="description" value="" placeholder="Reactie"></textarea><br />
    <input type="submit" id="idpl_form_btn" value="Publiceer"/>
  </fieldset>
</form>

<h2>Reacties</h2>
<ul id="idpl_comments">
<?php foreach ($comments as $comment) { ?>
  <li>Door <?php echo $comment->author_name; ?><br /><p><?php echo $comment->description; ?></p></li>
<?php } ?>
</ul>
This is the idea platform!
<script type="text/javascript">
  jQuery(document).ready(function() {
    // Submit form my ajax
    jQuery("#idpl_form").on( "submit", function( event ) {
      jQuery(this).hide();
      event.preventDefault();
      jQuery.post(jQuery(this).attr("action"), jQuery(this).serialize(), function(data) {
        // TODO: confirmation message
        window.location.href = '?<?php echo $_SERVER['QUERY_STRING'] ?>&idea_id=' + data.idea_id;
      });
      return false;
    });
  });
</script>
<form action="<?php echo $submit_url;?>" id="idpl_form">
  <fieldset>
    <legend>Deel jouw App-idee</legend>
    <input type="hidden" name="action" value="<?php echo $this->add_idea_func; ?>"/>
    <input type="text" name="author_name" placeholder="Naam" required="required"><br/>
    <input type="text" name="author_mail" placeholder="Mailadres" required="required"><br/>
    <select required="required" name="author_group">
      <option disabled selected="yes">Rol</option>
      <option>1</option>
      <option>2</option>
      <option>3</option>
    </select><br />
    <input type="text" name="title" placeholder="Titel" required="required"><br />
    <textarea name="description" value="" placeholder="Beschrijving"></textarea><br />
    <input type="text" name="files" placeholder="Bijlagen"><br />
    <input type="text" name="data_source" placeholder="Welke data kan gebruikt worden?"><br />
    <label>Is de data open?</label><input type="checkbox" name="data_open"><br />
    <input type="text" name="data_location" placeholder="Waar kan ik de data vinden?"><br />
    <input type="submit" id="idpl_form_btn" value="Publiceer"/>
  </fieldset>
</form>
<ul>
<?php foreach ($ideas as $idea) { ?>
  <li><a href="?<?php echo $_SERVER['QUERY_STRING'] . "&idea_id=" . $idea->id; ?>"><?php echo $idea->title; ?></a> door <?php echo $idea->author_name; ?></li>
<?php } ?>
</ul>
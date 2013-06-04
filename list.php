This is the idea platform!
<script type="text/javascript">
  jQuery(document).ready(function() {
    // Submit form my ajax
    jQuery("#idpl_form").on( "submit", function( event ) {
      event.preventDefault();
      jQuery.post(jQuery(this).attr("action"), jQuery(this).serializeArray(), function(data) {
        console.log(data);
        // TODO: redirect to newly formed idea page
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
    <select required="required">
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
  <li><?php echo $idea->title; ?> door <?php echo $idea->author_name; ?></li>
<?php } ?>
</ul>
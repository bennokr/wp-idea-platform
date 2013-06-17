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
    jQuery('#sorter').change(function() {
      window.location.href = '?<?php echo $_SERVER['QUERY_STRING'] ?>&sort=' + this.value;
    });
    jQuery('#filter').change(function() {
      window.location.href = '?<?php echo $_SERVER['QUERY_STRING'] ?>&filter=' + this.value;
    });

    jQuery('#idpl_form').hide();
  });
</script>
<div style="text-align:center; width:100%; margin-top:2em;">
  <a href="javascript:jQuery('#idpl_form').show();" class="idpl_button">Klik hier om jouw app-idee te delen!</a>
</div>
<form action="<?php echo $this->submit_url;?>" id="idpl_form">
  <fieldset>
    <input type="hidden" name="action" value="<?php echo $this->ajax_names['add_idea']; ?>"/>
    <input type="text" name="author_name" placeholder="Naam" required="required"><br/>
    <input type="text" name="author_mail" placeholder="Mailadres" required="required"><br/>
    <select required="required" name="author_group">
      <option disabled selected="yes">Ik ben een...</option>
      <?php foreach ($this->groups as $key => $value) { ?>
        <option value="<?php echo $key;?>"><?php echo $value;?></option>
      <?php } ?>
    </select><br />
    <input type="text" name="title" placeholder="Titel" required="required"><br />
    <textarea name="description" value="" placeholder="Beschrijving"></textarea><br />
    <input type="text" name="data_source" placeholder="Welke data kan gebruikt worden?"><br />
    <label>Is de data open?</label><input type="checkbox" name="data_open"><br />
    <input type="text" name="data_location" placeholder="Waar is de data te vinden?"><br />
    <div>
      <input type="submit" id="idpl_form_btn" value="Publiceer" style="float:right;"/>
      <a href="javascript:jQuery('#idpl_form').hide();">Annuleren</a>
    </div>
  </fieldset>
</form>
<br />
<form class="listdrop">
<select id="sorter" class="listdrop">
  <option disabled selected="yes">Sorteer op...</option>
  <option value="date">Datum</option>
  <option value="votes"><?php echo get_option('idpl_votes-namepl'); ?></option>
</select>
<select id="filter" class="listdrop">
  <option disabled selected="yes">Filter op...</option>
  <option value="">Niets</option>
  <option value="0">Openstaand</option>
  <option value="1">Gesloten</option>
  <option value="2">Gerealiseerd</option>
</select>
</form>
<h3 style="clear:none;">Nieuwste App-ideeën</h3>
<ul>
<?php foreach ($ideas as $idea) { ?>
  <li><a href="?<?php echo $_SERVER['QUERY_STRING'] . "&idea_id=" . $idea->id; ?>"><?php echo $idea->title; ?></a> door <?php echo $idea->author_name; ?></li>
<?php } ?>
</ul>
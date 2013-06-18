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
<div style="text-align:center; width:100%; margin-top:1em;" id="idpl_formbtn">
  <a href="javascript:jQuery('#idpl_form').slideDown(); jQuery('#idpl_formbtn').slideUp();" class="idpl_button">Klik hier om jouw app-idee te delen!</a>
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
    <textarea name="description" value="" placeholder="Beschrijving" required="required"></textarea><br />
    <input type="text" name="data_source" placeholder="Welke data kan gebruikt worden?"><br />
    <label>Is de data open?</label><input type="checkbox" name="data_open"><br />
    <input type="text" name="data_location" placeholder="Waar is de data te vinden?"><br />
    <div>
      <input type="submit" id="idpl_form_btn" value="Publiceer" style="float:right;"/>
      <a href="javascript:jQuery('#idpl_form').slideUp(); jQuery('#idpl_formbtn').slideDown();">Annuleren</a>
    </div>
  </fieldset>
</form>
<br />
<form class="listdrop">
<select id="sorter" class="listdrop">
  <option disabled<?php echo (!isset($_GET['sort']))? ' selected="yes"' : ''; ?>>Sorteer op...</option>
  <option value="date"<?php echo ($_GET['sort']=='date')? ' selected="yes"' : ''; ?>>Datum</option>
  <option value="votes"<?php echo ($_GET['sort']=='votes')? ' selected="yes"' : ''; ?>><?php echo get_option('idpl_votes-namepl'); ?></option>
</select>
<select id="filter" class="listdrop">
  <option disabled<?php echo (!isset($_GET['filter']))? ' selected="yes"' : ''; ?>>Filter op...</option>
  <option value="">Niets</option>
  <option value="0"<?php echo ($_GET['filter']==0)? ' selected="yes"' : ''; ?>>Openstaand</option>
  <option value="1"<?php echo ($_GET['filter']==1)? ' selected="yes"' : ''; ?>>Gesloten</option>
  <option value="2"<?php echo ($_GET['filter']==2)? ' selected="yes"' : ''; ?>>Gerealiseerd</option>
</select>
</form>
<h3 style="clear:none;">Nieuwste App-ideeën</h3>
<ul id="idpl_idea_list">
<?php foreach ($ideas as $idea) { ?>
  <li>
    <h3 style="padding-bottom: 0px; margin-bottom: 0px;"><a href="?<?php echo $_SERVER['QUERY_STRING'] . "&idea_id=" . $idea->id; ?>"><?php echo $idea->title; ?></a></h3>
    <i>door <?php echo $idea->author_name; ?>, een <?php echo strtolower($this->groups[$idea->author_group]); ?>, ingezonden op <?php echo strftime("%e %B %Y", strtotime($idea->date)); ?></i><br />
    <div style="margin: 5px 0 5px 0"><?php echo substr($idea->description, 0, 140); ?>...</div>
    <i><?php echo $idea->votes; ?> <?php echo strtolower(get_option('idpl_votes-namepl')); ?>, <?php echo strtolower($this->getStatusString($idea->status)); ?></i><br />
  </li>
<?php } ?>
</ul>
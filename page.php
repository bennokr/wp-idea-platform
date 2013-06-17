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
        jQuery("#idpl_comments").append(jQuery("<b style=\"margin-left:-10px;\">Toegevoegde reactie:</b><br/><p>"+data.description+"</p>"));
      });
      return false;
    });

    jQuery("#idpl_contact").on( "submit", function( event ) {
      // jQuery(this).hide();
      event.preventDefault();
      jQuery.post(jQuery(this).attr("action"), jQuery(this).serialize(), function(data) {
        console.log(data);
      });
      return false;
    });
  });
</script>
<div class="back"><a href="?page_id=<?php echo the_ID(); ?>">&lt; Terug naar overzicht</a></div>

<div style="float:left; padding:2em;">
  <span id="idpl_votes"><?php echo $idea->votes; ?></span> <?php echo get_option('idpl_votes-namepl'); ?><br />
  <form action="<?php echo $this->submit_url;?>" id="idpl_vote">
    <input type="hidden" name="action" value="<?php echo $this->ajax_names['vote']; ?>"/>
    <input type="hidden" name="id" value="<?php echo $idea->id; ?>"/>
    <input type="submit" value="<?php echo get_option('idpl_votes-name'); ?>">
  </form>
</div>

<div>
  <h1 style="padding-bottom:0;margin-bottom:0px;clear:none;"><?php echo $idea->title; ?></h1>
  <i>door <?php echo $idea->author_name; ?>, een <?php echo $this->groups[$idea->author_group]; ?>, ingezonden op <?php echo $idea->date; ?></i><br />
  <i>Status: <b><?php switch ($idea->status) { case 0:{echo 'Openstaand'; break;}; case 1:{echo 'Gesloten'; break;}; case 2:{echo 'Gerealiseerd'; break;}; }; ?></b></i>
</div>
<p style="clear:both;"><?php echo nl2br($idea->description); ?></p>
<ul>
  <li>Data bron: <?php echo $idea->data_source; ?></li>
  <li>Data open: <?php echo (1==$idea->data_open)? 'ja': 'nee'; ?></li>
  <li>Data locatie: <?php echo $idea->data_location; ?></li>
</ul>
<form action="<?php echo $this->submit_url;?>" id="idpl_contact" style="background-color:#fef9e9;">
        <input type="hidden" name="action" value="<?php echo $this->ajax_names['contact']; ?>"/>
      <input type="hidden" name="idea_id" value="<?php echo $idea->id; ?>"/>

  <fieldset>
      <p>
        Neem contact op met de bedenker van dit idee!<br /><br />
        <input type="submit" id="idpl_form_btn" value="Stuur mail" />
      </p>
      <p>
        <textarea name="message" value="" placeholder="Bericht" rows="3"></textarea>
      </p>
      <p>
        <input type="text" name="telephone" placeholder="Tel." required="required"><br/>
        <input type="text" name="mail" placeholder="Mailadres" required="required">
      </p>
  </fieldset>
</form>

<h2>Reacties</h2>

<ul id="idpl_comments">
<?php foreach ($comments as $comment) { ?>
  <li>
    <i style="margin-left:-10px;">Op <?php echo $comment->date; ?> schreef <?php echo $comment->author_name; ?> (<?php echo $this->groups[$comment->author_group]; ?>):</i><br />
    <?php echo nl2br(htmlspecialchars($comment->description)); ?>
  </li>
<?php } ?>
</ul>

<form action="<?php echo $this->submit_url;?>" id="idpl_comment">
  <fieldset>
    <input type="hidden" name="action" value="<?php echo $this->ajax_names['comment']; ?>"/>
    <input type="hidden" name="idea_id" value="<?php echo $idea->id; ?>"/>
    <input type="text" name="author_name" placeholder="Naam" required="required"><br />
    <input type="text" name="author_mail" placeholder="Mailadres (wordt niet getoond)" required="required"><br />
    <select required="required" name="author_group">
      <option disabled selected="yes">Ik ben een...</option>
      <?php foreach ($this->groups as $key => $value) { ?>
        <option value="<?php echo $key;?>"><?php echo $value;?></option>
      <?php } ?>
    </select><br />
    <textarea name="description" value="" placeholder="Reactie" style="width:50%"></textarea><br />
    <input type="submit" id="idpl_form_btn" value="Publiceer"/>
  </fieldset>
</form>

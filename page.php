<script type="text/javascript">
  jQuery(document).ready(function() {
    // Submit form my ajax
    jQuery("#idpl_vote").on( "submit", function( event ) {
      event.preventDefault();
      // Set cookie (not actually double-voting protection, just for UI)
      var exdate=new Date();
      exdate.setDate(exdate.getDate() + 20*365);
      document.cookie="idpl-voted-<?php echo $idea->id; ?>;expires="+exdate.toUTCString();
      jQuery.post(jQuery(this).attr("action"), jQuery(this).serialize(), function(data) {
        jQuery('#idpl_votes').text(data.votes);
        jQuery('#idpl_votes_loader').hide();
      });
      jQuery(this).html('<img src="<?php echo $this->plugin_url("images/ajax-loader.gif"); ?>" id="idpl_votes_loader"/>');
      return false;
    });

    jQuery("#idpl_comment").on( "submit", function( event ) {
      event.preventDefault();
      jQuery.post(jQuery(this).attr("action"), jQuery(this).serialize(), function(data) {
        jQuery("#idpl_comments").append(jQuery("<b style=\"margin-left:-10px;\">Toegevoegde reactie:</b><br/><p>"+data.description+"</p>"));
        jQuery('#idpl_comment_loader').hide();
      });
      jQuery(this).html('<img src="<?php echo $this->plugin_url("images/ajax-loader.gif"); ?>" id ="idpl_comment_loader"/>');
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
    jQuery('#idpl_contact').hide();

    if (-1 == document.cookie.indexOf("idpl-voted-<?php echo $idea->id; ?>")) {
      jQuery('#idpl_vote_image').click(function() { jQuery('#idpl_vote').submit() });
      jQuery('#idpl_vote_image').css('cursor', 'pointer');
    } else {
      jQuery('#idpl_vote').remove();
    }

  });
</script>



<div style="float: left;padding: 2em;text-align: center;line-height: 40px;position: relative;margin-right: 30px;">
  <span id="idpl_votes"><?php echo $idea->votes; ?></span>
  <img id="idpl_vote_image" style="height:50px;position: absolute;top: 0;bottom: 0;margin: auto;padding: 10px;" src="<?php echo get_option('idpl_votes-img'); ?>" /><br />
  <form action="<?php echo $this->submit_url;?>" id="idpl_vote">
    <input type="hidden" name="action" value="<?php echo $this->ajax_names['vote']; ?>"/>
    <input type="hidden" name="id" value="<?php echo $idea->id; ?>"/>
  </form>
</div>

<div>
  <p style="font-size: 11px;font-style: normal;letter-spacing: 0.05em;text-transform: uppercase;">
    <?php echo date_i18n(get_option('date_format'), strtotime($idea->date)); ?>
    <a href="?page_id=<?php echo the_ID(); ?>" title="Terug naar overzicht" style="float:right; color:#666;">Terug</a>
  </p>
  <h1 style="padding:0;margin:0;clear:none;"><?php echo $idea->title; ?></h1>
  <p><i>door <?php echo $idea->author_name; ?>, een <?php echo strtolower($this->groups[$idea->author_group]); ?></i></p>
</div>
<p style="clear:both;"><?php echo nl2br($idea->description); ?></p>
<ul>
  <li>Beschikbare data: <?php echo $idea->data_source; ?></li>
  <li>Data open: <?php echo (1==$idea->data_open)? 'ja': 'nee'; ?></li>
  <li>Data locatie: <?php echo $idea->data_location; ?></li>
</ul>
<p id="idpl_contactbtn">
  <a href="javascript:jQuery('#idpl_contact').slideDown(); jQuery('#idpl_contactbtn').slideUp();">
    <img src="<?php echo $this->plugin_url("images/koffie.png"); ?>" style="width:275px;" /><br />
    Neem contact op met de bedenker van dit idee!
  </a>
</p>
<form action="<?php echo $this->submit_url;?>" id="idpl_contact" style="background-color:#fef9e9;">
  <input type="hidden" name="action" value="<?php echo $this->ajax_names['contact']; ?>"/>
  <input type="hidden" name="idea_id" value="<?php echo $idea->id; ?>"/>

  <fieldset>
      <p>
        <a href="javascript:jQuery('#idpl_contact').slideUp(); jQuery('#idpl_contactbtn').slideDown();">Annuleren</a><br /><br />
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
    <i style="margin-left:-10px;">Op <?php echo date_i18n(get_option('date_format'), strtotime($comment->date)); ?> schreef <?php echo $comment->author_name; ?> (<?php echo strtolower($this->groups[$comment->author_group]); ?>):</i><br />
    <?php echo nl2br(htmlspecialchars($comment->description)); ?>
  </li>
<?php } ?>
</ul>

<form action="<?php echo $this->submit_url;?>" id="idpl_comment">
  <fieldset>
    <legend><h4>Reageren</h4></legend>
    <input type="hidden" name="action" value="<?php echo $this->ajax_names['comment']; ?>"/>
    <input type="hidden" name="idea_id" value="<?php echo $idea->id; ?>" />
    <input type="text" name="author_name" placeholder="Naam" required="required" /><br />
    <input type="text" name="author_mail" placeholder="Mailadres (wordt niet getoond)" required="required" /><br />
    <select required="required" name="author_group">
      <option disabled selected="yes">Ik ben een...</option>
      <?php foreach ($this->groups as $key => $value) { ?>
        <option value="<?php echo $key;?>"><?php echo $value;?></option>
      <?php } ?>
    </select><br />
    <textarea name="description" value="" placeholder="Reactie" required="required"></textarea><br />
    <input type="submit" id="idpl_form_btn" value="Publiceer"/>
  </fieldset>
</form>




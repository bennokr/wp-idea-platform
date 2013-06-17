<div class="wrap">
  <h2>Ideeën Platform</h2>

  <form method="post" action="options.php">
    <legend><h3>Opties</h3></legend>
    <?php settings_fields( 'idpl_settings' ); ?>
    <label>Een stem is een </label>
    <input type="text" name="idpl_votes-name" value="<?php echo get_option('idpl_votes-name');?>"/>
    <label>, meervoud </label>
    <input type="text" name="idpl_votes-namepl" value="<?php echo get_option('idpl_votes-namepl');?>"/><br />
    <label>Rollen, met komma's ertussen: </label>
    <input type="text" name="idpl_groups" value="<?php echo get_option('idpl_groups');?>"/><br />
    <input type="submit" value="Opslaan" />
  </form>
  <br />
  <br />
  <form method="post" action="">
    <legend><h3>Ideeën</h3></legend>
    <table style="width:100%;">
      <tr>
        <td><b>Naam</b></td>
        <td><b>Auteur</b></td>
        <td><b>Status</b></td>
      </tr>
      <?php foreach ($ideas as $idea) { ?>
        <tr>
          <input type="hidden" name="idea_id[]" value="<?php echo $idea->id; ?>" />
          <td><a href="?<?php echo $_SERVER['QUERY_STRING'] . "&idea_id=" . $idea->id; ?>"><?php echo $idea->title; ?></a></td>
          <td><?php echo $idea->author_name; ?></td>
          <td>
            <select name="status[]">
              <option value="0"<?php echo (0==$idea->status)? ' selected="yes"' : ''; ?>>Openstaand</option>
              <option value="1"<?php echo (1==$idea->status)? ' selected="yes"' : ''; ?>>Gesloten</option>
              <option value="2"<?php echo (2==$idea->status)? ' selected="yes"' : ''; ?>>Gerealiseerd</option>
            </select>
          </td>
        </tr>
      <?php } ?>
    </table>
    <input type="submit" value="Opslaan"/>
  </form>
</div>
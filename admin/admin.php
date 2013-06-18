<div class="wrap">
  <h2>Ideeën Platform</h2>

  <form method="post" action="options.php">
    <legend><h3>Opties</h3></legend>
    <?php settings_fields( 'idpl_settings' ); ?>
    <label>Een stem is een </label>
    <input type="text" name="idpl_votes-name" value="<?php echo get_option('idpl_votes-name');?>"/>
    <label>, meervoud </label>
    <input type="text" name="idpl_votes-namepl" value="<?php echo get_option('idpl_votes-namepl');?>"/><br />
    <b>Lijsten</b><br />
    <div style="width:600px;">Deze velden bevatten lijsten van items met komma's ertussen. In de database staat alleen of we item nummer 1,2,3 ... willen, dus items verwijderen of vooraan de lijst toevoegen zal voor vreemde dingen zorgen.</div>
    <label>Rollen</label>
    <input type="text" name="idpl_groups" value="<?php echo get_option('idpl_groups');?>"/><br />
    <label>Statussen</label>
    <input type="text" name="idpl_statusses" value="<?php echo get_option('idpl_statusses');?>"/><br />
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
        <td><b style="color:red">Verwijderen</b></td>
      </tr>
      <?php foreach ($ideas as $idea) { ?>
        <tr>
          <input type="hidden" name="idea_id[<?php echo $idea->id; ?>]" value="<?php echo $idea->id; ?>" />
          <td><?php echo $idea->title; ?></td>
          <td><?php echo $idea->author_name; ?></td>
          <td>
            <select name="idea_status[<?php echo $idea->id; ?>]">
              <?php foreach ($this->statusses as $key => $value) { ?>
                <option value="<?php echo $key;?>"<?php echo ($idea->status==$key)? ' selected="yes"' : ''; ?>><?php echo $value;?></option>
              <?php } ?>
            </select>
          </td>
          <td>
            <input type="checkbox" name="idea_delete[<?php echo $idea->id; ?>]"/>
          </td>
        </tr>
      <?php } ?>
    </table>
    <input type="submit" value="Opslaan"/>
  </form>

  <form method="post" action="">
    <legend><h3>Reacties</h3></legend>
    <table style="width:100%;">
      <tr>
        <td><b>Reactie op...</b></td>
        <td><b>Auteur</b></td>
        <td><b>Reactie</b></td>
        <td><b style="color:red">Verwijderen</b></td>
      </tr>
      <?php foreach ($comments as $comment) { ?>
        <tr>
          <input type="hidden" name="comment_id[<?php echo $comment->id; ?>]" value="<?php echo $comment->id; ?>" />
          <td><?php foreach($ideas as $idea) { if ($idea->id == $comment->idea_id) { echo $idea->title; } } ?></td>
          <td><?php echo $comment->author_name; ?></td>
          <td><?php echo substr($comment->description, 0, 140); ?></td>
          <td>
            <input type="checkbox" name="comment_delete[<?php echo $comment->id; ?>]"/>
          </td>
        </tr>
      <?php } ?>
    </table>
    <input type="submit" value="Opslaan"/>
  </form>
</div>
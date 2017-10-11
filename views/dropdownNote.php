<?php

# Add note
echo'<div class="col-12 dropContainer" id="addNoteContainer">';
    echo'<form method="post" id="noteForm">';
        echo'<textarea name="note" class="inputArea" rows="5" id="noteArea"></textarea>';
        echo'<button type="submit" class="inputSubmit right" name="addNote"><i class="fa fa-plus" aria-hidden="true"></i></button>';
    echo'</form>';
echo'</div>';

# Unlock notes (pw input)
echo'<div class="col-12 dropContainer" id="unlockNoteContainer">';
    echo'<form method="post" id="unlockNotes">';
        echo'<input type="password" name="password" class="inputDefault" id="unlockPassword"/>';
        echo'<button type="submit" class="hiddenSubmit" name="unlockAllNotes">Catch me if you can!</button>';
    echo'</form>';
echo'</div>';

?>

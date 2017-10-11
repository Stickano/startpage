<?php

if(!empty($controller->getNotes())){
    # All notes
    echo'<div class="row noteRow dropContainer">';
        echo'<form method="post">';
            foreach ($controller->getNotes() as $key) {
                echo'<div class="col-12">';
                    echo '<div class="noteDateContainer">';
                        # Delete button, only if notes are unlocked
                        if($controller->getNoteSettings()[0]['locked'] == 0){ # TODO: double click to delete (confirm)
                            echo'<button class="delNoteButton" title="Delete" id="delNoteConfirm" name="delNote" value="'.$key['id'].'">
                                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                                </button>';
                        }
                        echo $key['dateTime'];
                    echo'</div>';
                    echo'<div class="noteContainer">'.nl2br($key['note']).'</div>';
                echo'</div>';
            }
        echo'</form>';
    echo'</div>';
}

?>

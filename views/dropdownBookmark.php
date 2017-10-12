<?php

# Add URL
echo'<div class="col-12 dropContainer" id="addUrlContainer">';
    echo'<form method="post" id="linkForm">';
        echo'<input type="text" class="inputDefault" id="newUrl" name="url" placeholder="Url" />';
        echo'<input type="text" class="inputDefault" name="head" placeholder="Headline" />';
        echo'<select class="inputDefault" name="category" id="chooseCat">';
            foreach ($controller->getCategories() as $key) {
                echo'<option value="'.strtolower($key).'">'.ucfirst($key).'</option>';
            }
        echo'</select>';
        echo'<input type="text" class="inputDefault" name="category" id="addCat" placeholder="Category" />';
        echo'<button class="catSwitch" title="New"><i class="fa fa-plus-square-o" aria-hidden="true"></i></button>';
        echo'<textarea class="inputArea" name="desc" rows="3"/></textarea>';
        echo'<br>';
        echo'<button type="submit" class="inputSubmit right" name="addUrl"><i class="fa fa-plus" aria-hidden="true"></i></button>';
    echo'</form>';
echo'</div>';

?>

<?php

$category = "";
$br = 0;

echo'<div class="row linkRow">';
    # Print out all the links from the database
    if(!empty($controller->getLinks())){
        echo'<form method="post" id="allUrlsDel">';
            foreach ($controller->getLinks() as $key) {

                # Print out the category
                if($key['category'] != $category){
                    # Close elements from previous category
                    if($category != "")
                        echo'</div>';

                    # Cause of dumb behaviour, clear to new line every 3 column
                    $br++;
                    $clearfix = null;
                    if($br == 4){
                        $clearfix = 'clearfix';
                        $br = 0;
                    }
                    # Open new link column
                    echo'<div class="col-4 linkCol '.$clearfix.'">';
                    # Echo out the category
                    $category = $key['category'];
                    echo "<h2 class='urlCategories'>".$category."</h2>";
                }

                # Link to display
                $displayVal = $key['link'];
                if(!empty($key['head']) && $key['head'] != null)
                    $displayVal = "<b>".$key['head']."</b>";

                # Delete checkbox and URL
                echo'<input type="checkbox" class="delUrlCheck" name="'.$key['id'].'" value="'.$key['id'].'"/> ';
                echo "<a href='".$key['link']."' target='_blank' class='urlHyper'>".$displayVal."</a><br>";
                # Description
                if(!empty($key['description']) && $key['description'] != null)
                    echo'<div class="urlDescription">'.$key['description'].'</div>';
            }
            # This will close the last link column
            echo'</div>';
        echo'</form>';
    }
echo'</div>';

?>

<?php

# DDG Search Go
if(isset($_POST['ddg']))
    $controller->ddg();

# Add a new URL (bookmark) 
if(isset($_POST['addUrl']))
    $controller->addLink();

# Delete a link (bookmarks)
if(isset($_POST['delUrls']))
    $controller->removeLinks();

# Add a new note
if(isset($_POST['addNote']))
    $controller->addNote();

# Delete a note
if(isset($_POST['delNote']))
    $controller->deleteNote();

# Creates the db
if(isset($_POST['addDb']))
    $controller->addDb();

# Drops the db
if(isset($_POST['dropDb']))
    $controller->dropDb();

# Adds a new password (notes)
if(isset($_POST['addPw']))
    $controller->addPw();

# Adds weather information (API key & city id)
if(isset($_POST['addWeather']))
    $controller->addWeather();

# Unlocks the notes
if(isset($_POST['unlockAllNotes']))
    $controller->unlockNotes();

# Lock the notes
if(isset($_POST['lockNotes']))
    $controller->lockNotes();


# Print out any errors
if($controller->getErrors()){
    echo '<div class="errorContainer">';
        echo $controller->getErrors();
        echo '<button class="closeErr right"><i class="fa fa-times" aria-hidden="true"></i></button>';
    echo '</div>';
}

# Grid buildup
echo'<div class="row">';
    echo'<div class="col-3 no-mobile"></div>';
    echo'<div class="col-6">';

        # DDG Search
        echo'<div class="row">';
            echo'<div class="col-12">';
                echo'<div class="searchContainer no-mobile"></div>';
                echo'<img src="media/icon.png" class="startpageLogo" alt="Logo"/>';
                echo '<span class="greeting">'.$controller::$time->greeting().'</span>';
                echo'<form method="post">';
                    echo'<input type="text" name="search" class="searchField" autofocus/>';
                    echo'<input type="submit" name="ddg" class="searchButton" value="&#xf002;"/>';
                echo'</form>';
            echo'</div>';
        echo'</div>';

        # Menu and Dropdowns
        echo'<div class="row">';
            echo'<div class="col-12 menuButtonContainer"> ';

                # The buttons for Bookmarks, weather (on condition) and notes, if db is set
                if($controller->issetDb()){
                    echo'<button class="menuButton" title="Bookmarks" id="openLinks"><i class="fa fa-bookmark" aria-hidden="true"></i></button>';
                    echo'<button class="menuButton" title="Notes" id="openNotes"><i class="fa fa-comment" aria-hidden="true"></i></button>';

                    # If weather is set, display menu button
                    if($controller->issetWeather()){
                        echo'<button class="menuButton" title="Weather" id="openWeather"><i class="wi wi-day-sleet"></i></button>';
                    }
                }
                
                # Settings (always available)
                echo'<button class="menuButton" title="Settings" id="openSettings"><i class="fa fa-cogs" aria-hidden="true"></i></button>';
                echo'<br>';
                
                # Sub buttons
                if($controller->issetDb()){
                    # Select URLs and Delete URLs
                    if(!empty($controller->getLinks())){
                        echo'<button class="menuButton" type="submit" title="Delete" id="delUrls" name="delUrls" form="allUrlsDel"><i class="fa fa-check" aria-hidden="true"></i></button>';
                        echo'<button class="menuButton" title="Select" id="delUrl"><i class="fa fa-trash" aria-hidden="true"></i></button>';
                    }

                    # Add URL
                    echo'<button class="menuButton" title="New" id="addUrl"><i class="fa fa-plus-square" aria-hidden="true"></i></button>';

                    # If a note password is set, change the button between lock/unlock
                    if($controller->issetNoteSettings()){
                        if($controller->getNoteSettings()[0]['locked'] == 0){
                            echo'<form method="post" id="lockForm"></form>';
                               echo'<button class="menuButton" type="submit" title="Lock" form="lockForm" id="lockNotes" name="lockNotes"><i class="fa fa-lock" aria-hidden="true"></i></button>';
                        }else{
                           echo'<button class="menuButton" title="Unlock" id="unlockNotes"><i class="fa fa-unlock" aria-hidden="true"></i></button>';
                        }
                    }

                    # Add new note button
                    if(!$controller->issetNoteSettings() || $controller->getNoteSettings()[0]['locked'] == 0){
                        echo'<button class="menuButton" title="New" id="addNote"><i class="fa fa-plus-square" aria-hidden="true"></i></button>';
                    }
                }
            echo'</div>'; # Close menu container, dropdowns next

            # Dropdowns for notes and bookmarks, when a db is set
            if($controller->issetDb()){
                require_once('views/dropdownBookmark.php');
                require_once('views/dropdownNote.php');
            }

            # Dropdown Settings (allways available)
            require_once('views/dropdownSettings.php');
        echo'</div>'; # Close menu and search container, views (content) next

        # Show the bookmark, weather(on condition) and notes views, when db is set
        if($controller->issetDb()){
            require_once('views/includeBookmarks.php');
            require_once('views/includeNotes.php');
            # If weather is set, load the weather panel
            if($controller->issetWeather())
                require_once('views/includeWeather.php');
        }
    echo'</div>';

    # Clock
    echo'<div class="col-3 no-mobile">';
        echo'<div class="row">';
            echo'<div class="col-12 time">';
                echo '<div id="timeCon">'.$controller::$time->timestamp('time').'</div>';
                echo '<div class="date">'.$controller->getDate().'</div>';
            echo'</div>';
        echo'</div>';
    echo'</div>';
echo'</div>';

?>

<script src="js/dynamics.js"></script>

<?php

echo'<div class="col-12 dropContainer settingsRow">';
    // # A placeholder form that will drop the STARTPAGE db
    // echo'<form method="post" id="dropDb"></form>'; TODO: Shouldn't be like this anyways - throw it in a if-statement
    echo'<div class="row">';
        # Add database
        if(!$controller->issetDb()){
            echo'<div class="settingHeadline">Install Database <span class="settingsNotice">A database must be installed for settings to be available!</span></div>';
            echo'<form method="post">';
                echo'<div class="col-12 settingsInputContainer">';
                    echo'<div class="settingInfo">Host Address</div>';
                    echo'<input type="text" name="host" class="settingsInput right" />';
                echo'</div>';
                echo'<div class="col-12 settingsInputContainer">';
                    echo'<div class="settingInfo">Username</div>';
                    echo'<input type="text" name="uname" class="settingsInput right" />';
                echo'</div>';
                echo'<div class="col-12 settingsInputContainer">';
                    echo'<div class="settingInfo">Password</div>';
                    echo'<input type="password" name="upass" class="settingsInput right" />';
                    echo'<button type="submit" name="addDb" title="Install Database" class="inputSubmit clearfix right settingsSubmit">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                        </button>';
                echo'</div>';
            echo'</form>';
        }

        # If an database is installed, provide additional settings
        if($controller->issetDb()){
            # Note settings
            $newPw = null;
            echo'<div class="settingHeadline">Note settings ';
                # Give a little notice if password is not set
                if(!$controller->issetNoteSettings())
                    echo'<span class="settingsNotice">In order to lock notes you must provide a password!</span>';
            echo'</div>';
            echo'<form method="post">';
                if($controller->issetNoteSettings()){
                    $newPw = "New ";
                    echo'<div class="col-12 settingsInputContainer">';
                        echo'<div class="settingInfo">Current Password</div>';
                        echo'<input type="password" name="currentPw" class="settingsInput right" />';
                    echo'</div>';
                }
                echo'<div class="col-12 settingsInputContainer">';
                    echo'<div class="settingInfo">'.$newPw.'Password</div>';
                    echo'<input type="password" name="pw1" class="settingsInput right" />';
                echo'</div>';
                echo'<div class="col-12 settingsInputContainer">';
                    echo'<div class="settingInfo">Retype</div>';
                    echo'<input type="password" name="pw2" class="settingsInput right" />';
                    echo'<button type="submit" name="addPw" title="Update" class="inputSubmit settingsSubmit clearfix right">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                        </button>';
                echo'</div>';
            echo'</form>';

            # Weather settings
            $key = $controller->getWeatherSettings()[0]['apiKey'];
            $city = $controller->getWeatherSettings()[0]['city'];
            echo'<div class="settingHeadline">Weather API ';
                # Give a little notice if password is not set
                if(!$controller->issetWeather())
                    echo'<span class="settingsNotice">Create an account at <a href="http://openweathermap.org" target="_blank" title="Takes you to OWMs website">OpenWeatherMap</a> for an API key.</span>';
            echo'</div>';
            echo'<form method="post">';
                echo'<div class="col-12 settingsInputContainer">';
                    echo'<div class="settingInfo">API Key</div>';
                    echo'<input type="text" name="api" class="settingsInput right" value="'.$key.'"/>';
                echo'</div>';
                echo'<div class="col-12 settingsInputContainer">';
                    echo'<div class="settingInfo"><a href="http://openweathermap.org/help/city_list.txt" class="settingInfoUrl" title="City list and their IDs" target="_blank">City ID</a></div>';
                    echo'<input type="text" name="city" class="settingsInput right" value="'.$city.'"/>';
                    echo'<button type="submit" name="addWeather" title="Update" class="inputSubmit settingsSubmit clearfix right">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                        </button>';
                echo'</div>';
            echo'</form>';
        }
    echo'</div>';
echo'</div>';

?>

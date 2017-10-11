<?php

echo'<div class="row weatherRow">';
    # Main weather overview
    echo'<div class="col-12 mainWeather">';
        echo $controller->getWeather(0, 'main');
    echo'</div>';

    # Todays weather
    echo'<div class="col-6 weatherDetails">';
        echo'<div class="row">';
            echo'<div class="col-12 weatherTime">';
                echo'Now';
            echo'</div>';
            # Weather description
            echo'<div class="col-12">';
                echo $controller->getWeather(0, 'icon');
                echo ' '.ucfirst($controller->getWeather());
            echo'</div>';
            # Weather temp
            echo'<div class="col-12 windSpeedCon">';
                echo '<i class="wi wi-thermometer"></i>'.$singleton->spaces(3);
                echo $controller->getWeather(0, 'temp');
            echo'</div>';
            # Wind direction/speed
            echo'<div class="col-12 windDirCon">';
                echo $controller->getWeather(0, 'windDegree').$singleton->spaces(3);
                echo $controller->getWeather(0, 'windSpeed');
            echo'</div>';
        echo'</div>';
    echo'</div>';

    # Tomorrows weather
    echo'<div class="col-6 weatherDetails">';
        echo'<div class="row">';
            echo'<div class="col-12 weatherTime">';
                echo'+24 Hours';
            echo'</div>';
            # Weather description
            echo'<div class="col-12">';
                echo $controller->getWeather(24, 'icon');
                echo ' '.ucfirst($controller->getWeather(24));
            echo'</div>';
            # Weather temp
            echo'<div class="col-12 windSpeedCon">';
                echo '<i class="wi wi-thermometer"></i>'.$singleton->spaces(3);
                echo $controller->getWeather(24, 'temp');
            echo'</div>';
            # Wind direction/speed
            echo'<div class="col-12 windDirCon">';
                echo $controller->getWeather(24, 'windDegree').$singleton->spaces(3);
                echo $controller->getWeather(24, 'windSpeed');
            echo'</div>';
        echo'</div>';
    echo'</div>';
echo'</div>';

?>

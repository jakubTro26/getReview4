<?php

// Use in the “Post-Receive URLs” section of your GitHub repo.
//hellofff



if ( $_POST['payload'] ) {
    putenv('PATH=/usr/local/bin');



    echo shell_exec('cd /var/www/woo && /usr/bin/git pull origin druga 2>&1');

    
    }

//bubaaa
?>

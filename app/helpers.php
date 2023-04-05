<?php

/**
 * This function prints out a variable and then stops the script. If the variable is an array, it uses print_r, otherwise it uses var_dump.
 * 
 * @param mixed $var
 * @return void
 */
function dd($var): void
{
    // Check if the variable is an array
    if (is_array($var)) {
        // If yes, print it using print_r
        print_r($var);
    } else {
        // If not, use var_dump to print the variable
        var_dump($var);
    }
    // Die to stop the script
    die();
}

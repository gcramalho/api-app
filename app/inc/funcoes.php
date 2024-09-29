<?php

// facilitando p/ saber tipo da váriavel (objeto ou array)
function printData($data, $die = true)
{

    echo "<pre>";

    if ((is_object($data)) || is_array($data)) {
        print_r($data);

    } elseif ($data == null) {
        echo "variavel está vazia";

    } else {
        echo "$data é string";
    }

    if ($die) {
        die(PHP_EOL . 'TERMINADO' . PHP_EOL);
    }
}

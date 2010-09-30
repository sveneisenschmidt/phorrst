<?php

function print_p($param, $exit = false)
{
    if(is_null($param)) {
        $param = 'null';
    } else if (is_bool($param)) {
        if($param === false) {
            $param = 'false';
        } else if($param === true) {
            $param = 'true';
        }
    }

    print "<pre>";
    print_r($param);
    print "</pre>";
    if($exit) exit();
}

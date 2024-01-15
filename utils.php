<?php

function generateRandomArray($length, $minValue, $maxValue)
{
    $randomArray = array();
    for ($i = 0; $i < $length; $i++) {
        $randomArray[] = mt_rand($minValue, $maxValue);
    }
    return $randomArray;
}

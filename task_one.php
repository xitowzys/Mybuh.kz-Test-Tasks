<?php

require_once "tests.php";

/**
 * Функция сортировки (Алгоритм Comb sort)
 * @param array $arr
 * @param bool $asc
 * @return array
 * @link https://en.wikipedia.org/wiki/Comb_sort
 */
function customSort(array $arr, bool $asc = true): array
{
    $gap = count($arr);
    $shrink = 1.3;
    $sorted = false;

    while (!$sorted) {
        $gap = floor($gap / $shrink);
        if ($gap <= 1) {
            $gap = 1;
            $sorted = true;
        } elseif ($gap == 9 || $gap == 10) {
            $gap = 11;
        }

        $i = 0;
        while ($i + $gap < count($arr)) {
            if (($asc && $arr[$i] > $arr[$i + $gap]) || (!$asc && $arr[$i] < $arr[$i + $gap])) {
                $arr[$i] = $arr[$i] + $arr[$i + $gap];
                $arr[$i + $gap] = $arr[$i] - $arr[$i + $gap];
                $arr[$i] = $arr[$i] - $arr[$i + $gap];

                $sorted = false;
            }
            $i = $i + 1;
        }
    }

    return $arr;
}

testCustomSort();

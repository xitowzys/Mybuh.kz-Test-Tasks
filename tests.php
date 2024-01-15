<?php

require_once "utils.php";

function testCustomSort()
{
    $inputData = generateRandomArray(10, 1, 100);

    $ascendingResult = customSort($inputData, true);

    $expectedAscendingResult = $inputData;
    sort($expectedAscendingResult);

    if ($ascendingResult === $expectedAscendingResult) {
        echo "Тест 1 Пройден\n";
    } else {
        echo "Тест 1 Не пройден\n";
    }

    $descendingResult = customSort($inputData, false);
    $expectedDescendingResult = $inputData;
    rsort($expectedDescendingResult);

    if ($descendingResult === $expectedDescendingResult) {
        echo "Тест 2 Пройден\n";
    } else {
        echo "Тест 2 Не пройден\n";
    }
}

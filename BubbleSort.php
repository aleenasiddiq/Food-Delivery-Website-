<?php
// classes/BubbleSort.php

class BubbleSort {
    
    // Sort implementation for array of associative arrays
    // $key is the field to sort by (e.g., 'price' or 'rating')
    public static function sort(array $items, $key) {
        $n = count($items);
        // Bubble Sort Algorithm
        for ($i = 0; $i < $n - 1; $i++) {
            for ($j = 0; $j < $n - $i - 1; $j++) {
                if ($items[$j][$key] > $items[$j + 1][$key]) {
                    // Swap
                    $temp = $items[$j];
                    $items[$j] = $items[$j + 1];
                    $items[$j + 1] = $temp;
                }
            }
        }
        return $items;
    }
}
?>

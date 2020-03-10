<?php

namespace TkachenkoMax\Sort;

/**
 * Class Sort
 *
 * @package TkachenkoMax\Sort
 */
class Sort
{
    /**
     * Algs training.
     *
     * @return array
     */
    public function algs()
    {
        $array = $this->generateRandomArray(100);

        return [
            'sortings' => [
                'php_sort' => $this->phpSort($array),
                'bubble' => $this->bubbleSort($array),
                'shaker' => $this->shakerSort($array),
                'selection' => $this->selectionSort($array),
                'insert' => $this->insertionSort($array),
                'merge' => $this->mergeSort($array),
                'qsort' => $this->qsort($array),
            ],
            'peak_memory' => (memory_get_peak_usage() / 1000000) . ' Mb'
        ];
    }

    /**
     * @param int $length
     * @return array
     */
    private function generateRandomArray(int $length): array
    {
        $range = range(0, $length - 1);
        shuffle($range);

        return $range;
    }

    /**
     * Sorting with laravel array_sort helper.
     *
     * @param array $array
     * @return array
     */
    private function phpSort(array $array): array
    {
        $memoryStart = memory_get_usage();
        $startTime = microtime(true);
        sort($array);
        $endTime = microtime(true);
        $memoryEnd = memory_get_usage();

        return [
            'time'   => $endTime - $startTime,
            'memory' => (($memoryEnd - $memoryStart) / 1000000) . ' Mb'
        ];
    }

    /**
     * Sorting with bubble sort.
     *
     * @param array $array
     * @return array
     */
    private function bubbleSort(array $array): array
    {
        $memoryStart = memory_get_usage();
        $startTime = microtime(true);

        $lapLength = count($array) - 1 ;
        for ($i = 0; $i < count($array); $i++) {
            for ($j = 0; $j < $lapLength; $j++) {
                if (!array_key_exists($array[$j + 1], $array)) {
                    continue;
                }

                $currentElement = $array[$j];
                $nextElement = $array[$j + 1];

                if ($currentElement > $nextElement) {
                    $array[$j] = $nextElement;
                    $array[$j + 1] = $currentElement;
                }
            }
            $lapLength--;
        }

        $endTime = microtime(true);
        $memoryEnd = memory_get_usage();

        return [
            'time'   => $endTime - $startTime,
            'memory' => (($memoryEnd - $memoryStart) / 1000000) . ' Mb'
        ];
    }

    /**
     * Sorting with shaker sort.
     *
     * @param array $array
     * @return array
     */
    private function shakerSort(array $array): array
    {
        $memoryStart = memory_get_usage();
        $startTime = microtime(true);

        $leftPointer = 0;
        $rightPointer = count($array) - 1;

        do {
            for ($i = $leftPointer; $i < $rightPointer; $i++) {
                if (!array_key_exists($array[$i + 1], $array)) {
                    continue;
                }

                $currentElement = $array[$i];
                $nextElement = $array[$i + 1];

                if ($currentElement > $nextElement) {
                    $array[$i] = $nextElement;
                    $array[$i + 1] = $currentElement;
                }
            }

            for ($i = $rightPointer; $i > $leftPointer; $i--) {
                if (!array_key_exists($array[$i - 1], $array)) {
                    continue;
                }

                $currentElement = $array[$i];
                $prevElement = $array[$i - 1];

                if ($currentElement < $prevElement) {
                    $array[$i] = $prevElement;
                    $array[$i - 1] = $currentElement;
                }
            }

            $leftPointer++;
            $rightPointer--;
        } while ($leftPointer < $rightPointer);

        $endTime = microtime(true);
        $memoryEnd = memory_get_usage();

        return [
            'time'   => $endTime - $startTime,
            'memory' => (($memoryEnd - $memoryStart) / 1000000) . ' Mb'
        ];
    }

    /**
     * Sorting with selection sort.
     *
     * @param array $array
     * @return array
     */
    private function selectionSort(array $array): array
    {
        $memoryStart = memory_get_usage();
        $startTime = microtime(true);

        $startPosition = 0;
        do {
            $maxElementIndex = $startPosition;

            for ($i = $startPosition + 1; $i < count($array); $i++) {
                if ($array[$i] < $array[$maxElementIndex]) {
                    $maxElementIndex = $i;
                }
            }

            if ($maxElementIndex !== $startPosition) {
                $buffer = $array[$startPosition];
                $array[$startPosition] = $array[$maxElementIndex];
                $array[$maxElementIndex] = $buffer;
            }

            $startPosition++;
        } while ($startPosition < (count($array) - 1));

        $endTime = microtime(true);
        $memoryEnd = memory_get_usage();

        return [
            'time'   => $endTime - $startTime,
            'memory' => (($memoryEnd - $memoryStart) / 1000000) . ' Mb'
        ];
    }

    /**
     * Sorting with insertion sort.
     *
     * @param array $array
     * @return array
     */
    private function insertionSort(array $array): array
    {
        $memoryStart = memory_get_usage();
        $startTime = microtime(true);

        for ($i = 0; $i < count($array); $i++) {
            if (!array_key_exists($i + 1, $array)) {
                continue;
            }

            $nextElement = $array[$i + 1];

            if ($nextElement >= $array[$i]) {
                continue;
            }

            for ($j = $i; $j >= 0; $j--) {
                if ($array[$j] <= $nextElement) {
                    break;
                }

                $array[$j + 1] = $array[$j];
                $array[$j] = $nextElement;
            }
        }

        $endTime = microtime(true);
        $memoryEnd = memory_get_usage();

        return [
            'time'   => $endTime - $startTime,
            'memory' => (($memoryEnd - $memoryStart) / 1000000) . ' Mb'
        ];
    }

    /**
     * Sorting with merge sort.
     *
     * @param array $array
     * @return array
     */
    private function mergeSort(array $array): array
    {
        $memoryStart = memory_get_usage();
        $startTime = microtime(true);

        $this->mergeSortFunc($array, 0, count($array) - 1);

        $endTime = microtime(true);
        $memoryEnd = memory_get_usage();

        return [
            'time'   => $endTime - $startTime,
            'memory' => (($memoryEnd - $memoryStart) / 1000000) . ' Mb'
        ];
    }

    /**
     * Merge sort recursive function.
     *
     * @param array $array
     * @param int $left
     * @param int $right
     */
    private function mergeSortFunc(array &$array, int $left, int $right): void
    {
        if ($left < $right) {
            $split = floor(($left + $right)/2);
            $this->mergeSortFunc($array, $left, $split);
            $this->mergeSortFunc($array, $split + 1, $right);
            $this->merge($array, $left, $split, $right);
        }
    }

    /**
     * Sort array.
     *
     * @param array $array
     * @param int $left
     * @param int $split
     * @param int $right
     */
    private function merge(array &$array, int $left, int $split, int $right): void
    {
        $leftPosition = $left;
        $rightPosition = $split + 1;
        $temp = [];

        while ($leftPosition <= $split && $rightPosition <= $right) {
            if ($array[$leftPosition] < $array[$rightPosition]) {
                array_push($temp, $array[$leftPosition]);
                $leftPosition++;
            } else {
                array_push($temp, $array[$rightPosition]);
                $rightPosition++;
            }
        }

        if ($leftPosition <= $split) {
            for ($i = $leftPosition; $i <= $split; $i++) {
                array_push($temp, $array[$i]);
            }
        }

        if ($rightPosition <= $right) {
            for ($i = $rightPosition; $i <= $right; $i++) {
                array_push($temp, $array[$i]);
            }
        }

        foreach ($temp as $sorted) {
            $array[$left++] = $sorted;
        }
    }

    /**
     * Sorting with qsort.
     *
     * @param array $array
     * @return array
     */
    private function qsort(array $array): array
    {
        $memoryStart = memory_get_usage();
        $startTime = microtime(true);

        $this->qsortFunc($array, 0, count($array) - 1);

        $endTime = microtime(true);
        $memoryEnd = memory_get_usage();

        return [
            'time' => $endTime - $startTime,
            'memory' => (($memoryEnd - $memoryStart) / 1000000) . ' Mb'
        ];
    }

    /**
     * @param array $array
     * @param int $left
     * @param int $right
     */
    private function qsortFunc(array &$array, int $left, int $right): void
    {
        if ($left < $right) {
            $pivotIndex = $this->partition($array, $left, $right);
            $this->qsortFunc($array, $left, $pivotIndex - 1);
            $this->qsortFunc($array, $pivotIndex, $right);
        }
    }

    /**
     * @param array $array
     * @param int $leftBorder
     * @param int $rightBorder
     * @return int
     */
    private function partition(array &$array, int $leftBorder, int $rightBorder): int
    {
        $root = $array[(int)($leftBorder + $rightBorder) / 2];
        $left = $leftBorder;
        $right = $rightBorder;

        while ($left <= $right) {
            while ($array[$left] < $root) {
                $left++;
            }
            while ($array[$right] > $root) {
                $right--;
            }

            if ($left <= $right) {
                $temp = $array[$left];
                $array[$left] = $array[$right];
                $array[$right] = $temp;

                $left++;
                $right--;
            }
        };

        return $left;
    }
}

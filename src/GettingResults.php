<?php

namespace App;

class GettingResults
{
    private function findInArray($element)
    {
        $keyword = $_GET['find'];
        $pattern = "/$keyword/i";
        if (is_array($element)) {
            return array_filter($element, [$this, "findInArray"]);
        }
        return preg_match($pattern, $element);
    }

    private function filterByParam($element)
    {
        $param = strtolower($_GET['filter']);
        $min = $_GET['min'] ?? '';
        $max = $_GET['max'] ?? '';

        if ($min && $max) {
            return $element[$param] >= $min && $element[$param] <= $max;
        } else if ($min) {
            return $element[$param] >= $min;
        } else {
            return $element[$param] <= $max;
        }
    }

    private function merge($left, $right, $sortBy, $sortType)
    {
        $res = [];
        while (count($left) > 0 && count($right) > 0) {
            if ($sortType == 'asc') {
                if ($left[0][$sortBy] > $right[0][$sortBy]) {
                    $res[] = $right[0];
                    $right = array_slice($right, 1);
                } else {
                    $res[] = $left[0];
                    $left = array_slice($left, 1);
                }
            } else {
                if ($left[0][$sortBy] > $right[0][$sortBy]) {
                    $res[] = $left[0];
                    $left = array_slice($left, 1);
                } else {
                    $res[] = $right[0];
                    $right = array_slice($right, 1);
                }
            }
        }
        while (count($left) > 0) {
            $res[] = $left[0];
            $left = array_slice($left, 1);
        }
        while (count($right) > 0) {
            $res[] = $right[0];
            $right = array_slice($right, 1);
        }
        return $res;
    }

    private function mergeSort($array, $sortBy, $sortType)
    {
        if (count($array) == 1) {
            return $array;
        }

        $mid = count($array) / 2;
        $left = array_slice($array, 0, $mid);
        $right = array_slice($array, $mid);
        $left = $this->mergeSort($left, $sortBy, $sortType);
        $right = $this->mergeSort($right, $sortBy, $sortType);
        return $this->merge($left, $right, $sortBy, $sortType);
    }

    private function sort($arr)
    {
        if (!isset($_GET['sort-by']) || !in_array(strtolower($_GET['sort-by']), ['price', 'rate']) || (isset($_GET['sort-type']) && !in_array(strtolower($_GET['sort-type']), ['asc', 'desc']))) {
            return $arr;
        }
        $sortBy = strtolower($_GET['sort-by']);
        $sortType = isset($_GET['sort-type']) ? strtolower($_GET['sort-type']) : 'desc';
        return $this->mergeSort($arr, $sortBy, $sortType);
    }

    private function paginate($arr)
    {
        if (!isset($_GET['page'])) {
            return $arr;
        }
        $numberPerPage = 5;
        $numberOfPages = ceil(count($arr) / $numberPerPage);
        $page = (floor($_GET['page']) > 0 && floor($_GET['page']) <= $numberOfPages) ?
        floor($_GET['page']) :
        (floor($_GET['page']) <= 0 ?
            1 : $numberOfPages);
        return [
            "data" => array_slice($arr, ($page - 1) * $numberPerPage, $numberPerPage),
            "pages" => $numberOfPages,
            "currentPage" => $page,
            "next" => $page + 1 <= $numberOfPages ? $page + 1 : null,
            "previous" => $page - 1 > 0 ? $page - 1 : null,
        ];
    }

    public function getResults(array $data)
    {
        if (isset($_GET['find'])) {
            $data = array_filter($data, [$this, "findInArray"]);
        }
        if (isset($_GET['filter']) && in_array(strtolower($_GET['filter']), ['price', 'rate']) && (isset($_GET['min']) || isset($_GET['max']))) {
            $data = array_filter($data, [$this, "filterByParam"]);
        }
        $data = $this->sort($data);
        $data = $this->paginate($data);

        return $data;
    }

}

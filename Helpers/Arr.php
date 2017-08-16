<?php

namespace Madedotcom\Bundle\Helpers;

final class Arr
{
    /**
     * Flatten a multi-dimensional associative array with dots.
     *
     * @param  array  $array
     * @param  string $prepend
     *
     * @return array
     */
    public static function dot($array, $prepend = '')
    {
        $results = [];

        foreach ($array as $key => $value) {
            if (is_array($value) && !empty($value)) {
                $results = array_merge($results, static::dot($value, $prepend . $key . '.'));
            } else {
                $results[$prepend . $key] = $value;
            }
        }

        return $results;
    }

    /**
     * Flatten a multi-dimensional array into a single level.
     *
     * @param  array $array
     * @param  int   $depth
     *
     * @return array
     */
    public static function flatten($array, $depth = INF)
    {
        return array_reduce($array, function ($result, $item) use ($depth) {
            if (!is_array($item)) {
                return array_merge($result, [$item]);
            }

            if ($depth === 1) {
                return array_merge($result, array_values($item));
            }

            return array_merge($result, static::flatten($item, $depth - 1));
        }, []);
    }
}

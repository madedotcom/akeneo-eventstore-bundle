<?php

namespace Madedotcom\Bundle\Helpers;

final class Str
{
    /**
     * Convert the given string to lower-case.
     *
     * @param  string $value
     *
     * @return string
     */
    public static function lower($value)
    {
        return mb_strtolower($value, 'UTF-8');
    }

    /**
     * Convert a string to snake case.
     *
     * @param  string $value
     * @param  string $delimiter
     *
     * @return string
     */
    public static function snake($value, $delimiter = '_')
    {
        // original snake function was: strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $value));
        if (!ctype_lower($value)) {
            $value = preg_replace('/\s+/u', '', $value);

            $value = static::lower(preg_replace('/(.)(?=[A-Z])/u', '$1' . $delimiter, $value));
        }

        return $value;
    }
}

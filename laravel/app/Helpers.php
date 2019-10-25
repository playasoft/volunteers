<?php

namespace App;

class Helpers
{
    /**
     * display name Helper
     * Return a burner name or an empty string.
     * If an alternative is given, it is used instead of the empty string.
     * If the alternative is a boolean false, it uses the users user name.
     * @param  User   $user         the user (non-null)
     * @param  string $alternative  the alternative display name
     * @return string               the appropriate display name
     */
    public static function displayName($user, string $alternative = '')
    {
        if ($user === null)
        {
            return $alternative;
        }

        // assume the display name will be the user name
        $display_name = $user->name;

        // if there exists a burner name, use it as the display name instead.
        if (!is_null($user->data) && !is_null($user->data->burner_name))
        {
            if (trim($user->data->burner_name) !== "")
            {
                $display_name = $user->data->burner_name;
            }
        }

        return $display_name;
    }

    /**
     * Find the subset key/value pairs of an array by given keys.
     *
     * @param Array $array  Superset Array
     * @param Array $keys   Subset Keys
     * @return Array        Subset Array
     */
    public static function subsetArray($array, $keys) 
    {
        if(!is_array($keys)) {
            $keys = [$keys];
        }
        $subset_associative_keys = array_flip($keys);
        return array_intersect_key($array, $subset_associative_keys);
    }

    /**
     * Turn timestamp strings in Carbon Objects.
     * If the timestamp is already a Carbon Object, return it.
     *
     * @param mixed $timestamp
     * @return Carbon
     */
    public static function carbonize($timestamp)
    {
        if(is_string($timestamp))
        {
            return Carbon::parse($timestamp);
        }
        return $timestamp;
    }
}

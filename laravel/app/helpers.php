<?php

namespace App;

class Helpers
{
    /**
     * display name Helper
     * Return a burner if the given user has one or return an empty string.
     * If $alt is false and no burner exists, return the user name of the user
     * instead.
     * If $alt is anything else and no burner exists, return the supplied $alt.
     * @param  User   $user   the user (non-null)
     * @param  string $alt    the alternative replacement for the burner name
     * @return string         the appropriate display name
     */
    public static function displayName($user, $alt='')
    {
        return (!is_null($user->data) &&
            !is_null($user->data->burner_name) &&
            trim($user->data->burner_name) !== "") ?
              $user->data->burner_name :
              (($alt === false) ? $user->name : $alt);
    }
}

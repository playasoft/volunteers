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
    public static function displayName($user, $alternative='')
    {
        // assume the display name will be the alternative name
        $display_name = $alternative;
        // if there exists a burner name, use it as the display name instead.
        if (!is_null($user->data) && !is_null($user->data->burner_name)) {
            if (trim($user->data->burner_name) !== "") {
                $display_name = $user->data->burner_name;
            }
        }
        // if there isn't a burner name, use the user name instead if there is
        // no alternative ($alternative === false)
        if ($display_name === $alternative && $alternative === false) {
            $display_name = $user->name;
        }
        return $display_name;
    }
}

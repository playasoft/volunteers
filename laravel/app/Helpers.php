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
     * Mail sender Helper
     * Sends an email and catches exceptions
     * @param string    $view       The name of the view containing the email
     * @param array     $data       Array of data to pass to the view
     * @param Closure   $callback   Closure which recieves the email instance
     */
    public static function sendMail($view, $data, $callback)
    {
        try
        {
            \Mail::send($view, $data, $callback);
        }
        catch(\Exception $exception)
        {
            if (config('app.debug'))
            {
                throw $exception;
            }
            \Log::error($exception);

            app('request')->session()->flash('warning', "Unable to send email, SMTP error. Please notify the administrator of this volunteer database.");
        }
    }
}

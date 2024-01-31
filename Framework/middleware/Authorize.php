<?php

namespace Framework\Middleware;

use Framework\Session;

class Authorize
{
    /**
     *Check if user is authenticate
     *
     *@return bool
     */

    public function isAuthenticate()
    {
        return Session::has('user');
    }

    /**
     *Handle the user request
     *
     *@param string $role
     *@return bool
     */

    public function handle($role)
    {
        if ($role === 'guest' && $this->isAuthenticate()) {
            return redirect('/');
        } elseif ($role === 'auth' && !$this->isAuthenticate()) {
            return redirect('/auth/login');
        }
    }
}

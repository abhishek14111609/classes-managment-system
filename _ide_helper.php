<?php

/**
 * IDE Helper for Laravel Auth
 * This file helps IDEs understand Laravel's auth() helper function.
 */

namespace {
    /**
     * @return \Illuminate\Contracts\Auth\Factory|\Illuminate\Contracts\Auth\Guard|\Illuminate\Contracts\Auth\StatefulGuard
     */
    function auth($guard = null)
    {
        return app('auth');
    }
}

namespace Illuminate\Contracts\Auth {
    /**
     * @method \App\Models\User|null user()
     * @method int|null id()
     * @method bool check()
     * @method bool guest()
     * @method void logout()
     */
    interface Guard {}

    /**
     * @method \App\Models\User|null user()
     * @method int|null id()
     * @method bool check()
     * @method bool guest()
     * @method void logout()
     * @method bool attempt(array $credentials = [], $remember = false)
     */
    interface StatefulGuard extends Guard {}
}

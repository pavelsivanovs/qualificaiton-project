<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Redirects the user to the previous page with a system error message.
     *
     * @return RedirectResponse
     */
    protected function redirectWithSystemError()
    {
        return redirect()->back()->with('error', 'Notikusi sistēmas kļūda. Lūdzu, mēģiniet vēlreiz!');
    }
}

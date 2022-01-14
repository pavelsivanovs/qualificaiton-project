<?php

namespace App\Http\Controllers;

/**
 * Lietotāju pārvaldes kontrolieris.
 * Kontrolieris īsteno lietotāju moduļa funkcijas.
 *
 * @author Pavels Ivanovs <paulivanov586@gmail.com> <pi15003@students.lu.lv>
 */

use App\Models\RequestStatus;
use App\Models\User;
use App\Models\UserAccountDeactivationRequest;
use App\Models\UserStatus;
use App\Models\UserStatusChangeRequest;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

/**
 * @UserController
 */
class UserController extends Controller
{
    /**
     * @const
     */
    const USER_EDIT_URL = '/user/edit';

    /**
     * Parādīt formu priekš konkrētas entītijas rediģēšanas.
     * Vienlaicīgi rāda arī tās pašas entītātes informāciju.
     *
     * Show the form for editing the specified resource.
     * At the same time displays user info.
     *
     * @return View
     */
    public function edit()
    {
        $user = Auth::user();
        $user_statuses = UserStatus::all();

        return view('user.edit', [
            'user' => $user,
            'user_statuses' => $user_statuses
        ]);
    }

    /**
     * Atjaunot konkrētas entītijas datus datubāzē.
     *
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return RedirectResponse|Redirector
     */
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'nullable|string',
            'surname' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:254|unique:users',
            'password' => 'nullable|string|min:8|max:30|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/',
            'password_repeated' => 'nullable|same:password',
            'telephone_number' => 'nullable|string|min:10|max:15',
            'profile_picture' => 'nullable|string|max:255'
        ]);

        $id = Auth::id();
        $new_data = array();

        $name = $request['name'];
        $surname = $request['surname'];
        $email = $request['email'];
        $password = $request['password'];
        $telephone_number = $request['telephone_number'];
        $profile_picture = $request['profile_picture'];

        if ($name) {
            $new_data['name'] = $name;
        }
        if ($surname) {
            $new_data['surname'] = $surname;
        }
        if ($email) {
            $new_data['email'] = $email;
        }
        if ($password) {
            $new_data['password'] = Hash::make($password);
        }
        if ($telephone_number) {
            $new_data['telephone_number'] = $telephone_number;
        }
        if ($profile_picture) {
            $new_data['profile_picture'] = $profile_picture;
        }

        if ($new_data != array()) {
            $new_data['updated_at'] = Carbon::now()->toDateTimeString();

            DB::table('users')->where('id', $id)->update($new_data);

            return redirect()->back()->with('message', 'Lietotāja informācija ir sekmīgi atjaunota!');
        }
        return redirect()->back()->with('message', 'nebūs');
    }

    /**
     * Izveidot pieteikumu uz lietotāja profila izslēgšanu.
     *
     * @return Application|RedirectResponse|Redirector
     */
    public function requestDeactivation()
    {
        $id = Auth::id();
        $deactivation_request = UserAccountDeactivationRequest::find($id);

        if ($deactivation_request) {
            return redirect(self::USER_EDIT_URL)->with('error', 'Konta izslēgšanas pieteikums jau ir izveidots!');
        }

        $deactivation_request = new UserAccountDeactivationRequest();
        $deactivation_request->user = $id;
        $deactivation_request->requestStatus = RequestStatus::STATUS_PENDING;
        $deactivation_request->setCreatedAt(Carbon::now()->toDateTimeString());
        $deactivation_request->save();

        return redirect()->back()
            ->with('message', 'Pieteikums lietotāju profila izslēgšanai ir izveidots sekmīgi.');
    }

    /**
     * Izveidot pieteikumu uz lietotāja statusa maiņu.
     *
     * @param Request $request
     * @return Application|RedirectResponse|Redirector
     */
    public function requestStatusChange(Request $request)
    {
        $request->validate([
            'new_status' => 'required|integer'
        ]);

        Log::debug('new_status', (array)$request);

        /** @var User $user */
        $user = Auth::user();
        $new_status = UserStatus::find($request['new_status']);

        if (!$new_status) {
            Log::debug('here1');
            return redirect()->back()
                ->with('error', 'Izvēlētais statuss neeksistē. Lūdzu, izvēlējieties citu statusu!');
        }

        if ($user->id == $new_status->id) {
            Log::debug('here2');
            return redirect()->back()->with('error', 'Izvēlētais statuss jau ir lietotājam.');
        }

        /** @var UserStatusChangeRequest $status_change_request */
        $status_change_request = UserStatusChangeRequest::firstWhere('user', $user->id);

        if ($status_change_request) {
            Log::debug('here3');
            return redirect()->back()
                ->with('error', 'Lietotāja statusa izmaiņas pieteikums jau ir izveidots!');
        }

        $status_change_request = new UserStatusChangeRequest();
        $status_change_request->user = $user->id;
        $status_change_request->user_requested_status = $new_status->id;
        $status_change_request->setCreatedAt(Carbon::now()->toDateTimeString());
        $status_change_request->save();

        return redirect()->back()
            ->with('message', 'Pieteikums lietotāja statusa maiņai ir izveidots sekmīgi.');
    }
}

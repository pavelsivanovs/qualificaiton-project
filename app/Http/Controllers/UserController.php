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
        return view('user.edit');
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
        $new_data = [];

        foreach ($request as $attribute => $value) {
            if ($request[$attribute]) {
                $new_data[$attribute] = $value;
            }
        }

        if (!empty($new_data)) {
            $new_data['updated_at'] = Carbon::now()->toDateTimeString();
            DB::table('users')->where('id', $id)->update($new_data);

            return redirect(self::USER_EDIT_URL)->with('message', 'Lietotāja informācija ir sekmīgi atjaunota!');
        }
        return redirect(self::USER_EDIT_URL);
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

        return redirect(self::USER_EDIT_URL)
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

        /** @var User $user */
        $user = Auth::user();
        $new_status = UserStatus::find($request['new_status']);

        if (!$new_status) {
            return redirect(self::USER_EDIT_URL)
                ->with('error', 'Izvēlētais statuss neeksistē. Lūdzu, izvēlējieties citu statusu!');
        }

        if ($user->id == $new_status->id) {
            return redirect('error', 'Izvēlētais statuss jau ir lietotājam.');
        }

        /** @var UserStatusChangeRequest $status_change_request */
        $status_change_request = UserStatusChangeRequest::firstWhere('user', $user->id);

        if ($status_change_request) {
            return redirect(self::USER_EDIT_URL)
                ->with('error', 'Lietotāja statusa izmaiņas pieteikums jau ir izveidots!');
        }

        $status_change_request = new UserStatusChangeRequest();
        $status_change_request->user = $user;
        $status_change_request->userRequestedStatus = $new_status;
        $status_change_request->setCreatedAt(Carbon::now()->toDateTimeString());
        $status_change_request->save();

        return redirect(self::USER_EDIT_URL)
            ->with('message', 'Pieteikums lietotāja statusa maiņai ir izveidots sekmīgi.');
    }
}

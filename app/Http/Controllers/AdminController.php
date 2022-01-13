<?php

namespace App\Http\Controllers;

use App\Mail\UserStatusChanged;
use App\Models\RequestStatus;
use App\Models\User;
use App\Models\UserAccountDeactivationRequest;
use App\Models\UserStatusChangeRequest;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Mail;

/**
 * @AdminController
 */
class AdminController extends Controller
{
    /**
     * Display an admin dashboard.
     *
     * @return Application|Factory|View|Response
     */
    public function index()
    {
        $status_change_requests = UserStatusChangeRequest::with(
            'request_status',
            RequestStatus::STATUS_PENDING
        )->get();
        $deactivation_requests = UserAccountDeactivationRequest::with(
            'request_status',
            RequestStatus::STATUS_PENDING
        )->get();

        return view('admin.index', [
            'status_change_requests' => $status_change_requests,
            'deactivation_requests' => $deactivation_requests
        ]);
    }

    /**
     * Display a list of user account deactivation requests.
     *
     * @return Application|Factory|View
     */
    public function indexDeactivationRequests()
    {
        $deactivation_requests = UserAccountDeactivationRequest::with(
            'request_status',
            RequestStatus::STATUS_PENDING
        )->get();

        return view('admin.deactivationRequest.index', ['requests' => $deactivation_requests]);
    }

    /**
     * Display a list of user status change requests.
     *
     * @return Application|Factory|View
     */
    public function indexStatusChangeRequests()
    {
        $status_change_requests = UserStatusChangeRequest::with(
            'request_status',
            RequestStatus::STATUS_PENDING
        )->get();

        return view('admin.statusChangeRequest.index', ['requests' => $status_change_requests]);
    }

    /**
     * Display the specified user account deactivation request.
     *
     * @param  int  $id
     * @return Application|Factory|RedirectResponse|View
     */
    public function showDeactivationRequest($id)
    {
        $request = UserAccountDeactivationRequest::find($id);

        if (!$request) {
            return $this->redirectWithSystemError();
        }
        return view('admin.request.show', ['request' => $request]);
    }

    /**
     * Display the specified user status change request.0
     *
     * @param $id
     * @return Application|Factory|View|RedirectResponse
     */
    public function showStatusChangeRequest($id)
    {
        $request = UserStatusChangeRequest::find($id);

        if (!$request) {
            return $this->redirectWithSystemError();
        }
        return view('admin.request.show', ['request' => $request]);
    }

    /**
     * Accept deactivation request.
     *
     * @param $request_id
     * @return Application|RedirectResponse|Redirector
     */
    public function acceptDeactivationRequest($request_id)
    {
        $deactivation_request = UserAccountDeactivationRequest::find($request_id);

        if (!$deactivation_request) {
            return $this->redirectWithSystemError();
        }

        /** @var User $user */
        $user = User::find($deactivation_request->user);
        $user->is_active = 0;
        $user->save();

        $deactivation_request->requestStatus = RequestStatus::STATUS_COMPLETED;
        $deactivation_request->save();

        return redirect('/admin/deactivationRequest/index')->with('message', 'Lietotājs ir izslēgts.');
    }

    /**
     * Decline deactivation request.
     *
     * @param $request_id
     * @return Application|RedirectResponse|Redirector
     */
    public function declineDeactivationRequest($request_id)
    {
        $deactivation_request = UserAccountDeactivationRequest::find($request_id);

        if (!$deactivation_request) {
            return $this->redirectWithSystemError();
        }

        $deactivation_request->requestStatus = RequestStatus::STATUS_DECLINED;
        $deactivation_request->save();

        return redirect('/admin/deactivationRequest/index');
    }

    /**
     * Accept user status change request.
     *
     * @param $request_id
     * @return Application|RedirectResponse|Redirector
     */
    public function acceptStatusChangeRequest($request_id)
    {
        $request = UserStatusChangeRequest::find($request_id);

        if (!$request) {
            return $this->redirectWithSystemError();
        }

        $user = User::find($request->user);
        $old_status = $user->status;
        $user->status = $request->userRequestedStatus;
        $user->save();

        $request->requestStatus = RequestStatus::STATUS_COMPLETED;
        $request->save();

        Mail::to($user)->send(new UserStatusChanged($old_status, $user->status));

        return redirect('/admin/statusChangeRequest/index')->with('message', 'Lietotāja statuss ir mainīts.');
    }

    /**
     * Decline user status change request.
     *
     * @param $request_id
     * @return Application|RedirectResponse|Redirector
     */
    public function declineStatusChangeRequest($request_id)
    {
        $request = UserStatusChangeRequest::find($request_id);

        if (!$request) {
            return $this->redirectWithSystemError();
        }

        $request->requestStatus = RequestStatus::STATUS_DECLINED;
        $request->save();

        return redirect('/admin/statusChangeRequest/index');
    }
}

<?php

namespace App\Http\Controllers;

use App\Mail\CommentAdded;
use App\Models\Comment;
use App\Models\Picture;
use App\Models\Project;
use App\Models\Task;
use App\Models\TaskPicture;
use App\Models\TaskStatus;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * @TaskController
 */
class TaskController extends Controller
{
    /**
     * @const
     */
    const CREATE_URL = '/task/create';

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View|Response
     */
    public function create()
    {
        /** @var User $user */
        $user = Auth::user();

        $projects = $user->isProjectManager()
            ? Project::with('project_manager', $user->id)->orderBy('title')->get()
            : Project::all();
        $assignees = User::with('is_active', 1);

        return view('task.create', [
            'user' => $user,
            'projects' => $projects,
            'assignees' => $assignees
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Application|RedirectResponse|Response|Redirector
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|min:2|max:100',
            'description' => 'required|string|min:2|max:2000',
            'project' => 'required|integer',
            'assignee' => 'required|integer',
            'deadline' => 'nullable|date|after_or_equal:today',
            'pictures' => 'nullable|array'
        ]);

        /** @var User $user */
        $user = Auth::user();
        /** @var Project $project */
        $project = Project::find($request['project']);

        if (!$project) {
            return redirect()->back()->with('error', 'Notikusi sistēmas kļūda. Lūdzu, mēģiniet vēlreiz!');
        }

        if ($user->isProjectManager()) {
            if ($project->projectManager != $user) {
                return redirect()->back()->with('error', 'Izvēlētam projektam nevar piesaistīt uzdevumu!');
            }
        }

        /** @var User $assignee */
        $assignee = User::find($request['assignee']);
        if (!$assignee || !$assignee->is_active) {
            return redirect()->back()->with('error', 'Izvēlētam lietotājam nevar piesaistīt uzdevumu!');
        }

        $task = new Task();
        $task->title = $request['title'];
        $task->description = $request['description'];
        $task->project = $request['project'];
        $task->assignee = $request['assignee'];
        $task->deadline = $request['deadline'];
        $task->save();


        foreach ($request['pictures'] as $picture_id) {
            Log::debug('Adding picture ' . $picture_id . ' to task ' . $task->id);

            $picture = Picture::find($picture_id);

            if (!$picture) {
                return redirect()->back()->with('error', 'Notikusi sistēmas kļūda. Lūdzu, mēģiniet vēlreiz!');
            }

            $task_picture = new TaskPicture();
            $task_picture->task = $task;
            $task_picture->picture = $picture;
            $task_picture->save();
        }

        return view('task.show', ['task' => $task]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Application|Factory|View|RedirectResponse
     */
    public function show($id)
    {
        /** @var Task $task */
        $task = Task::find($id);
        $pictures = $task->pictures();
        $comments = $task->comments->sortByDesc('created_at');
        $accent_color = $task->taskProject->accent_color ?? env('PRIMARY_COLOR');
        $task_statuses = TaskStatus::all();
        $can_edit = Auth::user()->isAdmin() || Auth::user() == $task->taskProject->projectManager;

        return $task ? view('task.show', [
            'task' => $task,
            'pictures' => $pictures,
            'comments' => $comments,
            'accent_color' => $accent_color,
            'task_statuses' => $task_statuses,
            'can_edit' => $can_edit
        ]) : redirect()->back()->with('error', 'Notikusi sistēmas kļūda. Lūdzu, mēģiniet vēlreiz!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Application|Factory|View|RedirectResponse
     */
    public function edit($id)
    {
        /** @var User $user */
        $user = Auth::user();

        $task = Task::find($id);
        $projects = $user->isProjectManager()
            ? Project::with('project_manager', $user->id)->orderBy('title')->get()
            : Project::all();
        $assignees = User::with('is_active', 1)->get();
//        $task_pictures = DB::table('pictures')
//            ->leftJoin('task_pictures', 'pictures.id', '=', 'task_pictures.picture')
//            ->where('task_pictures.task', '=', $task->id)
//            ->get();
        $task_pictures = $task->pictures(); // todo test if works

        return $task ? view('task.edit', [
            'task' => $task,
            'user' => $user,
            'projects' => $projects,
            'assignees' => $assignees,
            'task_pictures' => $task_pictures
        ])
            : redirect()->back()->with('error', 'Notikusi sistēmas kļūda. Lūdzu, mēģiniet vēlreiz!');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return Application|RedirectResponse|Response|Redirector
     */
    public function update(Request $request, int $id)
    {
        $request->validate([
            'title' => 'nullable|string|min:2|max:100',
            'description' => 'nullable|string|min:2|max:2000',
            'project' => 'nullable|integer',
            'assignee' => 'nullable|integer',
            'deadline' => 'nullable|date|after_or_equal:today',
            'pictures' => 'nullable|array'
        ]);

        /** @var User $user */
        $user = Auth::user();
        /** @var Task $task */
        $task = Task::find($id);

        if (!$task) {
            redirect()->back()->with('error', 'Notikusi sistēmas kļūda. Lūdzu, mēģiniet vēlreiz!');
        }

        if ($request['title']) {
            $task->title = $request['title'];
        }

        if ($request['description']) {
            $task->description = $request['description'];
        }

        if ($request['project']) {
            $project = Project::find($request['project']);

            if (!$project) {
                return redirect()->back()
                    ->with('error', 'Notikusi sistēmas kļūda. Lūdzu, mēģiniet vēlreiz!');
            }

            if ($user->isProjectManager()) {
                if ($project->projectManager != $user) {
                    return redirect()->back()
                    ->with('error', 'Izvēlētam projektam nevar piesaistīt uzdevumu!');
                }
            }

            $task->project = $project;
        }

        if ($request['assignee']) {
            $assignee = User::find($request['assignee']);

            if (!$assignee || !$assignee->is_active) {
                return redirect()->back()->with('error', 'Izvēlētam lietotājam nevar piesaistīt uzdevumu!');
            }

            $task->assignee = $assignee;
        }

        if ($request['deadline']) {
            $task->deadline = $request['deadline'];
        }

        $task->setUpdatedAt(Carbon::now()->toDateTimeString());
        $task->save();

        // in the request we get IDs of the NEW pictures, the old ones are being deleted via POST request
        if ($request['pictures']) {
            foreach ($request['pictures'] as $picture_id) {
                $picture = Picture::find($picture_id);

                if (!$picture) {
                    return redirect()->back()->with('error', 'Notikusi sistēmas kļūda. Lūdzu, mēģiniet vēlreiz!');
                }

                $task_picture = new TaskPicture();
                $task_picture->task = $task;
                $task_picture->picture = $picture;
                $task_picture->save();
            }
        }
        return $this->show($task->id);
    }

    /**
     * Remove the specified resource from storage.
     * TODO requires testing
     *
     * @param  int  $id
     * @return RedirectResponse|Response
     */
    public function destroy($id)
    {
        /** @var User $user */
        $user = Auth::user();
        /** @var Task $task */
        $task = Task::find($id);
        /** @var Project $project */
        $task_project = Project::find($task->project);

        if ($user->isProjectManager() && $user != $task_project->projectManager) {
            return redirect()->back()->with('error', 'Jums nav tiesību veikt šo darbību!');
        }

        // deleting the associated pictures
        /** @var Picture[] $pictures */
        $pictures = $task->pictures;
        $task_pictures = DB::table('task_pictures')->where('task', '=', $task->id)->get();
        $task_pictures->delete();

        foreach ($pictures as $picture) {
            $picture->delete();
        }

        $task->delete();

        return redirect(ProjectController::SHOW_URL . '/' . $task_project->id)
            ->with('message', 'Uzdevums ir sekmīgi izdzēsts!');
    }

    /**
     * Add comment to the task.
     *
     * @param Request $request
     * @param $task_id
     * @return RedirectResponse
     */
    public function addComment(Request $request, $task_id)
    {
        $request->validate([
            'comment' => 'required|string|max:2000'
        ]);

        /** @var User $user */ // note: maybe wrong
        $user = Auth::user();
        /** @var Task $task */
        $task = Task::find($task_id);

        if (!$task) {
            return redirect()->back()->with('error', 'Notikusi sistēmas kļūda. Lūdzu, mēģiniet vēlreiz!');
        }

        $comment = new Comment();
        $comment->task = $task->id;
        $comment->author = $user->id;
        $comment->comment = $request['comment'];
        $comment->setCreatedAt(Carbon::now()->toDateTimeString());
        $comment->save();

        $assignee = $task->taskAssignee;
        $project_manager = $task->taskProject->projectManager;

        if ($user == $assignee) {
            Mail::to($project_manager)->send(new CommentAdded($task, $user));
        } else if ($user == $project_manager) {
            Mail::to($assignee)->send(new CommentAdded($task, $user));
        } else {
            Mail::to($project_manager)->send(new CommentAdded($task, $user));
            Mail::to($assignee)->send(new CommentAdded($task, $user));
        }

        return redirect()->back()->with('message', 'Komentārs ir pievienots!');
    }

    /**
     * Change to the status of the task.
     *
     * @param $task_id
     * @param $new_status_id
     * @return RedirectResponse|void
     */
    public function changeStatus($task_id, $new_status_id)
    {
        /** @var Task $task */
        $task = Task::find($task_id);
        /** @var TaskStatus $new_status */
        $new_status = TaskStatus::find($new_status_id);

        if (!$task || !$new_status) {
            return redirect()->back()->with('error', 'Notikusi sistēmas kļūda. Lūdzu, mēģiniet vēlreiz!');
        }

        if ($new_status->id == $task->status) {
            return redirect()->back()->with('error', 'Uzdevuma esošs statuss sakrīt ar jaunu!');
        }

        $task->status = $new_status->id;
        $task->save();

        $comment = new Comment();
        $comment->author = Auth::user()->id;
        $comment->task = $task->id;
        $comment->comment = 'Nomainīja uzdevuma statusu uz \'' . $new_status->description . '\'';
        $comment->setCreatedAt(Carbon::now()->toDateTimeString());
        $comment->save();

        return redirect()->back()->with('message', 'Statuss ir nomainīts!');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Picture;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    CONST SHOW_URL = '/project/show';

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View|Response
     */
    public function index()
    {
        $projects = Project::all();

        return view('project.index', ['projects' => $projects]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View|Response
     */
    public function create()
    {
        return view('project.create');
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
            'title'=> 'required|string|min:2|max:100',
            'description'=> 'required|string|min:2|max:2000',
            'tasks' => 'nullable|array',
            'picture' => 'nullable|integer',
            'accent_color' => 'nullable|string|min:6|max:6'
        ]);

        $user = Auth::user();

        $project = new Project();
        $project->title = $request['title'];
        $project->description = $request['description'];
        $project->projectManager = $user;

        $picture = Picture::find($request['picture']);
        $accent_color = $request['accent_color'];

        if ($picture) {
            $project->icon = $picture;
        }

        if ($accent_color) {
            $project->accentColor = $accent_color;
        }

        $project->save();

        $tasks_id = $request['tasks'];

        foreach ($tasks_id as $task_id) {
            /** @var Task $task */
            $task = Task::find($task_id);

            if ($task) {
                $task->project = $project;
                $task->save();
            } else {
                return redirect('/project/edit/' . $project->id)
                    ->with('error', 'Izvēlēto/-s uzdevumu/-s nevar piesaistīt!');
            }
        }
        return redirect('/project/show/' . $project->id)
            ->with('message', 'Projekts ir izveidots sekmīgi!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Application|Factory|View|RedirectResponse|Response
     */
    public function show($id)
    {
        /** @var Project $project */
        $project = Project::find($id);

        if (!$project) {
            return redirect()->back()->with('error', 'Notikusi sistēmas kļūda. Lūdzu, mēģiniet vēlreiz!');
        }

        $tasks = Task::with('project', $project->id)->orderByDesc('updated_at')->get();

        return view('project.show', [
            'project' => $project,
            'tasks' => $tasks,
            'accent_color' => $project->accentColor
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Application|Factory|View|RedirectResponse|Response
     */
    public function edit($id)
    {
        /** @var Project $project */
        $project = Project::find($id);

        if (!$project) {
            return redirect()->back()->with('error', 'Notikusi sistēmas kļūda. Lūdzu, mēģiniet vēlreiz!');
        }
        return view('project.edit', [
            'project' => $project
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return RedirectResponse|Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title'=> 'nullable|string|min:2|max:100',
            'description'=> 'nullable|string|min:2|max:2000',
            'tasks' => 'nullable|array',
            'picture' => 'nullable|integer',
            'accent_color' => 'nullable|string|min:6|max:6'
        ]);

        /** @var Project $project */
        $project = Project::find($id);

        if ($request['title']) {
            $project->title = $request['title'];
        }

        if ($request['description']) {
            $project->description = $request['description'];
        }

        if ($request['picture']) {
            $picture = Picture::find($request['picture']);

            if (!$picture) {
                return redirect()->back()->with('error', 'Notikusi sistēmas kļūda. Lūdzu, mēģiniet vēlreiz!');
            }

            $project->icon = $picture;
        }

        if ($request['accent_color']) {
            $project->accentColor = $request['accent_color'];
        }

        $project->save();

        if ($request['tasks']) {
            $tasks = $request['tasks'];

            foreach ($tasks as $task_id) {
                $task = Picture::find($task_id);

                if (!$task) {
                    return redirect()->back()->with('error', 'Notikusi sistēmas kļūda. Lūdzu, mēģiniet vēlreiz!');
                }

                $task->project = $project;
                $task->save();
            }
        }

        return redirect('/project/show/' . $project->id)
            ->with('message', 'Projekts ir rediģēts sekmīgi!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Application|RedirectResponse|Response|Redirector
     */
    public function destroy($id)
    {
        /** @var Project $project */
        $project = Project::find($id);

        if (!$project) {
            return redirect()->back()->with('error', 'Notikusi sistēmas kļūda. Lūdzu, mēģiniet vēlreiz!');
        }

        $tasks = $project->tasks;

        /** @var Task $task */
        foreach ($tasks as $task) {
            $task->delete();
        }

        $project->delete();

        return redirect('/project/index')->with('message', 'Projekts ir sekmīgi izdzēsts!');
    }

    /**
     * Add task to the project.
     *
     * @param $task_id
     * @return RedirectResponse|void
     */
    public function addTask($project_id,$task_id)
    {
        /** @var User $user */
        $user = Auth::user();
        $project = Project::find($project_id);
        $task = Task::find($task_id);

        if ($user->isProjectManager() || $project->projectManager != $user) {
            return redirect()->back()->with('error', 'Jums nav tiesību veikt šo darbību!');
        }

        if (!$task) {
            return redirect()->back()->with('error', 'Izvēlēto/-s uzdevumu/-s nevar piesaistīt!');
        }

        $task->project = $project;
        $task->save();

        return redirect()->back()->with('message', 'Uzdevums ir piesaistīts projektam!');
    }
}

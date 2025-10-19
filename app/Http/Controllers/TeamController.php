<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Team;
use App\Models\TeamTask;
use App\Models\TeamTaskReport;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       if(!Auth::user()->hasRole('admin'))   {
        abort(403, 'Unauthorized action.');
       }
       $userId = auth()->id(); // ID авторизованого користувача

        $teams = Team::whereHas('users', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->get();

        return view('teams.index', compact('teams'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if(!Auth::user()->hasRole('admin'))  
        {
            abort(403, 'Unauthorized action.');
        }
        $boss = auth()->user()->relatedBack()->first();
        $relatedUsers = auth()->user()->relatedUsers()->get();
        $user = auth()->user();
        return view('teams.create', compact('boss', 'relatedUsers', 'user'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if(!Auth::user()->hasRole('admin'))  
        {
            abort(403, 'Unauthorized action.');
        }
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // 1. Створюємо команду
        $team = Team::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        // 2. Формуємо список користувачів
        $users = $request->input('users', []);

        // Додаємо користувачів по ТН
        if ($request->filled('add_personal_tn')) {
            $tns = array_map('trim', explode(';', $request->add_personal_tn));
            foreach ($tns as $tn) {
                $user = User::where('tn', $tn)->first();
                if ($user && !in_array($user->id, $users)) {
                    $users[] = $user->id;
                }
            }
        }

        // Обов’язково додаємо автора
        if (!in_array(auth()->id(), $users)) {
            $users[] = auth()->id();
        }

        // 3. Додаємо користувачів у teams_users без дублювання
        $team->users()->syncWithoutDetaching($users);

        // 4. Формуємо ролі
        $roles = [];

        // Admin (boss)
        $rolesb = $request->input('rolesb', []);
        foreach ($rolesb as $userId) {
            $roles[$userId] = 'admin';
        }

        // Member
        $rolesm = $request->input('rolesm', []);
        foreach ($rolesm as $userId) {
            if (!isset($roles[$userId])) {
                $roles[$userId] = 'member';
            }
        }

        // Обов’язково автор як admin
        if (!isset($roles[auth()->id()])) {
            $roles[auth()->id()] = 'admin';
        }

        // 5. Додаємо у teams_users_role, уникаючи дублювання
        foreach ($roles as $userId => $role) {
            $team->usersWithRole()->syncWithoutDetaching([
                $userId => ['role' => $role]
            ]);
        }

        return redirect()->route('teams.index')->with('success', 'Team created successfully.');
    }



    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $userId = auth()->id();

        // Отримуємо всі команди, де користувач є учасником
        $teams = Team::whereHas('users', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->get();

        // Якщо параметр ?team не переданий — беремо першу команду користувача
        $team_id = $request->team ?? $teams->first()?->id;

        // Якщо у користувача немає команд — повертаємо порожню сторінку
        if (!$team_id) {
            return view('teams.show', [
                'teams' => $teams,
                'tasks' => collect(),
                'closedTasks' => collect(),
                'team_id' => null
            ]);
        }

        // ID авторизованого користувача
        $id_auth = $userId;

        // Завдання, які не призначені користувачу
        $tasks_without_auth = TeamTask::where('team_id', $team_id)
            ->where('status', '!=', 'completed')
            ->where('assignee_id', '!=', $id_auth)
            ->where('due_at', '<=', now())
            ->get();

        // Завдання, які призначені користувачу
        $tasks_with_auth = TeamTask::where('team_id', $team_id)
            ->where('status', '!=', 'completed')
            ->where('assignee_id', $id_auth)
            ->where('due_at', '<=', now())
            ->get();

        // Об’єднуємо
        $tasks = $tasks_with_auth->merge($tasks_without_auth);

        // Виконані сьогодні
        $closedTasks = TeamTask::where('team_id', $team_id)
            ->where('status', 'completed')
            ->whereDate('updated_at', now()->toDateString())
            ->get();

        return view('teams.show', compact('teams', 'tasks', 'closedTasks', 'team_id'));
    }

    // колендар завдань всі завдання дивимось дні тижня як виконувались ким і що плануеться
    public function calendar()
    {
        //
       $userId = auth()->id(); // ID авторизованого користувача

        $teams = Team::whereHas('users', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->get();
        // вибираємо всі завдання, які належать команді з вказаним командами
        $tasks = TeamTask::whereIn('team_id', $teams->pluck('id'))
        ->with('reports')
        ->get();
        return view('teams.calendar', compact('teams', 'tasks'));

    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        if(!Auth::user()->hasRole('admin'))  
        {
            abort(403, 'Unauthorized action.');
        }
        $team = Team::with('usersWithRole')->findOrFail($id);

        $user = auth()->user();
        $boss = $user->relatedBack()->first();
        $relatedUsers = $user->relatedUsers()->get();
        $usersteam=$team->users;

        // зібрати можливих користувачів
        $users = collect([$user])
            ->when($boss, fn($c) => $c->push($boss))
            ->merge($relatedUsers)
            ->merge($usersteam)
            ->unique('id');

        // підготувати масив ролей користувачів цієї команди
        $teamUsers = [];
        foreach ($team->usersWithRole as $u) {
            $teamUsers[$u->id] = $u->pivot->role;
        }

        return view('teams.edit', compact('team', 'users', 'teamUsers'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if(!Auth::user()->hasRole('admin'))  
        {
            abort(403, 'Unauthorized action.');
        }
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $team = Team::findOrFail($id);

        // оновлюємо базові дані
        $team->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        // очищаємо старих учасників
        $team->users()->detach();
        $team->usersWithRole()->detach();
        $users = $request->input('users', []);
        $rolesb = $request->input('rolesb', []);
        $rolesm = $request->input('rolesm', []);

        // Додаємо користувачів по ТН
        if ($request->filled('add_personal_tn')) {
            $tns = array_map('trim', explode(';', $request->add_personal_tn));
            foreach ($tns as $tn) {
                $user = User::where('tn', $tn)->first();
                if ($user && !in_array($user->id, $users)) {
                    $users[] = $user->id;
                    $rolesb[] = $user->id;
                }
            }
        }
        // додаємо нових

            foreach ($users as $userId) {
                // базовий зв’язок
                $team->users()->attach($userId);

                // ролі
                if ($rolesb && in_array($userId, $rolesb)) {
                    $team->usersWithRole()->attach($userId, ['role' => 'admin']);
                }
                if ($rolesm && in_array($userId, $rolesm)) {
                    $team->usersWithRole()->attach($userId, ['role' => 'member']);
                }
            }


        return redirect()->route('teams.index')->with('success', 'Team updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if(!Auth::user()->hasRole('admin'))  
        {
            abort(403, 'Unauthorized action.');
        }
        $team = Team::findOrFail($id);
        $team->users()->detach();
        $team->usersWithRole()->detach();

        $team->delete();
        return redirect()->route('teams.index')->with('success', 'Team deleted successfully.');
    }

    // storeTask
    public function storeTask(Request $request)
    {
        $request->validate([
            'team_id' => 'required|exists:teams,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'priority' => 'nullable|in:low,medium,high',
            'assignee_id' => 'nullable|exists:users,id',
        ]);
        $due_date = $request->due_date ? date('Y-m-d', strtotime($request->due_date)) : now()->toDateString();
        $due_date = $this->shiftDateIfWeekend($due_date);

        $task = TeamTask::create([
            'team_id' => $request->team_id,
            'title' => $request->title,
            'description' => $request->description,
            'creator_id' => auth()->id(),
            'assignee_id' => $request->assignee_id,
            'type' => $request->type ?? 'once',
            'recurrence' => $request->recurrence ?? null,
            'start_at' => $request->start_at ?? now(),
            'due_at' => $request->due_date,
            'next_run_at' => $request->due_date,
            // 'pending','in_progress','completed','cancelled'
            'status' => $request->status ?? 'pending',
            'parent_task_id' => null
        ]);
        // якщо потрібно згенерувати повтори
        if($request->type && $request->type!='once' && $request->generate_times && $request->due_date){
            $this->generate_times($task,$request->generate_times,$request->type,$request->due_date);
        }

        return redirect()->back()->with('success', 'Task created successfully.');
    }
    
    //updateTask
    public function updateTask(Request $request, string $id)
    {
        $task = TeamTask::findOrFail($id);

        // Перевірка, чи є користувач виконавцем завдання
        if ($task->creator_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $task->title = $request->title;
        $task->description = $request->description;
        $task->assignee_id = $request->assignee_id;
        $task->due_at = $request->due_date;       
        $task->save();
        if($task->type && $task->type!='once' && $request->generate_times && $request->due_date){
            $this->generate_times($task,$request->generate_times,$task->type,$request->due_date);
        }
        return redirect()->back()->with('success', 'Task updated successfully.');
    }

   public function generate_times($task, $generate_times, $type, $due_date, $allowed_dates = [])
    {
        for ($i = 1; $i <= $generate_times; $i++) {
            // Обчислення наступної дати
            if ($type == 'daily') {
                $due_date = date('Y-m-d', strtotime($due_date . ' +1 day'));
            }
            if ($type == 'weekly') {
                $due_date = date('Y-m-d', strtotime($due_date . ' +1 week'));
            }
            // щоквартально
            if ($type == 'quarterly') {
                $due_date = date('Y-m-d', strtotime($due_date . ' +3 months'));
            }
            // щопівріччя
            if ($type == 'biannually') {
                $due_date = date('Y-m-d', strtotime($due_date . ' +6 months'));
            }
            if ($type == 'monthly') {
                $due_date = date('Y-m-d', strtotime($due_date . ' +1 month'));
            }
            if ($type == 'yearly') {
                $due_date = date('Y-m-d', strtotime($due_date . ' +1 year'));
            }

            // Перевірка на вихідний день
            $dayOfWeek = date('N', strtotime($due_date)); // 6 = Saturday, 7 = Sunday
            if ($dayOfWeek >= 6) {
                continue; // пропускаємо суботу та неділю
            }

            // Якщо заданий масив дозволених дат — перевіряємо
            if (!empty($allowed_dates) && !in_array($due_date, $allowed_dates)) {
                continue;
            }


            $due_date = $this->shiftDateIfWeekend($due_date);
            // Перевірка на дублювання
            $exists = TeamTask::where('team_id', $task->team_id)
                ->where('title', $task->title)
                ->where('description', $task->description)
                ->whereDate('due_at', $due_date)
                ->exists();

            if ($exists) {
                continue; // пропускаємо дублікати
            }

            // Створення нового завдання
            TeamTask::create([
                'team_id' => $task->team_id,
                'title' => $task->title,
                'description' => $task->description,
                'creator_id' => $task->creator_id,
                'assignee_id' => $task->assignee_id,
                'type' => $type,
                'recurrence' => $task->recurrence,
                'start_at' => $due_date,
                'due_at' => $due_date,
                'next_run_at' => $due_date,
                'status' => 'pending',
                'parent_task_id' => $task->id
            ]);
        }
    }
    // внутрешня функція для смещення дати якщо попадає на вихідний - 1, 2 дні (раніше)
    public function shiftDateIfWeekend($date) {
        $dayOfWeek = date('N', strtotime($date));
        if ($dayOfWeek == 6) { // Субота
            return date('Y-m-d', strtotime($date . ' -1 day'));
        } elseif ($dayOfWeek == 7) { // Неділя
            return date('Y-m-d', strtotime($date . ' -2 days'));
        }
        return $date;
    }

    // storeReport
    public function storeReport(Request $request)
    {
        $request->validate([
            'task_id' => 'required|exists:team_task,id',
            'report' => 'required|string',
            'attachment' => 'nullable|string|max:255',
        ]);
        $task = TeamTask::findOrFail($request->task_id);
        // status
        $task->status = 'completed';
        $task->save();

        $data = $request->only('task_id', 'report');
        $data['user_id'] = auth()->id();

        if ($request->filled('attachment')) {
            $data['attachment'] = $request->attachment;
        }

        TeamTaskReport::create($data);
        
        return redirect()->back()->with('success', __('Report submitted successfully.'));

    }
}

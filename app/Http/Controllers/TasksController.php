<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Task;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [];
        if (\Auth::check()) {
                $user = \Auth::user();
                $tasks = $user->tasks()->orderBy('created_at' , 'desc')->paginate(10);
                $data = [
                    'user' => $user,
                    'tasks' => $tasks,
                ];
        }
        return view('tasks.index', $data );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       $task = new Task;

        return view('tasks.create', [
            'task' => $task,
        ]);
        return redirect('/');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'status' => 'required|max:10',
            'content' => 'required|max:255',
        ]);
        // メッセージを作成
        $task = new Task;
        $task->user_id = $request->user()->id;
        $task->content = $request->content;
        $task->status = $request->status;
        $task->save();
        
        // トップページリダイレクトさせる
        return redirect('/');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $task = \App\Task::find($id);
        
        if (\Auth::check() == $task->user_id) {
                $task = Task::find($id);

                return view('tasks.show', [
                'task' => $task,
            ]);
            return redirect('/');
        }
        return redirect('/');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
         $task = \App\Task::find($id);
        
        if (\Auth::id() === $task->user_id) {
            $task = Task::find($id);

            return view('tasks.edit', [
                'task' => $task,
            ]);
            return redirect('/');
        }
        return redirect('/');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|max:10',
            'content' => 'required|max:255',
        ]);
        // idの値でタスクを検索して取得
        $task = Task::findOrFail($id);
        // タスクを更新
        $task->content = $request->content;
        $task->status = $request->status;
        $task->save();
        
        // トップページリダイレクトさせる
        return redirect('/');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $task = \App\Task::find($id);
        
        if (\Auth::id() === $task->user_id) {
            $task = Task::find($id);
            $task->delete();
            
            return redirect('/');
        }
        return redirect('/');
    }
}

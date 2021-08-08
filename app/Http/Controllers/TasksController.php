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
            $tasks = $user->tasks()->orderBy('created_at', 'desc')->paginate(10);
            $data = [
                
                'tasks' => $tasks,
            ];
        }
        
        return view('index', $data);
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
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // バリデーション
        $request->validate([
            'status' =>'required|max:255',
            'content' => 'required|max:255',
        ]);

        // 認証済みユーザのタスクとして作成（リクエストされた値をもとに作成）
        $request->user()->tasks()->create([
            'status' =>$request->status,
            'content' =>$request->content,
        ]);
        
        // 前のURLへリダイレクトさせる
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     
    public function show($id)
    {
       $task = Task::findOrFail($id);
         
        if (\Auth::id() === $task->user_id) {
        return view('tasks.show', [
             'task' => $task,
         ]);
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
        
        $task = Task::findOrFail($id);
        
        if (\Auth::id() === $task->user_id) {
        return view('tasks.edit', [
            'task' => $task,
         ]);
        }
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
        
        $task = Task::findOrFail($id);
        
       if (\Auth::id() === $task->user_id) {
        $task->status = $request->status;
        $task->content = $request->content;
        $task->save();
       }
       
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
        // idの値でタスクを検索して取得
        $task = \App\Task::findOrFail($id);

        // 認証済みユーザ（閲覧者）がそのタスクの所有者である場合は、タスクを削除
        if (\Auth::id() === $task->user_id) {
            $task->delete();
        }

        // 前のURLへリダイレクトさせる
        return back();
    }
}

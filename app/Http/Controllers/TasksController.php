<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Task;

use App\User;


class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $data = [];
        if (\Auth::check()){
             $user = \Auth::user();
            $tasks = $user->tasks()->orderBy('created_at', 'desc')->paginate(10);
            
            $data = [
                  'user' => $user,
                  'tasks' => $tasks,
                ];
                
                return view('tasks.index', $data);
            
        }else{

            return view('welcome');
        }
        
    }
    
    
    
    
    
    public function create()
    {
        $task = new Task;
        
        return view('tasks.create',[
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
        $this->validate($request,[
                'content' => 'required|max:191',
                'status' => 'required|max:10',
            ]);
            
           
            
             $request->user()->tasks()->create([
                    'content' => $request->content,
                    'status' => $request->status,
                ]);
                
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
        
        $task = Task::find($id);
        
        if ($task->user_id === \Auth::id()){
            return view('tasks.show',['task' => $task,]);
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
        $task = Task::find($id);
        
        if ($task->user_id === \Auth::id()){
            return view('tasks.edit',['task' => $task,]);
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
        $this->validate($request,[
                'content' => 'required|max:191',
                'status' => 'required|max:10',
            ]);
        
        $task = Task::find($id);
        
         if ($task->user_id === \Auth::id()){
           
            $task->content = $request->content;
            $task->status = $request->status;
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
        
        $task = \App\Task::find($id);
        
        if (\Auth::id() === $task->user_id){
            return view('tasks.destroy', ['task' => $task,]);
        }
            
            return redirect('/');
    }
}

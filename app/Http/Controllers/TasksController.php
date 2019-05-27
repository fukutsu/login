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
            $tasklists = $user->tasklists()->orderBy('created_at', 'desc')->paginate(10);
            
            $data = [
                  'user' => $user,
                  'tasklists' => $tasklists,
                ];
                $data += $this->counts($user);
                
                $tasks = Task::all();
                
                return view('tasks.index',[
                        'tasks' => $tasks,
                    ]);
                    
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
            
            $task = new Task;
            $task->content = $request->content;
            $task->status = $request->status;
            $task->save();
            
            return redirect('/');
            
             $request->user()->tasklists()->create([
                    'content' => $request->content,
                    'status' => $request->status,
                ]);
                
                return redirect()->back();
        
       
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
        
        return view('tasks.show',[
                'task' => $task,
            ]);
        
        $user = User::find($id);
        $tasklists = $user->tasklists()->orderBy('created_at','desc')->paginate(10);
        
        $data = [
              'user' => $user,
              'tasklists' => $tasklists,
            ];
            
            $data += $this->counts($user);
            
            return view('tasks.show', $data);
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
        
        return view('tasks.edit',[
                'task' => $task,
            ]);
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
        $task->content = $request->content;
        $task->status = $request->status;
        $task->save();
        
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
        
        $task = Task::find($id);
        $task->delete();
        
        return redirect('/');
    
        
        $micropost = \App\Tasklist::find($id);
        
        if (\Auth::id() === $tasklist->user_id){
            $tasklist->delete();
        }
        
        return redirect()->back();
        
    }
}

<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaskController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tasks = Task::all();

        return $this->sendResponse(TaskResource::collection($tasks), 'Tasks retrieved successfully.');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $user = $request->user();

        $validator = Validator::make($input, [
            'name' => 'required',
            'description' => 'required',
            'user_id' => 'required',
            'category_id' => 'required',
            'status_id' => 'required',
            'assignee' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $task = Task::create($input);
        $user->tasks()->attach($task);

        return $this->sendResponse(new TaskResource($task), 'Task created successfully.');
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

        if (is_null($task)) {
            return $this->sendError('Task not found.');
        }

        return $this->sendResponse(new TaskResource($task), 'Task retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required',
            'description' => 'required',
            'user_id' => 'required',
            'category_id' => 'required',
            'status_id' => 'required',
            'assignee' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $task->name = $input['name'];
        $task->description = $input['description'];
        $task->user_id = $input['user_id'];
        $task->category_id = $input['category_id'];
        $task->status_id = $input['status_id'];
        $task->assignee = $input['assignee'];
        $task->save();

        return $this->sendResponse(new TaskResource($task), 'Task updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        $task->delete();

        return $this->sendResponse([], 'Task deleted successfully.');
    }

    public function userTasks(Request $request)
    {
        $tasks = User::tasks;
        return $this->sendResponse(TaskResource::collection($tasks), 'Tasks retrieved successfully.');
    }
}

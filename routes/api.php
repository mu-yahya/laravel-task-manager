<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Task;
// use App\Http\Controllers\UserController;

// Route::middleware('auth:sanctum')->get('/tasks', fn() => Task::all());
Route::get('/tasks', fn() => Task::all());
Route::delete('/tasks/{id}', fn($id) => Task::destroy($id));
// Route::middleware('auth:sanctum')->delete('/tasks/{id}', fn($id) => Task::destroy($id));



// Route::middleware('auth:sanctum')->post('/tasks', function (Request $request) {
    Route::post('/tasks', function (Request $request) {
    return Task::create($request->validate([
        'title' => 'required|string',
        'description' => 'string',
        'status'  => 'required|integer',
    ]));
});
// Route::middleware('auth:sanctum')->patch('/tasks/{id}', function ($id, Request $request) {
    Route::patch('/tasks/{id}', function ($id, Request $request) {
    // $task = Task::find(intval($request.'id'));
    $task = Task::find($id);
    // echo "Task: ".$task;
    // return $task;
    /*$task->description = $request.'description';
    $task->title = $request.'title';
    $task->status = $request.'status';
    $task->save();*/
    
    
    if (!$task) {
        return response()->json(['message' => 'Post not found'], 404);
    }

    // Validate request data
    $validated = $request->validate([
        'title'   => 'required|string',
        'description' => 'string',
        'status'  => 'required|integer',
    ]);

    // Update and save
    $task->update($validated);


    
    return response()->json(['message' => 'Task updated successfully', 'post' => $task]);
    /*return Task::where('id', $request.id)->update(['content' => 'Updated with query']);
    / *($request->validate([
        'title' => 'required|string',
        'description' => 'string',
        'status'  => 'required|integer',
    ]));*/
});
<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Task;

Route::get('/tasks', fn() => Task::where('user_id',auth()->id())->get());
Route::get('/tasks-by-status/{status}', fn($status) => Task::where('user_id',auth()->id())->where('status',intval($status))->get());
Route::get('/users', fn() => User::all());
Route::delete('/tasks/{id}', fn($id) => Task::destroy($id));
Route::post('/tasks', function (Request $request) {
    return Task::create($request->validate([
        'title' => 'required|string',
        'description' => 'nullable|string',
        'status'  => 'required|integer',
        'user_id' => 'required|integer',
        'deadline' => 'nullable|date'
    ]));
});
    Route::patch('/tasks/{id}', function ($id, Request $request) {
    $task = Task::find($id);
    
    
    if (!$task) {
        return response()->json(['message' => 'Post not found'], 404);
    }

    // Validate request data
    $validated = $request->validate([
        'title'   => 'required|string',
        'description' => 'nullable|string',
        'status'  => 'required|integer',
        'user_id' => 'required|integer',
        'deadline' => 'nullable|date'
    ]);

    // Update and save
    $task->update($validated);


    
    return response()->json(['message' => 'Task updated successfully', 'post' => $task]);

});
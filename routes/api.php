<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Task;


Route::get('/quotes', function()  {

    $quote = Http::withHeaders([
        'X-Api-Key' => env('VITE_API_KEY')
    ])->get('https://api.api-ninjas.com/v1/quotes');

    // return response()->json(['quote' => $quote.quote, 'author' => $quote.author]);
    return response()->json($quote->json());
});


Route::get('/qr',  function()  {

    $qr = Http::withHeaders([
        'X-Api-Key' => env('VITE_API_KEY'),
        'Accept' => 'image/png'
        ])->get('https://api.api-ninjas.com/v1/qrcode?format=png&data=https://github.com/mu-yahya');
        // ])->withOptions(['stream' => true])->get('https://api.api-ninjas.com/v1/qrcode?format=png&data=https://github.com/mu-yahya');

    // return response()->json(['quote' => $quote.quote, 'author' => $quote.author]);
    // return response();
    // return response()->view();

    /*$data = base64_decode($quote.data);
    Storage::disk('public')->put('images/' . 'qr.png', $data);
    return response()->json(['path' => 'images/' . $filename]);*/
    /*echo $quote;
    print($quote);*/
    //echo $quote;
    // Log::debug($quote); 
    // return response()->json(['path' => $quote.data]);
    // return response()->json(['path' => $quote]);
    // return response($qr->getBody(), 200)->header('Content-Type', 'image/png');
    return response(base64_encode($qr->getBody()), 200)->header('Content-Type', 'image/png');
});



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
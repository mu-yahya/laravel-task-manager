<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\View\View;

class TaskContoller extends Controller
{
    //
    public function show(): View
    {
        return view('user.profile', [
            'user' => User::findOrFail($id)
        ]);
    }
}

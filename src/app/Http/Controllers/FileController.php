<?php

namespace App\Http\Controllers;

use App\Models\Deliverable;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function store(Request $request, Task $task)
    {
        $request->validate(['file' => 'required|file|max:10240']);
        $path = $request->file('file')->store('deliverables');

        Deliverable::create([
            'task_id' => $task->id,
            'file_path' => $path,
            'uploaded_by' => auth()->id(),
        ]);

        return redirect()->back()->with('success','File uploaded successfully.');
    }

    public function download(Deliverable $deliverable)
    {
        return Storage::download($deliverable->file_path);
    }
}


// <!-- <
// ?php

// namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use App\Models\Deliverable;
// use App\Models\Task;
// use Illuminate\Support\Facades\Storage;

// class FileController extends Controller
// {
//    public function store(Request $request, Task $task)
//     {
//         $request->validate([
//             'file' => 'required|file|max:10240',
//         ]);

//         $path = $request->file('file')->store('deliverables');

//         Deliverable::create([
//             'task_id' => $task->id,
//             'file_path' => $path,
//             'uploaded_by' => auth()->id(),
//         ]);

//         return redirect()->back()->with('success','File uploaded successfully.');
//     }

//     public function download(Deliverable $deliverable)
//     {
//         return Storage::download($deliverable->file_path);
//     }
// } -->


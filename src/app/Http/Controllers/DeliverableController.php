<?php

namespace App\Http\Controllers;

use App\Models\Deliverable;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class DeliverableController extends Controller
{
    public function index(Request $request)
    {
       
        $files = Deliverable::with('task.project')->latest()->get();

        return Inertia::render('Files/Index', [
            'files' => $files,
        ]);
    }

    public function store(Request $request, Task $task)
    {
        $request->validate(['file' => 'required|file|max:10240']);

        $path = $request->file('file')->store('deliverables');

        $deliverable = Deliverable::create([
            'task_id' => $task->id,
            'file_path' => $path,
            'uploaded_by' => $request->user()->id,
        ]);

        return back()->with('success', 'File uploaded successfully.');
    }

    public function download(Deliverable $deliverable)
    {
        return Storage::download($deliverable->file_path);
    }
}

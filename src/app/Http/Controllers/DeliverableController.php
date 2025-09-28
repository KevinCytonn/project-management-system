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
        $files = Deliverable::with('task.project', 'uploader')->latest()->get();

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

    public function view(Deliverable $deliverable)
    {
        // Check if file exists
        if (!Storage::exists($deliverable->file_path)) {
            abort(404, 'File not found');
        }

        // Get file details
        $filePath = Storage::path($deliverable->file_path);
        $mimeType = Storage::mimeType($deliverable->file_path);
        $fileName = basename($deliverable->file_path);

        
        return response()->file($filePath, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $fileName . '"'
        ]);
    }

    public function download(Deliverable $deliverable)
    {
        // Check if file exists
        if (!Storage::exists($deliverable->file_path)) {
            abort(404, 'File not found');
        }

        return Storage::download(
            $deliverable->file_path, 
            basename($deliverable->file_path)
        );
    }
}
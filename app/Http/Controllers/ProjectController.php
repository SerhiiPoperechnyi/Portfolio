<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;  

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::latest()->get();
        return view('admin.projects.index', compact('projects'));
    }
    public function create()
    {
        return view('admin.projects.create');
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $url = env('SUPABASE_URL');
        $bucket = env('SUPABASE_BUCKET');
        $key = env('SUPABASE_KEY');
        $uploadToSupabase = function ($file) use ($url, $bucket, $key) {
            if (!$file || !$file->isValid()) return null;

            $filename = \Illuminate\Support\Str::random(20) . '.' . $file->getClientOriginalExtension();
            
            $response = \Illuminate\Support\Facades\Http::timeout(300)
                ->withHeaders([
                    'apikey' => $key,
                    'Authorization' => 'Bearer ' . $key,
                    'Content-Type' => $file->getMimeType(),
                    'x-upsert' => 'true',
                ])
                ->withBody(fopen($file->getRealPath(), 'r'), $file->getMimeType())
                ->post($url . '/storage/v1/object/' . $bucket . '/' . $filename);
            if ($response->successful()) {
                return $url . '/storage/v1/object/public/' . $bucket . '/' . $filename;
            }
            return null;
        };
    $imageUrl = $request->hasFile('thumbnail') 
        ? $uploadToSupabase($request->file('thumbnail')) 
        : null;
    $videoUrl = $request->hasFile('video') 
        ? $uploadToSupabase($request->file('video')) 
        : null;
    \App\Models\Project::create([
        'user_id'     => auth()->id() ?? 1,
        'title'       => $request->title ?? 'Без названия',
        'slug'        => \Illuminate\Support\Str::slug($request->title ?? 'test') . '-' . time(),
        'description' => $request->description,
        'thumbnail'   => $imageUrl,      
        'main_video'  => $videoUrl,      
    ]);

        return redirect()->route('admin.projects.index')->with('success', 'Проект создан!');
    }
    public function show(Project $project)
    {
        return view('admin.projects.show', compact('project'));
    }
    public function edit(Project $project)
    {
        return view('admin.projects.edit', compact('project'));
    }
    public function update(Request $request, Project $project)
    {
        $project->update([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'description' => $request->description,
        ]);
        return redirect()->route('projects.index');
    }
    public function destroy(\App\Models\Project $project)
    {
        $url = env('SUPABASE_URL');
        $bucket = env('SUPABASE_BUCKET');
        $key = env('SUPABASE_KEY');
        $filesToDelete = [];
        foreach (['thumbnail', 'main_video'] as $field) {
            if ($project->$field) {
                $parts = explode('/', $project->$field);
                $filesToDelete[] = end($parts);
            }
        }
        if (!empty($filesToDelete)) {
            \Illuminate\Support\Facades\Http::withHeaders([
                'apikey' => $key,
                'Authorization' => 'Bearer ' . $key,
            ])->delete($url . '/storage/v1/object/' . $bucket, [
                'prefixes' => $filesToDelete
            ]);
        }
        $project->delete();
        return redirect()->back()->with('success', 'Проект и файлы удалены');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;  
use App\Models\ProjectImage;

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
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'thumbnail' => 'nullable|image',
            'video' => 'nullable|file',
            'images.*' => 'image|mimes:jpg,jpeg,png|max:2048'
        ]);
        $url = env('SUPABASE_URL');
        $bucket = env('SUPABASE_BUCKET');
        $key = env('SUPABASE_KEY');
        $uploadToSupabase = function ($file) use ($url, $bucket, $key) {
            if (!$file || !$file->isValid()) return null;

            $filename = \Illuminate\Support\Str::random(20) . '.' . $file->getClientOriginalExtension();
            
            $response = \Illuminate\Support\Facades\Http::timeout(30)
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
            $project = \App\Models\Project::create([
            'user_id'     => auth()->id() ?? 1,
            'title'       => $request->title ?? 'Sin título',
            'slug'        => \Illuminate\Support\Str::slug($request->title ?? 'test') . '-' . time(),
            'description' => $request->description,
            'thumbnail'   => $imageUrl,
            'main_video'  => $videoUrl,
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {

                $imageUrl = $uploadToSupabase($image);

                if ($imageUrl) {
                    \App\Models\ProjectImage::create([
                        'project_id' => $project->id,
                        'image_url' => $imageUrl
                    ]);
                }
            }
        }

        return redirect()->route('admin.projects.index')->with('success', 'Project created successfully!');
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
    $url = env('SUPABASE_URL');
    $bucket = env('SUPABASE_BUCKET');
    $key = env('SUPABASE_KEY');

    $uploadToSupabase = function ($file) use ($url, $bucket, $key) {
        if (!$file || !$file->isValid()) return null;

        $filename = Str::random(20) . '.' . $file->getClientOriginalExtension();

        $response = Http::withHeaders([
            'apikey' => $key,
            'Authorization' => 'Bearer ' . $key,
            'Content-Type' => $file->getMimeType(),
            'x-upsert' => 'true',
        ])->withBody(
            fopen($file->getRealPath(), 'r'),
            $file->getMimeType()
        )->post($url . '/storage/v1/object/' . $bucket . '/' . $filename);

        if ($response->successful()) {
            return $url . '/storage/v1/object/public/' . $bucket . '/' . $filename;
        }

        return null;
    };

    $project->update([
        'title' => $request->title,
        'slug' => Str::slug($request->title) . '-' . time(),
        'description' => $request->description,
    ]);

    if ($request->hasFile('thumbnail')) {
        $project->thumbnail = $uploadToSupabase($request->file('thumbnail'));
    }

    if ($request->hasFile('video')) {
        $project->main_video = $uploadToSupabase($request->file('video'));
    }

    if ($request->delete_video) {
        $project->main_video = null;
    }

    $project->save();

    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $image) {

            $imageUrl = $uploadToSupabase($image);

            if ($imageUrl) {
                \App\Models\ProjectImage::create([
                    'project_id' => $project->id,
                    'image_url' => $imageUrl
                ]);
            }
        }
    }

    return redirect()->route('admin.projects.index')
        ->with('success', 'Updated!');
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
        foreach ($project->images as $image) {
            $parts = explode('/', $image->image_url);
            $filename = end($parts);

            \Illuminate\Support\Facades\Http::withHeaders([
                'apikey' => $key,
                'Authorization' => 'Bearer ' . $key,
            ])->delete($url . '/storage/v1/object/' . $bucket, [
                'prefixes' => [$filename]
            ]);
        }
        $project->delete();
        return redirect()->back()->with('success', 'Project deleted successfully!');
    }
    public function deleteImage(\App\Models\ProjectImage $image)
    {
        $url = env('SUPABASE_URL');
        $bucket = env('SUPABASE_BUCKET');
        $key = env('SUPABASE_KEY');

        $parts = explode('/', $image->image_url);
        $filename = end($parts);

        Http::withHeaders([
            'apikey' => $key,
            'Authorization' => 'Bearer ' . $key,
        ])->delete($url . '/storage/v1/object/' . $bucket, [
            'prefixes' => [$filename]
        ]);

        $image->delete();

        return back()->with('success', 'Image deleted');
    }
    public function uploadCV(Request $request)
{
    $url = env('SUPABASE_URL');
    $bucket = env('SUPABASE_BUCKET');
    $key = env('SUPABASE_KEY');

    $file = $request->file('cv');

    $filename = Str::random(20) . '.' . $file->getClientOriginalExtension();

    Http::withHeaders([
        'apikey' => $key,
        'Authorization' => 'Bearer ' . $key,
        'Content-Type' => $file->getMimeType(),
    ])->withBody(
        fopen($file->getRealPath(), 'r'),
        $file->getMimeType()
    )->post($url . '/storage/v1/object/' . $bucket . '/' . $filename);

    $fileUrl = $url . '/storage/v1/object/public/' . $bucket . '/' . $filename;

    \App\Models\ProjectFile::updateOrCreate(
        ['type' => 'cv'],
        ['file_url' => $fileUrl]
    );

    return back();
}
public function downloadCV()
{
    $cv = \App\Models\ProjectFile::where('type', 'cv')->first();

    return redirect($cv->file_url);
}
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function uploadCV(Request $request)
    {
        $request->validate([
            'cv' => 'required|mimes:pdf|max:5000'
        ]);

        $file = $request->file('cv');

        $filename = 'cv_' . time() . '.pdf';

        $url = env('SUPABASE_URL');
        $bucket = env('SUPABASE_BUCKET');
        $key = env('SUPABASE_KEY');

        Http::withHeaders([
            'apikey' => $key,
            'Authorization' => 'Bearer ' . $key,
            'Content-Type' => 'application/pdf',
            'x-upsert' => 'true',
        ])->withBody(
            fopen($file->getRealPath(), 'r'),
            'application/pdf'
        )->post($url . '/storage/v1/object/' . $bucket . '/' . $filename);
        file_put_contents(storage_path('app/cv.txt'),
            $url . '/storage/v1/object/public/' . $bucket . '/' . $filename
        );

        return back()->with('success', 'CV uploaded');
    }

    public function deleteCV()
    {
        if (file_exists(storage_path('app/cv.txt'))) {
            unlink(storage_path('app/cv.txt'));
        }

        return back()->with('success', 'CV deleted');
    }
}

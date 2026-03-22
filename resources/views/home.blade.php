<x-app-layout>
<h1>My Portfolio</h1>

<div style="display:flex; gap:20px; flex-wrap:wrap;">
@foreach($projects as $project)
    <div style="width:300px; border:1px solid #ccc; padding:10px;">
        
        @if($project->thumbnail)
            <img src="{{ $project->thumbnail }}" style="width:100%;">
        @endif

        <h3>{{ $project->title }}</h3>

        <a href="{{ route('projects.show', $project->id) }}">
            Ver detalle
        </a>

    </div>
@endforeach
</div>
</x-app-layout>
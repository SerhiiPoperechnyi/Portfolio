<x-app-layout>
<h1>{{ $project->title }}</h1>



@if($project->main_video)
    <video controls width="1000">
        <source src="{{ $project->main_video }}" type="video/mp4">
    </video>
@endif
<p>{{ $project->description }}</p>
</x-app-layout>
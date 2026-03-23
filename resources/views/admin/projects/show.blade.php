<x-app-layout>

<div class="flex flex-col items-center text-center py-10">

    <h1 class="text-3xl font-bold mb-6">
        {{ $project->title }}
    </h1>

    @if($project->main_video)
        <video controls class="w-[60%] rounded shadow mb-6">
            <source src="{{ $project->main_video }}">
        </video>
    @endif

    <p class="w-[60%] text-lg">
        {{ $project->description }}
    </p>

</div>

</x-app-layout>
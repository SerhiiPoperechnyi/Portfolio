<x-app-layout>
<h1>Projects</h1>
<a href="{{ route('admin.projects.create') }}">Create</a>
@foreach($projects as $project)
    <div>
        <h3>{{ $project->title }}</h3>

        <a href="{{ route('admin.projects.edit', $project) }}">Edit</a>

        <form method="POST" action="{{ route('admin.projects.destroy', $project) }}">
            @csrf
            @method('DELETE')
            <button>Delete</button>
        </form>
    </div>
@endforeach
</x-app-layout>
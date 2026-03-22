<x-app-layout>
<h1>Edit</h1>

<form method="POST" action="{{ route('admin.projects.update', $project) }}">
    @csrf
    @method('PUT')

    <input name="title" value="{{ $project->title }}"><br>

    <textarea name="description">{{ $project->description }}</textarea><br>

    <button>Update</button>
</form>
</x-app-layout>
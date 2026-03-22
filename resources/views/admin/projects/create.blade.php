<x-app-layout>

<h1>Create Project</h1>

<form method="POST" action="{{ route('admin.projects.store') }}" enctype="multipart/form-data">
    @csrf

    <input name="title" placeholder="Title"><br>

    <textarea name="description"placeholder="Description"></textarea><br>

    <input type="file" name="video"><br>
    <input type="file" name="thumbnail"><br>

    <button>Save</button>
</form>
</x-app-layout>
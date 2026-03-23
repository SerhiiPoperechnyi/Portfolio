<x-app-layout>

<div class="max-w-4xl mx-auto py-10">

<h1 class="text-2xl font-bold mb-6">Admin Panel</h1>

<div class="space-y-4">

    <a href="{{ route('admin.projects.index') }}"
       class="block bg-gray-300 text-black p-3 rounded">
        Manage Projects
    </a>

    <a href="{{ route('admin.projects.create') }}"
       class="block bg-gray-300 text-black p-3 rounded">
        Create Project
    </a>

</div>

<hr class="my-6">

<h2 class="text-xl mb-4">CV</h2>

<form method="POST" action="{{ route('admin.upload.cv') }}" enctype="multipart/form-data">
    @csrf
    <input type="file" name="cv">
    <button class="bg-gray-300 text-black px-4 py-2">Upload CV</button>
</form>

<form method="POST" action="{{ route('admin.delete.cv') }}" class="mt-3">
    @csrf
    @method('DELETE')
    <button class="bg-red-300 text-black px-4 py-2">Delete CV</button>
</form>

</div>

</x-app-layout>
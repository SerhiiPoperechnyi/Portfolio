<x-app-layout>

<div class="max-w-3xl mx-auto py-10">

    <h1 class="text-2xl font-bold mb-6 text-center">Create Project</h1>

    <form method="POST"
          action="{{ route('admin.projects.store') }}"
          enctype="multipart/form-data"
          class="bg-white shadow-md rounded-lg p-6 space-y-4">

        @csrf

        <input name="title"
               class="w-full border rounded p-2"
               placeholder="Title">

        <textarea name="description"
                  class="w-full border rounded p-2"
                  rows="4"
                  placeholder="Description"></textarea>

        <div>
            <label class="font-semibold">Video</label>
            <input type="file" name="video" class="mt-2">
        </div>

        <div>
            <label class="font-semibold">Images</label>
            <input type="file" name="images[]" multiple class="mt-2">
        </div>

        <button class="bg-gray-400 text-black px-4 py-2 rounded hover:bg-gray-600">
            Save
        </button>

    </form>

</div>

</x-app-layout>
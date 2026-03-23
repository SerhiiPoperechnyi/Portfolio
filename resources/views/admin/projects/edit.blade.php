<x-app-layout>

<div class="max-w-3xl mx-auto py-10">

    <h1 class="text-2xl font-bold mb-6 text-center">Edit Project</h1>

    <form method="POST"
          action="{{ route('admin.projects.update', $project->id) }}"
          enctype="multipart/form-data"
          class="bg-white shadow-md rounded-lg p-6 space-y-4">

        @csrf
        @method('PUT')

        {{-- TITLE --}}
        <input name="title"
               value="{{ $project->title }}"
               class="w-full border rounded p-2"
               placeholder="Title">

        {{-- DESCRIPTION --}}
        <textarea name="description"
                  class="w-full border rounded p-2"
                  rows="4"
                  placeholder="Description">{{ $project->description }}</textarea>

        {{-- VIDEO --}}
        <div>
            <label class="font-semibold">Video</label>
            <input type="file" name="video" class="block mt-2">

            @if($project->main_video)
                <video width="200" controls class="mt-2">
                    <source src="{{ $project->main_video }}">
                </video>

                <label class="block mt-2">
                    <input type="checkbox" name="delete_video">
                    Delete video
                </label>
            @endif
        </div>

        {{-- IMAGES --}}
        <div>
            <label class="font-semibold">Add Images</label>

            <div id="dropzone"
                 class="border-2 border-dashed p-6 text-center cursor-pointer mt-2">
                Drop images here or click
                <input type="file" name="images[]" multiple class="hidden" id="fileInput">
            </div>
        </div>

        {{-- SAVE --}}
        <button class="bg-gray-400 text-black px-4 py-2 rounded hover:bg-gray-500">
            Update
        </button>

    </form>

    {{-- EXISTING IMAGES --}}
    @if($project->images)
        <div class="flex gap-4 mt-6 flex-wrap">
            @foreach($project->images as $img)
                <div class="relative">
                    <img src="{{ $img->image_url }}" class="w-32 h-32 object-cover rounded">

                    <form method="POST"
                          action="{{ route('admin.images.delete', $img->id) }}"
                          class="absolute top-1 right-1">
                        @csrf
                        @method('DELETE')
                        <button class="bg-red-300 text-black text-xs px-2 rounded">
                            ✕
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
    @endif

</div>

<script>
let dropzone = document.getElementById('dropzone');
let input = document.getElementById('fileInput');

dropzone.onclick = () => input.click();

dropzone.ondragover = e => {
    e.preventDefault();
    dropzone.classList.add('bg-gray-200');
};

dropzone.ondragleave = () => {
    dropzone.classList.remove('bg-gray-200');
};

dropzone.ondrop = e => {
    e.preventDefault();
    input.files = e.dataTransfer.files;
};
</script>

</x-app-layout>
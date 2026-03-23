<x-app-layout>

<div class="max-w-5xl mx-auto py-10">

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Projects</h1>

        <a href="{{ route('admin.projects.create') }}"
           class="bg-gray-400 text-black px-4 py-2 rounded hover:bg-gray-600">
            + Create
        </a>
    </div>

    <div class="grid gap-6">

        @foreach($projects as $project)
            <div class="bg-white shadow rounded-lg p-4 flex justify-between items-center">

                <div>
                    <h3 class="text-lg font-semibold">
                        {{ $project->title }}
                    </h3>

                    <p class="text-gray-500 text-sm">
                        {{ Str::limit($project->description, 80) }}
                    </p>
                </div>

                <div class="flex gap-3">

                    <a href="{{ route('admin.projects.edit', $project) }}"
                       class="bg-sky-400 text-white px-3 py-1 rounded">
                        Edit
                    </a>

                    <form method="POST"
                          action="{{ route('admin.projects.destroy', $project) }}">
                        @csrf
                        @method('DELETE')

                        <button class="bg-red-400 text-white px-3 py-1 rounded">
                            Delete
                        </button>
                    </form>

                </div>

            </div>
        @endforeach

    </div>

</div>

</x-app-layout>
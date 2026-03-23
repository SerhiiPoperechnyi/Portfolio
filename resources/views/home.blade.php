<x-app-layout>
<h1 class="text-3xl font-bold text-center my-10">Mis Proyectos</h1>
<div class="flex flex-col items-center gap-16 py-10">

@foreach($projects as $project)

<div class="w-[60%] bg-white shadow-lg rounded-xl overflow-hidden">

    {{-- TITLE --}}
    <h2 class="text-2xl font-bold text-center mt-4">
        {{ $project->title }}
    </h2>

    {{-- CAROUSEL --}}
<div class="relative mt-4 overflow-hidden">

    <div id="carousel-{{ $project->id }}" 
         class="flex transition-transform duration-500">

        @foreach($project->images as $image)
            <div class="min-w-full flex justify-center">
                <img src="{{ $image->image_url }}"
                     class="w-[90%] h-[400px] object-cover rounded-xl cursor-pointer"
                     onclick="openModal('{{ $image->image_url }}')">
            </div>
        @endforeach

    </div>

    {{-- LEFT --}}
    <button onclick="prevSlide({{ $project->id }})"
        class="absolute left-2 top-1/2 -translate-y-1/2 bg-black/50 text-white px-3 py-2 rounded-full z-10">
        ←
    </button>

    {{-- RIGHT --}}
    <button onclick="nextSlide({{ $project->id }})"
        class="absolute right-2 top-1/2 -translate-y-1/2 bg-black/50 text-white px-3 py-2 rounded-full z-10">
        →
    </button>

</div>

    {{-- LINK --}}
    <div class="text-center py-4">
        <a href="{{ route('projects.show', $project->id) }}"
           class="text-blue-500 hover:underline">
            Ver detalle
        </a>
    </div>

</div>

@endforeach

</div>

{{-- MODAL --}}
<div id="imageModal" class="fixed inset-0 bg-black/80 hidden items-center justify-center z-50">
    <span onclick="closeModal()" class="absolute top-5 right-8 text-white text-3xl cursor-pointer">✕</span>
    <img id="modalImage" class="max-w-4xl max-h-[90vh] rounded">
</div>

<script>
let sliders = {};

function nextSlide(id) {
    const el = document.getElementById('carousel-' + id);
    const total = el.children.length;

    if (!sliders[id]) sliders[id] = 0;

    sliders[id] = (sliders[id] + 1) % total;

    el.style.transform = `translateX(-${sliders[id] * 100}%)`;
}

function prevSlide(id) {
    const el = document.getElementById('carousel-' + id);
    const total = el.children.length;

    if (!sliders[id]) sliders[id] = 0;

    sliders[id] = (sliders[id] - 1 + total) % total;

    el.style.transform = `translateX(-${sliders[id] * 100}%)`;
}

// autoplay
setInterval(() => {
    Object.keys(sliders).forEach(id => {
        nextSlide(id);
    });
}, 4000);
</script>
<div id="imageModal" class="fixed inset-0 bg-black/80 hidden items-center justify-center z-50">
    <span onclick="closeModal()" class="absolute top-5 right-8 text-white text-3xl cursor-pointer">✕</span>
    <img id="modalImage" class="max-w-4xl max-h-[90vh] rounded">
</div>

<script>
function openModal(src) {
    document.getElementById('modalImage').src = src;
    document.getElementById('imageModal').classList.remove('hidden');
    document.getElementById('imageModal').classList.add('flex');
}

function closeModal() {
    document.getElementById('imageModal').classList.add('hidden');
}
</script>
</x-app-layout>
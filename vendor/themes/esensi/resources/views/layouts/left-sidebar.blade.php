<div class="container mx-auto lg:px-5 px-3 flex flex-col lg:flex-row my-5 gap-3 lg:gap-5 justify-between text-gray-600">
    <!-- Widget -->
    <div class="lg:w-1/3 w-full">
        @include('partials.sidebar', ['w_cos' => $w_cos])
    </div>
    {{-- Content --}}
    <main class="w-full space-y-1 bg-white rounded-lg px-4 py-2 lg:py-4 lg:px-5 shadow">
        @include("layouts.content")
    </main>
</div>
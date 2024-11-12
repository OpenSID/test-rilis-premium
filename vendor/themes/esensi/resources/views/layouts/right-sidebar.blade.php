<div class="container mx-auto lg:px-5 px-3 flex flex-col lg:flex-row my-5 gap-3 lg:gap-5 justify-between text-gray-600">
  {{-- Content --}}
  <main class="lg:w-2/3 w-full overflow-hidden space-y-5">
    @include("layouts.content")
  </main>
  <!-- Widget -->
  <div class="lg:w-1/3 w-full">
    @include('partials.sidebar')
  </div>
</div>
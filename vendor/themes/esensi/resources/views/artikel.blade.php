@extends('layouts.index')

@section('content')
  @if($single_artikel['id'])
    @include('layouts.commons.loading_screen')
    @include('layouts.commons.header')    
    
    @if ($single_artikel['tampilan'] == 1)
      <div class="container mx-auto lg:px-5 px-3 flex flex-col lg:flex-row my-5 gap-3 lg:gap-5 justify-between text-gray-600">
        <main class="lg:w-2/3 w-full overflow-hidden space-y-1 bg-white rounded-lg px-4 py-2 lg:py-4 lg:px-5 shadow">
            @include('layouts.partials.article')
            @include('layouts.partials.comment')
            @include('layouts.commons.sticky_share')            
        </main>
        <div class="lg:w-1/3 w-full">
          @include('layouts.partials.sidebar')          
        </div>
      </div>
    @elseif ($single_artikel['tampilan'] == 2): ?>
      <div class="container mx-auto lg:px-5 px-3 flex flex-col lg:flex-row my-5 gap-3 lg:gap-5 justify-between text-gray-600">
        <div class="lg:w-1/3 w-full">
          @include('layouts.partials.sidebar')
        </div>
        <main class="lg:w-2/3 w-full overflow-hidden space-y-1 bg-white rounded-lg px-4 py-2 lg:py-4 lg:px-5 shadow">
            @include('layouts.partials.article')
            @include('layouts.partials.comment')
            @include('layouts.commons.sticky_share')            
        </main>
      </div>
    @else
      <div class="container mx-auto lg:px-5 px-3 flex flex-col lg:flex-row my-5 gap-3 lg:gap-5 justify-between text-gray-600">
        <main class="lg:w-3/3 w-full overflow-hidden space-y-1 bg-white rounded-lg px-4 py-2 lg:py-4 lg:px-5 shadow">
            @include('layouts.partials.article')
            @include('layouts.partials.comment')
            @include('layouts.commons.sticky_share')            
        </main>
      </div>
    @endif    
      @include('layouts.commons.footer')                
    @else
      @include('layouts.commons.404')
    @endif
@endsection
  
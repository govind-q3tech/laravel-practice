<x-layouts.front-layout>
  @section('title', 'Home')
  
  <x-slot name="content">
    <x-jet-validation-errors class="mb-4" />
    @if (session('status'))
    <div class="mb-4 font-medium text-sm text-green-600">
      {{ session('status') }}
    </div>
    @endif
    
 


  </x-slot>
  </x-front-layout>
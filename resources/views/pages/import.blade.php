<x-layouts.front-layout>
    @section('title', 'Import')
    <x-slot name="breadcrumb">
        {{ Breadcrumbs::view('partials.breadcrumbs-front', 'front', ['append' => [['label'=> "Import"]]]) }}

    </x-slot>
    <x-slot name="content">
        <div class="login-area">
            <div class="white-box login-box">
                <div class="row no-gutters">
                    <div class="col-md-8">
                        <div class="user-login">
                           <h2>Import Records</h2>
                           <p>NOTE : Please Press one button at one time till execution complete. </p>
                            <div>
                            <a href="{{ route('import-users') }}">
                              <button class="greebtn align-items-center"><img src="{{ asset('img/user.png') }}" alt=""> Import User </button>
                            </a>
                            <a href="{{ route('import-category') }}">
                              <button class="bluebtn align-items-center"><i class="fa fa-cogs"></i> Import Category </button>
                            </a>
                            <a href="{{ route('import-location') }}">
                              <button class="bluebtn align-items-center"><i class="fa fa-map"></i>  Import Location </button>
                            </a>
                            <a href="{{ route('import-post') }}">
                              <button class="greebtn align-items-center"><i class="fa fa-blog"></i>  Import Post </button>
                            </a>
                          </div>

                        </div>
                    </div>
                    <div class="col-md-4 loginimg d-flex align-items-center justify-content-center">
                        <img src="{{ asset('img/loginimg.png') }}" alt="">
                    </div>
                </div>
            </div>
        </div>
   
    </x-slot>
</x-front-layout>

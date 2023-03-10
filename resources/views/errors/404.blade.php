<x-layouts.front-layout>
 @push('styles')
  <style type="text/css">

        

        .error-main{

          background-color: #fff;

          

        }

        .error-main h1{

          font-weight: bold;

          color: #444444;

          font-size: 100px;

          text-shadow: 2px 4px 5px #6E6E6E;

        }

        .error-main h6{

          color: #42494F;

        }

        .error-main p{

          color: #9897A0;

          font-size: 14px; 

        }

    </style>
    @endpush
    @section('title', '404')
    
    <x-slot name="content">

        <!--Membership start-->
        <div class="padT50 padB50">
            <div class="container">
                <div class="row text-center">

        <div class="col-lg-6 offset-lg-3 col-sm-6 offset-sm-3 col-12 p-3 error-main">

          <div class="row">

            <div class="col-lg-8 col-12 col-sm-10 offset-lg-2 offset-sm-1">

              <h1 class="m-0">404</h1>

              <h6>Page not found - {{ Request::url() }}</h6>

              <p><span class="text-info"><a  class="btn btn-primary" href="{{url('/')}}" >Go Back To Home</a></span></p>

            </div>

          </div>

        </div>

      </div>
            </div>
        </div>
        
    </x-slot>
</x-layouts.front-layout>


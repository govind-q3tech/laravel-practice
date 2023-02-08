<x-layouts.front-layout>
    @section('title', 'Enquries')
    <x-slot name="breadcrumb">
        {{ Breadcrumbs::view('partials.breadcrumbs-front', 'front', ['append' => [['label'=> "Enquiry"]]]) }}
    </x-slot>
    <x-slot name="content">

        <!--Dashboard Start-->

        <div class="cms-page innerblock-padd">
            <div class="container">
                <div class="dashborad-panel">
                    <x-elements.front.sidebar />


                    <div class="dashborad-rightsider">
                        <h1 class="dash-head">{{ $contact->subject }}</h1>
                        <div class="card">


                             <!-- /.card-header -->
          <div class="card-body">
          <table class="table table-hover table-striped">
                <tr>
                    <th scope="row">{{ __('Name') }}</th>
                    <td>{{ $contact->name }}</td>
                </tr>
                <tr>
                    <th scope="row">{{ __('email') }}</th>
                    <td>{{ $contact->email }}</td>
                </tr>                <tr>
                    <th scope="row">{{ __('Subject') }}</th>
                    <td>{{ $contact->subject }}</td>
                </tr>
                <tr>
                    <th scope="row">{{ __('Message') }}</th>
                    <td>{{ $contact->message }}</td>
                </tr>
               
                
                <tr>
                    <th scope="row"><?= __('Created') ?></th>
                    <td>{{ $contact->created_at->format(config('get.ADMIN_DATE_TIME_FORMAT')) }}</td>
                </tr>

                
            </table>
          </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!--Dashboard End-->
    </x-slot>
</x-layouts.front-layout>
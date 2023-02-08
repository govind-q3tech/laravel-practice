<x-layouts.master>
@section('title', 'Locations')
  <!-- Content Header (Location header) -->
  <x-slot name="breadcrumb">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Location Manager</h1>
      </div>
      <div class="col-sm-6">
      {{ Breadcrumbs::render('common',['append' => [['label'=> $getController,'route'=> 'admin.locations.index'],['label' => 'View Location Detail']]]) }}
      </div>
    </div>
  </x-slot>

  <x-slot name="content">
    <!-- Main content -->
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Location</h3>

            <div class="card-tools">
              <a href="{{ route('admin.locations.index') }}" class="btn btn-default pull-right" title="Cancel"><i class="fa fa-fw fa-chevron-circle-left"></i> Back</a>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
          <table class="table table-hover table-striped">
                <tr>
                    <th scope="row">{{ __('Title') }}</th>
                    <td>{{ $location->title }}</td>
                </tr>
          

                <tr>
                    <th scope="row">{{ __('Slug') }}</th>
                    <td>{{ $location->slug }}</td>
                </tr>

                <tr>
                    <th scope="row">{{ __('Area') }}</th>
                    <td>{{ isset($location->area->title)?$location->area->title:'NA' }}</td>
                </tr>
                <tr>
                    <th scope="row">{{ __('Status') }}</th>
                    <td>{{ $location->status ? __('Active') : __('Inactive')  }}</td>
                </tr>
                <tr>
                    <th scope="row">{{ __('Description') }}</th>
                    <td class="editor-content">{!! $location->description  !!}</td>
                </tr>
                
                <tr>
                    <th scope="row"><?= __('Created') ?></th>
                    <td>{{ $location->created_at->format(config('get.ADMIN_DATE_TIME_FORMAT')) }}</td>
                </tr>
                <tr>
                    <th scope="row">{{ __('Modified') }}</th>
                    <td>{{ $location->updated_at->format(config('get.ADMIN_DATE_TIME_FORMAT')) }}</td>
                </tr>
                
            </table>
          </div>
   
        </div>
        <!-- /.card -->
      </div>
      <!-- /.col -->
    </div>
    <!-- /.content -->
  </x-slot>
</x-layouts.master>
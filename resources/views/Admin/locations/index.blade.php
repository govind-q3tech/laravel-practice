@php
 $qparams = app('request')->query();

 @endphp
<x-layouts.master>
@section('title', 'Locations')
  <!-- Content Header (Location header) -->
  <x-slot name="breadcrumb">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Location Manager</h1>
      </div>
      <div class="col-sm-6">
        {{-- <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Simple Tables</li>
          </ol> --}}
        {{ Breadcrumbs::render('common',['append' => [['label'=> $getController, 'route'=> \Request::route()->getName()]]]) }}
      </div>
    </div>
  </x-slot>
<?php
  $response = Gate::inspect('check-user', "locations-create");
  $canCreate = true;
  if (!$response->allowed()) {
      $canCreate = false;
  }
  ?>

  <x-slot name="content">
    <!-- Main content -->
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Locations</h3>
            @if($canCreate)
            <div class="card-tools">
              <a href="{{ route('admin.locations.create') }}" class="btn btn-block btn-primary btn-sm" title="Add Location"><i class="fa fa-plus"></i> Add Location</a>
            </div>
            @endif
          </div>
          <!-- /.card-header -->
              <div class="filter-outer box box-info">
                <div class="card-header">
                  <h3 class="box-title"><span class="caption-subject font-green bold uppercase">Filter</span></h3>
                </div>
                <div class="card-body">
                  {{ Form::open(['url' => route('admin.locations.index', $qparams),'method' => 'get']) }}
                  
                    <div class="row">

                      <div class="col-md-5 form-group">
                        {{ Form::text('keyword', app('request')->query('keyword'), ['class' => 'form-control','placeholder' => 'Location Title']) }}
                      </div>
                      <div class="col-md-3 form-group">
                        <button class="btn btn-success" title="Filter" type="submit"><i class="fa fa-filter"></i> Filter</button>
                        <a href="{{ route('admin.locations.index') }}" class="btn btn-warning" title="Reset"><i class="fa  fa-refresh"></i> Reset</a>
                      </div>
                    </div>
                 
                  {{ Form::close() }}
                </div>
              </div>
          <div class="card-body table-responsive p-0"> 
            <table class="table table-bordered table-hover">
              <thead>
                
                <tr>
                  <th style="width:7%">#</th>
                  <th scope="col">@sortablelink('title', 'Title', ['filter' => 'active, visible'], ['rel' => 'nofollow'])</th>
                  <th scope="col">@sortablelink('slug', 'Slug', ['filter' => 'active, visible'], ['rel' => 'nofollow'])</th>
                  <th scope="col">Area Name</th>
                  <th>@sortablelink('status', 'Status')</th>
                  <th scope="col">@sortablelink('created_at', 'Created', ['filter' => 'active, visible'], ['rel' => 'nofollow'])</th>
                  <th scope="col" class="actions" style="width:12%">Action</th>
                </tr>
                
              </thead>
              @if($locations->count() > 0)
              <tbody>
                @php
                $i = (($locations->currentPage() - 1) * ($locations->perPage()) + 1)
                @endphp
                @foreach($locations as $location)
              
                <tr class="row-{{ $location->id }}">
                  <td> {{$i}}. </td>
                  <td>{{$location->title}}</td>
                  <td>{{$location->slug}}</td>
                  <td>{{$location->area->title??'--NA--'}}</td>
                  <td>
                    @if ($location->status == 1)
                        <span class='btn1 btn-block btn-primary btn-xs text-center' data-value="1" data-column="status">Active</span>
                    @else
                        <span class='btn1 btn-block btn-danger btn-xs text-center' data-value="0">In-Active</span>
                    @endif
                  </td>
                  <td>{{ $location->created_at->format(config('get.FRONT_DATE_FORMAT')) }}</td>
                  <td class="actions action-btn-tab">

                    <a href="{{ route('admin.locations.show',['location' => $location->id]) }}" class="btn btn-warning btn-xs" data-toggle="tooltip" alt="View setting" title="View Location" data-original-title="View"><i class="fa fa-fw fa-eye"></i></a>

                    @if($canCreate)
                      <a href="{{ route('admin.locations.edit',['location' => $location->id]) }}" class="btn btn-primary btn-xs" data-toggle="tooltip" alt="Edit" title="Edit Location" data-original-title="Edit"><i class="fa fa-edit"></i></a>

                     <a href="javascript:void(0);" class="confirmDeleteBtn btn btn-danger btn-xs" data-toggle="tooltip" alt="Delete {{ $location->title }}" title="Delete Location" data-url="{{ route('admin.locations.destroy', $location->id) }}" data-title="{{ $location->title }}"  data-action="delete" data-message="Are you sure want to delete this location?"><i class="fa fa-trash"></i></a>
                   @endif
                  </td>
                </tr>
                @php
                $i++;
                @endphp
                @endforeach
              </tbody>
              @else
              <tfoot>
                <tr>
                  <td colspan='7' align='center'> <strong>Record Not Available</strong> </td>
                </tr>
              </tfoot>
              @endif
            </table>
          </div>
          <!-- /.card-body -->
          <div class="card-footer clearfix">
          {{ $locations->appends(Request::query())->links() }}
          </div>
        </div>
        <!-- /.card -->
      </div>
      <!-- /.col -->
    </div>
    <!-- /.content -->
  </x-slot>
</x-layouts.master>
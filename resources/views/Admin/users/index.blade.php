@php
 $qparams = app('request')->query();
if (isset($qparams['type']) && $qparams['type'] == 'customers'):
$title = "Customers Manager";
$add = "Customer";
elseif (isset($qparams['type']) && $qparams['type'] == 'advertisers'):
$title = "Advertisers Manager";
$add = "Advertiser";
else:
$title = "User Manager";
$add = "User";
endif
@endphp
{{-- @dump($qparams) --}}
<x-layouts.master>
@section('title', $title)
  <!-- Content Header (User header) -->
  <x-slot name="breadcrumb">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>{{ $title }}</h1>
      </div>
      <div class="col-sm-6">
        {{ Breadcrumbs::render('common',['append' => [['label'=> $title, 'route'=> \Request::route()->getName()]]]) }}
      </div>
    </div>
  </x-slot>
  <?php
  $response = Gate::inspect('check-user', "users-create");
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
            <h3 class="card-title">{{ $title }}</h3>
            @if($canCreate)
            <div class="card-tools">
            
              <a href="{{ route('admin.users.create', ['type' => $qparams['type']]) }}" class="btn btn-block btn-primary btn-sm" title="Add {{ $add }}"><i class="fa fa-plus"></i> Add {{ $add }}</a>
            
            </div>
            @endif
          </div>
          <!-- /.card-header -->
           <div class="card-body">
              <div class="filter-outer box box-info">
                <div class="box-header">
                  <h3 class="box-title"><span class="caption-subject font-green bold uppercase">Filter</span></h3>
                </div>
                <div class="box-body">
                  
                  {{ Form::open(['url' => route('admin.users.index', $qparams),'method' => 'get']) }}
                    <div class="row">
                      <div class="col-md-5 form-group">
                        {{ Form::text('keyword', app('request')->query('keyword'), ['class' => 'form-control','placeholder' => 'Keyword e.g: First Name, Last Name,email']) }}
                        @if(in_array('customers',$qparams))
                          {!! Form::hidden('type', 'customers') !!}
                        @elseif(in_array('advertisers',$qparams))
                          {!! Form::hidden('type', 'advertisers') !!}
                        @endif
                      </div>
                      <div class="col-md-3 form-group">
                        <button class="btn btn-success" title="Filter" type="submit"><i class="fa fa-filter"></i> Filter</button>
                        @if(in_array('customers',$qparams) || in_array('advertisers',$qparams))
                        <a href="{{ route('admin.users.index', ['type'=> $qparams['type'] ]) }}" class="btn btn-warning" title="Reset"><i class="fa  fa-refresh"></i> Reset</a>
                        @else
                        <a href="{{ route('admin.users.index') }}" class="btn btn-warning" title="Reset"><i class="fa  fa-refresh"></i> Reset</a>
                        @endif
                      </div>
                    </div>
                 
                  {{ Form::close() }}
                </div>
              </div>
            </div>
            
          <div class="card-body table-responsive p-0"> 
            <table class="table table-bordered table-hover">
              <thead>
                
                <tr>
                  <th style="width: 7%">#</th>
                  <th scope="col">@sortablelink('first_name', 'First Name', ['filter' => 'active, visible'], ['rel' => 'nofollow'])</th>
                  <th scope="col">@sortablelink('last_name', 'Last Name', ['filter' => 'active, visible'], ['rel' => 'nofollow'])</th>
                  <th scope="col">@sortablelink('email', 'Email', ['filter' => 'active, visible'], ['rel' => 'nofollow'])</th>
                  <th>@sortablelink('status', 'Status')</th>
                  @if (isset($qparams['type']) && $qparams['type'] == 'advertisers')
                  <th>@sortablelink('email_verified_at', 'Verified')</th>
                  @endif
                  @if (isset($qparams['type']) && $qparams['type'] == 'customers')
                  <th>@sortablelink('email_verified_at', 'Verified')</th>
                  @endif
                  @if (isset($qparams['type']) && $qparams['type'] == 'advertisers')
                  <!-- <th>@sortablelink('is_approved', 'Is Approved')</th> -->
                  @endif
                  <!-- <th scope="col">@sortablelink('created_at', 'Member Since', ['filter' => 'active, visible'], ['rel' => 'nofollow'])</th> -->
                  <th scope="col" class="actions" width="10%">Action</th>
                </tr>
                
              </thead>
              @if($users->count() > 0)
              <tbody>
                @php
                $i = (($users->currentPage() - 1) * ($users->perPage()) + 1)
                @endphp
                @foreach($users as $user)
                <tr class="row-{{ $user->id }}">
                  <td> {{$i}}. </td>
                  <td>{{$user->first_name}}</td>
                  <td>{{$user->last_name}}</td>
                  <td>{{$user->email}}</td>
                  <td>
                    @if ($user->status == 1)
                        <span class='btn1 btn-block btn-success btn-xs updateStatus text-center' data-value="1" data-column="status">Active</span>
                    @else
                        <span class='btn1 btn-block btn-danger btn-xs updateStatus text-center' data-value="0">In-Active</span>
                    @endif
                  </td>
                  @if (isset($qparams['type']) && $qparams['type'] == 'customers')
                  <td>
                     @if ($user->hasVerifiedEmail())
                          <span class='btn1 btn-block btn-success btn-xs text-center' data-url="">Verified</span>
                      @else
                          <span class='btn1 btn-block btn-danger btn-xs text-center'>Not Verified</span>
                      @endif
                  </td>
                  @endif
                  <!-- <td>
                      @if ($user->hasVerifiedEmail())
                          <span class='btn1 btn-block btn-success btn-xs text-center' data-url="">Verified</span>
                      @else
                          <span class='btn1 btn-block btn-danger btn-xs text-center'>Not Verified</span>
                      @endif
                  </td> -->
                  @if (isset($qparams['type']) && $qparams['type'] == 'advertisers')
                    <td>
                       @if ($user->hasVerifiedEmail())
                          <span class='btn1 btn-block btn-success btn-xs text-center' data-url="">Verified</span>
                      @else
                          <span class='btn1 btn-block btn-danger btn-xs text-center'>Not Verified</span>
                      @endif
                    </td>
                  @endif
                  <!-- <td>{{ $user->created_at->format(config('get.FRONT_DATE_FORMAT')) }}</td> -->
                  <td class="actions action-btn-tab">
                    <a href="{{ route('admin.users.show',['user' => $user->id]) }}" class="btn btn-warning btn-xs" data-toggle="tooltip" alt="View setting" title="View {{ $user->first_name }}" data-original-title="View"><i class="fa fa-fw fa-eye"></i></a>
                    @if($canCreate)
                    <a href="{{ route('admin.users.edit',['user' => $user->id,'type' => $qparams['type']]) }}" class="btn btn-primary btn-xs" data-toggle="tooltip" alt="Edit {{ $user->first_name }}" title="Edit {{ $user->first_name }}" data-original-title="Edit"><i class="fa fa-edit"></i></a>
                    {{-- <a href="javascript:void(0);" class="confirmDeleteBtn btn btn-danger btn-xs" data-toggle="tooltip" alt="Delete {{ $user->first_name }}" title="Delete User" data-url="{{ route('admin.users.destroy', $user->id) }}" data-title="{{ $user->name }}"><i class="fa fa-trash"></i></a> --}}
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
          {{ $users->appends(Request::query())->links() }}
          </div>
        </div>
        <!-- /.card -->
      </div>
      <!-- /.col -->
    </div>
    <!-- /.content -->
  </x-slot>
</x-layouts.master>
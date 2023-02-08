<x-layouts.master>
  @section('title', 'Admin Roles')
 <!-- Content Header (Page header) -->
<x-slot name="breadcrumb">
    <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Admin Roles Manager</h1>
        </div>
        <div class="col-sm-6">
          {{ Breadcrumbs::render('common',['append' => [['label'=> $getController, 'route'=> \Request::route()->getName()]]]) }}
        </div> 
      </div>
</x-slot>

<x-slot name="content">
  <!-- Main content -->
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Admin Roles</h3>
          {{--
          <div class="card-tools">
                <a href="{{ route('admin.adminroles.create') }}" class="btn btn-block btn-primary btn-sm"><i class="fa fa-plus"></i> Add Role</a>
          </div>
          --}}
        </div>
        <!-- /.card-header -->
        <div class="card-body table-responsive p-0"> 
          <table class="table table-bordered table-hover">
            <thead>
              <tr>
                <th style="width: 7%">#</th>
                <th>@sortablelink('title', 'Title')</th>
                <th width="12%">@sortablelink('status', 'Status')</th>
                <th scope="col" class="actions" width="12%">Manage Permission</th>
                <th scope="col" class="actions" width="12%">Action</th>
              </tr>
            </thead>
            @if($adminRoles->count() > 0)
            <tbody>
                @php
                $i = (($adminRoles->currentPage() - 1) * ($adminRoles->perPage()) + 1)
                @endphp
                @foreach($adminRoles as $roles)
                  <tr class="row-{{ $roles->id }}">
                      <td> {{ $i }}. </td>
                      <td>
                        {{ $roles->title ?? "" }}
                      </td>
                      <td>
                          @if ($roles->status == 1)
                              <span class='btn1 btn-block btn-success btn-xs updateStatus text-center' data-value="1" data-column="status">Active</span>
                          @else
                              <span class='btn1 btn-block btn-danger btn-xs updateStatus text-center' data-value="0">In-Active</span>
                          @endif
                      </td>
                     
                      <td>
                        @if($roles->id != 1)
                        <a href="{{ route('admin.modules.index', $roles->id) }}" class="btn btn-success btn-xs text-center" data-toggle="tooltip" alt="View User Detail" title="Role Permission Detail" data-original-title="View"><i class="fa fa-edit"></i> Permission</a>
                        @else
                        N/A
                        @endif
                      </td>
                      <td class="actions">
                          @php
                              $queryStr['id'] = $roles->id;
                              $queryStr = array_merge( $queryStr , app('request')->query());
                          @endphp
                          <div class="form-group">
                              <a href="{{ route('admin.adminroles.show', $roles->id) }}" class="btn btn-warning btn-xs" data-toggle="tooltip" alt="View User Detail" title="View Admin Role Detail" data-original-title="View"><i class="fa fa-fw fa-eye"></i></a>
                              {{--<a href="{{ route('admin.adminroles.edit', $roles->id) }}" class="btn btn-success btn-xs" data-toggle="tooltip" alt="Edit" title="Edit User" data-original-title="Edit User"><i class="fa fa-edit"></i></a>--}}
                              {{--<a href="javascript:void(0);" class="confirmDeleteBtn btn btn-danger btn-xs" data-toggle="tooltip" alt="Delete {{ $roles->name }}" title="Delete Admin User" data-url="{{ route('admin.roles.destroy', $roles->id) }}" data-title="{{ $roles->title }}"><i class="fa fa-trash"></i></a>--}}
                          </div>
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
                    <td colspan='10' align='center'> <strong>No records available</strong> </td>
                </tr>
            </tfoot>
            @endif
          </table>
        </div>
        <!-- /.card-body -->
        <div class="card-footer clearfix">
          {!! $adminRoles->appends(\Request::except('page'))->render() !!}
        </div>
      </div>
      <!-- /.card -->
    </div>
    <!-- /.col -->
  </div>
  <!-- /.content -->
</x-slot>
</x-layouts.master>

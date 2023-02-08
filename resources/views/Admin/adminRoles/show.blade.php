<x-layouts.master>

  <!-- Content Header (Role header) -->
  <x-slot name="breadcrumb">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Admin Role Manager</h1>
      </div>
      <div class="col-sm-6">
      {{ Breadcrumbs::render('common',['append' => [['label'=> $getController,'route'=> 'admin.adminroles.index'],['label' => 'View Role Detail']]]) }}
      </div>
    </div>
  </x-slot>

  <x-slot name="content">
    <!-- Main content -->
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Admin Role Manager</h3>

            <div class="card-tools">
            <a href="{{ route('admin.adminroles.index') }}" class="btn btn-default pull-right" title="Cancel"><i class="fa fa-fw fa-chevron-circle-left"></i> Back</a>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
          <table class="table table-hover table-striped">
                <tr>
                    <th scope="row">{{ __('Name') }}</th>
                    <td>{{ $adminRole->title }}</td>
                </tr>
                <tr>
                    <th scope="row">{{ __('Status') }}</th>
                    <td>{{ $adminRole->status ? __('Active') : __('Inactive')  }}</td>
                </tr>
                 <tr>
                    <th scope="row">{{ __('Manage Permission') }}</th>
                    <td>
                      @if($adminRole->id != 1)
                        <a href="{{ route('admin.modules.index', $adminRole->id) }}" class="btn btn-success btn-xs text-center" data-toggle="tooltip" alt="View User Detail" title="Role Permission Detail" data-original-title="View"><i class="fa fa-edit"></i> Permission</a>
                      @else
                        N/A
                      @endif
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?= __('Created') ?></th>
                    <td>{{ $adminRole->created_at->format(config('get.ADMIN_DATE_TIME_FORMAT')) }}</td>
                </tr>
                <tr>
                    <th scope="row">{{ __('Modified') }}</th>
                    <td>{{ $adminRole->updated_at->format(config('get.ADMIN_DATE_TIME_FORMAT')) }}</td>
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
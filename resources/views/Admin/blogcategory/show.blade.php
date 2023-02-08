<x-layouts.master>
@section('title', 'Blog category')
  <!-- Content Header (Faq header) -->
  <x-slot name="breadcrumb">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Admin Blog Category Manager</h1>
      </div>
      <div class="col-sm-6">
      {{ Breadcrumbs::render('common',['append' => [['label'=> 'Blog Category','route'=> 'admin.blogcategory.index'],['label' => 'View Blog Category Detail']]]) }}
      </div>
    </div>
  </x-slot>

  <x-slot name="content">
    <!-- Main content -->
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Admin Blog Category </h3>

            <div class="card-tools">
              <a href="{{ route('admin.blogcategory.index') }}" class="btn btn-default pull-right" title="Cancel"><i class="fa fa-fw fa-chevron-circle-left"></i> Back</a>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
          <table class="table table-hover table-striped">
                <tr>
                    <th scope="row">{{ __('Category name') }}</th>
                    <td>{{ $sahowblogcategory->title }}</td>
                </tr>
          

            
                <tr>
                    <th scope="row">{{ __('Status') }}</th>
                    <td>{{ $sahowblogcategory->status ? __('Active') : __('Inactive')  }}</td>
                </tr>

                
                <tr>
                    <th scope="row"><?= __('Created') ?></th>
                    <td>{{ $sahowblogcategory->created_at->format(config('get.ADMIN_DATE_TIME_FORMAT')) }}</td>
                </tr>
                <tr>
                    <th scope="row">{{ __('Modified') }}</th>
                    <td>{{ $sahowblogcategory->updated_at->format(config('get.ADMIN_DATE_TIME_FORMAT')) }}</td>
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
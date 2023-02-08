<x-layouts.master>
<x-slot name="breadcrumb">
    <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Manage Redirect Url</h1>
        </div>
        <div class="col-sm-6">
     
         {{ Breadcrumbs::render('common',['append' => [['label'=> 'Redirect Url','route'=> 'admin.redirecturls.index'],['label' => 'Redirect Url Detail']]]) }}
        </div>
      </div>
</x-slot>

<x-slot name="content">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Redirect Url</h3>

            <div class="card-tools">
            <a href="{{ route('admin.redirecturls.index', app('request')->query()) }}" class="btn btn-default pull-right" title="Back"><i class="fa fa-fw fa-chevron-circle-left"></i> Back</a>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
    
        <div class="box-body">
            <table class="table table-hover table-striped">
                <tr>
                    <th scope="row">{{ __('Old Url') }}</th>
                    <td>{{ $redirect->old_url }}</td>
                </tr>
                <tr>
                    <th scope="row">{{ __('New Url') }}</th>
                     <td>{{ $redirect->new_url }}</td>
                </tr>
                 <tr>
                     <th scope="row"><?= __('Created') ?></th>
                    <td>{{ Carbon\Carbon::parse($redirect->created_at)->format(config('get.ADMIN_DATE_TIME_FORMAT')) }}</td>
                </tr>
                <tr>
                    <th scope="row">{{ __('Modified') }}</th>
                    <td>{{ Carbon\Carbon::parse($redirect->updated_at)->format(config('get.ADMIN_DATE_TIME_FORMAT')) }}</td>
                </tr>
               
            </table>
            
        </div>
        <div class="box-footer">
                <a href="{{route('admin.redirecturls.index')}}" class="btn btn-default pull-left" title="Cancel"><i class="fa fa-fw fa-chevron-circle-left"></i> Back</a>
        </div>
    </div>
</div>
</div>
</div>

</x-slot>


</x-layouts.master>
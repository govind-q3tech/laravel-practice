<x-layouts.master>
@section('title', 'Redirect Url')
<x-slot name="breadcrumb">
    <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Manage Redirect Url</h1>
        </div>
        <div class="col-sm-6">
     
          {{ Breadcrumbs::render('common',['append' => [['label'=> 'Redirect Url','route'=> \Request::route()->getName()]]]) }}
        </div>
      </div>
</x-slot>

<!-- Content Header (Page header) -->

@php $qparams = app('request')->query();@endphp
<x-slot name="content">
    <!-- Main content -->
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Admin Redirect Url</h3>

            <div class="card-tools">
            <a href="{{ route('admin.redirecturls.create') }}" class="btn btn-block btn-primary btn-sm"><i class="fa fa-plus"></i> Add Url</a>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table class="table table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th >Old Url</th>
                                        <th >New Url</th>
                                        <th >Status</th>
                                        <th >created</th>
                                        <th >Action</th>
                                    </tr>
                                </thead>
                                @if($redirectUrl->count() > 0)
                                <tbody>
                                    @php
                                    $i = (($redirectUrl->currentPage() - 1) * ($redirectUrl->perPage()) + 1)
                                    @endphp
                                    @foreach($redirectUrl as $redData)
                                    <tr class="row-{{ $redData->id }}">
                                        <td> {{$i}}. </td> 
                                        <td>{{ $redData->old_url }}</td> 
                                        <td>{{ $redData->new_url }}</td> 
                                        <td>{{$redData->status?'Active':'Inactive' }}</td>
                                        <td>{{ $redData->created_at->format(config('get.ADMIN_DATE_TIME_FORMAT')) }}</td>
                                        <td class="actions">
                                            <div class="btn-group">
                                                 <a href="{{ route('admin.redirecturls.show',$redData->id) }}" class="btn btn-warning btn-sm" data-toggle="tooltip" alt="View Faq" title="" data-original-title="View"><i class="fa fa-fw fa-eye"></i></a>
                                                 &nbsp;
                                                <a href="{{ route('admin.redirecturls.edit',$redData->id) }}" class="btn btn-primary btn-sm" data-toggle="tooltip" alt="Edit" title="" data-original-title="Edit"><i class="fa fa-edit"></i></a>
                                                &nbsp;
                                                <a href="javascript:void(0);" class="confirmDeleteBtn btn btn-danger btn-sm btn-flat" data-toggle="tooltip" alt="Delete Redirect Url" data-url="{{ 
                                                route('admin.redirecturls.destroy',$redData->id) }}" data-title="Redirect Url"><i class="fa fa-trash"></i></a>
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
                                        <td colspan='6' align='center'> <strong>Record Not Available</strong> </td>
                                    </tr>
                                </tfoot>
                                @endif
                            </table>
          </div>
          <!-- /.card-body -->
          <div class="card-footer admin_pagination clearfix">
          {{ $redirectUrl->appends(Request::query())->links() }}
          </div>
        </div>
        <!-- /.card -->
      </div>
      <!-- /.col -->
    </div>
    <!-- /.content -->
</x-slot>

</x-layouts.master>

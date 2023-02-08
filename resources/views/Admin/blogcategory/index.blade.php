<x-layouts.master>
@section('title', 'Blog category')
  <!-- Content Header (Faq header) -->
  <x-slot name="breadcrumb">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Admin Blog Category Manager</h1>
      </div>
      <div class="col-sm-6">
        {{-- <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Simple Tables</li>
          </ol> --}}
        {{ Breadcrumbs::render('common',['append' => [['label'=> 'Blog Category', 'route'=> \Request::route()->getName()]]]) }}
      </div>
    </div>
  </x-slot>
<?php
      $response = Gate::inspect('check-user', "blog-create");
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
            <h3 class="card-title">Admin Blog Category</h3>
            @if($canCreate)
            <div class="card-tools">
              <a href="{{ route('admin.blogcategory.create') }}" class="btn btn-block btn-primary btn-sm" title="Add Blog Category"><i class="fa fa-plus"></i> Add Blog Category</a>
            </div>
            @endif
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table class="table table-bordered">
              <thead>
                <tr>
                <tr>
                  <th>#</th>
                  <th scope="col">@sortablelink('title', 'Category name', ['filter' => 'active, visible'], ['rel' => 'nofollow'])</th>
                    
                  
                  <th>@sortablelink('status', 'Status')</th>
                  <th scope="col">@sortablelink('created_at', 'Created', ['filter' => 'active, visible'], ['rel' => 'nofollow'])</th>
                  <th scope="col" class="actions">Action</th>
                </tr>
                </tr>
              </thead>
              @if($blogcategory->count() > 0)
              <tbody>
                @php
                $i = (($blogcategory->currentPage() - 1) * ($blogcategory->perPage()) + 1)
                @endphp
                @foreach($blogcategory as $blogcat)
              
                <tr class="row-{{ $blogcat->id }}">
                  <td> {{$i}}. </td>
                  <td>{{ \Illuminate\Support\Str::limit($blogcat->title, 100,'...') }}</td>
                  <td>
                    @if ($blogcat->status == 1)
                        <span class='btn1 btn-block btn-success btn-xs updateStatus text-center' data-value="1" data-column="status">Active</span>
                    @else
                        <span class='btn1 btn-block btn-danger btn-xs updateStatus text-center' data-value="0">In-Active</span>
                    @endif
                  </td>
                  <td>{{ $blogcat->created_at->format(config('get.FRONT_DATE_FORMAT')) }}</td>
                  <td class="actions action-btn-tab">

                    <a href="{{ route('admin.blogcategory.show',[$blogcat->id]) }}" class="btn btn-warning btn-xs" data-toggle="tooltip" alt="View setting" title="View {{$blogcat->category_name}}" data-original-title="View"><i class="fa fa-fw fa-eye"></i></a>

                   @if($canCreate)
                    <a href="{{ route('admin.blogcategory.edit',[$blogcat->id]) }}" class="btn btn-primary btn-xs" data-toggle="tooltip" alt="Edit" title="Edit {{$blogcat->category_name}}" data-original-title="Edit"><i class="fa fa-edit"></i></a>

                   <a 
                   href="javascript:void(0);" 
                   class="confirmDeleteBtn btn btn-danger btn-xs" 
                   data-toggle="tooltip" 
                   alt="Delete {{ $blogcat->title }}" 
                   title="Delete {{$blogcat->category_name}}" 
                   data-url="{{ route('admin.blogcategory.destroy', $blogcat->id) }}" 
                   data-title="{{ $blogcat->title }}" 
                   data-action="delete" 
                   data-message="Are you sure want to delete this blog category?"
                   ><i class="fa fa-trash"></i></a>

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
          {{ $blogcategory->appends(Request::query())->links() }}
          </div>
        </div>
        <!-- /.card -->
      </div>
      <!-- /.col -->
    </div>
    <!-- /.content -->
  </x-slot>
</x-layouts.master>
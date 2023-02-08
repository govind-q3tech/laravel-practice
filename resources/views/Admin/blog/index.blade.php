<x-layouts.master>
@section('title', 'Blog')
  <!-- Content Header (Faq header) -->
  <x-slot name="breadcrumb">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Admin Blog  Manager</h1>
      </div>
      <div class="col-sm-6">
        {{ Breadcrumbs::render('common',['append' => [['label'=> 'Blog ', 'route'=> \Request::route()->getName()]]]) }}
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
            <h3 class="card-title">Admin Blog </h3>
            @if($canCreate)
              <div class="card-tools">
              <a href="{{ route('admin.blog.create') }}" class="btn btn-block btn-primary btn-sm" title="Add Blog"><i class="fa fa-plus"></i> Add Blog</a>
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
                  <th scope="col">@sortablelink('title', 'Title', ['filter' => 'active, visible'], ['rel' => 'nofollow'])</th>
                  {{-- <th scope="col">@sortablelink('<div class="">.
                    </div>blog_categories_id', 'Blog Category', ['filter' => 'active, visible'], ['rel' => 'nofollow'])</th> --}}
                  
                  <th>@sortablelink('status', 'Status')</th>
                  <th scope="col">@sortablelink('created_at', 'Created', ['filter' => 'active, visible'], ['rel' => 'nofollow'])</th>
                  <th scope="col" class="actions">Action</th>
                </tr>
                </tr>
              </thead>
              @if($blogs->count() > 0)
              <tbody>
                @php
                $i = (($blogs->currentPage() - 1) * ($blogs->perPage()) + 1)
                @endphp
                @foreach($blogs as $blog)
              
                <tr class="row-{{ $blog->id }}">
                  <td> {{$i}}. </td>
                  <td>{{$blog->title}}</td>
                  {{-- <td>@if(isset($blog->blog_category->title)){{$blog->blog_category->title}} @else N|A @endif</td> --}}
                  <td>
                    @if ($blog->status == 1)
                        <span class='btn1 btn-block btn-success btn-xs updateStatus text-center' data-value="1" data-column="status">Active</span>
                    @else
                        <span class='btn1 btn-block btn-danger btn-xs updateStatus text-center' data-value="0">In-Active</span>
                    @endif
                  </td>
                  <td>{{ $blog->created_at->format(config('get.FRONT_DATE_FORMAT')) }}</td>
                  <td class="actions action-btn-tab">

                    <a href="{{ route('admin.blog.show',[$blog->id]) }}" class="btn btn-warning btn-xs" data-toggle="tooltip" alt="View setting" title="View {{$blog->title}}" data-original-title="View"><i class="fa fa-fw fa-eye"></i></a>

                    @if($canCreate)
                    <a href="{{ route('admin.blog.edit',[$blog->id]) }}" class="btn btn-primary btn-xs" data-toggle="tooltip" alt="Edit" title="Edit {{$blog->title}}" data-original-title="Edit"><i class="fa fa-edit"></i></a>

                    <a href="javascript:void(0);" 
                    class="confirmDeleteBtn btn btn-danger btn-xs" 
                    data-toggle="tooltip" 
                    alt="Delete {{$blog->title}}" 
                    title="Delete {{$blog->title}}" 
                    data-action="delete" 
                    data-url="{{ route('admin.blog.destroy', $blog->id) }}" 
                    data-title="{{ $blog->title }}"><i class="fa fa-trash"></i></a>
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
          {{ $blogs->appends(Request::query())->links() }}
          </div>
        </div>
        <!-- /.card -->
      </div>
      <!-- /.col -->
    </div>
    <!-- /.content -->
  </x-slot>
</x-layouts.master>
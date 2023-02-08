<x-layouts.master>
@section('title', 'Blog')
  <!-- Content Header (Faq header) -->
  <x-slot name="breadcrumb">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Admin Blog Manager</h1>
      </div>
      <div class="col-sm-6">
      {{ Breadcrumbs::render('common',['append' => [['label'=> 'Blog','route'=> 'admin.blog.index'],['label' => 'View Blog Detail']]]) }}
      </div>
    </div>
  </x-slot>

  <x-slot name="content">
    <!-- Main content -->
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Admin Blog Manager</h3>

            <div class="card-tools">
              <a href="{{ route('admin.blog.index') }}" class="btn btn-default pull-right" title="Cancel"><i class="fa fa-fw fa-chevron-circle-left"></i> Back</a>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
          <table class="table table-hover table-striped">
                <tr>
                    <th scope="row">{{ __('Title') }}</th>
                    <td>{{ $blog->title }}</td>
                </tr>
          

                <tr>
                    <th scope="row">{{ __('Blog category') }}</th>
                    <td>@if(isset($blog->blog_category->title)){{$blog->blog_category->title}} @else N\A @endif</td>
                </tr>
                <tr>
                  <th scope="row">{{ __('Meta Title') }}</th>
                  <td>{{$blog->meta_title}}</td>
              </tr>
              <tr>
                <th scope="row">{{ __('Meta Keyword') }}</th>
                <td>{{$blog->meta_keyword}}</td>
            </tr>
            <tr>
              <th scope="row">{{ __('Sub Title') }}</th>
              <td>{{$blog->sub_title}}</td>
          </tr>
           <tr>
              <th scope="row">{{ __('Description') }}</th>
              <td>{!!$blog->description!!}</td>
          </tr> 
          <tr>
              <th scope="row">{{ __('Image') }}</th>
              <td><img src="@if(isset($blog->images)){{asset('/images/'.$blog->images)}} @else @endif" style="width:100px;"></td>
          </tr>
             
                <tr>
                    <th scope="row">{{ __('Status') }}</th>
                    <td>{{ $blog->status ? __('Active') : __('Inactive')  }}</td>
                </tr>

                
                <tr>
                    <th scope="row"><?= __('Created') ?></th>
                    <td>{{ $blog->created_at->format(config('get.ADMIN_DATE_TIME_FORMAT')) }}</td>
                </tr>
                <tr>
                    <th scope="row">{{ __('Modified') }}</th>
                    <td>{{ $blog->updated_at->format(config('get.ADMIN_DATE_TIME_FORMAT')) }}</td>
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
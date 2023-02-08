<x-layouts.master>
  @section('title', 'Blog Category')
  <!-- Content Header (Faq header) -->
  <x-slot name="breadcrumb">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Admin Blog Category Manager</h1>
      </div>
      <div class="col-sm-6">

        {{ Breadcrumbs::render('common',['append' => [['label'=> 'Blog Category','route'=> 'admin.blogcategory.index'],['label' => !empty($blogcat) ? 'Edit Category' : 'Add Category' ]]]) }}
      </div>
    </div>
  </x-slot>

  <x-slot name="content">
    <!-- Main content -->
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Admin Blog Category</h3>

            <div class="card-tools">
              <a href="{{ route('admin.blogcategory.index') }}" class="btn btn-default pull-right" title="Cancel"><i class="fa fa-fw fa-chevron-circle-left"></i> Back</a>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            @if(isset($blogcat))
            {{ Form::model($blogcat, ['route' => ['admin.blogcategory.update', $blogcat->id], 'method' => 'patch', 'id' => 'submit-faq']) }}
            @else
            {{ Form::open(['route' => ['admin.blogcategory.store'], 'id' => 'submit-faq']) }}
            @endif
            <div class="card-body">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group required {{ $errors->has('title') ? 'has-error' : '' }}">
                    <label for="title">Category Name</label>
                    {{ Form::text('title', old('title'), ['class' => 'form-control textarea-resize','placeholder' => 'Category Name','required']) }}
                   
                    @if($errors->has('title'))
                    <span class="help-block">{{ $errors->first('title') }}</span>
                    @endif
                  </div>
                </div>               

              </div> <!-- /.row -->


              <div class="row">
                <div class="col-md-12">
                  <label class="chk-container">Is Active
                    @if(isset($blogcat))
                    @if($blogcat->status == 1)
                    <input name="status" type="checkbox" checked="checked">
                    @else
                    <input name="status" type="checkbox">
                    @endif
                    @else
                    <input name="status" type="checkbox" checked="checked">
                    @endif
                    <span class="checkmark"></span>
                  </label>
                </div>
              </div>


              



            </div><!-- /.card-body -->
            <div class="card-footer">
              <button class="btn btn-primary btn-flat submit-form" title="Submit" type="submit"><i class="fa fa-fw fa-save"></i> Submit</button>
              <a href="{{ route('admin.blogcategory.index') }}" class="btn btn-warning btn-fanswer" title="Cancel"><i class="fa fa-fw fa-chevron-circle-left"></i> Back</a>
            </div>
            {{ Form::close() }}
          </div>
          <!-- /.card-body -->

        </div>
        <!-- /.card -->
      </div>
      <!-- /.col -->
    </div>
    <!-- /.content -->
    @push('scripts')
    <script type="text/javascript">
      $(document).ready(function(){
        $("form").submit(function( event ) {
          $('.submit-form').attr('disabled', 'disabled');
        });
      });
    </script>
    @endpush
  </x-slot>
</x-layouts.master>
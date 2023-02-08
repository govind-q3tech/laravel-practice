<x-layouts.master>
  @section('title', 'Blog')
  <!-- Content Header (Faq header) -->
  <x-slot name="breadcrumb">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Admin Blog Manager</h1>
      </div>
      <div class="col-sm-6">

        {{ Breadcrumbs::render('common',['append' => [['label'=> 'Blog','route'=> 'admin.blog.index'],['label' => !empty($editbolg) ? 'Edit Blog' : 'Add Blog' ]]]) }}
      </div>
    </div>
  </x-slot>

  <x-slot name="content">
    <!-- Main content -->
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Admin Blog</h3>

            <div class="card-tools">
              <a href="{{ route('admin.blog.index') }}" class="btn btn-default pull-right" title="Cancel"><i class="fa fa-fw fa-chevron-circle-left"></i> Back</a>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            @if(isset($editbolg))
            {{ Form::model($editbolg, ['route' => ['admin.blog.update', $editbolg->id], 'method' => 'patch', 'id' => 'submit-faq','enctype' => "multipart/form-data"]) }}
            @else
            {{ Form::open(['route' => ['admin.blog.store'], 'id' => 'submit-faq','enctype' => "multipart/form-data"]) }}
            @endif
            <div class="card-body">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group required {{ $errors->has('title') ? 'has-error' : '' }}">
                    <label for="title">Title</label>
                    {{ Form::text('title', old('title'), ['class' => 'form-control textarea-resize','placeholder' => 'Title']) }}
                   
                    @if($errors->has('title'))
                    <span class="help-block">{{ $errors->first('title') }}</span>
                    @endif                    
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group required {{ $errors->has('meta_title') ? 'has-error' : '' }}">
                      <label for="title">Meta Title</label>
                      {{ Form::text('meta_title', old('meta_title'), ['class' => 'form-control textarea-resize','placeholder' => 'Meta Title']) }}
                     
                      @if($errors->has('meta_title'))
                      <span class="help-block">{{ $errors->first('meta_title') }}</span>
                      @endif                    
                   </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group required {{ $errors->has('sub_title') ? 'has-error' : '' }}">
                    <label for="answer">Sub title</label>
                    {{ Form::text('sub_title', old('sub_title'), ['class' => 'form-control textarea-resize','placeholder' => 'Sub Title']) }}
                   
                    @if($errors->has('sub_title'))
                    <span class="help-block">{{ $errors->first('sub_title') }}</span>
                    @endif
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-group required {{ $errors->has('meta_keyword') ? 'has-error' : '' }}">
                    <label for="ordering">Meta keyword</label>
                    {{ Form::text('meta_keyword', old('meta_keyword'), ['class' => 'form-control','placeholder' => 'Meta keyword']) }}
                    @if($errors->has('meta_keyword'))
                    <span class="help-block">{{ $errors->first('meta_keyword') }}</span>
                    @endif
                  </div>
                </div>
              </div> <!-- /.row -->
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group required {{ $errors->has('blog_categories_id') ? 'has-error' : '' }}">
                  <label>Select Blog category</label>
                  {{ Form::select('blog_categories_id',$blogcategory ,old('blog_categories_id'), ['class' => 'form-control','placeholder' => 'Select Blog category']) }}
                
                @if($errors->has('blog_categories_id'))
                    <span class="help-block">{{ $errors->first('blog_categories_id') }}</span>
                    @endif
                <br>
              </div>
              </div>
            </div>
         

              <div class="row">
                <div class="col-md-12">
                <div class="form-group required {{ $errors->has('description') ? 'has-error' : '' }}">
                  <label><strong>Description :</strong></label>
                  <textarea class="ckeditor form-control" name="description">@if(isset($editbolg->description)){!!$editbolg->description!!} @else @endif</textarea>
                   @if($errors->has('description'))
                    <span class="help-block">{{ $errors->first('description') }}</span>
                    @endif
                </div>
              </div>

              <div class="row">
                <div class="col-md-8">
                  <div class="form-group required {{ $errors->has('images') ? 'has-error' : '' }}">
                <label>Image</label>
                <input type="file" name="images" class="form-control" >
                @if($errors->has('images'))
                    <span class="help-block">{{ $errors->first('images') }}</span>
                    @endif
              </div>
                </div>
                <div class="col-md-4">
                 <img src="@if(isset($editbolg->images)){{asset('/images/'.$editbolg->images)}} @else @endif" style="width:100px;">
                </div>
              </div>

              </div>

              <div class="row">
                <div class="col-md-12">
                  <label class="chk-container">Is Active
                    @if(isset($editbolg))
                    @if($editbolg->status == 1)
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
              <button class="btn btn-primary btn-fanswer submit-form" title="Submit" type="submit"><i class="fa fa-fw fa-save"></i> Submit</button>
              <a href="{{ route('admin.blog.index') }}" class="btn btn-warning btn-fanswer" title="Cancel"><i class="fa fa-fw fa-chevron-circle-left"></i> Back</a>
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
  </x-slot>
  <script src="//cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
  <script type="text/javascript">
    $(document).ready(function() {
       $('.ckeditor').ckeditor();
    });
      $(document).ready(function(){
        $("form").submit(function( event ) {
          $('.submit-form').attr('disabled', 'disabled');
        });
      });  
  </script> 
</x-layouts.master>
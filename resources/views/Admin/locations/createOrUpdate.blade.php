<x-layouts.master>
  @section('title', 'Locations')
  <!-- Content Header (Location header) -->
  <x-slot name="breadcrumb">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Location Manager</h1>
      </div>
      <div class="col-sm-6">

        {{ Breadcrumbs::render('common',['append' => [['label'=> $getController,'route'=> 'admin.locations.index'],['label' => !empty($location) ? 'Edit Location' : 'Add Location' ]]]) }}
      </div>
    </div>
  </x-slot>

  <x-slot name="content">
    <!-- Main content -->
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Location</h3>

            <div class="card-tools">
              <a href="{{ route('admin.locations.index') }}" class="btn btn-default pull-right" title="Cancel"><i class="fa fa-fw fa-chevron-circle-left"></i> Back</a>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            @if(isset($location))
            {{ Form::model($location, ['route' => ['admin.locations.update', $location->id], 'method' => 'patch', 'id' => 'submit-location']) }}
            @else
            {{ Form::open(['route' => ['admin.locations.store'], 'id' => 'submit-location']) }}
            @endif
            <div class="box-body">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group required {{ $errors->has('area_id') ? 'has-error' : '' }}">
                    <label for="area_id">Select Area</label>
                    {{ Form::select('area_id', $cityAreas,  old('area_id') , ['class' => 'form-control select2', 'placeholder' => 'Please Select']) }}
                    @if($errors->has('area_id'))
                    <span class="help-block">{{ $errors->first('area_id') }}</span>
                    @endif
                  </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group required {{ $errors->has('title') ? 'has-error' : '' }}">
                      <label for="title">Title</label>
                      {{ Form::text('title', old('title'), ['class' => 'form-control','placeholder' => 'Title']) }}
                      @if($errors->has('title'))
                      <span class="help-block">{{ $errors->first('title') }}</span>
                      @endif
                    </div>
                  </div>
                  {{-- <div class="col-md-6">
                    <div class="form-group required {{ $errors->has('slug') ? 'has-error' : '' }}">
                      <label for="title">slug</label>
                      {{ Form::text('slug', old('slug'), ['class' => 'form-control','placeholder' => 'Slug']) }}
                      @if($errors->has('slug'))
                      <span class="help-block">{{ $errors->first('slug') }}</span>
                      @endif
                    </div>
                  </div> --}}
                  {{-- <div class="col-md-6" style="display: none;">
                    <div class="form-group required {{ $errors->has('lat') ? 'has-error' : '' }}">
                      <label for="lat">Latitude</label>
                      {{ Form::text('lat', old('lat'), ['class' => 'form-control','placeholder' => 'Latitude']) }}
                      @if($errors->has('lat'))
                      <span class="help-block">{{ $errors->first('lat') }}</span>
                      @endif
                    </div>
                  </div>
                  <div class="col-md-6" style="display: none;">
                    <div class="form-group required {{ $errors->has('lng') ? 'has-error' : '' }}">
                      <label for="lng">Longitude</label>
                      {{ Form::text('lng', old('lng'), ['class' => 'form-control','placeholder' => 'Longitude']) }}
                      @if($errors->has('lng'))
                      <span class="help-block">{{ $errors->first('lng') }}</span>
                      @endif
                    </div>
                  </div> --}}

              </div> <!-- /.row -->


              <div class="row">
                <div class="col-md-6">
                  <div class="form-group  {{ $errors->has('meta_title') ? 'has-error' : '' }}">
                    <label for="title">Meta Title</label>
                    {{ Form::text('meta_title', old('meta_title'), ['class' => 'form-control','placeholder' => 'Meta Title']) }}
                    @if($errors->has('meta_title'))
                    <span class="help-block">{{ $errors->first('meta_title') }}</span>
                    @endif
                  </div>
                </div>
                <div class="col-md-6">

                  <div class="form-group  {{ $errors->has('meta_keyword') ? 'has-error' : '' }}">
                    <label for="title">Meta Keyword</label>
                    {{ Form::text('meta_keyword', old('meta_keyword'), ['class' => 'form-control','placeholder' => 'Meta Keyword']) }}
                    @if($errors->has('meta_keyword'))
                    <span class="help-block">{{ $errors->first('meta_keyword') }}</span>
                    @endif
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group  {{ $errors->has('meta_description') ? 'has-error' : '' }}">
                    <label for="description">Meta Description</label>
                    {{ Form::textarea('meta_description', old('meta_description'), ['class' => 'form-control','placeholder' => 'Meta Description', 'rows' => 4]) }}
                    @if($errors->has('meta_description'))
                    <span class="help-block">{{ $errors->first('meta_description') }}</span>
                    @endif
                  </div>

                </div>
              </div>
              


              <div class="row" style="margin-top: 15px;">
                <div class="col-md-12">

                  <div class="form-group  {{ $errors->has('description') ? 'has-error' : '' }}">
                    <label for="description">Description</label>
                    {{ Form::textarea('description', old('description'), ['class' => 'form-control ckeditor','placeholder' => 'Description', 'rows' => 8]) }}
                    @if($errors->has('description'))
                    <span class="help-block">{{ $errors->first('description') }}</span>
                    @endif
                  </div>


                </div>
              </div>

              <div class="row">
                <div class="col-md-12">
                  <label class="chk-container">Is Active
                    @if(isset($location))
                    @if($location->status == 1)
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

            </div><!-- /.box-body -->
            <div class="box-footer">
              <button class="btn btn-primary btn-flat submit-form" title="Submit" type="submit"><i class="fa fa-fw fa-save"></i> Submit</button>
              <a href="{{ route('admin.locations.index') }}" class="btn btn-warning btn-flat" title="Cancel"><i class="fa fa-fw fa-chevron-circle-left"></i> Back</a>
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
   @push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
      .select2-container--default .select2-selection--multiple .select2-selection__choice {
        color: #000000;
      }
      .select2-container .select2-selection--single {
        height: 38px;
      }
      .select2-container--default .select2-selection--single .select2-selection__arrow{
        height: 36px;
      }
    </style>
    @endpush
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script type="text/javascript">
      $(document).ready(function() {
        $("form").submit(function( event ) {
            $('.submit-form').attr('disabled', 'disabled');
        });
      });

      $(document).ready(function() {
        $('.select2').select2({
          'placeholder': 'Please Select'
        });
      });
    </script>
    @endpush
</x-layouts.master>
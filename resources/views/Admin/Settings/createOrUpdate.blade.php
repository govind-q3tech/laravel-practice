<x-layouts.master>
  @section('title', !empty($setting) ? 'Edit General Setting' : 'Add General Setting')
 <!-- Content Header (Page header) -->
<x-slot name="breadcrumb">
    <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Admin Setting Manager</h1>
        </div>
        <div class="col-sm-6">
          {{-- <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Simple Tables</li> 
          </ol> --}}
          @php
           // dd(\Request::route()->getName());
          @endphp
          {{ Breadcrumbs::render('common',['append' => [['label'=> 'General Settings', 'route'=> 'admin.settings.index'], ['label' => !empty($setting) ? 'Edit General Setting' : 'Add General Setting' ]]]) }}
        </div>
      </div>
</x-slot>
<x-slot name="content">
<!-- Main content -->
@php
$disabled = '';
@endphp
@if(isset($setting) && $setting->exists)
  @php
      $queryStr['setting'] = $setting->id;
      $queryStr = array_merge( $queryStr , app('request')->query());
      $disabled = 'disabled';
  @endphp
      {{ Form::model($setting, ['url' => route('admin.settings.update', $queryStr), 'method' => 'patch','files' => true]) }}
  @else
      {{ Form::open(['url' => route('admin.settings.store', app('request')->query()),'files' => true]) }}
  @endif
  <div class="row">
    <div class="col-md-12">
      <!-- SELECT2 EXAMPLE -->
      <div class="card card-default">
        <div class="card-header">
          <h3 class="card-title">{{ !empty($setting) ? 'Edit General Setting' : 'Add General Setting'  }}</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <div class="row">
            <div class="col-md-12">
              {{ Form::hidden('manager', 'manager') }}
              {{ Form::hidden('field_type', 'text') }}
              <div class="form-group row required {{ $errors->has('title') ? 'has-error' : '' }}">
                {{ Form::label('title', __('Title'), ['class' =>'col-sm-2 col-form-label']) }}
                <div class="col-sm-6">
                  {{ Form::text('title', old('title'), ['class' => 'form-control' . ($errors->has('title') ? ' is-invalid' : ''), 'placeholder' => 'Title']) }}
                  @error('title')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div>
              </div>
              <div class="form-group row {{ $errors->has('slug') ? 'has-error' : '' }}">
                {{ Form::label('slug', __('Constant/Slug'), ['class' =>'col-sm-2 col-form-label']) }}
                <div class="col-sm-6">
                  {{ Form::text('slug', old('slug'), ['class' => 'form-control' . ($errors->has('slug') ? ' is-invalid' : ''), 'placeholder' => 'Constant/Slug',$disabled]) }}
                  @error('slug')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                  <p class="help-block">No space, separate each word with underscore. (if you want auto generated then please leave blank)</p>
                </div>
              </div>

              <div class="form-group row required {{ $errors->has('config_value') ? 'has-error' : '' }}">
                {{ Form::label('config_value', __('Config Value'), ['class' =>'col-sm-2 col-form-label']) }}
                <div class="col-sm-6">
                  {{ Form::textarea('config_value', old('config_value'), ['class' => 'form-control' . ($errors->has('config_value') ? ' is-invalid' : ''), 'placeholder' => 'Constant/Slug']) }}
                  @error('config_value')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div>
              </div>
            </div>
          </div>
          <!-- /.row -->
        </div>
        <!-- /.card-body -->
        <div class="card-footer">
          <button class="btn btn-primary btn-flat submit-form" title="Submit" type="submit"><i class="fa fa-fw fa-save"></i> Submit</button>
          <a href="{{ route('admin.settings.index') }}" class="btn btn-default float-right">Cancel</a>
        </div>
      </div>
      <!-- /.card -->
      <!-- /.card -->
    </div>
    <!-- /.col -->
  </div>
  <!-- /.content -->
  @push('scripts')
    <script type="text/javascript">
      $(document).ready(function() {
        $("form").submit(function( event ) {
            $('.submit-form').attr('disabled', 'disabled');
        });
      });
    </script>
  @endpush
</x-slot>
</x-layouts.master>

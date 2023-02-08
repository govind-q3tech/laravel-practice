<x-layouts.master>
  @section('title', !empty($adminUser) ? 'Edit Admin Role' : 'Add Admin Role')
 <!-- Content Header (Page header) -->
<x-slot name="breadcrumb">
    <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Admin Role Manager</h1>
        </div>
        <div class="col-sm-6">
          @php
           // dd(\Request::route()->getName());
          @endphp
          {{ Breadcrumbs::render('common',['append' => [['label'=> $getController, 'route'=> 'admin.adminroles.index'], ['label' => !empty($adminUser) ? 'Edit Admin Role' : 'Add Admin Role' ]]]) }}
        </div>
      </div>
</x-slot>
<x-slot name="content">
  <!-- Main content -->
@if(isset($adminRole) && $adminRole->exists)
  @php
      $queryStr['roles'] = $adminRole->id;
      $queryStr = array_merge( $queryStr , app('request')->query());
  @endphp
      {{ Form::model($adminRole, ['url' => route('admin.adminroles.update', $queryStr), 'method' => 'patch','files' => true]) }}
  @else
      {{ Form::open(['url' => route('admin.adminroles.store', app('request')->query()),'files' => true]) }}
  @endif
  <div class="row">
    <div class="col-md-12">
      <!-- SELECT2 EXAMPLE -->
      <div class="card card-default">
        <div class="card-header">
          <h3 class="card-title">{{ !empty($adminRole) ? 'Edit Admin Role' : 'Add Admin Role'  }}</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group row required">
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

              
              <div class="form-group required {{ $errors->has('status') ? 'has-error' : '' }}">
                <div class="row">
                    {{ Form::label('status', __('Status'), ['class' =>'col-md-2 col-form-label text-md-left']) }}
                    <div class="col-md-6">
                        {{ Form::select('status', [1 => 'Active', 0 => 'Inactive'], old("status"), ['class' => 'form-control']) }}
                    </div>
                </div>
            </div>
              
            </div>
          </div>
          <!-- /.row -->
        </div>
        <!-- /.card-body -->
        <div class="card-footer">
          <button type="submit" class="btn btn-info">Submit</button>
          <a href="{{ route('admin.adminroles.index') }}" class="btn btn-default float-right">Cancel</a>
        </div>
      </div>
      <!-- /.card -->
      <!-- /.card -->
    </div>
    <!-- /.col -->
  </div>
  <!-- /.content -->
</x-slot>
</x-layouts.master>

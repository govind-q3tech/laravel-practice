<x-layouts.master>
  @section('title', 'Change Password')
 <!-- Content Header ( header) -->
<x-slot name="breadcrumb">
    <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Change Password</h1>
        </div>
        <div class="col-sm-6">
          {{ Breadcrumbs::render('common',['append' => [['label'=> 'Dashboard', 'route'=> 'admin.dashboard'], ['label' => Auth::user()->first_name .' Change Password' ]]]) }}
        </div>
      </div>
</x-slot>
<x-slot name="content">
  <!-- Main content -->

  {{ Form::open(['url' => route('admin.change.password'), 'method' => 'POST','files' => true]) }}
  
  <div class="row">
    <div class="col-md-12">
      <!-- SELECT2 EXAMPLE -->
      <div class="card card-default">
        <div class="card-header">
          <h3 class="card-title">Change Password</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group row required">
                {{ Form::label('current_password', __('Current Password'), ['class' =>'col-sm-2 col-form-label']) }}
                <div class="col-sm-6">
                  {{-- Form::text('current_password', old('current_password'), ['class' => 'form-control' . ($errors->has('current_password') ? ' is-invalid' : ''), 'placeholder' => 'Current Password']) --}}
                  <input name="current_password" type="password" value="{{old('current_password')}}" id="current_password" class="form-control{{ $errors->has('current_password') ? ' is-invalid' : ''}}" placeholder="Current Password">
                  @error('current_password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div>
              </div>              
              <div class="form-group row required">
                {{ Form::label('new_password', __('New Password'), ['class' =>'col-sm-2 col-form-label']) }}
                <div class="col-sm-6">
                  {{-- Form::text('new_password', old('new_password'), ['class' => 'form-control' . ($errors->has('new_password') ? ' is-invalid' : ''), 'placeholder' => 'New Password']) --}}
                  <input name="new_password" type="password" value="{{old('new_password')}}" id="new_password" class="form-control{{ $errors->has('new_password') ? ' is-invalid' : ''}}" placeholder="New Password">
                  @error('new_password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div>
              </div>              
              <div class="form-group row required">
                {{ Form::label('new_confirm_password', __('Confirm Password'), ['class' =>'col-sm-2 col-form-label']) }}
                <div class="col-sm-6">
                  {{-- Form::text('new_confirm_password', old('new_confirm_password'), ['class' => 'form-control' . ($errors->has('new_confirm_password') ? ' is-invalid' : ''), 'placeholder' => 'Confirm New Password']) --}}
                  <input name="new_confirm_password" type="password" value="{{old('new_confirm_password')}}" id="new_confirm_password" class="form-control{{ $errors->has('new_confirm_password') ? ' is-invalid' : ''}}" placeholder="Confirm Password">
                  @error('new_confirm_password')
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
          <button type="submit" class="btn btn-info">Submit</button>
          <a href="{{ route('admin.dashboard') }}" class="btn btn-default float-right">Cancel</a>
        </div>
      </div>
      <!-- /.card -->
      <!-- /.card -->
    </div>
    <!-- /.col -->
  </div>
  <!-- /.content -->
</x-slot>
@push('scripts')
<script>
//alert('I\'m coming from child')
</script>
@endpush
</x-layouts.master>

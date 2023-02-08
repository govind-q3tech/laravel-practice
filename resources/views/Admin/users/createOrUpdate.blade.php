<?php
use Illuminate\Support\Str;

 $qparams = app('request')->query();
if (isset($qparams['type']) && $qparams['type'] == 'customers'):
$title = "Customers Manager";
$add = "Customer";
elseif (isset($qparams['type']) && $qparams['type'] == 'advertisers'):
$title = "Advertisers Manager";
$add = "Advertiser";
else:
$title = "User Manager";
$add = "User";
endif

?>
<x-layouts.master>
@section('title', $title)
  <!-- Content Header (Customers header) -->
  <x-slot name="breadcrumb">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>{{ $title }}</h1>
      </div>
      <div class="col-sm-6">

        {{ Breadcrumbs::render('common',['append' => [['label'=> $title ,'url'=> url()->previous()],['label' => !empty($user) ? 'Edit '.$add : 'Add '.$add ]]]) }}
      </div>
    </div>
  </x-slot>

  <x-slot name="content">
    <!-- Main content -->
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">{{ $title }}</h3>

            <div class="card-tools">
              <a href="{{ route('admin.users.index', ['type' => $qparams['type']]) }}" class="btn btn-default pull-right" title="Cancel"><i class="fa fa-fw fa-chevron-circle-left"></i> Back</a>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            @if(isset($user))
            {{ Form::model($user, ['route' => ['admin.users.update', $user->id], 'method' => 'patch', 'id' => 'submit-user']) }}
            @else
            {{ Form::open(['route' => ['admin.users.store'], 'id' => 'submit-user']) }}
            @endif
            {{ Form::hidden('random', Str::random(8)) }}
            {{ Form::hidden('type', $qparams['type']) }}
            <div class="box-body">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group required {{ $errors->has('first_name') ? 'has-error' : '' }}">
                    <label for="first_name">First Name</label>
                    {{ Form::text('first_name', old('first_name'), ['class' => 'form-control','placeholder' => 'First Name']) }}
                    @if($errors->has('first_name'))
                    <span class="help-block">{{ $errors->first('first_name') }}</span>
                    @endif
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-group  {{ $errors->has('last_name') ? 'has-error' : '' }}">
                    <label for="last_name">Last name</label>
                    {{ Form::text('last_name', old('last_name'), ['class' => 'form-control','placeholder' => 'Last Name']) }}
                    @if($errors->has('last_name'))
                    <span class="help-block">{{ $errors->first('last_name') }}</span>
                    @endif
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-group required {{ $errors->has('email') ? 'has-error' : '' }}">
                    <label for="email">Email</label>
                    {{ Form::email('email', old('email'), ['class' => 'form-control','placeholder' => 'Email', 'readonly' => (isset($user)) ? true : false]) }}
                    @if($errors->has('email'))
                    <span class="help-block">{{ $errors->first('email') }}</span>
                    @endif
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group required {{ $errors->has('phone') ? 'has-error' : '' }}">
                    <label for="title">Phone</label>
                    {{ Form::text('phone', old('phone'), ['class' => 'form-control','placeholder' => '254xxxxxxxxx or 0xxxxxxxxx','maxlength' => 12, (isset($user)) ? true : false]) }}
                    @if($errors->has('phone'))
                    <span class="help-block">{{ $errors->first('phone') }}</span>
                    @endif
                  </div>
                </div>
              </div>
              <div class="row">
                
                @if (isset($qparams['type']) && $qparams['type'] == 'advertisers')
                <div class="col-md-6">
                  <div class="form-group required {{ $errors->has('description') ? 'has-error' : '' }}">
                    <label for="title">Description</label>
                    {{ Form::textarea('description', old('description'), ['class' => 'form-control textarea-resize','placeholder' => 'Description', 'rows' => 4]) }}
                    @if($errors->has('description'))
                    <span class="help-block">{{ $errors->first('description') }}</span>
                    @endif
                  </div>
                  <div class="form-group required {{ $errors->has('store_name') ? 'has-error' : '' }}">
                    <label for="store_name">Store name</label>
                    {{ Form::text('store_name', old('store_name'), ['class' => 'form-control','placeholder' => 'Store Name']) }}
                    @if($errors->has('store_name'))
                    <span class="help-block">{{ $errors->first('store_name') }}</span>
                    @endif
                  </div>
                </div>
                @endif
                <div class="col-md-6">
                  <div class="form-group  {{ $errors->has('address') ? 'has-error' : '' }}">
                    <label for="title">Address</label>
                    {{ Form::textarea('address', old('address'), ['class' => 'form-control textarea-resize','placeholder' => 'Address', 'rows' => 4]) }}
                    @if($errors->has('address'))
                    <span class="help-block">{{ $errors->first('address') }}</span>
                    @endif
                  </div>
                </div>
              </div>
              </div>
              <div class="row">
                 <div class="col-md-6">
                    <div class="form-group">
                      <label class="chk-container">Is Active
                        @if(isset($user))
                        @if($user->status == 1)
                        <input name="status" type="checkbox" checked="checked">
                        @else
                        <input name="status" type="checkbox">
                        @endif
                        @else
                        <input name="status" type="checkbox" checked="checked">
                        @endif
                        <span class="checkmark"></span>
                      </label>
                      @php /*
                      @if (isset($qparams['type']) && $qparams['type'] == 'advertisers')
                          <label class="chk-container" style="margin-left: 10px;">Is Approved
                            @if(isset($user))
                            @if($user->is_approved == 1)
                            <input name="is_approved" type="checkbox" checked="checked">
                            @else
                            <input name="is_approved" type="checkbox">
                            @endif
                            @else
                            <input name="is_approved" type="checkbox" checked="checked">
                            @endif
                            <span class="checkmark"></span>
                          </label>
                        @endif
                        */
                        @endphp
                    </div>
                  </div>
              </div>

            </div><!-- /.box-body -->
            <div class="card-footer">
              <button class="btn btn-primary btn-flat submit-form" title="Submit" type="submit"><i class="fa fa-fw fa-save"></i> Submit</button>
              <a href="{{ route('admin.users.index', ['type' => $qparams['type']]) }}" class="btn btn-warning btn-flat" title="Cancel"><i class="fa fa-fw fa-chevron-circle-left"></i> Back</a>
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
        $(document).ready(function () {   
            $("form").submit(function( event ) {
                $('.submit-form').attr('disabled', 'disabled');
            });
        });
    </script>  
    @endpush
  </x-slot>
</x-layouts.master>
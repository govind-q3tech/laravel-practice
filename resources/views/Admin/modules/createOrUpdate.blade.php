                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              <x-layouts.master>
  @section('title', 'Modules')
  <!-- Content Header (Module header) -->
  <x-slot name="breadcrumb">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Admin Permission Manager</h1>
      </div>
      <div class="col-sm-6">

        {{ Breadcrumbs::render('common',['append' => [['label'=> "Admin Roles",'route'=> 'admin.adminroles.index'],['label' => 'Permission' ]]])}}
      </div>
    </div>
  </x-slot>

  <x-slot name="content">
    <!-- Main content -->
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          @if ($errors->any())
    <div class="alert alert-danger">
       
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        
    </div>
@endif
          <div class="card-header">
            <h3 class="card-title">Admin Permission</h3>
            <div class="card-tools">
              <a href="{{ route('admin.adminroles.index') }}" class="btn btn-default pull-right" title="Cancel"><i class="fa fa-fw fa-chevron-circle-left"></i> Back</a>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
         
            {{ Form::open(['route' => ['admin.modules.store'], 'id' => 'submit-module']) }}
         
            <div class="box-body">
              <div class="row">
              <div class="col-md-{{12/($roles->count()+1)}}">
                  <div class="form-group">
                    <label for="title">Modules</label>
                  </div>
                </div>
              @foreach($roles as $role)                                                                                                                                                                                                   
                <div class="col-md-{{12/($roles->count()+1)}}">
                  <div class="form-group">
                    <input type="hidden" name="admin_role_id" value="{{ $role->id }}">
                    <label for="title">{{ $role->title }}</label>
                  </div>
                </div>
              @endforeach  
              </div> <!-- /.row -->
              @php 
              $gName = '';
              @endphp
              @foreach($modules as $module)
              @php
              $roleData = $module->roles->map(function($items){
                  return $items->id;
              })->toArray();                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   
              $group_name = explode('-',$module->slug);
              if(count($group_name) == 2){
                $gName = $group_name[1];
              }elseif(count($group_name) == 2){
                $gName = $group_name[2];
              }
              @endphp
              <div class="row">
              <div class="col-md-{{12/($roles->count()+1)}}">
                  <div class="form-group">
                    <label for="title">{{ $module->title }} 
                      @if($gName == 'index')
                      (Index/Show)
                      @elseif($gName == 'create')
                      (Add/Edit/Delete)
                      @endif
                    </label>
                  </div>
                </div>
                @foreach($roles as $role)                                         
                  <div class="col-md-{{12/($roles->count()+1)}}">
                    <label class="chk-container"> 
                      <input  name="module[{{ $module->id }}][admin_role_id]" value="{{ $role->id }}" type="checkbox" @if(in_array($role->id, $roleData)) checked="checked"  @endif >
                    </label>
                  </div>
                @endforeach
              </div>
              @endforeach
            </div><!-- /.box-body -->
            <div class="box-footer">
              <button class="btn btn-primary btn-flat submit-form" title="Submit" type="submit"><i class="fa fa-fw fa-save"></i> Submit</button>
              
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
</x-layouts.master>
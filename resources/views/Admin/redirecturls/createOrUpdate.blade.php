<x-layouts.master>
@section('title', 'Manage Redirect Url')
<x-slot name="breadcrumb">
    <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Manage Redirect Url</h1>
        </div>
        <div class="col-sm-6">
     
         {{ Breadcrumbs::render('common',['append' => [['label'=> 'Redirect Urls'],['label' => !empty($redirectUrl) ? 'Edit Redirect Url' : 'Add Redirect Url' ]]]) }}
        </div>
      </div>
</x-slot>
<x-slot name="content">
    <!-- Main content -->
    <div class="row card-body">
        <div class="col-md-12">
            <div class="card">
            <div class="card-header">
                 <h3 class="card-title">{{ !empty($redirectUrl) ? 'Edit Redirect Url' : 'Add Redirect Url' }} </h3>
                <div class="card-tools">
                   <a href="{{ route('admin.redirecturls.index') }}"class="btn btn-default pull-right" title="Cancel"><i class="fa fa-fw fa-chevron-circle-left"></i> Back</a>
                </div><!-- /.box-header -->
            </div>
            
                @if(isset($redirectUrl))
                {{ Form::model($redirectUrl, ['route' => ['admin.redirecturls.update', $redirectUrl->id], 'method' => 'patch']) }}
                  <input type="hidden" id="id" name="id" value="{{$redirectUrl->id}}">
                @else
                {{ Form::open(['route' => 'admin.redirecturls.store']) }}
                @endif
                <div class="card-body">
                    <!-- /.row --> 
                    <div class="row">
                        <div class="col-md-6"> 
                            <div class="form-group required {{ $errors->has('old_url') ? 'has-error' : '' }}">
                                <label for="description">Old Url</label>
                                {{ Form::text('old_url', old('old_url'), ['class' => 'form-control ','placeholder' => 'Old Url']) }}
                                @if($errors->has('old_url'))
                                <span class="help-block">{{ $errors->first('old_url') }}</span>
                                @endif
                            </div>


                        </div>
                        <div class="col-md-6"> 
                            <div class="form-group required {{ $errors->has('new_url') ? 'has-error' : '' }}">
                                <label for="description">New Url</label>
                                {{ Form::text('new_url', old('new_url'), ['class' => 'form-control ','placeholder' => 'New Url']) }}
                                @if($errors->has('new_url'))
                                <span class="help-block">{{ $errors->first('new_url') }}</span>
                                @endif
                            </div> 
                        </div>
                    </div>


                </div><!-- /.box-body -->
                <div class="box-footer">
                    <button class="btn btn-primary btn-flat" title="Submit" type="submit"><i class="fa fa-fw fa-save"></i> Submit</button>
                    <a href="{{ route('admin.redirecturls.index') }}" class="btn btn-warning btn-flat" title="Cancel"><i class="fa fa-fw fa-chevron-circle-left"></i> Back</a>
                </div>
                {{ Form::close() }}
             </div>
            </div>
        </div>
    </div>
    <!-- /.content -->
</x-slot>

</x-layouts.master>

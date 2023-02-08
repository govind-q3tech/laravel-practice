<x-layouts.master>
@section('name', 'CMS reviews')
 <!-- Content Header (review header) -->
<x-slot name="breadcrumb">
    <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Reviews</h1>
        </div>
        <div class="col-sm-6">
     
          {{ Breadcrumbs::render('common',['append' => [['label'=> $getController,'route'=> 'admin.reviews.index'],['label' => 'Edit Review']]]) }}
        </div>
      </div>
</x-slot>

<x-slot name="content">
  <!-- Main content -->
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-name">Reviews</h3>

          <div class="card-tools">
          <a href="{{ route('admin.reviews.index') }}" class="btn btn-default pull-right" name="Cancel"><i class="fa fa-fw fa-chevron-circle-left"></i> Back</a>
          </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
       
                {{ Form::model($review, ['route' => ['admin.reviews.update', $review->id], 'method' => 'patch', 'id' => 'submit-review']) }}
               
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">

                            <div class="form-group  {{ $errors->has('name') ? 'has-error' : '' }}">
                                <label for="name">name</label>
                                {{ Form::text('name', old('name'), ['class' => 'form-control','placeholder' => 'name', 'disabled']) }}
                                @if($errors->has('name'))
                                <span class="help-block">{{ $errors->first('name') }}</span>
                                @endif
                              </div>


                              <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                                <label for="name">Email</label>
                                {{ Form::text('email', old('email'), ['class' => 'form-control','placeholder' => 'email', 'disabled']) }}
                                @if($errors->has('email'))
                                <span class="help-block">{{ $errors->first('email') }}</span>
                                @endif
                              </div>

                              <div class="form-group">
                                <label for="rating">Rating</label>
                                {{ Form::select('rating', [1=>1,2=>2,3=>3,4=>4,5=>5], old('rating'), ['class' => 'form-control','placeholder' => 'Please Select']) }}
                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="form-group  {{ $errors->has('title') ? 'has-error' : '' }}">
                                <label for="name">title</label>
                                {{ Form::text('title', old('title'), ['class' => 'form-control','placeholder' => 'Title']) }}
                                @if($errors->has('title'))
                                <span class="help-block">{{ $errors->first('title') }}</span>
                                @endif
                              </div>

                              

                              <div class="form-group  {{ $errors->has('review') ? 'has-error' : '' }}">
                                <label for="description">Review</label>
                                {{ Form::textarea('review', old('review'), ['class' => 'form-control','placeholder' => 'Meta Description', 'rows' => 4]) }}
                                @if($errors->has('review'))
                                <span class="help-block">{{ $errors->first('review') }}</span>
                                @endif
                            </div>

                        </div>


                    </div> <!-- /.row -->

                    <div class="row" style="display:none">
                        <div class="col-md-12">
                            <label class="chk-container">Is Active
                              
                                   
                                        <input name="status" type="checkbox" checked="checked">
                                   
                                
                                <span class="checkmark"></span>
                            </label>
                        </div>
                    </div>

                    <div class="row">
                      <div class="col-md-12">
                          <label class="chk-container">Admin Approve
                            
                                  @if($review->admin_approve == 1)
                                      <input name="admin_approve" type="checkbox" checked="checked">
                                  @else 
                                      <input name="admin_approve" type="checkbox">
                                  @endif
                              
                              <span class="checkmark"></span>
                          </label>
                      </div>
                  </div>


                    



                </div><!-- /.box-body -->
                <div class="box-footer">
                        <button class="btn btn-primary btn-flat submit-form" name="Submit" type="submit"><i class="fa fa-fw fa-save"></i> Submit</button>
                        <a href="{{ route('admin.reviews.index') }}" class="btn btn-warning btn-flat" name="Cancel"><i class="fa fa-fw fa-chevron-circle-left"></i> Back</a>
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

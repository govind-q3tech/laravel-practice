<x-layouts.master>
@section('title', 'CMS reviews')
  <!-- Content Header (review header) -->
  <x-slot name="breadcrumb">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Reviews</h1>
      </div>
      <div class="col-sm-6">
      {{ Breadcrumbs::render('common',['append' => [['label'=> $getController,'route'=> 'admin.reviews.index'],['label' => 'View review Detail']]]) }}
      </div>
    </div>
  </x-slot>

  <x-slot name="content">
    <!-- Main content -->
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Reviews</h3>

            <div class="card-tools">
            <a href="{{ route('admin.reviews.index') }}" class="btn btn-default pull-right" title="Cancel"><i class="fa fa-fw fa-chevron-circle-left"></i> Back</a>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
          <table class="table table-hover table-striped">
                <tr>
                    <th scope="row">{{ __('Name') }}</th>
                    <td>{{ $review->user->first_name }}</td>
                </tr>
                <tr>
                    <th scope="row">{{ __('Email') }}</th>
                    <td>{{ $review->user->email}}</td>
                </tr>

                <tr>
                    <th scope="row">{{ __('Rating') }}</th>
                    <td>{{ $review->rating }}</td>
                </tr>

                <tr>
                    <th scope="row">{{ __('Advertisement') }}</th>
                    <td>@if(!empty($review->advertisement->title)){{ $review->advertisement->title}} @else N\A @endif</td>
                </tr>

                <tr>
                    <th scope="row">{{ __('Review') }}</th>
                    <td>{{ $review->review}}</td>
                </tr>            

               <!--  <tr>
                    <th scope="row">{{ __('reply') }}</th>
                    <td>{{ $review->reply  }}</td>
                </tr> -->
                <tr>
                    <th scope="row">{{ __('Admin Approve') }}</th>
                    <td>
                         @if($review->status == 3)
                            <a href="javascript:void(0);" style="width: 20%;display: inline-block;" class=" btn1 btn-block btn-danger btn-xs text-center" data-toggle="tooltip" alt="accepted" title="accepted">
                            <i class="fas fa-times"></i>
                          </a>
                         @else
                             @if($review->admin_approve)
                              <a style="width: 20%;display: inline-block;" class=" btn1 btn-block btn-success btn-xs text-center" data-toggle="tooltip" alt="accepted" title="accepted">
                                <i class="fa fa-fw fa-check"></i>
                              </a>
                            @else
                            <a href="javascript:void(0);" style="width: 20%;display: inline-block;" class="confirmDeleteBtn btn1 btn-block btn-success btn-xs text-center" data-toggle="tooltip" alt="Accepted" title="Accepted" data-url="{{ route('admin.review.approve', [$review->id,  'type'=>'accepted']) }}" data-action="POST" data-message="Are you sure you want to accepted this review?">
                              <i class="fa fa-fw fa-check"></i>
                              </a>
                              <a href="javascript:void(0);" style="width: 20%;display: inline-block;" class="confirmDeleteBtn btn1 btn-block btn-danger btn-xs text-center" data-toggle="tooltip" alt="Declined" title="Declined" data-url="{{ route('admin.review.approve', [$review->id, 'type'=>'declined']) }}" data-action="POST" data-message="Are you sure you want to declined this review?">
                              <i class="fas fa-times"></i>
                              </a>
                            @endif
                        @endif
                    </td>
                </tr>
                
                <tr>
                    <th scope="row"><?= __('Created') ?></th>
                    <td>{{ $review->created_at->format(config('get.ADMIN_DATE_TIME_FORMAT')) }}</td>
                </tr>
                <tr>
                    <th scope="row">{{ __('Modified') }}</th>
                    <td>{{ $review->updated_at->format(config('get.ADMIN_DATE_TIME_FORMAT')) }}</td>
                </tr>
                
            </table>
          </div>
   
        </div>
        <!-- /.card -->
      </div>
      <!-- /.col -->
    </div>
    <!-- /.content -->
  </x-slot>
</x-layouts.master>
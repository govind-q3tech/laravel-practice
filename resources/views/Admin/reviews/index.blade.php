<style>
    .admin_pagination svg{
      width: 20px;
    }
    
    .admin_pagination .flex-1 
    {
      display: none;
    }
    
    </style>
    <x-layouts.master>
    @section('title', 'Reviews')
      <!-- Content Header (user_review header) -->
      <x-slot name="breadcrumb">
        <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Reviews</h1>
          </div>
          <div class="col-sm-6">
            {{-- <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Simple Tables</li>
              </ol> --}}
            {{ Breadcrumbs::render('common',['append' => [['label'=> $getController, 'route'=> \Request::route()->getName()]]]) }}
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
    
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                    <tr>
                      <th>#</th>
                      <th scope="col">@sortablelink('name', 'User Name', ['filter' => 'active, visible'], ['rel' => 'nofollow'])</th>
                      <th scope="col">@sortablelink('review', 'Review', ['filter' => 'active, visible'], ['rel' => 'nofollow'])</th>
                      <th>@sortablelink('rating', 'Rating')</th>
                      <th>@sortablelink('Admin approve', 'Admin approve')</th>
                      <th scope="col">@sortablelink('created_at', 'Created', ['filter' => 'active, visible'], ['rel' => 'nofollow'])</th>
                      <th scope="col" class="actions">Action</th>
                    </tr>
                    </tr>
                  </thead>
                  @if($user_reviews->count() > 0)
                  <tbody>
                    @php
                    $i = (($user_reviews->currentPage() - 1) * ($user_reviews->perPage()) + 1);

                    @endphp
                    @foreach($user_reviews as $user_review)
                    <tr class="row-{{ $user_review->id }}">
                      <td> {{$i}}. </td>
                      <td>{{ $user_review->user->first_name }}</td>
                      <td>
                        {{ \Illuminate\Support\Str::limit($user_review->review, 50, $end = '...') }}
                      </td>
                      <td>{{ $user_review->rating }}</td>
                      
                      <td>
                        @if($user_review->status == 3)
                            <a style="width: 20%;display: inline-block;" class=" btn1 btn-block btn-danger btn-xs text-center" data-toggle="tooltip" alt="accepted" title="accepted">
                            <i class="fas fa-times"></i>
                          </a>
                         @else
                          @if($user_review->admin_approve)
                            <a style="width: 25%;display: inline-block;" class=" btn1 btn-block btn-success btn-xs text-center" data-toggle="tooltip" alt="accepted" title="accepted">
                              <i class="fa fa-fw fa-check"></i>
                            </a>
                          @else
                          <a href="javascript:void(0);" style="width: 25%;display: inline-block;" class="confirmDeleteBtn btn1 btn-block btn-success btn-xs text-center" data-toggle="tooltip" alt="Accepted" title="Accepted" data-url="{{ route('admin.review.approve', [$user_review->id, 'type'=>'accepted']) }}" data-action="POST" data-message="Are you sure you want to accept this review?">
                            <i class="fa fa-fw fa-check"></i>
                            </a>
                            <a href="javascript:void(0);" style="width: 25%;display: inline-block;" class="confirmDeleteBtn btn1 btn-block btn-danger btn-xs text-center" data-toggle="tooltip" alt="Declined" title="Declined" data-url="{{ route('admin.review.approve',  [$user_review->id, 'type'=>'declined']) }}" data-action="POST" data-message="Are you sure you want to decline this review?">
                            <i class="fas fa-times"></i>
                            </a>
                          @endif
                        @endif
                      </td>
                      <td>{{ $user_review->created_at->format(config('get.FRONT_DATE_FORMAT')) }}</td>
                      <td class="actions action-btn-tab">
    
                         <a href="{{ route('admin.reviews.show',['review' => $user_review->id]) }}" class="btn btn-warning btn-sm" data-toggle="tooltip" alt="View setting" title="View {{$user_review->user->first_name}}" data-original-title="View"><i class="fa fa-fw fa-eye"></i></a>
    
                        &nbsp;&nbsp;
                       {{-- <a href="{{ route('admin.reviews.edit',['review' => $user_review->id]) }}" class="btn btn-primary btn-sm" data-toggle="tooltip" alt="Edit" title="" data-original-title="Edit"><i class="fa fa-edit"></i></a>  --}}
                      </td>
                    </tr>
                    @php
                    $i++;
                    @endphp
                    @endforeach
                  </tbody>
                  @else
                  <tfoot>
                    <tr>
                      <td colspan='7' align='center'> <strong>Record Not Available</strong> </td>
                    </tr>
                  </tfoot>
                  @endif
                </table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer admin_pagination clearfix">
              {{ $user_reviews->appends(Request::query())->links() }}
              </div>
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
      </x-slot>
    </x-layouts.master>
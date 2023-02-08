@php
$selectedRoutes = Route::currentRouteName();
$image_path = !empty(Auth::user()->profile_picture) ? asset('storage/profile_pictures/' . Auth::user()->profile_picture) : asset('img/no-image.png');
@endphp

<!--Left Pannel Menu Start -->
<div class="left-panel-menu pr-2 ">
    <div class="dashboard-user">
        <div class="text-center">
            <img src="{{ \App\Helpers\Helper::imageUrlTimThumb($image_path, '88', '88', 100) }}" alt=""
                class="rounded-circle">
        </div>
        <div>
            <p>{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</p>
        </div>
        <div class="d-user-bar justify-content-around">
            <span>
                <a href="{{ route('frontend.profile.index') }}"><i class="fas fa-cog"></i></a>
            </span>
            <span>
                @php
                    $notifications = App\Http\Controllers\EventNotificationController::index_tip(Auth::user()->id);
                @endphp
                <a class="nav-link dropdown-toggle notification-ui_icon" href="#" id="navbarDropdown" role="button"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="far fa-bell"></i>@if (count($notifications) > 0) <span class="unread-notification"></span>@endif
                </a>
                <div class="dropdown-menu notification-ui_dd" aria-labelledby="navbarDropdown">
                    <div class="notification-ui_dd-header">
                        <h3 class="text-center">Notification</h3>
                    </div>
                    <div class="notification-ui_dd-content">
                        @if (count($notifications) > 0)
                            
                            @foreach ($notifications as $key => $row)
                            <div class="notification-list notification-list--unread">
                                @php
                                $sender_image = !empty($row->getSenderInfo->profile_picture) ? asset('storage/profile_pictures/' . $row->getSenderInfo->profile_picture) : asset('img/no-image.png');
                                $notification_status = config('constants.NOTIFICATION_LIST');
                                @endphp
                                
                                    <div class="notification-list_img"> <img
                                            src="{{ \App\Helpers\Helper::imageUrlTimThumb($sender_image, '88', '88', 100) }}"
                                            alt="" class="rounded-circle"> </div>
                                    <div class="notification-list_detail">
                                        <p><b>{{ $row->sender_type=='U' ? $row->getSenderInfo->first_name : 'Admin' }}</b> {!! $notification_status[$row->events]['subject'] !!}</p>
                                        <p><small>{{ $row->created_at->diffForHumans() }}</small></p>
                                    </div>

                                    <div class="notification-list_feature-img">

                                         @if ($row->getAdsInfo && $row->getAdsInfo->advertisement_images->count() && !empty($row->advertisement_id))
                                                @if (file_exists(storage_path() . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'advertisements' . DIRECTORY_SEPARATOR . $row->getAdsInfo->advertisement_images->first()->image_name) && $row->getAdsInfo->advertisement_images->first()->image_name != '')
                                                    @php
                                                        $imagePath = asset('storage/advertisements/' . $row->getAdsInfo->advertisement_images->first()->image_name);
                                                        $image_path = \App\Helpers\Helper::imageUrlTimThumb($imagePath, '90', '90', 90);
                                                    @endphp
                                                @else
                                                    @php
                                                        $image_path = asset('img/photo-camera-gray.svg');
                                                    @endphp
                                                @endif
                                        @else
                                                @php
                                                    $image_path = asset('img/photo-camera-gray.svg');
                                                @endphp
                                        @endif
                                        @if (!empty($row->advertisement_id) && !empty($row->getAdsInfo->advertisement_images))
                                                <img src="{{$image_path}}"
                                                    alt="Image">
                                        @endif
                                    </div>

                                </div>
                            @endforeach
                        @else
                            <div class="notification-list">
                                <div class="alert alert-warning col-md-12" role="alert">
                                    new notification not found .
                                </div>
                            </div>
                        @endif
                    </div>
                    @if (count($notifications) > 0)
                    <div class="notification-ui_dd-footer">
                        <a href="{{ route('frontend.notification.index') }}"
                            class="greebtn btn-publish align-items-center btn-block text-center">View All</a>
                    </div>
                    @endif
                </div>
            </span>
            <span>
                <!-- Authentication -->
                <form method="POST" action="{{ route('frontend.logout') }}">
                    @csrf
                    <x-jet-dropdown-link href="{{ route('frontend.logout') }}" title="Logout" class="dropdown-item"
                        onclick="event.preventDefault();
                          this.closest('form').submit();">
                        <span><i class="fas fa-sign-out-alt"></i></span>
                    </x-jet-dropdown-link>
                </form>
                <!-- <a href=""><i class="fas fa-sign-out-alt"></i></a> -->
            </span>
        </div>
    </div>
    <div class="dashboard-nav">
        <ul>
            <li><a href="{{ route('frontend.dashboard') }}" @if ($selectedRoutes == 'frontend.dashboard') class="active" @endif>Dashboard</a></li>
            <li><a href="{{ route('frontend.profile.index') }}" @if ($selectedRoutes == 'frontend.profile.index') class="active" @endif>My Profile</a></li>
            @if (\App\Helpers\Helper::checkBulkuploadStatus(auth()->user()->id, 5) && \App\Helpers\Helper::checkBulkuploadStatus(auth()->user()->id, 5)->value == 1)
                <li><a href="{{ route('frontend.bulkupload.index') }}" @if ($selectedRoutes == 'frontend.bulkupload.index') class="active" @endif>Bulk upload</a></li>
            @endif
            <li><a href="{{ route('frontend.message') }}" @if ($selectedRoutes == 'frontend.message') class="active" @endif>Internal message board</a></li>
            <li><a href="{{ route('frontend.plan.history') }}" @if ($selectedRoutes == 'frontend.plan.history') class="active" @endif>My Plan</a></li>
            <li><a href="{{ route('frontend.my_hashtag.index') }}" @if ($selectedRoutes == 'frontend.my_hashtag.index') class="active" @endif>My HashTags</a></li>
            <li><a href="{{ route('frontend.wishlist.index') }}" @if ($selectedRoutes == 'frontend.wishlist.index') class="active" @endif>My Wishlist</a></li>
            <li><a href="{{ route('frontend.change-password.index') }}" @if ($selectedRoutes == 'frontend.change-password.index') class="active" @endif>Change Password</a>
            </li>
            {{-- <li>
				<form method="POST" action="{{ route('frontend.logout') }}">
               @csrf
               <x-jet-dropdown-link href="{{ route('frontend.logout') }}" 
                    onclick="event.preventDefault();
                       this.closest('form').submit();">
                 Log Out
               </x-jet-dropdown-link>
             </form> 
			</li> --}}
        </ul>
    </div>
</div>
<!--Left Pannel Menu End -->

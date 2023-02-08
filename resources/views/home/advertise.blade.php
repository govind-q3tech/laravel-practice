<x-layouts.front-layout>
    @section('title', 'Subscription')
    <x-slot name="breadcrumb">
        {{ Breadcrumbs::view('partials.breadcrumbs-front', 'front', ['append' => [['label'=> "Subscription"]]]) }}
    </x-slot>
    <x-slot name="content">

        <!--Membership start-->
        <div class="padT50 padB50">
            <div class="container">
                <div class="sec_heading text-center">
                    <h2><span>Add Your Listing</span></h2>
                </div>
                <p class="get-exposure text-center">Get more exposure.</p>
                <div class="row">
                    @foreach($all_plans as $plan)
                    <div class="col-lg-4">
                        <div class="membership-outer">
                            <div class="membership">
                                @if($plan->voucher_text != '')
                                <div class="offer-advertise">{{ $plan->voucher_text }}</div>
                                @endif
                                <div class="membership-top">
                                    <h4>{{ $plan->title }}</h4>
                                    <p>Get found in your area for <br>free.</p>
                                    <div class="price-membership"><span>&pound;</span> {{ $plan->amount }}</div>

                                    <p>{{ ($plan->duration == 1)?"every 1 month ":"every year" }}</p>
                                    <div class="d-block btn-field">
                                       
                                        <a href="{{ route('register', ['plan'=> $plan->id]) }}" class="btn btn-green">Get Started</a>
                                       
                                    </div>
                                </div>
                                <div class="membership-content">
                                    <ul>
                                        @if(!empty($plan->plan_features))
                                            @foreach($plan->plan_features as $features)
                                                @if(!isset($features->feature->title))
                                                    @continue
                                                @endif
                                                @if($features->description != '')
                                                    <li>
                                                        <span>{{ $features->feature->title }}</span>
                                                        {{ $features->description }}
                                                    </li>
                                                @endif
                                            @endforeach
                                        @endif                                        
                                    </ul>
                                    <div class="d-block text-center">
                                       
                                        <a href="{{ route('register', ['plan'=> $plan->id]) }}" class="btn btn-green">Get Started</a>
                                      
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    

                </div>
                <div class="mbr-btm padT50">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mbr-content">
                                <i><img src="{{asset('images/membership-icon1.png')}}" alt=""></i>
                                <h3>Support Best-In-Business</h3>
                                <p>We live by making our clients happy and if you had anything less than a great experience with this theme please contact us now.</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mbr-content">
                                <i><img src="{{asset('images/membership-icon2.png')}}" alt=""></i>
                                <h3>Can I cancel my subscription?</h3>
                                <p>Yes, you can cancel and perform other actions on your subscriptions via the My Account page.</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mbr-content">
                                <i><img src="{{asset('images/membership-icon3.png')}}" alt=""></i>
                                <h3>Which payment methods do you take?</h3>
                                <p>PayPal (for accepting credit card and PayPal account payments).</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mbr-content">
                                <i><img src="{{asset('images/membership-icon4.png')}}" alt=""></i>
                                <h3>Is there any discount for an annual subscription?</h3>
                                <p>Yes, we offer a 40% discount if you choose annual subscription for any plan.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--Membership end-->
    </x-slot>
</x-layouts.front-layout>
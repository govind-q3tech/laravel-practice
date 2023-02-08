<x-layouts.front-layout>
    @section('title', 'Enquries')
    <x-slot name="breadcrumb">
        {{ Breadcrumbs::view('partials.breadcrumbs-front', 'front', ['append' => [['label'=> "contact"]]]) }}
    </x-slot>
    <x-slot name="content">
        <div class="container">
            <div class="white-box ptb70">
              <div class="pl-4 pr-4">
                <div class="row">
                  <div class="col-lg-5">
                    <div class="contact-info-box">
                      <h2>Get In Touch</h2>
                      <ul>
                        <li> <i class="fas fa-map-marker-alt"></i> <span> <strong>Our Location</strong>Nairobi Kenya </span> </li>
                        <li> <i class="fas fa-mobile-alt"></i> <span> <strong>Contact us Anytime</strong><a href="tel:0115609539"> 0115609539</a> </span> </li>
                        <li> <i class="fas fa-envelope"></i> <span> <strong>Email us</strong><a href="mailto:info@kwaki.co.ke">info@kwaki.co.ke</a> </span> </li>
                        <!-- <li> <i class="fas fa-clock"></i> <span> <strong>Hours</strong>
                          <p><strong>Mon - Fri:</strong> 9 AM - 7 PM</p>
                          <p><strong>Sat - Sun:</strong> 11 AM - 2 PM</p>
                          </span> </li> -->
                      </ul>
                    </div>
                  </div>
                  <div class="col-lg-7">
                    <div class="contact-form">
                      <div class="form-heading">
                        <h2>Leave a Message</h2>
                        <p>Please complete the form below and we will get back you as soon as possible.</p>
                      </div>
                      <div class="form-filds-block">
                        {{ Form::open(['route' => ['frontend.contacts.store'], 'id' => 'submit-contacts', 'files'=>'true']) }}
                        <div class="row">
                          <div class="col-md-6">
                            <div class="form-group {{ $errors->has('subject') ? 'has-error' : '' }}">
                                {{ Form::select('subject', [null=>'Select Subject'] +config('constants.CONTACT_SUBJECT'), old('Subject'), ['class' => 'form-control']) }}
                                @if($errors->has('subject'))
                                    <span class="help-block">{{ $errors->first('subject') }}</span>
                                @endif
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                                {{ Form::text('name', old('name'), ['class' => 'form-control','placeholder' => 'Name', 'maxlength' =>'50']) }}
                                @if($errors->has('name'))
                                <span class="help-block">{{ $errors->first('name') }}</span>
                                @endif
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                              {{ Form::text('email', old('email'), ['class' => 'form-control','placeholder' => 'Email', 'maxlength' =>'100']) }}
                              @if($errors->has('email'))
                                <span class="help-block">{{ $errors->first('email') }}</span>
                                @endif
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group {{ $errors->has('phone') ? 'has-error' : '' }}">
                                {{ Form::text('phone', old('phone'), ['class' => 'form-control','placeholder' => '254xxxxxxxxx or 0xxxxxxxxx', 'maxlength' =>'12']) }}
                                @if($errors->has('phone'))
                                    <span class="help-block">{{ $errors->first('phone') }}</span>
                                @endif
                            </div>
                          </div>
                          <div class="col-md-12">
                            <div class="form-group {{ $errors->has('message') ? 'has-error' : '' }}">
                                {{ Form::textarea('message', old('message'), ['class' => 'form-control area-resize','placeholder' => 'Comment/ Request']) }}
                                @if($errors->has('message'))
                                    <span class="help-block">{{ $errors->first('message') }}</span>
                                @endif
                            </div>
                          </div>
                          <div class="col-md-12">
                            <button type="submit" class="greebtn align-items-center w-100"><strong>SUBMIT</strong></button>
                          </div>
                        </div>
                        {{ Form::close() }}
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
    </x-slot>
</x-layouts.front-layout>
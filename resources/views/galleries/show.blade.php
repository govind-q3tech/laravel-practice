<x-layouts.front-layout>
    @section('title', 'galleries')
    <x-slot name="breadcrumb">
        {{ Breadcrumbs::view('partials.breadcrumbs-front', 'front', ['append' => [['label'=> "listings"]]]) }}
    </x-slot>
    <x-slot name="content">

        <!--Dashboard Start-->

        <div class="cms-page innerblock-padd">
            <!--Dashboard start-->
            <div class=" dashboard-section login-content">
                <div class="container">




                    <div class="add-listing upload-gallery bg-white form-sec ">
                        <div class="dash-heading">
                            <h1>Upload Gallery Images </h1>
                            <div class="back"><a href="javascript:void(0)" class="btn  btn-blue"><i class="fas fa-arrow-circle-left"></i> <span>Back to Dashboard</span></a></div>
                        </div>

                        <div class="row align-items-center upload-gallery">
                        {{ Form::open(['route' => ['galleries.store'], 'id' => 'submit-gallery', 'files'=>'true']) }}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="lbl">Listing Images <sup class="mandatory">*</sup></label>

                                    <div class="listing-img">
                                   
                                        {{ Form::hidden('listing_id', $id) }}
                                        {{ Form::file('images[]', ['class' => 'form-control','placeholder' => 'Image', 'name'=>"images[]", "multiple"=>"multiple", 'accept'=>'image/x-png,image/gif,image/jpeg' ]) }}
                                        <p>Please select multiple image with the help of ctrl.</p>
                                        @if($errors->has('images.*'))
                                        <span class="help-block">{{ $errors->first('images.*') }}</span>
                                        @endif
                                    </div>

                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="upload-btn">
                                    <button type="submit" class="btn btn-green mr-2">Upload</button>
                                    <button type="button" class="btn btn-blue">View Listing</button>
                                </div>
                            </div>
                            {{ Form::close() }}
                            <div class="col-md-12">
                                <div class="upload-imgs">
                                    <ul>
                                    @foreach($gallery as $image)
                                        <li>
                                            <div class="delete-img"><a href="javascript:void(0);" class="confirmDeleteBtn" data-toggle="tooltip" alt="Delete Image" title="Delete Image" data-url="{{ route('galleries.destroy', $image->id) }}" data-title="image."><i class="fas fa-times"></i></a></div>

                                            <img src="{{ url('storage/listing/gallery/'.$image->images)}}" alt="">
                                        </li>
                                        @endforeach  

                                    </ul>





                                </div>
                            </div>





                        </div>










                    </div>














                </div>
            </div>
            <!--Dashboard end-->
        </div>

        <!--Dashboard End-->
@push('scripts')
<script src="{{ asset('plugins/jquery-confirm-v3.3.4/js/jquery-confirm.js') }}"></script>
@endpush
@push('styles')
<link href="{{ asset('plugins/jquery-confirm-v3.3.4/css/jquery-confirm.css') }}" rel="stylesheet" type="text/css">
@endpush
    </x-slot>
</x-layouts.front-layout>
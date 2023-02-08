<x-layouts.front-layout>
@section('title'){{($metacmsPageTags->meta_title)??''}}@stop
@section('meta_description'){{isset($cmsPage->meta_description)??''}}@stop

  <!-- Inner header End --> 
    {{--<x-slot name="breadcrumb">
        {{ Breadcrumbs::view('partials.breadcrumbs-front', 'front', ['append' => [['label'=> $cmsPage->title]]]) }}
    </x-slot>--}}
    <x-slot name="content">
        <div class="mt-60"><!-- Inner header Start -->
            <div class="inner-head text-center d-flex align-items-center justify-content-center " >
                <h1>{{ $cmsPage->title }}</h1>
            </div>
            <div class="pb70">
                <div class="container cms-page mt-5">
                    {!! $cmsPage->description !!}
                </div>
            </div>
        </div>
    </x-slot>
</x-layouts.front-layout>
<x-layouts.front-layout>
    @section('title', 'Blog')
    <x-slot name="breadcrumb">
        {{ Breadcrumbs::view('partials.breadcrumbs-front', 'front', ['append' => [['label'=> (!empty($bolgcategory)) ? $bolgcategory->category_name : 'Blogs' ]]]) }}
    </x-slot>
    <x-slot name="content">


        <!--Dashboard Start-->

        <div class="container mb-70">
            <div class="row">
              <div class="col-lg-9">
               <div>@if(!empty($bolgcategory))<h2>Blogs > {{ \Illuminate\Support\Str::limit($bolgcategory->category_name,70) }}</h2> @else <h2>Blogs</h2> @endif</div>
              <div class="post-left"> 
                @if(count($blogs)>0)
                  @foreach($blogs as $key=>$blog)
                  <article>
                      <div class="post-img">
                        @php 
                        $image_path = !empty($blog->images) ? asset('/images/'.$blog->images) : asset('img/no-image.png');
                        @endphp
                        <a href="{{route('frontend.blog.detail',$blog->slug)}}">
                          <img src="{{ \App\Helpers\Helper::imageUrlTimThumb($image_path,'975','700',90) }}" alt="blog">
                        </a>
                      </div>
                      <div class="post-content">
                        <h3><a href="{{route('frontend.blog.detail',$blog->slug)}}">{{++$key}} {{$blog->title}} </a></h3>
                        <div class="post-mid d-flex align-items-center justify-content-between flex-wrap">
                          <div class="meta-info-post">
                            <span class="item-info">
                              By <a>Admin</a>
                            </span>
                            <span class="item-info">
                              <span>{{ $blog->created_at->diffForHumans() }}</span>
                            </span>
                            <span class="item-info">
                              <a href="{{route('frontend.blog',['slug'=>$blog->blog_category->slug])}}">@if(isset($blog->blog_category->category_name)){{$blog->blog_category->category_name}} @else @endif</a>, 
                            </span>
                          </div>
                            <!-- <ul class="ft-social-link d-flex">
                              <li><a href=""><i class="fab fa-facebook-f"></i></a></li>
                              <li><a href=""><i class="fab fa-twitter"></i></a></li>
                              <li><a href=""><i class="fab fa-linkedin-in"></i></a></li>
                              <li><a href=""><i class="fab fa-pinterest-p"></i></a></li>
                            </ul> -->
                        </div>
                        <p> {!! Str::limit($blog->description, 150) !!} .<a href="{{route('frontend.blog.detail',$blog->slug)}}">Read More</a></p>
                      </div>
                    </article>
                @endforeach

                @else
                    <h3><center>No Record found</center></h3>
                @endif
                </div>
               
               
              </div>
              <div class="col-lg-3">
              <div class="blogflt-search d-flex align-items-center justify-content-between mb-3">
               <form name="{{route('frontend.blog')}}" method="get">
                <input type="text" class="form-control" name="search" id="search" placeholder="Search" value="{{request('search')}}">
                <div class="search-icon"><i class="fas fa-search"></i></div>
               </form>
                </div>
              
              <div class="gray-box widget-categories">
              <div class="dtl-head pt-2 pb-2 pl-3 pr-3"><h3>Categories</h3></div>
                <div class="p-3">
                @if($categories->count() > 0)
                <ul>
                    @foreach($categories as $category)
                        <li>
                          <a href="{{route('frontend.blog',$category->slug)}}">{{\Illuminate\Support\Str::limit($category->category_name,25,)}}</a>
                          <span class="count">({{$category->active_blogs_count}})</span>
                        </li>
                     @endforeach
                </ul>
                @endif
            </div>
              </div>
              <div class="gray-box">
              <div class="dtl-head pt-2 pb-2 pl-3 pr-3"><h3>Recent Post</h3></div>
                <div class="p-3 ">
                  @foreach($recentBlogs as $recentblog)
                    <div class="item-post d-flex">
                      @php 
                      $image_path = !empty($recentblog->images) ? asset('/images/'.$recentblog->images) : asset('img/no-image.png');
                      @endphp
                        <a href="{{route('frontend.blog.detail',$recentblog->slug)}}">
                          <img src="{{ \App\Helpers\Helper::imageUrlTimThumb($image_path,'90','90',90) }}" alt="blog">
                        </a>
                        <div class="post-text">
                          <h4><a href="{{route('frontend.blog.detail',$recentblog->slug)}}">{{$recentblog->title}}</a></h4>
                          <p>{{ $recentblog->created_at->diffForHumans() }}</p>
                        </div>
                      </div>
                 @endforeach
             
            </div>
              </div>
              {{-- <div class="gray-box">
              <div class="dtl-head pt-2 pb-2 pl-3 pr-3"><h3>Tags</h3></div>
                <div class="p-3 widget-tag ">
             <div class="tag-block">
                        <a href="">Villas</a>
                        <a href="">Land </a>
                        <a href="">Showroom</a>
                        <a href="">Independent Houses</a>            
                      </div>
            </div>
              </div> --}}
              
              </div>
            </div>
            
          </div>
        </div>

        <!--Dashboard End-->
    </x-slot>

</x-layouts.master>

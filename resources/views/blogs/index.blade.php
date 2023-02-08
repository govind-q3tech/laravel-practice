<x-layouts.front-layout>
@section('title'){{($metacmsPageTags->meta_title)??''}}@stop
@section('meta_description'){{isset($cmsPage->meta_description)??''}}@stop

  <!-- Inner header End --> 
    <x-slot name="breadcrumb">
        {{ Breadcrumbs::view('partials.breadcrumbs-front', 'front', ['append' => [['label'=> 'Blogs']]]) }}
    </x-slot>
    <x-slot name="content">
        <div class="container mb-70">
            <div class="row">
              <div class="col-lg-9">
               <div><h2>Blogs</h2></div>
              <div class="post-left"> <article>
                      <div class="post-img">
                        <a href=""><img src="{{ asset('img/blog-01.jpg') }}" alt="blog"></a>
                      </div>
                      <div class="post-content">
                        <h3><a href="blog-details.html">01 The Mysterious Mansion </a></h3>
                        <div class="post-mid d-flex align-items-center justify-content-between flex-wrap">
                          <div class="meta-info-post">
                            <span class="item-info">
                              By <a href="#">Admin</a>
                            </span>
                            <span class="item-info">
                              <span>September 30, 2021</span>
                            </span>
                            <span class="item-info">
                              <a href="#">Fashion</a>, 
                            </span>
                          </div>
                          <ul class="ft-social-link d-flex">
                            <li><a href=""><i class="fab fa-facebook-f"></i></a></li>
                            <li><a href=""><i class="fab fa-twitter"></i></a></li>
                            <li><a href=""><i class="fab fa-linkedin-in"></i></a></li>
                            <li><a href=""><i class="fab fa-pinterest-p"></i></a></li>
                          </ul>
                        </div>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam quis risus sed lectus vulputate tincidunt. Sed in convallis libero, in vulputate dui. Sed metus risus, ullamcorper eget ante vitae, iaculis congue lectus. Duis lacinia in dui vel tristique ullamcorper eget ante vitae. <a href="blog-details.html">Read More</a></p>
                      </div>
                    </article>
                    <article>
                      <div class="post-img">
                        <a href=""><img src="{{ asset('img/blog-01.jpg') }}" alt="blog"></a>
                      </div>
                      <div class="post-content">
                        <h3><a href="blog-details.html">01 The Mysterious Mansion </a></h3>
                        <div class="post-mid d-flex align-items-center justify-content-between flex-wrap">
                          <div class="meta-info-post">
                            <span class="item-info">
                              By <a href="#">Admin</a>
                            </span>
                            <span class="item-info">
                              <span>September 30, 2021</span>
                            </span>
                            <span class="item-info">
                              <a href="#">Fashion</a>, 
                            </span>
                          </div>
                          <ul class="ft-social-link d-flex">
                            <li><a href=""><i class="fab fa-facebook-f"></i></a></li>
                            <li><a href=""><i class="fab fa-twitter"></i></a></li>
                            <li><a href=""><i class="fab fa-linkedin-in"></i></a></li>
                            <li><a href=""><i class="fab fa-pinterest-p"></i></a></li>
                          </ul>
                        </div>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam quis risus sed lectus vulputate tincidunt. Sed in convallis libero, in vulputate dui. Sed metus risus, ullamcorper eget ante vitae, iaculis congue lectus. Duis lacinia in dui vel tristique ullamcorper eget ante vitae. <a href="blog-details.html">Read More</a></p>
                      </div>
                    </article>
                    <article>
                      <div class="post-img">
                        <a href=""><img src="{{ asset('img/blog-01.jpg') }}" alt="blog"></a>
                      </div>
                      <div class="post-content">
                        <h3><a href="blog-details.html">01 The Mysterious Mansion </a></h3>
                        <div class="post-mid d-flex align-items-center justify-content-between flex-wrap">
                          <div class="meta-info-post">
                            <span class="item-info">
                              By <a href="#">Admin</a>
                            </span>
                            <span class="item-info">
                              <span>September 30, 2021</span>
                            </span>
                            <span class="item-info">
                              <a href="#">Fashion</a>, 
                            </span>
                          </div>
                          <ul class="ft-social-link d-flex">
                            <li><a href=""><i class="fab fa-facebook-f"></i></a></li>
                            <li><a href=""><i class="fab fa-twitter"></i></a></li>
                            <li><a href=""><i class="fab fa-linkedin-in"></i></a></li>
                            <li><a href=""><i class="fab fa-pinterest-p"></i></a></li>
                          </ul>
                        </div>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam quis risus sed lectus vulputate tincidunt. Sed in convallis libero, in vulputate dui. Sed metus risus, ullamcorper eget ante vitae, iaculis congue lectus. Duis lacinia in dui vel tristique ullamcorper eget ante vitae. <a href="blog-details.html">Read More</a></p>
                      </div>
                    </article></div>
               
               
              </div>
              <div class="col-lg-3">
              <div class="blogflt-search d-flex align-items-center justify-content-between mb-3">
                <div><input type="" class="form-control" id="" placeholder="Search"></div>
                <div class="search-icon"><i class="fas fa-search"></i></div>
                
                </div>
              
              <div class="gray-box widget-categories">
              <div class="dtl-head pt-2 pb-2 pl-3 pr-3"><h3>Categories</h3></div>
                <div class="p-3">
                <ul>
                        <li>
                          <a href="#">Health and Beauty Services</a>
                          <span class="count">(15)</span>
                        </li>
                        <li>
                          <a href="#">Vehicles</a>
                          <span class="count">(09)</span>
                        </li>
                        <li>
                          <a href="#">Property  </a>
                          <span class="count">(28)</span>
                        </li>
                        <li>
                          <a href="#">Computing </a>
                          <span class="count">(13)</span>
                        </li>
                        <li>
                          <a href="#">Electronics </a>
                          <span class="count">(30)</span>
                        </li>
                    
                      </ul>
            </div>
              </div>
              <div class="gray-box">
              <div class="dtl-head pt-2 pb-2 pl-3 pr-3"><h3>Recent Post</h3></div>
                <div class="p-3 ">
              <div class="item-post d-flex">
                        <a href="" class="pic-post"><img src="{{ asset('img/blog-01.jpg') }}" alt="post img"></a>
                        <div class="post-text">
                          <h4><a href="">Kayak Point House</a></h4>
                          <p>September 30, 2021</p>
                        </div>
                      </div>
                      <div class="item-post d-flex">
                        <a href="" class="pic-post"><img src="{{ asset('img/blog-01.jpg') }}" alt="post img"></a>
                        <div class="post-text">
                          <h4><a href="">Kayak Point House</a></h4>
                          <p>September 30, 2021</p>
                        </div>
                      </div>
                      <div class="item-post d-flex">
                        <a href="" class="pic-post"><img src="{{ asset('img/blog-01.jpg') }}" alt="post img"></a>
                        <div class="post-text">
                          <h4><a href="">Kayak Point House</a></h4>
                          <p>September 30, 2021</p>
                        </div>
                      </div>
                      <div class="item-post d-flex">
                        <a href="" class="pic-post"><img src="{{ asset('img/blog-01.jpg') }}" alt="post img"></a>
                        <div class="post-text">
                          <h4><a href="">Kayak Point House</a></h4>
                          <p>September 30, 2021</p>
                        </div>
                      </div>
             
            </div>
              </div>
              <div class="gray-box">
              <div class="dtl-head pt-2 pb-2 pl-3 pr-3"><h3>Tags</h3></div>
                <div class="p-3 widget-tag ">
             <div class="tag-block">
                        <a href="">Villas</a>
                        <a href="">Land </a>
                        <a href="">Showroom</a>
                        <a href="">Independent Houses</a>            
                      </div>
            </div>
              </div>
              
              </div>
            </div>
            
          </div>
    </x-slot>
</x-layouts.front-layout>
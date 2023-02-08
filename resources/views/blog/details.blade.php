<x-layouts.front-layout>
    @section('title', 'Blog')
    <x-slot name="breadcrumb">
        {{ Breadcrumbs::view('partials.breadcrumbs-front', 'front', ['append' => [['label'=> "Blog Details"]]]) }}
    </x-slot>
    <x-slot name="content">
<style>


.button-group {
  margin-bottom: 20px;
}
.counter {
  display: inline;
  margin-top: 0;
  margin-bottom: 0;
  margin-right: 10px;
}
.posts {
  clear: both;
  list-style: none;
  padding-left: 0;
  width: 100%;
  text-align: left;
}
.posts li {
  background-color: #fff;
  border: 1.5px solid #d8d8d8;
  border-radius: 10px;
  padding-top: 10px;
  padding-left: 20px;
  padding-right: 20px;
  padding-bottom: 10px;
  margin-bottom: 10px;
  word-wrap: break-word;
  min-height: 42px;
  box-shadow:8px 8px 5px #888888;
}
.btn-primary {
    color: #fff;
    background-color: #337ab7;
    border-color: #2e6da4;
}


@import url(//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css);

.detailBox {
    /* width:320px; */
    border:1px solid #bbb;
    margin:50px;
}
.titleBox {
    background-color:#fdfdfd;
    padding:10px;
}
.titleBox label{
  color:#444;
  margin:0;
  display:inline-block;
}

.commentBox {
    padding:10px;
    border-top:1px dotted #bbb;
}
.commentBox .form-group:first-child, .actionBox .form-group:first-child {
    width:80%;
}
.commentBox .form-group:nth-child(2), .actionBox .form-group:nth-child(2) {
    width:18%;
}
.actionBox .form-group * {
    width:100%;
}
.taskDescription {
    margin-top:10px 0;
}
.commentList {
    padding:0;
    list-style:none;
    max-height:200px;
    overflow:auto;
}
.commentList li {
    margin:0;
    margin-top:10px;
}
.commentList li > div {
    display:table-cell;
}
.commenterImage {
    width:30px;
    margin-right:5px;
    height:100%;
    float:left;
}
.commenterImage img {
    width:100%;
    border-radius:50%;
}
.commentText p {
    margin:0;
}
.sub-text {
    color:#aaa;
    font-family:verdana;
    font-size:11px;
}
.actionBox {
    border-top:1px dotted #bbb;
    padding:10px;
}


  </style>

        <!--Dashboard Start-->

        <div class="container mb-70">
          <article><div><h2>01 {{$blogdetails->title}}</h2></div>
           <div class="post-img">
          <img src="@if(isset($blogdetails->images)){{asset('/images/'.$blogdetails->images)}} @else {{asset('img/blog-01.jpg')}} @endif" alt="blog">                   </div>
                    
                    <div class="post-content">                      
                      <div class="post-mid d-flex align-items-center justify-content-between flex-wrap mb-3">
                        <div class="meta-info-post">
                          <span class="item-info">
                            By <a>Admin</a>
                          </span>
                          <span class="item-info">
                            <span>{{ $blogdetails->created_at->format(config('get.FRONT_DATE_FORMAT')) }}</span>
                          </span>
                         
                          <span class="item-info">
                            <a href="{{route('frontend.blog',['slug'=>$blogdetails->blog_category->slug])}} ">{{$blogdetails->blog_category->category_name ?? ""}}</a> 
                          </span>
                        </div>
                        <!-- <ul class="ft-social-link post-link d-flex">
                          <li><a href=""><i class="fab fa-facebook-f"></i></a></li>
                          <li><a href=""><i class="fab fa-twitter"></i></a></li>
                          <li><a href=""><i class="fab fa-linkedin-in"></i></a></li>
                          <li><a href=""><i class="fab fa-pinterest-p"></i></a></li>
                        </ul> -->
                      </div>
                      <p>{!! $blogdetails->description !!}</p>
                    </div></article>

                  
                    
          </div>
        </div>
        <!--Dashboard End-->
    </x-slot>
<script>
var main = function() {
  $('.btn').click(function() {
    var post = $('.status-box').val();
    $('<li>').text(post).prependTo('.posts');
    $('.status-box').val('');
    $('.counter').text('250');
    $('.btn').addClass('disabled');
  });
  $('.status-box').keyup(function() {
    var postLength = $(this).val().length;
    var charactersLeft = 250 - postLength;
    $('.counter').text(charactersLeft);
    if (charactersLeft < 0) {
      $('.btn').addClass('disabled');
    } else if (charactersLeft === 250) {
      $('.btn').addClass('disabled');
    } else {
      $('.btn').removeClass('disabled');
    }
  });
}
$('.btn').addClass('disabled');
$(document).ready(main)
  </script>


</x-layouts.master>

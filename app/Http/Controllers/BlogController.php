<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\Comment;
use Response;
use Redirect;
use Gate;

class BlogController extends Controller
{
    public function index(Request $request, $slug = null)
    {
        $data = $request->search;
        if (isset($slug)) {
            $bolgcategory = Blogcategory::where('slug', $slug)->where('status', 1)->first();
            $blogs = Blog::whereHas('blog_category', function ($q) use ($slug) {
                $q->where('slug', $slug)->where('status',1);
            })->with('blog_category')->status()->where('status',1)->get();
        } else {
            $bolgcategory = [];
            $blogs = Blog::whereHas('blog_category', function ($q){
                $q->where('status', 1);
            })->with('blog_category')->status()->where('status', 1)->get();
        }
        if ($request && $request->search)
        {
            $bolgcategory = [];
            $blogs = Blog::whereHas('blog_category', function ($q) {
                $q->where('status', 1);
            })->where('title','like','%'.$request->search.'%')->with('blog_category')->status()->where('status', 1)->get();
        }
        $recentBlogs = Blog::whereHas('blog_category', function ($q) {
            $q->where('status', 1);
        })->sortable(['created_at' => 'DESC'])->status()->take(4)->where('status', 1)->get();
        $categories = BlogCategory::withCount('ActiveBlogs')->where('status', 1)->get();
        return view('blog.index', compact('blogs', 'categories', 'bolgcategory', 'recentBlogs'));
    }

    public function blogDetails($slug = null)
    {

        // $blogdetails = Blog::whereHas('comment')->where('slug', $slug)->with('comment.children')->first();  
        $blogdetails = Blog::where('slug', $slug)->with('comment.children')->first();
        if ($blogdetails) {
            $comments = Comment::where(['parent_id' => '0', 'blog_id' => $blogdetails->id])->with('children')->get();
            if (isset(auth()->user()->id)) {
                $finduser = Comment::where(['user_id' => auth()->user()->id, 'blog_id' => $blogdetails->id])->first();
            } else {
                $finduser = Comment::where('blog_id', $blogdetails->id)->first();
            }
            return view('blog.details', compact('blogdetails', 'comments', 'finduser'));
        } else {
            return redirect()->route('frontend.blog');
        }






        // 
        //     $comments=Comment::where(['blog_id'=>$blogdetails->id, 'parent_id'=>'0'])->get();  
        //     if(isset(auth()->user()->id)){
        //         $finduser=Comment::where(['user_id'=>auth()->user()->id, 'blog_id'=>$blogdetails->id])->first();
        //     }


        //  foreach($comments as $key=>$vals){
        //      $admincomm=Comment::where(['parent_id'=>$vals->blog_id, 'user_id'=>$vals->user_id])->first();
        //      $comments[$key]->adminreply=$admincomm;
        //  }



        //     return view('blog.details',compact('blogdetails','comments','finduser'));
        // }else{

        // }        
    }

    public function comment(Request $request)
    {

        $savecomment = new Comment;
        $savecomment->user_id = auth()->user()->id;
        $savecomment->category_id = $request->category_id;
        $savecomment->blog_id = $request->blog_id;
        $savecomment->message = $request->comment;
        $savecomment->parent_id = 0;
        $savecomment->status = 1;
        $savecomment->save();
        return Redirect::back();
    }
}

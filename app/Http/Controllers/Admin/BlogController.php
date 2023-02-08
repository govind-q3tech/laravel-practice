<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\BlogRequest;
use Illuminate\Support\Str;
use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Support\Facades\Validator;

use Gate, DB;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $response = Gate::inspect('check-user', "blog-index");
        if (!$response->allowed()) {
            return redirect()->route('admin.dashboard', app('request')->query())->with('error', $response->message());
        }
        $blogs = Blog::sortable(['created_at' => 'desc'])->paginate(config('get.ADMIN_PAGE_LIMIT'));
        return view('Admin.blog.index', compact('blogs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $response = Gate::inspect('check-user', "blog-create");
        if (!$response->allowed()) {
            return redirect()->route('admin.dashboard', app('request')->query())->with('error', $response->message());
        }
            $blogcategory=BlogCategory::pluck('title','id');
        return view('Admin.blog.createOrUpdate',compact('blogcategory'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BlogRequest $request)
    {
        // dd($request->all());
        $response = Gate::inspect('check-user', "blog-create");
        if (!$response->allowed()) {
            return redirect()->route('admin.dashboard', app('request')->query())->with('error', $response->message());
        }
        try {
            $requestData = $request->all();
            $requestData['status'] = (isset($requestData['status'])) ? 1 : 0;
            $requestData['title'] = $requestData['title'];
            $requestData['description'] = $requestData['description'];
            $requestData['blog_categories_id'] = $requestData['blog_categories_id'];
            $requestData['meta_keyword'] = $requestData['meta_keyword'];
            $requestData['sub_title'] = $requestData['sub_title'];
            $requestData['meta_title'] = $requestData['meta_title'];
            // if(!$request->images){
            //     $request->validate(['images'=>'required']);
            // }
            if($requestData['images']){
                // dd($request->all());
                $filename = time().'.'.request()->images->getClientOriginalExtension();
                request()->images->move(public_path('/images'), $filename);
                $requestData['images']=$filename;                
            }  
            Blog::create($requestData);
        } catch (\Illuminate\Database\QueryException $e) {
            return back()->withError($e->getMessage())->withInput();
        }
        return redirect()->route('admin.blog.index')->with('success', 'Blog has been saved successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Blog $blog)
    {
        $response = Gate::inspect('check-user', "blog-index");
        if (!$response->allowed()) {
            return redirect()->route('admin.dashboard', app('request')->query())->with('error', $response->message());
        }
        return view('Admin.blog.show', compact('blog'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    { 
        $blogcategory = BlogCategory::pluck('title','id');
        $editbolg = Blog::find($id);
        return view('Admin.blog.createOrUpdate',compact('editbolg','blogcategory'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BlogRequest $request, $id)
    {
     
        $response = Gate::inspect('check-user', "blog-create");
        if (!$response->allowed()) {
            return redirect()->route('admin.dashboard', app('request')->query())->with('error', $response->message());
        }
        try {
            $blog = Blog::findOrFail($id);
            $requestData = $request->all();
            // $requestData['slug'] = Str::slug($request->title, '-');
            $requestData['status'] = (isset($requestData['status'])) ? 1 : 0;
            $requestData['title'] = $requestData['title'];
            $requestData['description'] = $requestData['description'];
            $requestData['blog_categories_id'] = $requestData['blog_categories_id'];
            $requestData['meta_keyword'] = $requestData['meta_keyword'];
            $requestData['sub_title'] = $requestData['sub_title'];
            $requestData['meta_title'] = $requestData['meta_title'];

            if(isset($requestData['images']) && $requestData['images']){
                // dd($request->all());
                $filename = time().'.'.request()->images->getClientOriginalExtension();
                request()->images->move(public_path('/images'), $filename);
                $requestData['images']=$filename;                
            }   
            $blog->fill($requestData);
            $blog->save();
        } catch (\Illuminate\Database\QueryException $e) {
            return back()->withError($e->getMessage())->withInput();
        }
        return redirect()->route('admin.blog.index')->with('success', 'blog has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
            try {
                $blog_category = Blog::findOrFail($id)->delete();
                DB::commit();
                $responce = ['status' => true, 'message' => 'This blog has been deleted successfully.', 'data' => $blog_category];
            } catch (\Exception $e) {
                DB::rollBack();
                $responce = ['status' => false, 'message' => $e->getMessage()];
            }
            return $responce;        
    }
}

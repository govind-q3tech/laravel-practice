<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BlogCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class BlogCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request  )
    {
        $blogcategory=BlogCategory::sortable(['created_at' => 'desc'])->paginate(config('get.ADMIN_PAGE_LIMIT'));
        return view('Admin.blogcategory.index',compact('blogcategory'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('Admin.blogcategory.createOrUpdate');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(['title'=>'required|max:100']);
        $saveBlogCategory= new BlogCategory;
        $saveBlogCategory->title = $request->title;
        $saveBlogCategory->status=(isset($request->status) ? 1 : 0);
        $saveBlogCategory->save();
        return redirect()->route('admin.blogcategory.index')->with('success', 'Blog category has been saved successfully');
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $sahowblogcategory=BlogCategory::find($id);
        return view('Admin.blogcategory.show',compact('sahowblogcategory'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {   $blogcat=BlogCategory::find($id);
        return view('Admin.blogcategory.createOrUpdate',compact('blogcat'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $response = Gate::inspect('check-user', "cities-create");
        if (!$response->allowed()) {
            return redirect()->route('admin.dashboard', app('request')->query())->with('error', $response->message());
        }
        try {
            $BlogCategory = BlogCategory::findOrFail($id);
            // $BlogCategory = $request->all();
            $BlogCategory->status = (isset($request->status) ? 1 : 0);
            $BlogCategory->title = $request->title;
            // $BlogCategory->fill($BlogCategory);
            $BlogCategory->save();
        } catch (\Illuminate\Database\QueryException $e) {
            return back()->withError($e->getMessage())->withInput();
        }
        return redirect()->route('admin.blogcategory.index')->with('success', 'Blog category has been updated successfully.');
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
                $blog_category = BlogCategory::findOrFail($id)->delete();
                DB::commit();
                $responce = ['status' => true, 'message' => 'This blog category has been deleted successfully.', 'data' => $blog_category];
            } catch (\Exception $e) {
                DB::rollBack();
                $responce = ['status' => false, 'message' => $e->getMessage()];
            }
            return $responce;

    }
}

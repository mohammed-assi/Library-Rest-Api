<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\category\StoreCategoryRequest;
use App\Http\Requests\category\UpdateCategoryRequest;
use App\Models\books;
use App\Models\categories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminCategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $category = categories::all();
        return success_response($category);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCategoryRequest $request)
    {
        $category = categories::create($request->except('image'));

        if ($category) {
            if ($request->hasFile('image')) {
                
                $fileName = time().'.'.$request->file('image')->getClientOriginalExtension();
                $path = $request->file('image')->storeAs('category', $fileName, 'public');

                $category->image = $path;
                $category->save();
            }
        }
        return success_response($category);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = categories::findOrFail($id);
        return success_response($category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCategoryRequest $request, $id)
    {
        $category = categories::findOrFail($id);
        $category->update($request->except('image'));
        
        if ($request->hasFile('image')) {
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            $fileName = time().'.'.$request->file('image')->getClientOriginalExtension();
            $path = $request->file('image')->storeAs('books/image', $fileName, 'public');
            $category->image = $path;
            $category->save();
        }
        return success_response(categories::findOrFail($id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $book = books::where('category_id',$id)->first();
        if($book){

            return response()->json(['status' => 0, 'message' => 'هذا القسم يحوي كتب....لذلك لايمكن حذفه'], 403);

            // return error_response('
            // هذا القسم يحوي كتب....لذلك لايمكن حذفه
            // ');
        }
        else{
        categories::findOrFail($id)->delete();
        return success_response();
        }
    }
}

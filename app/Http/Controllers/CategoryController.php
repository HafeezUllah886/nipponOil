<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Image;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::orderByDesc('categoryID')->get();
        return view('category.index', compact('categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('category.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $check = Category::where('name', $request->name)->count();
        if($check > 0)
        {
            return back()->with('error', "Already Exists");
        }
        $image_path1 = null;
        if($request->hasFile('image'))
        {
            $image = $request->file('image');
            $filename = $request->name.".".$image->getClientOriginalExtension();
            $image_path = public_path('/images/categories/'.$filename);
            $image_path1 = '/images/categories/'.$filename;
            $img = Image::make($image);
            $img->save($image_path,70);
        }
        Category::create([
            'name' => $request['name'] ,
            'image' => $image_path1,
            'parentID' =>  $request['parentID'],
            'isActive' => $request['isActive'],
            'createdBy' => auth()->user()->email,
        ]);
        if ($request->has('category')){
            $request->session()->flash('message', 'Category created Successfully!');
            return to_route('product.create');
        }
        $request->session()->flash('message', 'Category created Successfully!');
        return to_route('category.index');
    }
    public function show(Category $category)
    {
        return view('category.show', compact('category'));
    }

    public function edit(Category $category)
    {
        $categories = Category::all();
        return view('category.edit', compact('category', 'categories'));
    }

    public function update(Request $request, Category $category)
    {
        $check = Category::where('name', $request->name)->where('categoryID', '!=', $category->categoryID)->count();
        if($check > 0)
        {
            return back()->with('error', "Already Exists");
        }
        $input = $request->all();
        if ($request->hasFile('image')) {
            @unlink(public_path($category->image));
            $image = $request->file('image');
            $filename = $request->name.".".$image->getClientOriginalExtension();
            $image_path = public_path('/images/categories/'.$filename);
            $image_path1 = '/images/categories/'.$filename;
            $img = Image::make($image);
            $img->save($image_path,70);
            $input['image'] = $image_path1;
        }
        $category->update($input);
        $request->session()->flash('message', 'Category Updated Successfully!');
        return to_route('category.index');
    }

    public function destroy(Category $category, Request $request)
    {

        $cat = Category::where('parentID', $category->categoryID)->count();
        $pro = Product::where('categoryID', $category->categoryID)->count();

        if($cat > 0){
            return back()->with('error', "Category Can't be deleted as it has some child categories");
        }
        elseif($pro > 0)
        {
            return back()->with('error', "Category Can't be deleted as it has some products");
        }
        else {
            $category->delete();
            $request->session()->flash('message', 'Category Deleted Successfully!');
            return to_route('category.index');
        }
    }
}

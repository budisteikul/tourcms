<?php

namespace budisteikul\tourcms\Controllers;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use budisteikul\tourcms\DataTables\FinCategoriesDataTable;
use Illuminate\Support\Facades\Validator;
use budisteikul\tourcms\Models\fin_categories;
use budisteikul\tourcms\Models\fin_transactions;
use budisteikul\tourcms\Helpers\AccHelper;

class FinCategoryController extends Controller
{
    public function structure()
    {
        $root_categories = fin_categories::where('parent_id',0)->orderBy('name')->get();
        return view('tourcms::fin.categories.show',['root_categories'=>$root_categories]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(FinCategoriesDataTable $dataTable)
    {
        return $dataTable->render('tourcms::fin.categories.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = AccHelper::getCategories();
        return view('tourcms::fin.categories.create',['categories'=>$categories]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
          	'name' => 'required|string|max:255',
       	]);
        
       	if ($validator->fails()) {
            $errors = $validator->errors();
			return response()->json($errors);
       	}
		
		$name =  $request->input('name');
		$type =  $request->input('type');
		$parent_id =  $request->input('parent_id');

		$fin_categories = new fin_categories();
		$fin_categories->name = $name;
        $fin_categories->parent_id = $parent_id;
		if($parent_id>0) {
            $type = fin_categories::select(['type'])->where('id', $parent_id)->first();
            $fin_categories->type = $type->type;
        } else {
            $fin_categories->type = $type;
        }
		$fin_categories->save();
		
		return response()->json([
					"id" => "1",
					"message" => 'Success'
				]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(fin_categories $category)
    {
        $categories = AccHelper::getCategories(true,$category->id);
        return view('tourcms::fin.categories.edit',['category'=>$category,'categories'=>$categories]);
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
        $validator = Validator::make($request->all(), [
          	'name' => 'required|string|max:255',
       	]);
        
       	if ($validator->fails()) {
            $errors = $validator->errors();
			return response()->json($errors);
       	}
		
		$name =  $request->input('name');
		$type =  $request->input('type');
        $parent_id =  $request->input('parent_id');
		
		$fin_categories = fin_categories::findOrFail($id);
		$fin_categories->name = $name;
        $fin_categories->parent_id = $parent_id;
        

        if($parent_id>0) {
            $type = fin_categories::select(['type'])->where('id', $parent_id)->first();
            $fin_categories->type = $type->type;
        } else {
            $fin_categories->type = $type;
        }
		
        

		$fin_categories->save();

        $categories = AccHelper::getChild($fin_categories->id);
        foreach($categories as $category)
        {
            fin_categories::where('id',$category)->update(['type' => $fin_categories->type]);
        }
		
		return response()->json([
					"id" => "1",
					"message" => 'Success'
				]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(fin_categories $category)
    {
        fin_categories::where('parent_id',$category->id)->update(['parent_id'=>0]);
        fin_transactions::where('category_id', $category->id)->delete();
        $category->delete();
		
    }
}

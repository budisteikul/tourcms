<?php

namespace budisteikul\tourcms\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use budisteikul\toursdk\Models\Marketplace;
use budisteikul\toursdk\Models\Product;
use budisteikul\tourcms\DataTables\MarketplacesDataTable;

class MarketplaceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(MarketplacesDataTable $dataTable)
    {
        return $dataTable->render('tourcms::marketplaces.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $products = Product::orderBy('name')->get();
        return view('tourcms::marketplaces.create',['products'=>$products]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreMarketplaceRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'link' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors);
        }

        $name =  $request->input('name');
        $link =  $request->input('link');
        $product_id =  $request->input('product_id');

        $marketplace = new Marketplace();
        $marketplace->name = $name;
        $marketplace->link = $link;
        $marketplace->product_id = $product_id;
        $marketplace->save();

        return response()->json([
                    "id" => "1",
                    "message" => 'Success'
                ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Marketplace  $marketplace
     * @return \Illuminate\Http\Response
     */
    public function show(Marketplace $marketplace)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Marketplace  $marketplace
     * @return \Illuminate\Http\Response
     */
    public function edit(Marketplace $marketplace)
    {
        $products = Product::orderBy('name')->get();
        return view('tourcms::marketplaces.edit',['marketplace'=>$marketplace,'products'=>$products]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateMarketplaceRequest  $request
     * @param  \App\Models\Marketplace  $marketplace
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Marketplace $marketplace)
    {
        $validator = Validator::make($request->all(), [
            'link' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors);
        }

        $name =  $request->input('name');
        $link =  $request->input('link');
        $product_id =  $request->input('product_id');

        $marketplace->name = $name;
        $marketplace->link = $link;
        $marketplace->product_id = $product_id;
        $marketplace->save();

        return response()->json([
                    "id" => "1",
                    "message" => 'Success'
                ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Marketplace  $marketplace
     * @return \Illuminate\Http\Response
     */
    public function destroy(Marketplace $marketplace)
    {
        $marketplace->delete();
    }
}

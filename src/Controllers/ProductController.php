<?php

namespace budisteikul\tourcms\Controllers;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;

use budisteikul\tourcms\DataTables\ProductDataTable;
use budisteikul\toursdk\Models\Product;
use budisteikul\toursdk\Models\Image;
use budisteikul\toursdk\Models\Category;
use budisteikul\toursdk\Models\Slug;
use budisteikul\coresdk\Models\FileTemp;
use budisteikul\toursdk\Helpers\CategoryHelper;
use budisteikul\toursdk\Helpers\ImageHelper;
use budisteikul\toursdk\Helpers\BokunHelper;


class ProductController extends Controller
{
    public static function product_api($path,$data)
    {
            $endpoint = env("APP_API_URL");
            $headers = [
                'content-type' => 'application/json',
            ];

            $client = new \GuzzleHttp\Client(['headers' => $headers,'http_errors' => false]);

            
                $response = $client->request('POST',$endpoint.$path,
                [   
                    'json' => $data
                ]);

            $contents = $response->getBody()->getContents();
            return $contents;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ProductDataTable $dataTable)
    {
        return $dataTable->render('tourcms::product.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('tourcms::product.create',[
            'categories'=>$categories,
            'file_key'=>Uuid::uuid4()->toString()
        ]);
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
            'name' => 'required|string|max:255|unique:products,name',
            'bokun_id' => 'required|numeric|unique:products,bokun_id',
        ]);
        
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors);
        }
		
		$name =  $request->input('name');
		$category_id =  $request->input('category_id');
        $bokun_id =  $request->input('bokun_id');
        $deposit_percentage =  $request->input('deposit_percentage');
        $deposit_percentage = $deposit_percentage === 'true'? true: false;
        $deposit_amount =  $request->input('deposit_amount');
		if($deposit_amount=="") $deposit_amount = 0;
        $min_participant =  $request->input('min_participant');
        if($min_participant=="") $min_participant = 1;


        $value = BokunHelper::get_product($bokun_id);
        if($value=="")
        {
            return response()->json([
                    "bokun_id" => ["Activity not found"]
                ]);
        }
        

		$product = new Product();
        $product->name = $name;
        $product->slug = Str::slug($name,'-');
		$product->bokun_id = $bokun_id;
        $product->deposit_percentage = $deposit_percentage;
        $product->deposit_amount = $deposit_amount;
        $product->min_participant = $min_participant;
        $product->category_id = $category_id;
        $product->save();

        $slug = new Slug();
        $slug->type = "product";
        $slug->link_id = $product->id;
        $slug->slug = Str::slug($name,'-');
        $slug->save();
		
        $key = $request->input('key');
        $filetemps = FileTemp::where('key',$key)->get();
        $sort = 0 ;

        foreach($filetemps as $filetemp)
        {
                $sort++;
                //$response = ImageHelper::uploadImageCloudinary($filetemp->file);
                $response = ImageHelper::uploadImageGoogle($filetemp->file);

                $image = new Image();
                $image->product_id = $product->id;
                $image->public_id = $response['public_id'];
                $image->secure_url = $response['secure_url'];
                $image->sort = $sort;
                $image->save();

                $filetemp->delete();
        }

		return response()->json([
                    "id" => "1",
                    "message" => 'Success'
                ]);
		
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
		return view('tourcms::product.edit',[
				'product'=>$product,
				'categories'=>$categories,
				'file_key'=>Uuid::uuid4()->toString()
			]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        if($request->input('action')=="refresh")
        {
            $value = self::product_api('/product/add',$product->bokun_id);
            return response()->json([
                    "id" => "1",
                    "message" => $value
                ]);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:products,name,'.$product->id,
            'bokun_id' => 'required|numeric|unique:products,bokun_id,'.$product->id,
        ]);
        
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors);
        }
		
		$name =  $request->input('name');
		$category_id =  $request->input('category_id');
        $bokun_id =  $request->input('bokun_id');
		$deposit_percentage =  $request->input('deposit_percentage');
        $deposit_percentage = $deposit_percentage === 'true'? true: false;
        $deposit_amount =  $request->input('deposit_amount');
        if($deposit_amount=="") $deposit_amount = 0;
        $min_participant =  $request->input('min_participant');
        if($min_participant=="") $min_participant = 1;

        $value = BokunHelper::get_product($bokun_id);
        if($value=="")
        {
            return response()->json([
                    "bokun_id" => ["Activity not found"]
                ]);
        }
        


        $product->name = $name;
        $product->slug = Str::slug($name,'-');
		$product->bokun_id = $bokun_id;
        $product->deposit_percentage = $deposit_percentage;
        $product->deposit_amount = $deposit_amount;
		$product->category_id = $category_id;
        $product->min_participant = $min_participant;
        $product->save();

        $slug = new Slug();
        $slug->type = "product";
        $slug->link_id = $product->id;
        $slug->slug = Str::slug($name,'-');
        $slug->save();
        
        foreach($product->images->sortBy('sort') as $image)
        {

            $sort = $request->input('image_'. str_ireplace("-","_",$image->id));
            if($sort=="") $sort = 0;
			$image->sort = $sort;
            $image->save();
            
            $check = $request->input('del_image_'. str_ireplace("-","_",$image->id));
            
            if($check=="hapus")
            {
                ImageHelper::deleteImageGoogle($image->public_id);
                $image->delete();
            }
            
        }
		
		$key = $request->input('key');
        $filetemps = FileTemp::where('key',$key)->get();
        $sort = $product->images->max('sort');
        foreach($filetemps as $filetemp)
        {
                $sort++;
                //$response = ImageHelper::uploadImageCloudinary($filetemp->file);
                $response = ImageHelper::uploadImageGoogle($filetemp->file);
                $image = new Image();
                $image->product_id = $product->id;
                $image->public_id = $response['public_id'];
                $image->secure_url = $response['secure_url'];
                $image->sort = $sort;
                $image->save();
                $filetemp->delete();
        }
		
		
		return response()->json([
                    "id" => "1",
                    "message" => 'Success'
                ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        Slug::where('type','product')->where('link_id',$product->id)->delete();
        self::product_api('/product/remove',$product->bokun_id);
        foreach($product->images as $image)
        {
            ImageHelper::deleteImageGoogle($image->public_id);
        }

        $product->delete();
    }
}

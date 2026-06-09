<?php

namespace budisteikul\tourcms\Controllers;
use App\Http\Controllers\Controller;


use budisteikul\tourcms\Models\Channel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use budisteikul\tourcms\DataTables\ChannelDataTable;

class ChannelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ChannelDataTable $dataTable)
    {
        return $dataTable->render('tourcms::channel.index');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('tourcms::channel.create');
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
            'name' => 'required|string|max:255|unique:channels,name'

        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors);
        }

        $name =  $request->input('name');
        $description =  $request->input('description');
        $invoice =  $request->input('invoice');
        $can_review =  $request->input('can_review');
        $can_review = $can_review === 'true'? true: false;
        $can_booking =  $request->input('can_booking');
        $can_booking = $can_booking === 'true'? true: false;

        $channel = new Channel();
        $channel->name = $name;
        $channel->description = $description;
        $channel->invoice = $invoice;
        $channel->can_review = $can_review;
        $channel->can_booking = $can_booking;
        $channel->save();

        return response()->json([
                    "id" => "1",
                    "message" => 'Success'
                ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Channel  $channel
     * @return \Illuminate\Http\Response
     */
    public function show(Channel $channel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Channel  $channel
     * @return \Illuminate\Http\Response
     */
    public function edit(Channel $channel)
    {
        //print_r($channel);
        return view('tourcms::channel.edit',['channel'=>$channel]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Channel  $channel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Channel $channel)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:channels,name,'.$channel->id
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors);
        }

        $name =  $request->input('name');
        $description =  $request->input('description');
        $invoice =  $request->input('invoice');
        $can_review =  $request->input('can_review');
        $can_review = $can_review === 'true'? true: false;
        $can_booking =  $request->input('can_booking');
        $can_booking = $can_booking === 'true'? true: false;
        
        
        $channel->name = $name;
        $channel->description = $description;
        $channel->invoice = $invoice;
        $channel->can_review = $can_review;
        $channel->can_booking = $can_booking;
        $channel->save();

        return response()->json([
                    "id" => "1",
                    "log" => $invoice,
                    "message" => 'Success'
                ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Channel  $channel
     * @return \Illuminate\Http\Response
     */
    public function destroy(Channel $channel)
    {
        $channel->delete();
    }
}

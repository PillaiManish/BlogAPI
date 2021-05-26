<?php

namespace App\Http\Controllers;

class BlogController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function blog(){
        return response()->json([
            'status'    =>'Success',
            'message'   => Blog::all()->latest()
        ]);
    }

    public function newBlog(Request $request){
        $input = Validator::make($request->all(),[
            'title'         => 'required|min:2',
            'description'   => 'required|min:20',
            'image'         => 'required'
        ]);

        if ($input->fails()){
            return response()->json([
                'status'    => 'Error',
                'message'   => 'Please check if input are correct'
            ],401);
        }

        Blog::create(['user_id'=>auth()->user()->id,'title'=>$request->title, 'description'=>$request->description]);
        return response()->json([
            'status'    => 'Success',
            'message'   => 'New Post has been created'
        ], 200);
    }

    
}

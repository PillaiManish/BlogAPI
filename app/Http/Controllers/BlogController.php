<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Blog;
use Validator;



class BlogController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['list']]);
    }

    // All User can see the post no need for login
    public function list(){
        return response()->json([
            'status'    =>'Success',
            'message'   => Blog::latest()->get()
        ]);
    }

    public function add(Request $request){
        $input = Validator::make($request->all(),[
            'title'         => 'required|min:2',
            'description'   => 'required|min:20',
        ]);

        $image = $request->file('image')->store('images');

        if ($input->fails()){
            return response()->json([
                'status'    => 'Error',
                'message'   => 'Please check if input are correct'
            ],401);
        }

        Blog::create(['user_id'=>auth()->user()->id,'title'=>$request->title, 'description'=>$request->description, 'image'=>$image]);
        return response()->json([
            'status'    => 'Success',
            'message'   => 'New Post has been created'
        ], 200);
    }

    public function edit(Request $request){
        $input = Validator::make($request->all(),[
            'id'            => 'required',
            'title'         => 'required|min:2',
            'description'   => 'required|min:20',
        ]);


        if ($input->fails()){
            return response()->json([
                'status' => 'Error',
                'message'=> $input->errors()
            ],401);
        }

        $blog = Blog::find($request->id);
        
        if ($blog->user->id != auth()->user()->id){
            return response()->json([
                "status"    => "Error",
                "message"   => "You don't have access to delete this blog"
            ],401);
    
        }
        $blog->title = $request->title;
        $blog->description = $request->description;
        $blog->image = $request->file('image')->store('images');
        $blog->save();

        return response()->json([
            "status"    => "Success",
            "message"   => "Your post has been updated."
        ], 200);
    }

    public function delete(Request $request){
        $input = Validator::make($request->all(),[
            'id'         => 'required', 
        ]);

        if ($input->fails()){
            return response()->json([
                'status' => 'Error',
                'message'=> 'Please enter the ID of Post'
            ],401);
        }

        $blog   = Blog::find($request->id);

        return auth()->user()->id;
        if ($blog->user->id != auth()->user()->id){
            return response()->json([
                'status'    => 'Error',
                'message'   => 'You are not the owner of the post'   
            ],401);
        }

        $blog->delete();
        return response()->json([
            'status'    => 'Success',
            'message'   => 'Post has been deleted' 
        ],200);
    }

}

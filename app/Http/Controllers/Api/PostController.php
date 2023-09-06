<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Validator;


class PostController extends Controller
{
    use ApiResponseTrait;
    public function index()
    {

        $posts = PostResource::collection(Post::get());
        return $this->apiResponse($posts , 'ok' , 200);

    }

    public function show($id)
    {
        $post = $post = Post::find($id);

        if($post)
        {
            return $this->apiResponse(new PostResource($post) , 'ok' , 200);
        }
        return $this->apiResponse(null , 'post not found' , 401);

    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'body' => 'required',
        ]);

        if ($validator->fails())
        {
            return $this->apiResponse(null , $validator->errors() , 400);
        }

        $post = Post::create($request->all());
        if($post)
        {
            return $this->apiResponse(new PostResource($post) , 'post saved' , 201);
        }
        return $this->apiResponse(null , 'post not save' , 400);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'body' => 'required',
        ]);

        if ($validator->fails())
        {
            return $this->apiResponse(null , $validator->errors() , 400);
        }

        $post = Post::find($id);

        if(!$post)
        {
            return $this->apiResponse(null , 'post not found' , 401);
        }

        $post->update($request->all());

        if($post)
        {
            return $this->apiResponse(new PostResource($post) , 'post updated' , 201);
        }
        return $this->apiResponse(null , 'the post not found' , 404);
    }

    public function destroy($id)
    {
        $post = Post::find($id);

        if(!$post)
        {
            return $this->apiResponse(null , 'post not found' , 401);
        }
        $post->delete($id);

        if($post)
        {
            return $this->apiResponse(null , 'post deleted' , 200);
        }
    }
}

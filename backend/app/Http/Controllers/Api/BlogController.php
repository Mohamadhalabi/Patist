<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Knowledgebase;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Show category of the resource.
     *
     * @param  mixed $request
     * @return void
     */
    public function category(Request $request, $param1 = null)
    {
        $blogData = Knowledgebase::where('slug', $param1)->get();

        if($blogData->count() <= 0){
            return response()->json([
                'message' => 'No data found'
            ], 404);
        }
            $category = [
                'title' => $blogData->first()->title,
                'slug' => $blogData->first()->slug,
                'type' => $blogData->first()->type,
                'total' => $blogData->count()
            ];

        return response()->json([
            'category' => $category,
            'blogData' => $blogData
        ]);
    }

    /**
     *
     * Show blog of the resource.
     *
     * @param  mixed $request
     * @return void
     */
    public function blog(Request $request)
    {
        $relatedTopics = Knowledgebase::where('slug', $request->category)->get();
        $blog = Knowledgebase::where('question_link', $request->slug)->first();
        if($blog == null){
            return response()->json([
                'message' => 'No data found'
            ], 404);
        }

        // Remove $blog in $relatedTopics
        $relatedTopics = $relatedTopics->filter(function ($value, $key) use ($blog) {
            return $value->id;
        });

        return response()->json([
            'blog' => $blog,
            'relatedTopics' => $relatedTopics
        ]);
    }
}

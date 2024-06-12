<?php

namespace App\Http\Controllers\Api\Renewal;

use App\Http\Controllers\Controller;
use App\Models\Knowledgebase;
use Illuminate\Http\Request;

class KnowledgeBaseController extends Controller
{
    public function index()
    {
        $knowledgeBase = Knowledgebase::where('project', 'renewal')->get();

        if (!$knowledgeBase) {
            return response()->json([
                'success' => false,
                'message' => 'Knowledgebase not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $knowledgeBase
        ]);
    }

    public function show($slug)
    {
        $knowledgeBase = Knowledgebase::where('slug', $slug)->first();
        $relatedKnowledgeBase = Knowledgebase::where('project', 'renewal')->where('slug', '!=', $slug)->get();
        if (!$knowledgeBase) {
            return response()->json([
                'success' => false,
                'message' => 'Knowledgebase not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'knowledge_base' => $knowledgeBase,
                'related_topics' => $relatedKnowledgeBase
            ]
        ]);
    }
}

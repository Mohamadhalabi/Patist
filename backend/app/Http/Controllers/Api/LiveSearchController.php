<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Faq;
use App\Models\Knowledgebase;
use App\Models\Service;
use App\Models\ServiceName;
use Illuminate\Http\Request;
use ProtoneMedia\LaravelCrossEloquentSearch\Search;

class LiveSearchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function search($lang, $query)
    {
        // Your search logic here
        // Use $lang and $query as needed

        $result = Search::add(ServiceName::where('title','LIKE','%' . $query. '%'))
            ->add(Faq::where('title','LIKE','%' . $query. '%')->where('language', $lang))
            ->add(Knowledgebase::where('question', 'LIKE', '%' . $query . '%'))
            ->paginate($perPage = 5, $pageName = 'page', $page = 1)
            ->orderByModel([
                ServiceName::class, Faq::class, Knowledgebase::class
            ])
            ->search($query);

        return response()->json($result);
    }

}

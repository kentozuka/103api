<?php

namespace App\Http\Controllers;

use App\Jugyou;
use App\Saved;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClassController extends Controller
{
    public function detail(Request $request)
    {
        $url = $request->url;
        $stem = Jugyou::where('url', $url)->first();
        if (!$stem) {
            return response()->json('invalid request', 400);
        }
        $stem->language;
        $stem->dept;
        $stem->campus;
        $stem->term;
        foreach ($stem->class_cat as $cat) {
            $cat->first;
            $cat->second;
            $cat->third;
            $cat->type;
            $cat->level;
        }
        foreach ($stem->class_prof as $prof) {
            $prof->prof;
        }
        $stem->class_room;
        $stem->period;
        $stem->detail;
        $stem->detail->grading;
        foreach ($stem->review as $rev) {
            $rev->like;
            $rev->dislike;
        }
        return response()->json($stem);
    }
}

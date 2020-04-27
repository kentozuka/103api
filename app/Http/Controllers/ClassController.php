<?php

namespace App\Http\Controllers;

use App\Jugyou;
use App\Saved;
use App\Detail;
use App\View;
use App\Period;
use App\ClassCat;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Pagination\LengthAwarePaginator;

class ClassController extends Controller
{
    public function search(Request $request)
    {
        // manual search functionality create april 27, 2020

        // first step parameter validation
        $lng = $request->get('lng');
        $dsp = $request->get('dsp');
        if (!$lng || !$dsp) return response(null, 400);
        // narrowing the search scope by searching the base information first
        $base = [];
        array_push($base, ['year', now()->year]);
        if ($sch = $request->get('school')) array_push($base, ['dept_id', $sch]);
        if ($cre = $request->get('credit')) array_push($base, ['credit', $cre]);
        if ($eli = $request->get('eligible')) array_push($base, ['eligible', $eli]);
        if ($ter = $request->get('term')) array_push($base, ['term_id', $ter]);
        $lan = $request->get('language') ? json_decode($request->get('language')) : null;
        $can = $request->get('campus') ? json_decode($request->get('campus')) : null;
        if ($lan && $base) {
            $base = $can 
            ? Jugyou::where($base)->whereIn('language_id', $lan)->whereIn('campus_id', $can)->pluck('id') 
            : Jugyou::where($base)->whereIn('language_id', $lan)->pluck('id');
        } else if ($base) {
            $base = $can
            ? Jugyou::where($base)->whereIn('campus_id', $can)->pluck('id')
            : Jugyou::where($base)->pluck('id');
        }
        
        // futher narrowing the search scope by day and period
        $date = [];
        $day = $request->get('days') ? json_decode($request->get('days')) : [];
        $per = $request->get('periods') ? json_decode($request->get('periods')) : [];
        if ($base) {
            $date = Period::whereIn('class_id', $base)->get();
            if ($day && $per) {
                $date = $date->whereIn('day', $day)->whereIn('period', $per)->pluck('class_id');
            } else if ($day || $per) {
                $date = $day ? $date->whereIn('day', $day)->pluck('class_id') : $date->whereIn('period', $per)->pluck('id');
            } else {
                $date = $date->pluck('class_id');
            }
        } else {
            if ($day && $per) {
                $date = Period::whereIn('day', $day)->whereIn('period', $per)->pluck('class_id');
            } else if ($day || $per) {
                $date = $day ? Period::whereIn('day', $day)->pluck('class_id') : Period::whereIn('period', $per)->pluck('id');
            }
        }

        // further narrowing the search scope by academic desciplines
        $academic = [];
        $firsts = $request->get('firsts') ? json_decode($request->get('firsts')) : null;
        $seconds = $request->get('seconds') ? json_decode($request->get('seconds')) : null;
        $thirds = $request->get('thirds') ? json_decode($request->get('thirds')) : null;
        if ($date) {
            $academic = ClassCat::whereIn('class_id', $date)->get();
            if ($thirds) {
                $academic = $academic->whereIn('first_id', $firsts)->whereIn('second_id', $seconds)->whereIn('third_id', $thirds)->pluck('class_id');
            } else if ($seconds) {
                $academic = $academic->whereIn('first_id', $firsts)->whereIn('second_id', $seconds)->pluck('class_id');
            }else if ($firsts) {
                $academic = $academic->whereIn('first_id', $firsts)->pluck('class_id');
            } else {
                $academic = $academic->pluck('class_id');
            }
        } else {
            if ($thirds) {
                $academic = ClassCat::whereIn('first_id', $firsts)->whereIn('second_id', $seconds)->whereIn('third_id', $thirds)->pluck('class_id');
            } else if ($seconds) {
                $academic = ClassCat::whereIn('first_id', $firsts)->whereIn('second_id', $seconds)->pluck('class_id');
            }else if ($firsts) {
                $academic = ClassCat::whereIn('first_id', $firsts)->pluck('class_id');
            }
        }

        // create a ranking based on the search keyword relavance
        $key = $request->get('keyword');
        $holder = [];
        if ($key) {
            if ($academic) {
                $en = Jugyou::whereIn('id', $academic)->where('title_en', 'like', "%$key%")->pluck('id')->toArray(); // 12pt
                $jp = Jugyou::whereIn('id', $academic)->where('title_jp', 'like', "%$key%")->pluck('id')->toArray(); // 12pt
                $sub = Detail::whereIn('class_id', $academic)->where('subtitle', 'like', "%$key%")->pluck('class_id')->toArray(); // 8pt
                $out = Detail::whereIn('class_id', $academic)->where('outline', 'like', "%$key%")->limit(80)->pluck('class_id')->toArray(); // 6pt
                $obj = Detail::whereIn('class_id', $academic)->where('objective', 'like', "%$key%")->limit(80)->pluck('class_id')->toArray(); // 2pt
            } else {
                $en = Jugyou::where('title_en', 'like', "%$key%")->pluck('id')->toArray(); // 12pt
                $jp = Jugyou::where('title_jp', 'like', "%$key%")->pluck('id')->toArray(); // 12pt
                $sub = Detail::where('subtitle', 'like', "%$key%")->pluck('class_id')->toArray(); // 8pt
                $out = Detail::where('outline', 'like', "%$key%")->limit(80)->pluck('class_id')->toArray(); // 6pt
                $obj = Detail::where('objective', 'like', "%$key%")->limit(80)->pluck('class_id')->toArray(); // 2pt
            }
            foreach ($en as $e) { array_key_exists($e, $holder) ? $holder[$e] += 10 : $holder[$e] = 12;}
            foreach ($jp as $j) {array_key_exists($j, $holder) ? $holder[$j] += 10 : $holder[$j] = 12;}
            foreach ($sub as $s) {array_key_exists($s, $holder) ? $holder[$s] += 6 : $holder[$s] = 8;}
            foreach ($out as $o) {array_key_exists($o, $holder) ? $holder[$o] += 4 : $holder[$o] = 6;}
            foreach ($obj as $b) {array_key_exists($b, $holder) ? $holder[$b] += 2: $holder[$b] = 2;}
            $holder = array_keys(collect($holder)->sort()->reverse()->toArray());
        } else {
            $holder = $academic->toArray();
        }

        // eliminating the result
        $trimmed = count($holder) > 25 ? count($holder) : false;

        // create result object
        $user_saved = $request->user()->saved_jugyous->pluck('class_id');
        $bs = Jugyou::whereIn('id', $holder)->get('id');
        if ($dsp === 'compact') {
            foreach ($bs as $ie) {
                $al = Jugyou::find($ie->id);
                $ie->title = $al['title_'.$lng];
                $ie->professors = $al->profs->pluck('name_'.$lng);
                $ie->dept = $al->dept->$lng;
                $ie->term = $al->term->$lng;
                $ie->campus = $al->campus->$lng;
                $ie->rooms = $al->rooms;
                $ie->periods = $al->period;
                $ie->saved = Saved::where('class_id', $ie->id)->count();
                $ie->view = View::where('class_id', $ie->id)->count();
                $ie->user_saved = in_array($ie->id, $user_saved->toArray());
                $ie->index = array_search($ie->id, $holder, true);
            }
        } else if ($dsp === 'detail') {
            foreach ($bs as $ie) {
                $al = Jugyou::find($ie->id);
                $ie->title = $al['title_'.$lng];
                $ie->category = $al['category_'.$lng];
                $ie->professors = $al->profs->pluck('name_'.$lng);
                $ie->dept = $al->dept->$lng;
                $ie->term = $al->term->$lng;
                $ie->campus = $al->campus->$lng;
                $ie->rooms = $al->rooms;
                $ie->periods = $al->period;
                $cats = ClassCat::where('class_id', $ie->id)->first();
                $ie->level = $cats->level->$lng;
                $ie->type = $cats->type->$lng;
                $ie->detail = Detail::where('class_id', $ie->id)->select('outline')->first();
                $ie->saved = Saved::where('class_id', $ie->id)->count();
                $ie->view = View::where('class_id', $ie->id)->count();
                $ie->user_saved = in_array($ie->id, $user_saved->toArray());
                $ie->index = array_search($ie->id, $holder, true);
            }
        }
        $result = $bs->toArray();
        usort($result, function ($a, $b) { return $a['index'] <=> $b['index']; });
        return response()->json(['trimmed' => $trimmed, 'classes' => $result]);
    }

    public function index(Request $request)
    {
        $url = $request->url;
        $base = Jugyou::where('url', $url)->first();
        $user_data = $request->user()->saved_jugyous->pluck('class_id');
        if (!$base || !$language = $request->input('lng')) return response(null, 400);

        $basic = $base->only('year', 'title_' . $language, 'credit', 'eligible', 'url', 'category_' . $language, 'is_open');
        $profs = $base->profs->pluck('name_' . $language);
        $dept = $base->dept->$language;
        $term = $base->term->$language;
        $campus = $base->campus->$language;
        $rooms = $base->rooms;
        $periods = $base->period;
        $level = $base->class_cat->first()->level->$language;
        $type = $base->class_cat->first()->type->$language;
        $detail = $base->detail;
        $base->detail->grading;

        $view = View::where('class_id', $base['id'])->count();
        $saved = Saved::where('class_id', $base['id'])->count();
        $is_saved = $user_data->contains($base['id']);

        $result = array('dept' => $dept, 'term' => $term, 'campus' => $campus, 'profs' => $profs, 'rooms' => $rooms, 'periods' => $periods, 'level' => $level, 'type' => $type, 'saved' => $saved, 'viwe' => $view, 'user_saved' => $is_saved, 'detail' => $detail);
        return response()->json(array_merge($basic, $result), 200);
    }

    public function saved(Request $request)
    {

        function class_data($data, $lng)
        {
            foreach ($data as $cl) {
                $base = Jugyou::find($cl->class_id);
                $cl->title = $base->only('title_' . $lng)['title_' . $lng];
                $cl->profs = $base->profs->pluck('name_' . $lng);
                $cl->term = $base->term->$lng;
                $cl->languge = $base->language->$lng;
                $cl->department = $base->dept->$lng;
                $cl->saved = Saved::where('class_id', $cl->class_id)->count();
                $cl->view = View::where('class_id', $cl->class_id)->count();
            }
        }

        if (!$language =  $request->input('lng')) return response(null, 400);
        $jugyous = $request->user()->saved_jugyous()->select('class_id')->paginate(25);

        if ($language === 'en') {
            class_data($jugyous, 'en');
        } else if ($language === 'jp') {
            class_data($jugyous, 'jp');
        }

        return response($jugyous, 200);
    }
}

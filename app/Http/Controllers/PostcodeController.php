<?php

namespace App\Http\Controllers;

use App\PostcodeRange;
use App\Suburb;
use Illuminate\Http\Request;

class PostcodeController extends Controller
{
    public function index()
    {
        return view('postcode.index');
    }

    public function check(Request $request)
    {
        $request->validate([
            'search' => 'required',
        ]);

        $input = $request->query('search');
        $results = [];

        if (is_numeric($input)) {
            // Search by postcode
            $suburbs = Suburb::where('postcode', $input)->get();
        } else {
            // Search by suburb (case-insensitive, partial match)
            $suburbs = Suburb::where('suburb', 'like', '%' . $input . '%')->get();
        }

        foreach ($suburbs as $suburb) {
            $range = PostcodeRange::where('start_postcode', '<=', $suburb->postcode)
                ->where('end_postcode', '>=', $suburb->postcode)
                ->first();
            $results[] = [
                'suburb' => $suburb->suburb,
                'postcode' => $suburb->postcode,
                'state' => $suburb->state,
                'category' => $range ? $range->category : 'Not found or not a designated regional area',
            ];
        }

        return view('postcode.index', [
            'results' => $results,
            'search' => $input
        ]);
    }

    public function suggest(Request $request)
    {
        $query = $request->query('q');
        $results = [];

        if (is_numeric($query)) {
            $results = Suburb::where('postcode', 'like', $query . '%')
                ->limit(10)
                ->get(['suburb', 'postcode', 'state']);
        } else {
            $results = Suburb::where('suburb', 'like', $query . '%')
                ->limit(10)
                ->get(['suburb', 'postcode', 'state']);
        }

        return response()->json($results);
    }
} 
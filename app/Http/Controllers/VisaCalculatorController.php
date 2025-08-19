<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VisaCalculatorController extends Controller
{
    public function index()
    {
        return view('visa_calculator');
    }

    public function calculate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_duration' => 'required|integer|min:1|max:12',
            'tuition_fees' => 'required|numeric|min:0',
            'primary_applicant' => 'required|integer|min:1|max:1',
            'spouse' => 'required|integer|min:0',
            'children' => 'required|integer|min:0',
            'school_children' => 'required|integer|min:0|lte:children',
        ]);

        if ($validator->fails()) {
            return redirect()->route('visa.calculator')
                            ->withErrors($validator)
                            ->withInput();
        }

        $data = $request->all();
        $duration = $data['course_duration'];

        // Adjust duration: add 2 months if <= 10, cap at 12
        if ($duration <= 10) {
            $duration += 2;
            if ($duration > 12) $duration = 12;
        }

        $factor = $duration / 12;

        $result = [
            'tuition' => round($data['tuition_fees'] * $factor),
            'living_main' => round(29710 * $factor),
            'living_spouse' => round(10394 * $data['spouse'] * $factor),
            'living_children' => round(4449 * $data['children'] * $factor),
            'schooling' => round(13502 * $data['school_children'] * $factor),
            'travel' => 2000 * (1 + $data['spouse'] + $data['children']),
        ];

        $result['total'] = array_sum($result);

        return redirect()->route('visa.calculator')->with('result', $result);
    }
}
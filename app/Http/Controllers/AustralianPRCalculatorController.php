<?php
// app/Http/Controllers/AustralianPRCalculatorController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Rules\AgeRule;

class AustralianPRCalculatorController extends Controller
{
    public function index()
    {
        return view('calculator.index');
    }

    public function calculate(Request $request)
    {
        $validated = $request->validate([
            'age' => ['required', 'integer', new AgeRule],
            'english' => 'required|in:0,10,20',
            'education' => 'required|in:0,10,15,20',
            'work_overseas' => 'required|in:0,5,10,15,20',
            'work_australia' => 'required|in:0,5,10,15,20',
            'study_australia' => 'required|in:0,5',
            'regional_study' => 'required|in:0,5',
            'specialist_education' => 'required|in:0,10',
            'partner_skills' => 'required|in:0,5,10',
            'community_language' => 'required|in:0,5',
            'professional_year' => 'required|in:0,5',
        ]);

        $points = $this->calculatePoints($validated);

        return response()->json([
            'total_points' => $points,
            'breakdown' => $this->getPointsBreakdown($validated),
            'advice_letter' => $this->generateAdviceLetter($validated, $points)
        ]);
    }

    private function calculatePoints($data)
    {
        $points = 0;

        // Sum all field values (points are directly from form inputs)
        $points += (int)$data['age'];
        $points += (int)$data['english'];
        $points += (int)$data['education'];
        $points += (int)$data['work_overseas'];
        $points += (int)$data['work_australia'];
        $points += (int)$data['study_australia'];
        $points += (int)$data['regional_study'];
        $points += (int)$data['specialist_education'];
        $points += (int)$data['partner_skills'];
        $points += (int)$data['community_language'];
        $points += (int)$data['professional_year'];

        return $points;
    }

    private function getPointsBreakdown($data)
    {
        return [
            'age' => (int)$data['age'],
            'english' => (int)$data['english'],
            'education' => (int)$data['education'],
            'work_overseas' => (int)$data['work_overseas'],
            'work_australia' => (int)$data['work_australia'],
            'study_australia' => (int)$data['study_australia'],
            'regional_study' => (int)$data['regional_study'],
            'specialist_education' => (int)$data['specialist_education'],
            'partner_skills' => (int)$data['partner_skills'],
            'community_language' => (int)$data['community_language'],
            'professional_year' => (int)$data['professional_year'],
        ];
    }

    private function generateAdviceLetter($data, $points)
    {
        $date = date('d F Y');
        $breakdown = $this->getPointsBreakdown($data);

        $letter = [
            'date' => $date,
            'reference' => 'PR-' . strtoupper(substr(md5(json_encode($data) . time()), 0, 8)),
            'subject' => 'Australian PR Points Assessment',
            'greeting' => 'Dear Valued Client,',
            'introduction' => 'Thank you for using the Bansal Immigration PR Points Calculator. Below is your points assessment based on the information provided.',
            'points_summary' => "Your profile has achieved a total of {$points} points.",
            'points_breakdown' => [
                "Age: {$breakdown['age']} points",
                "English Skills: {$breakdown['english']} points",
                "Education: {$breakdown['education']} points",
                "Work Experience (Outside Australia): {$breakdown['work_overseas']} points",
                "Work Experience (Australia): {$breakdown['work_australia']} points",
                "Australian Study: {$breakdown['study_australia']} points",
                "Regional Study: {$breakdown['regional_study']} points",
                "Specialist Education: {$breakdown['specialist_education']} points",
                "Partner Skills: {$breakdown['partner_skills']} points",
                "Community Language: {$breakdown['community_language']} points",
                "Professional Year: {$breakdown['professional_year']} points",
            ],
            'conclusion' => 'This assessment is based on the information provided. For official visa eligibility, please consult with immigration authorities.',
            'closing' => 'For further assistance, please contact our team at Bansal Immigration.',
            'signature' => [
                'regards' => 'Best Regards,',
                'name' => 'Bansal Immigration Team',
                'title' => 'Australian Immigration Assessment Tool'
            ]
        ];

        return $letter;
    }
}
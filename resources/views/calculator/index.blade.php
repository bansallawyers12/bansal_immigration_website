<!-- resources/views/calculator/index.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Australia PR Points Calculator 2025 - Bansal Immigration</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: url("data:image/svg+xml;utf8,<svg fill='%2314B8A6' height='24' viewBox='0 0 24 24' width='24' xmlns='http://www.w3.org/2000/svg'><path d='M7 10l5 5 5-5z'/><path d='M0 0h24v24H0z' fill='none'/></svg>");
            background-repeat: no-repeat;
            background-position-x: 98%;
            background-position-y: 50%;
        }
        .hover-scale {
            transition: transform 0.2s;
        }
        .hover-scale:hover {
            transform: scale(1.05);
        }
        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
    </style>
</head>
<body class="bg-teal-50">
    <!-- Header -->
    <header class="bg-teal-500 text-white py-6">
        <div class="container mx-auto px-4 flex items-center justify-between">
            <div class="flex items-center">
                <img src="https://www.bansalimmigration.com.au/public/img/logo_img/bansal-imm-logo-11_vrUFM77pu7.png" alt="Bansal Immigration Consultants" class="h-12 mr-4 rounded">
                <div>
                    <h1 class="text-2xl font-bold">Australia PR Points Calculator 🌏</h1>
                    <p class="mt-1 text-sm">Calculate your PR points with Bansal Immigration!</p>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Left Section: Input Form -->
            <section class="lg:w-1/2 bg-white p-6 rounded-xl shadow-lg">
                <h2 class="text-xl font-semibold mb-4 text-teal-600">Your Profile Details 😊</h2>
                <p class="mb-6 text-gray-600">
                    Enter your details to calculate your points for Australian PR. It's quick and easy!
                </p>
                <form id="points-calculator-form" class="space-y-4">
                    @csrf
                    <!-- Age -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Age</label>
                        <select name="age" class="mt-1 block w-full p-2 border border-teal-300 rounded-lg bg-teal-50 focus:ring-teal-500 focus:border-teal-500 hover-scale" required>
                            <option value="">Select Age</option>
                            <option value="0">Under 18</option>
                            <option value="25">18–24 years</option>
                            <option value="30">25–32 years</option>
                            <option value="25">33–39 years</option>
                            <option value="15">40–44 years</option>
                            <option value="0">45 and over</option>
                        </select>
                    </div>

                    <!-- English Proficiency -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">English Skills</label>
                        <select name="english" class="mt-1 block w-full p-2 border border-teal-300 rounded-lg bg-teal-50 focus:ring-teal-500 focus:border-teal-500 hover-scale" required>
                            <option value="">Select English Level</option>
                            <option value="0">Competent (IELTS 6)</option>
                            <option value="10">Proficient (IELTS 7)</option>
                            <option value="20">Superior (IELTS 8)</option>
                            <option value="0">Basic</option>
                        </select>
                    </div>

                    <!-- Education -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Education</label>
                        <select name="education" class="mt-1 block w-full p-2 border border-teal-300 rounded-lg bg-teal-50 focus:ring-teal-500 focus:border-teal-500 hover-scale" required>
                            <option value="">Select Education</option>
                            <option value="20">Doctorate</option>
                            <option value="15">Bachelor's/Master's</option>
                            <option value="10">Diploma/Trade</option>
                            <option value="0">None</option>
                        </select>
                    </div>

                    <!-- Overseas Work Experience -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Work Experience (Outside Australia)</label>
                        <select name="work_overseas" class="mt-1 block w-full p-2 border border-teal-300 rounded-lg bg-teal-50 focus:ring-teal-500 focus:border-teal-500 hover-scale" required>
                            <option value="">Select Experience</option>
                            <option value="0">Less than 3 years</option>
                            <option value="5">3–4 years</option>
                            <option value="10">5–7 years</option>
                            <option value="15">8–10 years</option>
                            <option value="20">11+ years</option>
                        </select>
                    </div>

                    <!-- Australian Work Experience -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Work Experience (In Australia)</label>
                        <select name="work_australia" class="mt-1 block w-full p-2 border border-teal-300 rounded-lg bg-teal-50 focus:ring-teal-500 focus:border-teal-500 hover-scale" required>
                            <option value="">Select Experience</option>
                            <option value="0">Less than 1 year</option>
                            <option value="5">1–2 years</option>
                            <option value="10">3–4 years</option>
                            <option value="15">5–7 years</option>
                            <option value="20">8–10 years</option>
                        </select>
                    </div>

                    <!-- Australian Study -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Studied in Australia?</label>
                        <select name="study_australia" class="mt-1 block w-full p-2 border border-teal-300 rounded-lg bg-teal-50 focus:ring-teal-500 focus:border-teal-500 hover-scale" required>
                            <option value="">Select Option</option>
                            <option value="0">No</option>
                            <option value="5">Yes (2+ years)</option>
                        </select>
                    </div>

                    <!-- Regional Study -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Studied in Regional Australia?</label>
                        <select name="regional_study" class="mt-1 block w-full p-2 border border-teal-300 rounded-lg bg-teal-50 focus:ring-teal-500 focus:border-teal-500 hover-scale" required>
                            <option value="">Select Option</option>
                            <option value="0">No</option>
                            <option value="5">Yes</option>
                        </select>
                    </div>

                    <!-- Specialist Education -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Specialist Education (STEM)</label>
                        <select name="specialist_education" class="mt-1 block w-full p-2 border border-teal-300 rounded-lg bg-teal-50 focus:ring-teal-500 focus:border-teal-500 hover-scale" required>
                            <option value="">Select Option</option>
                            <option value="0">No</option>
                            <option value="10">Master's/Doctorate in STEM</option>
                        </select>
                    </div>

                    <!-- Partner Skills -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Partner Skills</label>
                        <select name="partner_skills" class="mt-1 block w-full p-2 border border-teal-300 rounded-lg bg-teal-50 focus:ring-teal-500 focus:border-teal-500 hover-scale" required>
                            <option value="">Select Option</option>
                            <option value="0">No partner/Not eligible</option>
                            <option value="5">Partner has English</option>
                            <option value="10">Partner has skills + English</option>
                            <option value="5">Single</option>
                        </select>
                    </div>

                    <!-- Community Language -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Community Language</label>
                        <select name="community_language" class="mt-1 block w-full p-2 border border-teal-300 rounded-lg bg-teal-50 focus:ring-teal-500 focus:border-teal-500 hover-scale" required>
                            <option value="">Select Option</option>
                            <option value="0">No</option>
                            <option value="5">Yes</option>
                        </select>
                    </div>

                    <!-- Professional Year -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Professional Year in Australia</label>
                        <select name="professional_year" class="mt-1 block w-full p-2 border border-teal-300 rounded-lg bg-teal-50 focus:ring-teal-500 focus:border-teal-500 hover-scale" required>
                            <option value="">Select Option</option>
                            <option value="0">No</option>
                            <option value="5">Yes</option>
                        </select>
                    </div>
                    <button type="submit" class="w-full bg-teal-500 text-white py-2 px-4 rounded-lg hover:bg-teal-600 hover-scale transition">Calculate Points</button>
                </form>
            </section>

            <!-- Right Section: Points Display -->
            <section class="lg:w-1/2 bg-orange-100 p-6 rounded-xl shadow-lg fade-in">
                <h2 class="text-xl font-semibold mb-4 text-orange-600">Your Points Score! 🎉</h2>
                <div class="space-y-4">
                    <div class="flex items-center">
                        <span class="text-teal-500 mr-2">⭐</span>
                        <p>Age: <span id="points-age" class="font-bold text-teal-600">0</span> points</p>
                    </div>
                    <div class="flex items-center">
                        <span class="text-teal-500 mr-2">⭐</span>
                        <p>English Skills: <span id="points-english" class="font-bold text-teal-600">0</span> points</p>
                    </div>
                    <div class="flex items-center">
                        <span class="text-teal-500 mr-2">⭐</span>
                        <p>Education: <span id="points-education" class="font-bold text-teal-600">0</span> points</p>
                    </div>
                    <div class="flex items-center">
                        <span class="text-teal-500 mr-2">⭐</span>
                        <p>Work Experience (Outside): <span id="points-work_overseas" class="font-bold text-teal-600">0</span> points</p>
                    </div>
                    <div class="flex items-center">
                        <span class="text-teal-500 mr-2">⭐</span>
                        <p>Work Experience (Australia): <span id="points-work_australia" class="font-bold text-teal-600">0</span> points</p>
                    </div>
                    <div class="flex items-center">
                        <span class="text-teal-500 mr-2">⭐</span>
                        <p>Australian Study: <span id="points-study_australia" class="font-bold text-teal-600">0</span> points</p>
                    </div>
                    <div class="flex items-center">
                        <span class="text-teal-500 mr-2">⭐</span>
                        <p>Regional Study: <span id="points-regional_study" class="font-bold text-teal-600">0</span> points</p>
                    </div>
                    <div class="flex items-center">
                        <span class="text-teal-500 mr-2">⭐</span>
                        <p>Specialist Education: <span id="points-specialist_education" class="font-bold text-teal-600">0</span> points</p>
                    </div>
                    <div class="flex items-center">
                        <span class="text-teal-500 mr-2">⭐</span>
                        <p>Partner Skills: <span id="points-partner_skills" class="font-bold text-teal-600">0</span> points</p>
                    </div>
                    <div class="flex items-center">
                        <span class="text-teal-500 mr-2">⭐</span>
                        <p>Community Language: <span id="points-community_language" class="font-bold text-teal-600">0</span> points</p>
                    </div>
                    <div class="flex items-center">
                        <span class="text-teal-500 mr-2">⭐</span>
                        <p>Professional Year: <span id="points-professional_year" class="font-bold text-teal-600">0</span> points</p>
                    </div>
                    <hr class="border-teal-300">
                    <h3 class="text-lg font-semibold text-orange-600">Total Points:</h3>
                    <p><span id="total-points" class="font-bold text-teal-600">0</span> points</p>
                    <button id="print-letter" class="mt-4 bg-teal-500 text-white px-6 py-2 rounded-lg hover:bg-teal-600 hover-scale transition hidden">Print Advice Letter</button>
                    <a href="mailto:info@bansalimmigration.com" class="inline-block mt-2 bg-orange-500 text-white px-6 py-2 rounded-lg hover:bg-orange-600 hover-scale transition">Contact Us! 📞</a>
                </div>
            </section>
        </div>

        <!-- Disclaimer -->
        <section class="mt-8 text-gray-600 bg-white p-6 rounded-xl">
            <h3 class="text-lg font-semibold text-teal-600">A Quick Note 📝</h3>
            <p class="mt-2">
                This calculator from Bansal Immigration provides an estimate of your PR points. Official eligibility must be verified by the Australian Department of Home Affairs. Contact our migration agents for professional guidance!
            </p>
        </section>
    </main>

    <!-- Footer -->
    <footer class="bg-teal-500 text-white py-6">
        <div class="container mx-auto px-4 text-center">
            <p class="text-sm">© 2025 Bansal Immigration. All rights reserved.</p>
            <p class="mt-2 text-sm">Helping you chase your Australian dream! 🌟</p>
        </div>
    </footer>

    <!-- JavaScript for Form Submission and Letter Printing -->
    <script>
        $(document).ready(function() {
            $('#points-calculator-form').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: '/calculate-pr-points',
                    method: 'POST',
                    data: $(this).serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $('#total-points').text(response.total_points);
                        const breakdown = response.breakdown;
                        $('#points-age').text(breakdown.age);
                        $('#points-english').text(breakdown.english);
                        $('#points-education').text(breakdown.education);
                        $('#points-work_overseas').text(breakdown.work_overseas);
                        $('#points-work_australia').text(breakdown.work_australia);
                        $('#points-study_australia').text(breakdown.study_australia);
                        $('#points-regional_study').text(breakdown.regional_study);
                        $('#points-specialist_education').text(breakdown.specialist_education);
                        $('#points-partner_skills').text(breakdown.partner_skills);
                        $('#points-community_language').text(breakdown.community_language);
                        $('#points-professional_year').text(breakdown.professional_year);
                        $('#print-letter').removeClass('hidden').data('letter', response.advice_letter);
                    },
                    error: function(xhr) {
                        alert('Error: ' + xhr.responseJSON.message);
                    }
                });
            });

            $('#print-letter').on('click', function() {
                let letter = $(this).data('letter');
                let printWindow = window.open('', '_blank');
                printWindow.document.write(`
                    <html>
                        <head>
                            <title>PR Points Advice Letter</title>
                            <style>
                                body { font-family: Arial, sans-serif; margin: 40px; }
                                .letter { max-width: 800px; margin: auto; }
                                h2 { color: #14B8A6; }
                                ul { list-style-type: disc; padding-left: 20px; }
                            </style>
                        </head>
                        <body>
                            <div class="letter">
                                <p>${letter.date}</p>
                                <p>Reference: ${letter.reference}</p>
                                <h2>${letter.subject}</h2>
                                <p>${letter.greeting}</p>
                                <p>${letter.introduction}</p>
                                <p>${letter.points_summary}</p>
                                <ul>
                                    ${letter.points_breakdown.map(point => `<li>${point}</li>`).join('')}
                                </ul>
                                <p>${letter.conclusion}</p>
                                <p>${letter.closing}</p>
                                <p>${letter.signature.regards}</p>
                                <p>${letter.signature.name}</p>
                                <p>${letter.signature.title}</p>
                            </div>
                        </body>
                    </html>
                `);
                printWindow.document.close();
                printWindow.print();
            });
        });
    </script>
</body>
</html>

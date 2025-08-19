@extends('layouts.frontend')
@section('seoinfo')
	<title>Australian PR Points Calculator</title>
	<meta name="description" content="{{@$pagedata->meta_description}}" />
	<link rel="canonical" href="<?php echo URL::to('/'); ?>/{{@$pagedata->slug}}" />
	<meta property="og:locale" content="en_US" />
	<meta property="og:type" content="website" />
	<meta property="og:title" content="{{@$pagedata->meta_title}}" />
	<meta property="og:description" content="{{@$pagedata->meta_description}}" />
	<meta property="og:url" content="<?php echo URL::to('/'); ?>/{{@$pagedata->slug}}" />
	<meta property="og:site_name" content="<?php echo @\App\ThemeOption::where('meta_key','site_name')->first()->meta_value; ?>" />
	<meta property="article:publisher" content="https://www.facebook.com/BANSALImmigration/" />
	<meta property="article:modified_time" content="2023-04-04T21:06:24+00:00" />
	<meta property="og:image" content="<?php echo URL::to('/'); ?>/public/img/bansal-immigration-icon.jpg" />
	<meta property="og:image:width" content="200" />
	<meta property="og:image:height" content="200" />
	<meta property="og:image:type" content="image/jpeg" />
	<meta name="twitter:card" content="summary_large_image" />
	<meta name="twitter:title" content="{{@$pagedata->meta_title}}" />
	<meta name="twitter:description" content="{{@$pagedata->meta_description}}" />
	<meta name="twitter:image" content="<?php echo URL::to('/'); ?>/public/img/bansal-immigration-icon.jpg" />
	<meta name="twitter:site" content="@Bansalimmi" />
	<meta name="twitter:label1" content="Est. reading time" />
	<meta name="twitter:data1" content="6 minutes" />
@endsection
@section('content')
<link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<div class="container mx-auto px-4 py-8" x-data="calculator()">
        <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-lg p-6">
            <h1 class="text-2xl font-bold mb-6">Australian PR Points Calculator</h1>

            <form @submit.prevent="calculatePoints">
                <!-- Age -->
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Age</label>
                    <input type="number" x-model="formData.age" class="w-full p-2 border rounded" required>
                </div>

                <!-- English Level -->
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">English Level</label>
                    <select x-model="formData.english_level" class="w-full p-2 border rounded" required>
                        <option value="superior">Superior</option>
                        <option value="proficient">Proficient</option>
                        <option value="competent">Competent</option>
                        <option value="basic">Basic</option>
                    </select>
                </div>

                <!-- Education -->
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Education</label>
                    <select x-model="formData.education" class="w-full p-2 border rounded" required>
                        <option value="phd">Doctorate (PhD)</option>
                        <option value="masters">Masters Degree</option>
                        <option value="bachelors">Bachelor Degree</option>
                        <option value="diploma">Diploma</option>
                        <option value="trade">Trade Qualification</option>
                    </select>
                </div>

                <!-- Work Experience -->
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Overseas Work Experience (years)</label>
                    <input type="number" x-model="formData.work_experience" class="w-full p-2 border rounded" required min="0" max="20">
                </div>

                <!-- Australian Work Experience -->
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Australian Work Experience (years)</label>
                    <input type="number" x-model="formData.australian_work_exp" class="w-full p-2 border rounded" required min="0" max="10">
                </div>

                <!-- Additional Qualifications -->
                <div class="space-y-4 mb-6">
                    <div class="flex items-center">
                        <input type="checkbox" x-model="formData.professional_year" class="mr-2">
                        <label>Professional Year in Australia</label>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" x-model="formData.australian_study" class="mr-2">
                        <label>Australian Study Requirement</label>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" x-model="formData.partner_skills" class="mr-2">
                        <label>Partner Skills</label>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" x-model="formData.regional_study" class="mr-2">
                        <label>Regional Study</label>
                    </div>
                </div>

                <button type="submit" class="w-full bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">
                    Calculate Points
                </button>
            </form>

            <!-- Results Section -->
            <template x-if="results">
                <div class="mt-8">
                    <h2 class="text-xl font-bold mb-4">Results</h2>
                    <div class="bg-gray-100 p-4 rounded">
                        <p class="text-2xl font-bold text-center mb-4">
                            Total Points: <span x-text="results.total_points"></span>
                        </p>
                        <div class="space-y-2">
                            <template x-for="(points, category) in results.breakdown" :key="category">
                                <div class="flex justify-between">
                                    <span x-text="formatCategory(category)"></span>
                                    <span x-text="points"></span>
                                </div>
                            </template>
                        </div>
                        <!-- Visa Options Section -->
						<div class="mt-4 p-4 border rounded" x-show="results.visa_options">
							<h3 class="font-bold mb-2">Possible Visa Options</h3>
							<template x-for="option in results.visa_options" :key="option.visa">
								<div class="mb-3 p-3 border rounded" :class="option.eligible ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200'">
									<div class="flex justify-between items-center">
										<span class="font-semibold" x-text="option.visa"></span>
										<span x-text="option.eligible ? '✓ Eligible' : '✗ Not Eligible'"
											  :class="option.eligible ? 'text-green-600' : 'text-red-600'"></span>
									</div>
									<p class="text-sm mt-1" x-text="option.description"></p>
									<p class="text-sm mt-1" x-text="'Required Points: ' + option.required_points"></p>
								</div>
							</template>
						</div>
						<!-- Letter of Advice Section -->
						<div class="mt-8 bg-white p-6 border rounded shadow" x-show="results.advice_letter">
							<div class="flex justify-between mb-4">
								<div>
									<p class="text-sm" x-text="results.advice_letter.date"></p>
									<p class="text-sm" x-text="'Ref: ' + results.advice_letter.reference"></p>
								</div>
								<button @click="window.print()" class="text-blue-600 hover:text-blue-800">
									Print Letter
								</button>
							</div>

							<h2 class="text-xl font-bold mb-4" x-text="results.advice_letter.subject"></h2>

							<p class="mb-4" x-text="results.advice_letter.greeting"></p>

							<p class="mb-4" x-text="results.advice_letter.introduction"></p>

							<p class="mb-4" x-text="results.advice_letter.points_summary"></p>

							<h3 class="font-bold mb-2">Detailed Assessment</h3>
							<pre class="whitespace-pre-wrap mb-4 text-sm" x-text="results.advice_letter.detailed_assessment"></pre>

							<h3 class="font-bold mb-2">Visa Options</h3>
							<pre class="whitespace-pre-wrap mb-4 text-sm" x-text="results.advice_letter.visa_recommendations"></pre>

							<p class="mb-4" x-text="results.advice_letter.conclusion"></p>

							<p class="mb-4" x-text="results.advice_letter.closing"></p>

							<div class="mt-8">
								<p x-text="results.advice_letter.signature.regards"></p>
								<p x-text="results.advice_letter.signature.name"></p>
								<p class="text-sm" x-text="results.advice_letter.signature.title"></p>
							</div>
						</div>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <script>
        function calculator() {
            return {
                formData: {
                    age: '',
                    english_level: 'competent',
                    education: 'bachelors',
                    work_experience: 0,
                    australian_work_exp: 0,
                    professional_year: false,
                    australian_study: false,
                    partner_skills: false,
                    regional_study: false
                },
                results: null,
                async calculatePoints() {
                    try {
                        const response = await fetch('/calculate-pr-points', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify(this.formData)
                        });

                        if (!response.ok) {
                            const errorData = await response.json();
                            console.error('Validation errors:', errorData);
                            return;
                        }

                        this.results = await response.json();
                    } catch (error) {
                        console.error('Error calculating points:', error);
                    }
                },
                formatCategory(category) {
                    return category
                        .split('_')
                        .map(word => word.charAt(0).toUpperCase() + word.slice(1))
                        .join(' ');
                }
            }
        }
    </script>


@endsection

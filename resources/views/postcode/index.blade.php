@extends('layouts.frontend')
@section('seoinfo')
	<title>Postcode Checker</title>
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
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-center">Postcode & Suburb Checker</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('postcode.check') }}" method="GET">
                            @csrf
                            <div class="mb-3">
                                <label for="search" class="form-label">Enter Suburb or Postcode:</label>
                                <input type="text" class="form-control @error('search') is-invalid @enderror"
                                    id="search" name="search" value="{{ old('search', $search ?? '') }}"
                                    placeholder="e.g. 2000 or Sydney">
                                @error('search')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Search</button>
                            </div>
                        </form>

                        @if(isset($results))
                            <div class="mt-4">
                                <h4>Results:</h4>
                                @if(count($results) > 0)
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Suburb</th>
                                                <th>Postcode</th>
                                                <th>State</th>
                                                <th>Category</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($results as $result)
                                                <tr>
                                                    <td>{{ $result['suburb'] }}</td>
                                                    <td>{{ $result['postcode'] }}</td>
                                                    <td>{{ $result['state'] }}</td>
                                                    <td>{{ $result['category'] }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <div class="alert alert-warning">No results found.</div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const input = document.getElementById('search');
        const suggestionBox = document.createElement('div');
        suggestionBox.className = 'list-group position-absolute w-100';
        suggestionBox.style.zIndex = 1000;
        input.parentNode.appendChild(suggestionBox);

        input.addEventListener('input', function() {
            const query = this.value;
            if (query.length < 2) {
                suggestionBox.innerHTML = '';
                return;
            }
            fetch(`/suggest?q=${encodeURIComponent(query)}`)
                .then(res => res.json())
                .then(data => {
                    suggestionBox.innerHTML = '';
                    data.forEach(item => {
                        const option = document.createElement('button');
                        option.type = 'button';
                        option.className = 'list-group-item list-group-item-action';
                        option.textContent = `${item.suburb} (${item.postcode}, ${item.state})`;
                        option.onclick = () => {
                            input.value = item.suburb;
                            suggestionBox.innerHTML = '';
                        };
                        suggestionBox.appendChild(option);
                    });
                });
        });

        document.addEventListener('click', function(e) {
            if (!suggestionBox.contains(e.target) && e.target !== input) {
                suggestionBox.innerHTML = '';
            }
        });
    });
    </script>
@endsection

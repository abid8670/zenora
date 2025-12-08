
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Submit a Support Ticket - Zenora</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/feather-icons"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f7fafc;
        }
        .form-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
        }
        .input-with-icon {
            padding-left: 3rem !important;
        }
        .alert-banner {
            animation: fadeIn 0.5s ease-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="antialiased">

    <div class="container mx-auto px-4 py-8 md:py-16">

        <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="p-8 md:p-12">

                <div class="text-center mb-10">
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-800 tracking-tight">Submit a Support Request</h1>
                    <p class="text-gray-500 mt-3">We are here to help. Please fill out the form below.</p>
                </div>

                <!-- Success Message -->
                @if (session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-800 p-6 my-8 rounded-lg text-center alert-banner" role="alert">
                        <p class="font-bold text-xl mb-2">Success!</p>
                        <p>{{ session('success') }}</p>
                        <div class="mt-6">
                             <a href="{{ route('support-ticket.create') }}" class="inline-block text-white font-bold py-3 px-8 rounded-lg transition duration-300 transform hover:scale-105" style="background-image: linear-gradient(to right, #667eea 0%, #764ba2 100%); box-shadow: 0 10px 20px -10px rgba(118, 75, 162, 0.5);">Submit Another Ticket</a>
                        </div>
                    </div>
                @endif

                <!-- Validation Errors -->
                @if ($errors->any() && !session('success'))
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-800 p-4 mb-6 rounded-md alert-banner" role="alert">
                        <p class="font-bold">Oops! There were some issues.</p>
                        <ul class="mt-2 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (!session('success'))
                    <form action="{{ route('support-ticket.store') }}" method="POST" class="space-y-6">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                            <div class="relative">
                                <i data-feather="user" class="form-icon"></i>
                                <input type="text" name="full_name" id="full_name" placeholder="Your Full Name" value="{{ old('full_name') }}" class="input-with-icon w-full py-4 px-4 bg-gray-50 rounded-lg border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#667eea] transition" required>
                            </div>
                            <div class="relative">
                                <i data-feather="mail" class="form-icon"></i>
                                <input type="email" name="email" id="email" placeholder="Your Email Address" value="{{ old('email') }}" class="input-with-icon w-full py-4 px-4 bg-gray-50 rounded-lg border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#667eea] transition" required>
                            </div>
                            <div class="relative">
                                <i data-feather="map-pin" class="form-icon"></i>
                                <select name="office_id" id="office_id" class="input-with-icon w-full py-4 px-4 bg-gray-50 rounded-lg border-gray-200 appearance-none focus:outline-none focus:ring-2 focus:ring-[#667eea] transition" required>
                                    <option value="" disabled selected>Select Office Location</option>
                                    @foreach($offices as $office)
                                        <option value="{{ $office->id }}" {{ old('office_id') == $office->id ? 'selected' : '' }}>{{ $office->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="relative">
                                <i data-feather="tag" class="form-icon"></i>
                                <select name="support_type_id" id="support_type_id" class="input-with-icon w-full py-4 px-4 bg-gray-50 rounded-lg border-gray-200 appearance-none focus:outline-none focus:ring-2 focus:ring-[#667eea] transition" required>
                                    <option value="" disabled selected>Select Support Category</option>
                                    @foreach($supportTypes as $type)
                                        <option value="{{ $type->id }}" {{ old('support_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="relative pt-2">
                            <i data-feather="edit-3" class="form-icon"></i>
                            <input type="text" name="title" id="title" placeholder="Ticket Title" value="{{ old('title') }}" class="input-with-icon w-full py-4 px-4 bg-gray-50 rounded-lg border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#667eea] transition" required>
                        </div>
                        <div class="relative pt-2">
                            <i data-feather="message-square" class="form-icon" style="top: 1.7rem; transform: translateY(0);"></i>
                            <textarea name="description" id="description" rows="5" placeholder="Describe your issue in detail..." class="input-with-icon w-full py-4 px-4 bg-gray-50 rounded-lg border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#667eea] transition" required>{{ old('description') }}</textarea>
                        </div>
                        <div class="text-center pt-6">
                            <button type="submit" class="w-full md:w-auto text-white font-bold py-4 px-12 rounded-lg transition duration-300 transform hover:scale-105" style="background-image: linear-gradient(to right, #667eea 0%, #764ba2 100%); box-shadow: 0 10px 20px -10px rgba(118, 75, 162, 0.5);">Submit Your Ticket</button>
                        </div>
                    </form>
                @endif
                 <div class="text-center mt-8">
                    <a href="{{ route('landing') }}" class="text-sm text-gray-500 hover:text-gray-700 transition">Back to Home</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        feather.replace()
    </script>
</body>
</html>

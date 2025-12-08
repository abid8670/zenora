
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit a Support Ticket</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/heroicons/2.1.3/24/outline/heroicons.min.css">
    <style>
        body {
            background-color: #f3f4f6;
        }
        .noise-texture {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            opacity: 0.025;
            z-index: -1;
            pointer-events: none;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.65' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)'/%3E%3C/svg%3E");
        }
        .form-container {
            background-color: white;
            padding: 2.5rem;
            border-radius: 1rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            max-width: 48rem;
            margin: 4rem auto;
        }
        .form-title {
            font-size: 2.25rem;
            font-weight: 800;
            color: #111827;
            margin-bottom: 1.5rem;
        }
        .form-input, .form-select {
            width: 100%;
            border-radius: 0.5rem;
            border: 1px solid #d1d5db;
            padding: 0.75rem 1rem;
            transition: box-shadow 0.2s, border-color 0.2s;
        }
        .form-input:focus, .form-select:focus {
            outline: none;
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.3);
        }
        .submit-button {
            background-color: #4f46e5;
            color: white;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            transition: background-color 0.2s, box-shadow 0.2s;
            box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.2), 0 2px 4px -1px rgba(79, 70, 229, 0.1);
        }
        .submit-button:hover {
            background-color: #4338ca;
            box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.3), 0 4px 6px -2px rgba(79, 70, 229, 0.2);
        }
        .success-message {
            background-color: #d1fae5;
            color: #065f46;
            padding: 1rem;
            border-radius: 0.5rem;
            border: 1px solid #6ee7b7;
            margin-top: 1.5rem;
        }
    </style>
</head>
<body>
    <div class="noise-texture"></div>
    <div class="form-container">
        <h1 class="form-title">Submit a Support Ticket</h1>
        <p class="mb-8 text-gray-600">Please fill out the form below and we will get back to you as soon as possible.</p>
        
        @if(session('success'))
            <div class="success-message">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('support-ticket.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block mb-2 font-medium text-gray-700">Full Name</label>
                    <input type="text" id="name" name="name" class="form-input" value="{{ old('name') }}" required>
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="email" class="block mb-2 font-medium text-gray-700">Email Address</label>
                    <input type="email" id="email" name="email" class="form-input" value="{{ old('email') }}" required>
                     @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="office_id" class="block mb-2 font-medium text-gray-700">Office Location</label>
                    <select id="office_id" name="office_id" class="form-select" required>
                        <option value="">Select an office</option>
                        @foreach($offices as $office)
                            <option value="{{ $office->id }}" {{ old('office_id') == $office->id ? 'selected' : '' }}>{{ $office->name }}</option>
                        @endforeach
                    </select>
                     @error('office_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="support_type_id" class="block mb-2 font-medium text-gray-700">Support Category</label>
                    <select id="support_type_id" name="support_type_id" class="form-select" required>
                        <option value="">Select a category</option>
                        @foreach($supportTypes as $type)
                            <option value="{{ $type->id }}" {{ old('support_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                        @endforeach
                    </select>
                    @error('support_type_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="md:col-span-2">
                    <label for="title" class="block mb-2 font-medium text-gray-700">Subject</label>
                    <input type="text" id="title" name="title" class="form-input" value="{{ old('title') }}" required>
                    @error('title')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="md:col-span-2">
                    <label for="description" class="block mb-2 font-medium text-gray-700">Problem Description</label>
                    <textarea id="description" name="description" rows="4" class="form-input" required>{{ old('description') }}</textarea>
                    @error('description')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
            <div class="mt-8 text-right">
                <button type="submit" class="submit-button">Submit Ticket</button>
            </div>
        </form>
    </div>
</body>
</html>

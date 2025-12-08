
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Zenora - Your Ultimate Support Solution</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f2f5;
        }
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .btn {
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        .btn-primary {
            background-color: #ffffff;
            color: #764ba2;
        }
        .btn-secondary {
            background-color: transparent;
            color: #ffffff;
            border: 2px solid #ffffff;
        }
    </style>
</head>
<body class="antialiased">
    <div class="relative flex items-center justify-center min-h-screen bg-gray-100 dark:bg-gray-900">
        <div class="w-full max-w-4xl mx-auto p-6 lg:p-8">
            <div class="hero-section text-white rounded-3xl shadow-2xl overflow-hidden">
                <div class="px-8 py-20 lg:px-12 lg:py-24">
                    <div class="text-center">
                        <svg class="mx-auto h-20 w-20 text-white mb-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 20.944A12.02 12.02 0 0012 21a12.02 12.02 0 009-8.056 11.955 11.955 0 00-2.382-8.984z" />
                        </svg>
                        <h1 class="text-4xl md:text-6xl font-bold tracking-tight mb-4">
                            Welcome to Zenora
                        </h1>
                        <p class="text-lg md:text-xl text-gray-200 max-w-2xl mx-auto mb-10">
                            Your unified platform for seamless administration and exceptional customer support.
                        </p>
                        <div class="flex flex-col sm:flex-row items-center justify-center space-y-4 sm:space-y-0 sm:space-x-6">
                            <a href="{{ url('/zenora') }}" class="btn btn-primary font-semibold py-3 px-8 rounded-full text-lg">
                                Admin Login
                            </a>
                            <a href="{{ route('support-ticket.create') }}" class="btn btn-secondary font-semibold py-3 px-8 rounded-full text-lg">
                                Get Support
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-center mt-12 px-6 sm:items-center sm:justify-between">
                <div class="text-sm text-center text-gray-500 dark:text-gray-400 sm:text-left">
                    <div class="flex items-center gap-4">
                        <a href="https://laravel.com/docs" class="group inline-flex items-center hover:text-gray-700 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                           Powered by Laravel
                        </a>
                    </div>
                </div>

                <div class="ml-4 text-sm text-center text-gray-500 dark:text-gray-400 sm:text-right sm:ml-0">
                    Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
                </div>
            </div>
        </div>
    </div>
</body>
</html>

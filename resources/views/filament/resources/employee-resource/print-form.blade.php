<div id="printable-content" class="p-6 bg-white dark:bg-gray-800">

    <style>
        /* Screen-specific styles for dark mode content */
        .dark .dark\:text-white {
            color: #fff;
        }
        .dark .dark\:text-gray-300 {
            color: #d1d5db;
        }
        .dark .dark\:border-gray-600 {
            border-color: #4b5563;
        }
        .dark .dark\:bg-gray-700 {
            background-color: #374151;
        }

        @media print {
            body * {
                visibility: hidden;
            }
            #printable-content, #printable-content * {
                visibility: visible;
            }
            #printable-content {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                padding: 0;
                margin: 0;
                /* Force light mode for printing */
                background-color: #ffffff !important;
                color: #000000 !important;
            }
            /* Ensure all text inside is black for printing */
            #printable-content h1, #printable-content h2, #printable-content p, #printable-content div, #printable-content strong, #printable-content th, #printable-content td {
                color: #000000 !important;
            }
            /* Ensure table has proper borders for printing */
            .assets-table th, .assets-table td {
                border: 1px solid #ccc !important;
                background-color: #f9fafb !important;
            }
            .no-print {
                display: none;
            }
        }
        .details-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 1rem;
        }
        .assets-table {
            width: 100%;
            margin-top: 1.5rem;
            border-collapse: collapse;
        }
        .assets-table th, .assets-table td {
            padding: 0.75rem;
            border: 1px solid #e5e7eb; /* Light mode border */
            text-align: left;
        }
        .assets-table th {
            background-color: #f9fafb; /* Light mode header */
        }
    </style>

    {{-- Header --}}
    <div class="text-center mb-8">
        <h1 class="text-2xl font-bold dark:text-white">Employee Asset Handover Form</h1>
        <p class="text-gray-500 dark:text-gray-300">Official record of assets assigned to the employee.</p>
    </div>

    {{-- Employee Details --}}
    <div class="mb-8">
        <h2 class="text-xl font-semibold border-b pb-2 mb-4 dark:text-white dark:border-gray-600">Employee Details</h2>
        <div class="details-grid dark:text-gray-300">
            <div><strong>Name:</strong> {{ $employee->name }}</div>
            <div><strong>Employee ID:</strong> {{ $employee->employee_id }}</div>
            <div><strong>Designation:</strong> {{ $employee->designation }}</div>
            <div><strong>Department:</strong> {{ $employee->department }}</div>
            <div><strong>Office:</strong> {{ $employee->office->name }}</div>
        </div>
    </div>

    {{-- Assigned Assets --}}
    <div>
        <h2 class="text-xl font-semibold border-b pb-2 mb-4 dark:text-white dark:border-gray-600">Currently Assigned Assets</h2>
        @php
            $currentAssets = $employee->assetAssignmentLogs->whereNull('returned_date');
        @endphp

        @if($currentAssets->count() > 0)
            <table class="assets-table">
                <thead class="dark:bg-gray-700">
                    <tr>
                        <th class="dark:text-white">Asset Name</th>
                        <th class="dark:text-white">Serial Number</th>
                        <th class="dark:text-white">Assigned Date</th>
                    </tr>
                </thead>
                <tbody class="dark:text-gray-300">
                    @foreach($currentAssets as $log)
                        <tr>
                            <td>{{ $log->asset->name }}</td>
                            <td>{{ $log->asset->serial_number }}</td>
                            <td>{{ \Carbon\Carbon::parse($log->assigned_date)->format('d-M-Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="mt-4 text-gray-600 dark:text-gray-400">No assets are currently assigned to this employee.</p>
        @endif
    </div>

    {{-- Signature Section --}}
    <div class="mt-16 grid grid-cols-2 gap-16 dark:text-gray-300">
        <div>
            <div class="border-t pt-2 dark:border-gray-600"><strong>Employee Signature</strong></div>
        </div>
        <div>
            <div class="border-t pt-2 dark:border-gray-600"><strong>Authorized Signature (IT Dept)</strong></div>
        </div>
    </div>

    {{-- Print Button (will not be printed) --}}
    <div class="no-print text-right mt-8">
        <button onclick="window.print()" class="filament-button filament-button-size-md inline-flex items-center justify-center py-1 gap-1 font-medium rounded-lg border transition-colors outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset min-h-[2.25rem] px-4 text-sm text-white shadow focus:ring-white border-transparent bg-primary-600 hover:bg-primary-500 focus:bg-primary-700 focus:ring-offset-primary-700">
            <svg class="w-5 h-5 mr-1 -ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6 13.829m0 0c-.34.057-.67.112-1 .168m1.933-2.186c.24.03.48.062.72.096m-1.44.168a44.166 44.166 0 0112 0m-12 0a44.166 44.166 0 00-12 0" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 0" /><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v.01M12 12v.01M12 9v.01M12 6v.01M12 3v.01" /></svg>
            Print Form
        </button>
    </div>
</div>

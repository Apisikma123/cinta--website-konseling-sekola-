@extends('layouts.admin-panel', ['title' => 'Component Showcase'])

@section('content')
<div class="space-y-12">
    <!-- Section: Stat Cards -->
    <section>
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Stat Cards</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <x-admin-panel.stat-card 
                label="Total Reports" 
                value="1,234" 
                icon="file-text"
                subtitle="This month" />
            
            <x-admin-panel.stat-card 
                label="Active Teachers" 
                value="45" 
                icon="users"
                subtitle="Verified" />
            
            <x-admin-panel.stat-card 
                label="Schools" 
                value="12" 
                icon="home"
                subtitle="Registered" />
            
            <x-admin-panel.stat-card 
                label="Completion Rate" 
                value="87%" 
                icon="trending-up"
                subtitle="Average" />
        </div>
    </section>

    <!-- Section: Status Badges -->
    <section>
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Status Badges</h2>
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <div class="flex flex-wrap gap-4">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                    <span class="w-2 h-2 bg-emerald-600 rounded-full mr-2"></span>
                    Active
                </span>
                
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                    <span class="w-2 h-2 bg-yellow-600 rounded-full mr-2"></span>
                    Pending
                </span>
                
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                    <span class="w-2 h-2 bg-red-600 rounded-full mr-2"></span>
                    Inactive
                </span>
                
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    <span class="w-2 h-2 bg-blue-600 rounded-full mr-2"></span>
                    Processing
                </span>
            </div>
        </div>
    </section>

    <!-- Section: Buttons -->
    <section>
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Buttons</h2>
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <div class="flex flex-wrap gap-4">
                <!-- Primary -->
                <button class="px-6 py-2 bg-purple-600 text-white rounded-lg font-medium hover:bg-purple-700 transition-colors duration-200">
                    Primary Button
                </button>
                
                <!-- Secondary -->
                <button class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition-colors duration-200">
                    Secondary Button
                </button>
                
                <!-- Danger -->
                <button class="px-6 py-2 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700 transition-colors duration-200">
                    Delete Button
                </button>
                
                <!-- With Icon -->
                <button class="inline-flex items-center gap-2 px-6 py-2 bg-purple-600 text-white rounded-lg font-medium hover:bg-purple-700 transition-colors duration-200">
                    <i data-feather="plus" class="w-5 h-5"></i>
                    <span>Add New</span>
                </button>
                
                <!-- Disabled -->
                <button disabled class="px-6 py-2 bg-gray-300 text-gray-500 rounded-lg font-medium cursor-not-allowed">
                    Disabled Button
                </button>
            </div>
        </div>
    </section>

    <!-- Section: Form Fields -->
    <section>
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Form Fields</h2>
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <x-admin-panel.form action="#" method="POST" class="space-y-6 max-w-2xl">
                <x-admin-panel.form-field 
                    name="text_field" 
                    label="Text Input" 
                    type="text"
                    placeholder="Enter text..."
                    required />

                <x-admin-panel.form-field 
                    name="email_field" 
                    label="Email Input" 
                    type="email"
                    placeholder="Enter email..."
                    required />

                <x-admin-panel.form-field 
                    name="select_field" 
                    label="Select Field" 
                    type="select"
                    :options="['option1' => 'Option 1', 'option2' => 'Option 2', 'option3' => 'Option 3']"
                    required />

                <x-admin-panel.form-field 
                    name="textarea_field" 
                    label="Textarea" 
                    type="textarea"
                    placeholder="Enter message..."
                    rows="4" />

                <div class="flex gap-3 justify-end pt-6 border-t border-gray-200">
                    <button type="reset" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition-colors duration-200">
                        Reset
                    </button>
                    <button type="submit" class="px-6 py-2 bg-purple-600 text-white rounded-lg font-medium hover:bg-purple-700 transition-colors duration-200">
                        Submit
                    </button>
                </div>
            </x-admin-panel.form>
        </div>
    </section>

    <!-- Section: Table -->
    <section>
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Table Example</h2>
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center font-bold text-sm">
                                        JD
                                    </div>
                                    <span class="text-sm font-medium text-gray-900">John Doe</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">john@example.com</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                    Active
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <div class="flex items-center gap-2">
                                    <a href="#" class="text-blue-600 hover:text-blue-700 font-medium">Edit</a>
                                    <button class="text-red-600 hover:text-red-700 font-medium">Delete</button>
                                </div>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-sm">
                                        JA
                                    </div>
                                    <span class="text-sm font-medium text-gray-900">Jane Anderson</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">jane@example.com</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    Pending
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <div class="flex items-center gap-2">
                                    <a href="#" class="text-blue-600 hover:text-blue-700 font-medium">Edit</a>
                                    <button class="text-red-600 hover:text-red-700 font-medium">Delete</button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- Section: Alerts -->
    <section>
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Alerts</h2>
        <div class="space-y-4">
            <!-- Success Alert -->
            <div class="p-4 rounded-lg border border-emerald-200 bg-emerald-50 flex items-start gap-3">
                <i data-feather="check-circle" class="w-5 h-5 text-emerald-600 flex-shrink-0 mt-0.5"></i>
                <div>
                    <p class="text-sm font-medium text-emerald-900">Success!</p>
                    <p class="text-sm text-emerald-700 mt-1">Your changes have been saved successfully.</p>
                </div>
            </div>

            <!-- Error Alert -->
            <div class="p-4 rounded-lg border border-red-200 bg-red-50 flex items-start gap-3">
                <i data-feather="alert-circle" class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5"></i>
                <div>
                    <p class="text-sm font-medium text-red-900">Error!</p>
                    <p class="text-sm text-red-700 mt-1">Something went wrong. Please try again.</p>
                </div>
            </div>

            <!-- Warning Alert -->
            <div class="p-4 rounded-lg border border-yellow-200 bg-yellow-50 flex items-start gap-3">
                <i data-feather="alert-triangle" class="w-5 h-5 text-yellow-600 flex-shrink-0 mt-0.5"></i>
                <div>
                    <p class="text-sm font-medium text-yellow-900">Warning!</p>
                    <p class="text-sm text-yellow-700 mt-1">Please review this information carefully.</p>
                </div>
            </div>

            <!-- Info Alert -->
            <div class="p-4 rounded-lg border border-blue-200 bg-blue-50 flex items-start gap-3">
                <i data-feather="info" class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5"></i>
                <div>
                    <p class="text-sm font-medium text-blue-900">Information</p>
                    <p class="text-sm text-blue-700 mt-1">This is an informational message for the user.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Section: Empty State -->
    <section>
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Empty State</h2>
        <div class="bg-white rounded-lg border border-gray-200 py-12 text-center">
            <i data-feather="inbox" class="w-12 h-12 text-gray-300 mx-auto mb-3"></i>
            <p class="text-gray-500 font-medium">No data available</p>
            <p class="text-gray-400 text-sm mt-1">Try adjusting your filters or create a new item.</p>
            <button class="mt-4 inline-flex items-center gap-2 px-6 py-2 bg-purple-600 text-white rounded-lg font-medium hover:bg-purple-700 transition-colors duration-200">
                <i data-feather="plus" class="w-5 h-5"></i>
                <span>Create New</span>
            </button>
        </div>
    </section>

    <!-- Section: Loading State -->
    <section>
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Loading State</h2>
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <div class="space-y-4">
                <div class="h-4 bg-gray-200 rounded skeleton"></div>
                <div class="h-4 bg-gray-200 rounded skeleton" style="width: 80%;"></div>
                <div class="h-4 bg-gray-200 rounded skeleton" style="width: 60%;"></div>
            </div>
        </div>
    </section>
</div>

<style>
    .skeleton {
        background: linear-gradient(90deg, #f3f4f6 25%, #e5e7eb 50%, #f3f4f6 75%);
        background-size: 200% 100%;
        animation: loading 1.5s infinite;
    }

    @keyframes loading {
        0% {
            background-position: 200% 0;
        }
        100% {
            background-position: -200% 0;
        }
    }
</style>
@endsection

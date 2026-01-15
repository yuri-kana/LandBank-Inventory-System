<x-app-layout>
    <div class="min-h-screen bg-gray-100 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <div class="text-center">
                <i class="fas fa-check-circle text-green-500 text-6xl mb-4"></i>
                <h2 class="text-3xl font-extrabold text-gray-900">
                    Email Verified Successfully!
                </h2>
            </div>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
                <div class="text-center mb-6">
                    <i class="fas fa-envelope-open-text text-blue-500 text-5xl mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">
                        Welcome to the Inventory System!
                    </h3>
                    <p class="text-gray-600 mb-4">
                        Your email has been verified successfully.
                    </p>
                </div>

                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle text-blue-500"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-700">
                                <strong>Account Details:</strong>
                            </p>
                            <ul class="mt-2 text-sm text-blue-600 space-y-1">
                                <li><strong>Name:</strong> {{ $user->name }}</li>
                                <li><strong>Email:</strong> {{ $user->email }}</li>
                                <li><strong>Team:</strong> {{ $user->team->name ?? 'N/A' }}</li>
                                <li><strong>Status:</strong> <span class="text-green-600 font-semibold">Active</span></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-green-500"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700">
                                Your account is now active. You can log in using your credentials.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mt-6">
                    <a href="{{ route('login') }}" 
                       class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        Go to Login
                    </a>
                </div>

                <div class="mt-4 text-center">
                    <p class="text-sm text-gray-600">
                        Need help? Contact system Inventory Head.
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
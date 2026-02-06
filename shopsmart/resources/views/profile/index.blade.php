@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">My Profile</h1>
            <p class="text-gray-600 mt-1">Manage your account settings and preferences</p>
        </div>
        <a href="{{ route('profile.edit') }}" class="px-4 py-2 text-white rounded-lg" style="background-color: #009245;" onmouseover="this.style.backgroundColor='#007a38'" onmouseout="this.style.backgroundColor='#009245'">Edit Profile</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Profile Card -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="text-center">
                    @if($user && $user->name)
                    <div class="w-24 h-24 rounded-full flex items-center justify-center text-white text-3xl font-bold mx-auto mb-4" style="background-color: #009245;">
                        {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                    </div>
                    <h2 class="text-xl font-bold text-gray-900">{{ $user->name ?? 'User' }}</h2>
                    <p class="text-gray-500 mt-1">{{ $user->email ?? 'No email' }}</p>
                    @if($user->role ?? null)
                    <span class="inline-block mt-2 px-3 py-1 text-xs font-semibold rounded-full" style="background-color: #e6f5ed; color: #009245;">
                        {{ ucfirst($user->role) }}
                    </span>
                    @endif
                    @else
                    <div class="w-24 h-24 bg-gray-300 rounded-full flex items-center justify-center text-white text-3xl font-bold mx-auto mb-4">
                        ?
                    </div>
                    <h2 class="text-xl font-bold text-gray-900">No User Found</h2>
                    <p class="text-gray-500 mt-1">Please log in or create a user account</p>
                    @endif
                </div>
                
                @if($user)
                <div class="mt-6 space-y-3">
                    <div class="flex items-center space-x-3 text-sm">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        <span class="text-gray-600">{{ $user->phone ?? 'Not set' }}</span>
                    </div>
                    <div class="flex items-start space-x-3 text-sm">
                        <svg class="w-5 h-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span class="text-gray-600">{{ $user->address ?? 'Not set' }}</span>
                    </div>
                    <div class="flex items-center space-x-3 text-sm">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span class="text-gray-600">Joined {{ $user->created_at ? $user->created_at->format('M Y') : 'N/A' }}</span>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Profile Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Personal Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Personal Information</h3>
                @if($user)
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Full Name</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $user->name ?? 'Not set' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Email Address</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $user->email ?? 'Not set' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Phone Number</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $user->phone ?? 'Not set' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Address</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $user->address ?? 'Not set' }}</dd>
                    </div>
                    @if($user->bio ?? null)
                    <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">Bio</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $user->bio }}</dd>
                    </div>
                    @endif
                </dl>
                @else
                <p class="text-gray-500">No user information available.</p>
                @endif
            </div>

            <!-- Preferences -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Preferences</h3>
                @if($user)
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Language</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ strtoupper($user->language ?? 'en') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Timezone</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $user->timezone ?? 'UTC' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Date Format</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $user->date_format ?? 'Y-m-d' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Theme</dt>
                        <dd class="mt-1 text-sm text-gray-900 capitalize">{{ $user->theme ?? 'light' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Email Notifications</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ ($user->notifications_email ?? true) ? 'Enabled' : 'Disabled' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">SMS Notifications</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ ($user->notifications_sms ?? false) ? 'Enabled' : 'Disabled' }}</dd>
                    </div>
                </dl>
                @else
                <p class="text-gray-500">No preferences available.</p>
                @endif
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <a href="{{ route('profile.edit') }}" class="flex items-center space-x-3 px-4 py-3 border border-gray-200 rounded-lg hover:bg-gray-50">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        <span class="text-sm font-medium text-gray-700">Edit Profile</span>
                    </a>
                    <a href="{{ route('profile.security') }}" class="flex items-center space-x-3 px-4 py-3 border border-gray-200 rounded-lg hover:bg-gray-50">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        <span class="text-sm font-medium text-gray-700">Change Password</span>
                    </a>
                    <a href="{{ route('profile.activity') }}" class="flex items-center space-x-3 px-4 py-3 border border-gray-200 rounded-lg hover:bg-gray-50">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-sm font-medium text-gray-700">View Activity</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


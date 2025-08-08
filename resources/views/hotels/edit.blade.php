<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold text-gray-800 dark:text-white">
            Edit Hotel
        </h2>
    </x-slot>

    <div class="bg-white dark:bg-gray-900 rounded-lg shadow-md p-6">
        <form method="POST" action="{{ route('hotels.update', $hotel) }}" class="space-y-6">
            @csrf
            @method('PUT')
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                <input type="text" name="name" id="name" value="{{ $hotel->name }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus-ring dark:bg-gray-800 dark:text-white" required>
            </div>
            <div>
                <label for="subdomain" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Subdomain</label>
                <input type="text" name="subdomain" id="subdomain" value="{{ $hotel->subdomain }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus-ring dark:bg-gray-800 dark:text-white" required>
            </div>
            <div>
                <label for="db_database" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Database Name</label>
                <input type="text" name="db_database" id="db_database" value="{{ $hotel->db_database }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus-ring dark:bg-gray-800 dark:text-white" required>
            </div>
            <div>
                <label for="db_username" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Database Username</label>
                <input type="text" name="db_username" id="db_username" value="{{ $hotel->db_username }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus-ring dark:bg-gray-800 dark:text-white" required>
            </div>
            <div>
                <label for="db_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Database Password</label>
                <input type="password" name="db_password" id="db_password" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus-ring dark:bg-gray-800 dark:text-white">
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Leave blank to keep the current password.</p>
            </div>
            <div class="flex justify-end">
                <button type="submit" class="btn">
                    Update Hotel
                </button>
            </div>
        </form>
    </div>
</x-app-layout>

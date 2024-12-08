    {{-- Filter Button --}}
    <div class="p-2">
        <label class="block space-y-1">
            <span class="text-gray-700 text-sm font-semibold block flex items-center space-x-1">
                <span class="material-symbols-outlined">hourglass_top</span>
                <span>Remaining Hours</span>
            </span>
            <div class="relative">
                <form method="GET" action="{{ route('admin.interns') }}">
                    <select name="remaining_hours"
                        class="w-full px-2 py-2 text-gray-700 bg-white border border-gray-200 rounded-lg 
                        shadow-md shadow-gray-400 cursor-pointer hover:border-gray-400 focus:outline-none focus:ring-2 
                        focus:ring-blue-400 focus:border-blue-400 transition-all duration-200"
                        onchange="this.form.submit()">
                        <option value="" {{ request('remaining_hours') == '' ? 'selected' : '' }}>Select time
                            range</option>
                        <option value="50" {{ request('remaining_hours') == '50' ? 'selected' : '' }}>Below 50 hours
                        </option>
                        <option value="100" {{ request('remaining_hours') == '100' ? 'selected' : '' }}>50 - 100
                            hours</option>
                        <option value="150" {{ request('remaining_hours') == '150' ? 'selected' : '' }}>Above 100
                            hours</option>
                    </select>
                </form>
            </div>
        </label>
    </div>

    </div>

    <!-- Interns List Container -->
    <div class="bg-white rounded-xl shadow-gray-400 shadow-lg border border-gray-200">

        @if ($interns->isEmpty())
            <!-- Display when no data is found -->
            <div class="flex flex-col items-center justify-center p-6">
                <img src="{{ asset('images/no-data-found.jpg') }}" alt="No Data Found" class="w-64 h-auto">
                <p class="text-gray-600 mt-4 text-lg">No interns found.</p>
            </div>
        @else
            <!-- Desktop View -->
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-[#FFE047] border-b border-gray-200">
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">
                                <a href="{{ route('admin.interns', ['sort' => 'fname', 'direction' => $sortField === 'fname' && $sortDirection === 'asc' ? 'desc' : 'asc', 'office' => $selectedOffice]) }}"
                                    class="flex items-center hover:text-gray-600">
                                    Name
                                    {{-- <span
                                        class="material-icons ml-auto {{ $sortField === 'fname' ? 'text-black' : 'text-black' }}">
                                        {{ $sortField === 'fname' && $sortDirection === 'asc' ? 'unfold_more' : 'unfold_more' }}
                                    </span> --}}
                                </a>
                            </th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Email</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Office</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">
                                <a href="{{ route('admin.interns', ['sort' => 'remaining_hours', 'direction' => $sortField === 'remaining_hours' && $sortDirection === 'asc' ? 'desc' : 'asc', 'office' => $selectedOffice]) }}"
                                    class="flex items-center hover:text-gray-600">
                                    Remaining Hours
                                    <span
                                        class="material-icons ml-auto {{ $sortField === 'remaining_hours' ? 'text-black' : 'text-black' }}">
                                        {{ $sortField === 'remaining_hours' && $sortDirection === 'asc' ? 'unfold_more' : 'unfold_more' }}
                                    </span>
                                </a>
                            </th>
                            <th class="px-6 py-4 text-right text-sm font-semibold text-gray-900">Actions</th>
                        </tr>
                    </thead>


                    <tbody class="divide-y divide-gray-200">
                        @foreach ($interns as $intern)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        @if ($intern->avatar)
                                            <img src="{{ asset('storage/' . $intern->avatar) }}" alt="Avatar"
                                                class="w-10 h-10 rounded-full object-cover border border-gray-200">
                                        @else
                                            <div
                                                class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center">
                                                <span class="material-icons text-gray-400">person</span>
                                            </div>
                                        @endif
                                        <div class="ml-4">
                                            <div class="font-medium text-gray-900 capitalize">
                                                {{ $intern->user->fname }} {{ $intern->user->lname }} </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $intern->user->email }}</td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex px-2.5 py-1 text-xs font-medium rounded-full bg-blue-50 text-blue-700">
                                        {{ $intern->office->name ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-800 font-semibold">
                                    @if ($intern->remaining_hours > 0)
                                        {{ number_format($intern->remaining_hours, 2) }} hrs
                                    @else
                                        <span class="text-green-500 font-semibold">Completed</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end space-x-2">
                                        <a href="{{ route('admin.editIntern', ['id' => $intern->id]) }}"
                                            class="p-2 rounded-lg text-blue-600 hover:bg-blue-50 transition-colors">
                                            <span class="material-icons">edit</span>
                                        </a>

                                        <button
                                            class="p-2 rounded-lg text-red-600 hover:bg-red-50 transition-colors delete-btn"
                                            data-intern-id="{{ $intern->id }}"
                                            data-intern-name="{{ $intern->user->fname }} {{ $intern->user->lname }}">
                                            <span class="material-icons">delete</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mobile View -->
            <div class="md:hidden divide-y divide-gray-200">
                @foreach ($interns as $intern)
                    <div class="p-4 space-y-4">
                        <!-- Header with Avatar and Name -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                @if ($intern->avatar)
                                    <img src="{{ asset('storage/' . $intern->avatar) }}" alt="Avatar"
                                        class="w-12 h-12 rounded-full object-cover border border-gray-200">
                                @else
                                    <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center">
                                        <span class="material-icons text-gray-400">person</span>
                                    </div>
                                @endif
                                <div>
                                    <div class="font-medium text-gray-900">
                                        {{ $intern->user->lname }}, {{ $intern->user->fname }}
                                    </div>
                                    <div class="text-sm text-gray-600 mt-0.5">
                                        {{ $intern->user->email }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Info Grid -->
                        <div class="grid grid-cols-2 gap-4 py-3">
                            <div class="space-y-1">
                                <div class="text-xs text-gray-500">Office</div>
                                <div
                                    class="inline-flex px-2.5 py-1 text-xs font-medium rounded-full bg-blue-50 text-blue-700">
                                    {{ $intern->office->name ?? 'N/A' }}
                                </div>
                            </div>
                            <div class="space-y-1">
                                <div class="text-xs text-gray-500">Remaining Hours</div>
                                <div class="text-sm text-gray-900">N/A</div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex justify-end space-x-2 pt-3 border-t border-gray-100">
                            <a href="{{ route('admin.editIntern', ['id' => $intern->id]) }}"
                                class="p-2 rounded-lg text-blue-600 hover:bg-blue-50 transition-colors">
                                <span class="material-icons">edit</span>
                            </a>

                            <button class="p-2 rounded-lg text-red-600 hover:bg-red-50 transition-colors delete-btn"
                                data-intern-id="{{ $intern->id }}"
                                data-intern-name="{{ $intern->user->fname }} {{ $intern->user->lname }}">
                                <span class="material-icons">delete</span>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif 
    </div>

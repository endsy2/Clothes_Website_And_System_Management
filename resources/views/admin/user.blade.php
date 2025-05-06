@php
$titles=[
'Id','Full name','Email','Address','Phone Number']
@endphp

<x-admin-layout>
    <h1 class="text-2xl font-bold mb-6">Customer </h1>
    <table class="min-w-full divide-y divide-gray-200 text-sm">
        <thead class="bg-gray-50">
            <tr>
                @foreach ($titles as $title)
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    {{ $title }}
                </th>
                @endforeach
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($customers['data'] as $customer)

            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4 text-gray-700">
                    <a href="/admin/user/{{ $customer['id'] }}">{{ $customer['id']??null }}</a>
                </td>
                <td class="px-6 py-4 text-gray-700"><a
                        href="/admin/user/{{ $customer['id'] }}">{{ $customer['full_name']??null }}</a></td>
                <td class="px-6 py-4 text-gray-700"><a
                        href="/admin/user/{{ $customer['id'] }}">{{ $customer['email']??null }}</a>
                </td>
                <td class="px-6 py-4 text-gray-700"><a
                        href="/admin/user/{{ $customer['id'] }}">{{ $customer['address']??null }}</a>
                </td>
                <td class="px-6 py-4 text-gray-700"><a
                        href="/admin/user/{{ $customer['id'] }}">{{ $customer['phone_number']??null }}</a></td>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <!-- Pagination -->
    <div class="p-4 border-t flex justify-center space-x-1 bg-white">
        @foreach ($customers['links'] as $link)
        @if ($link['url'])
        <a href="{{ $link['url'] }}"
            class="px-3 py-1 border rounded text-sm {{ $link['active'] ? 'bg-blue-500 text-white' : 'bg-white text-gray-700 hover:bg-gray-100' }}">
            {!! $link['label'] !!}
        </a>
        @else
        <span class="px-3 py-1 border rounded text-sm text-gray-400 cursor-not-allowed">{!!
            $link['label']
            !!}</span>
        @endif
        @endforeach
    </div>
</x-admin-layout>
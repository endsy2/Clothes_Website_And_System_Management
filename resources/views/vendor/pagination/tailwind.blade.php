@if ($paginator->hasPages())
<nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex justify-center my-6">
    <ul class="inline-flex items-center gap-1">

        {{-- Previous --}}
        @if (!$paginator->onFirstPage())
        <li>
            <a href="{{ $paginator->previousPageUrl() }}"
                class="px-3 py-1 rounded-md bg-white text-black border border-gray-300 hover:bg-gray-100 transition"
                aria-label="{{ __('pagination.previous') }}">
                &laquo;
            </a>
        </li>
        @endif

        {{-- Page Numbers --}}
        @foreach ($elements as $element)
        @if (is_string($element))
        <li>
            <span class="px-3 py-1 rounded-md bg-white text-black border border-gray-300 cursor-default">
                {{ $element }}
            </span>
        </li>
        @endif

        @if (is_array($element))
        @foreach ($element as $page => $url)
        @if ($page == $paginator->currentPage())
        <li>
            <span class="px-3 py-1 rounded-md bg-black text-white border border-black">
                {{ $page }}
            </span>
        </li>
        @else
        <li>
            <a href="{{ $url }}"
                class="px-3 py-1 rounded-md bg-white text-black border border-gray-300 hover:bg-gray-100 transition"
                aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                {{ $page }}
            </a>
        </li>
        @endif
        @endforeach
        @endif
        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())
        <li>
            <a href="{{ $paginator->nextPageUrl() }}"
                class="px-3 py-1 rounded-md bg-white text-black border border-gray-300 hover:bg-gray-100 transition"
                aria-label="{{ __('pagination.next') }}">
                &raquo;
            </a>
        </li>
        @endif

    </ul>
</nav>
@endif
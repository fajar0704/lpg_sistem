@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex flex-col sm:flex-row items-center justify-between gap-3 w-full">
        {{-- Result Count Summary --}}
        <div class="text-xs sm:text-sm text-slate-500 font-medium text-center sm:text-left">
            Menampilkan
            @if ($paginator->firstItem())
                <span class="font-bold text-slate-800">{{ $paginator->firstItem() }}</span>
                sampai
                <span class="font-bold text-slate-800">{{ $paginator->lastItem() }}</span>
            @else
                {{ $paginator->count() }}
            @endif
            dari
            <span class="font-bold text-slate-800">{{ $paginator->total() }}</span>
            hasil
        </div>

        {{-- Page Numbers & Next/Previous Links --}}
        <div class="flex items-center justify-center">
            <span class="inline-flex shadow-xs rounded-xl overflow-hidden border border-slate-200 bg-white">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <span aria-disabled="true" aria-label="Sebelumnya">
                        <span class="inline-flex items-center px-2.5 sm:px-3 py-2 text-xs sm:text-sm font-semibold text-slate-300 bg-slate-50 cursor-not-allowed border-r border-slate-200 h-9" aria-hidden="true">
                            <svg class="w-4 h-4 mr-0.5 sm:mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            <span class="hidden xs:inline">Sebelumnya</span>
                        </span>
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center px-2.5 sm:px-3 py-2 text-xs sm:text-sm font-semibold text-slate-600 bg-white hover:bg-slate-50 border-r border-slate-200 hover:text-blue-600 transition h-9" aria-label="Sebelumnya">
                        <svg class="w-4 h-4 mr-0.5 sm:mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                        <span class="hidden xs:inline">Sebelumnya</span>
                    </a>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <span aria-disabled="true">
                            <span class="inline-flex items-center px-2.5 sm:px-3 py-2 text-xs sm:text-sm font-semibold text-slate-400 bg-slate-50 border-r border-slate-200 h-9 cursor-default">{{ $element }}</span>
                        </span>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span aria-current="page">
                                    <span class="inline-flex items-center px-2.5 sm:px-3 py-2 text-xs sm:text-sm font-extrabold text-blue-600 bg-blue-50 border-r border-slate-200 h-9 cursor-default">{{ $page }}</span>
                                </span>
                            @else
                                <a href="{{ $url }}" class="inline-flex items-center px-2.5 sm:px-3 py-2 text-xs sm:text-sm font-semibold text-slate-600 bg-white hover:bg-slate-50 border-r border-slate-200 hover:text-blue-600 transition h-9" aria-label="Halaman {{ $page }}">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center px-2.5 sm:px-3 py-2 text-xs sm:text-sm font-semibold text-slate-600 bg-white hover:bg-slate-50 hover:text-blue-600 transition h-9" aria-label="Next">
                        <span class="hidden xs:inline">Next</span>
                        <svg class="w-4 h-4 ml-0.5 sm:ml-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </a>
                @else
                    <span aria-disabled="true" aria-label="Next">
                        <span class="inline-flex items-center px-2.5 sm:px-3 py-2 text-xs sm:text-sm font-semibold text-slate-300 bg-slate-50 cursor-not-allowed h-9" aria-hidden="true">
                            <span class="hidden xs:inline">Next</span>
                            <svg class="w-4 h-4 ml-0.5 sm:ml-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </span>
                    </span>
                @endif
            </span>
        </div>
    </nav>
@endif

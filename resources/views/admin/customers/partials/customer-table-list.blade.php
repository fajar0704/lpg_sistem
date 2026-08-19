<div id="customer-table-container" class="transition-opacity duration-300 ease-in-out">
    <div class="overflow-x-auto">
        <table class="w-full min-w-max text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/75 border-b border-slate-100">
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Nama Lengkap</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">NIK (Nomor KTP)</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">No. Telepon</th>
                    @if(request('source', 'pangkalan') !== 'sub_pangkalan')
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Kategori</th>
                    @endif
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm">
                @forelse($customers as $customer)
                <tr class="hover:bg-slate-50/40 transition duration-150">
                    <!-- Nama -->
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-sm border border-blue-100">
                                {{ strtoupper(substr($customer->name, 0, 1)) }}
                            </div>
                            <div>
                                <span class="font-bold text-slate-800 block">{{ $customer->name }}</span>
                                @if($customer->sub_pangkalan_id)
                                    <span class="text-[10px] text-blue-600 font-bold block mt-0.5">Pengecer: {{ $customer->subPangkalan->name ?? '-' }}</span>
                                @endif
                            </div>
                        </div>
                    </td>

                    <!-- KTP -->
                    <td class="px-6 py-4 font-mono font-semibold text-slate-700 whitespace-nowrap">
                        {{ $customer->ktp }}
                    </td>

                    <!-- Telepon -->
                    <td class="px-6 py-4 text-slate-600 whitespace-nowrap font-medium">
                        {{ $customer->phone ?? '-' }}
                    </td>

                    <!-- Kategori -->
                    @if(request('source', 'pangkalan') !== 'sub_pangkalan')
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if(is_null($customer->sub_pangkalan_id))
                            @if($customer->category === 'rumah_tangga')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-100">
                                    🏠 Rumah Tangga
                                </span>
                            @elseif($customer->category === 'usaha_mikro')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-purple-50 text-purple-700 border border-purple-100">
                                    🏪 Usaha Mikro
                                </span>
                            @elseif($customer->category === 'pengecer')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                    🏢 Sub Pangkalan
                                </span>
                            @elseif($customer->category === 'konsumen_umum')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                    🏢 Konsumen Umum
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-50 text-slate-700 border border-slate-100">
                                    ❓ {{ $customer->category_label }}
                                </span>
                            @endif
                        @else
                            <span class="text-slate-400 italic text-xs font-semibold">-</span>
                        @endif
                    </td>
                    @endif

                    <!-- Aksi -->
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <div class="flex items-center justify-center gap-3">
                            @if(request('source', 'pangkalan') === 'sub_pangkalan')
                            <button type="button" 
                                onclick="openCustomerModal('{{ addslashes($customer->name) }}', '{{ $customer->category_label }}', '{{ $customer->ktp }}', '{{ $customer->phone ?? '-' }}', '{{ addslashes($customer->address ?? '-') }}', '{{ $customer->photo ? asset('storage/' . $customer->photo) : '' }}', '{{ $customer->kk_photo ? asset('storage/' . $customer->kk_photo) : '' }}')"
                                style="background-color: #2563eb !important; color: #ffffff !important;"
                                class="inline-flex items-center gap-1 text-xs font-bold px-3 py-1.5 rounded-lg hover:bg-blue-700 transition cursor-pointer shadow-sm">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <span>Lihat Detail</span>
                            </button>
                            @else
                            <a href="{{ route('admin.customers.show', $customer) }}" class="inline-flex items-center gap-1 text-xs font-bold text-emerald-600 hover:text-emerald-800 transition" title="Detail">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <span>Detail</span>
                            </a>

                            <a href="{{ route('admin.customers.edit', $customer) }}" class="inline-flex items-center gap-1 text-xs font-bold text-blue-600 hover:text-blue-800 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                <span>Ubah</span>
                            </a>

                            <form action="{{ route('admin.customers.destroy', $customer) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pelanggan ini?')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center gap-1 text-xs font-bold text-rose-600 hover:text-rose-800 transition cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    <span>Hapus</span>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="{{ request('source', 'pangkalan') === 'sub_pangkalan' ? 4 : 5 }}" class="px-6 py-12 text-center text-slate-500">
                        <div class="flex flex-col items-center justify-center">
                            <svg class="w-12 h-12 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <span class="font-bold text-slate-700 text-sm">Tidak Ada Data Pelanggan</span>
                            <span class="text-xs text-slate-400 mt-1 max-w-xs font-medium">Belum ada data pelanggan yang terdaftar atau tidak ada data yang cocok dengan pencarian Anda.</span>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($customers->hasPages())
    <div class="p-4 border-t border-slate-100 bg-slate-50/50">
        {{ $customers->links() }}
    </div>
    @endif
</div>

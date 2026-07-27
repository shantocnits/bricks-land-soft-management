{{-- ==================== PRINT PREVIEW MODAL (Single Invoice) ==================== --}}
@if($showPrintModal && $printChallan)
<div class="fixed inset-0 z-[9999] flex items-center justify-center p-4 sm:p-6 bg-slate-900/60 backdrop-blur-sm transition-opacity overflow-y-auto" x-cloak>
    <div class="relative w-full max-w-4xl bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-gray-200 dark:border-slate-800 my-8 overflow-hidden transition-all" @click.away="$wire.closePrintModal()">
        
        <!-- Top Options Bar -->
        <div class="relative bg-slate-950 p-4 border-b border-slate-800 flex flex-col items-center gap-3 text-white">
            
            <!-- Close Modal Cross Button at Top Right -->
            <button type="button" wire:click="closePrintModal" 
                    class="absolute top-3.5 right-4 p-1.5 rounded-xl text-gray-400 hover:text-white hover:bg-slate-800 transition-colors cursor-pointer"
                    title="বন্ধ করুন">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>

            <!-- Centered Header Title -->
            <h3 class="text-xs font-bold text-slate-300 uppercase tracking-wide text-center">
                চালান প্রিন্ট লেআউট নির্বাচন করুন
            </h3>
            
            <!-- 4 Horizontal Serial Print Action Buttons -->
            <div class="flex flex-wrap sm:flex-nowrap items-center justify-center gap-2.5 w-full pr-8 sm:pr-0">
                <!-- 1. A4 Customer -->
                <button type="button" 
                        onclick="printChallanArea('print-a4-customer')"
                        class="flex-1 sm:flex-initial px-4 py-2 bg-[#059669] hover:bg-[#047857] text-white text-xs font-bold rounded-xl shadow-md transition-all flex items-center justify-center gap-1.5 cursor-pointer active:scale-95 whitespace-nowrap">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                    A4 (কাস্টমার)
                </button>

                <!-- 2. A4 Both -->
                <button type="button" 
                        onclick="printChallanArea('print-a4-dual')"
                        class="flex-1 sm:flex-initial px-4 py-2 bg-[#034C3C] hover:bg-[#023E31] text-white text-xs font-bold rounded-xl shadow-md transition-all flex items-center justify-center gap-1.5 cursor-pointer active:scale-95 whitespace-nowrap">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                    A4 (কাস্টমার+অফিস)
                </button>

                <!-- 3. POS Customer -->
                <button type="button" 
                        onclick="printChallanArea('print-pos-customer')"
                        class="flex-1 sm:flex-initial px-4 py-2 bg-[#E05A16] hover:bg-[#BE4B11] text-white text-xs font-bold rounded-xl shadow-md transition-all flex items-center justify-center gap-1.5 cursor-pointer active:scale-95 whitespace-nowrap">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                    POS (কাস্টমার)
                </button>

                <!-- 4. POS Both -->
                <button type="button" 
                        onclick="printChallanArea('print-pos-dual')"
                        class="flex-1 sm:flex-initial px-4 py-2 bg-[#D97706] hover:bg-[#B45309] text-white text-xs font-bold rounded-xl shadow-md transition-all flex items-center justify-center gap-1.5 cursor-pointer active:scale-95 whitespace-nowrap">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                    POS (কাস্টমার+অফিস)
                </button>
            </div>
        </div>

        <!-- On-Screen Modal Fixed Preview Area -->
        <div class="p-4 sm:p-6 bg-slate-950 max-h-[78vh] overflow-y-auto">
            <div class="bg-white p-6 sm:p-8 rounded-2xl border border-gray-200 text-gray-900 space-y-6 shadow-xl max-w-3xl mx-auto">
                <div class="flex justify-between items-start border-b border-gray-200 pb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-14 h-14 rounded-xl bg-red-50 flex items-center justify-center p-2 shrink-0 border border-red-100">
                            <svg class="w-10 h-10 text-red-500" viewBox="0 0 64 64" fill="currentColor">
                                <path d="M26 12 L38 12 L44 50 L20 50 Z" fill="#EF4444" />
                                <path d="M22 24 L42 24 L44 32 L20 32 Z" fill="#FFFFFF" />
                                <path d="M12 50 L52 50 L56 58 L8 58 Z" fill="#EF4444" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-black text-gray-900 tracking-wide">জয়া ব্রিকস</h2>
                            <p class="text-xs text-gray-600 font-medium">বিলমারিয়া,বাগাতিপাড়া,নাটোর</p>
                            <p class="text-xs text-gray-600 font-mono">০১৭২৩-৬৭৫-১৬৭, ০১৭২৮-৭৬৭-৯৫৫</p>
                            <p class="text-xs font-bold text-gray-800">প্রোপাইটরঃ মেঃ জহুরুল ইসলাম</p>
                        </div>
                    </div>
                    <div class="text-right space-y-1">
                        <h1 class="text-3xl font-black text-gray-900 uppercase tracking-wider font-mono">INVOICE</h1>
                        <p class="text-xs font-semibold text-gray-500">কাস্টমার কপি</p>
                        <p class="text-[11px] text-gray-400 font-mono">প্রিন্ট তারিখ: {{ now()->format('d-m-Y h:i A') }}</p>
                    </div>
                </div>

                <div class="flex justify-between items-start text-xs bg-gray-50 p-4 rounded-xl border border-gray-200">
                    <div class="space-y-1.5">
                        <p class="text-gray-700"><span class="font-bold text-gray-900">লেজার আইডি:</span> {{ $printChallan->ledger_id ?: '—' }}</p>
                        <p class="text-gray-700"><span class="font-bold text-gray-900">চালানের তারিখ:</span> {{ $printChallan->date ? \Carbon\Carbon::parse($printChallan->date)->format('d-m-Y') : now()->format('d-m-Y') }}, {{ now()->format('A h:i:s') }}</p>
                        <p class="text-gray-700"><span class="font-bold text-gray-900">কাস্টমার প্রোফাইল:</span></p>
                    </div>
                    <div class="text-right space-y-1 pl-4 border-r-4 border-black pr-2">
                        <p class="text-gray-900 font-black text-sm">{{ $printChallan->customer_name }}</p>
                        <p class="text-gray-600 font-semibold">{{ $printChallan->customer_address ?: 'ঘোড়াঘাট' }}</p>
                        <p class="text-gray-600 font-mono font-bold">{{ $printChallan->customer_phone ?: '০১৬৫৬৪৫৬৪৫৬' }}</p>
                    </div>
                </div>

                <div class="overflow-x-auto rounded-xl border border-gray-200">
                    <table class="w-full text-xs text-left">
                        <thead>
                            <tr class="bg-gray-100 text-gray-800 font-bold border-b border-gray-200">
                                <th class="p-3 text-center">চালান নং</th>
                                <th class="p-3 text-left">শ্রেণি</th>
                                <th class="p-3 text-center">পরিমাণ</th>
                                <th class="p-3 text-right">দর</th>
                                <th class="p-3 text-right">মূল্য</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 font-sans">
                            @forelse($printChallan->items as $item)
                                <tr>
                                    <td class="p-3 text-center font-bold font-mono">{{ $printChallan->challan_no }}</td>
                                    <td class="p-3 text-left font-semibold text-gray-800">{{ $item->category_name }}</td>
                                    <td class="p-3 text-center font-mono font-bold">{{ number_format($item->quantity) }}</td>
                                    <td class="p-3 text-right font-mono">৳ {{ number_format($item->rate, 2) }}</td>
                                    <td class="p-3 text-right font-mono font-bold text-gray-900">৳ {{ number_format($item->amount, 0) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="p-4 text-center text-gray-400">কোনো আইটেম পাওয়া যায়নি</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="grid grid-cols-2 gap-6 items-end pt-2">
                    <div class="space-y-4">
                        <div class="bg-white border border-gray-200 rounded-xl p-3.5 text-[11px] text-gray-700 space-y-1">
                            <p class="font-bold underline mb-1 text-gray-900">শর্তাবলী:</p>
                            <p>১. সকল মালের ঝুঁকি ক্রেতার।</p>
                            <p>২. মালের ডেলিভারির সময় রিসিভ সাইন বাধ্যতামূলক।</p>
                            <p>৩. যেকোনো বিরোধ মেহেরপুর আদালতের এখতিয়ারাধীন।</p>
                        </div>
                        <div class="inline-block border-2 border-black rounded-xl px-10 py-3 text-center font-black text-2xl tracking-wide uppercase">
                            {{ $printChallan->due > 0 ? 'বাকি' : 'পরিশোধিত' }}
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-200 space-y-2 text-xs font-sans">
                        <div class="flex justify-between items-center text-gray-700"><span class="font-semibold">মোট মূল্য</span><span class="font-mono font-bold text-gray-900">৳ {{ number_format($printChallan->total_value ?: $printChallan->items->sum('amount'), 0) }}</span></div>
                        <div class="flex justify-between items-center text-gray-700"><span class="font-semibold">ছাড়</span><span class="font-mono font-bold text-gray-900">৳ {{ number_format($printChallan->discount ?: 0, 0) }}</span></div>
                        <div class="flex justify-between items-center text-gray-700"><span class="font-semibold">গাড়ি ভাড়া</span><span class="font-mono font-bold text-gray-900">৳ {{ number_format($printChallan->transport_rent ?: 0, 0) }}</span></div>
                        <div class="flex justify-between items-center font-extrabold text-gray-900 pt-2 border-t border-gray-200 text-sm"><span>সর্বমোট</span><span class="font-mono">৳ {{ number_format($printChallan->grand_total, 0) }}</span></div>
                        <div class="flex justify-between items-center text-gray-900 font-bold"><span>নগদ</span><span class="font-mono">৳ {{ number_format($printChallan->cash, 0) }}</span></div>
                        <div class="flex justify-between items-center text-gray-900 font-bold"><span>বাকি</span><span class="font-mono">৳ {{ number_format($printChallan->due, 0) }}</span></div>
                    </div>
                </div>

                <div class="flex justify-between items-center pt-8 border-t border-gray-200 text-xs font-semibold text-gray-800">
                    <span class="border-t border-black pt-1 px-6">কাস্টমার স্বাক্ষর</span>
                    <span class="border-t border-black pt-1 px-6">কর্তৃপক্ষের স্বাক্ষর</span>
                </div>
                <div class="text-center text-[10px] text-gray-400 font-mono uppercase tracking-wider">
                    Software By - CODENEXTIT.COM
                </div>
            </div>
        </div>

    </div>

    <!-- 4 Hidden Single Challan Print Layout Containers -->
    <div id="print-a4-customer" class="hidden">
        <x-print-layout type="a4-customer" :challan="$printChallan" />
    </div>
    <div id="print-a4-dual" class="hidden">
        <x-print-layout type="a4-dual" :challan="$printChallan" />
    </div>
    <div id="print-pos-customer" class="hidden">
        <x-print-layout type="pos-customer" :challan="$printChallan" />
    </div>
    <div id="print-pos-dual" class="hidden">
        <x-print-layout type="pos-dual" :challan="$printChallan" />
    </div>

</div>

<!-- Helper JavaScript for Printing Specific Area -->
<script>
    if (typeof window.printChallanArea !== 'function') {
        window.printChallanArea = function(divId) {
            var printElement = document.getElementById(divId);
            if (!printElement) return;

            var printWindow = window.open('', '_blank', 'width=950,height=750');
            if (!printWindow) {
                alert('পপআপ ব্লক করা আছে, অনুগ্রহ করে ব্রাউজার পপআপ এলাউ করুন');
                return;
            }

            var headContent = document.head.innerHTML;
            var bodyContent = printElement.innerHTML;

            printWindow.document.write('<!DOCTYPE html><html><head>' + headContent + '<style>@media print { @page { margin: 0 !important; } body { margin: 0 !important; padding: 10px !important; } } body { background: white !important; margin: 0 !important; padding: 10px !important; }</style></head><body onload="window.focus(); window.print(); window.close();">' + bodyContent + '</body></html>');
            printWindow.document.close();
        };
    }
</script>
@endif

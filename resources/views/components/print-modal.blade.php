{{-- 
========================================================================================
🖨️ PROJECT UNIVERSAL PRINT PREVIEW MODAL COMPONENT (x-print-modal)
========================================================================================
File Path: resources/views/components/print-modal.blade.php
Used In Pages: 
  - All Challans Page (@see livewire.challan.all-challan)
  - Today Challans Page (@see livewire.challan.today-challan)
  - Pending Challans Page (@see livewire.challan.pending-challan)
  - Customer Profile Page (@see livewire.challan.customer-profile)
  - Delivery Print Actions (Single & Dual Delivery Receipts)
========================================================================================
--}}
@props([
    'showPrintModal' => false,
    'printChallan' => null,
    'isDeliveryPrint' => false,
])

@if($showPrintModal && $printChallan)
@php
    $companyName = \App\Models\Setting::get('company_name_bn', 'ডেমো ব্রিকস');
    $companyAddress = \App\Models\Setting::get('address', 'হিলালীপাড়া,কাটাবাড়ি,গোবیندগঞ্জ');
    $companyPhone = \App\Models\Setting::get('invoice_phones') ?: \App\Models\Setting::get('owner_phone', '01901349901,01901349906');
    $proprietor = \App\Models\Setting::get('owner_name', 'মোঃ মানিক মিয়া');

    $latestDelivery = null;
    if ($printChallan) {
        $latestDelivery = \App\Models\Delivery::where('challan_id', $printChallan->id)->latest()->first();
    }
    $driverName = ($latestDelivery && $latestDelivery->driver_name) ? $latestDelivery->driver_name : '—';
    $driverPhone = ($latestDelivery && $latestDelivery->driver_phone) ? $latestDelivery->driver_phone : '—';
    $vehicleNo = ($latestDelivery && $latestDelivery->vehicle_no) ? $latestDelivery->vehicle_no : '—';
    $vehicleRent = $latestDelivery ? $latestDelivery->vehicle_rent : ($printChallan->transport_rent ?: 0);
    $deliveryNo = $latestDelivery ? $latestDelivery->delivery_no : '১';
@endphp

<!-- Modal Overlay Box -->
<div class="fixed inset-0 z-[9999] flex items-center justify-center p-4 sm:p-6 bg-slate-900/60 backdrop-blur-sm transition-opacity overflow-y-auto" x-cloak>
    <div class="relative w-full max-w-4xl bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-gray-200 dark:border-slate-800 my-8 overflow-hidden transition-all" @click.away="$wire.closePrintModal()">
        
        <!-- ======================================================================= -->
        <!-- 🔘 TOP CONTROL BAR: 4 PRINT FORMAT BUTTONS                              -->
        <!-- ======================================================================= -->
        <div class="relative bg-slate-950 p-4 border-b border-slate-800 flex flex-col items-center gap-3 text-white">
            
            <!-- Close Modal Cross Button at Top Right -->
            <button type="button" wire:click="closePrintModal" 
                    class="absolute top-3.5 right-4 p-1.5 rounded-xl text-gray-400 hover:text-white hover:bg-slate-800 transition-colors cursor-pointer"
                    title="বন্ধ করুন">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>

            <!-- Centered Header Title -->
            <h3 class="text-xs font-bold text-slate-300 uppercase tracking-wide text-center">
                প্রিন্ট অপশন সিলেক্ট করুন
            </h3>
            
            <!-- 4 Horizontal Action Buttons -->
            <div class="flex flex-wrap sm:flex-nowrap items-center justify-center gap-2.5 w-full pr-8 sm:pr-0">
                <!-- 1. A4 Customer Button -->
                <button type="button" 
                        onclick="printChallanArea('print-a4-customer')"
                        class="flex-1 sm:flex-initial px-4 py-2 bg-[#059669] hover:bg-[#047857] text-white text-xs font-bold rounded-xl shadow-md transition-all flex items-center justify-center gap-1.5 cursor-pointer active:scale-95 whitespace-nowrap">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                    A4 (কাস্টমার)
                </button>

                <!-- 2. A4 Dual (Customer + Office) Button -->
                <button type="button" 
                        onclick="printChallanArea('print-a4-dual')"
                        class="flex-1 sm:flex-initial px-4 py-2 bg-[#034C3C] hover:bg-[#023E31] text-white text-xs font-bold rounded-xl shadow-md transition-all flex items-center justify-center gap-1.5 cursor-pointer active:scale-95 whitespace-nowrap">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                    A4 (কাস্টমার+অফিস)
                </button>

                <!-- 3. POS Customer Button -->
                <button type="button" 
                        onclick="printChallanArea('print-pos-customer')"
                        class="flex-1 sm:flex-initial px-4 py-2 bg-[#E05A16] hover:bg-[#BE4B11] text-white text-xs font-bold rounded-xl shadow-md transition-all flex items-center justify-center gap-1.5 cursor-pointer active:scale-95 whitespace-nowrap">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                    POS (কাস্টমার)
                </button>

                <!-- 4. POS Dual (Customer + Office) Button -->
                <button type="button" 
                        onclick="printChallanArea('print-pos-dual')"
                        class="flex-1 sm:flex-initial px-4 py-2 bg-[#D97706] hover:bg-[#B45309] text-white text-xs font-bold rounded-xl shadow-md transition-all flex items-center justify-center gap-1.5 cursor-pointer active:scale-95 whitespace-nowrap">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                    POS (কাস্টমার+অফিস)
                </button>
            </div>
        </div>

        <!-- ======================================================================= -->
        <!-- 👁️ ON-SCREEN MODAL PREVIEW AREA (LIVE IN-MODAL DISPLAY)                -->
        <!-- ======================================================================= -->
        <div class="p-4 sm:p-6 bg-slate-950 max-h-[78vh] overflow-y-auto">
            @if(isset($isDeliveryPrint) && $isDeliveryPrint)
                {{-- 🚚 ON-SCREEN LIVE PREVIEW: DELIVERY RECEIPT --}}
                <div class="bg-white p-6 sm:p-8 rounded-2xl border border-gray-200 text-gray-900 space-y-6 shadow-xl max-w-3xl mx-auto font-sans">
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
                                <h2 class="text-2xl font-black text-gray-900 tracking-wide">{{ $companyName }}</h2>
                                <p class="text-xs text-gray-600 font-medium">{{ $companyAddress }}</p>
                                <p class="text-xs text-gray-600 font-mono">{{ $companyPhone }}</p>
                                <p class="text-xs font-bold text-gray-800">প্রোপাইটরঃ {{ $proprietor }}</p>
                            </div>
                        </div>
                        <div class="text-right space-y-1">
                            <h1 class="text-3xl font-black text-gray-900 uppercase tracking-wider font-mono">DELIVERY</h1>
                            <p class="text-xs font-semibold text-gray-500">গ্রাহক ডেলিভারি কপি</p>
                            <p class="text-[11px] text-gray-400 font-mono">প্রিন্ট: {{ now()->format('d-m-Y h:i A') }}</p>
                        </div>
                    </div>

                    <div class="flex justify-between items-start text-xs bg-gray-50 p-4 rounded-xl border border-gray-200">
                        <div class="space-y-1.5">
                            <p class="text-gray-700"><span class="font-bold text-gray-900">কাস্টমার আইডি:</span> {{ $printChallan->ledger_id ?: $printChallan->id }}</p>
                            <p class="text-gray-700"><span class="font-bold text-gray-900">ডেলিভারি তারিখ:</span> {{ $printChallan->date ? \Carbon\Carbon::parse($printChallan->date)->format('d-m-Y') : now()->format('d-m-Y') }}, {{ $printChallan->date ? \Carbon\Carbon::parse($printChallan->date)->format('বিকেল: h:i') : now()->format('h:i') }}</p>
                            <p class="text-gray-700"><span class="font-bold text-gray-900">ইস্যু করেছে:</span> Demo</p>
                        </div>
                        <div class="text-right space-y-1 pl-4 border-r-4 border-black pr-2">
                            <p class="text-gray-900 font-black text-sm">{{ $printChallan->customer_name }}</p>
                            <p class="text-gray-600 font-semibold">{{ $printChallan->customer_address ?: 'ঘোড়াঘাট' }}</p>
                            <p class="text-gray-600 font-mono font-bold">{{ $printChallan->customer_phone ?: '০১৬৫৬৪৫৬৫৬৫' }}</p>
                        </div>
                    </div>

                    <div class="overflow-x-auto rounded-xl border border-gray-200">
                        <table class="w-full text-xs text-left">
                            <thead>
                                <tr class="bg-gray-100 text-gray-800 font-bold border-b border-gray-200">
                                    <th class="p-3 text-center">ডে.নং</th>
                                    <th class="p-3 text-center">চালান</th>
                                    <th class="p-3 text-left">শ্রেণি</th>
                                    <th class="p-3 text-center">ডেলিভারি</th>
                                    <th class="p-3 text-center">ডে.বাকি</th>
                                    <th class="p-3 text-center">সময়</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 font-sans">
                                @forelse($printChallan->items as $idx => $item)
                                    <tr>
                                        <td class="p-3 text-center font-bold font-mono">{{ $idx + 1 }}</td>
                                        <td class="p-3 text-center font-bold font-mono">{{ $printChallan->challan_no }}</td>
                                        <td class="p-3 text-left font-semibold text-gray-800">{{ $item->category_name }}</td>
                                        <td class="p-3 text-center font-mono font-bold">{{ number_format($item->delivered_quantity ?: $item->quantity) }}</td>
                                        <td class="p-3 text-center font-mono font-bold">{{ number_format(max(0, $item->quantity - ($item->delivered_quantity ?: $item->quantity))) }}</td>
                                        <td class="p-3 text-center font-mono">{{ $printChallan->date ? \Carbon\Carbon::parse($printChallan->date)->format('বিকেল h:i') : '' }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="p-4 text-center text-gray-400">কোনো ডেলিভারি তথ্য পাওয়া যায়নি</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="grid grid-cols-2 gap-6 items-start pt-2">
                        <div class="bg-white rounded-xl p-3.5 text-[11px] text-gray-700 space-y-1">
                            <p class="font-bold underline mb-1 text-gray-900">বিশেষ দ্রষ্টব্যঃ</p>
                            <p>১। চালান অথবা রসিদ ছাড়া কোনো লেনদেন করবেন না।</p>
                            <p>২। ইট ডেলিভারি নেওয়ার পরকোনও অভিযোগ গ্রহণ যোগ্য হবে না।</p>
                            <p>৩। চালান করার ৩০ দিনের মধ্যে ইট ডেলিভারি নিতে হবে।</p>
                        </div>

                        <div class="bg-gray-50/80 rounded-xl p-4 space-y-2 text-xs font-sans">
                            <p class="font-black text-gray-900 text-sm border-b border-gray-200 pb-1.5">গাড়ি ভাড়া: {{ number_format($vehicleRent) }} টাকা</p>
                            <p class="text-gray-700 font-semibold">ড্রাইভার: {{ $driverName }}</p>
                            <p class="text-gray-700 font-semibold">ফোন নম্বর: {{ $driverPhone }}</p>
                            <p class="text-gray-700 font-semibold">গাড়ি নং: {{ $vehicleNo }}</p>
                        </div>
                    </div>

                    <div class="flex justify-between items-center pt-8 border-t border-gray-200 text-xs font-semibold text-gray-800">
                        <span class="border-t border-black pt-1 px-6">গ্রাহকের স্বাক্ষর</span>
                        <span class="border-t border-black pt-1 px-6">ম্যানেজারের স্বাক্ষর</span>
                    </div>
                    <div class="text-center text-[10px] text-gray-400 font-mono uppercase tracking-wider">
                        Software By - CODENEXTIT.COM
                    </div>
                </div>

            @else
                {{-- 🧾 ON-SCREEN LIVE PREVIEW: STANDARD CHALLAN / INVOICE --}}
                <div class="bg-white p-6 sm:p-8 rounded-2xl border border-gray-200 text-gray-900 space-y-6 shadow-xl max-w-3xl mx-auto font-sans">
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
                                <h2 class="text-2xl font-black text-gray-900 tracking-wide">{{ $companyName }}</h2>
                                <p class="text-xs text-gray-600 font-medium">{{ $companyAddress }}</p>
                                <p class="text-xs text-gray-600 font-mono">{{ $companyPhone }}</p>
                                <p class="text-xs font-bold text-gray-800">প্রোপাইটরঃ {{ $proprietor }}</p>
                            </div>
                        </div>
                        <div class="text-right space-y-1">
                            <h1 class="text-3xl font-black text-gray-900 uppercase tracking-wider font-mono">INVOICE</h1>
                            <p class="text-xs font-semibold text-gray-500">গ্রাহক কপি</p>
                            <p class="text-[11px] text-gray-400 font-mono">প্রিন্ট: {{ now()->format('d-m-Y h:i A') }}</p>
                        </div>
                    </div>

                    <div class="flex justify-between items-start text-xs bg-gray-50 p-4 rounded-xl border border-gray-200">
                        <div class="space-y-1.5">
                            <p class="text-gray-700"><span class="font-bold text-gray-900">কাস্টমার আইডি:</span> {{ $printChallan->ledger_id ?: $printChallan->id }}</p>
                            <p class="text-gray-700"><span class="font-bold text-gray-900">চালানের তারিখ:</span> {{ $printChallan->date ? \Carbon\Carbon::parse($printChallan->date)->format('d-m-Y') : now()->format('d-m-Y') }}, {{ $printChallan->date ? \Carbon\Carbon::parse($printChallan->date)->format('দুপুর: h:i:s') : now()->format('h:i:s') }}</p>
                            <p class="text-gray-700"><span class="font-bold text-gray-900">ইস্যু করেছে:</span></p>
                        </div>
                        <div class="text-right space-y-1 pl-4 border-r-4 border-black pr-2">
                            <p class="text-gray-900 font-black text-sm">{{ $printChallan->customer_name }}</p>
                            <p class="text-gray-600 font-semibold">{{ $printChallan->customer_address ?: 'ঘোড়াঘাট' }}</p>
                            <p class="text-gray-600 font-mono font-bold">{{ $printChallan->customer_phone ?: '০১৬৫৬৪৫৬৫৬৫' }}</p>
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
                                <p class="font-bold underline mb-1 text-gray-900">বিশেষ দ্রষ্টব্যঃ</p>
                                <p>১। চালান অথবা রসিদ ছাড়া কোনো লেনদেন করবেন না।</p>
                                <p>২। ইট ডেলিভারি নেওয়ার পরকোনও অভিযোগ গ্রহণ যোগ্য হবে না।</p>
                                <p>৩। চালান করার ৩০ দিনের মধ্যে ইট ডেলিভারি নিতে হবে।</p>
                            </div>
                            @if($printChallan->due > 0)
                                <div class="border-2 border-red-500 rounded-xl p-3 text-center space-y-1">
                                    <p class="text-base font-black text-red-600">বাকি: ৳ {{ number_format($printChallan->due) }}</p>
                                    <p class="text-xs font-bold text-red-500">পরিশোধের তারিখ : {{ $printChallan->due_payment_date ? \Carbon\Carbon::parse($printChallan->due_payment_date)->format('d-m-Y') : '—' }}</p>
                                </div>
                            @else
                                <div class="inline-block border-2 border-green-600 rounded-xl px-8 py-2.5 text-center font-black text-xl tracking-wide uppercase text-green-700">
                                    পরিশোধিত
                                </div>
                            @endif
                        </div>

                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-200 space-y-2 text-xs font-sans">
                            <div class="flex justify-between items-center text-gray-700"><span class="font-semibold">মোট মূল্য</span><span class="font-mono font-bold text-gray-900">৳ {{ number_format($printChallan->total_value ?: $printChallan->items->sum('amount'), 0) }}</span></div>
                            <div class="flex justify-between items-center text-gray-700"><span class="font-semibold">ছাড়</span><span class="font-mono font-bold text-gray-900">৳ {{ number_format($printChallan->discount ?: 0, 0) }}</span></div>
                            <div class="flex justify-between items-center text-gray-700"><span class="font-semibold">গাড়ি ভাড়া</span><span class="font-mono font-bold text-gray-900">৳ {{ number_format($printChallan->transport_rent ?: 0, 0) }}</span></div>
                            <div class="flex justify-between items-center font-extrabold text-gray-900 pt-2 border-t border-gray-200 text-sm"><span>সর্বমোট</span><span class="font-mono">৳ {{ number_format($printChallan->grand_total, 0) }}</span></div>
                            <div class="flex justify-between items-center text-gray-900 font-bold"><span>জমা</span><span class="font-mono">৳ {{ number_format($printChallan->cash, 0) }}</span></div>
                            <div class="flex justify-between items-center text-gray-900 font-bold"><span>বাকি</span><span class="font-mono">৳ {{ number_format($printChallan->due, 0) }}</span></div>
                        </div>
                    </div>

                    <div class="flex justify-between items-center pt-8 border-t border-gray-200 text-xs font-semibold text-gray-800">
                        <span class="border-t border-black pt-1 px-6">গ্রাহকের স্বাক্ষর</span>
                        <span class="border-t border-black pt-1 px-6">ম্যানেজারের স্বাক্ষর</span>
                    </div>
                    <div class="text-center text-[10px] text-gray-400 font-mono uppercase tracking-wider">
                        Software By - CODENEXTIT.COM
                    </div>
                </div>
            @endif
        </div>

    </div>

    <!-- ======================================================================= -->
    <!-- 📄 4 HIDDEN PRINT PAPER LAYOUT CONTAINERS (TARGETS FOR printChallanArea)-->
    <!-- ======================================================================= -->
    
    <!-- 📄 PRINT LAYOUT 1: A4 Single Customer Copy (#print-a4-customer) -->
    <div id="print-a4-customer" class="hidden">
        @if(isset($isDeliveryPrint) && $isDeliveryPrint)
            <style>
                @media print {
                    @page {
                        size: A4 portrait !important;
                        margin: 5mm !important;
                    }
                    html, body {
                        background: #ffffff !important;
                        color: #000000 !important;
                        margin: 0 !important;
                        padding: 0 !important;
                        -webkit-print-color-adjust: exact !important;
                        print-color-adjust: exact !important;
                    }
                }
            </style>
            <div class="bg-white p-4 text-gray-900 space-y-6 max-w-4xl mx-auto font-sans">
                <!-- Header -->
                <div class="flex justify-between items-start border-b border-gray-300 pb-3">
                    <div class="flex items-center gap-3">
                        <div class="w-14 h-14 rounded-xl bg-red-50 flex items-center justify-center p-2 shrink-0 border border-red-100">
                            <svg class="w-10 h-10 text-red-500" viewBox="0 0 64 64" fill="currentColor">
                                <path d="M26 12 L38 12 L44 50 L20 50 Z" fill="#EF4444" />
                                <path d="M22 24 L42 24 L44 32 L20 32 Z" fill="#FFFFFF" />
                                <path d="M12 50 L52 50 L56 58 L8 58 Z" fill="#EF4444" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-black text-gray-900 tracking-wide">{{ $companyName }}</h2>
                            <p class="text-xs text-gray-600 font-medium">{{ $companyAddress }}</p>
                            <p class="text-xs text-gray-600 font-mono">{{ $companyPhone }}</p>
                            <p class="text-xs font-bold text-gray-800">প্রোপাইটরঃ {{ $proprietor }}</p>
                        </div>
                    </div>
                    <div class="text-right space-y-1">
                        <h1 class="text-3xl font-black text-gray-900 uppercase tracking-wider font-mono">DELIVERY</h1>
                        <p class="text-xs font-semibold text-gray-500">গ্রাহক ডেলিভারি কপি</p>
                        <p class="text-[11px] text-gray-500 font-mono">প্রিন্ট: {{ now()->format('d-m-Y h:i') }}</p>
                    </div>
                </div>

                <!-- Customer Details Metadata Box -->
                <div class="flex justify-between items-start text-xs bg-gray-50/80 p-3.5 rounded-xl border border-gray-200">
                    <div class="space-y-1.5">
                        <p class="text-gray-800"><span class="font-bold">কাস্টমার আইডি:</span> {{ $printChallan->ledger_id ?: $printChallan->id }}</p>
                        <p class="text-gray-800"><span class="font-bold">ডেলিভারি তারিখ:</span> {{ $printChallan->date ? \Carbon\Carbon::parse($printChallan->date)->format('d-m-Y') : now()->format('d-m-Y') }}, {{ $printChallan->date ? \Carbon\Carbon::parse($printChallan->date)->format('বিকেল: h:i') : now()->format('h:i') }}</p>
                        <p class="text-gray-800"><span class="font-bold">ইস্যু করেছে:</span> Demo</p>
                    </div>
                    <div class="text-right space-y-1 pl-4 border-r-4 border-black pr-2">
                        <p class="text-gray-900 font-black text-sm">{{ $printChallan->customer_name }}</p>
                        <p class="text-gray-700 font-semibold">{{ $printChallan->customer_address ?: 'ঘোড়াঘাট' }}</p>
                        <p class="text-gray-700 font-mono font-bold">{{ $printChallan->customer_phone ?: '০১৬৫৬৪৫৬৫৬৫' }}</p>
                    </div>
                </div>

                <!-- Items Table -->
                <div class="overflow-x-auto rounded-xl border border-gray-200">
                    <table class="w-full text-xs text-left">
                        <thead>
                            <tr class="bg-gray-100 text-gray-900 font-bold border-b border-gray-200">
                                <th class="p-2.5 text-center">ডে.নং</th>
                                <th class="p-2.5 text-center">চালান</th>
                                <th class="p-2.5 text-left">শ্রেণি</th>
                                <th class="p-2.5 text-center">ডেলিভারি</th>
                                <th class="p-2.5 text-center">ডে.বাকি</th>
                                <th class="p-2.5 text-center">সময়</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 font-sans">
                            @forelse($printChallan->items as $idx => $item)
                                <tr>
                                    <td class="p-2.5 text-center font-bold font-mono">{{ $idx + 1 }}</td>
                                    <td class="p-2.5 text-center font-bold font-mono">{{ $printChallan->challan_no }}</td>
                                    <td class="p-2.5 text-left font-semibold text-gray-900">{{ $item->category_name }}</td>
                                    <td class="p-2.5 text-center font-mono font-bold">{{ number_format($item->delivered_quantity ?: $item->quantity) }}</td>
                                    <td class="p-2.5 text-center font-mono font-bold">{{ number_format(max(0, $item->quantity - ($item->delivered_quantity ?: $item->quantity))) }}</td>
                                    <td class="p-2.5 text-center font-mono">{{ $printChallan->date ? \Carbon\Carbon::parse($printChallan->date)->format('বিকেল h:i') : '' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="p-3 text-center text-gray-400">কোনো ডেলিভারি তথ্য পাওয়া যায়নি</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Notes & Delivery Info Grid -->
                <div class="grid grid-cols-2 gap-6 items-start pt-1">
                    <div class="bg-white rounded-xl p-3 text-[11px] text-gray-800 space-y-1">
                        <p class="font-bold underline mb-1 text-gray-900">বিশেষ দ্রষ্টব্যঃ</p>
                        <p>১। চালান অথবা রসিদ ছাড়া কোনো লেনদেন করবেন না।</p>
                        <p>২। ইট ডেলিভারি নেওয়ার পর কোনও অভিযোগ গ্রহণ যোগ্য হবে না।</p>
                        <p>৩। চালান করার ৩০ দিনের মধ্যে ইট ডেলিভারি নিতে হবে।</p>
                    </div>

                    <div class="bg-gray-50/80 rounded-xl p-3.5 space-y-1.5 text-xs font-sans">
                        <p class="font-black text-gray-900 text-sm border-b border-gray-200 pb-1">গাড়ি ভাড়া: {{ number_format($vehicleRent) }} টাকা</p>
                        <p class="text-gray-700 font-semibold">ড্রাইভার: {{ $driverName }}</p>
                        <p class="text-gray-700 font-semibold">ফোন নম্বর: {{ $driverPhone }}</p>
                        <p class="text-gray-700 font-semibold">গাড়ি নং: {{ $vehicleNo }}</p>
                    </div>
                </div>

                <!-- Signatures -->
                <div class="flex justify-between items-center pt-8 border-t border-gray-300 text-xs font-semibold text-gray-800">
                    <span class="border-t border-black pt-1 px-8">গ্রাহকের স্বাক্ষর</span>
                    <span class="border-t border-black pt-1 px-8">ম্যানেজারের স্বাক্ষর</span>
                </div>

                <div class="text-center pt-2 text-[10px] text-gray-400 font-mono uppercase tracking-wider">
                    Software By - CODENEXTIT.COM
                </div>
            </div>
        @else
            <x-print-layout type="a4-customer" :challan="$printChallan" />
        @endif
    </div>

    <!-- 📄 PRINT LAYOUT 2: A4 Dual Copies Customer + Office Landscape (#print-a4-dual) -->
    <div id="print-a4-dual" class="hidden">
        @if(isset($isDeliveryPrint) && $isDeliveryPrint)
            <style type="text/css" media="print">
                @page {
                    size: A4 landscape !important;
                    margin: 5mm !important;
                }
                html, body {
                    background: #ffffff !important;
                    color: #000000 !important;
                    margin: 0 !important;
                    padding: 0 !important;
                    -webkit-print-color-adjust: exact !important;
                    print-color-adjust: exact !important;
                }
            </style>
            <div class="bg-white p-2 text-gray-900 max-w-full mx-auto font-sans">
                <div class="grid grid-cols-2 gap-6 relative">
                    <!-- Middle Dashed Divider Line -->
                    <div class="absolute inset-y-0 left-1/2 -ml-px border-r-2 border-dashed border-gray-400 pointer-events-none"></div>

                    <!-- Customer Copy -->
                    <div class="pr-6 space-y-4">
                        <div class="flex justify-between items-start border-b border-gray-300 pb-2">
                            <div class="flex items-center gap-2">
                                <div class="w-10 h-10 rounded-lg bg-red-50 flex items-center justify-center p-1 shrink-0 border border-red-100">
                                    <svg class="w-7 h-7 text-red-500" viewBox="0 0 64 64" fill="currentColor">
                                        <path d="M26 12 L38 12 L44 50 L20 50 Z" fill="#EF4444" />
                                        <path d="M22 24 L42 24 L44 32 L20 32 Z" fill="#FFFFFF" />
                                        <path d="M12 50 L52 50 L56 58 L8 58 Z" fill="#EF4444" />
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-lg font-black text-gray-900 tracking-wide">{{ $companyName }}</h2>
                                    <p class="text-[10px] text-gray-600">{{ $companyAddress }}</p>
                                    <p class="text-[10px] text-gray-600 font-mono">{{ $companyPhone }}</p>
                                    <p class="text-[10px] font-bold text-gray-800">প্রোপাইটরঃ {{ $proprietor }}</p>
                                </div>
                            </div>
                            <div class="text-right space-y-0.5">
                                <h1 class="text-xl font-black text-gray-900 uppercase font-mono">DELIVERY</h1>
                                <p class="text-[10px] font-semibold text-gray-500">গ্রাহক ডেলিভারি কপি</p>
                                <p class="text-[9px] text-gray-500 font-mono">প্রিন্ট: {{ now()->format('d-m-Y h:i') }}</p>
                            </div>
                        </div>

                        <div class="flex justify-between items-start text-[11px] bg-gray-50 p-2 rounded-lg border border-gray-200">
                            <div class="space-y-1">
                                <p class="text-gray-800"><span class="font-bold">কাস্টমার আইডি:</span> {{ $printChallan->ledger_id ?: $printChallan->id }}</p>
                                <p class="text-gray-800"><span class="font-bold">ডেলিভারি তারিখ:</span> {{ $printChallan->date ? \Carbon\Carbon::parse($printChallan->date)->format('d-m-Y') : now()->format('d-m-Y') }}, {{ $printChallan->date ? \Carbon\Carbon::parse($printChallan->date)->format('বিকেল: h:i') : now()->format('h:i') }}</p>
                                <p class="text-gray-800"><span class="font-bold">ইস্যু করেছে:</span> Demo</p>
                            </div>
                            <div class="text-right space-y-0.5 pl-3 border-r-4 border-black pr-1.5">
                                <p class="text-gray-900 font-black text-xs">{{ $printChallan->customer_name }}</p>
                                <p class="text-gray-700 font-semibold">{{ $printChallan->customer_address ?: 'ঘোড়াঘাট' }}</p>
                                <p class="text-gray-700 font-mono font-bold">{{ $printChallan->customer_phone ?: '০১৬৫৬৪৫৬৫৬৫' }}</p>
                            </div>
                        </div>

                        <table class="w-full text-[11px] border border-gray-200 rounded-lg">
                            <thead>
                                <tr class="bg-gray-100 font-bold border-b border-gray-200 text-gray-900"><th class="p-1.5 text-center">ডে.নং</th><th class="p-1.5 text-center">চালান</th><th class="p-1.5 text-left">শ্রেণি</th><th class="p-1.5 text-center">ডেলিভারি</th><th class="p-1.5 text-center">ডে.বাকি</th><th class="p-1.5 text-center">সময়</th></tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 font-sans">
                                @foreach($printChallan->items as $idx => $item)
                                    <tr><td class="p-1.5 text-center font-mono font-bold">{{ $idx + 1 }}</td><td class="p-1.5 text-center font-mono font-bold">{{ $printChallan->challan_no }}</td><td class="p-1.5 text-left font-semibold">{{ $item->category_name }}</td><td class="p-1.5 text-center font-mono font-bold">{{ number_format($item->delivered_quantity ?: $item->quantity) }}</td><td class="p-1.5 text-center font-mono font-bold">{{ number_format(max(0, $item->quantity - ($item->delivered_quantity ?: $item->quantity))) }}</td><td class="p-1.5 text-center font-mono text-[10px]">{{ $printChallan->date ? \Carbon\Carbon::parse($printChallan->date)->format('বিকেল h:i') : '' }}</td></tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="grid grid-cols-2 gap-3 items-start text-[10px]">
                            <div class="bg-white p-2 rounded-lg space-y-0.5">
                                <p class="font-bold underline text-gray-900">বিশেষ দ্রষ্টব্যঃ</p>
                                <p>১। চালান অথবা রসিদ ছাড়া কোনো লেনদেন করবেন না।</p>
                                <p>২। ইট ডেলিভারি নেওয়ার পর অভিযোগ গ্রহণ যোগ্য হবে না।</p>
                                <p>৩। চালান করার ৩০ দিনের মধ্যে ইট ডেলিভারি নিতে হবে।</p>
                            </div>
                            <div class="bg-gray-50 p-2 rounded-lg space-y-1">
                                <p class="font-bold text-gray-900 border-b pb-0.5">গাড়ি ভাড়া: {{ number_format($vehicleRent) }} টাকা</p>
                                <p class="text-gray-700">ড্রাইভার: {{ $driverName }}</p>
                                <p class="text-gray-700">ফোন নম্বর: {{ $driverPhone }}</p>
                                <p class="text-gray-700">গাড়ি নং: {{ $vehicleNo }}</p>
                            </div>
                        </div>

                        <div class="flex justify-between pt-6 text-[10px] font-semibold text-gray-800"><span class="border-t border-black px-4">গ্রাহকের স্বাক্ষর</span><span class="border-t border-black px-4">ম্যানেজারের স্বাক্ষর</span></div>
                        <div class="text-center text-[9px] text-gray-400 font-mono">Software By - CODENEXTIT.COM</div>
                    </div>

                    <!-- Office Copy -->
                    <div class="pl-6 space-y-4">
                        <div class="flex justify-between items-start border-b border-gray-300 pb-2">
                            <div class="flex items-center gap-2">
                                <div class="w-10 h-10 rounded-lg bg-red-50 flex items-center justify-center p-1 shrink-0 border border-red-100">
                                    <svg class="w-7 h-7 text-red-500" viewBox="0 0 64 64" fill="currentColor">
                                        <path d="M26 12 L38 12 L44 50 L20 50 Z" fill="#EF4444" />
                                        <path d="M22 24 L42 24 L44 32 L20 32 Z" fill="#FFFFFF" />
                                        <path d="M12 50 L52 50 L56 58 L8 58 Z" fill="#EF4444" />
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-lg font-black text-gray-900 tracking-wide">{{ $companyName }}</h2>
                                    <p class="text-[10px] text-gray-600">{{ $companyAddress }}</p>
                                    <p class="text-[10px] text-gray-600 font-mono">{{ $companyPhone }}</p>
                                    <p class="text-[10px] font-bold text-gray-800">প্রোপাইটরঃ {{ $proprietor }}</p>
                                </div>
                            </div>
                            <div class="text-right space-y-0.5">
                                <h1 class="text-xl font-black text-gray-900 uppercase font-mono">DELIVERY</h1>
                                <p class="text-[10px] font-semibold text-gray-500">অফিস ডেলিভারি কপি</p>
                                <p class="text-[9px] text-gray-500 font-mono">প্রিন্ট: {{ now()->format('d-m-Y h:i') }}</p>
                            </div>
                        </div>

                        <div class="flex justify-between items-start text-[11px] bg-gray-50 p-2 rounded-lg border border-gray-200">
                            <div class="space-y-1">
                                <p class="text-gray-800"><span class="font-bold">কাস্টমার আইডি:</span> {{ $printChallan->ledger_id ?: $printChallan->id }}</p>
                                <p class="text-gray-800"><span class="font-bold">ডেলিভারি তারিখ:</span> {{ $printChallan->date ? \Carbon\Carbon::parse($printChallan->date)->format('d-m-Y') : now()->format('d-m-Y') }}, {{ $printChallan->date ? \Carbon\Carbon::parse($printChallan->date)->format('বিকেল: h:i') : now()->format('h:i') }}</p>
                                <p class="text-gray-800"><span class="font-bold">ইস্যু করেছে:</span> Demo</p>
                            </div>
                            <div class="text-right space-y-0.5 pl-3 border-r-4 border-black pr-1.5">
                                <p class="text-gray-900 font-black text-xs">{{ $printChallan->customer_name }}</p>
                                <p class="text-gray-700 font-semibold">{{ $printChallan->customer_address ?: 'ঘোড়াঘাট' }}</p>
                                <p class="text-gray-700 font-mono font-bold">{{ $printChallan->customer_phone ?: '০১৬৫৬৪৫৬৫৬৫' }}</p>
                            </div>
                        </div>

                        <table class="w-full text-[11px] border border-gray-200 rounded-lg">
                            <thead>
                                <tr class="bg-gray-100 font-bold border-b border-gray-200 text-gray-900"><th class="p-1.5 text-center">ডে.নং</th><th class="p-1.5 text-center">চালান</th><th class="p-1.5 text-left">শ্রেণি</th><th class="p-1.5 text-center">ডেলিভারি</th><th class="p-1.5 text-center">ডে.বাকি</th><th class="p-1.5 text-center">সময়</th></tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 font-sans">
                                @foreach($printChallan->items as $idx => $item)
                                    <tr><td class="p-1.5 text-center font-mono font-bold">{{ $idx + 1 }}</td><td class="p-1.5 text-center font-mono font-bold">{{ $printChallan->challan_no }}</td><td class="p-1.5 text-left font-semibold">{{ $item->category_name }}</td><td class="p-1.5 text-center font-mono font-bold">{{ number_format($item->delivered_quantity ?: $item->quantity) }}</td><td class="p-1.5 text-center font-mono font-bold">{{ number_format(max(0, $item->quantity - ($item->delivered_quantity ?: $item->quantity))) }}</td><td class="p-1.5 text-center font-mono text-[10px]">{{ $printChallan->date ? \Carbon\Carbon::parse($printChallan->date)->format('বিকেল h:i') : '' }}</td></tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="grid grid-cols-2 gap-3 items-start text-[10px]">
                            <div class="bg-white p-2 rounded-lg space-y-0.5">
                                <p class="font-bold underline text-gray-900">বিশেষ দ্রষ্টব্যঃ</p>
                                <p>১। চালান অথবা রসিদ ছাড়া কোনো লেনদেন করবেন না।</p>
                                <p>২। ইট ডেলিভারি নেওয়ার পর অভিযোগ গ্রহণ যোগ্য হবে না।</p>
                                <p>৩। চালান করার ৩০ দিনের মধ্যে ইট ডেলিভারি নিতে হবে।</p>
                            </div>
                            <div class="bg-gray-50 p-2 rounded-lg space-y-1">
                                <p class="font-bold text-gray-900 border-b pb-0.5">গাড়ি ভাড়া: {{ number_format($vehicleRent) }} টাকা</p>
                                <p class="text-gray-700">ড্রাইভার: {{ $driverName }}</p>
                                <p class="text-gray-700">ফোন নম্বর: {{ $driverPhone }}</p>
                                <p class="text-gray-700">গাড়ি নং: {{ $vehicleNo }}</p>
                            </div>
                        </div>

                        <div class="flex justify-between pt-6 text-[10px] font-semibold text-gray-800"><span class="border-t border-black px-4">গ্রাহকের স্বাক্ষর</span><span class="border-t border-black px-4">ম্যানেজারের স্বাক্ষর</span></div>
                        <div class="text-center text-[9px] text-gray-400 font-mono">Software By - CODENEXTIT.COM</div>
                    </div>
                </div>
            </div>
        @else
            <x-print-layout type="a4-dual" :challan="$printChallan" />
        @endif
    </div>

    <!-- 📄 PRINT LAYOUT 3: POS Thermal Customer Receipt (#print-pos-customer) -->
    <div id="print-pos-customer" class="hidden">
        @if(isset($isDeliveryPrint) && $isDeliveryPrint)
            <style>
                @media print {
                    @page {
                        size: 80mm auto !important;
                        margin: 2mm !important;
                    }
                    html, body {
                        background: #ffffff !important;
                        color: #000000 !important;
                        margin: 0 !important;
                        padding: 0 !important;
                        -webkit-print-color-adjust: exact !important;
                        print-color-adjust: exact !important;
                    }
                }
            </style>
            <div class="max-w-[300px] mr-auto ml-0 bg-white min-h-screen p-2 text-gray-900 font-sans text-xs space-y-3 text-left">
                <div class="text-center space-y-1 border-b border-dashed border-gray-400 pb-2">
                    <p class="text-[11px] underline font-bold text-gray-800">ডেলিভারি রশিদ</p>
                    <h2 class="text-xl font-black text-gray-900 tracking-wide">{{ $companyName }}</h2>
                    <p class="text-[10px] text-gray-600 font-medium">{{ $companyAddress }}</p>
                    <p class="text-[10px] text-gray-600 font-mono">{{ $companyPhone }}</p>
                    <p class="text-[10px] font-bold text-gray-800">প্রোপাইটরঃ {{ $proprietor }}</p>
                </div>

                <div class="space-y-1 text-[11px] border-b border-dashed border-gray-400 pb-2 font-mono">
                    <div class="flex justify-between"><span>ডেলিভারি: {{ $deliveryNo }}</span><span>কাস্টমার: {{ $printChallan->customer_name }}</span></div>
                    <div class="flex justify-between"><span>চালান: {{ $printChallan->challan_no }}</span><span>ঠিকানা: {{ $printChallan->customer_address ?: 'ঘোড়াঘাট' }}</span></div>
                    <div class="flex justify-between"><span>তারিখ: {{ $printChallan->date ? \Carbon\Carbon::parse($printChallan->date)->format('d-m-Y') : now()->format('d-m-Y') }}</span><span>মোবাইল: {{ $printChallan->customer_phone }}</span></div>
                </div>

                <div class="border-b border-dashed border-gray-400 pb-2 text-[11px]">
                    <div class="flex justify-between font-bold border-b border-gray-300 pb-1 mb-1 text-gray-900"><span>শ্রেণি</span><span>ডেলিভারি</span><span>ডে.বাকি</span><span>সময়</span></div>
                    @foreach($printChallan->items as $item)
                        <div class="flex justify-between font-mono"><span>{{ $item->category_name }}</span><span>{{ number_format($item->delivered_quantity ?: $item->quantity) }}</span><span>{{ number_format(max(0, $item->quantity - ($item->delivered_quantity ?: $item->quantity))) }}</span><span>{{ $printChallan->date ? \Carbon\Carbon::parse($printChallan->date)->format('বিকেল h:i') : '' }}</span></div>
                    @endforeach
                </div>

                <div class="space-y-1 text-[11px] font-mono border-b border-dashed border-gray-400 pb-2">
                    <div class="flex justify-between font-bold text-gray-900"><span>মোট ডেলিভারি</span><span>= {{ number_format($printChallan->items->sum(fn($i) => $i->delivered_quantity ?: $i->quantity)) }}/-</span></div>
                    <div class="flex justify-between text-gray-700"><span>ড্রাইভার</span><span>{{ $driverName }}</span></div>
                    <div class="flex justify-between text-gray-700"><span>গাড়ি নং</span><span>{{ $vehicleNo }}</span></div>
                    <div class="flex justify-between text-gray-700"><span>গাড়ি ভাড়া</span><span>৳ {{ number_format($vehicleRent) }}</span></div>
                </div>

                <div class="flex justify-between text-[10px] font-semibold text-gray-800 pt-4">
                    <span class="border-t border-dashed border-gray-500 pt-0.5 px-1">গ্রাহকের স্বাক্ষর</span>
                    <span class="border-t border-dashed border-gray-500 pt-0.5 px-1">ম্যানেজারের স্বাক্ষর</span>
                </div>

                <div class="text-center pt-2 border-t border-dashed border-gray-400 space-y-0.5 text-[10px]">
                    <p class="font-bold text-gray-800">চালান/রশিদ ছাড়া লেনদেন করবেন না</p>
                    <p class="text-gray-400 font-mono text-[9px]">Software By - CODENEXTIT.COM</p>
                </div>
            </div>
        @else
            <x-print-layout type="pos-customer" :challan="$printChallan" />
        @endif
    </div>

    <!-- 📄 PRINT LAYOUT 4: POS Thermal Dual Stacked Customer + Office Slips (#print-pos-dual) -->
    <div id="print-pos-dual" class="hidden">
        @if(isset($isDeliveryPrint) && $isDeliveryPrint)
            <style>
                @media print {
                    @page {
                        size: A4 portrait !important;
                        margin: 5mm !important;
                    }
                    html, body {
                        background: #ffffff !important;
                        color: #000000 !important;
                        margin: 0 !important;
                        padding: 0 !important;
                        -webkit-print-color-adjust: exact !important;
                        print-color-adjust: exact !important;
                    }
                }
            </style>
            <div class="max-w-[300px] mr-auto ml-0 bg-white p-2 text-gray-900 font-sans text-xs space-y-4 text-left">
                <!-- Customer Slip -->
                <div class="space-y-3 border-b-2 border-dashed border-gray-500 pb-4">
                    <div class="text-center space-y-1 border-b border-dashed border-gray-400 pb-2">
                        <p class="text-[11px] underline font-bold text-gray-800">ডেলিভারি রশিদ</p>
                        <h2 class="text-xl font-black text-gray-900 tracking-wide">{{ $companyName }}</h2>
                        <p class="text-[10px] text-gray-600 font-medium">{{ $companyAddress }}</p>
                        <p class="text-[10px] text-gray-600 font-mono">{{ $companyPhone }}</p>
                        <p class="text-[10px] font-bold text-gray-800">প্রোপাইটরঃ {{ $proprietor }}</p>
                    </div>

                    <div class="space-y-1 text-[11px] border-b border-dashed border-gray-400 pb-2 font-mono">
                        <div class="flex justify-between"><span>ডেলিভারি: {{ $deliveryNo }}</span><span>কাস্টমার: {{ $printChallan->customer_name }}</span></div>
                        <div class="flex justify-between"><span>চালান: {{ $printChallan->challan_no }}</span><span>ঠিকানা: {{ $printChallan->customer_address ?: 'ঘোড়াঘাট' }}</span></div>
                        <div class="flex justify-between"><span>তারিখ: {{ $printChallan->date ? \Carbon\Carbon::parse($printChallan->date)->format('d-m-Y') : now()->format('d-m-Y') }}</span><span>মোবাইল: {{ $printChallan->customer_phone }}</span></div>
                    </div>

                    <div class="border-b border-dashed border-gray-400 pb-2 text-[11px]">
                        <div class="flex justify-between font-bold border-b border-gray-300 pb-1 mb-1 text-gray-900"><span>শ্রেণি</span><span>ডেলিভারি</span><span>ডে.বাকি</span><span>সময়</span></div>
                        @foreach($printChallan->items as $item)
                            <div class="flex justify-between font-mono"><span>{{ $item->category_name }}</span><span>{{ number_format($item->delivered_quantity ?: $item->quantity) }}</span><span>{{ number_format(max(0, $item->quantity - ($item->delivered_quantity ?: $item->quantity))) }}</span><span>{{ $printChallan->date ? \Carbon\Carbon::parse($printChallan->date)->format('বিকেল h:i') : '' }}</span></div>
                        @endforeach
                    </div>

                    <div class="space-y-1 text-[11px] font-mono border-b border-dashed border-gray-400 pb-2">
                        <div class="flex justify-between font-bold text-gray-900"><span>মোট ডেলিভারি</span><span>= {{ number_format($printChallan->items->sum(fn($i) => $i->delivered_quantity ?: $i->quantity)) }}/-</span></div>
                        <div class="flex justify-between text-gray-700"><span>ড্রাইভার</span><span>{{ $driverName }}</span></div>
                        <div class="flex justify-between text-gray-700"><span>গাড়ি নং</span><span>{{ $vehicleNo }}</span></div>
                        <div class="flex justify-between text-gray-700"><span>গাড়ি ভাড়া</span><span>৳ {{ number_format($vehicleRent) }}</span></div>
                    </div>

                    <div class="flex justify-between text-[10px] font-semibold text-gray-800 pt-4">
                        <span class="border-t border-dashed border-gray-500 pt-0.5 px-1">গ্রাহকের স্বাক্ষর</span>
                        <span class="border-t border-dashed border-gray-500 pt-0.5 px-1">ম্যানেজারের স্বাক্ষর</span>
                    </div>

                    <div class="text-center pt-2 border-t border-dashed border-gray-400 space-y-0.5 text-[10px]">
                        <p class="font-bold text-gray-800">চালান/রশিদ ছাড়া লেনদেন করবেন না</p>
                        <p class="text-gray-400 font-mono text-[9px]">Software By - CODENEXTIT.COM</p>
                    </div>
                </div>

                <div class="text-center text-[10px] font-bold text-gray-500 py-1">--- অফিস কপি ---</div>

                <!-- Office Slip -->
                <div class="space-y-3">
                    <div class="text-center space-y-1 border-b border-dashed border-gray-400 pb-2">
                        <p class="text-[11px] underline font-bold text-gray-800">ডেলিভারি রশিদ (অফিস কপি)</p>
                        <h2 class="text-xl font-black text-gray-900 tracking-wide">{{ $companyName }}</h2>
                        <p class="text-[10px] text-gray-600 font-medium">{{ $companyAddress }}</p>
                        <p class="text-[10px] text-gray-600 font-mono">{{ $companyPhone }}</p>
                        <p class="text-[10px] font-bold text-gray-800">প্রোপাইটরঃ {{ $proprietor }}</p>
                    </div>

                    <div class="space-y-1 text-[11px] border-b border-dashed border-gray-400 pb-2 font-mono">
                        <div class="flex justify-between"><span>ডেলিভারি: {{ $deliveryNo }}</span><span>কাস্টমার: {{ $printChallan->customer_name }}</span></div>
                        <div class="flex justify-between"><span>চালান: {{ $printChallan->challan_no }}</span><span>ঠিকানা: {{ $printChallan->customer_address ?: 'ঘোড়াঘাট' }}</span></div>
                        <div class="flex justify-between"><span>তারিখ: {{ $printChallan->date ? \Carbon\Carbon::parse($printChallan->date)->format('d-m-Y') : now()->format('d-m-Y') }}</span><span>মোবাইল: {{ $printChallan->customer_phone }}</span></div>
                    </div>

                    <div class="border-b border-dashed border-gray-400 pb-2 text-[11px]">
                        <div class="flex justify-between font-bold border-b border-gray-300 pb-1 mb-1 text-gray-900"><span>শ্রেণি</span><span>ডেলিভারি</span><span>ডে.বাকি</span><span>সময়</span></div>
                        @foreach($printChallan->items as $item)
                            <div class="flex justify-between font-mono"><span>{{ $item->category_name }}</span><span>{{ number_format($item->delivered_quantity ?: $item->quantity) }}</span><span>{{ number_format(max(0, $item->quantity - ($item->delivered_quantity ?: $item->quantity))) }}</span><span>{{ $printChallan->date ? \Carbon\Carbon::parse($printChallan->date)->format('বিকেল h:i') : '' }}</span></div>
                        @endforeach
                    </div>

                    <div class="space-y-1 text-[11px] font-mono border-b border-dashed border-gray-400 pb-2">
                        <div class="flex justify-between font-bold text-gray-900"><span>মোট ডেলিভারি</span><span>= {{ number_format($printChallan->items->sum(fn($i) => $i->delivered_quantity ?: $i->quantity)) }}/-</span></div>
                        <div class="flex justify-between text-gray-700"><span>ড্রাইভার</span><span>{{ $driverName }}</span></div>
                        <div class="flex justify-between text-gray-700"><span>গাড়ি নং</span><span>{{ $vehicleNo }}</span></div>
                        <div class="flex justify-between text-gray-700"><span>গাড়ি ভাড়া</span><span>৳ {{ number_format($vehicleRent) }}</span></div>
                    </div>

                    <div class="flex justify-between text-[10px] font-semibold text-gray-800 pt-4">
                        <span class="border-t border-dashed border-gray-500 pt-0.5 px-1">গ্রাহকের স্বাক্ষর</span>
                        <span class="border-t border-dashed border-gray-500 pt-0.5 px-1">ম্যানেজারের স্বাক্ষর</span>
                    </div>

                    <div class="text-center pt-2 border-t border-dashed border-gray-400 space-y-0.5 text-[10px]">
                        <p class="font-bold text-gray-800">চালান/রশিদ ছাড়া লেনদেন করবেন না</p>
                        <p class="text-gray-400 font-mono text-[9px]">Software By - CODENEXTIT.COM</p>
                    </div>
                </div>
            </div>
        @else
            <x-print-layout type="pos-dual" :challan="$printChallan" />
        @endif
    </div>

</div>
@endif

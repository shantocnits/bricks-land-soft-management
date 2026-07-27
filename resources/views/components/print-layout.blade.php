@props([
    'type' => 'a4-customer', // a4-customer, a4-dual, pos-customer, pos-dual, table-report
    'challan' => null,
    'title' => 'চালান কপি',
    'isDelivery' => false,
])

@php
    $setting = \App\Models\Setting::first();
    $companyName = $setting->site_title ?? $setting->company_name ?? 'ডেমো ব্রিকস';
    $companyAddress = $setting->company_address ?? 'হিলালিপাড়া,কাটাবাড়ি,গোবিন্দগঞ্জ';
    $companyPhone = $setting->company_phone ?? '০১৯১০৩-০০০-০০০, ০১৯১০৩-০০০-০০০';
    $proprietor = $setting->proprietor_name ?? 'মো: মালিক মিয়া';
    $printTime = now()->format('d-m-Y h:i A');
@endphp

<div class="print-layout-wrapper text-gray-900 font-sans">
    
    {{-- Dynamic Print Page CSS --}}
    <style>
        @media print {
                @page {
    @if($type === 'a4-customer')
        size: A4 portrait;
        margin: 6mm;

    @elseif($type === 'a4-dual')
        size: A4 landscape;
        margin: 4mm;

    @elseif($type === 'pos-customer')
        size: 80mm auto;
        margin: 2mm;

    @elseif($type === 'pos-dual')
        size: A4 portrait;
        margin: 4mm;

    @else
        size: A4 portrait;
        margin: 6mm;
    @endif
}
            html, body {
                background: #ffffff !important;
                color: #000000 !important;
                margin: 0 !important;
                padding: 0 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            .print-layout-wrapper {
                margin: 0 !important;
                padding: 0mm !important;
                border: none !important;
                box-shadow: none !important;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>

    {{-- ==================== MODE 1: A4 Single Customer Copy ==================== --}}
    @if($type === 'a4-customer')
        <div class="bg-white p-2 sm:p-4 text-gray-900 space-y-5 max-w-4xl mx-auto shadow-none border-0">
            
            <!-- Header -->
            <div class="flex justify-between items-start border-b border-gray-300 pb-3">
                <div class="flex items-center gap-3">
                    <div class="w-14 h-14 rounded-xl bg-red-50 flex items-center justify-center p-2 shrink-0 border border-red-100">
                        <svg class="w-10 h-10 text-red-500" viewBox="0 0 64 64" fill="currentColor">
                            <path d="M26 12 L38 12 L44 50 L20 50 Z" fill="#EF4444"/>
                            <path d="M22 24 L42 24 L44 32 L20 32 Z" fill="#FFFFFF"/>
                            <path d="M12 50 L52 50 L56 58 L8 58 Z" fill="#EF4444"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-black text-gray-900 tracking-wide">{{ $companyName }}</h2>
                        <p class="text-xs text-gray-600 font-medium">{{ $companyAddress }}</p>
                        <p class="text-xs text-gray-600 font-mono">{{ $companyPhone }}</p>
                        <p class="text-xs font-bold text-gray-800">প্রোপাইটর: {{ $proprietor }}</p>
                    </div>
                </div>
                <div class="text-right space-y-1">
                    <h1 class="text-3xl font-black text-gray-900 uppercase tracking-wider font-mono">INVOICE</h1>
                    <p class="text-xs font-semibold text-gray-500">গ্রাহক চালান কপি</p>
                    <p class="text-[11px] text-gray-500 font-mono">প্রিন্ট: {{ $printTime }}</p>
                </div>
            </div>

            <!-- Customer Details Metadata Box -->
            @if($challan)
            <div class="flex justify-between items-start text-xs bg-gray-50/80 p-3.5 rounded-xl border border-gray-200">
                <div class="space-y-1.5">
                    <p class="text-gray-800"><span class="font-bold">কাস্টমার আইডি:</span> {{ $challan->ledger_id ?: '১২' }}</p>
                    <p class="text-gray-800"><span class="font-bold">চালানের তারিখ:</span> {{ $challan->date ? \Carbon\Carbon::parse($challan->date)->format('d-m-Y') : now()->format('d-m-Y') }}, {{ now()->format('A h:i:s') }}</p>
                    <p class="text-gray-800"><span class="font-bold">ইস্যু করেছে:</span></p>
                </div>
                <div class="text-right space-y-1 pl-4 border-r-4 border-black pr-2">
                    <p class="text-gray-900 font-black text-sm">{{ $challan->customer_name }}</p>
                    <p class="text-gray-700 font-semibold">{{ $challan->customer_address ?: 'ঘোড়াঘাট' }}</p>
                    <p class="text-gray-700 font-mono font-bold">{{ $challan->customer_phone ?: '০১৬৫৬৪৫৬৪৫৬' }}</p>
                </div>
            </div>

            <!-- Items Table -->
            <div class="overflow-x-auto rounded-xl border border-gray-200">
                <table class="w-full text-xs text-left">
                    <thead>
                        <tr class="bg-gray-100 text-gray-900 font-bold border-b border-gray-200">
                            <th class="p-2.5 text-center">চালান নং</th>
                            <th class="p-2.5 text-left">শ্রেণি</th>
                            <th class="p-2.5 text-center">পরিমাণ</th>
                            <th class="p-2.5 text-right">দর</th>
                            <th class="p-2.5 text-right">মূল্য</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 font-sans">
                        @forelse($challan->items as $item)
                            <tr>
                                <td class="p-2.5 text-center font-bold font-mono">{{ $challan->challan_no }}</td>
                                <td class="p-2.5 text-left font-semibold text-gray-900">{{ $item->category_name }}</td>
                                <td class="p-2.5 text-center font-mono font-bold">{{ number_format($item->quantity) }}</td>
                                <td class="p-2.5 text-right font-mono">৳ {{ number_format($item->rate, 2) }}</td>
                                <td class="p-2.5 text-right font-mono font-bold text-gray-900">৳ {{ number_format($item->amount, 0) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="p-3 text-center text-gray-400">কোন আইটেম ডাটা পাওয়া যায়নি</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Notes, Stamp & Summary Box Grid -->
            <div class="grid grid-cols-2 gap-6 items-end pt-1">
                <div class="space-y-4">
                    <div class="bg-white border border-gray-200 rounded-xl p-3 text-[11px] text-gray-800 space-y-1">
                        <p class="font-bold underline mb-1 text-gray-900">বিশেষ দ্রষ্টব্য:</p>
                        <p>১। চালান অথবা রশিদ ছাড়া কোনো লেনদেন করবেন না।</p>
                        <p>২। ইট ডেলিভারি নেওয়ার পর কোনো অভিযোগ গ্রহণ যোগ্য হবে না।</p>
                        <p>৩। চালান করার ৩০ দিনের মধ্যে ইট ডেলিভারি নিতে হবে।</p>
                    </div>
                    @if($challan->due > 0)
                        <div class="border-2 border-red-500 rounded-xl p-3 text-center">
                            <p class="text-xs font-bold text-red-500">পরিশোধের তারিখ :</p>
                            <p class="text-lg font-black text-red-600 mt-0.5">{{ $challan->due_payment_date ? \Carbon\Carbon::parse($challan->due_payment_date)->format('d-m-Y') : now()->addDay()->format('d-m-Y') }}</p>
                        </div>
                    @else
                        <div class="inline-block border-2 border-green-600 rounded-xl px-8 py-2 text-center font-black text-xl tracking-wide uppercase text-green-700">
                            পরিশোধিত
                        </div>
                    @endif
                </div>

                <div class="bg-gray-50/80 rounded-xl p-3.5 border border-gray-200 space-y-1.5 text-xs font-sans">
                    <div class="flex justify-between items-center text-gray-800"><span class="font-semibold">মোট মূল্য</span><span class="font-mono font-bold text-gray-900">৳ {{ number_format($challan->total_value ?: $challan->items->sum('amount'), 0) }}</span></div>
                    <div class="flex justify-between items-center text-gray-800"><span class="font-semibold">ছাড়</span><span class="font-mono font-bold text-gray-900">৳ {{ number_format($challan->discount ?: 0, 0) }}</span></div>
                    <div class="flex justify-between items-center text-gray-800"><span class="font-semibold">গাড়ি ভাড়া</span><span class="font-mono font-bold text-gray-900">৳ {{ number_format($challan->transport_rent ?: 0, 0) }}</span></div>
                    <div class="flex justify-between items-center font-extrabold text-gray-900 pt-1.5 border-t border-gray-200 text-sm"><span>সর্বমোট</span><span class="font-mono">৳ {{ number_format($challan->grand_total, 0) }}</span></div>
                    <div class="flex justify-between items-center text-gray-900 font-bold"><span>জমা</span><span class="font-mono">৳ {{ number_format($challan->cash, 0) }}</span></div>
                    <div class="flex justify-between items-center text-gray-900 font-bold"><span>বাকি</span><span class="font-mono">৳ {{ number_format($challan->due, 0) }}</span></div>
                </div>
            </div>
            @else
                <div>{{ $slot }}</div>
            @endif

            <!-- Signatures -->
            <div class="flex justify-between items-center pt-8 border-t border-gray-300 text-xs font-semibold text-gray-800">
                <span class="border-t border-black pt-1 px-8">গ্রাহকের স্বাক্ষর</span>
                <span class="border-t border-black pt-1 px-8">ম্যানেজারের স্বাক্ষর</span>
            </div>

            <div class="text-center pt-2 text-[10px] text-gray-400 font-mono uppercase tracking-wider">
                Software By - CODENEXTIT.COM
            </div>
        </div>

    {{-- ==================== MODE 2: A4 Dual Side-by-Side Copies ==================== --}}
    @elseif($type === 'a4-dual')
        <div class="grid grid-cols-2 gap-6 items-stretch w-full p-2 relative bg-white border-0">
            
            <!-- Left Column: Customer Copy -->
            <div class="bg-white p-2 sm:p-4 text-gray-900 space-y-5 max-w-4xl mx-auto shadow-none border-0">
            
            <!-- Header -->
            <div class="flex justify-between items-start border-b border-gray-300 pb-3">
                <div class="flex items-center gap-3">
                    <div class="w-14 h-14 rounded-xl bg-red-50 flex items-center justify-center p-2 shrink-0 border border-red-100">
                        <svg class="w-10 h-10 text-red-500" viewBox="0 0 64 64" fill="currentColor">
                            <path d="M26 12 L38 12 L44 50 L20 50 Z" fill="#EF4444"/>
                            <path d="M22 24 L42 24 L44 32 L20 32 Z" fill="#FFFFFF"/>
                            <path d="M12 50 L52 50 L56 58 L8 58 Z" fill="#EF4444"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-black text-gray-900 tracking-wide">{{ $companyName }}</h2>
                        <p class="text-xs text-gray-600 font-medium">{{ $companyAddress }}</p>
                        <p class="text-xs text-gray-600 font-mono">{{ $companyPhone }}</p>
                        <p class="text-xs font-bold text-gray-800">প্রোপাইটর: {{ $proprietor }}</p>
                    </div>
                </div>
                <div class="text-right space-y-1">
                    <h1 class="text-3xl font-black text-gray-900 uppercase tracking-wider font-mono">INVOICE</h1>
                    <p class="text-xs font-semibold text-gray-500">গ্রাহক চালান কপি</p>
                    <p class="text-[11px] text-gray-500 font-mono">প্রিন্ট: {{ $printTime }}</p>
                </div>
            </div>

            <!-- Customer Details Metadata Box -->
            @if($challan)
            <div class="flex justify-between items-start text-xs bg-gray-50/80 p-3.5 rounded-xl border border-gray-200">
                <div class="space-y-1.5">
                    <p class="text-gray-800"><span class="font-bold">কাস্টমার আইডি:</span> {{ $challan->ledger_id ?: '১২' }}</p>
                    <p class="text-gray-800"><span class="font-bold">চালানের তারিখ:</span> {{ $challan->date ? \Carbon\Carbon::parse($challan->date)->format('d-m-Y') : now()->format('d-m-Y') }}, {{ now()->format('A h:i:s') }}</p>
                    <p class="text-gray-800"><span class="font-bold">ইস্যু করেছে:</span></p>
                </div>
                <div class="text-right space-y-1 pl-4 border-r-4 border-black pr-2">
                    <p class="text-gray-900 font-black text-sm">{{ $challan->customer_name }}</p>
                    <p class="text-gray-700 font-semibold">{{ $challan->customer_address ?: 'ঘোড়াঘাট' }}</p>
                    <p class="text-gray-700 font-mono font-bold">{{ $challan->customer_phone ?: '০১৬৫৬৪৫৬৪৫৬' }}</p>
                </div>
            </div>

            <!-- Items Table -->
            <div class="overflow-x-auto rounded-xl border border-gray-200">
                <table class="w-full text-xs text-left">
                    <thead>
                        <tr class="bg-gray-100 text-gray-900 font-bold border-b border-gray-200">
                            <th class="p-2.5 text-center">চালান নং</th>
                            <th class="p-2.5 text-left">শ্রেণি</th>
                            <th class="p-2.5 text-center">পরিমাণ</th>
                            <th class="p-2.5 text-right">দর</th>
                            <th class="p-2.5 text-right">মূল্য</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 font-sans">
                        @forelse($challan->items as $item)
                            <tr>
                                <td class="p-2.5 text-center font-bold font-mono">{{ $challan->challan_no }}</td>
                                <td class="p-2.5 text-left font-semibold text-gray-900">{{ $item->category_name }}</td>
                                <td class="p-2.5 text-center font-mono font-bold">{{ number_format($item->quantity) }}</td>
                                <td class="p-2.5 text-right font-mono">৳ {{ number_format($item->rate, 2) }}</td>
                                <td class="p-2.5 text-right font-mono font-bold text-gray-900">৳ {{ number_format($item->amount, 0) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="p-3 text-center text-gray-400">কোন আইটেম ডাটা পাওয়া যায়নি</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Notes, Stamp & Summary Box Grid -->
            <div class="grid grid-cols-2 gap-6 items-end pt-1">
                <div class="space-y-4">
                    <div class="bg-white border border-gray-200 rounded-xl p-3 text-[11px] text-gray-800 space-y-1">
                        <p class="font-bold underline mb-1 text-gray-900">বিশেষ দ্রষ্টব্য:</p>
                        <p>১। চালান অথবা রশিদ ছাড়া কোনো লেনদেন করবেন না।</p>
                        <p>২। ইট ডেলিভারি নেওয়ার পর কোনো অভিযোগ গ্রহণ যোগ্য হবে না।</p>
                        <p>৩। চালান করার ৩০ দিনের মধ্যে ইট ডেলিভারি নিতে হবে।</p>
                    </div>
                    @if($challan->due > 0)
                        <div class="border-2 border-red-500 rounded-xl p-3 text-center">
                            <p class="text-xs font-bold text-red-500">পরিশোধের তারিখ :</p>
                            <p class="text-lg font-black text-red-600 mt-0.5">{{ $challan->due_payment_date ? \Carbon\Carbon::parse($challan->due_payment_date)->format('d-m-Y') : now()->addDay()->format('d-m-Y') }}</p>
                        </div>
                    @else
                        <div class="inline-block border-2 border-green-600 rounded-xl px-8 py-2 text-center font-black text-xl tracking-wide uppercase text-green-700">
                            পরিশোধিত
                        </div>
                    @endif
                </div>

                <div class="bg-gray-50/80 rounded-xl p-3.5 border border-gray-200 space-y-1.5 text-xs font-sans">
                    <div class="flex justify-between items-center text-gray-800"><span class="font-semibold">মোট মূল্য</span><span class="font-mono font-bold text-gray-900">৳ {{ number_format($challan->total_value ?: $challan->items->sum('amount'), 0) }}</span></div>
                    <div class="flex justify-between items-center text-gray-800"><span class="font-semibold">ছাড়</span><span class="font-mono font-bold text-gray-900">৳ {{ number_format($challan->discount ?: 0, 0) }}</span></div>
                    <div class="flex justify-between items-center text-gray-800"><span class="font-semibold">গাড়ি ভাড়া</span><span class="font-mono font-bold text-gray-900">৳ {{ number_format($challan->transport_rent ?: 0, 0) }}</span></div>
                    <div class="flex justify-between items-center font-extrabold text-gray-900 pt-1.5 border-t border-gray-200 text-sm"><span>সর্বমোট</span><span class="font-mono">৳ {{ number_format($challan->grand_total, 0) }}</span></div>
                    <div class="flex justify-between items-center text-gray-900 font-bold"><span>জমা</span><span class="font-mono">৳ {{ number_format($challan->cash, 0) }}</span></div>
                    <div class="flex justify-between items-center text-gray-900 font-bold"><span>বাকি</span><span class="font-mono">৳ {{ number_format($challan->due, 0) }}</span></div>
                </div>
            </div>
            @else
                <div>{{ $slot }}</div>
            @endif

            <!-- Signatures -->
            <div class="flex justify-between items-center pt-8 border-t border-gray-300 text-xs font-semibold text-gray-800">
                <span class="border-t border-black pt-1 px-8">গ্রাহকের স্বাক্ষর</span>
                <span class="border-t border-black pt-1 px-8">ম্যানেজারের স্বাক্ষর</span>
            </div>

            <div class="text-center pt-2 text-[10px] text-gray-400 font-mono uppercase tracking-wider">
                Software By - CODENEXTIT.COM
            </div>
        </div>

            <!-- Middle Dashed Divider Line -->
            <div class="absolute inset-y-0 left-1/2 -ml-px border-r-2 border-dashed border-gray-400 pointer-events-none"></div>

            <!-- Right Column: Office Copy -->
            <div class="bg-white p-2 sm:p-4 text-gray-900 space-y-5 max-w-4xl mx-auto shadow-none border-0">
            
            <!-- Header -->
            <div class="flex justify-between items-start border-b border-gray-300 pb-3">
                <div class="flex items-center gap-3">
                    <div class="w-14 h-14 rounded-xl bg-red-50 flex items-center justify-center p-2 shrink-0 border border-red-100">
                        <svg class="w-10 h-10 text-red-500" viewBox="0 0 64 64" fill="currentColor">
                            <path d="M26 12 L38 12 L44 50 L20 50 Z" fill="#EF4444"/>
                            <path d="M22 24 L42 24 L44 32 L20 32 Z" fill="#FFFFFF"/>
                            <path d="M12 50 L52 50 L56 58 L8 58 Z" fill="#EF4444"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-black text-gray-900 tracking-wide">{{ $companyName }}</h2>
                        <p class="text-xs text-gray-600 font-medium">{{ $companyAddress }}</p>
                        <p class="text-xs text-gray-600 font-mono">{{ $companyPhone }}</p>
                        <p class="text-xs font-bold text-gray-800">প্রোপাইটর: {{ $proprietor }}</p>
                    </div>
                </div>
                <div class="text-right space-y-1">
                    <h1 class="text-3xl font-black text-gray-900 uppercase tracking-wider font-mono">INVOICE</h1>
                    <p class="text-xs font-semibold text-gray-500">গ্রাহক চালান কপি</p>
                    <p class="text-[11px] text-gray-500 font-mono">প্রিন্ট: {{ $printTime }}</p>
                </div>
            </div>

            <!-- Customer Details Metadata Box -->
            @if($challan)
            <div class="flex justify-between items-start text-xs bg-gray-50/80 p-3.5 rounded-xl border border-gray-200">
                <div class="space-y-1.5">
                    <p class="text-gray-800"><span class="font-bold">কাস্টমার আইডি:</span> {{ $challan->ledger_id ?: '১২' }}</p>
                    <p class="text-gray-800"><span class="font-bold">চালানের তারিখ:</span> {{ $challan->date ? \Carbon\Carbon::parse($challan->date)->format('d-m-Y') : now()->format('d-m-Y') }}, {{ now()->format('A h:i:s') }}</p>
                    <p class="text-gray-800"><span class="font-bold">ইস্যু করেছে:</span></p>
                </div>
                <div class="text-right space-y-1 pl-4 border-r-4 border-black pr-2">
                    <p class="text-gray-900 font-black text-sm">{{ $challan->customer_name }}</p>
                    <p class="text-gray-700 font-semibold">{{ $challan->customer_address ?: 'ঘোড়াঘাট' }}</p>
                    <p class="text-gray-700 font-mono font-bold">{{ $challan->customer_phone ?: '০১৬৫৬৪৫৬৪৫৬' }}</p>
                </div>
            </div>

            <!-- Items Table -->
            <div class="overflow-x-auto rounded-xl border border-gray-200">
                <table class="w-full text-xs text-left">
                    <thead>
                        <tr class="bg-gray-100 text-gray-900 font-bold border-b border-gray-200">
                            <th class="p-2.5 text-center">চালান নং</th>
                            <th class="p-2.5 text-left">শ্রেণি</th>
                            <th class="p-2.5 text-center">পরিমাণ</th>
                            <th class="p-2.5 text-right">দর</th>
                            <th class="p-2.5 text-right">মূল্য</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 font-sans">
                        @forelse($challan->items as $item)
                            <tr>
                                <td class="p-2.5 text-center font-bold font-mono">{{ $challan->challan_no }}</td>
                                <td class="p-2.5 text-left font-semibold text-gray-900">{{ $item->category_name }}</td>
                                <td class="p-2.5 text-center font-mono font-bold">{{ number_format($item->quantity) }}</td>
                                <td class="p-2.5 text-right font-mono">৳ {{ number_format($item->rate, 2) }}</td>
                                <td class="p-2.5 text-right font-mono font-bold text-gray-900">৳ {{ number_format($item->amount, 0) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="p-3 text-center text-gray-400">কোন আইটেম ডাটা পাওয়া যায়নি</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Notes, Stamp & Summary Box Grid -->
            <div class="grid grid-cols-2 gap-6 items-end pt-1">
                <div class="space-y-4">
                    <div class="bg-white border border-gray-200 rounded-xl p-3 text-[11px] text-gray-800 space-y-1">
                        <p class="font-bold underline mb-1 text-gray-900">বিশেষ দ্রষ্টব্য:</p>
                        <p>১। চালান অথবা রশিদ ছাড়া কোনো লেনদেন করবেন না।</p>
                        <p>২। ইট ডেলিভারি নেওয়ার পর কোনো অভিযোগ গ্রহণ যোগ্য হবে না।</p>
                        <p>৩। চালান করার ৩০ দিনের মধ্যে ইট ডেলিভারি নিতে হবে।</p>
                    </div>
                    @if($challan->due > 0)
                        <div class="border-2 border-red-500 rounded-xl p-3 text-center">
                            <p class="text-xs font-bold text-red-500">পরিশোধের তারিখ :</p>
                            <p class="text-lg font-black text-red-600 mt-0.5">{{ $challan->due_payment_date ? \Carbon\Carbon::parse($challan->due_payment_date)->format('d-m-Y') : now()->addDay()->format('d-m-Y') }}</p>
                        </div>
                    @else
                        <div class="inline-block border-2 border-green-600 rounded-xl px-8 py-2 text-center font-black text-xl tracking-wide uppercase text-green-700">
                            পরিশোধিত
                        </div>
                    @endif
                </div>

                <div class="bg-gray-50/80 rounded-xl p-3.5 border border-gray-200 space-y-1.5 text-xs font-sans">
                    <div class="flex justify-between items-center text-gray-800"><span class="font-semibold">মোট মূল্য</span><span class="font-mono font-bold text-gray-900">৳ {{ number_format($challan->total_value ?: $challan->items->sum('amount'), 0) }}</span></div>
                    <div class="flex justify-between items-center text-gray-800"><span class="font-semibold">ছাড়</span><span class="font-mono font-bold text-gray-900">৳ {{ number_format($challan->discount ?: 0, 0) }}</span></div>
                    <div class="flex justify-between items-center text-gray-800"><span class="font-semibold">গাড়ি ভাড়া</span><span class="font-mono font-bold text-gray-900">৳ {{ number_format($challan->transport_rent ?: 0, 0) }}</span></div>
                    <div class="flex justify-between items-center font-extrabold text-gray-900 pt-1.5 border-t border-gray-200 text-sm"><span>সর্বমোট</span><span class="font-mono">৳ {{ number_format($challan->grand_total, 0) }}</span></div>
                    <div class="flex justify-between items-center text-gray-900 font-bold"><span>জমা</span><span class="font-mono">৳ {{ number_format($challan->cash, 0) }}</span></div>
                    <div class="flex justify-between items-center text-gray-900 font-bold"><span>বাকি</span><span class="font-mono">৳ {{ number_format($challan->due, 0) }}</span></div>
                </div>
            </div>
            @else
                <div>{{ $slot }}</div>
            @endif

            <!-- Signatures -->
            <div class="flex justify-between items-center pt-8 border-t border-gray-300 text-xs font-semibold text-gray-800">
                <span class="border-t border-black pt-1 px-8">গ্রাহকের স্বাক্ষর</span>
                <span class="border-t border-black pt-1 px-8">ম্যানেজারের স্বাক্ষর</span>
            </div>

            <div class="text-center pt-2 text-[10px] text-gray-400 font-mono uppercase tracking-wider">
                Software By - CODENEXTIT.COM
            </div>
        </div>
        </div>

    {{-- ==================== MODE 3: POS Single Thermal Slip (Aligned Left) ==================== --}}
    @elseif($type === 'pos-customer')
        <div class="max-w-[300px] mr-auto ml-0 bg-white p-2 text-gray-900 font-sans text-xs space-y-3 border-0 shadow-none text-left">
            <div class="text-center space-y-1 border-b border-dashed border-gray-400 pb-2">
                <p class="text-[11px] underline font-bold text-gray-800">চালান রশিদ</p>
                <h2 class="text-xl font-black text-gray-900 tracking-wide">{{ $companyName }}</h2>
                <p class="text-[11px] text-gray-600 font-medium">{{ $companyAddress }}</p>
                <p class="text-[11px] text-gray-600 font-mono">{{ $companyPhone }}</p>
                <p class="text-[11px] font-bold text-gray-800">প্রোপাইটর: {{ $proprietor }}</p>
            </div>

            @if($challan)
            <div class="space-y-1 text-[11px] border-b border-dashed border-gray-400 pb-2 font-mono">
                <div class="flex justify-between"><span>চালান: {{ $challan->challan_no }}</span><span>কাস্টমার: {{ $challan->customer_name }}</span></div>
                <div class="flex justify-between"><span>তারিখ: {{ $challan->date ? \Carbon\Carbon::parse($challan->date)->format('d-m-Y') : now()->format('d-m-Y') }}</span><span>ঠিকানা: {{ $challan->customer_address ?: 'ঘোড়াঘাট' }}</span></div>
                <div class="flex justify-between"><span>সময়: {{ now()->format('h:i A') }}</span><span>মোবাইল: {{ $challan->customer_phone ?: '০১৬৫৬৪৫৬৪৫৬' }}</span></div>
            </div>

            <div class="border-b border-dashed border-gray-400 pb-2 text-[11px]">
                <div class="flex justify-between font-bold border-b border-gray-200 pb-1 mb-1 text-gray-900"><span>শ্রেণি</span><span>পরিমাণ</span><span>দর</span><span>মূল্য</span></div>
                @foreach($challan->items as $item)
                    <div class="flex justify-between font-mono"><span>{{ $item->category_name }}</span><span>{{ $item->quantity }}</span><span>৳{{ $item->rate }}</span><span>৳{{ number_format($item->amount) }}</span></div>
                @endforeach
            </div>

            <div class="space-y-1.5 text-[11px] font-mono">
                <div class="flex justify-between text-gray-700"><span>মোট মূল্য</span><span>৳ {{ number_format($challan->total_value ?: $challan->items->sum('amount')) }}</span></div>
                <div class="flex justify-between text-gray-700"><span>ছাড়</span><span>৳ {{ number_format($challan->discount ?: 0) }}</span></div>
                <div class="flex justify-between text-gray-700"><span>গাড়ি ভাড়া</span><span>৳ {{ number_format($challan->transport_rent ?: 0) }}</span></div>
                <div class="flex justify-between font-bold text-gray-900 pt-1 border-t border-gray-200 text-xs"><span>সর্বমোট</span><span>৳ {{ number_format($challan->grand_total) }}</span></div>
                <div class="flex justify-between font-bold text-emerald-600"><span>জমা</span><span>৳ {{ number_format($challan->cash) }}</span></div>
                <div class="flex justify-between font-bold text-rose-600"><span>বাকি</span><span>৳ {{ number_format($challan->due) }}</span></div>
            </div>
            @else
                <div>{{ $slot }}</div>
            @endif

            <div class="pt-6 border-t border-dashed border-gray-400 flex justify-between text-[10px] font-semibold text-gray-800">
                <span class="border-t border-gray-500 pt-0.5 px-2">গ্রাহকের স্বাক্ষর</span>
                <span class="border-t border-gray-500 pt-0.5 px-2">ম্যানেজারের স্বাক্ষর</span>
            </div>

            <div class="text-center text-[10px] pt-2 border-t border-gray-200 space-y-0.5">
                <p class="font-bold text-gray-800">চালান/রশিদ ছাড়া লেনদেন করবেন না</p>
                <p class="text-gray-400 font-mono text-[9px]">Software By - CODENEXTIT.COM</p>
            </div>
        </div>

    {{-- ==================== MODE 4: POS Dual Stacked Slips (Aligned Left) ==================== --}}
    @elseif($type === 'pos-dual')
        <div class="max-w-[300px] mr-auto ml-0 space-y-4 font-sans text-xs border-0 shadow-none text-left">
            <!-- Customer Slip Top -->
            <div class="bg-white p-2 text-gray-900 space-y-2">
                <div class="text-center space-y-1 border-b border-dashed border-gray-400 pb-2">
                <p class="text-[11px] underline font-bold text-gray-800">চালান রশিদ</p>
                <h2 class="text-xl font-black text-gray-900 tracking-wide">{{ $companyName }}</h2>
                <p class="text-[11px] text-gray-600 font-medium">{{ $companyAddress }}</p>
                <p class="text-[11px] text-gray-600 font-mono">{{ $companyPhone }}</p>
                <p class="text-[11px] font-bold text-gray-800">প্রোপাইটর: {{ $proprietor }}</p>
            </div>

            @if($challan)
            <div class="space-y-1 text-[11px] border-b border-dashed border-gray-400 pb-2 font-mono">
                <div class="flex justify-between"><span>চালান: {{ $challan->challan_no }}</span><span>কাস্টমার: {{ $challan->customer_name }}</span></div>
                <div class="flex justify-between"><span>তারিখ: {{ $challan->date ? \Carbon\Carbon::parse($challan->date)->format('d-m-Y') : now()->format('d-m-Y') }}</span><span>ঠিকানা: {{ $challan->customer_address ?: 'ঘোড়াঘাট' }}</span></div>
                <div class="flex justify-between"><span>সময়: {{ now()->format('h:i A') }}</span><span>মোবাইল: {{ $challan->customer_phone ?: '০১৬৫৬৪৫৬৪৫৬' }}</span></div>
            </div>

            <div class="border-b border-dashed border-gray-400 pb-2 text-[11px]">
                <div class="flex justify-between font-bold border-b border-gray-200 pb-1 mb-1 text-gray-900"><span>শ্রেণি</span><span>পরিমাণ</span><span>দর</span><span>মূল্য</span></div>
                @foreach($challan->items as $item)
                    <div class="flex justify-between font-mono"><span>{{ $item->category_name }}</span><span>{{ $item->quantity }}</span><span>৳{{ $item->rate }}</span><span>৳{{ number_format($item->amount) }}</span></div>
                @endforeach
            </div>

            <div class="space-y-1.5 text-[11px] font-mono">
                <div class="flex justify-between text-gray-700"><span>মোট মূল্য</span><span>৳ {{ number_format($challan->total_value ?: $challan->items->sum('amount')) }}</span></div>
                <div class="flex justify-between text-gray-700"><span>ছাড়</span><span>৳ {{ number_format($challan->discount ?: 0) }}</span></div>
                <div class="flex justify-between text-gray-700"><span>গাড়ি ভাড়া</span><span>৳ {{ number_format($challan->transport_rent ?: 0) }}</span></div>
                <div class="flex justify-between font-bold text-gray-900 pt-1 border-t border-gray-200 text-xs"><span>সর্বমোট</span><span>৳ {{ number_format($challan->grand_total) }}</span></div>
                <div class="flex justify-between font-bold text-emerald-600"><span>জমা</span><span>৳ {{ number_format($challan->cash) }}</span></div>
                <div class="flex justify-between font-bold text-rose-600"><span>বাকি</span><span>৳ {{ number_format($challan->due) }}</span></div>
            </div>
            @else
                <div>{{ $slot }}</div>
            @endif

            <div class="pt-6 border-t border-dashed border-gray-400 flex justify-between text-[10px] font-semibold text-gray-800">
                <span class="border-t border-gray-500 pt-0.5 px-2">গ্রাহকের স্বাক্ষর</span>
                <span class="border-t border-gray-500 pt-0.5 px-2">ম্যানেজারের স্বাক্ষর</span>
            </div>

            <div class="text-center text-[10px] pt-2 border-t border-gray-200 space-y-0.5">
                <p class="font-bold text-gray-800">চালান/রশিদ ছাড়া লেনদেন করবেন না</p>
                <p class="text-gray-400 font-mono text-[9px]">Software By - CODENEXTIT.COM</p>
            </div>
            </div>

            <!-- Dashed Divider with Cut Text -->
            <div class="text-center text-[11px] font-mono text-gray-500 border-t border-b border-dashed border-gray-500 py-1">
                --- অফিস কপি ---
            </div>

            <!-- Office Slip Bottom -->
            <div class="bg-white p-2 text-gray-900 space-y-2">
                <div class="text-center space-y-1 border-b border-dashed border-gray-400 pb-2">
                <p class="text-[11px] underline font-bold text-gray-800">চালান রশিদ</p>
                <h2 class="text-xl font-black text-gray-900 tracking-wide">{{ $companyName }}</h2>
                <p class="text-[11px] text-gray-600 font-medium">{{ $companyAddress }}</p>
                <p class="text-[11px] text-gray-600 font-mono">{{ $companyPhone }}</p>
                <p class="text-[11px] font-bold text-gray-800">প্রোপাইটর: {{ $proprietor }}</p>
            </div>

            @if($challan)
            <div class="space-y-1 text-[11px] border-b border-dashed border-gray-400 pb-2 font-mono">
                <div class="flex justify-between"><span>চালান: {{ $challan->challan_no }}</span><span>কাস্টমার: {{ $challan->customer_name }}</span></div>
                <div class="flex justify-between"><span>তারিখ: {{ $challan->date ? \Carbon\Carbon::parse($challan->date)->format('d-m-Y') : now()->format('d-m-Y') }}</span><span>ঠিকানা: {{ $challan->customer_address ?: 'ঘোড়াঘাট' }}</span></div>
                <div class="flex justify-between"><span>সময়: {{ now()->format('h:i A') }}</span><span>মোবাইল: {{ $challan->customer_phone ?: '০১৬৫৬৪৫৬৪৫৬' }}</span></div>
            </div>

            <div class="border-b border-dashed border-gray-400 pb-2 text-[11px]">
                <div class="flex justify-between font-bold border-b border-gray-200 pb-1 mb-1 text-gray-900"><span>শ্রেণি</span><span>পরিমাণ</span><span>দর</span><span>মূল্য</span></div>
                @foreach($challan->items as $item)
                    <div class="flex justify-between font-mono"><span>{{ $item->category_name }}</span><span>{{ $item->quantity }}</span><span>৳{{ $item->rate }}</span><span>৳{{ number_format($item->amount) }}</span></div>
                @endforeach
            </div>

            <div class="space-y-1.5 text-[11px] font-mono">
                <div class="flex justify-between text-gray-700"><span>মোট মূল্য</span><span>৳ {{ number_format($challan->total_value ?: $challan->items->sum('amount')) }}</span></div>
                <div class="flex justify-between text-gray-700"><span>ছাড়</span><span>৳ {{ number_format($challan->discount ?: 0) }}</span></div>
                <div class="flex justify-between text-gray-700"><span>গাড়ি ভাড়া</span><span>৳ {{ number_format($challan->transport_rent ?: 0) }}</span></div>
                <div class="flex justify-between font-bold text-gray-900 pt-1 border-t border-gray-200 text-xs"><span>সর্বমোট</span><span>৳ {{ number_format($challan->grand_total) }}</span></div>
                <div class="flex justify-between font-bold text-emerald-600"><span>জমা</span><span>৳ {{ number_format($challan->cash) }}</span></div>
                <div class="flex justify-between font-bold text-rose-600"><span>বাকি</span><span>৳ {{ number_format($challan->due) }}</span></div>
            </div>
            @else
                <div>{{ $slot }}</div>
            @endif

            <div class="pt-6 border-t border-dashed border-gray-400 flex justify-between text-[10px] font-semibold text-gray-800">
                <span class="border-t border-gray-500 pt-0.5 px-2">গ্রাহকের স্বাক্ষর</span>
                <span class="border-t border-gray-500 pt-0.5 px-2">ম্যানেজারের স্বাক্ষর</span>
            </div>

            <div class="text-center text-[10px] pt-2 border-t border-gray-200 space-y-0.5">
                <p class="font-bold text-gray-800">চালান/রশিদ ছাড়া লেনদেন করবেন না</p>
                <p class="text-gray-400 font-mono text-[9px]">Software By - CODENEXTIT.COM</p>
            </div>
            </div>
        </div>

    {{-- ==================== MODE 5: Generic Table Report ==================== --}}
    @else
        <div class="bg-white p-4 text-gray-900 space-y-6 border-0">
            <div class="flex justify-between items-start border-b-2 border-gray-800 pb-3">
                <div>
                    <h2 class="text-2xl font-black text-gray-900 tracking-wide">{{ $companyName }}</h2>
                    <p class="text-xs text-gray-600">{{ $companyAddress }} | {{ $companyPhone }}</p>
                </div>
                <div class="text-right">
                    <h1 class="text-xl font-black text-gray-900">{{ $title }}</h1>
                    <p class="text-[11px] text-gray-500">প্রিন্ট: {{ $printTime }}</p>
                </div>
            </div>
            <div>{{ $slot }}</div>
            <div class="flex justify-between items-center pt-8 text-xs font-semibold">
                <span class="border-t border-black pt-1 px-6">মালিকের স্বাক্ষর</span>
                <span class="border-t border-black pt-1 px-6">ম্যানেজারের স্বাক্ষর</span>
            </div>
        </div>
    @endif

</div>

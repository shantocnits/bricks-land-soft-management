{{-- 
========================================================================================
🖨️ PROJECT PRINT PAPER LAYOUTS COMPONENT (x-print-layout)
========================================================================================
File Path: resources/views/components/print-layout.blade.php
Purpose: Contains paper print templates for all standard challans, invoices, statements & reports.

Supported Layout Modes ($type):
  1. 'a4-customer'  -> A4 Single Customer Copy Print Layout (চালান পেজ / কাস্টমার প্রোফাইল চালান পোট্রেট প্রিন্ট)
  2. 'a4-dual'      -> A4 Dual Customer + Office Side-by-Side Copies (সব চালান / কাস্টমার প্রোফাইল চালানের ল্যান্ডস্কেপ প্রিন্ট)
  3. 'pos-customer' -> POS Thermal Slip Single Customer Copy (থার্মাল কাস্টমার রসিদ প্রিন্ট)
  4. 'pos-dual'     -> POS Thermal Dual Stacked Customer + Office Slips (থার্মাল কাস্টমার+অফিস ডাবল রসিদ প্রিন্ট)
  5. 'table-report' -> Generic Table / Statement Report Print Layout (কাস্টমার স্টেটমেন্ট ও ড্যাশবোর্ড রিপোর্ট প্রিন্ট)
========================================================================================
--}}
@props([
    'type' => 'a4-customer',
    'challan' => null,
    'title' => 'চালান কপি',
    'isDelivery' => false,
    'payments' => null,
    'selectedLedger' => '',
    'ledgerGroup' => '',
    'totalBill' => 0,
    'totalAdvance' => 0,
    'totalDeduction' => 0,
    'totalPayment' => 0,
    'deliveries' => null,
    'collections' => null,
    'customerIdMap' => [],
    'netDueMap' => [],
    'reportTitle' => 'দৈনিক ডেলিভারি তালিকা',
    'reportDate' => null,
    'activeSeason' => null,
    'totalDeliverySum' => 0,
    'totalCollectionSum' => 0,
    'isDuePrint' => false,
])

@php
    $companyName = \App\Models\Setting::get('company_name_bn', 'ডেমো ব্রিকস');
    $companyAddress = \App\Models\Setting::get('address', 'হিলালিপাড়া,কাটাবাড়ি,গোবیندগঞ্জ');
    $companyPhone = \App\Models\Setting::get('invoice_phones') ?: \App\Models\Setting::get('owner_phone', '01901349901,01901349906');
    $proprietor = \App\Models\Setting::get('owner_name', 'মো: মালিক মিয়া');
    $printTime = now()->format('d-m-Y h:i A');

    $cLogoSetting = \App\Models\Setting::get('logo_url') ?: \App\Models\Setting::get('company_logo');
    $cLogoSrc = null;
    if ($cLogoSetting && file_exists(public_path('storage/' . $cLogoSetting))) {
        $cLogoSrc = asset('storage/' . $cLogoSetting);
    } elseif (file_exists(public_path('assets/logo.png'))) {
        $cLogoSrc = asset('assets/logo.png');
    }
@endphp

<div class="print-layout-wrapper text-gray-900 font-sans">
    
    {{-- Dynamic Print Page CSS Rules --}}
    <style>
        @media print {
            @page {
                @if($type === 'a4-customer')
                    size: A4 portrait !important;
                    margin: 5mm !important;

                @elseif($type === 'a4-dual')
                    size: A4 landscape !important;
                    margin: 5mm !important;

                @elseif($type === 'pos-customer')
                    size: 80mm auto !important;
                    margin: 2mm !important;

                @elseif($type === 'pos-dual')
                    size: A4 portrait !important;
                    margin: 5mm !important;

                @else
                    size: A4 portrait !important;
                    margin: 5mm !important;
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

    {{-- ======================================================================= --}}
    {{-- 📄 MODE 1: A4 SINGLE CUSTOMER COPY PRINT LAYOUT                         --}}
    {{--    (চালান পেজ / কাস্টমার প্রোফাইল চালান সিঙ্গেল কপি পোট্রেট প্রিন্ট)      --}}
    {{-- ======================================================================= --}}
    @if($type === 'a4-customer')
        <div class="bg-white p-2 sm:p-4 text-gray-900 space-y-5 max-w-4xl mx-auto shadow-none border-0">
            
            <!-- Header -->
            <div class="flex justify-between items-start border-b border-gray-300 pb-3">
                <div class="flex items-center gap-3">
                    <div class="w-14 h-14 rounded-xl border border-gray-300 p-1 flex items-center justify-center bg-gray-50 shrink-0 overflow-hidden">
                        @if($cLogoSrc)
                            <img src="{{ $cLogoSrc }}" class="w-full h-full object-contain" alt="Logo">
                        @else
                            <span class="text-2xl select-none">🧱</span>
                        @endif
                    </div>
                    <div>
                        <h2 class="text-2xl font-black text-gray-900 tracking-wide">{{ $companyName }}</h2>
                        <p class="text-xs text-gray-600 font-medium">{{ $companyAddress }}</p>
                        <p class="text-xs text-gray-600 font-mono">{{ $companyPhone }}</p>
                        <p class="text-xs font-bold text-gray-800">প্রোপাইটর: {{ $proprietor }}</p>
                    </div>
                </div>
                <div class="text-right space-y-1">
                    <h1 class="text-3xl font-black text-gray-900 uppercase tracking-wider font-mono">{{ $isDuePrint ? 'RECEIPT' : 'INVOICE' }}</h1>
                    <p class="text-xs font-semibold text-gray-500">গ্রাহক চালান কপি</p>
                    <p class="text-[11px] text-gray-500 font-mono">প্রিন্ট: {{ $printTime }}</p>
                </div>
            </div>

            <!-- Customer Details Metadata Box -->
            @if($challan)
            <div class="flex justify-between items-start text-xs bg-gray-50/80 p-3.5 rounded-xl border border-gray-200">
                <div class="space-y-1.5">
                    @php
                        $plDateStr1 = $challan->date ? \Carbon\Carbon::parse($challan->date)->format('d-m-Y') : now('Asia/Dhaka')->format('d-m-Y');
                        $plTimeStr1 = $challan->created_at ? \Carbon\Carbon::parse($challan->created_at)->setTimezone('Asia/Dhaka')->format('h:i A') : ($challan->updated_at ? \Carbon\Carbon::parse($challan->updated_at)->setTimezone('Asia/Dhaka')->format('h:i A') : now('Asia/Dhaka')->format('h:i A'));
                    @endphp
                    <p class="text-gray-800"><span class="font-bold">{{ $isDuePrint ? 'জমার তারিখ:' : 'চালানের তারিখ:' }}</span> {{ $plDateStr1 }} {{ $plTimeStr1 }}</p>
                    <p class="text-gray-800"><span class="font-bold">ইস্যু করেছে:</span></p>
                </div>
                <div class="text-right space-y-1 pl-4 border-r-4 border-black pr-2">
                    <p class="text-gray-900 font-black text-sm">{{ $challan->customer_name }}</p>
                    <p class="text-gray-700 font-semibold">{{ $challan->customer_address ?: 'ঘোড়াঘাট' }}</p>
                    <p class="text-gray-700 font-mono font-bold">{{ $challan->customer_phone ?: '০১৬৫৬৪৫৬৪৫৬' }}</p>
                </div>
            </div>

            @if(!$isDuePrint)
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
                                <td class="p-2.5 text-right font-mono">৳ {{ number_format((float)($item->rate), (float)($item->rate) == (int)($item->rate) ? 0 : 2) }}</td>
                                <td class="p-2.5 text-right font-mono font-bold text-gray-900">৳ {{ number_format($item->amount, 0) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="p-3 text-center text-gray-400">কোন আইটেম ডাটা পাওয়া যায়নি</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @endif

            <!-- Notes, Stamp & Summary Box Grid -->
            <div class="grid grid-cols-2 gap-6 items-end pt-1">
                <div class="space-y-4">
                    <div class="bg-white border border-gray-200 rounded-xl p-3 text-[11px] text-gray-800 space-y-1">
                        <p class="font-bold underline mb-1 text-gray-900">বিশেষ দ্রষ্টব্য:</p>
                        <p>১। চালান অথবা রশিদ ছাড়া কোনো লেনদেন করবেন না।</p>
                        <p>২। ইট ডেলিভারি নেওয়ার পর কোনো অভিযোগ গ্রহণ যোগ্য হবে না।</p>
                        <p>৩। চালান করার ৩০ দিনের মধ্যে ইট ডেলিভারি নিতে হবে।</p>
                    </div>
                    @if($isDuePrint)
                        @php
                            $plCash = floatval($challan->cash ?? 0);
                            $plCustName = $challan->customer_name;
                            $plCustPhone = $challan->customer_phone;
                            $plNetDue = (float)\App\Models\Challan::where(function($q) use ($plCustName, $plCustPhone) {
                                $q->where('customer_name', $plCustName);
                                if ($plCustPhone) { $q->orWhere('customer_phone', $plCustPhone); }
                            })->sum('due');
                            $plPrevDue = $plNetDue + $plCash;
                        @endphp
                        <div class="border-2 {{ $plNetDue > 0 ? 'border-red-400' : 'border-green-500' }} rounded-xl p-3 text-center">
                            @if($plNetDue > 0)
                                <p class="text-xs font-bold text-red-600">পরিশোধের তারিখ: {{ $challan->due_payment_date ? \Carbon\Carbon::parse($challan->due_payment_date)->format('d-m-Y') : '—' }}</p>
                            @else
                                <p class="text-sm font-black text-green-700 uppercase tracking-wide">পরিশোধিত</p>
                            @endif
                        </div>
                    @else
                        @if($challan->due > 0)
                            <div class="border-2 border-red-500 rounded-xl p-3 text-center space-y-1">
                                <p class="text-base font-black text-red-600">বাকি: ৳ {{ number_format($challan->due) }}</p>
                                <p class="text-xs font-bold text-red-500">পরিশোধের তারিখ : {{ $challan->due_payment_date ? \Carbon\Carbon::parse($challan->due_payment_date)->format('d-m-Y') : '—' }}</p>
                            </div>
                        @else
                            <div class="inline-block border-2 border-green-600 rounded-xl px-8 py-2 text-center font-black text-xl tracking-wide uppercase text-green-700">
                                পরিশোধিত
                            </div>
                        @endif
                    @endif
                </div>

                <div class="bg-gray-50/80 rounded-xl p-3.5 border border-gray-200 space-y-1.5 text-xs font-sans">
                    @if($isDuePrint)
                        <div class="flex justify-between items-center text-gray-800"><span class="font-semibold">মোট বাকি ছিল</span><span class="font-mono font-bold text-gray-900">৳ {{ number_format($plPrevDue) }}</span></div>
                        <div class="flex justify-between items-center text-gray-800"><span class="font-semibold">জমা দেওয়া</span><span class="font-mono font-bold text-emerald-600">৳ {{ number_format($plCash) }}</span></div>
                        <div class="flex justify-between items-center font-extrabold text-gray-900 pt-1.5 border-t border-gray-200 text-sm"><span>বর্তমান বাকি</span><span class="font-mono text-rose-600">৳ {{ number_format($plNetDue) }}</span></div>
                    @else
                        <div class="flex justify-between items-center text-gray-800"><span class="font-semibold">মোট মূল্য</span><span class="font-mono font-bold text-gray-900">৳ {{ number_format($challan->total_value ?: $challan->items->sum('amount'), 0) }}</span></div>
                        <div class="flex justify-between items-center text-gray-800"><span class="font-semibold">ছাড়</span><span class="font-mono font-bold text-gray-900">৳ {{ number_format($challan->discount ?: 0, 0) }}</span></div>
                        <div class="flex justify-between items-center text-gray-800"><span class="font-semibold">গাড়ি ভাড়া</span><span class="font-mono font-bold text-gray-900">৳ {{ number_format($challan->transport_rent ?: 0, 0) }}</span></div>
                        <div class="flex justify-between items-center font-extrabold text-gray-900 pt-1.5 border-t border-gray-200 text-sm"><span>সর্বমোট</span><span class="font-mono">৳ {{ number_format($challan->grand_total, 0) }}</span></div>
                        <div class="flex justify-between items-center text-gray-900 font-bold"><span>জমা</span><span class="font-mono">৳ {{ number_format($challan->cash, 0) }}</span></div>
                        <div class="flex justify-between items-center text-gray-900 font-bold"><span>বাকি</span><span class="font-mono">৳ {{ number_format($challan->due, 0) }}</span></div>
                    @endif
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

    {{-- ======================================================================= --}}
    {{-- 📄 MODE 2: A4 DUAL SIDE-BY-SIDE COPIES PRINT LAYOUT                     --}}
    {{--    (সব চালান পেজ / কাস্টমার প্রোফাইল চালান কাস্টমার+অফিস ল্যান্ডস্কেপ প্রিন্ট)--}}
    {{-- ======================================================================= --}}
    @elseif($type === 'a4-dual')
        <div class="grid grid-cols-2 gap-6 items-stretch w-full p-2 relative bg-white border-0">
            
            <!-- Left Column: Customer Copy -->
            <div class="bg-white p-2 sm:p-4 text-gray-900 space-y-5 max-w-4xl mx-auto shadow-none border-0">
            
            <!-- Header -->
            <div class="flex justify-between items-start border-b border-gray-300 pb-3">
                <div class="flex items-center gap-3">
                    <div class="w-14 h-14 rounded-xl border border-gray-300 p-1 flex items-center justify-center bg-gray-50 shrink-0 overflow-hidden">
                        @if($cLogoSrc)
                            <img src="{{ $cLogoSrc }}" class="w-full h-full object-contain" alt="Logo">
                        @else
                            <span class="text-2xl select-none">🧱</span>
                        @endif
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
                    @php
                        $plDateStr2 = $challan->date ? \Carbon\Carbon::parse($challan->date)->format('d-m-Y') : now('Asia/Dhaka')->format('d-m-Y');
                        $plTimeStr2 = $challan->created_at ? \Carbon\Carbon::parse($challan->created_at)->setTimezone('Asia/Dhaka')->format('h:i A') : ($challan->updated_at ? \Carbon\Carbon::parse($challan->updated_at)->setTimezone('Asia/Dhaka')->format('h:i A') : now('Asia/Dhaka')->format('h:i A'));
                    @endphp
                    <p class="text-gray-800"><span class="font-bold">{{ $isDuePrint ? 'জমার তারিখ:' : 'চালানের তারিখ:' }}</span> {{ $plDateStr2 }} {{ $plTimeStr2 }}</p>
                    <p class="text-gray-800"><span class="font-bold">ইস্যু করেছে:</span></p>
                </div>
                <div class="text-right space-y-1 pl-4 border-r-4 border-black pr-2">
                    <p class="text-gray-900 font-black text-sm">{{ $challan->customer_name }}</p>
                    <p class="text-gray-700 font-semibold">{{ $challan->customer_address ?: 'ঘোড়াঘাট' }}</p>
                    <p class="text-gray-700 font-mono font-bold">{{ $challan->customer_phone ?: '০১৬৫৬৪৫৬৪৫৬' }}</p>
                </div>
            </div>

            @if(!$isDuePrint)
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
                                <td class="p-2.5 text-right font-mono">৳ {{ number_format((float)($item->rate), (float)($item->rate) == (int)($item->rate) ? 0 : 2) }}</td>
                                <td class="p-2.5 text-right font-mono font-bold text-gray-900">৳ {{ number_format($item->amount, 0) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="p-3 text-center text-gray-400">কোন আইটেম ডাটা পাওয়া যায়নি</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @endif

            <!-- Notes, Stamp & Summary Box Grid -->
            <div class="grid grid-cols-2 gap-6 items-end pt-1">
                <div class="space-y-4">
                    <div class="bg-white border border-gray-200 rounded-xl p-3 text-[11px] text-gray-800 space-y-1">
                        <p class="font-bold underline mb-1 text-gray-900">বিশেষ দ্রষ্টব্য:</p>
                        <p>১। চালান অথবা রশিদ ছাড়া কোনো লেনদেন করবেন না।</p>
                        <p>২। ইট ডেলিভারি নেওয়ার পর কোনো অভিযোগ গ্রহণ যোগ্য হবে না।</p>
                        <p>৩। চালান করার ৩০ দিনের মধ্যে ইট ডেলিভারি নিতে হবে।</p>
                    </div>
                    @if($isDuePrint)
                        @php
                            if (!isset($plNetDue)) {
                                $plCash = floatval($challan->cash ?? 0);
                                $plCustName = $challan->customer_name;
                                $plCustPhone = $challan->customer_phone;
                                $plNetDue = (float)\App\Models\Challan::where(function($q) use ($plCustName, $plCustPhone) {
                                    $q->where('customer_name', $plCustName);
                                    if ($plCustPhone) { $q->orWhere('customer_phone', $plCustPhone); }
                                })->sum('due');
                                $plPrevDue = $plNetDue + $plCash;
                            }
                        @endphp
                        <div class="border-2 {{ $plNetDue > 0 ? 'border-red-400' : 'border-green-500' }} rounded-xl p-3 text-center">
                            @if($plNetDue > 0)
                                <p class="text-xs font-bold text-red-600">পরিশোধের তারিখ: {{ $challan->due_payment_date ? \Carbon\Carbon::parse($challan->due_payment_date)->format('d-m-Y') : '—' }}</p>
                            @else
                                <p class="text-sm font-black text-green-700 uppercase tracking-wide">পরিশোধিত</p>
                            @endif
                        </div>
                    @else
                        @if($challan->due > 0)
                            <div class="border-2 border-red-500 rounded-xl p-3 text-center space-y-1">
                                <p class="text-base font-black text-red-600">বাকি: ৳ {{ number_format($challan->due) }}</p>
                                <p class="text-xs font-bold text-red-500">পরিশোধের তারিখ : {{ $challan->due_payment_date ? \Carbon\Carbon::parse($challan->due_payment_date)->format('d-m-Y') : '—' }}</p>
                            </div>
                        @else
                            <div class="inline-block border-2 border-green-600 rounded-xl px-8 py-2 text-center font-black text-xl tracking-wide uppercase text-green-700">
                                পরিশোধিত
                            </div>
                        @endif
                    @endif
                </div>

                <div class="bg-gray-50/80 rounded-xl p-3.5 border border-gray-200 space-y-1.5 text-xs font-sans">
                    @if($isDuePrint)
                        <div class="flex justify-between items-center text-gray-800"><span class="font-semibold">মোট বাকি ছিল</span><span class="font-mono font-bold text-gray-900">৳ {{ number_format($plPrevDue) }}</span></div>
                        <div class="flex justify-between items-center text-gray-800"><span class="font-semibold">জমা দেওয়া</span><span class="font-mono font-bold text-emerald-600">৳ {{ number_format($plCash) }}</span></div>
                        <div class="flex justify-between items-center font-extrabold text-gray-900 pt-1.5 border-t border-gray-200 text-sm"><span>বর্তমান বাকি</span><span class="font-mono text-rose-600">৳ {{ number_format($plNetDue) }}</span></div>
                    @else
                        <div class="flex justify-between items-center text-gray-800"><span class="font-semibold">মোট মূল্য</span><span class="font-mono font-bold text-gray-900">৳ {{ number_format($challan->total_value ?: $challan->items->sum('amount'), 0) }}</span></div>
                        <div class="flex justify-between items-center text-gray-800"><span class="font-semibold">ছাড়</span><span class="font-mono font-bold text-gray-900">৳ {{ number_format($challan->discount ?: 0, 0) }}</span></div>
                        <div class="flex justify-between items-center text-gray-800"><span class="font-semibold">গাড়ি ভাড়া</span><span class="font-mono font-bold text-gray-900">৳ {{ number_format($challan->transport_rent ?: 0, 0) }}</span></div>
                        <div class="flex justify-between items-center font-extrabold text-gray-900 pt-1.5 border-t border-gray-200 text-sm"><span>সর্বমোট</span><span class="font-mono">৳ {{ number_format($challan->grand_total, 0) }}</span></div>
                        <div class="flex justify-between items-center text-gray-900 font-bold"><span>জমা</span><span class="font-mono">৳ {{ number_format($challan->cash, 0) }}</span></div>
                        <div class="flex justify-between items-center text-gray-900 font-bold"><span>বাকি</span><span class="font-mono">৳ {{ number_format($challan->due, 0) }}</span></div>
                    @endif
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
                    <div class="w-14 h-14 rounded-xl border border-gray-300 p-1 flex items-center justify-center bg-gray-50 shrink-0 overflow-hidden">
                        @if($cLogoSrc)
                            <img src="{{ $cLogoSrc }}" class="w-full h-full object-contain" alt="Logo">
                        @else
                            <span class="text-2xl select-none">🧱</span>
                        @endif
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
                    <p class="text-xs font-semibold text-gray-500">অফিস চালান কপি</p>
                    <p class="text-[11px] text-gray-500 font-mono">প্রিন্ট: {{ $printTime }}</p>
                </div>
            </div>

            <!-- Customer Details Metadata Box -->
            @if($challan)
            <div class="flex justify-between items-start text-xs bg-gray-50/80 p-3.5 rounded-xl border border-gray-200">
                <div class="space-y-1.5">
                    @php
                        $plDateStr3 = $challan->date ? \Carbon\Carbon::parse($challan->date)->format('d-m-Y') : now('Asia/Dhaka')->format('d-m-Y');
                        $plTimeStr3 = $challan->created_at ? \Carbon\Carbon::parse($challan->created_at)->setTimezone('Asia/Dhaka')->format('h:i A') : ($challan->updated_at ? \Carbon\Carbon::parse($challan->updated_at)->setTimezone('Asia/Dhaka')->format('h:i A') : now('Asia/Dhaka')->format('h:i A'));
                    @endphp
                    <p class="text-gray-800"><span class="font-bold">{{ $isDuePrint ? 'জমার তারিখ:' : 'চালানের তারিখ:' }}</span> {{ $plDateStr3 }} {{ $plTimeStr3 }}</p>
                    <p class="text-gray-800"><span class="font-bold">ইস্যু করেছে:</span></p>
                </div>
                <div class="text-right space-y-1 pl-4 border-r-4 border-black pr-2">
                    <p class="text-gray-900 font-black text-sm">{{ $challan->customer_name }}</p>
                    <p class="text-gray-700 font-semibold">{{ $challan->customer_address ?: 'ঘোড়াঘাট' }}</p>
                    <p class="text-gray-700 font-mono font-bold">{{ $challan->customer_phone ?: '০১৬৫৬৪৫৬৪৫৬' }}</p>
                </div>
            </div>

            @if(!$isDuePrint)
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
                                <td class="p-2.5 text-right font-mono">৳ {{ number_format((float)($item->rate), (float)($item->rate) == (int)($item->rate) ? 0 : 2) }}</td>
                                <td class="p-2.5 text-right font-mono font-bold text-gray-900">৳ {{ number_format($item->amount, 0) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="p-3 text-center text-gray-400">কোন আইটেম ডাটা পাওয়া যায়নি</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @endif

            <!-- Notes, Stamp & Summary Box Grid -->
            <div class="grid grid-cols-2 gap-6 items-end pt-1">
                <div class="space-y-4">
                    <div class="bg-white border border-gray-200 rounded-xl p-3 text-[11px] text-gray-800 space-y-1">
                        <p class="font-bold underline mb-1 text-gray-900">বিশেষ দ্রষ্টব্য:</p>
                        <p>১। চালান অথবা রশিদ ছাড়া কোনো লেনদেন করবেন না।</p>
                        <p>২। ইট ডেলিভারি নেওয়ার পর কোনো অভিযোগ গ্রহণ যোগ্য হবে না।</p>
                        <p>৩। চালান করার ৩০ দিনের মধ্যে ইট ডেলিভারি নিতে হবে।</p>
                    </div>
                    @if($isDuePrint)
                        @php
                            if (!isset($plNetDue)) {
                                $plCash = floatval($challan->cash ?? 0);
                                $plCustName = $challan->customer_name;
                                $plCustPhone = $challan->customer_phone;
                                $plNetDue = (float)\App\Models\Challan::where(function($q) use ($plCustName, $plCustPhone) {
                                    $q->where('customer_name', $plCustName);
                                    if ($plCustPhone) { $q->orWhere('customer_phone', $plCustPhone); }
                                })->sum('due');
                                $plPrevDue = $plNetDue + $plCash;
                            }
                        @endphp
                        <div class="border-2 {{ $plNetDue > 0 ? 'border-red-400' : 'border-green-500' }} rounded-xl p-3 text-center">
                            @if($plNetDue > 0)
                                <p class="text-xs font-bold text-red-600">পরিশোধের তারিখ: {{ $challan->due_payment_date ? \Carbon\Carbon::parse($challan->due_payment_date)->format('d-m-Y') : '—' }}</p>
                            @else
                                <p class="text-sm font-black text-green-700 uppercase tracking-wide">পরিশোধিত</p>
                            @endif
                        </div>
                    @else
                        @if($challan->due > 0)
                            <div class="border-2 border-red-500 rounded-xl p-3 text-center space-y-1">
                                <p class="text-base font-black text-red-600">বাকি: ৳ {{ number_format($challan->due) }}</p>
                                <p class="text-xs font-bold text-red-500">পরিশোধের তারিখ : {{ $challan->due_payment_date ? \Carbon\Carbon::parse($challan->due_payment_date)->format('d-m-Y') : '—' }}</p>
                            </div>
                        @else
                            <div class="inline-block border-2 border-green-600 rounded-xl px-8 py-2 text-center font-black text-xl tracking-wide uppercase text-green-700">
                                পরিশোধিত
                            </div>
                        @endif
                    @endif
                </div>

                <div class="bg-gray-50/80 rounded-xl p-3.5 border border-gray-200 space-y-1.5 text-xs font-sans">
                    @if($isDuePrint)
                        <div class="flex justify-between items-center text-gray-800"><span class="font-semibold">মোট বাকি ছিল</span><span class="font-mono font-bold text-gray-900">৳ {{ number_format($plPrevDue) }}</span></div>
                        <div class="flex justify-between items-center text-gray-800"><span class="font-semibold">জমা দেওয়া</span><span class="font-mono font-bold text-emerald-600">৳ {{ number_format($plCash) }}</span></div>
                        <div class="flex justify-between items-center font-extrabold text-gray-900 pt-1.5 border-t border-gray-200 text-sm"><span>বর্তমান বাকি</span><span class="font-mono text-rose-600">৳ {{ number_format($plNetDue) }}</span></div>
                    @else
                        <div class="flex justify-between items-center text-gray-800"><span class="font-semibold">মোট মূল্য</span><span class="font-mono font-bold text-gray-900">৳ {{ number_format($challan->total_value ?: $challan->items->sum('amount'), 0) }}</span></div>
                        <div class="flex justify-between items-center text-gray-800"><span class="font-semibold">ছাড়</span><span class="font-mono font-bold text-gray-900">৳ {{ number_format($challan->discount ?: 0, 0) }}</span></div>
                        <div class="flex justify-between items-center text-gray-800"><span class="font-semibold">গাড়ি ভাড়া</span><span class="font-mono font-bold text-gray-900">৳ {{ number_format($challan->transport_rent ?: 0, 0) }}</span></div>
                        <div class="flex justify-between items-center font-extrabold text-gray-900 pt-1.5 border-t border-gray-200 text-sm"><span>সর্বমোট</span><span class="font-mono">৳ {{ number_format($challan->grand_total, 0) }}</span></div>
                        <div class="flex justify-between items-center text-gray-900 font-bold"><span>জমা</span><span class="font-mono">৳ {{ number_format($challan->cash, 0) }}</span></div>
                        <div class="flex justify-between items-center text-gray-900 font-bold"><span>বাকি</span><span class="font-mono">৳ {{ number_format($challan->due, 0) }}</span></div>
                    @endif
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

    {{-- ======================================================================= --}}
    {{-- 📄 MODE 3: POS SINGLE THERMAL SLIP PRINT LAYOUT                         --}}
    {{--    (থার্মাল প্রিন্টার কাস্টমার সিঙ্গেল রসিদ প্রিন্ট)                   --}}
    {{-- ======================================================================= --}}
    @elseif($type === 'pos-customer')
        <div class="max-w-[300px] mr-auto ml-0 bg-white min-h-screen p-2 text-gray-900 font-sans text-xs space-y-3 border-0 shadow-none text-left">
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

    {{-- ======================================================================= --}}
    {{-- 📄 MODE 4: POS DUAL STACKED SLIPS PRINT LAYOUT                          --}}
    {{--    (থার্মাল প্রিন্টার কাস্টমার + অফিস ডাবল রসিদ স্ট্যাকড প্রিন্ট)       --}}
    {{-- ======================================================================= --}}
    @elseif($type === 'pos-dual')
        <div class="max-w-[300px] mr-auto ml-0 space-y-4 font-sans text-xs border-0 shadow-none text-left">
            <!-- Customer Slip Top -->
            <div class="bg-white p-2 text-gray-900 space-y-2">
                <div class="text-center space-y-1 border-b border-dashed border-gray-400 pb-2">
                <p class="text-[11px] underline font-bold text-gray-800">চালান রশিদ (গ্রাহক কপি)</p>
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
                <p class="text-[11px] underline font-bold text-gray-800">চালান রশিদ (অফিস কপি)</p>
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

    {{-- ======================================================================= --}}
    {{-- 📄 MODE 6: KHOTIAN STATEMENT REPORT PRINT LAYOUT                        --}}
    {{--    (খতিয়ান পেজের পেমেন্ট স্টেটমেন্ট কাস্টম এ৪ পোট্রেট প্রিন্ট লেআউট)      --}}
    {{-- ======================================================================= --}}
    @elseif($type === 'khotian-statement')
        @php
            $printKhotianPayments = $payments ?: [];
            $seasonVal = \App\Models\Setting::get('season', '২৫-২৬');
            $companyNameVal = \App\Models\Setting::get('company_name_bn', 'ডেমো ব্রিকস');
            $companyAddressVal = \App\Models\Setting::get('address', 'হিলালীপাড়া,কাটাবাড়ি,গোবیندগঞ্জ');
            $ownerPhonesVal = \App\Models\Setting::get('invoice_phones') ?: \App\Models\Setting::get('owner_phone', '01901349901, 01901349906');
            $ownerPhonesBn = function_exists('toBanglaNum') ? toBanglaNum($ownerPhonesVal) : $ownerPhonesVal;
            $ownerNameVal = \App\Models\Setting::get('owner_name', 'মোঃ মানিক মিয়া');
            $formattedPrintDate = function_exists('toBanglaNum') ? toBanglaNum(now()->format('d-m-Y')) : now()->format('d-m-Y');
            $formattedPrintTime = function_exists('toBanglaNum') ? toBanglaNum(now()->format('d-m-Y h:i a')) : now()->format('d-m-Y h:i a');

            $printTotalQty = $totalQty ?? 0;
            $printTotalBill = $totalBill ?? 0;
            $printTotalAdvance = $totalAdvance ?? 0;
            $printTotalDeduction = $totalDeduction ?? 0;
            $printTotalPayment = $totalPayment ?? 0;
            $printDueBalance = $printTotalBill - $printTotalDeduction - $printTotalPayment;
        @endphp

        <div id="khotian-statement-print-wrapper" class="bg-white text-gray-900 font-sans p-2 sm:p-4">
            <!-- Document Wrapper -->
            <div class="max-w-4xl mx-auto space-y-4">

                <!-- Header Section: Logo + Company Info & Report Title -->
                <div class="flex items-start justify-between border-b-2 border-gray-900 pb-3">
                    <!-- Left: Logo & Company Info -->
                    <div class="flex items-start gap-3">
                        <div class="w-14 h-14 rounded-xl border border-gray-400 p-1 flex items-center justify-center bg-gray-50 flex-shrink-0 overflow-hidden">
                            @if(isset($cLogoSrc) && $cLogoSrc)
                                <img src="{{ $cLogoSrc }}" class="w-full h-full object-contain" alt="Logo">
                            @else
                                <div class="flex items-center justify-center text-center leading-none">
                                    <span class="text-2xl select-none">🧱</span>
                                </div>
                            @endif
                        </div>
                        <div class="space-y-0.5">
                            <h1 class="text-xl font-black text-emerald-700 tracking-tight leading-none">{{ $companyNameVal }}</h1>
                            <p class="text-xs text-gray-700 font-bold leading-tight">{{ $companyAddressVal }}</p>
                            <p class="text-xs text-gray-700 font-bold leading-tight">{{ $ownerPhonesBn }}</p>
                        </div>
                    </div>

                    <!-- Right: Title Badge, Ledger, Season & Print Date -->
                    <div class="text-right space-y-1">
                        <span class="inline-block bg-emerald-100 text-emerald-800 text-sm font-black px-3 py-0.5 rounded-full border border-emerald-300">
                            স্টেটমেন্ট
                        </span>
                        <p class="text-xs font-bold text-gray-700">প্রিন্ট তারিখঃ {{ $formattedPrintDate }}</p>
                        <p class="text-xs font-black text-gray-900">{{ $selectedLedger }} ({{ $ledgerGroup ?: 'অন্যান্য' }})</p>
                        <p class="text-xs font-bold text-gray-700">সিজনঃ {{ $seasonVal }} ({{ $ledgerGroup ?: 'অন্যান্য' }})</p>
                        <p class="text-[11px] font-bold text-gray-700">
                            পরিমাণঃ {{ function_exists('toBanglaNum') ? toBanglaNum(number_format($printTotalQty)) : number_format($printTotalQty) }}, মোট পেমেন্টঃ {{ function_exists('toBanglaNum') ? toBanglaNum(number_format($printTotalPayment)) : number_format($printTotalPayment) }} টাকা
                        </p>
                    </div>
                </div>

                <!-- Sub-header Full Width Summary Bar -->
                <div class="bg-gray-100/90 border border-gray-300 rounded-lg py-1.5 px-4 text-center text-xs font-black text-gray-900 tracking-wide">
                    মোট পেমেন্ট {{ function_exists('toBanglaNum') ? toBanglaNum(number_format($printTotalPayment)) : number_format($printTotalPayment) }} টাকা, মোট বিল {{ function_exists('toBanglaNum') ? toBanglaNum(number_format($printTotalBill)) : number_format($printTotalBill) }} টাকা, বাকিঃ {{ function_exists('toBanglaNum') ? toBanglaNum(number_format($printDueBalance)) : number_format($printDueBalance) }} টাকা
                </div>

                <!-- Khotian Data Table -->
                <table class="w-full text-xs border-collapse border border-gray-400 font-sans">
                    <thead>
                        <tr class="bg-gray-100 text-gray-900 font-bold border-b border-gray-400">
                            <th class="py-2 px-2 border-r border-gray-400 text-center w-8">নং</th>
                            <th class="py-2 px-3 border-r border-gray-400 text-left w-24">তারিখ</th>
                            <th class="py-2 px-3 border-r border-gray-400 text-left">বিবরণ</th>
                            <th class="py-2 px-3 border-r border-gray-400 text-right w-16">পরিমাণ</th>
                            <th class="py-2 px-3 border-r border-gray-400 text-right w-14">রেট</th>
                            <th class="py-2 px-3 border-r border-gray-400 text-right w-20">বিল</th>
                            <th class="py-2 px-3 border-r border-gray-400 text-right w-16">অগ্রিম</th>
                            <th class="py-2 px-3 border-r border-gray-400 text-right w-16">কর্তন</th>
                            <th class="py-2 px-3 border-r border-gray-400 text-right w-20">পেমেন্ট</th>
                            <th class="py-2 px-3 text-right w-16">কম/বেশি</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-300">
                        @forelse($printKhotianPayments as $index => $pay)
                            @php
                                $qty = floatval(is_object($pay) ? $pay->qty : ($pay['qty'] ?? 0));
                                $rate = floatval(is_object($pay) ? $pay->rate : ($pay['rate'] ?? 0));
                                $total = floatval(is_object($pay) ? $pay->total : ($pay['total'] ?? 0));
                                $advance = floatval(is_object($pay) ? $pay->advance : ($pay['advance'] ?? 0));
                                $deduction = floatval(is_object($pay) ? $pay->deduction : ($pay['deduction'] ?? 0));
                                $payment = floatval(is_object($pay) ? $pay->payment : ($pay['payment'] ?? 0));
                                $purchaseRec = floatval(is_object($pay) ? $pay->purchase_receive : ($pay['purchase_receive'] ?? 0));

                                $dateVal = is_object($pay) ? $pay->date : ($pay['date'] ?? '');
                                $createdVal = is_object($pay) ? $pay->created_at : ($pay['created_at'] ?? null);
                                $dt = function_exists('toKhotianDateTimeParts') ? toKhotianDateTimeParts($dateVal, $createdVal) : ['formattedDate' => $dateVal];
                            @endphp
                            <tr class="hover:bg-gray-50 border-b border-gray-300">
                                <td class="py-1.5 px-2 border-r border-gray-400 text-center font-semibold">{{ function_exists('toBanglaNum') ? toBanglaNum($index + 1) : ($index + 1) }}</td>
                                <td class="py-1.5 px-3 border-r border-gray-400 font-semibold text-gray-800">{{ $dt['formattedDate'] }}</td>
                                <td class="py-1.5 px-3 border-r border-gray-400 font-semibold text-gray-800 whitespace-pre-wrap">{{ is_object($pay) ? $pay->desc : ($pay['desc'] ?? '-') }}</td>
                                <td class="py-1.5 px-3 border-r border-gray-400 text-right font-mono font-semibold">{{ $qty > 0 ? (function_exists('toBanglaNum') ? toBanglaNum(number_format($qty)) : number_format($qty)) : '-' }}</td>
                                <td class="py-1.5 px-3 border-r border-gray-400 text-right font-mono font-semibold">{{ $rate > 0 ? (function_exists('toBanglaNum') ? toBanglaNum(number_format($rate)) : number_format($rate)) : '-' }}</td>
                                <td class="py-1.5 px-3 border-r border-gray-400 text-right font-mono font-semibold">{{ $total > 0 ? '৳' . (function_exists('toBanglaNum') ? toBanglaNum(number_format($total)) : number_format($total)) : '-' }}</td>
                                <td class="py-1.5 px-3 border-r border-gray-400 text-right font-mono font-semibold">{{ $advance > 0 ? '৳' . (function_exists('toBanglaNum') ? toBanglaNum(number_format($advance)) : number_format($advance)) : '-' }}</td>
                                <td class="py-1.5 px-3 border-r border-gray-400 text-right font-mono font-semibold">{{ $deduction > 0 ? '৳' . (function_exists('toBanglaNum') ? toBanglaNum(number_format($deduction)) : number_format($deduction)) : '-' }}</td>
                                <td class="py-1.5 px-3 border-r border-gray-400 text-right font-mono font-bold text-gray-900">{{ $payment > 0 ? '৳' . (function_exists('toBanglaNum') ? toBanglaNum(number_format($payment)) : number_format($payment)) : '৳০' }}</td>
                                <td class="py-1.5 px-3 text-right font-mono font-semibold text-gray-800">{{ $purchaseRec > 0 ? '৳' . (function_exists('toBanglaNum') ? toBanglaNum(number_format($purchaseRec)) : number_format($purchaseRec)) : '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="py-8 text-center text-gray-500 font-semibold">এই খতিয়ানে কোনো বিবরণ পাওয়া যায়নি।</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr class="bg-gray-50 border-t border-gray-400 font-bold text-xs">
                            <td colspan="3" class="py-2 px-3 text-right border-r border-gray-400 font-black">সর্বমোট:</td>
                            <td class="py-2 px-3 border-r border-gray-400 text-right font-mono font-black">{{ $printTotalQty > 0 ? (function_exists('toBanglaNum') ? toBanglaNum(number_format($printTotalQty)) : number_format($printTotalQty)) : '-' }}</td>
                            <td class="py-2 px-3 border-r border-gray-400 text-right font-mono font-black">-</td>
                            <td class="py-2 px-3 border-r border-gray-400 text-right font-mono font-black">{{ $printTotalBill > 0 ? '৳' . (function_exists('toBanglaNum') ? toBanglaNum(number_format($printTotalBill)) : number_format($printTotalBill)) : '-' }}</td>
                            <td class="py-2 px-3 border-r border-gray-400 text-right font-mono font-black">{{ $printTotalAdvance > 0 ? '৳' . (function_exists('toBanglaNum') ? toBanglaNum(number_format($printTotalAdvance)) : number_format($printTotalAdvance)) : '-' }}</td>
                            <td class="py-2 px-3 border-r border-gray-400 text-right font-mono font-black">{{ $printTotalDeduction > 0 ? '৳' . (function_exists('toBanglaNum') ? toBanglaNum(number_format($printTotalDeduction)) : number_format($printTotalDeduction)) : '-' }}</td>
                            <td class="py-2 px-3 border-r border-gray-400 text-right font-mono font-black">{{ $printTotalPayment > 0 ? '৳' . (function_exists('toBanglaNum') ? toBanglaNum(number_format($printTotalPayment)) : number_format($printTotalPayment)) : '-' }}</td>
                            <td class="py-2 px-3 text-right font-mono font-black">-</td>
                        </tr>
                    </tfoot>
                </table>

                <!-- Signatures Row (Avoid page break split) -->
                <div class="pt-16 pb-6 flex items-center justify-between font-bold text-xs text-gray-900" style="page-break-inside: avoid; break-inside: avoid;">
                    <div class="text-center w-40">
                        <div class="border-t border-gray-900 pt-1.5 font-bold">ক্যাশিয়ার</div>
                    </div>
                    <div class="text-center w-40">
                        <div class="border-t border-gray-900 pt-1.5 font-bold">মালিক</div>
                    </div>
                </div>

                <!-- Footer Info -->
                <div class="pt-3 border-t border-gray-200 text-center text-[10px] text-gray-500 font-semibold" style="page-break-inside: avoid; break-inside: avoid;">
                    রিপোর্ট প্রিন্ট: {{ $formattedPrintTime }} | Software by: CODENEXTIT.COM
                </div>
            </div>
        </div>

    {{-- ======================================================================= --}}
    {{-- 🚚 MODE 6: DELIVERY LIST REPORT PRINT LAYOUT                            --}}
    {{--    (আজকের ডেলিভারি ও বাকি ডেলিভারি তালিকা টেবিল প্রিন্ট লেআউট)        --}}
    {{-- ======================================================================= --}}
    @elseif($type === 'delivery-report')
        <style media="print">
            @page {
                size: A4 portrait !important;
                margin: 8mm 8mm !important;
            }
            html, body {
                background: #ffffff !important;
                color: #000000 !important;
                margin: 0 !important;
                padding: 0 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            table { page-break-inside: auto; }
            tr    { page-break-inside: avoid; break-inside: avoid; page-break-after: auto; }
            thead { display: table-header-group; }
            tfoot { display: table-footer-group; }
            .print-page-break-avoid { page-break-inside: avoid; break-inside: avoid; }
        </style>
        <div class="bg-white p-4 text-gray-900 font-sans max-w-4xl mx-auto">
            <!-- Header Section -->
            <div class="text-center space-y-1 pb-3 border-b-2 border-gray-800">
                <h1 class="text-2xl font-black text-gray-900 tracking-wide">{{ $companyName }}</h1>
                <p class="text-xs font-semibold text-gray-700">{{ $companyAddress }}</p>
                <p class="text-xs font-mono font-semibold text-gray-700">{{ $companyPhone }}</p>
                <p class="text-xs font-bold text-gray-800">প্রোপাইটরঃ {{ $proprietor }}</p>
            </div>

            <!-- Sub Header Metadata Row -->
            <div class="flex items-center justify-between py-3 my-2 text-xs font-semibold">
                <div>
                    <span>তারিখ: {{ $reportDate ?? now()->format('d-m-Y') }}</span>
                    @if($activeSeason)
                        <span class="font-mono ml-1">| {{ $activeSeason }}</span>
                    @endif
                </div>

                <div class="px-4 py-1.5 bg-gray-100 text-gray-900 rounded-full font-bold text-sm tracking-wide shadow-sm border border-gray-300">
                    {{ $reportTitle ?? 'দৈনিক ডেলিভারি তালিকা' }}
                </div>

                <div class="text-right font-bold text-sm">
                    মোট ডেলিভারি: <span class="font-mono text-gray-900 font-black">{{ number_format((int)$totalDeliverySum) }}</span>
                </div>
            </div>

            <!-- Table -->
            <table class="w-full text-xs border-collapse border border-gray-400 mt-2">
                <thead>
                    <tr class="bg-gray-100 font-bold text-gray-900 border-b border-gray-400 text-center">
                        <th class="p-2 border-r border-gray-400 w-10">রোল</th>
                        <th class="p-2 border-r border-gray-400 w-14">চালান</th>
                        <th class="p-2 border-r border-gray-400 text-left">কাস্টমার</th>
                        <th class="p-2 border-r border-gray-400 text-left">ডেলিভারি ঠিকানা</th>
                        <th class="p-2 border-r border-gray-400 text-center">শ্রেণি</th>
                        <th class="p-2 border-r border-gray-400 text-right">পরিমাণ</th>
                        <th class="p-2 border-r border-gray-400 text-right">ডেলিভারি</th>
                        <th class="p-2 text-right">বাকি</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-300 font-sans">
                    @if($deliveries)
                        @foreach($deliveries as $idx => $item)
                            @php
                                $cNo = isset($item->challan_no) ? $item->challan_no : ($item->challan->challan_no ?? '-');
                                $cName = isset($item->customer_name) ? $item->customer_name : ($item->challan->customer_name ?? '-');
                                $cPhone = isset($item->customer_phone) ? $item->customer_phone : ($item->challan->customer_phone ?? '');
                                $cAddr = isset($item->customer_address) ? $item->customer_address : ($item->challan->customer_address ?? '-');
                                $catName = $item->category_name ?? '-';
                                
                                $totalQ = isset($item->quantity) ? (int)$item->quantity : 0;
                                $delQ = isset($item->today_delivery_qty) ? (int)$item->today_delivery_qty : (isset($item->delivered_quantity) ? (int)$item->delivered_quantity : 0);
                                $remQ = isset($item->delivery_remaining_qty) ? (int)$item->delivery_remaining_qty : max(0, $totalQ - $delQ);
                            @endphp
                            <tr class="hover:bg-gray-50 border-b border-gray-300 text-xs">
                                <td class="p-2 text-center font-mono font-bold border-r border-gray-300">{{ $idx + 1 }}</td>
                                <td class="p-2 text-center font-mono font-bold border-r border-gray-300">{{ $cNo }}</td>
                                <td class="p-2 text-left border-r border-gray-300">
                                    <div class="font-bold text-gray-900">{{ $cName }}</div>
                                    @if($cPhone)<div class="text-[10px] text-gray-500 font-mono">{{ $cPhone }}</div>@endif
                                </td>
                                <td class="p-2 text-left border-r border-gray-300 font-medium">{{ $cAddr }}</td>
                                <td class="p-2 text-center font-semibold border-r border-gray-300">{{ $catName }}</td>
                                <td class="p-2 text-right font-mono font-semibold border-r border-gray-300">{{ number_format($totalQ) }}</td>
                                <td class="p-2 text-right font-mono font-bold text-emerald-600 border-r border-gray-300">{{ number_format($delQ) }}</td>
                                <td class="p-2 text-right font-mono font-bold text-rose-600">{{ number_format($remQ) }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="8" class="p-4 text-center text-gray-500 font-semibold">কোনো তথ্য পাওয়া যায়নি</td>
                        </tr>
                    @endif
                </tbody>
                <tfoot>
                    <tr class="bg-gray-100 font-bold border-t-2 border-gray-400 text-xs">
                        <td colspan="6" class="p-2 text-right font-black border-r border-gray-400">সর্বমোট ডেলিভারি:</td>
                        <td class="p-2 text-right font-mono font-black text-emerald-700 border-r border-gray-400">{{ number_format((int)$totalDeliverySum) }}</td>
                        <td class="p-2 text-right font-mono font-black text-rose-600">
                            @php
                                $totalRemSum = 0;
                                if ($deliveries) {
                                    foreach($deliveries as $d) {
                                        $tQ = isset($d->quantity) ? (int)$d->quantity : 0;
                                        $dQ = isset($d->today_delivery_qty) ? (int)$d->today_delivery_qty : (isset($d->delivered_quantity) ? (int)$d->delivered_quantity : 0);
                                        $totalRemSum += isset($d->delivery_remaining_qty) ? (int)$d->delivery_remaining_qty : max(0, $tQ - $dQ);
                                    }
                                }
                            @endphp
                            {{ number_format($totalRemSum) }}
                        </td>
                    </tr>
                </tfoot>
            </table>

            <!-- Signatures Row (Page break protected) -->
            <div class="pt-16 pb-6 flex items-center justify-between font-bold text-xs text-gray-900 print-page-break-avoid" style="page-break-inside: avoid; break-inside: avoid;">
                <div class="text-center w-40">
                    <div class="border-t border-gray-900 pt-1.5 font-bold">ম্যানেজার</div>
                </div>
                <div class="text-center w-40">
                    <div class="border-t border-gray-900 pt-1.5 font-bold">মালিক</div>
                </div>
            </div>

            <!-- Bottom Print Footer -->
            <div class="pt-3 border-t border-gray-200 flex justify-between items-center text-[10px] text-gray-500 font-semibold print-page-break-avoid" style="page-break-inside: avoid; break-inside: avoid;">
                <div>রিপোর্ট প্রিন্ট: {{ $printTime }}</div>
                <div>Software by: Payratech.com</div>
        </div>

    {{-- ======================================================================= --}}
    {{-- 💰 MODE 7: DUE KHATA / COLLECTION REPORT PRINT LAYOUT                  --}}
    {{--    (আজকের জমা, আজ জমা দেবে, সব বাকি তালিকা টেবিল প্রিন্ট লেআউট)       --}}
    {{-- ======================================================================= --}}
    @elseif($type === 'due-report')
        <style media="print">
            @page {
                size: A4 portrait !important;
                margin: 8mm 8mm !important;
            }
            html, body {
                background: #ffffff !important;
                color: #000000 !important;
                margin: 0 !important;
                padding: 0 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            table { page-break-inside: auto; }
            tr    { page-break-inside: avoid; break-inside: avoid; page-break-after: auto; }
            thead { display: table-header-group; }
            tfoot { display: table-footer-group; }
            .print-page-break-avoid { page-break-inside: avoid; break-inside: avoid; }
        </style>
        <div class="bg-white p-4 text-gray-900 font-sans max-w-4xl mx-auto">
            <!-- Header Section -->
            <div class="text-center space-y-1 pb-3 border-b-2 border-gray-800">
                <h1 class="text-2xl font-black text-gray-900 tracking-wide">{{ $companyName }}</h1>
                <p class="text-xs font-semibold text-gray-700">{{ $companyAddress }}</p>
                <p class="text-xs font-mono font-semibold text-gray-700">{{ $companyPhone }}</p>
                <p class="text-xs font-bold text-gray-800">প্রোপাইটরঃ {{ $proprietor }}</p>
            </div>

            <!-- Sub Header Metadata Row -->
            <div class="flex items-center justify-between py-3 my-2 text-xs font-semibold">
                <div>
                    <span>তারিখ: {{ $reportDate ? \Carbon\Carbon::parse($reportDate)->format('d-m-Y') : now()->format('d-m-Y') }}</span>
                    @if($activeSeason)
                        <span class="font-mono ml-1">| {{ $activeSeason }}</span>
                    @endif
                </div>

                <div class="px-4 py-1.5 bg-gray-200 text-gray-900 rounded-full font-bold text-sm tracking-wide shadow-sm border border-gray-300">
                    {{ $reportTitle ?? 'বাকি আদায় রিপোর্ট' }}
                </div>

                <div class="text-right font-bold text-sm">
                    {{ $reportTitle === 'আজকের জমা রিপোর্ট' ? 'মোট আদায়' : 'মোট বাকি' }}: <span class="font-mono text-gray-900 font-black">{{ number_format((float)$totalCollectionSum) }} টাকা</span>
                </div>
            </div>

            <!-- Table -->
            <table class="w-full text-xs border-collapse border border-gray-400 mt-2">
                <thead>
                    <tr class="bg-gray-100 font-bold text-gray-900 border-b border-gray-400 text-center">
                        <th class="p-2 border-r border-gray-400 w-10">নং</th>
                        <th class="p-2 border-r border-gray-400 w-12">#</th>
                        <th class="p-2 border-r border-gray-400 text-center">জমা তারিখ</th>
                        <th class="p-2 border-r border-gray-400 text-left">কাস্টমার নাম</th>
                        <th class="p-2 border-r border-gray-400 text-left">ঠিকানা</th>
                        <th class="p-2 border-r border-gray-400 text-right">পূর্বের বাকি</th>
                        <th class="p-2 border-r border-gray-400 text-right">জমা</th>
                        <th class="p-2 border-r border-gray-400 text-right">বাকি</th>
                        <th class="p-2 text-center">নতুন তারিখ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-300 font-sans">
                    @if(isset($collections) && count($collections) > 0)
                        @foreach($collections as $idx => $item)
                            @php
                                $bnNum = function ($num) {
                                    $eng = ['0','1','2','3','4','5','6','7','8','9'];
                                    $bn = ['০','১','২','৩','৪','৫','৬','৭','৮','৯'];
                                    return str_replace($eng, $bn, (string)$num);
                                };
                                $slNo = $bnNum($idx + 1);
                                $key = $item->customer_name . '|' . ($item->customer_phone ?: '');
                                $cId = isset($customerIdMap[$key]) 
                                    ? $customerIdMap[$key] 
                                    : (\App\Models\Challan::where('customer_name', $item->customer_name)->min('id') ?: ($item->customer_id ?? $item->id));
                                
                                $datePart = $item->date ? \Carbon\Carbon::parse($item->date)->format('d-m-Y') : ($item->created_at ? \Carbon\Carbon::parse($item->created_at)->setTimezone('Asia/Dhaka')->format('d-m-Y') : null);
                                $timePart = $item->created_at ? \Carbon\Carbon::parse($item->created_at)->setTimezone('Asia/Dhaka')->format('h:i A') : ($item->updated_at ? \Carbon\Carbon::parse($item->updated_at)->setTimezone('Asia/Dhaka')->format('h:i A') : now('Asia/Dhaka')->format('h:i A'));
                                $dateStr = $datePart ? ($datePart . ' ' . $timePart) : '-';
                                $cName = $item->customer_name ?? '-';
                                $cAddr = $item->customer_address ?? '-';
                                
                                $cash = floatval($item->cash ?? 0);
                                $due = floatval($item->due ?? 0);
                                
                                if (isset($netDueMap[$key])) {
                                    $currentDue = floatval($netDueMap[$key]);
                                    $prevDue = $currentDue + $cash;
                                } elseif ($cash > 0 && $item->grand_total == 0) {
                                    $customerScope = function ($q) use ($item) {
                                        $q->where('customer_name', $item->customer_name);
                                        if ($item->customer_phone) {
                                            $q->orWhere('customer_phone', $item->customer_phone);
                                        }
                                    };
                                    $netDue = (float) \App\Models\Challan::where($customerScope)->sum('due');
                                    $depositsFrom = (float) \App\Models\Challan::where($customerScope)
                                        ->where('grand_total', 0)
                                        ->where('id', '>=', $item->id)
                                        ->sum('cash');
                                    $prevDue = $netDue + $depositsFrom;
                                    $currentDue = $prevDue - $cash;
                                } else {
                                    $currentDue = $due;
                                    $prevDue = $due + $cash;
                                }
                                
                                $newDate = $item->due_payment_date ? \Carbon\Carbon::parse($item->due_payment_date)->format('d-m-Y') : '-';
                            @endphp
                            <tr class="hover:bg-gray-50 border-b border-gray-300 text-xs text-center">
                                <td class="p-2 font-mono border-r border-gray-300">{{ $slNo }}</td>
                                <td class="p-2 font-mono font-bold border-r border-gray-300">{{ $cId }}</td>
                                <td class="p-2 font-mono text-[11px] border-r border-gray-300">{{ $dateStr }}</td>
                                <td class="p-2 text-left font-bold border-r border-gray-300">{{ $cName }}</td>
                                <td class="p-2 text-left border-r border-gray-300">{{ $cAddr }}</td>
                                <td class="p-2 text-right font-mono border-r border-gray-300">{{ number_format($prevDue) }}</td>
                                <td class="p-2 text-right font-mono font-bold text-emerald-600 border-r border-gray-300">{{ number_format($cash) }}</td>
                                <td class="p-2 text-right font-mono font-bold text-rose-600 border-r border-gray-300">{{ number_format($currentDue) }}</td>
                                <td class="p-2 font-mono text-[11px]">{{ $newDate }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="9" class="p-4 text-center text-gray-500 font-semibold">কোনো তথ্য পাওয়া যায়নি</td>
                        </tr>
                    @endif
                </tbody>
            </table>

            <!-- Signatures Row (Page break protected) -->
            <div class="pt-16 pb-6 flex items-center justify-between font-bold text-xs text-gray-900 print-page-break-avoid" style="page-break-inside: avoid; break-inside: avoid;">
                <div class="text-center w-40">
                    <div class="border-t border-gray-900 pt-1.5 font-bold">ম্যানেজার</div>
                </div>
                <div class="text-center w-40">
                    <div class="border-t border-gray-900 pt-1.5 font-bold">মালিক</div>
                </div>
            </div>

            <!-- Bottom Print Footer -->
            <div class="pt-3 border-t border-gray-200 text-center text-[10px] text-gray-500 font-semibold print-page-break-avoid" style="page-break-inside: avoid; break-inside: avoid;">
                রিপোর্ট প্রিন্ট: {{ \Carbon\Carbon::now('Asia/Dhaka')->format('d-m-Y h:i A') }} | Software by: Payratech.com
            </div>
        </div>

    {{-- ======================================================================= --}}
    {{-- 📄 MODE 5: GENERIC TABLE REPORT & STATEMENT PRINT LAYOUT                --}}
    {{--    (কাস্টমার স্টেটমেন্ট ও ড্যাশবোর্ড ফিল্টারড রিপোর্ট প্রিন্ট)        --}}
    {{-- ======================================================================= --}}
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

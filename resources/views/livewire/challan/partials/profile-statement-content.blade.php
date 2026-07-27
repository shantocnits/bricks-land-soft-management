@php
    $setting = \App\Models\Setting::first();
    $companyName = $setting->site_title ?? $setting->company_name ?? 'জয়া ব্রিকস';
    $companyAddress = $setting->company_address ?? 'বিলমারিয়া,বাগাতিপাড়া,নাটোর';
    $companyPhone = $setting->company_phone ?? '০১৭২৩-৬৭৫-১৬৭, ০১৭২৮-৭৬৭-৯৫৫';
    $proprietor = $setting->proprietor_name ?? 'মেঃ জহুরুল ইসলাম';
@endphp

<!-- Company Header -->
<div class="text-center border-b border-gray-300 pb-2 mb-3">
    <h1 class="text-2xl font-black text-gray-900 tracking-wide">{{ $companyName }}</h1>
    <p class="text-xs font-bold text-gray-700 mt-0.5">{{ $companyAddress }}</p>
    <p class="text-xs font-bold text-gray-600">মোবাইল: {{ $companyPhone }}</p>
    <p class="text-xs font-bold text-gray-800">প্রোপাইটরঃ {{ $proprietor }}</p>
</div>

<!-- Title Banner -->
<div class="bg-gray-200 text-center py-1.5 rounded-lg font-black text-xs text-gray-900 mb-3 uppercase">
    কাস্টমার স্টেটমেন্ট (সব তথ্য)
</div>

<!-- Customer Meta Grid -->
<div class="flex justify-between items-start text-xs border-l-4 border-black pl-3 py-1 mb-3 font-sans">
    <div class="space-y-0.5">
        <h3 class="font-extrabold text-sm text-gray-900">{{ $customer_name }}</h3>
        <p class="text-gray-700 font-semibold">{{ $customer_address ?: 'ঘোড়াঘাট' }} | মোবাইল: {{ $customer_phone ?: '০১৬৫৬৪৫৬৪৫৬' }}</p>
        <p class="text-gray-600 font-semibold">আইডি: {{ $printChallans->first() ? $printChallans->first()->id : '১২' }}</p>
    </div>
    <div class="text-right space-y-0.5 font-bold text-gray-800 text-[11px]">
        <p class="font-black text-gray-900 text-xs">সিজন: ২৫-২৬</p>
        <p class="font-normal text-gray-600">প্রিন্ট তারিখ: {{ now()->format('d-m-Y') }}</p>
        <p class="font-normal text-gray-600">সময়: {{ now()->format('h:i:s a') }}</p>
    </div>
</div>

@if($activeTab === 'all_challan')
    <!-- Stat Summary Grid (3 Columns, 2 Rows) -->
    <div class="grid grid-cols-3 gap-2 text-center text-xs mb-3 font-bold border border-gray-200 rounded-xl p-2 bg-gray-50/50">
        <div class="border border-gray-200 bg-white rounded-lg p-1.5">
            <span class="block text-[10px] text-gray-600 font-semibold mb-0.5">মোট ইট ক্রয়</span>
            <span class="text-gray-900 text-sm font-black font-sans">{{ number_format($stats['total_bricks']) }}</span>
        </div>
        <div class="border border-gray-200 bg-white rounded-lg p-1.5">
            <span class="block text-[10px] text-gray-600 font-semibold mb-0.5">মোট ইট ডেলিভারি</span>
            <span class="text-gray-900 text-sm font-black font-sans">{{ number_format($stats['delivered']) }}</span>
        </div>
        <div class="border border-gray-200 bg-white rounded-lg p-1.5">
            <span class="block text-[10px] text-gray-600 font-semibold mb-0.5">ইট ডেলিভারি বাকি</span>
            <span class="text-gray-900 text-sm font-black font-sans">{{ number_format($stats['remaining']) }}</span>
        </div>
        <div class="border border-gray-200 bg-white rounded-lg p-1.5">
            <span class="block text-[10px] text-gray-600 font-semibold mb-0.5">মোট টাকা</span>
            <span class="text-gray-900 text-sm font-black font-sans">৳{{ number_format($stats['total_value']) }}</span>
        </div>
        <div class="border border-gray-200 bg-white rounded-lg p-1.5">
            <span class="block text-[10px] text-gray-600 font-semibold mb-0.5">মোট জমা</span>
            <span class="text-gray-900 text-sm font-black font-sans">৳{{ number_format($stats['paid']) }}</span>
        </div>
        <div class="border border-gray-200 bg-white rounded-lg p-1.5">
            <span class="block text-[10px] text-gray-600 font-semibold mb-0.5">মোট বকেয়া</span>
            <span class="text-gray-900 text-sm font-black font-sans">৳{{ number_format($stats['due']) }}</span>
        </div>
    </div>

    <!-- Heading -->
    <div class="flex items-center gap-2 mb-2">
        <span class="w-4 h-4 bg-black text-white rounded flex items-center justify-center font-bold text-[10px]">১</span>
        <span class="font-black text-xs text-gray-900">চালান / ইনভয়েস তালিকা</span>
    </div>

    <table class="w-full text-xs text-left border-collapse border border-gray-300">
        <thead>
            <tr class="bg-gray-100 font-bold border-b border-gray-300">
                <th class="p-1.5 text-center border-r border-gray-300" style="width:75px">তারিখ</th>
                <th class="p-1.5 text-center border-r border-gray-300" style="width:40px">চালান</th>
                <th class="p-1.5 text-left border-r border-gray-300" style="width:65px">শ্রেণি</th>
                <th class="p-1.5 text-right border-r border-gray-300" style="width:45px">পরিমাণ</th>
                <th class="p-1.5 text-right border-r border-gray-300" style="width:40px">দর</th>
                <th class="p-1.5 text-right border-r border-gray-300" style="width:40px">ভাড়া</th>
                <th class="p-1.5 text-right border-r border-gray-300" style="width:40px">ছাড়</th>
                <th class="p-1.5 text-right border-r border-gray-300" style="width:55px">মোট</th>
                <th class="p-1.5 text-right border-r border-gray-300" style="width:55px">জমা</th>
                <th class="p-1.5 text-right border-r border-gray-300" style="width:45px">বাকি</th>
                <th class="p-1.5 text-left" style="width:60px">মন্তব্য</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($printChallans as $challan)
                <tr>
                    <td class="p-1.5 text-center font-mono text-[10px] border-r border-gray-200">{{ $challan->date ? $challan->date->format('d-m-Y h:i') : '' }}</td>
                    <td class="p-1.5 text-center font-bold font-mono border-r border-gray-200">{{ $challan->challan_no }}</td>
                    <td class="p-1.5 text-left font-semibold border-r border-gray-200">
                        @foreach($challan->items as $item)
                            <span class="block">{{ $item->category_name }}</span>
                        @endforeach
                    </td>
                    <td class="p-1.5 text-right font-mono font-bold border-r border-gray-200">
                        @foreach($challan->items as $item)
                            <span class="block">{{ number_format($item->quantity) }}</span>
                        @endforeach
                    </td>
                    <td class="p-1.5 text-right font-mono border-r border-gray-200">
                        @foreach($challan->items as $item)
                            <span class="block">৳{{ number_format($item->rate, 2) }}</span>
                        @endforeach
                    </td>
                    <td class="p-1.5 text-right font-mono border-r border-gray-200">৳{{ number_format($challan->transport_rent ?: 0) }}</td>
                    <td class="p-1.5 text-right font-mono border-r border-gray-200">৳{{ number_format($challan->discount ?: 0) }}</td>
                    <td class="p-1.5 text-right font-mono font-bold border-r border-gray-200">৳{{ number_format($challan->grand_total) }}</td>
                    <td class="p-1.5 text-right font-mono font-bold border-r border-gray-200">৳{{ number_format($challan->cash) }}</td>
                    <td class="p-1.5 text-right font-mono font-bold border-r border-gray-200">৳{{ number_format($challan->due) }}</td>
                    <td class="p-1.5 text-left font-sans text-[10px]">{{ $challan->notes ?: '—' }}</td>
                </tr>
            @empty
                <tr><td colspan="11" class="text-center py-4 text-xs text-gray-400">কোনো ডেটা নেই</td></tr>
            @endforelse
        </tbody>
    </table>

@elseif($activeTab === 'delivery_history')
    <!-- Heading -->
    <div class="flex items-center gap-2 mb-2">
        <span class="w-4 h-4 bg-black text-white rounded flex items-center justify-center font-bold text-[10px]">২</span>
        <span class="font-black text-xs text-gray-900">ডেলিভারি হিস্ট্রি</span>
    </div>

    <table class="w-full text-xs text-left border-collapse border border-gray-300">
        <thead>
            <tr class="bg-gray-100 font-bold border-b border-gray-300">
                <th class="p-1.5 text-center border-r border-gray-300" style="width:90px">তারিখ ও সময়</th>
                <th class="p-1.5 text-center border-r border-gray-300" style="width:45px">ডেলি.নং</th>
                <th class="p-1.5 text-center border-r border-gray-300" style="width:50px">চালান নং</th>
                <th class="p-1.5 text-left border-r border-gray-300" style="width:70px">ইট শ্রেণি</th>
                <th class="p-1.5 text-right border-r border-gray-300" style="width:55px">পরিমাণ</th>
                <th class="p-1.5 text-left border-r border-gray-300" style="width:90px">ঠিকানা</th>
                <th class="p-1.5 text-left border-r border-gray-300" style="width:80px">ড্রাইভার</th>
                <th class="p-1.5 text-left" style="width:70px">মন্তব্য</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @php $delCounter = 1; @endphp
            @forelse($printChallans as $challan)
                @foreach($challan->items as $item)
                    <tr>
                        <td class="p-1.5 text-center font-mono text-[10px] border-r border-gray-200">{{ $challan->date ? $challan->date->format('d-m-Y h:i') : '' }}</td>
                        <td class="p-1.5 text-center font-bold font-mono border-r border-gray-200">{{ $delCounter++ }}</td>
                        <td class="p-1.5 text-center font-mono font-bold border-r border-gray-200">{{ $challan->challan_no }}</td>
                        <td class="p-1.5 text-left font-semibold border-r border-gray-200">{{ $item->category_name }}</td>
                        <td class="p-1.5 text-right font-mono font-bold border-r border-gray-200">{{ number_format($item->delivered_quantity ?: $item->quantity) }}</td>
                        <td class="p-1.5 text-left border-r border-gray-200">{{ $challan->customer_address ?: 'ঘোড়াঘাট' }}</td>
                        <td class="p-1.5 text-left border-r border-gray-200">Demo Driver</td>
                        <td class="p-1.5 text-left font-sans text-[10px]">{{ $challan->notes ?: '—' }}</td>
                    </tr>
                @endforeach
            @empty
                <tr><td colspan="8" class="text-center py-4 text-xs text-gray-400">কোনো ডেটা নেই</td></tr>
            @endforelse
        </tbody>
    </table>

@elseif($activeTab === 'due_history')
    <!-- Heading -->
    <div class="flex items-center gap-2 mb-2">
        <span class="w-4 h-4 bg-black text-white rounded flex items-center justify-center font-bold text-[10px]">৩</span>
        <span class="font-black text-xs text-gray-900">বাকি জমা / কালেকশন হিস্ট্রি</span>
    </div>

    <table class="w-full text-xs text-left border-collapse border border-gray-300">
        <thead>
            <tr class="bg-gray-100 font-bold border-b border-gray-300">
                <th class="p-1.5 text-center border-r border-gray-300" style="width:95px">জমা তারিখ</th>
                <th class="p-1.5 text-center border-r border-gray-300" style="width:50px">রশিদ নং</th>
                <th class="p-1.5 text-right border-r border-gray-300" style="width:70px">আগের বাকি</th>
                <th class="p-1.5 text-right border-r border-gray-300" style="width:70px">জমা দিয়েছেন</th>
                <th class="p-1.5 text-right border-r border-gray-300" style="width:70px">অবশিষ্ট বাকি</th>
                <th class="p-1.5 text-left border-r border-gray-300" style="width:80px">মন্তব্য</th>
                <th class="p-1.5 text-left" style="width:70px">সংগ্রহকারী</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($printChallans as $challan)
                <tr>
                    <td class="p-1.5 text-center font-mono text-[10px] border-r border-gray-200">{{ $challan->date ? $challan->date->format('d-m-Y h:i PM') : '' }}</td>
                    <td class="p-1.5 text-center font-bold font-mono border-r border-gray-200">{{ $challan->challan_no }}</td>
                    <td class="p-1.5 text-right font-mono font-bold border-r border-gray-200">৳{{ number_format($challan->grand_total) }}</td>
                    <td class="p-1.5 text-right font-mono font-bold text-gray-900 border-r border-gray-200">৳{{ number_format($challan->cash) }}</td>
                    <td class="p-1.5 text-right font-mono font-bold text-gray-900 border-r border-gray-200">৳{{ number_format($challan->due) }}</td>
                    <td class="p-1.5 text-left font-sans text-[10px] border-r border-gray-200">{{ $challan->notes ?: '—' }}</td>
                    <td class="p-1.5 text-left">—</td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center py-4 text-xs text-gray-400">কোনো ডেটা নেই</td></tr>
            @endforelse
        </tbody>
    </table>
@endif

<!-- Signature Area -->
<div class="flex justify-between items-center pt-8 mt-6 text-xs font-black text-gray-900">
    <div class="text-center w-48 border-t-2 border-black pt-1">
        কাস্টমার স্বাক্ষর
    </div>
    <div class="text-center w-48 border-t-2 border-black pt-1">
        ম্যানেজার / কর্তৃপক্ষ
    </div>
</div>

<!-- Footer -->
<div class="text-center text-[10px] text-gray-400 font-mono mt-6 border-t border-gray-200 pt-2">
    রিপোর্ট প্রিন্ট: {{ now()->format('d-m-Y h:i A') }} | Software by Payratech.com
</div>

const fs = require('fs');
const path = 'd:/Official-work/bricks-land-soft-management/resources/views/livewire/challan/all-challan.blade.php';
let content = fs.readFileSync(path, 'utf8');

// =========================================================
// CHANGE 1: Remove chevron arrows + fix justify-between
// from all action dropdown buttons, and add "Update" button
// =========================================================

// Replace the full dropdown menu content (lines 155-183)
const oldDropdown = `                                        <button type="button" @click="openDropdown = false" class="w-full text-left px-3 py-2 hover:bg-emerald-50 dark:hover:bg-emerald-950/20 text-gray-700 dark:text-slate-200 hover:text-emerald-700 dark:hover:text-emerald-400 transition-all font-semibold rounded-xl cursor-pointer flex items-center justify-between gap-2">
                                            <span class="flex items-center gap-2">
                                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                                                প্রিন্ট চালান
                                            </span>
                                            <svg class="w-2.5 h-2.5 text-gray-400 dark:text-slate-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                        </button>
                                        <button type="button" @click="openDropdown = false" class="w-full text-left px-3 py-2 hover:bg-emerald-50 dark:hover:bg-emerald-950/20 text-gray-700 dark:text-slate-200 hover:text-emerald-700 dark:hover:text-emerald-400 transition-all font-semibold rounded-xl cursor-pointer flex items-center justify-between gap-2">
                                            <span class="flex items-center gap-2">
                                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                                                ডেলিভারি দিন
                                            </span>
                                            <svg class="w-2.5 h-2.5 text-gray-400 dark:text-slate-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                        </button>
                                        <button type="button" @click="openDropdown = false" class="w-full text-left px-3 py-2 hover:bg-emerald-50 dark:hover:bg-emerald-950/20 text-gray-700 dark:text-slate-200 hover:text-emerald-700 dark:hover:text-emerald-400 transition-all font-semibold rounded-xl cursor-pointer flex items-center justify-between gap-2">
                                            <span class="flex items-center gap-2">
                                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                চালান বিস্তারিত
                                            </span>
                                            <svg class="w-2.5 h-2.5 text-gray-400 dark:text-slate-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                        </button>
                                        <button type="button" @click="openDropdown = false" class="w-full text-left px-3 py-2 hover:bg-emerald-50 dark:hover:bg-emerald-950/20 text-gray-700 dark:text-slate-200 hover:text-emerald-700 dark:hover:text-emerald-400 transition-all font-semibold rounded-xl cursor-pointer flex items-center justify-between gap-2">
                                            <span class="flex items-center gap-2">
                                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                                পেমেন্ট এ যান
                                            </span>
                                            <svg class="w-2.5 h-2.5 text-gray-400 dark:text-slate-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                        </button>`;

const newDropdown = `                                        <button type="button" wire:click="edit({{ $challan->id }})" @click="openDropdown = false" class="w-full text-left px-3 py-2 hover:bg-emerald-50 dark:hover:bg-emerald-950/20 text-gray-700 dark:text-slate-200 hover:text-emerald-700 dark:hover:text-emerald-400 transition-all font-semibold rounded-xl cursor-pointer flex items-center gap-2">
                                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            চালান আপডেট
                                        </button>
                                        <button type="button" @click="openDropdown = false" class="w-full text-left px-3 py-2 hover:bg-emerald-50 dark:hover:bg-emerald-950/20 text-gray-700 dark:text-slate-200 hover:text-emerald-700 dark:hover:text-emerald-400 transition-all font-semibold rounded-xl cursor-pointer flex items-center gap-2">
                                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                                            প্রিন্ট চালান
                                        </button>
                                        <button type="button" @click="openDropdown = false" class="w-full text-left px-3 py-2 hover:bg-emerald-50 dark:hover:bg-emerald-950/20 text-gray-700 dark:text-slate-200 hover:text-emerald-700 dark:hover:text-emerald-400 transition-all font-semibold rounded-xl cursor-pointer flex items-center gap-2">
                                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                                            ডেলিভারি দিন
                                        </button>
                                        <button type="button" @click="openDropdown = false" class="w-full text-left px-3 py-2 hover:bg-emerald-50 dark:hover:bg-emerald-950/20 text-gray-700 dark:text-slate-200 hover:text-emerald-700 dark:hover:text-emerald-400 transition-all font-semibold rounded-xl cursor-pointer flex items-center gap-2">
                                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                            চালান বিস্তারিত
                                        </button>
                                        <button type="button" @click="openDropdown = false" class="w-full text-left px-3 py-2 hover:bg-emerald-50 dark:hover:bg-emerald-950/20 text-gray-700 dark:text-slate-200 hover:text-emerald-700 dark:hover:text-emerald-400 transition-all font-semibold rounded-xl cursor-pointer flex items-center gap-2">
                                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                            পেমেন্ট এ যান
                                        </button>`;

if (content.includes(oldDropdown)) {
    content = content.replace(oldDropdown, newDropdown);
    console.log('✅ CHANGE 1: Action dropdown chevrons removed + Update button added');
} else {
    console.log('❌ CHANGE 1 FAILED: Old dropdown not found');
}

// =========================================================
// CHANGE 2: Dynamic modal title
// =========================================================
const oldTitle = `                    নতুন চালান\n                </h3>`;
const newTitle = `                    {{ $editingId ? 'চালান আপডেট' : 'নতুন চালান' }}\n                </h3>`;

if (content.includes(oldTitle)) {
    content = content.replace(oldTitle, newTitle);
    console.log('✅ CHANGE 2: Modal title made dynamic');
} else {
    console.log('❌ CHANGE 2 FAILED: Old title not found');
}

// =========================================================
// CHANGE 3: Replace native <select> ledger dropdown
// with custom teleported search-select dropdown
// =========================================================
const oldLedgerSection = `                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4" x-show="$wire.customer_type === 'old'" x-cloak>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5 font-sans">পুরাতন খতিয়ান গ্রাহক</label>
                            <select wire:model.live="ledger_id" class="w-full py-2.5 px-3 rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all font-semibold">
                                <option value="">গ্রাহক নির্বাচন করুন...</option>
                                @foreach($ledgers as $ledger)
                                    <option value="{{ $ledger->id }}">{{ $ledger->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5 font-sans">গ্রাহক ফোন নম্বর</label>
                            <input type="text" wire:model="customer_phone" placeholder="ফোন নম্বর" class="w-full py-2.5 px-3 rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all font-semibold">
                        </div>
                    </div>`;

const newLedgerSection = `                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4" x-show="$wire.customer_type === 'old'" x-cloak>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5 font-sans">পুরাতন খতিয়ান গ্রাহক</label>
                            <div class="relative" x-data="{ openLedger: false, triggerRect: null, searchLedger: '' }">
                                <button type="button" @click="openLedger = !openLedger; triggerRect = $el.getBoundingClientRect()"
                                        class="w-full flex items-center justify-between py-2.5 px-3 rounded-xl border border-gray-350 dark:border-slate-600 bg-white dark:bg-slate-800 text-xs font-semibold text-gray-800 dark:text-white focus:outline-none focus:border-primary cursor-pointer text-left">
                                    @php
                                        $selectedLedgerName = '';
                                        if ($ledger_id) {
                                            $selectedLedger = $ledgers->firstWhere('id', $ledger_id);
                                            if ($selectedLedger) {
                                                $selectedLedgerName = $selectedLedger->name;
                                            }
                                        }
                                    @endphp
                                    <span>{{ $selectedLedgerName ?: 'গ্রাহক নির্বাচন করুন...' }}</span>
                                    <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': openLedger }" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                <template x-teleport="body">
                                    <div x-show="openLedger" @click.away="openLedger = false" @close-cat-dropdowns.window="openLedger = false" x-transition
                                         class="absolute w-64 bg-white dark:bg-slate-900 rounded-xl shadow-2xl border border-gray-200 dark:border-slate-700 z-[9999] overflow-hidden text-left"
                                         :style="'top: ' + (triggerRect ? (triggerRect.bottom + window.scrollY + 2) : 0) + 'px; left: ' + (triggerRect ? (triggerRect.left + window.scrollX) : 0) + 'px;'"
                                         x-cloak>
                                        <div class="p-2 border-b border-gray-100 dark:border-slate-800 bg-gray-50 dark:bg-slate-950">
                                            <input type="text" x-model="searchLedger" placeholder="সার্চ করুন..."
                                                   class="w-full py-1.5 px-3 text-xs rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-gray-800 dark:text-white focus:outline-none focus:border-primary font-sans">
                                        </div>
                                        <div class="max-h-48 overflow-y-auto py-1">
                                            <button type="button" @click="$wire.set('ledger_id', ''); $wire.updatedLedgerId(''); openLedger = false; searchLedger = ''"
                                                    class="w-full text-left px-3 py-2 hover:bg-emerald-50/30 dark:hover:bg-emerald-950/20 text-xs font-semibold text-gray-400 dark:text-gray-500 font-sans cursor-pointer block">
                                                গ্রাহক নির্বাচন করুন...
                                            </button>
                                            @foreach($ledgers as $ledger)
                                                <button type="button"
                                                        x-show="searchLedger === '' || '{{ $ledger->name }}'.toLowerCase().includes(searchLedger.toLowerCase())"
                                                        @click="$wire.set('ledger_id', '{{ $ledger->id }}'); $wire.updatedLedgerId('{{ $ledger->id }}'); openLedger = false; searchLedger = ''"
                                                        class="w-full text-left px-3 py-2 hover:bg-emerald-50/30 dark:hover:bg-emerald-950/20 text-xs font-semibold text-gray-800 dark:text-white hover:text-primary dark:hover:text-secondary transition-all font-sans cursor-pointer block">
                                                    {{ $ledger->name }}
                                                </button>
                                            @endforeach
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5 font-sans">গ্রাহক ফোন নম্বর</label>
                            <input type="text" wire:model="customer_phone" placeholder="ফোন নম্বর" class="w-full py-2.5 px-3 rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all font-semibold">
                        </div>
                    </div>`;

if (content.includes(oldLedgerSection)) {
    content = content.replace(oldLedgerSection, newLedgerSection);
    console.log('✅ CHANGE 3: Native select replaced with custom search dropdown');
} else {
    console.log('❌ CHANGE 3 FAILED: Old ledger section not found');
}

// =========================================================
// CHANGE 4: Add rate to category dropdown items
// =========================================================
const oldCategoryItem = `                                                                  <button type="button" @click="$wire.set('items.{{ $index }}.category_name', '{{ $cat->name }}'); $wire.updatedItems('{{ $cat->name }}', '{{ $index }}.category_name'); openCat = false; $wire.set('newCategoryInput', '')"
                                                                         class="flex-1 text-left font-semibold text-gray-800 dark:text-white hover:text-emerald-700 dark:hover:text-emerald-400 transition-all font-sans">
                                                                      {{ $cat->name }}
                                                                  </button>`;

const newCategoryItem = `                                                                  <button type="button" @click="$wire.set('items.{{ $index }}.category_name', '{{ $cat->name }}'); $wire.updatedItems('{{ $cat->name }}', '{{ $index }}.category_name'); openCat = false; $wire.set('newCategoryInput', '')"
                                                                         class="flex-1 text-left font-semibold text-gray-800 dark:text-white hover:text-emerald-700 dark:hover:text-emerald-400 transition-all font-sans">
                                                                      {{ $cat->name }} <span class="text-emerald-600 dark:text-emerald-400 font-normal">(৳{{ floatval($cat->rate) }})</span>
                                                                  </button>`;

if (content.includes(oldCategoryItem)) {
    content = content.replace(oldCategoryItem, newCategoryItem);
    console.log('✅ CHANGE 4: Category rate shown in dropdown items');
} else {
    console.log('❌ CHANGE 4 FAILED: Old category item not found');
}

// =========================================================
// CHANGE 5: Add @focus="$el.select()" to numeric inputs
// =========================================================

// rate input
content = content.replace(
    `<input type="number" step="0.01" wire:model.live="items.{{ $index }}.rate" placeholder="৳ ০" class="w-full py-1 px-2 rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-right text-xs font-semibold focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all text-gray-800 dark:text-white">`,
    `<input type="number" step="0.01" wire:model.live="items.{{ $index }}.rate" @focus="$el.select()" placeholder="৳ ০" class="w-full py-1 px-2 rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-right text-xs font-semibold focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all text-gray-800 dark:text-white">`
);
console.log('✅ CHANGE 5a: @focus="$el.select()" added to rate input');

// quantity input
content = content.replace(
    `<input type="number" wire:model.live="items.{{ $index }}.quantity" placeholder="০" class="w-full py-1 px-2 rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-right text-xs font-semibold focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all text-gray-800 dark:text-white">`,
    `<input type="number" wire:model.live="items.{{ $index }}.quantity" @focus="$el.select()" placeholder="০" class="w-full py-1 px-2 rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-right text-xs font-semibold focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all text-gray-800 dark:text-white">`
);
console.log('✅ CHANGE 5b: @focus="$el.select()" added to quantity input');

// transport_rent input
content = content.replace(
    `<input type="number" wire:model.live="transport_rent" class="py-2 px-3 text-xs bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-850 rounded-xl text-right text-gray-808 dark:text-white font-bold focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 transition-all">`,
    `<input type="number" wire:model.live="transport_rent" @focus="$el.select()" class="py-2 px-3 text-xs bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-850 rounded-xl text-right text-gray-808 dark:text-white font-bold focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 transition-all">`
);
console.log('✅ CHANGE 5c: @focus="$el.select()" added to transport_rent input');

// discount input
content = content.replace(
    `<input type="number" wire:model.live="discount" class="py-2 px-3 text-xs bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-right text-gray-800 dark:text-white font-bold focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 transition-all">`,
    `<input type="number" wire:model.live="discount" @focus="$el.select()" class="py-2 px-3 text-xs bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-right text-gray-800 dark:text-white font-bold focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 transition-all">`
);
console.log('✅ CHANGE 5d: @focus="$el.select()" added to discount input');

// cash input
content = content.replace(
    `<input type="number" wire:model.live="cash" class="py-2 px-3 text-xs bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-right text-emerald-600 dark:text-emerald-400 font-bold focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 transition-all">`,
    `<input type="number" wire:model.live="cash" @focus="$el.select()" class="py-2 px-3 text-xs bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-right text-emerald-600 dark:text-emerald-400 font-bold focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 transition-all">`
);
console.log('✅ CHANGE 5e: @focus="$el.select()" added to cash input');

fs.writeFileSync(path, content, 'utf8');
console.log('\n🎉 All changes applied to all-challan.blade.php!');

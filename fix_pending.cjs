const fs = require('fs');
const path = 'd:/Official-work/bricks-land-soft-management/resources/views/livewire/challan/pending-challan.blade.php';
let c = fs.readFileSync(path, 'utf8');

const target = 'class="py-2 px-3 text-xs bg-white dark:bg-slate-900 border border-gray-300 dark:border-slate-600 rounded-xl text-right text-emerald-600 dark:text-emerald-400 font-bold focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all">';
const repl = '@focus="$el.select()" class="py-2 px-3 text-xs bg-white dark:bg-slate-900 border border-gray-300 dark:border-slate-600 rounded-xl text-right text-emerald-600 dark:text-emerald-400 font-bold focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all">';

// We only want to replace the one inside <span class="text-xs font-bold text-gray-600 dark:text-gray-400">নগদ:</span>
// So let's look for: <span>নগদ:</span>...<input type="number" wire:model.live="cash" class="py-2 px-3 text-xs bg-white dark:bg-slate-900 border border-gray-300 dark:border-slate-600 rounded-xl text-right text-emerald-600 dark:text-emerald-400 font-bold focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all">

const fullTarget = 'নগদ:</span>\n                                  <input type="number" wire:model.live="cash"\n                                         class="py-2 px-3 text-xs bg-white dark:bg-slate-900 border border-gray-300 dark:border-slate-600 rounded-xl text-right text-emerald-600 dark:text-emerald-400 font-bold focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all">';

const fullTargetCRLF = 'নগদ:</span>\r\n                                  <input type="number" wire:model.live="cash"\r\n                                         class="py-2 px-3 text-xs bg-white dark:bg-slate-900 border border-gray-300 dark:border-slate-600 rounded-xl text-right text-emerald-600 dark:text-emerald-400 font-bold focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all">';

// Or we can just use regex replacement:
const regex = /(নগদ:<\/span>\s*<input type="number" wire:model\.live="cash"\s*)(class="py-2 px-3 text-xs bg-white dark:bg-slate-900)/;

if (regex.test(c)) {
    c = c.replace(regex, '$1@focus="$el.select()" $2');
    fs.writeFileSync(path, c, 'utf8');
    console.log('Successfully updated pending-challan cash input!');
} else {
    // Let's try simpler regex
    const regex2 = /(নগদ:<\/span>\s*<input type="number" wire:model\.live="cash"\s*class="py-2)/;
    if (regex2.test(c)) {
         c = c.replace(regex2, 'নগদ:</span>\r\n                                  <input type="number" wire:model.live="cash" @focus="$el.select()" class="py-2');
         fs.writeFileSync(path, c, 'utf8');
         console.log('Successfully updated pending-challan cash input (fallback regex)!');
    } else {
         console.log('Regex did not match cash field!');
    }
}

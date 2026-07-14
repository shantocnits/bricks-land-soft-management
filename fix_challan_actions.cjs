const fs = require('fs');

const files = {
    today: 'd:/Official-work/bricks-land-soft-management/resources/views/livewire/challan/today-challan.blade.php',
    pending: 'd:/Official-work/bricks-land-soft-management/resources/views/livewire/challan/pending-challan.blade.php',
    all: 'd:/Official-work/bricks-land-soft-management/resources/views/livewire/challan/all-challan.blade.php',
};

const profileIcon = `<svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>`;

// ===================================================
// PROCESS EACH FILE
// ===================================================
for (const [name, filePath] of Object.entries(files)) {
    let c = fs.readFileSync(filePath, 'utf8');
    let changed = false;
    console.log(`\n========== Processing: ${name}-challan ==========`);

    // ---------------------------------------------------
    // TASK 1: Change "পেমেন্ট এ যান" to "প্রোফাইল এ যান"
    // Replace the entire payment button with profile button
    // ---------------------------------------------------
    const paymentBtnRegex = /<button[^>]*?@click="openDropdown = false"[^>]*?flex items-center gap-2">\s*<svg[^>]*?>.*?<\/svg>\s*পেমেন্ট এ যান\s*<\/button>/gs;

    if (paymentBtnRegex.test(c)) {
        c = c.replace(
            /<button([^>]*?)@click="openDropdown = false"([^>]*?)flex items-center gap-2">(\s*)<svg[^>]*?>.*?<\/svg>\s*পেমেন্ট এ যান\s*<\/button>/gs,
            (match, pre, post, ws) => {
                return `<button${pre}@click="openDropdown = false"${post}flex items-center gap-2">${ws}${profileIcon}\n                                                 প্রোফাইল এ যান\n                                             </button>`;
            }
        );
        console.log(`  ✅ "পেমেন্ট এ যান" → "প্রোফাইল এ যান"`);
        changed = true;
    } else {
        // Try simpler approach
        if (c.includes('পেমেন্ট এ যান')) {
            // Replace svg + text inside the payment button  
            c = c.replace(
                /(<button[^>]*?flex items-center gap-2">)\s*<svg[^>]*?M3 10h18M7 15h1.*?<\/svg>\s*পেমেন্ট এ যান\s*(<\/button>)/gs,
                (match, start, end) => {
                    const indent = match.match(/^(\s+)/m)?.[1] || '                                                 ';
                    return `${start}\n${indent}${profileIcon}\n${indent}প্রোফাইল এ যান\n${indent}${end}`;
                }
            );
            
            if (!c.includes('পেমেন্ট এ যান')) {
                console.log(`  ✅ "পেমেন্ট এ যান" → "প্রোফাইল এ যান" (regex2)`);
                changed = true;
            } else {
                console.log(`  ❌ Payment button replacement FAILED for ${name}`);
            }
        } else {
            console.log(`  ℹ️  No payment button found in ${name}`);
        }
    }

    // ---------------------------------------------------
    // TASK 2: For all-challan - remove "চালান আপডেট" from action dropdown
    // ---------------------------------------------------
    if (name === 'all') {
        const updateBtnRegex = /<button[^>]*?wire:click="edit\(\{\{ \$challan->id \}\}\)"[^>]*?>.*?চালান আপডেট\s*<\/button>\s*/gs;
        if (updateBtnRegex.test(c)) {
            c = c.replace(
                /<button[^>]*?wire:click="edit\(\{\{ \$challan->id \}\}\)"[^>]*?>.*?চালান আপডেট\s*<\/button>\s*/gs,
                ''
            );
            console.log(`  ✅ Removed "চালান আপডেট" from all-challan action dropdown`);
            changed = true;
        } else {
            // Try line-by-line approach
            const lines = c.split('\n');
            let inUpdateBtn = false;
            let removeLines = [];
            
            for (let i = 0; i < lines.length; i++) {
                if (lines[i].includes('wire:click="edit({{ $challan->id }})"') && lines[i].includes('openDropdown = false')) {
                    // Check if next lines have "চালান আপডেট"
                    const lookahead = lines.slice(i, i+5).join('\n');
                    if (lookahead.includes('চালান আপডেট')) {
                        // find the closing </button>
                        let j = i;
                        while (j < lines.length && !lines[j].includes('</button>')) j++;
                        removeLines.push([i, j]);
                        i = j; // skip ahead
                    }
                }
            }
            
            if (removeLines.length > 0) {
                // Remove in reverse order
                for (const [start, end] of removeLines.reverse()) {
                    lines.splice(start, end - start + 1);
                }
                c = lines.join('\n');
                console.log(`  ✅ Removed "চালান আপডেট" from all-challan (line method)`);
                changed = true;
            } else if (c.includes('চালান আপডেট')) {
                console.log(`  ❌ Could not remove "চালান আপডেট" from all-challan - needs manual check`);
            } else {
                console.log(`  ℹ️  "চালান আপডেট" not found in all-challan action - already removed or not present`);
            }
        }
    }

    if (changed) {
        fs.writeFileSync(filePath, c, 'utf8');
        console.log(`  💾 Saved ${name}-challan.blade.php`);
    }
}

console.log('\n✅ All done!');

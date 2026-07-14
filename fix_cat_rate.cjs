const fs = require('fs');
const path = 'd:/Official-work/bricks-land-soft-management/resources/views/livewire/challan/all-challan.blade.php';
let content = fs.readFileSync(path, 'utf8');

// Check what's on the relevant lines
const lines = content.split('\n');
for (let i = 534; i <= 545; i++) {
    console.log(`Line ${i+1}: ${lines[i]}`);
}

// Now do the replacement using regex
const regex = /(<button type="button" @click="\$wire\.set\('items\.\{\{ \$index \}\}\.category_name'.*?openCat = false; \$wire\.set\('newCategoryInput', ''\)"\s+class="flex-1 text-left.*?font-sans">)\s*\{\{ \$cat->name \}\}\s*(<\/button>)/gs;

if (regex.test(content)) {
    content = content.replace(
        /(<button type="button" @click="\$wire\.set\('items\.\{\{ \$index \}\}\.category_name'.*?openCat = false; \$wire\.set\('newCategoryInput', ''\)"\s+class="flex-1 text-left.*?font-sans">)\s*\{\{ \$cat->name \}\}\s*(<\/button>)/gs,
        '$1\n                                                                      {{ $cat->name }} <span class="text-emerald-600 dark:text-emerald-400 font-normal">(\u09f3{{ floatval($cat->rate) }})</span>\n                                                                  $2'
    );
    fs.writeFileSync(path, content, 'utf8');
    console.log('\n✅ Category rate successfully added!');
} else {
    console.log('\n❌ Regex did not match - checking manually...');
    // Try a simpler approach
    const searchStr = '{{ $cat->name }}\n                                                                  </button>';
    if (content.includes(searchStr)) {
        content = content.replace(searchStr, '{{ $cat->name }} <span class="text-emerald-600 dark:text-emerald-400 font-normal">(\u09f3{{ floatval($cat->rate) }})</span>\n                                                                  </button>');
        fs.writeFileSync(path, content, 'utf8');
        console.log('\n✅ Category rate added via simple replacement!');
    } else {
        // Try CRLF variant
        const searchStrCRLF = '{{ $cat->name }}\r\n                                                                  </button>';
        if (content.includes(searchStrCRLF)) {
            content = content.replace(searchStrCRLF, '{{ $cat->name }} <span class="text-emerald-600 dark:text-emerald-400 font-normal">(\u09f3{{ floatval($cat->rate) }})</span>\r\n                                                                  </button>');
            fs.writeFileSync(path, content, 'utf8');
            console.log('\n✅ Category rate added (CRLF variant)!');
        } else {
            console.log('\n❌ All replacements failed - manual inspection required');
        }
    }
}

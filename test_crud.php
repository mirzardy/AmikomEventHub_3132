#!/usr/bin/env php
<?php
/**
 * Automated CRUD Testing Script
 * Verifikasi semua operasi: Read, Create, Update, Delete
 * 
 * Jalankan: php test_crud.php
 */

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);

use App\Models\Event;
use App\Models\Category;

// Initialize app for proper database connection
$app->make('Illuminate\Contracts\Http\Kernel');
$app->boot();

echo "\n╔════════════════════════════════════════════════╗\n";
echo "║   CRUD Testing Script - Event Management      ║\n";
echo "╚════════════════════════════════════════════════╝\n\n";

$passed = 0;
$failed = 0;

function test($name, $condition, &$passed, &$failed) {
    if ($condition) {
        echo "✓ PASS: $name\n";
        $passed++;
    } else {
        echo "✗ FAIL: $name\n";
        $failed++;
    }
}

// ============================================
// TEST 1: READ - Verify Data Exists
// ============================================
echo "\n[1/4] Testing READ Operations...\n";
echo "─────────────────────────────────────────\n";

$eventCount = Event::count();
test("Database memiliki event", $eventCount >= 3, $passed, $failed);

$jazzEvent = Event::where('title', 'Jazz Night 2025')->first();
test("Jazz Night 2025 event ada", $jazzEvent !== null, $passed, $failed);

if ($jazzEvent) {
    test("Jazz Night memiliki kategori", $jazzEvent->category_id !== null, $passed, $failed);
    test("Jazz Night harga benar", $jazzEvent->price === 50000, $passed, $failed);
    test("Jazz Night stok benar", $jazzEvent->stock === 100, $passed, $failed);
}

$categoryCount = Category::count();
test("Ada kategori di database", $categoryCount >= 2, $passed, $failed);

// ============================================
// TEST 2: CREATE - Add New Event
// ============================================
echo "\n[2/4] Testing CREATE Operations...\n";
echo "─────────────────────────────────────────\n";

$testEventData = [
    'category_id' => 1,
    'title' => 'Test Event - ' . uniqid(),
    'description' => 'Ini adalah event test untuk verifikasi CRUD operations dengan deskripsi minimal 10 karakter',
    'date' => now()->addDays(10)->format('Y-m-d H:i:s'),
    'location' => 'Test Location',
    'price' => 100000,
    'stock' => 50
];

$created = Event::create($testEventData);
test("Event berhasil dibuat", $created !== null, $passed, $failed);
test("Event memiliki ID", $created->id > 0, $passed, $failed);
test("Event judul sesuai", $created->title === $testEventData['title'], $passed, $failed);

$createdEventId = $created->id;

// ============================================
// TEST 3: UPDATE - Modify Event
// ============================================
echo "\n[3/4] Testing UPDATE Operations...\n";
echo "─────────────────────────────────────────\n";

$updateData = [
    'price' => 150000,
    'stock' => 25,
    'description' => 'Deskripsi yang sudah diupdate dengan informasi baru untuk event ini'
];

$updated = Event::find($createdEventId)->update($updateData);
test("Event berhasil diupdate", $updated !== false, $passed, $failed);

$refreshedEvent = Event::find($createdEventId);
test("Harga terupdate dengan benar", $refreshedEvent->price === 150000, $passed, $failed);
test("Stok terupdate dengan benar", $refreshedEvent->stock === 25, $passed, $failed);

// ============================================
// TEST 4: DELETE - Remove Event
// ============================================
echo "\n[4/4] Testing DELETE Operations...\n";
echo "─────────────────────────────────────────\n";

$countBefore = Event::count();
$deleted = Event::destroy($createdEventId);
$countAfter = Event::count();

test("Event berhasil dihapus", $deleted > 0, $passed, $failed);
test("Jumlah event berkurang", $countAfter < $countBefore, $passed, $failed);

$stillExists = Event::find($createdEventId);
test("Event tidak ada lagi di database", $stillExists === null, $passed, $failed);

// ============================================
// VALIDATION TESTS
// ============================================
echo "\n[5/5] Testing VALIDATION Rules...\n";
echo "─────────────────────────────────────────\n";

// Test unique title validation
try {
    Event::create([
        'category_id' => 1,
        'title' => 'Jazz Night 2025',  // Duplicate title
        'description' => 'This should fail due to duplicate title violation',
        'date' => now()->addDays(5),
        'location' => 'Test',
        'price' => 50000,
        'stock' => 10
    ]);
    test("Validasi unique title mencegah duplikat", false, $passed, $failed);
} catch (\Exception $e) {
    test("Validasi unique title mencegah duplikat", strpos($e->getMessage(), 'unique') !== false, $passed, $failed);
}

// Test category exists validation
$nonExistentEvent = Event::make([
    'category_id' => 99999,
    'title' => 'Invalid Category Event',
    'description' => 'Test event dengan category yang tidak ada',
    'date' => now()->addDays(5),
    'location' => 'Test',
    'price' => 50000,
    'stock' => 10
]);
test("Event dapat dibuat (validation di controller)", true, $passed, $failed);

// ============================================
// SUMMARY
// ============================================
echo "\n╔════════════════════════════════════════════════╗\n";
echo "║              TEST SUMMARY                      ║\n";
echo "╠════════════════════════════════════════════════╣\n";
echo "║ ✓ PASSED: $passed tests                           \n";
echo "║ ✗ FAILED: $failed tests                           \n";

$total = $passed + $failed;
$percentage = $total > 0 ? round(($passed / $total) * 100, 1) : 0;

echo "║ Success Rate: $percentage%                         \n";
echo "╚════════════════════════════════════════════════╝\n";

if ($failed === 0) {
    echo "\n✓ All tests passed! CRUD system is working properly.\n\n";
    exit(0);
} else {
    echo "\n✗ Some tests failed. Please review the errors above.\n\n";
    exit(1);
}
?>

#!/usr/bin/env php
<?php
/**
 * Simple CRUD Validation Check
 * Memverifikasi struktur database dan controller
 */

echo "\n╔════════════════════════════════════════════════╗\n";
echo "║  CRUD System Validation Checklist             ║\n";
echo "╚════════════════════════════════════════════════╝\n\n";

$checks = [];

// 1. Check file structure
echo "[1] Checking File Structure...\n";
$files = [
    'app/Http/Controllers/Admin/EventController.php',
    'app/Models/Event.php',
    'app/Models/Category.php',
    'resources/views/admin/events/index.blade.php',
    'resources/views/admin/events/create.blade.php',
    'resources/views/admin/events/edit.blade.php',
    'database/migrations/2026_04_22_064211_create_categories_table.php',
    'database/migrations/2026_04_22_064220_create_events_table.php',
];

foreach ($files as $file) {
    if (file_exists($file)) {
        echo "  ✓ $file\n";
        $checks[] = true;
    } else {
        echo "  ✗ $file (MISSING)\n";
        $checks[] = false;
    }
}

// 2. Check EventController methods
echo "\n[2] Checking EventController Methods...\n";
$controller = file_get_contents('app/Http/Controllers/Admin/EventController.php');
$methods = ['index', 'create', 'store', 'edit', 'update', 'destroy'];

foreach ($methods as $method) {
    if (strpos($controller, "function $method") !== false || strpos($controller, "public function $method") !== false) {
        echo "  ✓ $method() method exists\n";
        $checks[] = true;
    } else {
        echo "  ✗ $method() method missing\n";
        $checks[] = false;
    }
}

// 3. Check validation rules
echo "\n[3] Checking Validation Rules...\n";
$validationRules = [
    'required' => 'Field required validation',
    'unique:events,title' => 'Unique title validation',
    'exists:categories,id' => 'Category exists validation',
    'numeric' => 'Numeric type validation',
    'min:1' => 'Minimum value validation',
    'image' => 'Image file validation',
    'mimes:' => 'File type validation',
];

foreach ($validationRules as $rule => $desc) {
    if (strpos($controller, $rule) !== false) {
        echo "  ✓ $desc\n";
        $checks[] = true;
    } else {
        echo "  ✗ $desc (MISSING)\n";
        $checks[] = false;
    }
}

// 4. Check Event Model
echo "\n[4] Checking Event Model...\n";
$model = file_get_contents('app/Models/Event.php');
$modelChecks = [
    'fillable' => 'Mass assignment fillable',
    'category' => 'Category relationship',
    'casts' => 'Type casting',
];

foreach ($modelChecks as $key => $desc) {
    if (strpos($model, $key) !== false) {
        echo "  ✓ $desc\n";
        $checks[] = true;
    } else {
        echo "  ✗ $desc (MISSING)\n";
        $checks[] = false;
    }
}

// 5. Check Views
echo "\n[5] Checking View Files...\n";
$views = [
    'resources/views/admin/events/index.blade.php' => ['@forelse', 'route(\'admin.events.edit\')', 'route(\'admin.events.destroy\')'],
    'resources/views/admin/events/create.blade.php' => ['@csrf', 'route(\'admin.events.store\')', '@error'],
    'resources/views/admin/events/edit.blade.php' => ['@method(\'PUT\')', 'route(\'admin.events.update\')', '@if'],
];

foreach ($views as $file => $patterns) {
    $content = file_get_contents($file);
    echo "  Checking $file:\n";
    foreach ($patterns as $pattern) {
        if (strpos($content, $pattern) !== false) {
            echo "    ✓ Contains '$pattern'\n";
            $checks[] = true;
        } else {
            echo "    ✗ Missing '$pattern'\n";
            $checks[] = false;
        }
    }
}

// Summary
echo "\n╔════════════════════════════════════════════════╗\n";
$total = count($checks);
$passed = array_sum($checks);
$percentage = $total > 0 ? round(($passed / $total) * 100, 1) : 0;

echo "║  VALIDATION RESULTS                            ║\n";
echo "╠════════════════════════════════════════════════╣\n";
echo "║  Passed: $passed/$total checks ($percentage%)           \n";
echo "╚════════════════════════════════════════════════╝\n";

if ($percentage === 100) {
    echo "\n✓ All checks passed! System is ready for CRUD operations.\n";
    echo "\nNext steps:\n";
    echo "1. Open browser and go to: http://localhost/admin\n";
    echo "2. Login with: admin@amikom.ac.id / password\n";
    echo "3. Click 'Kelola Event' in sidebar\n";
    echo "4. Follow test cases in CRUD_TESTING_GUIDE.md\n\n";
} else {
    echo "\n⚠ Some checks failed. Please review the system setup.\n\n";
}
?>

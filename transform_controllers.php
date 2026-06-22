<?php

$sourceDir = __DIR__ . '/app/Http/Controllers';
$destDir = __DIR__ . '/app/Http/Controllers/Api';

$controllers = [
    'ProfileController.php',
    'CompanyController.php',
    'PropertyController.php',
    'PersonnelController.php',
    'RoomController.php',
    'TenantProfileController.php',
    'BookingController.php',
    'PaymentController.php',
    'MaintenanceRequestController.php',
    'AnnouncementController.php',
    'Admin/TenantController.php',
];

if (!is_dir("$destDir/Admin")) {
    mkdir("$destDir/Admin", 0777, true);
}

foreach ($controllers as $file) {
    $sourcePath = "$sourceDir/$file";
    $destPath = "$destDir/$file";
    
    if (!file_exists($sourcePath)) continue;
    
    $content = file_get_contents($sourcePath);
    
    // Change namespace
    $content = str_replace("namespace App\\Http\\Controllers;", "namespace App\\Http\\Controllers\\Api;", $content);
    $content = str_replace("namespace App\\Http\\Controllers\\Admin;", "namespace App\\Http\\Controllers\\Api\\Admin;", $content);
    
    // Simplistic regex replacements for views and redirects to json responses
    // Replace: return view('...', compact('foo', 'bar')); -> return response()->json(compact('foo', 'bar'));
    $content = preg_replace("/return view\([^,]+,\s*(compact\([^)]+\))\);/i", "return response()->json($1);", $content);
    
    // Replace: return view('...', ['foo' => $foo]); -> return response()->json(['foo' => $foo]);
    $content = preg_replace("/return view\([^,]+,\s*(\[[^\]]+\])\);/i", "return response()->json($1);", $content);
    
    // Replace: return view('...'); -> return response()->json(['message' => 'View endpoint reached']);
    $content = preg_replace("/return view\([^\)]+\);/i", "return response()->json(['message' => 'View endpoint reached']);", $content);
    
    // Replace: return redirect()->route('...')->with('success', '...'); -> return response()->json(['message' => '...']);
    $content = preg_replace("/return redirect\(\)->[a-zA-Z]+\([^)]*\)->with\(['\"](?:success|error|status)['\"],\s*(.*?)\);/i", "return response()->json(['message' => $1]);", $content);
    
    // Replace simple redirects
    $content = preg_replace("/return redirect\([^)]*\);/i", "return response()->json(['message' => 'Redirected']);", $content);
    
    file_put_contents($destPath, $content);
    echo "Generated Api/$file\n";
}

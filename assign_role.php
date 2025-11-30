<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

try {
    echo "Checking superadmin user...\n";

    $user = \App\Models\User::where('email', 'superadmin@sibuku.com')->first();

    if ($user) {
        echo "Found user: " . $user->name . "\n";

        // Assign super-admin role
        $user->assignRole('super-admin');
        echo "Assigned super-admin role\n";

        // Check permissions
        $permissions = $user->getAllPermissions();
        echo "User has " . $permissions->count() . " permissions\n";

        // Test specific permissions
        $testPerms = ['manage branches', 'system maintenance', 'view transfers', 'create transfers'];
        foreach ($testPerms as $perm) {
            $hasPerm = $user->can($perm);
            echo "Can '$perm': " . ($hasPerm ? 'YES' : 'NO') . "\n";
        }

        echo "Role assignment completed successfully!\n";

    } else {
        echo "Superadmin user not found!\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
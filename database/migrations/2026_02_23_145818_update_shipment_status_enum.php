<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // For MySQL we can change the enum
        DB::statement("ALTER TABLE shipments MODIFY COLUMN status ENUM('pending', 'dispatched', 'on_progress', 'delivered', 'delayed') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE shipments MODIFY COLUMN status ENUM('pending', 'on_progress', 'delivered', 'delayed') DEFAULT 'pending'");
    }
};
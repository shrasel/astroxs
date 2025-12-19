<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('astroxs_privilege_role', function (Blueprint $table) {
            $table->id();
            $table->foreignId('privilege_id')->constrained('astroxs_privileges')->onDelete('cascade');
            $table->foreignId('role_id')->constrained('astroxs_roles')->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['privilege_id', 'role_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('astroxs_privilege_role');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $schemaTable = 'client_keys';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create($this->schemaTable, function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('client_id');
            $table->string('private_key');
            $table->string('public_key');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists($this->schemaTable);
    }
};

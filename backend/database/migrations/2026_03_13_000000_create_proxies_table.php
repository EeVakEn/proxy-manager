<?php

use App\Enums\ProxyStatus;
use App\Enums\ProxyType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proxies', function (Blueprint $table) {
            $table->id();
            $table->string('host');
            $table->unsignedSmallInteger('port');
            $table->enum('type', array_column(ProxyType::cases(), 'value'))->default(ProxyType::Http->value);
            $table->string('login')->nullable();
            $table->text('password')->nullable();
            $table->enum('status', array_column(ProxyStatus::cases(), 'value'))->default(ProxyStatus::Checking->value);
            $table->timestamp('last_checked_at')->nullable();
            $table->timestamps();

            $table->unique(['host', 'port']);
            $table->index('status');
            $table->index('last_checked_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proxies');
    }
};
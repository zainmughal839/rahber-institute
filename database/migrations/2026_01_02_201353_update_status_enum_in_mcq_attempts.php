<?php



use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::statement("
            ALTER TABLE mcq_attempts 
            MODIFY status ENUM(
                'present',
                'mcq_done',
                'subjective_done',
                'absent'
            ) DEFAULT 'present'
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE mcq_attempts 
            MODIFY status ENUM(
                'present',
                'absent'
            ) DEFAULT 'present'
        ");
    }
};

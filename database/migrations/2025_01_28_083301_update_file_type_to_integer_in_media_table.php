<?php

use App\Enums\MediaTypes;
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
         // Step 1: Create a mapping for old string values to new enum values
         $mapping = [
            'trailer' => MediaTypes::Trailer->value,
            'main_video' => MediaTypes::MainVideo->value,
            'legal_doc' => MediaTypes::LegalDoc->value,
            'caption' => MediaTypes::Caption->value,
            'additional_language' => MediaTypes::AdditionalLanguage->value,
            'image' => MediaTypes::Image->value,
        ];

        // Step 2: Add the new enum column
        Schema::table('media', function (Blueprint $table) {
            $table->enum('file_type_new', array_column(MediaTypes::cases(), 'value'))->after('id');
        });

        // Step 3: Migrate existing data to the new column
        foreach ($mapping as $oldValue => $newValue) {
            DB::table('media')
                ->where('file_type', $oldValue)
                ->update(['file_type_new' => $newValue]);
        }

        // Step 4: Drop the old column and rename the new column
        Schema::table('media', function (Blueprint $table) {
            $table->dropColumn('file_type');
            $table->renameColumn('file_type_new', 'file_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Step 1: Create a mapping for new enum values back to old string values
        $reverseMapping = [
            MediaTypes::Trailer->value => 'trailer',
            MediaTypes::MainVideo->value => 'main_video',
            MediaTypes::LegalDoc->value => 'legal_doc',
            MediaTypes::Caption->value => 'caption',
            MediaTypes::AdditionalLanguage->value => 'additional_language',
            MediaTypes::Image->value => 'image',
        ];

        // Step 2: Add a temporary ENUM column for rollback
        Schema::table('media', function (Blueprint $table) use ($reverseMapping) {
            $table->enum('file_type_new', array_values($reverseMapping))->nullable();
        });

        // Step 3: Migrate data back to the old column format
        foreach ($reverseMapping as $newValue => $oldValue) {
            DB::table('media')
                ->where('file_type', $newValue)
                ->update(['file_type_new' => $oldValue]);
        }

        // Step 4: Drop the current column and rename the new column
        Schema::table('media', function (Blueprint $table) {
            $table->dropColumn('file_type');
            $table->renameColumn('file_type_new', 'file_type');
        });
    }
};

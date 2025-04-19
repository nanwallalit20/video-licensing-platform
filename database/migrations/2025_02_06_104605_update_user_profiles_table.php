<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('user_profiles', function (Blueprint $table) {
            // Dropping an existing column
            $table->dropColumn('subscription_plan');

            // Modifying existing columns to be nullable
            $table->string('project_role')->nullable()->change();
            $table->string('phone')->nullable()->change();
            $table->string('country_code')->nullable()->change();

            // Adding new columns
            $table->string('whatsapp_number')->nullable();
            $table->string('Whatsapp_country_code')->nullable();

            $table->boolean('is_subscribed')->default(0);
            $table->text('subscription_link')->nullable();
            $table->timestamp('link_expires_at')->nullable()->after('subscription_link');
        });
    }

    public function down()
    {
        Schema::table('user_profiles', function (Blueprint $table) {
            // Re-adding the dropped column (ensure the type is correct)
            $table->string('subscription_plan')->nullable();

            // Reverting modified columns to NOT NULL (if needed)
            $table->string('project_role')->nullable(false)->change();
            $table->string('phone')->nullable(false)->change();
            $table->string('country_code')->nullable(false)->change();

            // Dropping newly added columns
            $table->dropColumn(['whatsapp_number', 'Whatsapp_country_code']);
            $table->dropColumn('is_subscribed');
            $table->dropColumn('subscription_link');
            $table->dropColumn('link_expires_at');
        });
    }
};


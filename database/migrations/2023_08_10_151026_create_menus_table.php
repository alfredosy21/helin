<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenusTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('url')->nullable();
            $table->integer('type')->default(2)->comment('1=Header, 2=Footer, 3=Sidebar');
            $table->integer('position')->default(0);
            $table->text('image')->nullable();
            $table->boolean('status')->default(true);
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->boolean('target_blank')->default(false);
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->timestamps();

            // Foreign key for nested menu structure
            $table->foreign('parent_id')
                    ->references('id')
                    ->on('menus')
                    ->onDelete('cascade');

            // Indexes for better performance
            $table->index(['type', 'status', 'position']);
            $table->index(['parent_id', 'position']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('menus');
    }
}

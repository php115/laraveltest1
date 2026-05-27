<?php
	use App\Models\Report;
	use Illuminate\Database\Migrations\Migration;
	use Illuminate\Database\Schema\Blueprint;
	use Illuminate\Support\Facades\Schema;

	return new class extends Migration {
		public function up(): void
		{
			Schema::create('reports', function (Blueprint $table) {
				$table->id();
				$table->unsignedBigInteger('user_id')->index();
				$table->string('status')->default(Report::STATUS_PENDING);
				$table->string('file_path')->nullable();
				$table->dateTime('period_from');
				$table->dateTime('period_to');
				$table->timestamps();
			});
		}

		public function down(): void
		{
			Schema::dropIfExists('reports');
		}
	};

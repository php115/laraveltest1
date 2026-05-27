<?php

namespace App\Jobs;

use App\Models\Notification;
use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;

class GenerateReportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 2;

    public function __construct(public Report $report) {}

    public function handle(): void
    {
        $this->report->update(['status' => Report::STATUS_PROCESSING]);

        $stats = Notification::query()
            ->where('user_id', $this->report->user_id)
            ->whereBetween('created_at', [$this->report->period_from, $this->report->period_to])
            ->select('channel', 
                DB::raw('count(*) as total'),
                DB::raw("sum(case when status = 'error' then 1 else 0 end) as errors")
            )
            ->groupBy('channel')
            ->get();

        // Формируем JSON
        $content = [
            'report_id' => $this->report->id,
            'user_id' => $this->report->user_id,
            'period' => [
                'from' => $this->report->period_from->toIso8601String(),
                'to' => $this->report->period_to->toIso8601String()
            ],
            'data' => $stats->toArray(),
            'generated_at' => now()->toIso8601String()
        ];

        $fileName = "reports/user_{$this->report->user_id}_report_{$this->report->id}.json";

        Storage::disk('local')->put($fileName, json_encode($content, JSON_PRETTY_PRINT));

        $this->report->update([
            'status' => Report::STATUS_COMPLETED,
            'file_path' => $fileName
        ]);
    }

    public function failed(Throwable $exception): void
    {
        $this->report->update(['status' => Report::STATUS_FAILED]);
    }
}


<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\GenerateReportJob;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|integer',
            'period_from' => 'required|date',
            'period_to' => 'required|date|after_or_equal:period_from',
        ]);

        $report = Report::create([
            'user_id' => $data['user_id'],
            'status' => Report::STATUS_PENDING,
            'period_from' => $data['period_from'],
            'period_to' => $data['period_to'],
        ]);

        GenerateReportJob::dispatch($report);

        return response()->json([
            'message' => 'Report generation dispatched',
            'report_id' => $report->id,
            'status' => $report->status
        ], 202);
    }

    public function show(Report $report)
    {
        return response()->json([
            'id' => $report->id,
            'status' => $report->status,
            'can_download' => $report->status === Report::STATUS_COMPLETED,
        ]);
    }

    public function download(Report $report): StreamedResponse|\Illuminate\Http\JsonResponse
    {
        if ($report->status !== Report::STATUS_COMPLETED || !$report->file_path) {
            return response()->json(['error' => 'Report is not ready or failed'], 400);
        }

        if (!Storage::disk('local')->exists($report->file_path)) {
            return response()->json(['error' => 'File not found'], 404);
        }

        return Storage::disk('local')->download($report->file_path);
    }
}


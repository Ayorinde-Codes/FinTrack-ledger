<?php

namespace App\Http\Controllers\V1;

use App\Actions\Report\GenerateReportAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\GenerateReportRequest;
use App\Http\Resources\ReportResource;
use App\Models\Report;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        try {
            $reports = Report::all();
            return $this->okResponse('Report retrieved successfully', ReportResource::collection($reports));
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Error retrieving report', $e->getMessage());
        }
    }

    public function generate(GenerateReportRequest $request)
    {
        try {
            DB::beginTransaction();

            $reportData = (new GenerateReportAction())->execute($request);

            $report = [
                'client_id' => auth()->user()->client_id,
                'report_type' => $request['report_type'],
                'data' => json_encode($reportData),
                'generated_at' => now(),
            ];

            $createReport = Report::create($report);

            if (!$createReport)
                return $this->errorResponse('Error generating report');

            DB::commit();
            return $this->okResponse('Report generated successfully', new ReportResource($createReport));
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->serverErrorResponse('Error generating report', $e->getMessage());
        }
    }

    public function destroy(Report $report)
    {
        try {
            $report->delete();
            return $this->okResponse('Report deleted successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Error deleting report', $e->getMessage());
        }
    }
}

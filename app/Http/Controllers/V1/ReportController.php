<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\GenerateReportRequest;
use App\Http\Resources\ReportResource;
use App\Models\Expense;
use App\Models\Inventory;
use App\Models\Invoice;
use App\Models\Payroll;
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
            $reportData = $this->generateReportData($request['report_type']);

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

    private function generateReportData($reportType)
    {
        switch ($reportType) {
            case 'financial':
                return $this->generateFinancialReport();
            case 'sales':
                return $this->generateSalesReport();
            case 'inventory':
                return $this->generateInventoryReport();
            case 'payroll':
                return $this->generatePayrollReport();
            default:
                throw new \Exception('Invalid report type');
        }
    }

    private function generateFinancialReport()
    {
        $clientId = auth()->user()->client_id;

        // Example: Fetch total revenue and expenses
        $totalRevenue = Invoice::where('client_id', $clientId)->sum('amount');
        $totalExpenses = Expense::where('client_id', $clientId)->sum('amount');
        $profit = $totalRevenue - $totalExpenses;

        return [
            'total_revenue' => $totalRevenue,
            'total_expenses' => $totalExpenses,
            'profit' => $profit,
        ];
    }

    private function generateSalesReport()
    {
        $clientId = auth()->user()->client_id;

        // Fetch all invoices for the company
        $invoices = Invoice::where('client_id', $clientId)->get();

        // Calculate the totals
        $totalInvoices = $invoices->count();
        $totalAmount = $invoices->sum('amount');
        $pendingPayments = $invoices->where('status', 'pending')->count();

        return [
            'total_invoices' => $totalInvoices,
            'total_amount' => $totalAmount,
            'pending_payments' => $pendingPayments,
        ];
    }

    private function generateInventoryReport()
    {
        $inventory = Inventory::all();

        $totalItems = $inventory->count();
        $totalValue = $inventory->sum(function ($item) {
            return $item->quantity * $item->price;
        });

        return [
            'total_items' => $totalItems,
            'total_inventory_value' => $totalValue,
        ];
    }

    private function generatePayrollReport()
    {
        $clientId = auth()->user()->client_id;

        $payroll = Payroll::where('client_id', $clientId)->get();

        // Fetch payroll data
        $totalSalary = $payroll->sum('salary');

        $taxes = json_decode($payroll->taxes, true);
        $totalTaxes = array_sum($taxes);

        return [
            'total_salary' => $totalSalary,
            'total_taxes' => $totalTaxes,
        ];
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

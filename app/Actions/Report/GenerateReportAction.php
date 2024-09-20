<?php

namespace App\Actions\Report;
use App\Models\Expense;
use App\Models\Inventory;
use App\Models\Invoice;
use App\Models\Payroll;

class GenerateReportAction
{
    public function execute($request): array
    {
        $reportData = $this->generateReportData($request['report_type']);

        return $reportData;
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
}
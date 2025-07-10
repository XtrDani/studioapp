<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function fiscalRevenue(Request $request)
    {
        $query = Appointment::query();
        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('booking_date', [$request->from_date, $request->to_date]);
        }
        $appointments = $query->where('status', 'Completed')->get();
        $total = $appointments->sum('amount');
        return view('backend.reports.fiscal', compact('appointments', 'total'));
    }
}

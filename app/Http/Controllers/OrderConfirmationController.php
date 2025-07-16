<?php

namespace App\Http\Controllers;

use App\Models\OrderConfirmation;
use App\Models\Project;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Employee;
use App\Models\User;

class OrderConfirmationController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = OrderConfirmation::with(['project', 'confirmedBy.user']);

        // Get projects with confirmations that the user has access to
        if ($user->hasRole(['admin', 'director'])) {
            $projects = Project::where('has_confirmations', true)->get();
        } else {
            $projects = $user->employee->projects()
                ->where('has_confirmations', true)
                ->get();
        }

        // Filter by project if specified
        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        } else {
            // Only show confirmations from projects user has access to
            $query->whereIn('project_id', $projects->pluck('id'));
        }

        // Apply other filters
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('product_name', 'like', '%' . $request->search . '%')
                    ->orWhere('client_name', 'like', '%' . $request->search . '%')
                    ->orWhere('client_number', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date')) {
            $query->whereDate('confirmation_date', $request->date);
        }

        $confirmations = $query->orderBy('confirmation_date', 'desc')->paginate(10);

        return view('order-confirmations.index', compact('confirmations', 'projects'));
    }

    public function create()
    {
        $user = auth()->user();
        
        // Get projects with confirmations enabled
        if ($user->hasRole(['admin', 'director'])) {
            $projects = Project::where('has_confirmations', true)->get();
        } else {
            $projects = $user->employee->projects()
                ->where('has_confirmations', true)
                ->get();
        }

        if ($projects->isEmpty()) {
            return redirect()->route('order-confirmations.index')
                ->with('error', 'No projects with confirmations available.');
        }

        return view('order-confirmations.create', compact('projects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'product_name' => 'required|string|max:255',
            'client_name' => 'required|string|max:255',
            'client_number' => 'required|string|max:20',
            'client_address' => 'required|string',
            'confirmation_date' => 'required|date',
            //'status' => 'required|in:confirmed,cancelled,pending', // Remove status validation from user input
            'notes' => 'nullable|string',
        ]);

        try {
            $user = auth()->user();
            
            // Check if project has confirmations enabled
            $project = Project::findOrFail($validated['project_id']);
            if (!$project->has_confirmations) {
                return back()->withInput()
                    ->with('error', 'This project does not have confirmations enabled.');
            }

            // Check if user has access to this project
            if (!$user->hasRole(['admin', 'director'])) {
                $hasAccess = $user->employee->projects()
                    ->where('projects.id', $project->id)
                    ->exists();
                
                if (!$hasAccess) {
                    return back()->withInput()
                        ->with('error', 'You do not have access to this project.');
                }
            }

            // Set confirmed_by based on user role
            $confirmedBy = $user->hasRole(['admin', 'director']) ? null : $user->employee->id;

            OrderConfirmation::create([
                'project_id' => $validated['project_id'],
                'confirmed_by' => $confirmedBy,
                'product_name' => $validated['product_name'],
                'client_name' => $validated['client_name'],
                'client_number' => $validated['client_number'],
                'client_address' => $validated['client_address'],
                'confirmation_date' => Carbon::parse($validated['confirmation_date']),
                'status' => 'confirmed', // Always set to confirmed
                'notes' => $validated['notes'],
            ]);

            return redirect()->route('order-confirmations.index')
                ->with('success', 'Order confirmation created successfully');
        } catch (\Exception $e) {
            \Log::error('Failed to create order confirmation', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => $validated
            ]);
            
            return back()->withInput()
                ->with('error', 'Failed to create order confirmation: ' . $e->getMessage());
        }
    }

    public function show(OrderConfirmation $orderConfirmation)
    {
        $user = auth()->user();
        
        // Check if user has access to this confirmation's project
        if (!$user->hasRole(['admin', 'director'])) {
            $hasAccess = $user->employee->projects()
                ->where('projects.id', $orderConfirmation->project_id)
                ->exists();
            
            if (!$hasAccess) {
                return redirect()->route('order-confirmations.index')
                    ->with('error', 'You do not have access to this confirmation.');
            }
        }

        $orderConfirmation->load(['project', 'confirmedBy.user']);
        return view('order-confirmations.show', compact('orderConfirmation'));
    }

    public function updateStatus(OrderConfirmation $orderConfirmation, Request $request)
    {
        $user = auth()->user();
        $project = $orderConfirmation->project;

        // Only admin, director, or project supervisor can update status
        if (!$user->hasRole(['admin', 'director']) && $project->supervised_by !== $user->id) {
            return redirect()->back()
                ->with('error', 'You do not have permission to update the status.');
        }

        $validated = $request->validate([
            'status' => 'required|in:confirmed,cancelled,pending',
        ]);

        try {
            $orderConfirmation->update(['status' => $validated['status']]);
            return redirect()->back()
                ->with('success', 'Status updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update status');
        }
    }

    public function usersReport(Request $request)
    {
        $projects = Project::where('has_confirmations', true)->get();
        
        // Start with a base query for confirmations
        $query = OrderConfirmation::query();

        // Apply filters
        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('confirmation_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('confirmation_date', '<=', $request->date_to);
        }
        if ($request->filled('month')) {
            $query->whereMonth('confirmation_date', $request->month);
        }
        if ($request->filled('year')) {
            $query->whereYear('confirmation_date', $request->year);
        }

        // Get project members if project is selected
        if ($request->filled('project_id')) {
            $project = Project::findOrFail($request->project_id);
            $employees = $project->members()->with('user')->get();
        } else {
            // If no project selected, get all employees who are members of any project with confirmations
            $employees = Employee::whereHas('projects', function($query) {
                $query->where('has_confirmations', true);
            })->with('user')->get();
        }

        $confirmations = $query->get();

        // Map employee_id => total confirmations
        $employeeTotals = $confirmations->groupBy('confirmed_by')->map->count();

        // Prepare report data: only project members
        $reportData = collect();
        
        // Add project members
        foreach ($employees as $employee) {
            if ($employee->user) {
                $reportData->push([
                    'name' => $employee->user->name,
                    'employee' => $employee,
                    'user' => $employee->user,
                    'total' => $employeeTotals[$employee->id] ?? 0,
                ]);
            }
        }

        // Add admin users only if they have confirmations and no project is selected
        if (!$request->filled('project_id')) {
            $adminConfirmations = $confirmations->whereNull('confirmed_by')->count();
            if ($adminConfirmations > 0) {
                $adminUsers = User::role(['admin', 'director'])->get();
                foreach ($adminUsers as $user) {
                    if (!$employees->contains('user_id', $user->id)) {
                        $reportData->push([
                            'name' => $user->name,
                            'employee' => null,
                            'user' => $user,
                            'total' => $adminConfirmations,
                        ]);
                    }
                }
            }
        }

        return view('order-confirmations.users-report', [
            'reportData' => $reportData,
            'projects' => $projects,
            'filters' => $request->only(['project_id', 'date_from', 'date_to', 'month', 'year'])
        ]);
    }
} 
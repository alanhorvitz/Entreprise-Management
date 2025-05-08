<?php

namespace App\Livewire\Reports;

use App\Models\DailyReport;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class DeleteReportModal extends Component
{
    public $reportId;
    public $report;

    public function mount($reportId)
    {
        $this->reportId = $reportId;
        $this->report = DailyReport::with('user')->find($reportId);
    }

    public function delete()
    {
        if (!$this->report) {
            $this->dispatch('notify', [
                'message' => 'Report not found.',
                'type' => 'error',
            ]);
            return;
        }

        $user = Auth::user();
        $canDelete = false;

        // Directors can delete any report
        if ($user->hasRole('director')) {
            $canDelete = true;
        }
        // Supervisors can delete reports from their supervised projects
        else if ($user->hasRole('supervisor')) {
            $canDelete = $this->report->project && $this->report->project->supervised_by === $user->id;
        }
        // Users can delete their own reports
        else {
            $canDelete = $user->id === $this->report->user_id;
        }

        if (!$canDelete) {
            $this->dispatch('notify', [
                'message' => 'You are not authorized to delete this report.',
                'type' => 'error',
            ]);
            return;
        }

        $this->report->delete();
        
        $this->dispatch('reportDeleted');
        $this->dispatch('closeDeleteModal');
        $this->dispatch('notify', [
            'message' => 'Report deleted successfully!',
            'type' => 'success',
        ]);
    }

    public function close()
    {
        $this->dispatch('closeDeleteModal');
    }

    public function render()
    {
        return view('livewire.reports.delete-report-modal');
    }
} 
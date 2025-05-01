<?php

namespace App\Livewire\Reports;

use App\Models\DailyReport;
use Livewire\Component;

class DeleteReportModal extends Component
{
    public $reportId;
    public $report;

    public function mount($reportId)
    {
        $this->reportId = $reportId;
        $this->report = DailyReport::with(['user', 'reportTasks.task'])->find($reportId);
    }

    public function delete()
    {
        if ($this->report) {
            // Delete all related report tasks first
            $this->report->reportTasks()->delete();
            
            // Then delete the report
            $this->report->delete();
            
            $this->dispatch('reportDeleted');
            $this->dispatch('closeDeleteModal');
            $this->dispatch('notify', [
                'message' => 'Report deleted successfully!',
                'type' => 'success',
            ]);
        }
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
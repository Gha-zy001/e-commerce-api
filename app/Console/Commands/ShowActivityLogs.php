<?php

namespace App\Console\Commands;

use App\Models\Activity;
use Illuminate\Console\Command;

class ShowActivityLogs extends Command
{
    protected $signature = 'activity:show 
        {--limit=10 : Number of logs to show} 
        {--subject= : Filter by subject type (e.g., App\Models\Catalog\Product)} 
        {--causer= : Filter by causer type (e.g., App\Models\Auth\Admin)} 
        {--event= : Filter by event type (created, updated, deleted, etc.)} 
        {--days= : Only show logs from the last N days} 
        {--json : Output as JSON}';

    protected $description = 'Display activity logs';

    public function handle(): int
    {
        $query = Activity::query()->with(['causer', 'subject']);

        // Apply filters
        if ($this->option('subject')) {
            $query->where('subject_type', $this->option('subject'));
        }

        if ($this->option('causer')) {
            $query->where('causer_type', $this->option('causer'));
        }

        if ($this->option('event')) {
            $query->where('event', $this->option('event'));
        }

        if ($this->option('days')) {
            $query->where('created_at', '>', now()->subDays($this->option('days')));
        }

        $logs = $query->latest()->take($this->option('limit'))->get();

        if ($this->option('json')) {
            $this->output->writeln(json_encode($logs->toArray(), JSON_PRETTY_PRINT));

            return self::SUCCESS;
        }

        if ($logs->isEmpty()) {
            $this->info('No activity logs found.');

            return self::SUCCESS;
        }

        $this->table(
            ['ID', 'Time', 'Event', 'Description', 'User', 'Subject'],
            $logs->map(function ($log) {
                return [
                    substr($log->id, 0, 8),
                    $log->created_at->format('Y-m-d H:i:s'),
                    $log->event,
                    $log->description,
                    $log->causer?->name ?? ($log->causer?->email ?? 'System'),
                    $log->subject?->name ?? class_basename($log->subject_type).' #'.substr($log->subject_id, 0, 8),
                ];
            })
        );

        return self::SUCCESS;
    }
}

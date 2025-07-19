<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'priority',
        'project_id'
    ];

    protected $casts = [
        'priority' => 'integer'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public static function getNextPriority($projectId = null)
    {
        $query = self::query();

        if ($projectId) {
            $query->where('project_id', $projectId);
        }

        return $query->max('priority') + 1;
    }

    public static function reorderTasks($taskIds, $projectId = null)
    {
        foreach ($taskIds as $index => $taskId) {
            $query = self::where('id', $taskId);

            if ($projectId) {
                $query->where('project_id', $projectId);
            }

            $query->update(['priority' => $index + 1]);
        }
    }
}

<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Schedule;
use App\Models\ClassModel;
use App\Models\ClassGroup;

echo "=== SCHEDULES WITH CLASS + GROUP ===\n";
$schedules = Schedule::with('classModel.group')->get();
foreach ($schedules as $s) {
    $cm = $s->classModel;
    $groupName = $cm && $cm->group ? $cm->group->name : 'NO GROUP';
    $cgi = $cm ? ($cm->class_group_id ?? 'null') : 'null';
    echo "Schedule #{$s->id}: {$s->schedule_label} | Class: " . ($cm->class_name ?? 'null') . " | class_group_id: {$cgi} | Group: {$groupName}\n";
}

echo "\n=== CLASS MODELS ===\n";
$all = ClassModel::with('group')->get();
foreach ($all as $c) {
    $groupName = $c->group ? $c->group->name : 'NO GROUP';
    echo "Class #{$c->id}: {$c->class_name} | class_group_id: " . ($c->class_group_id ?? 'null') . " | Group: {$groupName}\n";
}

echo "\n=== CLASS GROUPS ===\n";
$groups = ClassGroup::all();
foreach ($groups as $g) {
    echo "Group #{$g->id}: {$g->name} ({$g->level})\n";
}

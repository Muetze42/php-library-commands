<?php

namespace NormanHuth\Library\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\SoftDeletes;
use NormanHuth\Library\ClassFinder;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'app:cleanup:soft-deletes')]
class CleanupSoftDeletesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:cleanup:soft-deletes {--paths=app/Models} {--hours=720}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete finally soft deletes Model instances older than X hours';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $hours = (int) $this->option('hours');

        $models = collect(ClassFinder::load(
            paths: explode(',', $this->option('paths')),
            classUses: SoftDeletes::class
        ))->each(
            fn ($model) => $model::onlyTrashed()->where('deleted_at', '<=', now()->subHours($hours))->forceDelete()
        );

        $this->components->info(
            sprintf('Finished cleanup soft deletes for %d models', $models->count())
        );
    }
}

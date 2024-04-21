<?php

namespace NormanHuth\Library\Commands\Development;

use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'helper')]
class IdeHelperCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'helper';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a new IDE Helper file and autocompletion for models and metadata for PhpStorm';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        if (config('app.env') !== 'local') {
            $this->components->error('This command is only for local development environment');

            return;
        }

        $this->call(
            'ide-helper:models',
            [
                '--write' => false,
                '--nowrite' => true,
            ]
        );
        $this->call('ide-helper:generate');
        $this->call('ide-helper:meta');

        if (class_exists('Tutorigo\LaravelMacroHelper\IdeMacrosServiceProvider')) {
            $this->call('ide-helper:macros');
        }
    }
}

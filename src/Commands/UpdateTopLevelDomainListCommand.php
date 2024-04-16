<?php

namespace NormanHuth\Library\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'app:update-top-level-domain-list')]
class UpdateTopLevelDomainListCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-top-level-domain-list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Top-Level Domains list';

    /**
     * The source of the data.
     */
    protected string $source = 'https://data.iana.org/TLD/tlds-alpha-by-domain.txt';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        Storage::put(
            'top-level-domains.txt',
            Http::get($this->source)->body()
        );

        $this->components->info('Successfully updated Top-Level Domains list');
    }
}

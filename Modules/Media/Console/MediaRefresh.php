<?php

namespace Modules\Media\Console;

use Modules\Media\Repositories\MediaRepository;
use Modules\Media\Jobs\RebuildThumbnails;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;

class MediaRefresh extends Command
{
    use DispatchesJobs;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'media:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create and or refresh the thumbnails';

    /**
     * @var MediaRepository
     */
    private $file;

    public function __construct(MediaRepository $file)
    {
        parent::__construct();
        $this->file = $file;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->line('Preparing to regenerate all thumbnails...');

        $this->dispatch(new RebuildThumbnails($this->file->allForGrid()->pluck('path')));

        $this->info('All thumbnails refreshed');
    }
}



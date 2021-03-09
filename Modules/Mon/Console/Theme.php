<?php

namespace Modules\Mon\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class Theme extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'theme:publish {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish the assets of the theme';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name = $this->argument('name');
        $assetsPath = base_path("themes/$name/assets");
        $public = public_path("themes/$name");
        $themeInfo = \Modules\Mon\Support\Facades\Theme::info($name);
        if (File::exists($assetsPath)) {
            File::copyDirectory($assetsPath, $public);
            $this->info(ucwords("{$themeInfo->name} was published!"));
        }
    }
}

<?php namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Console\Commands\I18n\Generator;
use Illuminate\Support\Facades\Config;
use Symfony\Component\Console\Input\InputArgument;

class GenerateI18NTranslation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vue-i18n:generate {theme}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Generates a vue-i18n|vuex-i18n compatible js array out of project translations";

    /**
     * Execute the console command.
     * @return mixed
     * @throws \Exception
     */
    public function handle()
    {
        $root = base_path() . config('vue-i18n-generator.langPath');
        $config = config('vue-i18n-generator');

        // options
        $themeName = $this->argument('theme');

        if ($themeName) {
            //themes/guest/resources/lang
            $langPath = '/themes/' . $themeName . '/resources/lang';
            $jsPath = '/themes/' . $themeName . '/resources/js/lang';
            $jsFile = '/themes/' . $themeName . '/resources/js/vue-i18n-locales.generated.js';
            $configs = array_merge(config('vue-i18n-generator'), compact('langPath', 'jsPath', 'jsFile'));
            Config::set('vue-i18n-generator', $configs);

        }


        $data = (new Generator($config))
            ->generateFromPath($root);


        $jsFile = $this->getFileName($jsFile);
        file_put_contents($jsFile, $data);

        if ($config['showOutputMessages']) {
            $this->info("Written to : " . $jsFile);
        }
    }

    /**
     * @param string $fileNameOption
     * @return string
     */
    private function getFileName($fileNameOption)
    {
        if (isset($fileNameOption)) {
            return base_path() . $fileNameOption;
        }

        return base_path() . config('vue-i18n-generator.jsFile');
    }

    protected function getArguments()
    {
        return [
            ['theme', InputArgument::REQUIRED, 'Tên theme là bắt buộc'],
        ];
    }
}

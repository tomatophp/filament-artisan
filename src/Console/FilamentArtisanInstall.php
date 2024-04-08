<?php

namespace TomatoPHP\FilamentArtisan\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use TomatoPHP\ConsoleHelpers\Traits\RunCommand;

class FilamentArtisanInstall extends Command
{
    use RunCommand;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'filament-artisan:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'install package and publish assets';

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
        $this->info('Publish Vendor Assets');
        if(!File::exists(public_path('js/gui.js'))){
            File::copyDirectory(__DIR__ . '/../../publish', public_path());
        }
        $this->artisanCommand(["optimize:clear"]);
        $this->info('Filament Artisan installed successfully.');
    }
}

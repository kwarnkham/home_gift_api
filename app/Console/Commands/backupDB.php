<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class backupDB extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backupDB';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup the database to the spaces';

    /**
     * Create a new command instance.
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
        $filename = 'home_gift_'.Carbon::now()->format('Y-m-d H:i:s').'.gz';
        $filename = str_replace(' ', '_', $filename);
        $filename = str_replace(':', '-', $filename);
        $command = 'mysqldump --user='.env('DB_USERNAME').' --password='.env('DB_PASSWORD').' --host='.env('DB_HOST').' '.env('DB_DATABASE').'  | gzip > '.storage_path().'/app/backup/'.$filename;
        exec($command);
        $files = Storage::files('backup');
        if (count($files) > 0) {
            Storage::disk('spaces')->putFileAs('db_backup', storage_path().'/app/'.$files[0], $filename);
            Storage::delete($files[0]);
        }
        $spacesFiles = Storage::disk('spaces')->files('db_backup');
        if (count($spacesFiles) > 3) {
            Storage::disk('spaces')->delete($spacesFiles[0]);
        }
    }
}

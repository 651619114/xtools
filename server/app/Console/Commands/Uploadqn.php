<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\Cloud;
use App\Libraries\QNCloud;
use Illuminate\Support\Facades\Storage;
use PDO;

class Uploadqn extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'uploadqn';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return int
     */
    public function handle()
    {
        $qn = new QNCloud();

        //拉取需要同步的文件信息
        $info = Cloud::where("is_sync", 1)->get();
        foreach ($info as $key => $value) {
            if (Storage::exists($value->file_name)) {
                $res = $qn->upload(Storage::path($value->file_name), $value->real_name);
                if ($res['res']) {
                    $res1 = $qn->download($value->real_name);
                    if (!empty($res1)) {
                        Cloud::where('id', $value->id)->update(['remote_path' => $res1, 'is_sync' => 2]);
                    } else {
                        //邮件
                    }
                } else {
                    //邮件
                }
            }
        }

        return 1;
    }
}

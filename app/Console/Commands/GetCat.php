<?php

namespace App\Console\Commands;

use App\Helpers\Common;
use App\Models\Image;
use App\Models\ImGroupMessage;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;
use Psy\Util\Json;

class GetCat extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:cat
                            {start=0 : 起始页} 
                            {pages=3 : 页数} {limit=20 : 大小} {--download}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '获取猫的图片';

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
        //访问参数值
//        $arguments = $this->arguments(); //数组形式返回
//        $option = $this->options(); //选项
//
//        $name = $this->ask(" 你是？");
//        echo $name . PHP_EOL;
//        $password = $this->secret("尼玛");
//
//        if ($this->confirm('Do you wish to continue? [y|N]')) {
//            echo "执行了";
//        }
//
//        $this->info("info info info ");
//
//        $headers = ['g', 'msg'];
//        $groupMessage = ImGroupMessage::all(['group_id', 'content'])->toArray();
//
//        $this->table($headers, $groupMessage);
//
//        $bar = $this->output->createProgressBar(count($groupMessage));
//        foreach ($groupMessage as $m) {
//            sleep(2);
//
//            $bar->advance();
//        }
//        $bar->finish();
//        $this->info(PHP_EOL);
//        $this->info("ffffffff");

//         $value = Redis::lrange("GROUP_MESSAGE_1", 0,-1);
//         var_dump($value);
//         $this->info("得到一个");
//
//         $pop = Redis::RPOP("GROUP_MESSAGE_1");
//
//         var_dump($pop);
//         var_dump(Redis::lrange("GROUP_MESSAGE_1", 0,-1));
//        exit;
        $arguments = $this->arguments();

        $url = "https://api.thecatapi.com/v1/images/search?limit=%s&page=%s&order=Desc";
        $start = $this->argument("start");
        $pages = $this->argument("pages");
        $limit = $this->argument("limit");

        $prefix = "chongliao/";
        $path = date('Y/m/d/H');
        $successCount = 0;
        $failCount = 0;
        for ($i = 0; $i < $pages; $i++) {
            $api = sprintf($url, $limit, (int)$start + $i);
            $items = Common::curl($api);
            foreach ($items as $item) {
                $ext = pathinfo($item['url'], PATHINFO_EXTENSION);
                $name = "cat-" . $item['id'] . "." . $ext;
                $fullPath = $prefix . $path . "/" . $name;
                $status = \Storage::put($fullPath, file_get_contents($item['url']));
                if ($status) {
                    $params = [
                        'category_id' => 1,
                        'title' => $name,
                        'path' => $fullPath,
                        'is_deleted' => 0,
//                    'source_url' => ""
                    ];
                    $model = new Image($params);
                    if ($model->validate($params)) {
                        if (!$model->save()) {
                            throw new \RuntimeException("保存插入失败");
                        }
                        $successCount++;
                    } else {
                        $message = $model->errors[0] ?? "未知错误";
                        echo $message;
                        $failCount++;
                    }
                }
            }
        }

        echo sprintf("本次总共请求%d页，成功下载%d张图片,失败次数为%d" . PHP_EOL, $pages, $successCount, $failCount);
    }
}

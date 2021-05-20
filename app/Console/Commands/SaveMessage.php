<?php

namespace App\Console\Commands;

use App\Models\ImGroupMessage;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;
use Psy\Util\Json;

class SaveMessage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'save:message 
                            {group=0 : 群组id} {--echo}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '保存redis里面的聊天消息';

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

        $redis = app('redis.connection');
//        $value = $redis->lrange("GROUP_MESSAGE_1", 0, -1);
//        var_dump($value);
        while (true) {
            $data = json_decode($redis->rpop("GROUP_MESSAGE_1"), true);
            if($data) {
                try {
                    $this->info("存入消息");
                    var_dump($data);
                    $model = new ImGroupMessage();
                    $model->group_id = $data["group_id"];
                    $model->user_id = $data['user_id'] ?? 0;
                    $model->content = $data['message'];
                    $model->type = "group";
                    $model->status = 1;
                    $model->is_deleted = 0;
                    if($model->save()) {
                        $this->info($model->id . PHP_EOL);
                    }else{
                        var_dump($model->errors);
                    }
                }catch (\RuntimeException $e) {
                    echo $e->getMessage();
                }

            }
        }
    }
}

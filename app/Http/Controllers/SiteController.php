<?php

namespace App\Http\Controllers;

use App\Helpers\Common;
use App\Helpers\OSS;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Customer;
use App\Models\UserInfo;
use GuzzleHttp\Client;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class SiteController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */


    /**
     * Where to redirect users after login.
     *
     * @var string
     */
//    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
//    public function __construct()
//    {
//        $this->middleware('guest')->except('logout');
//    }

    public function option()
    {
        \Log::info("nidfsaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaahfasdf;kasjfjsadfjasfjdsafjsafdjsfsfdasdfasfdasdkfj;llllllllllllllllllllllmmm");
        echo date("H:i;s") . "hello world";exit;
    }

    public function test()
    {
        $reader           = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx'); //实例化阅读器对象。
        $spreadsheet      = $reader->load("./test.xlsx");  //将文件读取到到$spreadsheet对象中
        $sheet            = $spreadsheet->getSheet(0);//sheet
        $highestColumn    = $sheet->getHighestColumn(); // 取得总列数
        $highestRow       = $sheet->getHighestRow(); // 取得总行数
        var_dump($highestColumn);
        var_dump($highestRow);
        // 行迭代器初始化
        $endRow = 623;
        $headerCount = 1;
        $rowIterator = $sheet->getRowIterator( 1 + $headerCount, $endRow + $headerCount);

        $customers = [];
        foreach ($rowIterator as $key => $row) {
            $cellIterator = $row->getCellIterator();
            $row = [];
            foreach ($cellIterator as $cell) {
                /* @var $cell \PhpOffice\PhpSpreadsheet\Cell\Cell */
                $row[$cell->getColumn()] = trim($cell->getValue());
            }

            // key 为键(行号)
//            $row['key'] = $key;
            $customers[] = $row;
        }

        foreach ($customers as $c){
            $model = new Customer();
            $model->name = $c['B'];
            $model->gender = $c['C'] == '男' ? 1 : ($c['C'] == '女' ? 2: 0);
            $model->phone = $c['E'];
            $model->birthday = date('Y-m-d H:i:s',strtotime(substr($c['F'],6,8 )));
            $model->note = $c['F'];
            if(empty($c['B'])) {
                var_dump($c);
                continue;
            }
            $model->save();
        }

        exit;
        var_dump(getenv('APP_ENV'));
        EXIT;
        echo "start";
        $result = OSS::publicUpload("chongliao", "t/test.jpg", "./test.jpg", [
            'ContentType' => "image/jpeg"
        ]);
        var_dump($result);
        $eTag = 'AD8BE102A8E6B62A7E7C29B5E83153E0';

        $img = OSS::getPrivateObjectURLWithExpireTime("chongliao", "test.jpg", new \DateTime("3600"));
        var_dump($img, 'https://chongliao.oss-cn-hangzhou.aliyuncs.com/test.jpg');
        exit;

//        \Log::info("nimmm");
//        $redis = app('redis.connection');
//        $onlineCount = $redis->smembers("ONLINE_COUNT");
//        var_dump(count($onlineCount));
//        exit;


//        $categories =  Category::all();
//        foreach ($categories as $category) {
//            echo $category->name;
//        }
       $url = 'https://api.weixin.qq.com/sns/jscode2session';
//       $client = new Client();
//        $response = $client->request('GET', $url, ['query' => [
//            'appid' => 'wxc0b949e81c79d847',
//            'secret' => 'eb6e87629fcdadd2328569dd42d236e2',
//            'grant_type' => 'authorization_code',
//            'js_code' => '081DlOSo0kzCUk1fmeUo06WRSo0DlOSm'
//        ]]);
//
//
//// url will be: http://my.domain.com/test.php?key1=5&key2=ABC;
//
//        $statusCode = $response->getStatusCode();
//        $content = $response->getBody();
//        $query = [
//            'appid' => 'wxc0b949e81c79d847',
//            'secret' => 'eb6e87629fcdadd2328569dd42d236e2',
//            'grant_type' => 'authorization_code',
//            'js_code' => '081DlOSo0kzCUk1fmeUo06WRSo0DlOSm'
//        ];
//
//        $result = Common::curl($url,'GET', http_build_query($query));
//        var_dump($result);
//        exit;
        $userModel = UserInfo::query()->where('id', 100000000000)->first();

        var_dump($userModel);
        exit;


    }
}

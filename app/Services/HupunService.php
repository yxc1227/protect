<?php


namespace App\Services;


use App\Model\HupunTradesRawModel;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use phpDocumentor\Reflection\Types\Integer;
use Throwable;

class HupunService
{

    /**
     * 万里牛接口基础连接
     * @param string $uri
     * @param array $customParams
     * @param string $time
     * @return string
     */
    public function connectiong2hupun($uri = '', $customParams = [], $time = ''): string
    {
        if(!$time){
            $time = time();
        }
        $client = new Client([
            'base_uri' => config('app.HUPUN_BASIC_URI'),
        ]);
        $systemParams = [
            '_app' => config('app.HUPUN_KEY'),
            '_s' => '',
            '_t' => $time,
        ];

        $data = array_merge($systemParams, $customParams);
        $data['_sign'] = $this->createSign($data);
        try {
            $response = $client->request('POST', $uri, ['form_params' => $data]);
            return (String) $response->getBody();
        } catch (Throwable $e) {
            $message = $e->getMessage();
            Log::channel('hupun')->error($message,['uri'=>$uri,'data'=>$data]);
            return '';
        }
    }

    /**
     * 生成万里牛约定的签名
     * @param array $data
     * @return string
     */
    public function createSign($data = []): ?string
    {
        if (empty($data)) {
            return '';
        }
        $sign = config('app.HUPUN_SECRET');
        ksort($data, SORT_STRING);
        $flag = true;
        foreach ($data as $k => $v) {
            if ($flag) {
                $sign .= $k . "=" . urlencode($v);
                $flag = false;
            } else {
                $sign .= "&" . $k . "=" . urlencode($v);
            }
        }
        $sign .= config('app.HUPUN_SECRET');
        return md5($sign, FALSE);
    }


    /**
     * 获取并保存原始请求结果
     * @return int
     */
    public function getHupunTrades():Integer
    {
        $now = time();
        $res = HupunTradesRawModel::select('timestamp')->orderby('timestamp','desc')->first();
        if($res){
            $time = $res->timestamp;
        } else {
            //万里牛约定仅支持最近三个月的数据，但是实际发现有漏洞可以多拉几个月
            $time= strtotime(date("Y-m-d H:i:s", strtotime("-24 month")));
        }
        $uri = '/api/erp/opentrade/list/trades';
        //业务请求参数，大部分都没啥用
        $customParams = [
//            'bill_code'=>'P2020083100002367957',
            'create_time'=> $time * 1000,
//            'modify_time'=>'',
//            'send_goods_time'=>'',
//            'finish_time'=>'',
//            'end_time'=>'',
//            'pay_time'=>'',
//            'storage_code'=>'',
            'page' => 1,
            'limit' => 200,
//            'is_split'=>false,
//            'receiever_name'=>false,
//            'receiever_name' => '',
//            'trade_status' => '',
        ];
        $flag = true;
        while ($flag){
            $result = $this->connectiong2hupun($uri,$customParams,$now);
            if(!$result){
                break;
                return 0;
            }
            $content = json_decode($result,true);
            if($content['code'] != 0 || empty($content['data'])){
                $flag = false;
            }
            if(!empty($content['data'])){
                HupunTradesRawModel::create(['timestamp'=>$now,'raw'=>json_encode($content['data'])]);
            }
            $customParams['page'] ++;
        }
        return $customParams['page'];

    }
}

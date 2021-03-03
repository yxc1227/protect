<?php


namespace App\Services;


use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
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
        } finally {
            return '';
        }
    }

    /**
     * 生成万里牛约定的签名
     * @param array $data
     * @return string|null
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


    public function getHupunTrades()
    {
        $now = time();
        $uri = '/api/erp/opentrade/list/trades';
        $customParams = [
//            'bill_code'=>'P2020083100002367957',
//            'create_time'=>$now,
//            'modify_time'=>'',
//            'send_goods_time'=>'',
//            'finish_time'=>'',
//            'end_time'=>'',
//            'pay_time'=>'',
//            'storage_code'=>'',
            'page' => 1,
            'limit' => 1,
//            'is_split'=>false,
//            "receiever_name" => '',
//            "trade_status" => '',
        ];
        $flag = true;

        while ($flag == true){
            $result = $this->connectiong2hupun($uri,$customParams,$now);
            if(!$result){
                break;
            }
            $content = json_decode($result,true);
            if($content['code'] != 0 || empty($content['data'])){
                $flag = false;
            }
            $data = $content['data'];

        }

    }
}

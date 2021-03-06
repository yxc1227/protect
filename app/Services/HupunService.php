<?php


namespace App\Services;


use App\Model\HupunTradesInvoiceModel;
use App\Model\HupunTradesModel;
use App\Model\HupunTradesOrdersModel;
use App\Model\HupunTradesRawModel;
use GuzzleHttp\Client;
use Illuminate\Support\Arr;
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
        if (!$time) {
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
            return (string)$response->getBody();
        } catch (Throwable $e) {
            $message = $e->getMessage();
            Log::channel('hupun_error')->error($message, ['uri' => $uri, 'data' => $data]);
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
    public function getHupunTrades(): int
    {
        $now = time();
        $res = HupunTradesRawModel::select('timestamp')->orderby('timestamp', 'desc')->first();
        if ($res) {
            $time = $res->timestamp;
        } else {
            //万里牛约定仅支持最近三个月的数据，但是实际发现有漏洞可以多拉几个月
            $time = strtotime(date("Y-m-d H:i:s", strtotime("-24 month")));
        }
        $uri = '/api/erp/opentrade/list/trades';
        //业务请求参数，大部分都没啥用
        $customParams = [
//            'bill_code'=>'P2020083100002367957',
            'create_time' => $time * 1000,
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
        while ($flag) {
            $result = $this->connectiong2hupun($uri, $customParams, $now);
            if (!$result) {
                break;
                return 0;
            }
            $content = json_decode($result, true);
            if ($content['code'] != 0 || empty($content['data'])) {
                $flag = false;
            }
            if (!empty($content['data'])) {
                HupunTradesRawModel::create(['timestamp' => $now, 'raw' => json_encode($content['data'])]);
            }
            $customParams['page']++;
        }
        return $now;

    }


    /**
     *解析接口请求到的原始数据
     */
    public function resolveHupunTradesRaw(): void
    {
        $raws = HupunTradesRawModel::where('resolved','=',0)->get();
        if (!$raws) {
            return;
        }
        $raws->toArray();
        try {
            foreach ($raws as $raw) {
                $rawId = Arr::get($raw, 'id');
                $info = Arr::get($raw, 'raw');
                $trades = json_decode($info, true);
                foreach ($trades as $trade) {
                    $trade['rawid'] = $rawId;

                    //json扩展字段做特殊处理防止格式错误
                    $oln_order_list = Arr::get($trade, 'oln_order_list');
                    $trade['oln_order_list'] = json_encode($oln_order_list);
                    $trade_extend = Arr::get($trade, 'trade_extend');
                    $trade['trade_extend'] = json_encode($trade_extend);

                    //两个通过关系关联的对象要提前摘出
                    $invoice = Arr::get($trade, 'invoice');
                    unset($trade['invoice']);
                    $orders = Arr::get($trade, 'orders');
                    unset($trade['orders']);

                    //13位时间戳需要特殊处理
                    Arr::get($trade, 'create_time') ? $trade['create_time'] = date("Y-m-d H:i:s", $trade['create_time'] / 1000) : null;
                    Arr::get($trade, 'modify_time') ? $trade['modify_time'] = date("Y-m-d H:i:s", $trade['modify_time'] / 1000) : null;
                    Arr::get($trade, 'pay_time') ? $trade['pay_time'] = date("Y-m-d H:i:s", $trade['pay_time'] / 1000) : null;
                    Arr::get($trade, 'end_time') ? $trade['end_time'] = date("Y-m-d H:i:s", $trade['end_time'] / 1000) : null;
                    Arr::get($trade, 'print_time') ? $trade['print_time'] = date("Y-m-d H:i:s", $trade['print_time'] / 1000) : null;
                    Arr::get($trade, 'send_time') ? $trade['send_time'] = date("Y-m-d H:i:s", $trade['send_time'] / 1000) : null;

                    $trade_record = new HupunTradesModel();
                    $trade_record->timestamps = false;
                    $trade_record->forceFill($trade)->save();
                    $tradeId = $trade_record->id;
                    foreach ($orders as $order) {
                        $order['trades_id'] = $tradeId;
                        $order_record = new HupunTradesOrdersModel();
                        $order_record->timestamps = false;
                        $order_record->forceFill($order)->save();
                    }
                    if ($invoice) {
                        $invoice['trades_id'] = $tradeId;
                        $invoice_record = new HupunTradesInvoiceModel();
                        $invoice_record->timestamps = false;
                        $invoice_record->forceFill($invoice)->save();
                    }
                }
                $raw->resolved = 1;
                $raw->save();
            }
        } catch (Throwable $e) {
            $message = $e->getMessage();
            Log::channel('hupun_error')->error($message, ['trade' => $trade, 'order' => $order]);
        }
    }
}

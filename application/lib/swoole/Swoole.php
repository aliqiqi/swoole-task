<?php
namespace app\lib\swoole;

use think\swoole\Server;
use app\lib\swoole\Task;
use think\facade\Config;
class Swoole extends Server
{
    protected $host;
    protected $port;
    protected $serverType;
    protected $option;

    public function __construct()
    {
        $this->serverType = Config::get('swoole_server.type');
        $this->host = Config::get('swoole_server.host');
        $this->port = Config::get('swoole_server.port');
        $this->option = Config::get('swoole_server.option');
        parent::__construct();


    }


    //接收发送的消息
    public function onReceive($server, $fd, $from_id, $data)
    {
        $server->task($data);
    }
    public function onTask($server, $task_id, $reactor_id, $data)
    {
        //处理
        $data = json_decode($data,true);
        $class = $data['class'];
        $method = $data['method'];
        $flag = (new $class())->$method($data['data']);
        return $flag;
    }
    public function onFinish($server, $task_id, $data)
    {
        //处理完成返回值
        print_r($data);
    }
}

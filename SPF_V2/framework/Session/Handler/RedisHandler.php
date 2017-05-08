<?php
/**
 * RedisHandler
 *
 * @package SPF.Session.Handler
 * @author  XiaodongPan
 * @version $Id: RedisHandler.php 2017-04-12 $
 */
namespace SPF\Session\Handler;

use SPF\Cache\Redis;

class RedisHandler implements HandlerInterface
{
    /**
     * @var Redis
     */
    private $redis;

    private $lifetime = 1440;

    public function __construct(Redis $redis)
    {
        $this->redis = $redis;
        $this->lifetime = (int)ini_get('session.gc_maxlifetime');
    }

    public function open($save_path, $name)
    {
        return true;
    }

    public function close()
    {
        return true;
    }

    public function read($id)
    {
        return $this->redis->get($id);
    }

    public function write($id, $data)
    {
        $this->redis->set($id, $data, $this->lifetime);
        return true;
    }

    public function destroy($id)
    {
        $this->redis->delete($id);
        return true;
    }

    public function gc($maxlifetime)
    {
        return true;
    }
}
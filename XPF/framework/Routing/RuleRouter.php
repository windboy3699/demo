<?php
/**
 * 固定规则Url的路由解析
 *
 * @package SPF.Routing
 * @author  XiaodongPan
 * @version $Id: RuleRouter.php 2017-04-18 $
 */
namespace SPF\Routing;

class RuleRouter extends Router
{
    const DEFAULT_CONTROLLER = 'Index';
    const DEFAULT_ACTION = 'index';

    /**
     * path按/controller/action解析
     *
     * @return bool
     */
    public function parse()
    {
        $path = $this->getPath();

        if (empty($path)) {
            $this->controller = $this->buildController([self::DEFAULT_CONTROLLER]);
            $this->action = self::DEFAULT_ACTION . 'Action';
            if (method_exists($this->controller, $this->action)) {
                return true;
            }
            $this->notFound();
        }

        $pathArray = explode('/', $path);
        $count = count($pathArray);

        if ($count == 1) {
            $pathSegs[0] = ucfirst($pathArray[0]);
            $this->controller = $this->buildController($pathSegs);
            $this->action = self::DEFAULT_ACTION . 'Action';
            if (method_exists($this->controller, $this->action)) {
                return true;
            }
            $this->notFound();
        }

        $this->action = $pathArray[$count - 1] . 'Action';
        $controllerArray = array_slice($pathArray, 0, -1);
        foreach ($controllerArray as $key => $val) {
            $controllerArray[$key] = ucfirst($val);
        }
        $this->controller = $this->buildController($controllerArray);

        if (method_exists($this->controller, $this->action)) {
            return true;
        }
        foreach ($pathArray as $key => $val) {
            $pathSegs[$key] = ucfirst($val);
        }
        $this->controller = $this->buildController($pathSegs);
        $this->action = self::DEFAULT_ACTION . 'Action';
        if (method_exists($this->controller, $this->action)) {
            return true;
        }

        $this->notFound();
    }

    /**
     * build controller
     *
     * @param array $segs
     * @return string
     */
    public function buildController(array $segs)
    {
        return 'App\\Controller\\' . implode($segs, '\\') . 'Controller';
    }
}
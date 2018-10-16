<?php

declare(strict_types = 1);
namespace Xutengx\Route\Component;

/**
 * 成功匹配的路由对象
 * Class MatchedRouting
 * @package Xutengx\Route\Component
 */
class MatchedRouting {

	/**
	 * 此路由可接受的http方法
	 * @var array
	 */
	public $methods;
	/**
	 * 路由中间件组
	 * @var array
	 */
	public $middlewareGroups;
	/**
	 * 路由别名
	 * @var string
	 */
	public $alias;
	/**
	 * 主体方法
	 * @var string|\Closure
	 */
	public $subjectMethod;
	/**
	 * 域名参数
	 * @var array
	 */
	public $domainParameters;
	/**
	 * 静态参数(pathInfo参数)
	 * @var array
	 */
	public $staticParameters;
	/**
	 * 路由参数(array_merge($domainParam, $staticParamter))
	 * @var
	 */
	public $urlParameters;
	/**
	 * 具体的将要执行的每个中间件
	 * @var array
	 */
	public $middleware;

}

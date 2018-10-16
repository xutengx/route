<?php

use Xutengx\Route\Route;

//Route::set404('App\Dev\mysql\Contr\index2Contr@indexDo');
Route::set404(function($pathinfo){
	return obj(Response::class)->setStatus(404)->setContent('Not Found .. ' . $pathinfo)->sendExit();
});

return [
	'/wapSub/dtac' => 'App\bluepay\subscribe\dtac@index',
	'/phpinfo' => function() {
		phpinfo();exit;
	},
	Route::get('/', function() {
		return obj(\Response::class)->setContentType('html')->setContent('hello world');
//		return \Response::setContentType('html')->setContent('hello world');
//		return obj(\Gaara\Core\Response::class)->setContentType('html')->setContent('hello world');
	}),
	Route::get('/data/upload/{yearmonth}/{day}/{name}', function($yearmonth, $day, $name) {
		$filename = ROOT . "data/upload/$yearmonth/$day/$name";
		if (is_file($filename))
			return \Response::setContentType(substr(strrchr($name, '.'), 1))->setContent(file_get_contents($filename));
		else
			return \Response::setStatus(404)->setContent();
	}),
	// yh
	Route::group(['middleware' => ['web'], 'namespace' => 'App\yh\c'], function() {
		// 接口开发调试页面
		Route::get('/dev', 'Dev\Dev@index');
		// 不检测 token
		Route::group(['prefix' => 'user', 'namespace' => 'user'], function() {
			// 邮箱检测
			Route::get('/email', 'Reg@email');
			// 注册( 邮件发送 )
			Route::post('/reg', ['uses' => 'Reg@index']);
			// 注册 设置密码
			Route::post('/setpasswd', 'Reg@setPasswd');
			// 登入
			Route::post('/login', 'Login@index');
			// 忘记密码( 邮件发送 )
			Route::post('/forget', 'ForgetPasswd@index');
			// 忘记密码 设置密码
			Route::post('/resetpasswd', 'ForgetPasswd@setPasswd');
		});
		// 检测 merchant web登入令牌
		Route::group(['middleware' => ['merchant']], function() {
			// 令牌以旧换新( 重置有效期 )
			Route::post('/user/token', 'user\Login@changeToken');
			// 商户资料
			Route::restful('/merchant', 'merchant\Info');
			// 应用资料
			Route::restful('/application', 'merchant\Application');
			// 商户公私钥资料
			Route::restful('/secret', 'merchant\Secret');
		});
		// 不检测 token
		Route::group(['prefix' => 'admin', 'namespace' => 'admin'], function() {
			// 管理员登入
			Route::post('/login', 'Login@index');
		});
		// 检测 管理员登入令牌
		Route::group(['middleware' => ['admin'], 'prefix' => 'admin'], function() {
			// 令牌以旧换新( 重置有效期 )
			Route::post('/token', 'admin\Login@changeToken');
			// 管理员新增管理员
			Route::post('/reg', 'admin\Reg@index');
			// 管理员设置自己密码
			Route::put('/setpasswd', 'admin\Reg@setPasswd');
			// 管理员 管理商户信息
			Route::restful('/merchant', 'admin\Merchant');
			// 管理员 管理用户 登入/支付, 启用/禁用
			Route::restful('/user', 'admin\User');
			// 管理员 管理通道信息
			Route::restful('/passageway', 'admin\Passageway');
		});

		// 检测 api调用令牌
		Route::group(['middleware' => ['payment'], 'prefix' => 'api'], function() {
			Route::post('/create', 'UnifiedOrderRequest@index');
		});
	}),
	Route::group(['middleware' => ['web', 'api'], 'namespace' => 'App\Dev'], function() {
		// idehelp 页面
		Route::get('/ide', ['namespace' => '', 'uses' => 'development\Contr\idehelp@index']);
		// uuid 页面
		Route::get('/uuid', ['namespace' => '', 'uses' => 'readfile\index@uuid']);
		// readfile 页面
		Route::get('/readfile', ['namespace' => '', 'uses' => 'readfile\index@indexDo']);
		// response 页面
		Route::get('/response', ['namespace' => '', 'uses' => 'response\index@indexDo']);
		// lock 页面
		Route::get('/lock', ['namespace' => '', 'uses' => 'lock\index@indexDo']);
		// ajax 长轮询 页面
		Route::get('/ajax', ['namespace' => '', 'uses' => 'Comet\Contr\ajax@index']);
		// ajax 长轮询 请求
		Route::get('/ajax/do', ['namespace' => '', 'uses' => 'Comet\Contr\ajax@ajaxdo']);
		// ini文件
		Route::get('/ini', ['namespace' => '', 'uses' => 'inifile\index@index']);
		// 数据库测试
		Route::get('/mysql', ['namespace' => '', 'uses' => 'mysql\Contr\index2Contr@indexDo']);
		// 大文件下载
		Route::get('/download', ['namespace' => '', 'uses' => 'download\Contr\index@index']);
		// 大文件下载
		Route::get('/yield', ['namespace' => '', 'uses' => 'yieldtest\index@index']);
		// 数据库测试
		Route::get('/mysql/test', 'mysql\Contr\indexContr@test');
		// 缓存测试
		Route::get('/cache', ['namespace' => '', 'uses' => 'cache\index@indexDo']);
		// 邮件测试 给 emailAddr 发一份邮件
		Route::get('/mail/{emailAddr}', ['middleware' => ['sendMail'], 'uses' => 'mail\index@send']);
		// 视图相关
		Route::get('/view', 'view\index@index');
		Route::any('/view/ajax', 'view\index@getAjax');

		// cookie
		Route::get('/cookie', 'cookie\cookie@index');
		Route::get('/cookie/cookie/cookie', 'cookie\cookie@index');

		// 二维码
		Route::get('/qrcode', 'QRcode\index@index');
		// 验证码
		Route::get('/yzm', 'Yzm\index@index');
		// 共享内存写
		Route::get('/shmop', 'Shmop\index@index');
		// 共享内存读
		Route::get('/shmop/read', 'Shmop\index@read');
		// 共享内存读
		Route::get('/img/{t?}', function(){
			return 'ttttt';
		});

		Route::get('/phpinfo', function(){
			phpinfo();
			exit;
		});

		// 性能对比
		Route::group(['prefix' => 'performance', 'namespace' => 'performance\Contr'], function() {
			Route::get('/index', 'indexContr@indexDo');
			Route::get('/{class}/{func}', function($class, $func) {
				return obj('App\Dev\performance\Contr\\' . $class . 'Contr')->$func();
			});
		});

		Route::get('/c/{num1}/{num2}', 'C\index@indexDo');
		// Xutengx\Excel
		Route::get('/excel', 'excel\index@indexDo');
		// 新共能开发
		Route::get('/new', ['uses' => 'development\Contr\indexContr@indexDo', 'middleware' => ['api']]);

		Route::any('/route1', ['as' => 'tt1', 'uses' => 'development\Contr\indexContr@indexDo']);
	}),
	Route::get('/add/{num-1}/{num2}', function($num1 = 0, $num2){
		return $num1 + $num2;
	}),
	// 管道模式
	Route::get('/p', ['middleware' => ['web', 'testMiddleware'], 'namespace' => 'App\Dev', 'uses' => 'Pipeline\index@index']),
	'/test' => ['as'	 => 'tt1', 'uses'	 => function() {
			redirect('/dev', ['id' => '1232323123'], 'test');
			return 'test';
		}],
	'/404' => ['middleware' => ['web'], 'uses'		 => function() {
//            return Response::setStatus(404)->view(404);
			return false;
		}],
	// 支持隐式路由
	Route::any('/{app}/{contr}/{action}', function ($app, $contr, $action) {
		return run('\App/' . $app . '/Contr/' . $contr . 'Contr', $action);
	}),
	'/hello' => function(\Gaara\Core\Request $request, Gaara\Core\Response $response) {
		$response->setStatus(400);
		return $request->get('name');
	}
];

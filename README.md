## Eventer 3

体验不一样的Eventer！

- 高性能框架
- 高扩展性
- 业务专项优化
- 轻量级应用
- 灵活的资源调配策略
- 个性化定制



### 安装环境:

- PHP: 仅适配PHP8+



### 重构の大改动

1. 网络库 `AsyncTcpConnection` 完全成为异步连接以及处理(在Eventer2中是同步连接异步处理)
2. 更改处理逻辑以提升TPS
3. `Cache` 以及 `CacheNode` 废弃，Eventer3采用以 `Buffer` 类作为 `Cache` 的数据
4. 因 `HTTP` 系网络协议库的特性,暂时迁移至 `package/yimoEx/protocol` 协议库中



### 更新记录
[ChangeLog](CHANGELOG.md)



### 目录详解

##### - [App] 应用类

- app.php (应用入口)

##### - [Public] 公共访问 (IO库指定目录)

##### - [Core] 核心

##### - [Libs] 核心库

 - Base 基础库
 - Network 网络库
 - Protocol 协议库(用于实现协议通讯)

##### - [Package] 包

- protocol: 协议扩展
- base: 基础应用扩展
- network: 网络扩展
- libs: 依赖库



### 快速开始

[查看文档](FUNCTIONS.md)

##### 0. 应用创建

```PHP
namespace Eventer\App;

use Eventer\Core\Event;
use Eventer\Core\Eventer;

class Test {

    public function run(Eventer $eventer){
    	$ev = Event::make('test', function(Event $event){
			$event -> attr['execCount']++;
        	printf("execCount: %d\r", $event -> attr['execCount']);
    	}, 2);
    	$ev -> init(function($event){
        	$event -> attr['execCount'] = 0; //初始化属性为0
    	});
    	$eventer -> register($ev); //2秒执行一次
    }
 
}
```

##### 1. 网络库使用

```PHP
$tcp = new AsyncTcpConnection('127.0.0.1', 5517); //建立一个异步tcp连接
$tcp -> onConnect = function($tcp){
	$tcp -> send('hello world'); //向tcp发送
};
$tcp -> onClose = function($tcp){
	$tcp -> reconnect(); //自动重连
};
$tcp -> connect(); //连接

//以下是使用扩展协议
use Eventer\Package\YimoEx\Protocol\AsyncHttpConnection;
use Eventer\Package\YimoEx\Libs\HttpResponse;

$http = new AsyncHttpConnection('http://127.0.0.1:80');
$http -> onMessage = function($http, HttpResponse $data){
    var_dump($data); //data为一个HttpResponse对象
};
$http -> connect();
```

##### 2. Io库

```PHP
$io = new Io('test.md', 'w+'); //文件位于/public/下
//$io = new Io('test/test.md', 'w+'); //文件位于/test/public/下 (注意: 自动创建目录的功能仅单层)
$io -> write('hello')
    -> write(' ')
    -> write('world');
$io -> close();
```

##### 3. Listener库

```PHP
Listener::listen('userChecker', 'user.register', function($data){
    var_dump('注册数据: ', $data);
});
Listener::listen('userChecker', 'user.register.check', function($data){
    var_dump('检测注册数据');
});
Listener::listen('userChecker', 'user.login', function($data){
    var_dump('登录数据: ', $data);
});
Listener::emit('user.register', [ //触发user类目的register事件(会触发register类目下的所有事件,比如以上监听的check事件)
    'userName' => 'yimoEx',
    'password' => '123456',
]);
Listener::emit('user.*', [ //触发user类目下的所有事件(包括子事件)
    'userName' => 'yimoEx',
    'password' => '654321',
]);
Listener::unlistenAll('userChecker'); //关闭监听(注: 如果使用unlisten只能关闭一个userChecker)
```

##### 4. Cache库(Re)

- ~~实际上就是一个内置了 **Buffer** 的包装类 (带锁/超时属性)~~
- 数据容器 (带锁属性)
- 这里就不过多介绍了,详见文档

##### 5. AI模块 (扩展内容)
```php
$c = Ai::create();
$c -> send('你现在是一个C语言助手', '帮我写一个Hello world代码', function($msg){
    echo $msg . "\n";
});
```


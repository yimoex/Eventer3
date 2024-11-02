## Eventer3 类文档



#### 核心库 - Event

- Event::make(callback, float timer) => 建立事件

- init(callback) => 可用于处理初始化等任务 [回调函数参数: (Event event)]


#### 核心库 - Eventer

- register(Event $event) => 向核心中注册事件 (返回事件ID)

- unregister(string id) => 向核心中卸载事件 (id由 <register> 方法获取)



### Libs库


#### Buffer库: 数据缓冲

> 对数据的通用处理器

- length()  => 返回buffer中数据的长度
- update(string data) => 覆盖新的数据 [支持链式调用]
- add(string data) => 在数据尾部增加新数据 [支持链式调用]
- get() => 返回数据
- clear() => 清除 [支持链式调用]
- isEmpty() => 是否为空
- call(callback) => 回调函数  [回调函数参数: (string data, int dataSize)]



#### Cache库(Re): 内置的缓存控制机制

> ~~对Buffer的封装,内置权限和超时机制~~
>
> 数据容器 (而Buffer仅能存取String)

- Cache::create(string id) => 创建一个带有ID的 缓存节点,并存储于内存中
- set(string id, mixed data) => 向id的节点写入数据
- call(string id, callable caller) => 回调数据(返回值会覆盖缓存数据) [回调函数参数: (mixed data)]
- get(string id) => 获取数据
- lock(string id, bool enable) => 锁定/锁定缓存节点的数据



#### Listener库: 事件响应机制

> 以[事件发布<=>事件响应]的机制为应用解耦,提升内聚和扩展性

- listen(string id, string event_id, callback, [int count = -1]) => 注册一个内部标识id的事件

  并且响应event_id (规范: xxx.xxx.xxx)(支持通配符 *),响应次数count次(-1则为无限次)

- getListen(string id) => 获取监听器对象 `Listen`

- bindData(string id, mixed data) => 向监听器绑定数据

- unlisten(string|array id) => 解除目标id的事件监听

- unlistenByArray(array ids) => 使用数组进行批处理解除监听

- setCounts(string id, int counts) => 为监听器id设置响应次数

- emit(string id, data) => 触发名为id的事件(例: user.register),并附加data数据

##### Listen对象属性(可能会用到的)
 -  event_id(id): 触发事件ID(监听器ID)

 -  data: 由 <bindData> 绑定的数据




#### IO库: 基础文件控制器(本质是fopen等函数的封装)

> 基础的文件管理器

- (初始化) new Io(string filename, string mode) => 和fopen同理,但filename仅支持public文件夹下的文件
- write(string data) => 向io中写入数据 [支持链式调用]
- read(int size) => io中读入数据
- call(callback) => 与buffer同理  [回调函数参数: (文件流stream)]
- size() => 返回文件大小
- close() => 关闭io
- setAsync() => 设置为异步IO
- setSync() => 设置为同步IO


### 网络库

#### Connection: 基础连接类

> 该类无法用于网络请求/数据处理

- connect() => 启动连接

- reconnect() => 重新连接(一般于onClose中使用)

- close() => 关闭连接

  > 以下为Connection属性

- onConnect: 连接成功时触发  [回调函数参数: (Connection connection)]
- onMessage: 当数据传入时触发 [回调函数参数: (Connection connection, Buffer buf)]
- onTimeout: 当超时时触发(之后依旧会触发onClose) [回调函数参数: (Connection $connection)]
- onClose: 当连接被关闭时触发 [回调函数参数: (string signal)] (signal: 关闭者的ID)
- onError: Socket连接建立失败会触发 [回调函数参数: (int errCode, string errmsg)]

#### TcpConnection [继承Connection]: tcp连接类

- 无额外属性

#### AsyncTcpConnection [继承Connection]: 异步tcp连接类

- 无额外属性



### HTTP网络库扩展(位于package/yimoEx/protocol)

> 注: 在onMessage中,返回的数据不再是Buffer而是httpResponse类

#### HttpConnection [继承TcpConnection]: HTTP连接类

- request() => 返回 HTTP请求类
- isDataEnd() => 判断数据包是否传输完毕

#### AsyncHttpConnection [继承HttpConnection]: 异步HTTP连接类

- 无额外属性




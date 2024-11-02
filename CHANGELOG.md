### ChangeLog

#### V1.2 [[#a6c7c6]](https://github.com/yimoex/Eventer3/commit/a6c7c64c6f41ba6f3381dc2689c04fcc5160ee6e)

**[Re] 重构缓存组件 `Cache` (原Cache功能性过低,且与Buffer耦合过度)**

**[Feature/Add] 新增 `Promise` 类: 对业务解耦,可用于 `Connection` 类的更优化处理(例如发送邮件)**

[Feature/Add/Package - Base] 新增 `Timer` 类: 用于对运行时间的进一步控制

[Fix] 修复了 `Listener` 类中会出现多个同ID的节点的问题

[Fix] 修复了 `Listener` 类中的 `count` 属性不准确的问题

[Feature] `Listener` 新增<data>属性以及<bindData>方法来绑定数据,<getListen>获取Listen对象

[Feature] `Eventer` 现在支持<unregister>方法来卸载Event

[Change] `Event` 注册不再需要ID (由<register>方法返回获取),并且触发事件第二个参数为 `Eventer`


#### V1.11 (2024-11-1) [[#7561a8]](https://github.com/yimoex/Eventer3/commit/7561a8298ffe17a0af07e8f25702eeb5f26ef71f)

[Feature] setAttr对Event的支持

[Fix] 修复Cache的一些问题

[Doc] 完善了文档支持


#### V1.1 (2024-11-1) [[#9c3da4]](https://github.com/yimoex/Eventer3/commit/9c3da42a4e346a6680429dae5b5913d87d7a38a7)

[Fix] 适配PHP (httpRequest的属性问题) (PHP Ver >= 8.2)

[Fix] 修复了httpRequest特殊情况下无法发送POST数据的问题

[Fix] 修复了AsyncHttpConnection类：如果使用ssl协议会导致连接失败的问题

[Fix] 修复了HttpConnection类：无法记录时间的问题

[Fix] 修复了Network系中的数据包过大无法接受的BUG

[Add] AI模块 (仅需在Models下新建配置类即可使用)

[Feature] IO模块双模 (setAsync()/setSync() 方法)


#### V1.02 (2024-8-14)

- 添加Connection对ssl的支持 [[#d6c75b] ](https://github.com/yimoex/Eventer3/commit/d6c75b4ddad6d384e6d30191b549df4ae5a4cff9) [[#cfe943]](https://github.com/yimoex/Eventer3/commit/cfe943f038a0519a53450c8f41ced7cb59170927)

#### V1.01(2024-8-12)

- 添加对 [文档](FUNCTIONS.md) 的支持


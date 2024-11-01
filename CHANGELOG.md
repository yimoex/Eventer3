### ChangeLog

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


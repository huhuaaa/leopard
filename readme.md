## 文件目录
	Leopard
	|-- application
		|-- config 配置目录
		|-- controllers 控制器目录
		|-- error 错误提示目录
		|-- logs 日志目录
		|-- models 模型目录
		|-- views 视图目录
	|-- system
	|-- web //站点入口根目录，存放index.php、js、css以及其他公开的资源

## 系统类库
###	1、Leopard
- 目录： system/leopard.php
- 说明： 系统入口类，提供run静态方法（系统入口方法）。

### 2、config（配置类）
- 目录： system/config.php
- 说明： 配置管理类
- 静态方法： 
	<table>
		<tr><th>方法名称</th><th>说明</th><th>参数</th><th>参数类型</th><th>参数作用</th></tr>
		<tr><td>read</td><td>读取指定路径的配置文件</td><td>$path</td><td>string</td><td>配置目录（不包含php的扩展名）</td></tr>
		<tr><td>get</td><td>获取已读取的配置变量值</td><td>$key</td><td>string</td><td>配置键值</td></tr>
		<tr><td>load</td><td>读取指定名称的配置文件</td><td>$name</td><td>string</td><td>配置名称（application/config目录下对应文件）</td></tr>
	</table>
- 使用样例
		
		// application/config下test.php配置文件
		<?php
		$config['tname'] = 'test name';

		// config调用代码
		<?php
		use system\config;
		config::load('test');
		$tname = config::get('tname');
		echo $tname; //将输出'test name'
### 3、loader
- 目录： system/loader.php
- 说明： 根据类名加载类文件的方法（类需要使用命名空间），提供load静态方法。由于不需要单独调用这个方法，那么有需要直接看代码。

### 4、log
- 目录： system/log.php
- 说明： 日志类
- 静态方法：

	<table>
		<tr><th>方法名称</th><th>说明</th><th>参数</th><th>参数类型</th><th>参数作用</th></tr>
		<tr><td rowspan="2">write</td><td  rowspan="2">记录日志到application/logs目录</td><td>$type</td><td>string</td><td>日志类型标志（系统内置ERROR,DEBUG类型），DEBUG类型只在开发环境中有效</td></tr>
		<tr><td>$message</td><td>string</td><td>日志信息</td></tr>
	</table>

### 5、controller
- 目录： system/controller
- 说明： 控制器基类

### 6、view
- 目录： system/view
- 说明： 视图构建类
- 静态方法：
	<table>
		<tr><th>方法名称</th><th>说明</th><th>参数</th><th>参数类型</th><th>参数作用</th></tr>
		<tr><td rowspan="2">make</td><td  rowspan="2">创建视图</td><td>$view</td><td>string</td><td>视图相对路径（不包含扩展名）</td></tr>
		<tr><td>$data</td><td>array</td><td>传递到视图的参数</td></tr>
	</table>

		待续。。。
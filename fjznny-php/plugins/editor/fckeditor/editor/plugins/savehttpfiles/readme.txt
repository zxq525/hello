作者：九天工作室(http://www.cn09.com)
口号：开源不是施舍，而是一种合作方式
FCKeditor 远程保存图片插件
插件介绍：
在FCKeditor中复制网页内容时，其中的图片仍然保存在源站点上，使用该插件可将这些图片文件保存到站长自己的服务器上。

作为站长，或许，您遇到过下列问题：
1、我想给自己的网站增加一些小功能，但总也找不到合适的--因为很少有程序员愿意开发;
2、我终于找到了一个适合的小插件，却发现它是上世纪九十年代的，始终没有更新过--因为程序员没有更新的动力;
3、插件的功能和我要的总是会有出入，我需要不同的功能--程序员根据自己的需求而不是大众的需求开发插件。
或许，您可以尽自己的一份心力，改变这让人窘困的现状，到点击下面的链接
http://item.taobao.com/auction/item_detail-0db2-dbcac2ccb9810e4ef52ca073becce752.htm
或在http://shop33325042.taobao.com/ 中的站长捐赠类中寻找到该商品，
支付十元或更多捐款，给作者以动力，谢谢了。

最近更新：2009.4.18 v1.02
2009.4.18更新内容：
本插件修改自网上luojiannx@gmail.com发布的同名插件，几经修改，已与原版本迥然不同：
1、在save.php中对文件后缀名进行二次验证，解决了上传图片的安全隐患;
2、修正原代码的BUG，保证了服务器能够一次性顺利转存文件内容;
3、采用FCKeditor的dialog组件显示而不是弹出窗口，使程序更美观;
4、采用时间戳作为文件名(待商榷);
5、config.php文件独立以便于修改;
6、代码大量重写。

使用方法：
1)把解压出的文件夹放到\editor\plugins下
2)修改config.php文件,设置以下两个参数，例如：
  //设置图片保存绝对路径
  $saveFilePath='D:/www/pic';
  //设置显示的链接地址
  $displayUrl='http://localhost/pic';
3)修改fckconfig.js
  1.追加一行内容如下
  FCKConfig.Plugins.Add( 'savehttpfiles');
  2.在编辑器的工具栏上加一个按钮,书写如下
  FCKConfig.ToolbarSets["Default"] = 
  ['FitWindow','ShowBlocks','-','About','savehttpfiles']
4)joomla用户
  修改plugins/editors/fckeditor/editor/jtoolbarsetconfig.xml文件
  在你觉得合适的位置插入<plugin name="savehttpfiles" acl="*"/>，如果是完全不懂代码的站长，可以找任意一行<plugin name="随便什么内容" acl="*"/>，在其上或其下插入即可。
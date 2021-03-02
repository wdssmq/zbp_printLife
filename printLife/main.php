<?php
require '../../../zb_system/function/c_system_base.php';
require '../../../zb_system/function/c_system_admin.php';
$zbp->Load();
$action = 'root';
if (!$zbp->CheckRights($action)) {
  $zbp->ShowError(6);
  die();
}
if (!$zbp->CheckPlugin('printLife')) {
  $zbp->ShowError(48);
  die();
}
$act = GetVars('act', 'GET');
$suc = GetVars('suc', 'GET');
if ($act == 'save') {
  CheckIsRefererValid();
  foreach ($_POST as $key => $val) {
    switch ($key) {
      case 'article':
      case 'diff':
        $val = (int)trim($val);
        break;
      default:
        $val = trim($val);
        break;
    }
    $zbp->Config('printLife')->$key = $val;
  }
  $zbp->SaveConfig('printLife');
  $zbp->BuildTemplate();
  $zbp->SetHint('good');
  Redirect('./main.php' . ($suc == null ? '' : '?act=$suc'));
}
printLife_CheckExp();
InstallPlugin_printLife();
$blogtitle = 'Print My Life';
require $blogpath . 'zb_system/admin/admin_header.php';
require $blogpath . 'zb_system/admin/admin_top.php';
?>
<div id="divMain">
  <div class="divHeader"><?php echo $blogtitle; ?></div>
  <div class="SubMenu">
    <a href="main.php" title="首页"><span class="m-left m-now">首页</span></a>
    <?php require "about.php"; ?>
  </div>
  <div id="divMain2">
    <form action="<?php echo BuildSafeURL("main.php?act=save"); ?>" method="post">
      <table width="100%" class="tableBorder">
        <tr>
          <th width="10%">项目</th>
          <th>内容</th>
          <th width="45%">说明</th>
        </tr>
        <tr>
          <td>指定文章/页面ID</td>
          <td><?php zbpform::text("article", $zbp->Config("printLife")->article, "90%"); ?></td>
          <td>文章信息回显：<?php printLife_ViewPost(); ?></td>
        </tr>
        <tr>
          <td>选择器</td>
          <td><?php zbpform::text("selector", $zbp->Config("printLife")->selector, "90%"); ?></td>
          <td>如果为空将以iframe方式调用</td>
        </tr>
        <tr>
          <td>严格模式</td>
          <td><?php zbpform::zbradio("strict", $zbp->Config("printLife")->strict); ?></td>
          <td>开启后将设置上述文章为草稿，直到逾期</td>
        </tr>
        <tr>
          <td>逾期间隔</td>
          <td><?php zbpform::text("diff", $zbp->Config("printLife")->diff, "90%"); ?></td>
          <td>←距离上一次登录后台的时间天数；<br>如果指定的文章本身处于公开状态，同时严格模式未开启时则直接弹出；</td>
        </tr>
        <!-- <tr>
          <td>文章前的提示</td>
          <td><?php zbpform::textarea("note", htmlspecialchars($zbp->Config("printLife")->note), "90%"); ?></td>
          <td>暂时没写的功能</td>
        </tr> -->
        <tr>
          <td></td>
          <td colspan="2"><input type="submit" value="提交" /></td>
        </tr>
      </table>
    </form>
    <p>上一次记录时间：<b><?php echo date("Y-m-d H:i:s", $zbp->Config('printLife')->lstDayTime); ?></b>（每小时更新一次）</p>
    <p>所以不用好像，这就是个类似墓志铭的东西，连续N天不登录后台就会在全站弹出显示指定的内容页；</p>
    <p>弹出效果基于Layer；</p>
    <p>人生删除事务所 (豆瓣)</p>
    <p>-----</p>
    <p><a href="https://movie.douban.com/subject/30232260/" target="_blank"
        title="人生删除事务所 (豆瓣)">https://movie.douban.com/subject/30232260/</a></p>
    <p>人生删除事务所 _ 电视剧 _ bilibili _ 哔哩哔哩弹幕视频网</p>
    <p><a href="https://www.bilibili.com/bangumi/media/md28221628/" target="_blank"
        title="人生删除事务所 _ 电视剧 _ bilibili _ 哔哩哔哩弹幕视频网">https://www.bilibili.com/bangumi/media/md28221628/</a></p>
  </div>
</div>

<?php
require $blogpath . 'zb_system/admin/admin_footer.php';
RunTime();
?>

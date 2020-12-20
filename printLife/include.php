<?php
#注册插件
RegisterPlugin("printLife", "ActivePlugin_printLife");
function ActivePlugin_printLife()
{
  Add_Filter_Plugin('Filter_Plugin_Admin_Header', 'printLife_UpdateTime');
  Add_Filter_Plugin('Filter_Plugin_Feed_Begin', 'printLife_CheckExp');
  Add_Filter_Plugin('Filter_Plugin_ViewPost_Template', 'printLife_Main');
  Add_Filter_Plugin('Filter_Plugin_ViewList_Template', 'printLife_Main');
}
function printLife_Main(&$template)
{
  global $zbp;
  $selector = $zbp->Config("printLife")->selector;
  if (printLife_CheckExp($url, $title, $isExp)) {
    $script = printLife_Path("script", "host");
    $zbp->footer .= "<!--printLife-->\n<script>const printLifeObj = {url:'{$url}',title:'{$title}',isExp:{$isExp},selector:'${selector}'}</script>\n";
    $zbp->footer .= "<script src=\"{$script}?v=2020-12-20\"></script>\n";
  }
}
/**
 * 更新时间到当前
 * @return none
 */
function printLife_UpdateTime()
{
  global $zbp;
  $time = time();
  if (date("d-H", $time) == date("d-H", $zbp->Config('printLife')->lstDayTime)) {
    return;
  }
  $zbp->Config('printLife')->lstDayNum = intval($time / 86400);
  $zbp->Config('printLife')->lstDayTime = $time;
  $zbp->SaveConfig('printLife');
}
/**
 * 判断是否逾期
 * @return bool
 */
function printLife_CheckExp(&$url = "", &$title = "", &$isExp = 0)
{
  global $zbp;
  $isExp = 0;
  $article = GetPost($zbp->Config("printLife")->article);
  if ($article->ID == 0) {
    return false;
  }
  $url = $article->Url;
  $title = $article->Title;
  $time = time();
  $curDayNum = intval($time / 86400);
  $lstDayNum = $zbp->Config('printLife')->lstDayNum;
  $diff = $zbp->Config('printLife')->diff;
  if ($curDayNum - $lstDayNum > $diff) {
    $article->Status = 0;
    $article->Save();
    $isExp = 1;
    return true;
  }
  if ($zbp->Config("printLife")->strict) {
    $article->Status = 1;
    $article->Save();
  }
  if ($article->Status == 0) {
    return true;
  }
  return false;
}
function printLife_ViewPost()
{
  global $zbp;
  $article = GetPost($zbp->Config("printLife")->article);
  if ($article->ID == 0) {
    return;
  }
  $arrData = array(
    "-host-" => $zbp->host,
    // "-type-" => "PageEdt",
    "-id-" => $article->ID,
    "-title-" => $article->Title,
    "-href-" => $article->Url,
  );
  if ($article->Type == 0) {
    $arrData["-type-"] = "ArticleEdt";
  } else {
    $arrData["-type-"] = "PageEdt";
  }
  if ($article->Status == 0) {
    $arrData["-Status-"] = "公开";
  } else {
    $arrData["-Status-"] = "草稿";
  }
  $edt = '-host-zb_system/cmd.php?act=-type-&id=-id-';
  $arrData["-edt-"] = strtr($edt, $arrData);
  $tpl = '[<a target="_blank" href="-href-" title="-title-">-title-</a>][<a target="_blank" href="-edt-" title="编辑-title-">编辑</a>][当前状态：-Status-]';
  echo strtr($tpl, $arrData);
}
function printLife_Path($file, $t = 'path')
{
  global $zbp;
  $result = $zbp->$t . 'zb_users/plugin/printLife/';
  switch ($file) {
    case 'script':
      return $result . 'script/printLife.js';
      break;
    case 'usr':
      return $result . 'usr/';
      break;
    case 'var':
      return $result . 'var/';
      break;
    case 'main':
      return $result . 'main.php';
      break;
    default:
      return $result . $file;
  }
}
function InstallPlugin_printLife()
{
  global $zbp;
  if (!$zbp->HasConfig('printLife')) {
    $zbp->Config('printLife')->version = 1;
    $zbp->Config('printLife')->diff = 59;
    printLife_UpdateTime();
  }
  if ($zbp->CheckPlugin('zbpWCm')) {
    zbpWCm_require("layer", "printLife");
  }
}
function UninstallPlugin_printLife()
{
}
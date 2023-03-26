/* global printLifeObj, layer */

$(function () {
  // 防止套娃弹出
  if (window.frames.length != parent.frames.length) {
    return false;
  }
  // 根据Cookie决定要不要弹出
  const cookieName = "plug_printLife";
  const cookieValue = zbp.cookie.get(cookieName) || false;
  // const cookieValue = false;
  if (cookieValue && !printLifeObj.isExp) {
    return;
  }
  // const sleep = (ms) => new Promise((resolve) => setTimeout(resolve, ms));
  // Ajax提取选择元素
  function fnAjax(url, selector) {
    const filter = selector.split("--");
    // console.log(filter);
    $.get(url, function (html) {
      const $rlt = $(html).find(filter[0]);
      if (filter[1]) {
        $rlt.find(filter[1]).remove();
      }
      $("#printLife").html($rlt.html()).css({
        padding: "7px",
        overflowX: "hidden",
        backgroundColor: "#fff",
      });
      // console.log($rlt);
    });
  }
  const selector = printLifeObj.selector;
  if (selector) {
    fnAjax(printLifeObj.url, selector);
  }
  // 配置项初始
  const viewWidth = $(window).width();
  const layerOpt = {
    type: selector ? 1 : 2,
    title: `${printLifeObj.title} - 首次访问请手动关闭本弹出→`,
    width: viewWidth > 1054 ? 1054 : viewWidth,
    content: selector ? "null" : printLifeObj.url,
    id: "printLife",
  };
  layer.open({
    type: layerOpt.type,
    anim: 1,
    title: layerOpt.title,
    // shadeClose: true,
    id: layerOpt.id,
    shade: false,
    maxmin: true, // 开启最大化最小化按钮
    area: [`${layerOpt.width}px`, "593px"],
    content: layerOpt.content,
    success: function (layero, index) {
      if (cookieValue) {
        setTimeout(() => {
          layero.slideToggle(793, function () {
            layer.min(index);
            layero.slideToggle(793);
          });
        }, 5237);
      } else {
        zbp.cookie.set(cookieName, 1, 3);
      }
    },
    cancel: function (index, layero) {
      if (layero.find(".layui-layer-min").is(":visible")) {
        layer.min(index);
      }
      return false;
    },
  });
});

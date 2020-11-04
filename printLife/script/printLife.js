$(function () {
  if (window.frames.length != parent.frames.length) {
    return false;
  }
  const cookieName = "plug_printLife";
  const cookieValue = zbp.cookie.get(cookieName) || false;
  // const cookieValue = false;
  if (cookieValue && !printLifeObj.isExp) {
    return;
  }
  const viewWidth = $(window).width();
  const layerWidth = viewWidth > 1054 ? 1054 : viewWidth;
  layer.open({
    type: 2,
    anim: 1,
    title: `${printLifeObj.title} - 首次访问请手动关闭本弹出→`,
    // shadeClose: true,
    shade: false,
    maxmin: true, //开启最大化最小化按钮
    area: [`${layerWidth}px`, "593px"],
    content: printLifeObj.url,
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

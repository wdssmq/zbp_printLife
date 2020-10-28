$(function () {
  if (window.frames.length != parent.frames.length) {
    return false;
  }
  const strToken = zbp.cookie.get("capt_PubFeedHash");
  if (strToken) {
    return;
  }
  const cookieName = "plug_printLife";
  const cookieValue = zbp.cookie.get(cookieName);
  let isClosed = false;
  // const cookieValue = false;
  layer.open({
    type: 2,
    anim: 1,
    title: `${printLifeObj.title} - 首次访问请手动关闭本弹出`,
    // shadeClose: true,
    shade: false,
    maxmin: true, //开启最大化最小化按钮
    area: ["1054px", "593px"],
    content: printLifeObj.url,
    success: function (layero, index) {
      if (cookieValue) {
        setTimeout(() => {
          // !isClosed &&
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
      // isClosed = true;
      if (layero.find(".layui-layer-min").is(":visible")) {
        layer.min(index);
      }
      return false;
    },
  });
});

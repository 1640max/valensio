/* https://htmldom.dev/drag-to-scroll/ */

var scrollSnapTypeDefault;

document.querySelectorAll('.drag-to-scroll').forEach(function(container) {

  document.addEventListener('DOMContentLoaded', function () {
    container.style.cursor = 'grab';
    let pos = { top: 0, left: 0, x: 0, y: 0 };

    const mouseDownHandler = function (e) {
      // Prevents dragging ghost image (как если потянуть рандомную картинку на любом сайте)
      e.preventDefault();
      container.style.cursor = 'grabbing';
      // Если зажать мышь и потянуть, то картинки выделятся как текст. Предотвращаем это:
      container.style.userSelect = 'none';
      // Запоминаем, в каком состоянии был параметр scrollSnapType, чтоб потом вернуть его
      scrollSnapTypeDefault = container.style.scrollSnapType;
      container.style.scrollSnapType = 'none';

      pos = {
        left: container.scrollLeft,
        top: container.scrollTop,
        // Get the current mouse position
        x: e.clientX,
        y: e.clientY,
      };

      document.addEventListener('mousemove', mouseMoveHandler);
      document.addEventListener('mouseup', mouseUpHandler);
    };

    const mouseMoveHandler = function (e) {
      // How far the mouse has been moved
      const dx = e.clientX - pos.x;
      const dy = e.clientY - pos.y;

      // Scroll the element
      container.scrollTop = pos.top - dy;
      container.scrollLeft = pos.left - dx;
    };

    const mouseUpHandler = function () {
      container.style.cursor = 'grab';
      container.style.scrollSnapType = scrollSnapTypeDefault;
      container.style.removeProperty('user-select');

      document.removeEventListener('mousemove', mouseMoveHandler);
      document.removeEventListener('mouseup', mouseUpHandler);
    };

    // Attach the handler
    container.addEventListener('mousedown', mouseDownHandler);
  });

  // Фикс бага, когда container прогружается уже прокрученный (не очень помогает)
  window.onload = function() {
    container.style.removeProperty('align-self');
    container.style['align-self'] = 'start';
    
    // Ради эксперимента загнал в таймер (один хер)
    // setTimeout(() => {
    //   container.scrollLeft = 0;
    // }, "1000")
  };

  // Дополнительное. Выравниваем галерею по левому краю бутстраповских контейнеров
  function offsetToPadding() {
    let offset = document.querySelector(".footer").offsetLeft;

    // Тут не забудь добавить кастомизируемые паддинги для бутсапа, когд будешь выкладывать на гит
    container.style.paddingLeft = 'calc(' + offset +'px)'; // + .75rem)';
    container.style.paddingRight = 'calc(' + offset +'px)'; // + .75rem)';
  }

  document.addEventListener("DOMContentLoaded", offsetToPadding);
	window.addEventListener("resize", offsetToPadding);
});
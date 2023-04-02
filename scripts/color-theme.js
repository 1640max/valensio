var html = document.querySelector('html');
var colorMode = localStorage.getItem('color-mode');
var prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)');
var button = document.querySelector('.toggle-theme');
var icon = document.querySelector('.toggle-theme__icon');

function enableDarkTheme() {
  html.classList.add("color-theme_name_dark");
  icon.innerText = "☀️";
  refreshMetaThemeColor();
}

function disableDarkTheme() {
  html.classList.remove("color-theme_name_dark");
  icon.innerText = "🌛";
  refreshMetaThemeColor();
}

function isThemeDefault() {
  return !html.getAttribute('class').match(/color-theme_name_[^ ]/);
}

function isDarkThemeEnabled() {
  return html.classList.contains("color-theme_name_dark");
}

function getColorMode() {
  let name = 'color-mode';
  let matches = document.cookie.match(new RegExp(
    "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
  ));
  return matches ? matches[1] : undefined;
}

function setColorMode(colorMode) {
  // Устраняем мои старые хулиганства
  document.cookie = 'color-mode=' + colorMode + '; max-age=0;';
  document.cookie = 'color-mode=' + colorMode + '; max-age=34560000; path=/';
  
}

// Цвет панели в мобильном Хроме
function refreshMetaThemeColor() {
  // Если тема по умолчанию, то ставим акцентный цвет,
  // иначе ставим --background-2
  let color;
  if (isThemeDefault()) {
    color = getComputedStyle(document.documentElement).getPropertyValue('--accent');
  } else {
    color = getComputedStyle(document.documentElement).getPropertyValue('--background-2');
  }
  document.querySelector('meta[name="theme-color"]').setAttribute('content', color.trim());
}



// -----------------------------------------------------------------------------



document.addEventListener('DOMContentLoaded', function () {

  // Устанавливаем цвет панели в Хроме
  refreshMetaThemeColor();
  
  // Если сервер добавил класс тёмной темы, то надо также обновить иконку
  // на кнопке переключения темы и обновить meta-тег theme-color.
  if (isDarkThemeEnabled()) enableDarkTheme();

  // Если кука color-mode не установлена, то устанавливаем её
  // и меняем тему, если надо. А надо, если пользователь предпочитает тёмную
  // тему и сейчас используется дефолтная (белая).
  if (!getColorMode()) {
    if (prefersDark) {
      setColorMode('darkAndColors');
      if (isThemeDefault()) enableDarkTheme();
    }
    else
      setColorMode('light');
  }
});

// Обработчик клика по кнопке смены темы.
// При клике сменяется не значение colorMode, а меняется фактическая тема.
button.addEventListener('click', function() {
  if (isDarkThemeEnabled()) {
    disableDarkTheme();
    setColorMode('light');
  }
  else {
    enableDarkTheme();
    setColorMode('dark');
  }
});
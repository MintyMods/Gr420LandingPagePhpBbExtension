console.info('Minty Script Loader initialised...');
const APP_ROOT = "../ext/minty/homepage/styles/all/template/";

const BACKGROUND_IMAGES = [
    'bg1.jpg',
    'bg2.jpg',
    'bg3.jpg',
    'bg4.jpg',
    'bg5.jpg',
    'bg6.jpg'];

const scripts = [
    "js/jquery.min.js",
    "js/jquery.scrollex.min.js",
    "js/jquery.scrolly.min.js",
    "js/browser.min.js",
    "js/breakpoints.min.js",
    "js/util.js",
    "js/main.js"];
var _scripts = [];

loadScripts(scripts, _scripts);

function loadScripts(_scripts, scripts) {
    var x = 0;
    var loopScripts = function (_scripts, scripts) {
        loadScript(_scripts[x], scripts[x], function () {
            x++;
            if (x < _scripts.length) {
                loopScripts(_scripts, scripts);
            }
        });
    }
    loopScripts(_scripts, scripts);
}

function loadScript(src, script, callback) {
    script = document.createElement('script');
    script.onerror = function (e) {
        console.error(e);
    }
    script.onload = function () {
        console.info('Script ' + src + ' loaded ');
        if (callback) callback();
    }
    script.src = APP_ROOT + src;
    document.getElementsByTagName('head')[0].appendChild(script);
}

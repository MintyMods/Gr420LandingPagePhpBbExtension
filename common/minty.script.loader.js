console.info('Minty Script Loader initialised...');
const root = "../ext/minty/homepage/assests/js/";
const scripts = [
   "jquery.min.js",
   "jquery.scrollex.min.js",
   "jquery.scrolly.min.js",
   "browser.min.js",
   "breakpoints.min.js",
   "util.js",
   "main.js"];
var _scripts = [];

loadScripts(scripts, _scripts);

function loadScripts (_scripts, scripts) {
    var x = 0;
    var loopScripts = function(_scripts, scripts) {
        loadScript(_scripts[x], scripts[x], function(){
            x++;
            if (x < _scripts.length) {
               loopScripts(_scripts, scripts);   
            }
        }); 
    }
    loopScripts(_scripts, scripts);      
}

function loadScript( src, script, callback ){
    script = document.createElement('script');
    script.onerror = function(e) { 
        console.error(e);
    }
    script.onload = function(){
        console.info('Script ' + src + ' loaded ');
        if (callback) callback();
    }
    script.src = root + src;
    document.getElementsByTagName('head')[0].appendChild(script);
}

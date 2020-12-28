console.info('Minty Scheduler Script Loader initialised...');
const scheduler_scripts = [
   "dhtmlx/dhtmlxscheduler.js",
   "dhtmlx/ext/dhtmlxscheduler_year_view.js",
   "dhtmlx/ext/dhtmlxscheduler_container_autoresize.js",
   "js/minty_utils.js",
   "js/scheduler.js",
   // "dhtmlx/ext/dhtmlxscheduler_quick_info.js",
   // "dhtmlx/ext/dhtmlxscheduler_minical.js",
   // "dhtmlx/ext/dhtmlxscheduler_recurring.js",
   // "dhtmlx/ext/dhtmlxscheduler_multiselect.js",
   // "dhtmlx/ext/dhtmlxscheduler_editors.js",
   // "dhtmlx/ext/dhtmlxscheduler_serialize.js"
];
loadScripts(scheduler_scripts, _scripts);

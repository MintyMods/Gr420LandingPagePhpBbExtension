let sponsors = null;

window.addEventListener("DOMContentLoaded", function(){

    // different configs for different screen sizes
    var compactView = {
        xy: {
            nav_height: 80
        },
        config: {
            header: {
                rows: [
                    { 
                        cols: [
                            "prev",
                            "date",
                            "next",
                        ]
                    },
                    { 
                        cols: [
                            "day",
                            "week",
                            "month",
                            "year",
                            "spacer",
                            "today"
                        ]
                    }
                ]
            }
        },
        templates: {
            month_scale_date: scheduler.date.date_to_str("%D"),
            week_scale_date: scheduler.date.date_to_str("%D, %j"),
            event_bar_date: function(start,end,ev) {
                return "";
            }
            
        }
    };
    var fullView = {
        xy: {
            nav_height: 80
        },
        config: {
            header: [
                "day",
                "week",
                "month",
                "year",
                "date",
                "prev",
                "today",
                "next"
            ]
        },
        templates: {
            month_scale_date: scheduler.date.date_to_str("%l"),
            week_scale_date: scheduler.date.date_to_str("%l, %F %j"),
            event_bar_date: function(start,end,ev) {
                return "• <b>"+scheduler.templates.event_date(start)+"</b> ";
            }
        }
    };

    function resetConfig(){
        var settings;
        if(window.innerWidth < 1000){
            settings = compactView;
        } else {
            settings = fullView;
        }
        scheduler.utils.mixin(scheduler.config, settings.config, true);
        scheduler.utils.mixin(scheduler.templates, settings.templates, true);
        scheduler.utils.mixin(scheduler.xy, settings.xy, true);
        return true;
    }

    scheduler.config.responsive_lightbox = true;
    // scheduler.config.lightbox.sections = [
    //     {name:"description", height:200, map_to:"text", type:"textarea", focus:true},
    //     {name:"time", height:72, type:"calendar_time", map_to:"auto" }
    // ];




    sponsors = loadJSON('/json/sponsors.json');

    scheduler.config.responsive_lightbox = true;
    scheduler.config.multi_day = true;
    scheduler.config.prevent_cache = true;
    scheduler.locale.labels.timeline_tab = "Schedule";
    scheduler.locale.labels.unit_tab = "Events";
    scheduler.locale.labels.week_agenda_tab = "Agenda";
    scheduler.config.details_on_create = true;
    scheduler.config.details_on_dblclick = true;
    scheduler.config.date_format = "%Y-%m-%d %H:%i";


    scheduler.createTimelineView({
        name: "timeline",
        x_unit: "minute",
        x_date: "%H:%i",
        x_step: 60,
        x_size: 24,
        x_start: 0,
        render: "days",
        days: 63
        // ,
        // y_unit: automation,
        // y_property: "section_id"
    });



    scheduler.config.lightbox.sections = [
        { name: "Resource", tag: "RESOURCE:", type: "select", map_to: "RESOURCE:", options: automation, onchange: hideLightBoxControls },
        { name: "Custom Notes", tag: "CUSTOM:", height: 50, map_to: "CUSTOM:", type: "textarea" },
        { name: "Light", tag: "CONTROL:LIGHT:", options: ON_OFF, map_to: "CONTROL:LIGHT:", type: "select", onchange: checkTriggerEnabled },
        { name: "Extract Fan", tag: "CONTROL:AIR_EXTRACT_FAN:", options: ON_OFF, map_to: "CONTROL:AIR_EXTRACT_FAN:", type: "select", onchange: checkTriggerEnabled },
        { name: "Intake Fan", tag: "CONTROL:AIR_INTAKE_FAN:", options: HIGH_LOW_OFF, map_to: "CONTROL:AIR_INTAKE_FAN:", type: "select", onchange: checkTriggerEnabled },
        { name: "Std Fan A", tag: "CONTROL:AIR_MOVEMENT_FAN_A:", options: ON_OFF, map_to: "CONTROL:AIR_MOVEMENT_FAN_A:", type: "select", onchange: checkTriggerEnabled },
        { name: "Std Fan B", tag: "CONTROL:AIR_MOVEMENT_FAN_B:", options: ON_OFF, map_to: "CONTROL:AIR_MOVEMENT_FAN_B:", type: "select", onchange: checkTriggerEnabled },
        { name: "Water Heater", tag: "CONTROL:WATER_HEATER:", options: ON_OFF, map_to: "CONTROL:WATER_HEATER:", type: "select", onchange: checkTriggerEnabled },
        { name: "Air Heater", tag: "CONTROL:HEATER:", options: ON_OFF, map_to: "CONTROL:HEATER:", type: "select", onchange: checkTriggerEnabled },
        { name: "Humidifier", tag: "CONTROL:HUMIDIFIER:", options: HIGH_LOW_OFF, map_to: "CONTROL:HUMIDIFIER:", type: "select", onchange: checkTriggerEnabled },
        { name: "De-Humidifier", tag: "CONTROL:DEHUMIDIFIER:", options: ON_OFF, map_to: "CONTROL:DEHUMIDIFIER:", type: "select", onchange: checkTriggerEnabled },
        { name: "Air Pump", tag: "CONTROL:AIR_PUMP:", options: ON_OFF, map_to: "CONTROL:AIR_PUMP:", type: "select", onchange: checkTriggerEnabled },
        { name: "Recirculating Pump", tag: "CONTROL:WATER_PUMP:", options: ON_OFF, map_to: "CONTROL:WATER_PUMP:", type: "select", onchange: checkTriggerEnabled },
        { name: "Fill Pump", tag: "PUMP:FILL:", options: ON_OFF, map_to: "PUMP:FILL:", type: "select", onchange: checkTriggerEnabled },
        { name: "Drain Pump", tag: "PUMP:DRAIN:", options: ON_OFF, map_to: "PUMP:DRAIN:", type: "select", onchange: checkTriggerEnabled },
        { name: "Drip Feed Pump", tag: "PUMP:DRIP:", options: ON_OFF, map_to: "PUMP:DRIP:", type: "select", onchange: checkTriggerEnabled },
        { name: "Nutrient", tag: "NUTRIENT:DOSE:", options: nutrients, map_to: "NUTRIENT:DOSE:", type: "select", onchange: checkTriggerEnabled },
        { name: "Condition", tag: "TRIGGER:", type: "select", map_to: "TRIGGER:", options: conditions, onchange: getEventConditions },
        // { name:"recurring",  type:"recurring", map_to:"rec_type",  button:"recurring"},
        { name: "Time", tag: "_TIME", type: "calendar_time", map_to: "time" } 
    ];


    resetConfig();
    scheduler.attachEvent("onBeforeViewChange", resetConfig);
    scheduler.attachEvent("onSchedulerResize", resetConfig);
    scheduler.init('compitition_scheduler', new Date(), "month");
    // scheduler.load("../common/events.json");

    document.querySelector(".add_event_button").addEventListener("click", function(){
        scheduler.addEventNow();
    });
});
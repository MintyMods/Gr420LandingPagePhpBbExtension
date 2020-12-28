let sponsors = null;

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
        event_bar_date: function (start, end, ev) {
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
        event_bar_date: function (start, end, ev) {
            return "• <b>" + scheduler.templates.event_date(start) + "</b> ";
        }
    }
};

function resetConfig() {
    var settings;
    if (window.innerWidth < 1000) {
        settings = compactView;
    } else {
        settings = fullView;
    }
    scheduler.utils.mixin(scheduler.config, settings.config, true);
    scheduler.utils.mixin(scheduler.templates, settings.templates, true);
    scheduler.utils.mixin(scheduler.xy, settings.xy, true);
    return true;
}
scheduler.config.container_autoresize = true;
scheduler.config.readonly = true;
scheduler.locale.labels.timeline_tab = "Schedule";
scheduler.locale.labels.unit_tab = "Events";
scheduler.config.date_format = "%Y-%m-%d %H:%i";
scheduler.attachEvent("onBeforeViewChange", resetConfig);
scheduler.attachEvent("onSchedulerResize", resetConfig);
resetConfig();
scheduler.load("./data");
scheduler.init('compitition_scheduler', new Date(), "month");


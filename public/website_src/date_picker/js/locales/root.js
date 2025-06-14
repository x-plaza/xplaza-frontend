(function () {
  "use strict";

  if (typeof Date.dp_locales === "undefined") {
    Date.dp_locales = {
      texts: {
        buttonTitle: "Select date ...",
        buttonLabel:
          "Click or press the Enter key or the spacebar to open the calendar",
        prevButtonLabel: "Go to previous month",
        prevMonthButtonLabel: "Go to the previous year",
        prevYearButtonLabel: "Go to the previous twenty years",
        nextButtonLabel: "Go to next month",
        nextMonthButtonLabel: "Go to the next year",
        nextYearButtonLabel: "Go to the next twenty years",
        changeMonthButtonLabel:
          "Click or press the Enter key or the spacebar to change the month",
        changeYearButtonLabel:
          "Click or press the Enter key or the spacebar to change the year",
        changeRangeButtonLabel:
          "Click or press the Enter key or the spacebar to go to the next twenty years",
        closeButtonTitle: "Close",
        closeButtonLabel: "Close the calendar",
        calendarHelp:
          "- Up Arrow and Down Arrow - goes to the same day of the week in the previous or next week respectively. If the end of the month is reached, continues into the next or previous month as appropriate.\r\n- Left Arrow and Right Arrow - advances one day to the next, also in a continuum. Visually focus is moved from day to day and wraps from row to row in the grid of days.\r\n- Control+Page Up - Moves to the same date in the previous year.\r\n- Control+Page Down - Moves to the same date in the next year.\r\n- Home - Moves to the first day of the current month.\r\n- End - Moves to the last day of the current month.\r\n- Page Up - Moves to the same date in the previous month.\r\n- Page Down - Moves to the same date in the next month.\r\n- Enter or Espace - closes the calendar, and the selected date is shown in the associated text box.\r\n- Escape - closes the calendar without any action.",
      },
      directionality: "LTR",
      month_names: [
        "M01",
        "M02",
        "M03",
        "M04",
        "M05",
        "M06",
        "M07",
        "M08",
        "M09",
        "M10",
        "M11",
        "M12",
      ],
      month_names_abbreviated: [
        "M01",
        "M02",
        "M03",
        "M04",
        "M05",
        "M06",
        "M07",
        "M08",
        "M09",
        "M10",
        "M11",
        "M12",
      ],
      month_names_narrow: [
        "1",
        "2",
        "3",
        "4",
        "5",
        "6",
        "7",
        "8",
        "9",
        "10",
        "11",
        "12",
      ],
      day_names: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
      day_names_abbreviated: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
      day_names_short: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
      day_names_narrow: ["S", "M", "T", "W", "T", "F", "S"],
      day_periods: {
        am: "AM",
        noon: "noon",
        pm: "PM",
      },
      day_periods_abbreviated: {
        am: "AM",
        noon: "noon",
        pm: "PM",
      },
      day_periods_narrow: {
        am: "a",
        noon: "n",
        pm: "p",
      },
      quarter_names: ["Q1", "Q2", "Q3", "Q4"],
      quarter_names_abbreviated: ["Q1", "Q2", "Q3", "Q4"],
      quarter_names_narrow: ["1", "2", "3", "4"],
      era_names: ["BCE", "CE"],
      era_names_abbreviated: ["BCE", "CE"],
      era_names_narrow: ["BCE", "CE"],
      full_format: "y MMMM d, EEEE",
      long_format: "y MMMM d",
      medium_format: "y MMM d",
      short_format: "y-MM-dd",
      firstday_of_week: 0,
    };
  }
})();

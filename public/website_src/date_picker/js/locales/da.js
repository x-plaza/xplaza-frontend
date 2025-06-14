(function () {
  "use strict";

  if (typeof Date.dp_locales === "undefined") {
    Date.dp_locales = {
      texts: {
        buttonTitle: "Vælg dato ...",
        buttonLabel:
          "Klik eller tryk på Enter-tasten eller mellemrumstasten for at åbne kalenderen",
        prevButtonLabel: "Gå til forrige måned",
        nextButtonLabel: "Gå til næste måned",
        closeButtonTitle: "Luk",
        closeButtonLabel: "Luk kalenderen",
        prevMonthButtonLabel: "Gå til det foregående år",
        prevYearButtonLabel: "Gå til de foregående 20 år",
        nextMonthButtonLabel: "Gå til næste år",
        nextYearButtonLabel: "Gå til de næste tyve år",
        changeMonthButtonLabel:
          "Klik eller tryk på Enter-tasten eller mellemrumstasten til at ændre måneden",
        changeYearButtonLabel:
          "Klik eller tryk på Enter-tasten eller mellemrumstasten for at ændre året",
        changeRangeButtonLabel:
          "Klik eller tryk på Enter-tasten eller mellemrumstasten til at gå til de næste tyve år",
        calendarHelp:
          "- Pil op og Pil ned - går til den samme ugedag i den forrige eller næste uge hhv. Hvis slutningen af ​​måneden er nået, fortsætter ind i næste eller foregående måned efter omstændighederne.\r\n- Venstre pil og Højre pil - rykker den ene dag til den næste, også i et kontinuum. Visuelt fokus flyttes fra dag til dag og wraps fra række til række i gitteret dage.\r\n- Kontrol + Page Up - Flytter til samme dato i det foregående år.\r\n- Kontrol + Page Down - Flytter til samme dato i det næste år.\r\n- Hjem - Flytter til den første dag i den aktuelle måned.\r\n- End - Flytter til den sidste dag i den aktuelle måned.\r\n- Page Up - Flytter til samme dato i den foregående måned.\r\n- Side Down - Flytter til samme dato i næste måned.\r\n- Indtast eller Espace - lukker kalenderen, og den valgte dato vises i det tilhørende tekstfelt.\r\n- Escape - lukker kalenderen uden nogen handling.",
      },
      directionality: "LTR",
      month_names: [
        "januar",
        "februar",
        "marts",
        "april",
        "maj",
        "juni",
        "juli",
        "august",
        "september",
        "oktober",
        "november",
        "december",
      ],
      month_names_abbreviated: [
        "jan.",
        "feb.",
        "mar.",
        "apr.",
        "maj",
        "jun.",
        "jul.",
        "aug.",
        "sep.",
        "okt.",
        "nov.",
        "dec.",
      ],
      month_names_narrow: [
        "J",
        "F",
        "M",
        "A",
        "M",
        "J",
        "J",
        "A",
        "S",
        "O",
        "N",
        "D",
      ],
      day_names: [
        "søndag",
        "mandag",
        "tirsdag",
        "onsdag",
        "torsdag",
        "fredag",
        "lørdag",
      ],
      day_names_abbreviated: [
        "søn.",
        "man.",
        "tir.",
        "ons.",
        "tor.",
        "fre.",
        "lør.",
      ],
      day_names_short: ["sø", "ma", "ti", "on", "to", "fr", "lø"],
      day_names_narrow: ["S", "M", "T", "O", "T", "F", "L"],
      day_periods: {
        am: "AM",
        noon: "middag",
        pm: "PM",
      },
      day_periods_abbreviated: {
        am: "AM",
        noon: "middag",
        pm: "PM",
      },
      day_periods_narrow: {
        am: "a",
        noon: "middag",
        pm: "p",
      },
      quarter_names: ["1. kvartal", "2. kvartal", "3. kvartal", "4. kvartal"],
      quarter_names_abbreviated: ["1. kvt.", "2. kvt.", "3. kvt.", "4. kvt."],
      quarter_names_narrow: ["1", "2", "3", "4"],
      era_names: ["f.Kr.", "e.Kr."],
      era_names_abbreviated: ["f.Kr.", "e.Kr."],
      era_names_narrow: ["fKr", "eKr"],
      full_format: "EEEE 'den' d. MMMM y",
      long_format: "d. MMMM y",
      medium_format: "d. MMM y",
      short_format: "dd/MM/y",
      firstday_of_week: 0,
    };
  }
})();

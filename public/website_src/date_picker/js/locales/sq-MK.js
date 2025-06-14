(function () {
  "use strict";

  if (typeof Date.dp_locales === "undefined") {
    Date.dp_locales = {
      texts: {
        buttonTitle: "Zgjidh datë ...",
        buttonLabel:
          "Kliko ose shtypni tastin Enter ose spacebar për të hapur kalendarin",
        prevButtonLabel: "Shko tek muaji i kaluar",
        nextButtonLabel: "Shko në muajin e ardhshëm",
        closeButtonTitle: "Afër",
        closeButtonLabel: "Mbylle kalendarin",
        prevMonthButtonLabel: "Shko në vitin e kaluar",
        prevYearButtonLabel: "Shko në njëzet vitet e kaluara",
        nextMonthButtonLabel: "Shko në vitin e ardhshëm",
        nextYearButtonLabel: "Shko në njëzet vitet e ardhshme",
        changeMonthButtonLabel:
          "Kliko ose shtypni tastin Enter ose spacebar për të ndryshuar muajin",
        changeYearButtonLabel:
          "Kliko ose shtypni tastin Enter ose spacebar për të ndryshuar në vitin e",
        changeRangeButtonLabel:
          "Kliko ose shtypni tastin Enter ose spacebar për të shkuar në njëzet vitet e ardhshme",
        calendarHelp:
          "- Up Arrow dhe Down Arrow - shkon në të njëjtën ditë të javës në javën e kaluar ose e ardhshme, respektivisht. Në qoftë se në fund të muajit është arritur, vazhdon në muajin e ardhshëm ose të mëparshëm sipas rastit.\r\n- Left Arrow dhe Djathtas Arrow - përparon një ditë në tjetrën, edhe në një vazhdimësi. Fokusi Vizualisht është lëvizur nga dita në ditë dhe përfundon nga rresht për rresht në rrjetin e ditëve.\r\n- Kontrolli + Page Up - Lëviz në të njëjtën datë në vitin e kaluar.\r\n- Kontrolli + Kryesore poshtë - Lëviz në të njëjtën datë në vitin e ardhshëm.\r\n- Fillimi - Lëviz në ditën e parë të muajit aktual.\r\n- End - Lëviz në ditën e fundit të muajit aktual.\r\n- Page Up - Lëviz në të njëjtën datë në muajin e kaluar.\r\n- Page Down - Lëviz në të njëjtën datë në muajin e ardhshëm.\r\n- Shkruani apo Espace - mbyll kalendarin, dhe data e zgjedhur është treguar në kutinë e lidhur teksti.\r\n- Arratisjes - mbyllet kalendar pa ndonjë veprim.",
      },
      directionality: "LTR",
      month_names: [
        "janar",
        "shkurt",
        "mars",
        "prill",
        "maj",
        "qershor",
        "korrik",
        "gusht",
        "shtator",
        "tetor",
        "nëntor",
        "dhjetor",
      ],
      month_names_abbreviated: [
        "Jan",
        "Shk",
        "Mar",
        "Pri",
        "Maj",
        "Qer",
        "Kor",
        "Gsh",
        "Sht",
        "Tet",
        "Nën",
        "Dhj",
      ],
      month_names_narrow: [
        "J",
        "S",
        "M",
        "P",
        "M",
        "Q",
        "K",
        "G",
        "S",
        "T",
        "N",
        "D",
      ],
      day_names: [
        "e diel",
        "e hënë",
        "e martë",
        "e mërkurë",
        "e enjte",
        "e premte",
        "e shtunë",
      ],
      day_names_abbreviated: ["Die", "Hën", "Mar", "Mër", "Enj", "Pre", "Sht"],
      day_names_short: ["Die", "Hën", "Mar", "Mër", "Enj", "Pre", "Sht"],
      day_names_narrow: ["D", "H", "M", "M", "E", "P", "S"],
      day_periods: {
        am: "paradite",
        noon: "mesditë",
        pm: "pasdite",
      },
      day_periods_abbreviated: {
        am: "paradite",
        noon: "mesditë",
        pm: "pasdite",
      },
      day_periods_narrow: {
        am: "AM",
        noon: "MD",
        pm: "PM",
      },
      quarter_names: [
        "tremujori i parë",
        "tremujori i dytë",
        "tremujori i tretë",
        "tremujori i katërt",
      ],
      quarter_names_abbreviated: [
        "tremujori I",
        "tremujori II",
        "tremujori III",
        "tremujori IV",
      ],
      quarter_names_narrow: ["1", "2", "3", "4"],
      era_names: ["para erës së re", "erës së re"],
      era_names_abbreviated: ["p.e.r.", "e.r."],
      era_names_narrow: ["p.e.r.", "e.r."],
      full_format: "EEEE, d MMMM y",
      long_format: "d MMMM y",
      medium_format: "d MMM y",
      short_format: "d.M.yy",
      firstday_of_week: 1,
    };
  }
})();

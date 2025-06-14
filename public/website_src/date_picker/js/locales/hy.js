(function () {
  "use strict";

  if (typeof Date.dp_locales === "undefined") {
    Date.dp_locales = {
      texts: {
        buttonTitle: "Ընտրել ամսաթիվ ...",
        buttonLabel:
          "Սեղմեք կամ սեղմեք Enter ստեղնը, կամ spacebar է բացել օրացույցը",
        prevButtonLabel: "Գնալ նախորդ ամսվա",
        nextButtonLabel: "Գնալ հաջորդ ամսվա",
        closeButtonTitle: "Սերտ",
        closeButtonLabel: "Փակեք օրացույց",
        prevMonthButtonLabel: "Գնալ դեպի նախորդ տարվա",
        prevYearButtonLabel: "Գնալ դեպի նախորդ քսան տարիների",
        nextMonthButtonLabel: "Գնալ հաջորդ տարվա",
        nextYearButtonLabel: "Գնալ դեպի հաջորդ քսան տարիների",
        changeMonthButtonLabel:
          "Սեղմեք կամ սեղմեք Enter ստեղնը, կամ spacebar է փոխել ամիսը",
        changeYearButtonLabel:
          "Սեղմեք կամ սեղմեք Enter ստեղնը կամ spacebar է փոխել տարին",
        changeRangeButtonLabel:
          "Սեղմեք կամ սեղմեք Enter ստեղնը կամ spacebar է գնալ հաջորդ քսան տարիների",
        calendarHelp:
          "- Մինչեւ Arrow եւ Down Arrow - գնում է նույն օրը շաբաթվա, նախորդ կամ հաջորդ շաբաթ համապատասխանաբար. Եթե ​​վերջը ամսվա հասել, շարունակում է հաջորդ կամ նախորդ ամսվա, ինչպես նաեւ տեղին է.\r\n- Ձախ Arrow եւ աջ Arrow - կանխավճարների մեկ օր, որ հաջորդ, նաեւ մի continuum. Տեսողական կենտրոնում է տեղափոխվել օրեցօր եւ բոլորի ից անընդմեջ տողի ցանցին օր.\r\n- Control + Page Up - Տեղափոխվում է նույն օրը, որ նախորդ տարի.\r\n- Control + Page Down - Տեղափոխվում է նույն օրը, որ հաջորդ տարվա համար:\r\n- Գլխավոր - Տեղափոխվում է առաջին օրը ընթացիկ ամսվա.\r\n- Վերջ - Տեղափոխվում է վերջին օրը ընթացիկ ամսվա.\r\n- Էջ Up - Տեղափոխվում է նույն օրը, որ նախորդ ամսվա.\r\n- Էջ Down - Տեղափոխվում է նույն օրը հաջորդ ամսվա.\r\n- Մուտքագրեք կամ Espace - եզրափակում է օրացույցը, իսկ ընտրված ամսաթիվը, որը ցույց է հարակից տեքստում տուփի.\r\n- Escape - փակում է օրացույց առանց որեւէ գործողության:",
      },
      directionality: "LTR",
      month_names: [
        "հունվարի",
        "փետրվարի",
        "մարտի",
        "ապրիլի",
        "մայիսի",
        "հունիսի",
        "հուլիսի",
        "օգոստոսի",
        "սեպտեմբերի",
        "հոկտեմբերի",
        "նոյեմբերի",
        "դեկտեմբերի",
      ],
      month_names_abbreviated: [
        "հնվ",
        "փտվ",
        "մրտ",
        "ապր",
        "մյս",
        "հնս",
        "հլս",
        "օգս",
        "սեպ",
        "հոկ",
        "նոյ",
        "դեկ",
      ],
      month_names_narrow: [
        "Հ",
        "Փ",
        "Մ",
        "Ա",
        "Մ",
        "Հ",
        "Հ",
        "Օ",
        "Ս",
        "Հ",
        "Ն",
        "Դ",
      ],
      day_names: [
        "կիրակի",
        "երկուշաբթի",
        "երեքշաբթի",
        "չորեքշաբթի",
        "հինգշաբթի",
        "ուրբաթ",
        "շաբաթ",
      ],
      day_names_abbreviated: ["կիր", "երկ", "երք", "չրք", "հնգ", "ուր", "շբթ"],
      day_names_short: ["կիր", "երկ", "երք", "չրք", "հնգ", "ուր", "շբթ"],
      day_names_narrow: ["Կ", "Ե", "Ե", "Չ", "Հ", "Ու", "Շ"],
      day_periods: {
        am: "կեսօրից առաջ",
        noon: "կեսօրին",
        pm: "կեսօրից հետո",
      },
      day_periods_abbreviated: {
        am: "կեսօրից առաջ",
        noon: "կեսօրին",
        pm: "կեսօրից հետո",
      },
      day_periods_narrow: {
        am: "կա",
        noon: "կ",
        pm: "կհ",
      },
      quarter_names: [
        "1-ին եռամսյակ",
        "2-րդ եռամսյակ",
        "3-րդ եռամսյակ",
        "4-րդ եռամսյակ",
      ],
      quarter_names_abbreviated: [
        "1-ին եռմս.",
        "2-րդ եռմս.",
        "3-րդ եռմս.",
        "4-րդ եռմս.",
      ],
      quarter_names_narrow: ["1", "2", "3", "4"],
      era_names: ["մ.թ.ա.", "մ.թ."],
      era_names_abbreviated: ["մ.թ.ա.", "մ.թ."],
      era_names_narrow: ["մ.թ.ա.", "մ.թ."],
      full_format: "yթ. MMMM d, EEEE",
      long_format: "dd MMMM, yթ.",
      medium_format: "dd MMM, yթ.",
      short_format: "dd.MM.yy",
      firstday_of_week: 0,
    };
  }
})();

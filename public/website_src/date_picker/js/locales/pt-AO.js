(function () {
  "use strict";

  if (typeof Date.dp_locales === "undefined") {
    Date.dp_locales = {
      texts: {
        buttonTitle: "Selecionar data ...",
        buttonLabel:
          "Clique ou pressione a tecla Enter ou a barra de espaço para abrir o calendário",
        prevButtonLabel: "Ir para mês anterior",
        nextButtonLabel: "Ir para o próximo mês",
        closeButtonTitle: "Fechar",
        closeButtonLabel: "Feche o calendário",
        prevMonthButtonLabel: "Ir ao ano anterior",
        prevYearButtonLabel: "Ir para os vinte anos anteriores",
        nextMonthButtonLabel: "Vá para o próximo ano",
        nextYearButtonLabel: "Ir para os próximos 20 anos",
        changeMonthButtonLabel:
          "Clique ou pressione a tecla Enter ou a barra de espaço para alterar o mês",
        changeYearButtonLabel:
          "Clique ou pressione a tecla Enter ou a barra de espaço para mudar o ano",
        changeRangeButtonLabel:
          "Clique ou pressione a tecla ou a barra de espaço Enter para ir para os próximos 20 anos",
        calendarHelp:
          "- Seta para cima e Seta para baixo - vai para o mesmo dia da semana na semana anterior ou seguinte, respectivamente. Se o fim do mês é atingido, continua para a próxima ou mês anterior, conforme apropriado.\r\n- Seta para a esquerda e seta para a direita - avança um dia para o outro, também em um continuum. Visualmente foco é movido de dia para dia e envolve a partir de uma linha para outra na grade de dias.\r\n- Control + Page Up - Move para a mesma data do ano anterior.\r\n- Control + Page Down - Move para a mesma data no ano que vem.\r\n- Início - Muda-se para o primeiro dia do mês atual.\r\n- Fim - Move para o último dia do mês atual.\r\n- Page Up - Move para a mesma data no mês anterior.\r\n- Page Down - Move para a mesma data no próximo mês.\r\n- Introduzir ou Espace - fecha o calendário ea data selecionada é mostrada na caixa de texto associado.\r\n- Escape - fecha o calendário sem qualquer ação.",
      },
      directionality: "LTR",
      month_names: [
        "janeiro",
        "fevereiro",
        "março",
        "abril",
        "maio",
        "junho",
        "julho",
        "agosto",
        "setembro",
        "outubro",
        "novembro",
        "dezembro",
      ],
      month_names_abbreviated: [
        "jan",
        "fev",
        "mar",
        "abr",
        "mai",
        "jun",
        "jul",
        "ago",
        "set",
        "out",
        "nov",
        "dez",
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
        "domingo",
        "segunda-feira",
        "terça-feira",
        "quarta-feira",
        "quinta-feira",
        "sexta-feira",
        "sábado",
      ],
      day_names_abbreviated: ["dom", "seg", "ter", "qua", "qui", "sex", "sáb"],
      day_names_short: ["dom", "seg", "ter", "qua", "qui", "sex", "sáb"],
      day_names_narrow: ["D", "S", "T", "Q", "Q", "S", "S"],
      day_periods: {
        afternoon: "tarde",
        am: "da manhã",
        morning: "manhã",
        night: "noite",
        noon: "meio-dia",
        pm: "da tarde",
      },
      day_periods_abbreviated: {
        afternoon: "tarde",
        am: "a.m.",
        morning: "manhã",
        night: "noite",
        noon: "meio-dia",
        pm: "p.m.",
      },
      day_periods_narrow: {
        am: "a.m.",
        noon: "m.d.",
        pm: "p.m.",
      },
      quarter_names: [
        "1.º trimestre",
        "2.º trimestre",
        "3.º trimestre",
        "4.º trimestre",
      ],
      quarter_names_abbreviated: ["T1", "T2", "T3", "T4"],
      quarter_names_narrow: ["1", "2", "3", "4"],
      era_names: ["antes de Cristo", "depois de Cristo"],
      era_names_abbreviated: ["a.C.", "d.C."],
      era_names_narrow: ["a.C.", "d.C."],
      full_format: "EEEE, d 'de' MMMM 'de' y",
      long_format: "d 'de' MMMM 'de' y",
      medium_format: "dd/MM/y",
      short_format: "dd/MM/yy",
      firstday_of_week: 1,
    };
  }
})();

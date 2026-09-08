var optionsProfileVisit = {
  annotations: {
    position: "back",
  },
  dataLabels: {
    enabled: true,
    formatter: function (val) {
      return val;
    },
    offsetY: -20,
    style: {
      fontSize: '12px',
      colors: ["#304758"]
    }
  },
  chart: {
    type: "bar",
    height: 300,
  },
  fill: {
    opacity: 1,
  },
  plotOptions: {
    bar: {
      borderRadius: 10,
      dataLabels: {
        position: 'top', // Coloca os dados no topo das barras
      },
    }
  },
  series: [
    {
      name: "Casos",
      data: casosPorMes,
    },
  ],
  colors: "#435ebe",
  xaxis: {
    categories: meses,
    position: 'top',
    labels: {
      offsetY: -18,
    },
    axisBorder: {
      show: false
    },
    axisTicks: {
      show: false
    },
    crosshairs: {
      fill: {
        type: 'gradient',
        gradient: {
          colorFrom: '#D8E3F0',
          colorTo: '#BED1E6',
          stops: [0, 100],
          opacityFrom: 0.4,
          opacityTo: 0.5,
        }
      }
    },
    tooltip: {
      enabled: true,
      offsetY: -35,
    }
  },
  yaxis: {
    labels: {
      show: false,
    }
  },
  title: {
    text: 'Casos de Denúncias por Mês',
    floating: true,
    offsetY: 320,
    align: 'center',
    style: {
      color: '#444'
    }
  }
};

var chartProfileVisit = new ApexCharts(
  document.querySelector("#chart-profile-visit"),
  optionsProfileVisit
);

chartProfileVisit.render();

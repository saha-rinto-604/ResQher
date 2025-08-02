

/* Line chart -> Media start */
if ($("#line-adwords").length > 0) {
  var optionsLine = {
    chart: {
      height: 280,
      width: "100%",
      type: "line",
      zoom: {
        enabled: false,
      },
      dropShadow: {
        enabled: false, //false
        top: 3,
        left: 2,
        blur: 4,
        opacity: 1,
      },

    },
    stroke: {
      curve: "smooth",
      width: 2,
    },
    legend: {
      position: "bottom",
      horizontalAlign: "center",
      offsetY: 8,
    },

    series: [
      {
        name: "Earning",
        data: [30, 40, 60, 90, 60, 27, 20, 20, 30, 60, 80, 90],
      },
      {
        name: "New Customer",
        data: [70, 50, 80, 80, 100, 32, 20, 80, 0, 50, 80, 30],
      },
      {
        name: "Sale Product",
        data: [40, 35, 70, 70, 29, 43, 20, 60, 10, 60, 80, 30],
      },
    ],
    markers: {
      size: 6,
      strokeWidth: 0,
      hover: {
        size: 9,
      },
    },
    grid: {
      show: true,
      padding: {
        bottom: 0,
      },
    },
    labels: [
      "Jan",
      "Feb",
      "mar",
      "Apr",
      "May",
      "jun",
      "jul",
      "aug",
      "Sep",
      "oct",
      "Nov",
      "Dec",
    ],

    xaxis: {
      tooltip: {
        enabled: false,
      },
      axisBorder: {
        color: 'var(--primary-border)', // Use the custom property
        show: true,
        height: 1, // Set the border height
        width: '100%', // Set the border width
        offsetX: 0,
        offsetY: 0,
        style: {
          colors: 'var(--primary-border)', // Use the custom property
          borderType: 'dotted', // Set the border style to dotted
        },
      },
    }
  };
  var chartLine = new ApexCharts(
    document.querySelector("#line-adwords"),
    optionsLine
  );
  chartLine.render();
}
/* Line chart -> Media end */


/* Area Chart1 s t a r t */
if ($("#areaChart1").length > 0) {
  var optionsArea = {

    chart: {
      height: 120,
      type: 'area',
    },
    toolbar: {
      show: false
    },
    series: [{
      name: 'series1',
      data: [20, 50, 60, 100, 60, 90, 150],
    }],

    dataLabels: {
      enabled: false
    },

    stroke: {
      curve: 'smooth',
      width: 2,
      colors: ['#0CAF60']  // Line stroke color
    },
    colors: ["#0CAF60", "#0CAF60"],
    fill: {
      type: "gradient",
      gradient: {
        shadeIntensity: 1,
        opacityFrom: .2,
        opacityTo: .9,
        //   stops: [0, 90, 100]
      }
    },


    xaxis: {
      show: false,
      type: '',
      categories: [""],

      labels: {
        show: false
      },
      axisBorder: {
        show: false,
      },
      axisTicks: {
        show: false
      },
      lines: {
        show: false
      }
    },
    yaxis: {
      show: false,

    },
    grid: {
      show: false, // Hide horizontal grid lines
    }


  };
  var chart = new ApexCharts(document.querySelector("#areaChart1"), optionsArea);
  chart.render();
}
/* / Area Chart1 start */


/* Barchart Chart1 s t a r t */
if ($("#barchart").length > 0) {
  var optionsBar = {
    chart: {
      height: 400,
      type: 'bar',
      stacked: true,
    },
    dataLabels: {
      enabled: false // line series number hide
    },
    plotOptions: {
      bar: {
        columnWidth: '12%',
        horizontal: false,
        borderRadius: 2, // Adjust the radius as needed
        dataLabels: {
          enabled: false, // Hide series number labels
        },
      },
    },
    series: [{
      name: 'PRODUCT A',
      data: [14, 25, 21, 17, 12, 13, 11, 19]
    }, {
      name: 'PRODUCT B',
      data: [13, 23, 20, 8, 13, 27, 33, 12]
    }, {
      name: 'PRODUCT C',
      data: [11, 17, 15, 15, 21, 14, 15, 13]
    }],
    xaxis: {
      categories: ['Sat', 'Sun', 'Mon', 'Tue', 'Tue', 'Wed', 'Thu', 'Fri'],
    },
    fill: {
      opacity: 1
    },
    colors: ["#0CAF60", "#FA5F1C", "#5051F9",],

    tooltip: {
      enabled: false, // Disable tooltips by default
    },

  }
  var chartBar = new ApexCharts(
    document.querySelector("#barchart"),
    optionsBar
  );
  chartBar.render();
}
// / Barchart


// MultipleAxis
if ($("#multipleAxis").length > 0) {
  var options = {
    colors: ["#ced", "var(--primary)", "#e2e2e2",],
    series: [{
      name: 'Net Profit',
      data: [2531, 6743, 4825, 8561, 3932, 7204, 6017, 2946, 4820, 7312, 5879, 8004]
  }, {
      name: 'Revenue',
      data: [3500, 5400, 6400, 7200, 8500, 9200, 8000, 6000, 4500, 3100, 7700, 5500]
  }, {
      name: 'Free Cash Flow',
      data: [4900, 6100, 7200, 5400, 4300, 5800, 6900, 3300, 2600, 7100, 4800, 6200]
  }],
  
    chart: {
      type: 'bar',
      height: 350
    },
    plotOptions: {
      bar: {
        horizontal: false,
        columnWidth: '55%',
        endingShape: 'rounded'
      },
    },
    dataLabels: {
      enabled: false
    },
    stroke: {
      show: true,
      width: 2,
      colors: ['transparent']
    },
    xaxis: {
      categories: ['jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'nov', 'dec'],
    },
    yaxis: {
      title: {
        text: '$ (thousands)'
      }
    },
    fill: {
      opacity: 1
    },
    tooltip: {
      y: {
        formatter: function (val) {
          return "$ " + val + " thousands"
        }
      }
    }
  };

  var chart = new ApexCharts(document.querySelector("#multipleAxis"), options);
  chart.render();

}
// / multipleAxis
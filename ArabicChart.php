function create_chart($_atts)
{
    ob_start();
    $monthRates = readHistoryFile();
       $histroyRecords = count($monthRates);
   
       $QAR = 1;
       $result = 0.25;
       $defaults = array(
           'karat' => '',
           'currency' => '',
           'unit' => '',
       );
       $atts = shortcode_atts($defaults, $_atts);
       $currency = trim($atts['currency']);
       $unitValue = calculateUnit($atts['unit']);
       $currencyUpper = strtoupper($atts['currency']);
   
       $USPrice = oneGramGoldRateInDollar();
       $QAR = getCurrencyValue($currency);
       
        if ($currency == "usd")
       $QAR = 1;
       
   
       $finalRates = array();
       $finalRates22K = array();
       $finalRates18K = array();
       $finalRates21K = array();
       
       for ($x = 0; $x < $histroyRecords; $x++) {
           $result = (float) $monthRates[$x] * (float) $QAR *  (float) $unitValue;
           $rate22K = (float) $monthRates[$x] * (float) $QAR * (float) (22 / 24) * (float) $unitValue;
           $rate18K = (float) $monthRates[$x] * (float) $QAR * (float) (18 / 24) * (float) $unitValue;
           $rate21K = (float) $monthRates[$x] * (float) $QAR * (float) (21 / 24) * (float) $unitValue;
           array_push($finalRates, $result);
           array_push($finalRates22K, $rate22K);
           array_push($finalRates18K, $rate18K);
           array_push($finalRates21K, $rate21K);
       }
     //   $finalRates = array_reverse($finalRates);
       $finalCount = count($finalRates);
       $label = "عيار 24 في 7 أيام";
       $label21 = "عيار 21 في 7 أيام";
       $label22 = "عيار 22 في 7 أيام";
       $label18 = "عيار 18 في 7 أيام";
   
       
   
       $datesFromToday = getPreviousDatesExcludingWeekends($finalCount);
   
       ?>
       <style>
           <?php include 'css/history_chart.css'; ?>
       </style>
       <div class="chart-container" style="position: relative; height:60vh;">
           <canvas id="myChart"></canvas>
       </div>
       <script src="https://cdn.jsdelivr.net/npm/chart.js@latest/dist/chart.umd.js"></script>
       <script>
           function fetchData(days) {
                   let isAvailable = true;
                   const endDate = new Date();
                   const startDate = new Date();
                   startDate.setDate(endDate.getDate() - days);
   
   
                   let counter = 0 ;
   
                   const dates = [];
                   const rates = [];
                   for (let d = startDate; d <= endDate; d.setDate(d.getDate() + 1)) {
                       counter++;
                       if (d.getDay() !== 0 )  { // Exclude Sundays
                    
                           dates.push(d.toISOString().split('T')[0]);
                
                           rates.push(100); // Replace with actual rates fetching logic
                   
                          }
                  
                   }
                     dates.reverse();
              
   
                   return {dates, rates};
               }
           let sevendays = fetchData(8);
           
           document.addEventListener('DOMContentLoaded', (event) => {
               const ctx = document.getElementById('myChart').getContext('2d');
               const myChart = new Chart(ctx, {
                   type: 'line',
                   data: {
                       labels: sevendays.dates,
                       datasets: [{
                           label: <?php echo json_encode($label) ?>,
                           data: <?php echo json_encode(array_slice($finalRates, 0, 8)) ?> ,
                           backgroundColor: "#fbfbfb",
                           borderColor: "#085703",
                       },
                       {
                           label: <?php echo json_encode($label22) ?>,
                           data: <?php echo json_encode(array_slice($finalRates22K, 0, 8)) ?> ,
                           backgroundColor: "#fbfbfb",
                           borderColor: "#eb3d34",
                       },
                         {
                           label: <?php echo json_encode($label21) ?>,
                           data: <?php echo json_encode(array_slice($finalRates21K, 0, 8)) ?> ,
                           backgroundColor: "#fbfbfb",
                           borderColor: "#010001",
                       },
                       {
                           label: <?php echo json_encode($label18) ?>,
                           data: <?php echo json_encode(array_slice($finalRates18K, 0, 8)) ?> ,
                           backgroundColor: "#fbfbfb",
                           borderColor: "#4034eb",
                       }
                     ]
                   },
                   options: {
                       maintainAspectRatio: false,
                       responsive: true,
                       interaction: {
                           intersect: false,
                           axis: 'x'
                       },
                       plugins: {
                           title: {
                               display: true,
                           },
                           legend: {
                       display: true,
                       position: 'top',
                       align: 'center',
                       rtl: true,
                   },
                       }
                   }
               });
   
               function updateChartData(labelData, dateData, ratesData) {
                   myChart.data.labels = dateData;
                   label = "عيار 24 في 7 أيام";
                       label21 = "عيار 21 في 7 أيام";
                       label22 = "عيار 22 في 7 أيام";
                       label18 = "عيار 18 في 7 أيام";
                   RatesData24k =  <?php echo json_encode(array_slice($finalRates, 0, 8)) ?> ;
                   RatesData22k =  <?php echo json_encode(array_slice($finalRates22K, 0, 8)) ?> ;
                   RatesData21k =  <?php echo json_encode(array_slice($finalRates21K, 0, 8)) ?> ;
                   RatesData18k =  <?php echo json_encode(array_slice($finalRates18K, 0, 8)) ?> ;
                   
   
                   if (labelData === 8)
                   {
                       label = "عيار 24 في 7 أيام";
                       label21 = "عيار 21 في 7 أيام";
                       label22 = "عيار 22 في 7 أيام";
                       label18 = "عيار 18 في 7 أيام";
           
                   }
                   else if (labelData === 184)
                   {
                       RatesData24k =  <?php echo json_encode(array_slice($finalRates, 0, 184)) ?> ;
                   RatesData22k =  <?php echo json_encode(array_slice($finalRates22K, 0, 184)) ?> ;
                   RatesData21k =  <?php echo json_encode(array_slice($finalRates21K, 0, 184)) ?> ;
                   RatesData18k =  <?php echo json_encode(array_slice($finalRates18K, 0, 184)) ?> ;
                       label = "عيار 24 في 6 أشهر";
                       label21 = "عيار 21 في 6 أشهر";
                       label22 = "عيار 22 في 6 أشهر";
                       label18 = "عيار 18 في 6 أشهر";
           
                   }
                   else if (labelData === 366)
                   {
                       RatesData24k =  <?php echo json_encode(array_slice($finalRates, 0, 366)) ?> ;
                   RatesData22k =  <?php echo json_encode(array_slice($finalRates22K, 0, 366)) ?> ;
                   RatesData21k =  <?php echo json_encode(array_slice($finalRates21K, 0, 366)) ?> ;
                   RatesData18k =  <?php echo json_encode(array_slice($finalRates18K, 0, 366)) ?> ;
                       label = "عيار 24 في سنة واحدة";
                       label21 = "عيار 21 في سنة واحدة";
                       label22 = "عيار 22 في سنة واحدة";
                       label18 = "عيار 18 في سنة واحدة";
                      
           
                   }
                   else if (labelData === 731)
                   {
                       label = "عيار 24 في عامين";
                       label21 = "عيار 21 في عامين";
                       label22 = "عيار 22 في عامين";
                       label18 = "عيار 18 في عامين";
                       RatesData24k =  <?php echo json_encode(array_slice($finalRates, 0, 731)) ?> ;
                   RatesData22k =  <?php echo json_encode(array_slice($finalRates22K, 0, 731)) ?> ;
                   RatesData21k =  <?php echo json_encode(array_slice($finalRates21K, 0, 731)) ?> ;
                   RatesData18k =  <?php echo json_encode(array_slice($finalRates18K, 0, 731)) ?> ;
                      
           
                   }
                   else if (labelData === 1096)
                   {
                       label = "عيار 24 في 3 سنوات";
                       label21 = "عيار 21 في 3 سنوات";  
                       label22 = "عيار 22 في 3 سنوات";
                       label18 = "عيار 18 في 3 سنوات";
                       RatesData24k =  <?php echo json_encode(array_slice($finalRates, 0, 1096)) ?> ;
                   RatesData22k =  <?php echo json_encode(array_slice($finalRates22K, 0, 1096)) ?> ;
                   RatesData21k =  <?php echo json_encode(array_slice($finalRates21K, 0, 1096)) ?> ;
                   RatesData18k =  <?php echo json_encode(array_slice($finalRates18K, 0, 1096)) ?> ;
           
                   }
                    else if (labelData === 31)
                   {
                       RatesData24k =  <?php echo json_encode(array_slice($finalRates, 0, 31)) ?> ;
                   RatesData22k =  <?php echo json_encode(array_slice($finalRates22K, 0, 31)) ?> ;
                   RatesData21k =  <?php echo json_encode(array_slice($finalRates21K, 0, 31)) ?> ;
                   RatesData18k =  <?php echo json_encode(array_slice($finalRates18K, 0, 31)) ?> ;
                         label = "عيار 24 في  30 يوما";
                       label21 = "عيار 21 في 30 يوما";
                       label22 = "عيار 22 في 30 يوما";
                       label18 = "عيار 18 في 30 يوما";
           
                   }
                     else if (labelData === 90)
                   {
                       RatesData24k =  <?php echo json_encode(array_slice($finalRates, 0, 90)) ?> ;
                   RatesData22k =  <?php echo json_encode(array_slice($finalRates22K, 0, 90)) ?> ;
                   RatesData21k =  <?php echo json_encode(array_slice($finalRates21K, 0, 90)) ?> ;
                   RatesData18k =  <?php echo json_encode(array_slice($finalRates18K, 0, 90)) ?> ;
                            label = "عيار 24 في 3 أشهر";
                       label21 = "عيار 21 في 3 أشهر";
                       label22 = "عيار 22 في 3 أشهر";
                       label18 = "عيار 18 في 3 أشهر";
           
                   }
                   else 
                   {
                       label = "عيار 24 في 4 سنوات";
                       label21 = "عيار 21 في 4 سنوات";  
                       label22 = "عيار 22 في 4 سنوات";
                       label18 = "عيار 18 في 4 سنوات";
                       RatesData24k =  <?php echo json_encode($finalRates) ?> ;
                   RatesData22k =  <?php echo json_encode($finalRates22K) ?> ;
                   RatesData21k =  <?php echo json_encode($finalRates21K) ?> ;
                   RatesData18k =  <?php echo json_encode($finalRates18K) ?> ;
                      
           
                   }
   
                   myChart.data.datasets = [{
                       label: label,
                       data: RatesData24k,
                       backgroundColor: "#fbfbfb",
                           borderColor: "#085703",
                   },
                   {
                       label: label22,
                       data: RatesData22k,
                       backgroundColor: "#fbfbfb",
                           borderColor: "#eb3d34",
                   },
                   
                   {
                       label: label21,
                       data: RatesData21k,
                       backgroundColor: "#fbfbfb",
                           borderColor: "#010001",
                   },
                   {
                       label: label18,
                       data: RatesData18k,
                       backgroundColor: "#fbfbfb",
                           borderColor: "#4034eb",
                   }];
                   myChart.update();
               }
   
          
   
               document.getElementById('days7').addEventListener('click', () => {
                   const data = fetchData(7);
                   data.rates =  <?php echo json_encode(array_slice($finalRates, 0, 8)) ?> ;
                   updateChartData(8, data.dates, data.rates);
               });
   
               document.getElementById('months6').addEventListener('click', () => {
                   const data = fetchData(183);
                   data.rates =  <?php echo json_encode(array_slice($finalRates, 0, 184)) ?> ;
                   updateChartData(184, data.dates, data.rates);
               });
   
               document.getElementById('year1').addEventListener('click', () => {
                   const data = fetchData(365);
                   data.rates =  <?php echo json_encode(array_slice($finalRates, 0, 366)) ?> ;
                   updateChartData(366, data.dates, data.rates);
               });
   
               document.getElementById('year2').addEventListener('click', () => {
                   const data = fetchData(730);
                   data.rates =  <?php echo json_encode(array_slice($finalRates, 0, 731)) ?> ;
                   updateChartData(731, data.dates, data.rates);
               });
   
               document.getElementById('year3').addEventListener('click', () => {
                   const data = fetchData(1095);
                   data.rates =  <?php echo json_encode(array_slice($finalRates, 0, 1096)) ?> ;
                   updateChartData(1096, data.dates, data.rates);
               });
   
               document.getElementById('year4').addEventListener('click', () => {
                   const data = fetchData(1460);
                   data.rates =   <?php echo json_encode($finalRates) ?> ;
                   updateChartData(1460, data.dates, data.rates);
               });
                document.getElementById('month1').addEventListener('click', () => {
                   const data = fetchData(30);
                   data.rates =  <?php echo json_encode(array_slice($finalRates, 0, 31)) ?> ;
                   updateChartData(31, data.dates, data.rates);
               });
                 document.getElementById('months3').addEventListener('click', () => {
                   const data = fetchData(90);
                   data.rates =  <?php echo json_encode(array_slice($finalRates, 0, 91)) ?> ;
                   updateChartData(90, data.dates, data.rates);
               });
           });
       </script>
       <div id="historycontainer">
           <button id="days7">7 أيام</button>
           <button id="month1">30 يوما </button>
           <button id="months3">3 أشهر</button>
           <button id="months6">6 أشهر</button>
           <button id="year1">1 سنة</button>
           <button id="year2">2 سنين</button>
           <button id="year3">3 سنين</button>
           <button id="year4">4 سنين</button>
       </div>
       <?php
       return ob_get_clean();
}
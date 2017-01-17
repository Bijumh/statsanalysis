<!DOCTYPE html>
<html> 
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
        <meta name="author" content="Biju Maharjan">
        <meta name="description" content="Stats Analysis">
        <meta name="date" content="January 13, 2017">   

        <script type="text/javascript" src="lib/dhtmlx/codebase/dhtmlx.js" ></script>
        <link rel="stylesheet" type="text/css" href="lib/dhtmlx/codebase/dhtmlx.css">
        <link rel="stylesheet" type="text/css" href="generics/style.css">
    </head>

    <body onload="doOnLoad();">
        <div id="containter" style="width:100%; height:100%;"></div>
    </body>
</html>

<script type="text/javascript">
    
    /*
     * [onLoad Function]
     */
    doOnLoad = function () {
		loadTabbar();
		loadPublisherReport();
        loadDayReport();
        loadPeformanceByDay();
    }
    
    /*
     * [Created the tabbar]
     */
    loadTabbar = function() {
		statsAnalysisTabbarObj = new dhtmlXTabBar("containter");
		statsAnalysisTabbarObj.addTab("t1", "Overall Publisher Report", null, null, true);
		statsAnalysisTabbarObj.addTab("t2", "30 Days Report");
		statsAnalysisTabbarObj.addTab("t3", "Performance by Day Graph");
	}
    
    /*------------------ Publisher Report Start --------------- */
    
    /*
     * [Creates the Grid for the publisher report]
     */
    loadPublisherReport = function() {
		pubisherReportGridObj = statsAnalysisTabbarObj.tabs("t1").attachGrid();
		pubisherReportGridObj.setImagePath("lib/dhtmlx/codebase/imgs/");                 
        pubisherReportGridObj.setHeader("Publisher,Impressions,Conversions,Conversion Rate",null,["text-align:center;","text-align:center;","text-align:center","text-align:center"]);
        pubisherReportGridObj.attachHeader("#text_filter,#text_filter,#text_filter,#text_filter");
		pubisherReportGridObj.setInitWidths("300,200,200,299");
		pubisherReportGridObj.setColAlign("left,right,right,right");
		pubisherReportGridObj.setColTypes("ro,ro,ro,ro");
		pubisherReportGridObj.setColSorting("str,int,int,int");
		pubisherReportGridObj.init();
		
		getPublisherReportData();
    }

    /*
     * [Loads the data in the publisher grid]
     */
	getPublisherReportData = function() {
		var sqlString = 'CALL PublisherReport()';
		var loadURL = "generics/dataCollector.php?callFrom=Grid&sql=" + sqlString;
        
		pubisherReportGridObj.clearAll();
		pubisherReportGridObj.load(loadURL);
		
	}
    
    /*------------------ Publisher Report End --------------- */
    
    
    /*-------------------- Day Report Start ----------------- */
    
    /*
     * [Creates the filter and Grid for the day report]
     */
    loadDayReport = function() {
		//Creating the Layout
		loadDayReportLayout = statsAnalysisTabbarObj.tabs("t2").attachLayout({
										pattern: "2E",
										cells: [{id: "a", text: "Filter", height: 100},
												{id: "b", text: "Report"}]
									});
		
		//Creating the filter
		var filterFormJSON = [
                                {type: "settings", position: "label-left", labelWidth: 70, inputWidth: 150, offsetLeft: 15, offsetTop: 5, position: "label-top"},
                                {type: "calendar", dateFormat: "%Y-%m-%d", name: "startDate", label: "Start Date", value:"2016-05-01", calendarPosition: "bottom", validate: "NotEmpty", required: "true"},
                                {type: "newcolumn"},
                                {type: "calendar", dateFormat: "%Y-%m-%d", name: "endDate", label: "End Date", value: "2016-05-31", calendarPosition: "bottom", validate: "NotEmpty", required: "true"},
                                {type: "newcolumn"},
                                {type: "button", name:"okButton", value: "Ok", offsetTop:25}
                            ];
		daysReportFilterObj = loadDayReportLayout.cells("a").attachForm(filterFormJSON);
        daysReportFilterObj.enableLiveValidation(true);
		
        daysReportFilterObj.attachEvent("onButtonClick", function(name){
            if (name == 'okButton') 
                dayReportFilterBtnClick();
        });
		
		//Creating the grid for report
		daysReportGridObj = loadDayReportLayout.cells("b").attachGrid();
		daysReportGridObj.setImagePath("lib/dhtmlx/codebase/imgs/");                 
        daysReportGridObj.setHeader("Day,Impressions,Conversions,Conversion Rate",null,["text-align:center;","text-align:center;","text-align:center","text-align:center"]);
        daysReportGridObj.attachHeader("#text_filter,#text_filter,#text_filter,#text_filter");
		daysReportGridObj.setInitWidths("300,200,200,299");
		daysReportGridObj.setColAlign("left,right,right,right");
		daysReportGridObj.setColTypes("ro,ro,ro,ro");
		daysReportGridObj.setColSorting("str,int,int,int");
		daysReportGridObj.init();
    }
    
    /*
     * [Grab the  filter input and validate]
     */
    dayReportFilterBtnClick = function() {
        var validateStatus = daysReportFilterObj.validate();
                
        if (validateStatus == true) {
            var startDate = daysReportFilterObj.getItemValue('startDate',true);
            var endDate = daysReportFilterObj.getItemValue('endDate',true);
			
			if (startDate > endDate) {
				dhtmlx.message({
					title: "Alert!!!",
					type: "alert",
					text: "Start Date should not be greater than End Date!"
				});
				return;
			}
            getDayReportData(startDate,endDate);
        }
    }
    
    /*
     * [Load the data in Day Report grid]
     */
    getDayReportData = function(startDate,endDate) {
        var sqlString = 'CALL DaysReport("' + startDate + '","' + endDate + '")';
		var loadURL = "generics/dataCollector.php?callFrom=Grid&sql=" + sqlString;
        
        daysReportGridObj.clearAll();
		daysReportGridObj.load(loadURL);
    }
    
    /*-------------------- Day Report End ----------------- */
    
    
    /*--------------- Performance By Day Report Start -------------- */
    
    /*
     * [Create the filter, grid and graph]
     */
    loadPeformanceByDay = function() {
        //Creating the layout
		peformanceByDaysLayout = statsAnalysisTabbarObj.tabs("t3").attachLayout({
										pattern: "3E",
										cells: [{id: "a", text: "Filter", height: 100},
												{id: "b", text: "Report", collapse: true},
												{id: "c", text: "Graph"}]
									});
        
        //Creating filter form
        var filterFormJSON = [
                                {type: "settings", position: "label-left", labelWidth: 70, inputWidth: 150, offsetLeft: 15, offsetTop: 5, position: "label-top"},
                                {type: "calendar", dateFormat: "%Y-%m-%d", name: "startDate", label: "Start Date", value:"2016-05-01", calendarPosition: "bottom", validate: "NotEmpty", required: "true"},
                                {type: "newcolumn"},
                                {type: "calendar", dateFormat: "%Y-%m-%d", name: "endDate", label: "End Date", value: "2016-05-31", calendarPosition: "bottom", validate: "NotEmpty", required: "true"},
                                {type: "newcolumn"},
                                {type: "combo", name: "country", label: "Country", "options": "", filtering:"true"},
                                {type: "newcolumn"},
                                {type: "combo", name: "publisher", label: "Publisher", "options": "", filtering:"true"},
                                {type: "newcolumn"},
                                {type: "button", name:"okButton", value: "Ok", offsetTop:25}
                            ];
        
		performanceByDayFilterObj = peformanceByDaysLayout.cells("a").attachForm(filterFormJSON);
        performanceByDayFilterObj.enableLiveValidation(true);
        
        loadCountryCombo();
        loadPublisherCombo();
		
        performanceByDayFilterObj.attachEvent("onButtonClick", function(name){
            if (name == 'okButton') 
                performanceByDaytFilterBtnClick();
        });
        
        loadPeformanceByDayReport();
        loadPerformanceByDayChart();
	}
    
    /*
     * [Load the data in country combo]
     */
    loadCountryCombo = function() {
        var countryCmbObj = performanceByDayFilterObj.getCombo('country');
        var sqlString = 'CALL getCountry()';
        var cmbURL = "generics/dataCollector.php?callFrom=Combo&sql=" + sqlString;
        
		countryCmbObj.load(cmbURL);
    }
    
    /*
     * [Load the data in publisher combo]
     */
    loadPublisherCombo = function() {
        var publisherCmbObj = performanceByDayFilterObj.getCombo('publisher');
        var sqlString = 'CALL getPublisher()';
        var cmbURL = "generics/dataCollector.php?callFrom=Combo&sql=" + sqlString;
        
        publisherCmbObj.load(cmbURL);
    }
    
    /*
     * [Create the grid for the performance by day report]
     */
    loadPeformanceByDayReport = function() {
        peformanceByDaysGridObj = peformanceByDaysLayout.cells("b").attachGrid();
		peformanceByDaysGridObj.setImagePath("lib/dhtmlx/codebase/imgs/");                 
        peformanceByDaysGridObj.setHeader("Day,Android %,iPad %,iPhone %",null,["text-align:center;","text-align:center;","text-align:center","text-align:center"]);
        peformanceByDaysGridObj.attachHeader("#text_filter,#text_filter,#text_filter,#text_filter");
        peformanceByDaysGridObj.setInitWidths("300,200,200,299");
		peformanceByDaysGridObj.setColAlign("left,right,right,right");
		peformanceByDaysGridObj.setColTypes("ro,ro,ro,ro");
		peformanceByDaysGridObj.setColSorting("str,int,int,int");
		peformanceByDaysGridObj.init();
    }
    
    /*
     * [Grab the filter input and validate]
     */
    performanceByDaytFilterBtnClick = function() {
        var validateStatus = performanceByDayFilterObj.validate();
                
        if (validateStatus == true) {
            var startDate = performanceByDayFilterObj.getItemValue('startDate',true);
            var endDate = performanceByDayFilterObj.getItemValue('endDate',true);
            
			if (startDate > endDate) {
				dhtmlx.message({
					title: "Alert!!!",
					type: "alert",
					text: "Start Date should not be greater than End Date!"
				});
				return;
			}
			
            var country_obj = performanceByDayFilterObj.getCombo('country');
            var country = country_obj.getSelectedValue();
            if (country == null) country = '';
            
            var publisher_obj = performanceByDayFilterObj.getCombo('publisher');
            var publisher = publisher_obj.getSelectedValue();
            if (publisher == null || publisher == '') publisher = 0;
            
            getPerformanceByDayData(startDate,endDate,country,publisher);
        }
    }
    
    /*
     * [Load the data in performance by dat grid]
     */
    getPerformanceByDayData = function(startDate,endDate,country,publisher) {
        var sqlString = 'CALL PerformanceByDay("' + startDate + '","' + endDate + '","' + country + '",' + publisher + ')';
        var loadURL = "generics/dataCollector.php?callFrom=Grid&sql=" + sqlString;
        
        peformanceByDaysGridObj.clearAll();
		peformanceByDaysGridObj.load(loadURL, function(){
            //Loading data in the chart graph
            loadPerformanceByDayChart.clearAll();
            loadPerformanceByDayChart.parse(peformanceByDaysGridObj,"dhtmlxgrid");
            peformanceByDaysLayout.cells('b').collapse();
        });
    }
    
    /*
     * [Create the chart graph]
     */
    loadPerformanceByDayChart = function() {
        loadPerformanceByDayChart = peformanceByDaysLayout.cells("c").attachChart({
            view:"line",  
            value:"#data1#",
            color:"#36abee",
            padding:{left:75, bottom:60, top:50, right:30},
            yAxis:{start:0, step:5, title:"Performance"},
            xAxis:{                                        
                template:function(obj){ return "<span class='quarter'>" + obj.data0 + "</span>" }
            },
            preset:"column",                               
            border:0,
            legend:{
                values:[{text:"Android"},{text:"iPad"},{text:"iPhone"}],
                align:"right",
                valign:"middle",
                layout:"y",
                width: 100,
                margin: 8,
                marker:{
                    type: "item"
                }
            }
        });
        
        loadPerformanceByDayChart.addSeries({
            value:"#data2#",
            item:{
                borderColor: "#0a796a",
                color: "#4aa397",
                type:"s"
            },
            line:{color:"#4aa397"},
            tooltip:{
                template:function(obj){ return "<span class='quarter'>" + obj.data0 + "</span>" }
            }
        });  
        
        loadPerformanceByDayChart.addSeries({
            value:"#data3#",
            item:{
                borderColor: "#b7286c",
                color: "#de619c",
                type:"t"
            },
            line:{color:"#de619c"},
            tooltip:{
                template:function(obj){ return "<span class='quarter'>" + obj.data0 + "</span>" }
            }
        }); 
    }
    
    /*--------------- Performance By Day Report END -------------- */
</script>


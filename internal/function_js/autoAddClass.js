function autoAddClass(majorNo){
	$.get("function_php/autoAddClass.php", { planner : planner, majorNo : majorNo }, function(data){
		var autoAdd = JSON.parse(data);
		console.log(autoAdd);
		var semKeys = []; for(var k in autoAdd) semKeys.push(k);
		for(var i = 0; i < semKeys.length; i++)
		{
			var classKeys = []; for(var k in autoAdd[semKeys[i]]) classKeys.push(k);
			if(classKeys.length == 0) continue;
			for(var j = 0; j < classKeys.length; j++){
				autoAdd[semKeys[i]][classKeys[j]][8] = (autoAdd[semKeys[i]][classKeys[j]][8].length > 25)? autoAdd[semKeys[i]][classKeys[j]][8].substring(0, 24)+"..." : autoAdd[semKeys[i]][classKeys[j]][8];
				$("#"+semKeys[i]).append("<div class='course-grid-entry major-course' style='z-index: 68;display: block;' id='P_"+autoAdd[semKeys[i]][classKeys[j]][0]+"'>"+autoAdd[semKeys[i]][classKeys[j]][1]+" "+autoAdd[semKeys[i]][classKeys[j]][2]+"<div class='pull-right'>"+autoAdd[semKeys[i]][classKeys[j]][8]+"&nbsp;<span class='glyphicon glyphicon-info-sign' onclick=\"courseinfo_fetch('"+autoAdd[semKeys[i]][classKeys[j]][0]+"', 1);\"></span><span class='glyphicon glyphicon-remove' onclick=\"$('#P_"+autoAdd[semKeys[i]][classKeys[j]][0]+"').remove(); classRemove("+semKeys[i]+", "+autoAdd[semKeys[i]][classKeys[j]][0]+");\"></span></div></div>")
				
				planner[semKeys[i]].Credit += parseInt(autoAdd[semKeys[i]][classKeys[j]][4]);
				planner.Total_credits += parseInt(autoAdd[semKeys[i]][classKeys[j]][4]);
				$("#"+semKeys[i]+"_C").html("Credit:  "+planner[semKeys[i]].Credit);
				$("#Total_credit_college").html(planner.Total_credits);
				$("#Total_credit_remain").html(124-$("#Total_credit_college").html());

                planner.GERs[autoAdd[semKeys[i]][classKeys[j]][5]]--;
                $(".stats-elements-block:contains('"+planner.Major[majorNo].Name+"') > span").html(parseInt($(".stats-elements-block:contains('"+planner.Major[majorNo].Name+"') > span").html())-1);

                //Aliasing
                delete autoAdd[semKeys[i]][classKeys[j]][8];
                planner[semKeys[i]][classKeys[j]] = autoAdd[semKeys[i]][classKeys[j]];
			}
		}
		
		requirementValidation();
	});
}


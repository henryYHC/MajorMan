// class name(if it's error) = course-grid-error

function plannerValidation(target)
{
	/*if(planner[target].Credit < 12) $("#"+target).addClass("course-grid-error");
	else if(planner[target].Credit == 0) $("#"+target).removeClass("course-grid-error");
	else $("#"+target).removeClass("course-grid-error");*/

}

function requirementValidation(){
	$("#FSEM_num").html((planner.GERs.FSEM < 0) ? 0 : planner.GERs.FSEM);
	$("#FWRT_num").html((planner.GERs.FWRT < 0) ? 0 : planner.GERs.FWRT);
	$("#WRT_num").html((planner.GERs.WRT < 0) ? 0 : planner.GERs.WRT);
	$("#MQR_num").html((planner.GERs.MQR < 0) ? 0 : planner.GERs.MQR);
	$("#SNT_num").html((planner.GERs.SNT < 0) ? 0 : planner.GERs.SNT);
	$("#HSC_num").html((planner.GERs.HSC < 0) ? 0 : planner.GERs.HSC);
	$("#HAP_num").html((planner.GERs.HAP < 0) ? 0 : planner.GERs.HAP);
	$("#HAL_num").html((planner.GERs.HAL < 0) ? 0 : planner.GERs.HAL);
	$("#HTH_num").html((planner.GERs.HTH < 0) ? 0 : planner.GERs.HTH);
	$("#PED_num").html((planner.GERs.PED < 0) ? 0 : planner.GERs.PED);
	
}
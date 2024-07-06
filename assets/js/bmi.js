function calc()
{
	var wt = document.BMI.wg.value;
	var ht = document.BMI.ht.value;
	var op=document.BMI.opt1.value;
	
	//BMI Result
	var h = ht/100;
	var si = Math.round((wt/(h*h))*100)/100;
	document.BMI.si.value = si;
	
	//If value is not given for weight
	if(wt=="")
	{
		alert("Enter the value for weight");
	}

	//If weight is less than 10
	else if(wt<=10)
	{
		document.BMI.si.value = 0;
		alert("Weight should be greater than 10kgs");
	}
	
	//If weight is pounds and less than 22lbs
	else if(op=="pounds" && wt<=22)
	{
		document.BMI.si.value = 0;
		alert("Weight should be greater than 22lbs");
	}
	
	//If given height is less than 33cms
	else if(ht<33)
	{
		document.BMI.si.value = 0;
		alert("Height should be taller than 33cms");
	}
	else if(op=="pounds")
	{
		document.BMI.si.value=Math.round((si/2.2)*100)/100;
	
	}
	var d = "Your BMI Category is "
	if(si<15)
	{
		document.BMI.desc.value= d + "Starvation";
	}
	else if(si>15 && si<=18.5)
	{
		document.BMI.desc.value= d + "Underweight";
	}
	else if(si>18.5 && si<=25)
	{	
		document.BMI.desc.value= d + "Normal";	
	}
	else if(si>25 && si<=30)
	{
		document.BMI.desc.value=d + "Overweight";
	}
	else if(si>30)
	{
		document.BMI.desc.value=d + "Obese";
	}
}


function conv(aa)//Height conversion
{
	var ft=0, inc=0, ht=0;
	if(aa==1 || aa==2)
	{
		ft = document.BMI.opt2.value;
		inc = document.BMI.opt3.value;
		var ss = ft*12;
		var tot = ss+parseInt(inc);
       	var val= tot*2.54;
		document.BMI.ht.value = Math.round(val);
	}

	else{
		ht = document.BMI.ht.value;
		if(ht!="")
		{
			var cm=Math.round(ht/2.54);
			var div=parseInt(cm/12);
			var md=cm%12;
			document.BMI.opt2.value=div;
			document.BMI.opt3.value=md;
		}
    }

}


function unit()  //Weight conversion
{
	var pp=document.BMI.opt1.value;
	var ww = document.BMI.wg.value;
	
	//kg  to pounds
	if(pp=="pounds")	
	{
		document.BMI.wg.value = Math.round((ww*2.2)*100)/100;
	}

	//Pounds to kg
	else
	{
		document.BMI.wg.value=Math.round(ww/2.2);
	}
}


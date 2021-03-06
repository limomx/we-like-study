wls.question.check = Ext.extend(wls.question, {
	initDom:function(){
		$("#wls_quiz_main").append("<div id='w_qs_"+this.id+"'></div>");
		$("#w_qs_"+this.id).append("<div class='w_qw_title'>"+this.index+"&nbsp;<span class='w_qw_tool'></span>"+this.questionData.title+"</div>");
		$("#w_qs_"+this.id).append("<div class='w_qw_options'></div>");
		$(".w_qw_options","#w_qs_"+this.id).append("<span><input type='radio' name='w_qs_"+this.id+"' value='A' />&nbsp;对</span>");
		$(".w_qw_options","#w_qs_"+this.id).append("<span><input type='radio' name='w_qs_"+this.id+"' value='B' />&nbsp;错</span>");

		this.cent = this.questionData.cent;
		this.questionData = null;
	}
	,showDescription:function(){		
		var obj = this.answerData;		
		this.quiz.cent += parseFloat(obj.cent);
		this.quiz.count.total ++;
		$(".w_qw_tool",$('#w_qs_'+this.id)).append("<a href='#' class='w_q_d_t' title='查看解题思路及说明' id='w_qs_d_t_"+this.id+"' onclick='wls_question_toogle("+this.id+")'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>");
		$('#w_qs_'+this.id).append("<div class='w_q_d'>"+obj.description+"</div>");
		$(".w_q_d_t",$('#w_qs_'+this.id)).addClass("w_q_d_t_2");

		if(obj.myAnswer=='I_DONT_KNOW'){//放弃
			this.quiz.count.giveup ++;
			$(":radio[value='"+obj.answer+"']", $("#w_qs_"+this.id)).parent().addClass('w_qs_q_w');
			
			$('#w_q_subQuesNav_'+this.id).addClass('w_q_sn_g');
			$('#w_q_subQuesNav_'+this.id).attr('title','放弃\n分值:'+(this.cent));
			wls_question_toogle(this.id);
		}else if(obj.answer==obj.myAnswer){//做对了			
			this.quiz.count.right ++;
			this.quiz.mycent += parseFloat(obj.cent);		
			wls_question_toogle(this.id);
		}else{//做错了			
			this.quiz.count.wrong ++;
			$(":radio[value='"+obj.answer+"']", $("#w_qs_"+this.id)).parent().addClass('w_qs_q_w');
			
			$('#w_q_subQuesNav_'+this.id).addClass('w_q_sn_w');
			$('#w_q_subQuesNav_'+this.id).attr('title','做错\n分值:'+(this.cent));
			if(this.quiz.type=='paper')this.addWhyImWrong();
		}

		obj = null;
	}
	,getMyAnswer:function(){
		var answer = '';
		var value = $('input[name=w_qs_'+this.id+']:checked').val();
		if(typeof(value)=='undefined'){
			answer = 'I_DONT_KNOW';
			this.quiz.count.giveup ++;
		}else{
			answer = value;
		}
		return answer;
	}
	,setMyAnser:function(){
		var myAnswer = this.answerData.myAnswer;
		if(myAnswer!='I_DONT_KNOW'){
			var temp = {A:0,B:1};
			var c = $("input[name=w_qs_"+this.id+"]");
			eval("c[temp."+myAnswer+"].checked = true");
		}
	}
});
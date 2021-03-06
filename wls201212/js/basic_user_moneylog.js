/**
 * 用户的金币充值记录
 * 
 * @author wei1224hf@gmail.com
 * @requires myApp.js mylib.js
 * */
var basic_user = {

	 version: '201209'
	,session: ''
	,username: 'admin'
	,type: ""
	,loginData: {}
		
	,config: null
	,loadConfig: function(afterAjax){
		$.ajax({
			url: myAppServer()+ "&class=basic_user&function=loadConfig",
			dataType: 'json',
			success : function(response) {
				basic_user.config = response;
				if ( typeof(afterAjax) == "string" ){
					eval(afterAjax);
				}else if( typeof(afterAjax) == "function"){
					afterAjax();
				}
			},
			error : function(){				
				alert(top.il8n.disConnect);
			}
		});	
	}	

	/**
	 * 在页面前端提供一个登陆框跟注册框
	 * */
	,loginForm : function(){
		$(document.body).append('<form id="form"></form>');
		
		$("#form").ligerForm({
			inputWidth: 170, labelWidth: 90, space: 40,
			fields: [
			{ display: top.il8n.basic_user.username, name: "username",  type: "text",  validate : {required:true,minlength:3,maxlength:20} },
			{ display: top.il8n.basic_user.password, name: "password",  type: "password", validate : {required:true,minlength:3,maxlength:20} }
			]
		});
		$("#form").append('<br/><br/><br/><table style="width:280px"><tr><td style="width:48%"><input type="button" value="'+top.il8n.basic_user.register+'" id="Button1" class="l-button l-button-submit" /></td>'
				+'<td  style="width:48%"><input type="submit" value="'+top.il8n.basic_user.login+'" class="l-button l-button-submit" /></td></tr></table>' );
		
		var v = $("#form").validate({
			debug: true,
			errorPlacement: function (lable, element) {
				if (element.hasClass("l-textarea")) {
				element.addClass("l-textarea-invalid");
				}
				else if (element.hasClass("l-text-field")) {
				element.parent().addClass("l-text-invalid");
				} 
			},
			success: function (lable) {
				var element = $("[ligeruiid="+$(lable).attr('for')+"]",$("form"));
				if (element.hasClass("l-textarea")) {
					element.removeClass("l-textarea-invalid");
				} else if (element.hasClass("l-text-field")) {
					element.parent().removeClass("l-text-invalid");
				}
			},
			submitHandler: function () {
				basic_user.login($('#username').val(), MD5( MD5( $('#password').val() ) +((new Date()).getHours())) ,"$.ligerDialog.success('登陆成功','登陆成功',function(){top.$.ligerui.get('win_10').close();});top.desktop.loadIcons();");
			}
		});
	}
	
	,login : function(username,password,afterAjax){
		$.ajax({
			url : "../php/myApp.php?class=basic_user&function=login",
			data : {
				username:username,
				password:password
			},
			type : "POST",
			dataType: 'json',
			success : function(data) {			
				if(data.state=='1'){
					top.basic_user.session = data['msg'].substr(0,32);
					top.basic_user.username = username;
					top.basic_user.permission = data['permission'];
					
					top.il8n = data['il8n'];
					top.basic_user.loginData = data.loginData;
					SetCookie("myApp_username",username);
					SetCookie("myApp_password",password); 
					
					if ( typeof(afterAjax) == "string" ){
						eval(afterAjax);
					}else if( typeof(afterAjax) == "function"){
						afterAjax();
					}		
				}else{
					alert(data.msg);
				}
			},
			error : function(){
				$.ligerDialog.error('网络通信失败');
			}
		});
	}
	
	//页面列表ligerUI控件
	,grid : null
	
	,searchOptions: {}
	
	/**
	 * 初始化页面列表
	 * 需要依赖一个空的 document.body 
	 * */
	,initGrid : function(){
		var config = {
				id: 'basic_user__grid'
				,height:'100%'
				,columns: [
					{ display: top.il8n.basic_user.username, name: 'username' },
					{ display: top.il8n.basic_person.name, name: 'name' },
					{ display: top.il8n.money, name: 'money' },
					{ display: top.il8n.credits, name: 'money2'},
					{ display: top.il8n.type, name: 'type', isSort: false, render: function(a,b){
						
						for(var i=0; i<basic_user.config.type.length; i++){
							if( basic_user.config.type[i].code == a.type){
								return basic_user.config.type[i].value;
							}
						}				
					} },
					{ display: top.il8n.status, name: 'status', width: 55 , render: function(a,b){
						for(var i=0; i<basic_user.config.status.length; i++){
							if(basic_user.config.status[i].code == a.status){
								return basic_user.config.status[i].value;
							}
						}
					} }
					,{ display: top.il8n.time_created, name: 'time_created', width: 95 }
				],  pageSize:20 ,rownumbers:true,
				parms : {
					username: top.basic_user.username
					,session: MD5( top.basic_user.session +((new Date()).getHours()))
					,search: $.ligerui.toJSON( basic_user.searchOptions )
				},
				url: myAppServer() + "&class=basic_user&function=getGrid",
				method: "POST",				
				toolbar: { items: []}
		};
		
		//配置列表表头的按钮,根据当前用户的权限来初始化
		var permission = [];
		for(var i=0;i<top.basic_user.permission.length;i++){
			if(top.basic_user.permission[i].code=='12'){
				permission = top.basic_user.permission[i].children;
				for(var j=0;j<permission.length;j++){
					if(permission[j].code=='1202'){
						permission = permission[j].children;
					}
				}				
			}
		}
		for(var i=0;i<permission.length;i++){
			if(permission[i].code=='120201'){
				config.toolbar.items.push({line: true });
				config.toolbar.items.push({
					text: permission[i].name , img:permission[i].icon , click : function(){
						basic_user.search();
					}
				});
			}else if(permission[i].code=='120211'){
				config.toolbar.items.push({line: true });
				config.toolbar.items.push({
					text: permission[i].name , img:permission[i].icon , click : function(){
						basic_user.upload();
					}
				});
			}else if(permission[i].code=='120212'){
				config.toolbar.items.push({line: true });
				config.toolbar.items.push({
					text: permission[i].name , img:permission[i].icon , click : function(){
						basic_user.download();
					}
				});
			}else if(permission[i].code=='120222'){
				//若可以执行删除操作,则必定是多选批量删除,则列表右侧应该提供多选勾选框
				config.checkbox = true;
				config.toolbar.items.push({line: true });
				config.toolbar.items.push({
					text: permission[i].name , img:permission[i].icon , click : function(){
						basic_user.delet();
					}
				});
			}else if(permission[i].code=='120223'){
				//拥有 修改一个用户 的权限
				config.toolbar.items.push({line: true });
				config.toolbar.items.push({
					text: permission[i].name , img:permission[i].icon , click : function(){
						//basic_user.update();
						var selected;
						if(basic_user.grid.options.checkbox){
							selected = basic_user.grid.getSelecteds();
							if(selected.length!=1){alert(il8n.selectOneItemOnGrid);return;}
							selected = selected[0];
						}else{
							selected = basic_user.grid.getSelected();
							if(selected==null){
								alert(il8n.GRID.noSelect);return;
							}
						}						
						top.$.ligerDialog.open({ 
							url: 'basic_user__update.html?id='+selected.id+'&random='+Math.random(), height: 500,width: 400
							,title: top.il8n.modify
							,isHidden: false
						});						
					}
				});
			}else if(permission[i].code=='120221'){
				//拥有 添加一个用户 的权限
				config.toolbar.items.push({line: true });
				config.toolbar.items.push({
					text: permission[i].name , img:permission[i].icon , click : function(){
						//basic_user.insert();
						top.$.ligerDialog.open({ 
							url: 'basic_user__insert.html?random='+Math.random(), height: 500,width: 400
							,title: top.il8n.add
							,isHidden: false
						});
					}
				});
			}else if(permission[i].code=='120202'){
				config.toolbar.items.push({line: true });
				config.toolbar.items.push({
					text: permission[i].name , img:permission[i].icon , click : function(){
						basic_user.view();
					}
				});
			}else if(permission[i].code=='120290'){
				config.toolbar.items.push({line: true });
				config.toolbar.items.push({
					text: permission[i].name , img:permission[i].icon , click : function(){
						var selected;
						if(basic_user.grid.options.checkbox){
							selected = basic_user.grid.getSelecteds();
							if(selected.length!=1){alert(il8n.selectOneItemOnGrid);return;}
							selected = selected[0];
						}else{
							selected = basic_user.grid.getSelected();
							if(selected==null){
								alert(il8n.GRID.noSelect);return;
							}
						}						
						top.$.ligerDialog.open({ 
							url: 'basic_group_2_user__tree.html?usercode='+selected.username+'&random='+Math.random(), height: 500,width: 400
							,title: top.il8n.basic_user.updateUserGroup
							,isHidden: false
						});	
					}
				});
			}
		}
		
		basic_user.grid = $(document.body).ligerGrid(config);
	},
	
	/**
	 * 用户中心
	 * 登陆用户可以看到自己的用户状态,可以看到的内容有:
	 *  用户名 姓名 用户状态 用户组(组织结构组) 金币 积分
	 *  头像
	 *  待办事务数量(点击后,可以直接处理待办事务)
	 *  最后一次登录时间
	 * 
	 * 可以执行的操作有
	 *  修改账号 金币充值 更换皮肤 退出系统
	 *  
	 * 其中的 待办事务数量 ,来自 basic_workflow 工作流模块
	 * 
	 * 是否需要考虑不同的用户组,可以看到不同的内容?
	 *  不需要
	 * */
	center : function(){
		$('body:eq(0)').append("<div id='tb'></div>");
		$('body:eq(0)').append(
		 "<table id='table' width='60%'>"
			+"<tr height='25px;'><td width='50%' align='right'>"+top.il8n.basic_user.username+":&nbsp;&nbsp;</td><td width='50%' align='left'>&nbsp;&nbsp;"+top.basic_user.username+"</td></tr>"
			+"<tr height='25px;'><td width='50%' align='right'>"+top.il8n.basic_person.name+":&nbsp;&nbsp;</td><td width='50%' align='left'>&nbsp;&nbsp;"+top.basic_user.loginData.name+"</td></tr>"
			+"<tr height='25px;'><td width='50%' align='right'>"+top.il8n.status+":&nbsp;&nbsp;</td><td width='50%' align='left'>&nbsp;&nbsp;"+top.basic_user.loginData.statusname+"</td></tr>"
			+"<tr height='25px;'><td width='50%' align='right'>"+top.il8n.basic_user.group+":&nbsp;&nbsp;</td><td width='50%' align='left'>&nbsp;&nbsp;"+top.basic_user.loginData.groupname+"</td></tr>"
			+"<tr height='25px;'><td width='50%' align='right'>"+top.il8n.money+":&nbsp;&nbsp;</td><td width='50%' align='left'>&nbsp;&nbsp;"+top.basic_user.loginData.money+"</td></tr>"
			+"<tr height='25px;'><td width='50%' align='right'>"+top.il8n.credits+":&nbsp;&nbsp;</td><td width='50%' align='left'>&nbsp;&nbsp;"+top.basic_user.loginData.money2+"</td></tr>"
			
			+"<tr height='25px;'><td width='50%' align='right'>"+top.il8n.basic_user.lastLoginTime+":&nbsp;&nbsp;</td><td width='50%' align='left'>&nbsp;&nbsp;"+top.basic_user.loginData.lastlogintime+"</td></tr>"
			+"<tr height='25px;'><td width='50%' align='right'>"+top.il8n.basic_user.registerTime+":&nbsp;&nbsp;</td><td width='50%' align='left'>&nbsp;&nbsp;"+top.basic_user.loginData.time_created+"</td></tr>"
		+"</table>"
		+'<div style="position:absolute;right:5px;top:30px;"><img src="'+top.basic_user.loginData.photo+'" width="150" height="160" /></div>'
		);

		var permission = [];
		for(var i=0;i<top.basic_user.permission.length;i++){
			if(top.basic_user.permission[i].code=="11"){
				permission = top.basic_user.permission[i].children;
			}
		}
		var items = [];
		for(var i=0;i<permission.length;i++){
			items.push({line:true});
			var config = {text:permission[i].name,img:permission[i].icon};
			if(permission[i].code = "1106"){
				config.click = function(){
					top.basic_user.logout();
				}
			}
			items.push(config);
		}

		$("#tb").ligerToolBar({
			items : items
		});
	}
	
	/**
	 * 退出 注销 系统
	 * 清掉浏览器端的 cookie 
	 * 然后向服务端传达退出指令,服务端再删除数据库session表中的内容
	 * */
	,logout : function(){
		
		delCookie("myApp_username");
		delCookie("myApp_password");
		$.ajax({
			url : "../php/myApp.php?class=basic_user&function=logout",
			dataType: "json",
			data : {
				username : top.basic_user.username,
				session : MD5( top.basic_user.session +((new Date()).getHours()))
			},
			type : "POST",
			success : function(msg) {
				if(msg.state==1){
					top.window.location.reload();
				}
			}
        });
	}
	
	/**
	 * 使用EXCEL导入的方式,批量上传用户信息
	 * 后台根据EXCEL中的内容,一次性插入多条用户数据
	 * */
	,upload : function(){
		var dialog;
		if($.ligerui.get("basic_user__grid_upload_d")){
			dialog = $.ligerui.get("basic_user__grid_upload_d");
			dialog.show();
		}else{

			$(document.body).append( $("<div id='basic_user__grid_file'></div>"));
			var uploader = new qq.FileUploader({
				element: document.getElementById('basic_user__grid_file'),
				action: '../php/myApp.php?class=basic_user&function=import',
				allowedExtensions: ["xls"],
				params: {username: top.basic_user.username,
					session: MD5( top.basic_user.session +((new Date()).getHours()))},
				downloadExampleFile : "../file/download/basic_user.xls",
				debug: true,
				onComplete: function(id, fileName, responseJSON){
					basic_user.grid.loadData();
				}
	        });    
			
			$.ligerDialog.open({
				title: top.il8n.importFile,
				
				id : "basic_user__grid_upload_d",
				width : 350,
				height : 200,
				target : $("#basic_user__grid_file"),
				modal : true
			});
		}
	}
	
	,download: function(){
		var dialog;
		if($.ligerui.get("basic_user__grid_download_d")){
			dialog = $.ligerui.get("basic_user__grid_download_d");
			dialog.show();
		}else{
			$(document.body).append( $("<div id='basic_user__grid_download'></div>"));   
			$.ligerDialog.open({
				title: top.il8n.importFile,
				id : "basic_user__grid_download_d",
				width : 350,
				height : 200,
				target : $("#basic_user__grid_download"),
				modal : true
			});
		}
		$.ajax({
			url : "../php/myApp.php?class=basic_user&function=downloadAll",
			type : "POST",
			dataType: 'json',
			data: {username: top.basic_user.username,
				session: MD5( top.basic_user.session +((new Date()).getHours()))
				 },
			success : function(response) {
				if(response.state==0){
					alert(response.msg);
				}else if(response.state==1){
					$("#basic_user__grid_download").append("<a target='_blank' href='"+response.path+"'>"+response.file+"</a><br/>");
				}
			},
			error : function(){
				
				alert(top.il8n.disConnect);
			}
		});		
	}
	
	/**
	 * 删除一个或多个用户
	 * 如果用户拥有 删除权限 
	 * 则前端列表必定是一个带 checkBox 的
	 * */
	,delet: function(){
		//判断 ligerGrid 中,被勾选了的数据
		selected = basic_user.grid.getSelecteds();
		//如果一行都没有选中,就报错并退出函数
		if(selected.length==0){alert(il8n.noSelect);return;}
		//弹框让用户最后确认一下,是否真的需要删除.一旦删除,数据将不可恢复
		if(confirm(il8n.sureToDelete)){
			var ids = "";
			//遍历每一行元素,获得 id 
			for(var i=0; i<selected.length; i++){
				ids += selected[i].id+","
			}
			ids = ids.substring(0,ids.length-1);				
			
			$.ajax({
				url: myAppServer() + "&class=basic_user&function=delete",
				data: {
					ids: ids 
					
					//服务端权限验证所需
					,username: top.basic_user.username
					,session: MD5( top.basic_user.session +((new Date()).getHours()))
				},
				type: "POST",
				dataType: 'json',
				success: function(response) {
					if(response.state==1){
						basic_user.grid.loadData();
					}
				},
				error : function(){
					//网络通信失败,则删除按钮再也不能点了
					alert(top.il8n.disConnect);
				}
			});				
		}		
	}
	
	/**
	 * 添加一个用户
	 * 前端以表单的形式向后台提交数据,服务端AJAX解析入库,
	 * 服务端还会反馈一些数据,比如 用户编号 等
	 * */
	,insert: function(
			dom, //如果为空,则返回一个 ligerForm 的参数配置对象
			afterInsert //如果数据插入后,AJAX返回成功,需要执行一个回调函数
			){
		
		var config = {
			id: 'basic_user__addForm',
			fields: [
				{ display: top.il8n.basic_user.username, name: "basic_user__username",  type: "text",  validate: { required:true, minlength:3, maxlength:10} }
				,{ display: top.il8n.basic_user.password, name: "basic_user__password",  type: "password", validate: { required:true } }
				,{ display: top.il8n.type, name: "basic_user__type", type: "select" , options :{data : basic_user.config.type, valueField : "code" , textField: "value", slide: false }, validate: {required:true} }
				,{ display: top.il8n.money, name: "basic_user__money",  type: "text" , validate: {required: true, digits: true}}
				,{ display: top.il8n.basic_user.money2, name: "basic_user__money2",  type: "text" , validate: {digits: true}}
				,{ display: top.il8n.basic_user.money3, name: "basic_user__money3",  type: "text" , validate: {digits: true}}
				,{ display: top.il8n.status, name: "basic_user__status", type: "select" , options :{data : basic_user.config.status, valueField : "code" , textField: "value", slide: false }, validate: {required:true} }
				,{ display: top.il8n.remark, name: "basic_user__remark",  type: "text" }
			]
		};
		
		//主要用在用户信息修改,在其他页面潜入 用户添加功能 的时候,需要使用
		if (dom == undefined){
			return config;
		}else{
			$(dom).ligerForm(config);
			
			
			//ligerUI 浏览器不兼容 BUG 处理
			if($.browser.msie && top != self){
				$.ligerui.get('basic_user__type').setData(top.il8n.basic_user__types);
				$.ligerui.get('basic_user__status').setData(top.il8n.basic_user__status);
			}
			
			$(dom).append('<br/><br/><br/><br/><table style="width:80%"><tr><td style="width:25%"><input type="submit" value="'+top.il8n.submit+'" id="basic_user__submit" class="l-button l-button-submit" /></td></tr></table>' );
			
			var v = $(dom).validate({
				debug: true,
				//JS前端验证错误
				errorPlacement: function (lable, element) {
					if (element.hasClass("l-textarea")) {
					element.addClass("l-textarea-invalid");
					}
					else if (element.hasClass("l-text-field")) {
					element.parent().addClass("l-text-invalid");
					} 
				},
				//JS前端验证通过
				success: function (lable) {
					var element = $("[ligeruiid="+$(lable).attr('for')+"]",$("form"));
					if (element.hasClass("l-textarea")) {
						element.removeClass("l-textarea-invalid");
					} else if (element.hasClass("l-text-field")) {
						element.parent().removeClass("l-text-invalid");
					}
				},
				//提交表单,在表单内 submit 元素提交之后,要与后台通信
				submitHandler: function () {
					if(basic_user.ajaxState)return;
					basic_user.ajaxState = true;
					$("#basic_user__submit").attr("value",top.il8n.waitting);
					
					basic_user.type = $.ligerui.get('basic_user__type').getValue();
					$.ajax({
						url: myAppServer() + "&class=basic_user&function=insert",
						data: {
							json:$.ligerui.toJSON({
								username: $.ligerui.get('basic_user__username').getValue()
								,password: $.ligerui.get('basic_user__password').getValue()
		
								,type: $.ligerui.get('basic_user__type').getValue()
								
								,money: $.ligerui.get('basic_user__money').getValue()
								,money2: $.ligerui.get('basic_user__money2').getValue()
								,money3: $.ligerui.get('basic_user__money3').getValue()
								,remark: $.ligerui.get('basic_user__remark').getValue()
							}),
							
							//服务端权限验证所需
							username: top.basic_user.username,
							session: MD5( top.basic_user.session +((new Date()).getHours()))
						},
						type: "POST",
						dataType: 'json',						
						success: function(response) {		
							//服务端添加成功,修改 AJAX 通信状态,修改按钮的文字信息,读取反馈信息
							if(response.state==1){
								basic_user.ajaxState = false;
								$("#basic_user__submit").val(top.il8n.submit);	
								basic_user.insertResponse = {
								    user: response.data.id_user
								    ,person: response.data.id_person
								    ,extend: response.data.id_extend
								};
								//如果参数中,有 回调函数,则执行
								if ( typeof(afterInsert) == "string" ){
									eval(afterInsert);
								}
							
							//服务端添加失败
							}else if(response.state==0){
								basic_user.ajaxState = false;
								$("#basic_user__submit").val(top.il8n.submit);									
								alert(response.msg);
							}
						},
						error : function(){
							alert(top.il8n.disConnect);
						}
					});	
				}
			});
		}
	}
	
	//如果添加成功,服务端会返回一些添加成功后的编号数据
	,insertResponse: {
		 user: null //用户添加成功后,用户编号
		,person: null //用户信息对用的 人员详细信息,人员信息编号
		,extend: null //如果这个用户类型选的不是 系统 类型,则会返回一个 用户类型扩展信息表 的编号
	}
	
	//AJAX 通信状态,如果为TRUE,则表示服务端还在通信中	
	,ajaxState: false 
	
	,afterInsert: function(){
		$('tr',$('#form')).empty();
		var htmlStr = '<td style="width:25%">'
				+'<input style="width:100px;" onclick="basic_user.addExtend.person()" type="button" value="'+top.il8n.basic_user.addPersonInfo+'" id="basic_user__submit" class="l-button l-button-submit" />'
				+'</td>';
		if(this.type=='2'){
			htmlStr += '<td style="width:25%">'
			+'<input style="width:100px;" onclick="basic_user.addExtend.student()" type="button" value="'+top.il8n.basic_user.addStudentInfo+'" id="basic_user__submit" class="l-button l-button-submit" />'
			+'</td>';
		}else if(this.type=='3'){
			htmlStr += '<td style="width:25%">'
				+'<input style="width:100px;" onclick="basic_user.addExtend.teacher()" type="button" value="'+top.il8n.basic_user.addTeacherInfo+'" id="basic_user__submit" class="l-button l-button-submit" />'
				+'</td>';
		}

		$('tr',$('#form')).append(htmlStr);
	}
	
	,addExtend:{
		person: function(){
			top.$.ligerDialog.open({ 
				url: 'basic_person__update.html?id='+basic_user.insertResponse.person+'&random='+Math.random(), height: 540,width: 700
				,title: top.il8n.basic_user.addPersonInfo
			});
		}
		,teacher: function(){
			
		}
		,student: function(){
			
		}
	}
	
	,update: function(dom,afterAjax){
		var config = this.insert();
		$(dom).ligerForm(config);
		//ligerUI 浏览器不兼容 BUG 处理
		if($.browser.msie && top != self){
			$.ligerui.get('basic_user__type').setData(top.il8n.basic_user__types);
			$.ligerui.get('basic_user__status').setData(top.il8n.basic_user__status);
		}
		
		$(dom).append('<br/><br/><br/><br/><input type="submit" value="'+top.il8n.modify+'" id="basic_user__submit" class="l-button l-button-submit" />' );
		
		//从服务端读取信息,填充表单内容
		$.ajax({
			url: myAppServer() + "&class=basic_user&function=view"
			,data: {
				id: getParameter("id", window.location.toString() )
				
				//服务端权限验证所需
				,username: top.basic_user.username
				,session: MD5( top.basic_user.session +((new Date()).getHours()))
			}
			,type: "POST"
			,dataType: 'json'						
			,success: function(response) {	
			    basic_user.ajaxData = response;
				$.ligerui.get('basic_user__username').setValue(response.username);
				
				$.ligerui.get('basic_user__password').setValue(response.password);

				$.ligerui.get('basic_user__type').setValue(response.type);
				
				$.ligerui.get('basic_user__status').setValue(response.status);
				
				$.ligerui.get('basic_user__money').setValue(response.money);
				$.ligerui.get('basic_user__money2').setValue(response.money2);
				$.ligerui.get('basic_user__money3').setValue(response.money3);
				$.ligerui.get('basic_user__remark').setValue(response.remark);				
				
				$.ligerui.get('basic_user__type').setDisabled();
				$.ligerui.get('basic_user__username').setDisabled();
			}
		});
			
		var v = $(dom).validate({
			debug: true,
			//JS前端验证错误
			errorPlacement: function (lable, element) {
				if (element.hasClass("l-textarea")) {
				element.addClass("l-textarea-invalid");
				}
				else if (element.hasClass("l-text-field")) {
				element.parent().addClass("l-text-invalid");
				} 
			},
			//JS前端验证通过
			success: function (lable) {
				var element = $("[ligeruiid="+$(lable).attr('for')+"]",$("form"));
				if (element.hasClass("l-textarea")) {
					element.removeClass("l-textarea-invalid");
				} else if (element.hasClass("l-text-field")) {
					element.parent().removeClass("l-text-invalid");
				}
			},
			//提交表单,在表单内 submit 元素提交之后,要与后台通信
			submitHandler: function () {
				alert(1);
				
			}
		});
	}	
	
	/**
	 * 与表格功能对应的 查询条件 
	 * 
	 * 查询条件有 用户名关键字,状态,类型,金币,用户组关键字
	 * */
	,search: function(){
		var formD;
		if($.ligerui.get("formD")){
			formD = $.ligerui.get("formD");
			formD.show();
		}else{
			var form = $("<form id='form'></form>");
			$(form).ligerForm({
				inputWidth: 170
				,labelWidth: 90
				,space: 40
				,fields: [
					 { display: top.il8n.basic_user.username, name: "basic_user__search_username", newline: false, type: "text" }
					,{ display: top.il8n.type, name: "basic_user__search_type", newline: true, type: "select", options :{data : top.il8n.basic_user__types, valueField : "code" , textField: "value" } }
					,{ display: top.il8n.status, name: "basic_user__search_status", newline: true, type: "select", options :{data : top.il8n.basic_user__status, valueField : "code" , textField: "value" } }
					,{ display: top.il8n.money, name: "basic_user__search_money", newline: true, type: "number" }
					,{ display: top.il8n.basic_user.groups, name: "basic_user__search_groups", newline: true, type: "text" }
				]
			}); 
			$.ligerDialog.open({
				id : "formD",
				width : 350,
				height : 200,
				content : form,
				title : top.il8n.search,
				buttons : [
				    //清空查询条件
					{text:top.il8n.clear,onclick:function(){
						$.ligerui.get("basic_user__grid").options.parms.search = "{}";
						$.ligerui.get("basic_user__grid").loadData();
						
						$.ligerui.get("basic_user__search_username").setValue('');
						$.ligerui.get("basic_user__search_type").setValue('');
						$.ligerui.get("basic_user__search_status").setValue('');
						$.ligerui.get("basic_user__search_money").setValue('');
					}},
					//提交查询条件
				    {text:top.il8n.search,onclick:function(){
						var data = {};
						var username = $.ligerui.get("basic_user__search_username").getValue();
						var type = $.ligerui.get("basic_user__search_type").getValue();
						var status = $.ligerui.get("basic_user__search_status").getValue();
						var money = $.ligerui.get("basic_user__search_money").getValue();
						var groups = $.ligerui.get("basic_user__search_groups").getValue();
						
						if(username!="")data.username = username;
						if(type!="")data.type = type;
						if(status!="")data.status = status;
						if(groups!="")data.groups = groups;
						if(money!=0)data.money = money;
						
						$.ligerui.get("basic_user__grid").options.parms = {
	
							username: top.basic_user.username
							,session: MD5( top.basic_user.session +((new Date()).getHours()))
							,search: $.ligerui.toJSON(data)
							
						};
						$.ligerui.get("basic_user__grid").loadData();
				}}]
			});
		}
	}
	
	/**
	 * 查看一个用户信息
	 * */
	,view: function(){
		
	}
};
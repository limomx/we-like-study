po.src = {
	list : function(){
		var store = new Ext.data.JsonStore( {
			autoDestroy : true,
			proxy : new Ext.data.HttpProxy( {
				url : '/b/Server?class=po.Src&function=list',
				method : 'GET'
			}),
			root : 'data',
			remoteSort : true,
			idProperty : 'row_id',
			fields : [ 'row_id', 'created_user', 'corp_name', 'src_name', 'src_no',
					'created_date' ]
		});	
		
		var cm = new Ext.grid.ColumnModel( {
			defaults : {
				sortable : true
			},
			columns : [ {
				header : 'row_id',
				dataIndex : 'row_id'
	
			}, {
				header : 'created_user',
				dataIndex : 'created_user'
	
			}, {
				header : 'corp_name',
				dataIndex : 'corp_name'
	
			}, {
				header : 'src_name',
				dataIndex : 'src_name'
	
			}, {
				header : 'created_date',
				dataIndex : 'created_date'
	
			}, {
				header : 'src_no',
				dataIndex : 'src_no'
			} ]
		});
	
		var search = new Ext.form.TextField({
			id : 'search_',
			width : 170,
			enableKeyEvents : true
		});		
	
		search.on('keyup', function(a, b, c) {
			if (b.button == 12) {
				store.load({
					params : {
						start : 0,
						limit : 20,
						search : Ext.getCmp('search_').getValue()
					}
				});
			}
		});
		
		var tb = new Ext.Toolbar({
			id : "searth",
			items : [ search, {
				text : "查询",				
				handler : function() {
					store.load({
								params : {
									start : 0,
									limit : 20
								}
							});
				}
			}, '-' ,{
				text : "明细",				
				handler : function() {
					if (Ext.getCmp('po_src__list').getSelectionModel().selections.items.length == 0) {
						alert(0);
						return;
					}
					var row_id = Ext.getCmp('po_src__list').getSelectionModel().selections.items[0].data.row_id;
					var tabPanel = po.src.detail(row_id);
					var w = new Ext.Window({
						title : '明细',
						id : 'subject_add_win',
						width : '80%',
						height : 550,
						layout : 'fit',
						buttonAlign : 'center',
						items : [tabPanel],
						modal : true
					});
	
					w.show();
				}
			}, '-' ,{
				text : "参与",				
				handler : function() {
					if (Ext.getCmp('po_src__list').getSelectionModel().selections.items.length == 0) {
						alert(0);
						return;
					}
					var row_id = Ext.getCmp('po_src__list').getSelectionModel().selections.items[0].data.row_id;
					var tabPanel = po.src.bid.suppiler.mainPanel();
					var w = new Ext.Window({
						title : '参与',
						id : 'subject_add_win',
						width : '80%',
						height : 550,
						layout : 'fit',
						buttonAlign : 'center',
						items : [tabPanel],
						modal : true
					});
	
					w.show();
				}
			}]
		});	
	
		store.on('beforeload', function() {
			Ext.apply(this.baseParams, {
						search : Ext.getCmp('search_').getValue()
					});
		});
	
		var grid = new Ext.grid.GridPanel( {
			id : 'po_src__list',
			store : store,
			cm : cm,
			height : 350,
			width : '95%',
			tbar : tb,
			loadMask : {  
				msg : '加载数据中，请稍候...'  
			} ,
			bbar : new Ext.PagingToolbar( {
				store : store,
				pageSize : 15,
				displayInfo : true
			})
		});
	
		store.load();
		return grid;
	},
	detail : function(row_id){
		var ssList = po.src.ssList(row_id);
		ssList.title = "供应商列表";
		var spList = po.src.spList(row_id);
		spList.title = "物品列表";
		var tabPanel = new Ext.TabPanel({   
		    id: "mainTab",   
		    width: 500,   
		    height: 300,   
		    activeTab: 0,   
		    defaults: {   
		        autoScroll: true,   
		        autoHeight:true,   
		        style: "padding:5"  
		    },   
		    items:[   
				ssList,spList
		    ],   
		    enableTabScroll: true  
		});    
		return tabPanel;
	},
	ssList : function(row_id){
		var store = new Ext.data.JsonStore( {
			autoDestroy : true,
			proxy : new Ext.data.HttpProxy( {
				url : '/b/Server?class=po.Src&function=ssList&row_id='+row_id,
				method : 'GET'
			}),
			loadMask : {  
				msg : '加载数据中，请稍候...'  
			} ,
			root : 'data',
			remoteSort : true,
			idProperty : 'row_id',
			fields : [ 'row_id', 'created_user', 'created_date' , 'ownr_psn', 'sp_name' ]
		});
			
		var cm = new Ext.grid.ColumnModel( {
			defaults : {
				sortable : true
			},
			columns : [ {
				header : 'row_id',
				dataIndex : 'row_id'
			}, {
				header : 'created_user',
				dataIndex : 'created_user'
			}, {
				header : 'ownr_psn',
				dataIndex : 'ownr_psn'
			}, {
				header : 'sp_name',
				dataIndex : 'sp_name'
	
			}, {
				header : 'created_date',
				dataIndex : 'created_date'
	
			} ]
		});	
	
		var grid = new Ext.grid.GridPanel( {
			store : store,
			cm : cm,
			height : 350,
			width : '95%'
		});
	
		store.load();
		return grid;
	},
	spList : function(row_id){
		var store = new Ext.data.JsonStore( {
			autoDestroy : true,
			proxy : new Ext.data.HttpProxy( {
				url : '/b/Server?class=po.src.Sp&function=list&row_id='+row_id,
				method : 'GET'
			}),
			loadMask : {  
				msg : '加载数据中，请稍候...'  
			} ,
			root : 'data',
			remoteSort : true,
			idProperty : 'row_id',
			fields : [ 'row_id','brand_type','brand_rate','proc_model','level1','final_pr','final_order','sp_name','sp_id','sp_cert_level','biz_status','origin_flag' ]
		});
			
		var cm = new Ext.grid.ColumnModel( {
			defaults : {
				sortable : true
			},
			columns : [ 
				{ header : 'row_id', dataIndex : 'row_id' },
				{ header : 'brand_type', dataIndex : 'brand_type' },
				{ header : 'brand_rate', dataIndex : 'brand_rate' },
				{ header : 'proc_model', dataIndex : 'proc_model' },
				{ header : 'level1', dataIndex : 'level1' },
				{ header : 'final_pr', dataIndex : 'final_pr' },
				{ header : 'final_order', dataIndex : 'final_order' },
				{ header : 'sp_name', dataIndex : 'sp_name' },
				{ header : 'sp_id', dataIndex : 'sp_id' },
				{ header : 'sp_cert_level', dataIndex : 'sp_cert_level' },
				{ header : 'biz_status', dataIndex : 'biz_status' },
				{ header : 'origin_flag', dataIndex : 'origin_flag' }					
			]
		});	
	
		var grid = new Ext.grid.GridPanel( {
			store : store,
			cm : cm,
			height : 350,
			width : '95%'
		});
	
		store.load();
		return grid;
	},
	bid : {
		suppiler : {
			mainPanel : function(){
				var grid = po.src.bid.suppiler.spList(0,0);
				grid.region = 'center';
				var form = po.src.bid.suppiler.bidForm();
				form.region = 'south';
				form.height = 100;
				var borderPanel = new Ext.Panel({
					layout:'border',
					items: [grid,form]
				});
				return borderPanel;
			},
			spList : function(spid,ssid){
				var store = new Ext.data.JsonStore( {
					autoDestroy : true,
					proxy : new Ext.data.HttpProxy( {
						url : 'data/po_src_bid_suppiler_spList.txt',
						method : 'GET'
					}),
					loadMask : {  
						msg : '加载数据中，请稍候...'  
					} ,
					root : 'data',
					idProperty : 'row_id',
					fields : [ '商品名称','起价','我的报价','我的排名','最低报价','轮次','状态' ]
				});
					
				var cm = new Ext.grid.ColumnModel( {
					defaults : {
						sortable : true
					},
					columns : [ 
						{ header : '商品名称', dataIndex : '商品名称' },
						{ header : '起价', dataIndex : '起价' },
						{ header : '我的报价', dataIndex : '我的报价' },
						{ header : '我的排名', dataIndex : '我的排名' },
						{ header : '最低报价', dataIndex : '最低报价' },
						{ header : '轮次', dataIndex : '轮次' },
						{ header : '状态', dataIndex : '状态' }
					]
				});	
	
				var grid = new Ext.grid.GridPanel( {
					store : store,
					cm : cm,
					height : 350,
					width : '95%',
					tbar : new Ext.Toolbar({
						items : [ {
							text : "刷新",				
							handler : function() {
								store.load();
							}
						},{
							text : "放弃",				
							handler : function() {
								
							}
						}]
					})
				});
	
				store.load();
				return grid;
			},
			bidForm : function(){
				var panel = new Ext.Panel({
		            layout:"fit",
		            items:[{
		                layout:"absolute",
		                items:[
								new Ext.form.Label({
									x:"1%",
				                    y:5,
								    text: '上次报价'    
								}),
				                new Ext.form.TextField({
			                	 	x:"10%",
				                    y:5,
				                    id : "p_s_b_s_f_lastBid"
				                }),
								new Ext.form.Label({
									x:"25%",
				                    y:5,
								    text: '本次报价'									    
								}),
				                new Ext.form.TextField({
			                	 	x:"35%",
				                    y:5 ,
				                    id : "p_s_b_s_f_bid"  
				                }),
								new Ext.form.Label({
									x:"50%",
				                    y:5,
								    text: '时间'
								}),			                
				                new Ext.Button({
					                text : "提交",
					                x:5,
					                y:55,
					                width:130,
					                handler : function() {
					                	var bid = Ext.getCmp('p_s_b_s_f_bid').value;
										Ext.Ajax.request({
											method : 'POST',
											url : "data/po_src_bid_suppiler_bidForm_submit.txt",
											success : function(response) {
												var obj = Ext.decode(response.responseText);
												Ext.getCmp('p_s_b_s_f_lastBid').setValue(obj.上次报价);
											},
											failure : function(response) {
												alert("网络通信错误");
											},
											params : {"foo":"bar","bid":bid}
										});
									}
					            })
						]
		            }]
		        });    
		        return panel;
			}
		}
	}
};
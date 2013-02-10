<?php
$db_server = $_REQUEST['db_server'];
$db_username = $_REQUEST['db_username'];
$db_password = $_REQUEST['db_password'];
$db_name = $_REQUEST['db_name'];


$conn = mysql_connect($db_server,$db_username,$db_password);
mysql_select_db($db_name,$conn);
mysql_query('SET NAMES UTF8');
mysql_query("DELIMITER ;",$conn);

function createtables($conn){
$sql = "
drop table if exists  basic_parameter;
create table basic_parameter(
    code varchar(200) default '0', # 
    value varchar(200) default '0', #
    reference varchar(200) default '0' ,#参考,对于这个键值的其他详细说明 ,也可能是某个表的某个列
    id int auto_increment primary key, #主键 
    extend1 int ,
    extend2 int ,
    extend3 int ,
    extend4 varchar(200) ,
    extend5 varchar(200) ,
    extend6 varchar(200) ,
    remark text  #备注
)COMMENT ' 系统参数表,存储在硬盘上,非内存表.如果数据库中有一张数据行数小于1000条的基础信息表,那就应该将其合并到这里 ' ;


drop table if exists  basic_workflow ;
create table basic_workflow (
     msg varchar(800) default '通知公告' comment '通知公告内容'
    ,filepath varchar(200) default '' comment '如果有附件上传的话,只能有一个附件'
    ,receive varchar(500) default '' comment ' 有哪些人也接收到了此节点 '
    ,receive_count int default '0' comment '总共有多少人收到此节点'
    ,apppath varchar(200) default 'basic_workflow__msg.html' comment ' 程序路径 '
    ,node_source int default '0' comment ' 事件的最初发起点'
    ,node_from int default '0' comment '流转到本节点的上一个节点'

    #下面就是一些公共字段了，几乎每张业务表都会有
    ,type int default '0' comment '类别'
    ,id int primary key  comment ' 主键编号 '
    ,id_creater int default '0' comment ' 创建人编号 '
    ,id_creater_group int default '0' comment ' 创建人当时所在的行政部门 '
    ,time_created timestamp default CURRENT_TIMESTAMP comment ' 创建时间 '
    ,time_lastupdated datetime default '1900-01-01' comment ' 最后修改时间 '
    ,count_updated int default 0 comment ' 修改次数 '
    ,status int default 1 comment ' 状态 '
    ,remark text comment ' 备注 '
) DEFAULT CHARSET=utf8 comment ' 工作流表  ';

drop table if exists basic_excel ;
create table basic_excel (

     guid char(36) default '0' 
    ,sheets int comment ' 上传的文件的 sheet 总数 '
    ,sheetindex int not null comment ' 这行记录来自哪个 sheet  '
    ,sheetname varchar(200) not null
    ,rowindex int not null
    ,maxcolumn int not null

,   A   varchar(500)    
,   B   varchar(500)    
,   C   varchar(500)    
,   D   varchar(500)    
,   E   varchar(500)    
,   F   varchar(500)    
,   G   varchar(500)    
,   H   varchar(500)    
,   I   varchar(500)    
,   J   varchar(500)    
,   K   varchar(500)    
,   L   varchar(500)    
,   M   varchar(500)    
,   N   varchar(500)    
,   O   varchar(500)    
,   P   varchar(500)    
,   Q   varchar(500)    
,   R   varchar(500)    
,   S   varchar(500)    
,   T   varchar(500)    
,   U   varchar(500)    
,   V   varchar(500)    
,   W   varchar(500)    
,   X   varchar(500)    
,   Y   varchar(500)    
,   Z   varchar(500)    
,   AA  varchar(500)    
,   AB  varchar(500)    
,   AC  varchar(500)    
,   AD  varchar(500)    
,   AE  varchar(500)    
,   AF  varchar(500)    
,   AG  varchar(500)    
,   AH  varchar(500)    
,   AI  varchar(500)    
,   AJ  varchar(500)    
,   AK  varchar(500)    
,   AL  varchar(500)    
,   AM  varchar(500)    
,   AN  varchar(500)    

    ,id int auto_increment primary key  comment ' 主键编号 '
    ,id_creater int default '0' comment ' 创建人编号 '

) comment ' 用户经常向系统上传 EXCEL 文件,来实现业务数据的批量增加. 在上传 EXCEL 的时候,  不会将业务数据直接插入到业务表中  ' ;

drop table if exists basic_log ;
create table basic_log (
      id int auto_increment primary key  comment ' 主键编号 '
     ,type int default '0' comment '类型'
     ,username varchar(200) default 'system' comment '日志来源'
     ,msg varchar(300) default 'log' comment '日志内容'
     ,time_created timestamp default CURRENT_TIMESTAMP comment ' 创建时间 '
)COMMENT ' 系统日志表 ';

#用户表
drop table if exists  basic_user ;
create table basic_user (
     username varchar(200) default '0' unique COMMENT ' 用户名 是否应该支持汉字登录呢？'
    ,password varchar(200) default '0' COMMENT  ' 密码 当然是要MD5加密的，参数是域名 '
   
    ,money int default 0  comment ' 金币,用于积分政策,可消耗品,涉及到扣除扣分 '
    ,money2 int default 0  comment  ' 积分之类的 '

    ,lastlogintime datetime default '1900-01-01'  comment ' 最后一次登录时间 '
    ,lastlogouttime datetime default '1900-01-01' comment ' 最后一次退出时间 '
    ,count_actions int default '0' comment ' 这个账号总计的系统操作次数 '
    ,count_actions_period int default '0' comment ' 最近一段时间的操作次数,即最近3个月的操作数,如果3个月内操作数为0,就要将此账号关闭  '

    ,person_id  int default '0' unique comment '每一个用户必定有一条 详细个人信息 的记录'
    ,person_name varchar(200) default '0' comment ' 真实姓名,冗余数据存储,便于前端列显示 '
    ,person_cellphone varchar(200) default '0' comment ' 手机号码,涉及短信通知收发 '
    ,person_email varchar(200) default '0' comment ' 邮箱,涉及邮件收发 '

    ,group_id int default '0' comment ' 此用户的组织机构编号 '
    ,group_code varchar(200) default '0' comment ' 此用户的组织机构编码,有层级关系,可按层级统计 '
    ,group_name varchar(200) default '0' comment ' 此用户的组织机构名称,冗余存储,便于前台显示.用户换部门的时候,此冗余字段要改 '
    ,group_all varchar(200) default '0' comment ' 此用户所有的其他用户组编号集合.一个用户可能属于多个用户组 '

    #下面就是一些公共字段了，几乎每张业务表都会有
    ,id int primary key  comment ' 主键编号 '
    ,type int default '0' comment ' 类型 '    
    ,id_creater int default '0' comment ' 创建人编号 '
    ,id_creater_group int default '0' comment ' 创建人当时所在的行政部门 '
    ,code_creater_group int default '0' comment ' 创建人当时所在的行政部门编码,有层级关系,可用于统计  '
    ,time_created timestamp default CURRENT_TIMESTAMP comment ' 创建时间 '
    ,time_lastupdated datetime default '1900-01-01' comment ' 最后修改时间 '
    ,count_updated int default 0 comment ' 修改次数 '
    ,status int default 1 comment ' 状态 '
    ,remark text comment ' 备注 '

) comment = ' 用户表 ' DEFAULT CHARSET=utf8;

drop table if exists  basic_user_session ;
create table basic_user_session (

     id_user int primary key  comment '用户编码'
    ,username varchar(200) default '0' comment '用户名'
    ,id_group int default '0' comment '用户主要用户组的编码'
    ,groups varchar(200) default '0' comment '用户的其他用户组编码'
    ,ip varchar(200) default '0' comment '用户的登录IP'
    ,permissions varchar(1000) comment '权限编号'

    ,lastaction varchar(15) default '0'  comment ' 最后一次操作的操作名称 '
    ,lastactiontime datetime default '1900-01-01' comment  ' 最后一次执行操作的操作时间 '
    ,count_actions int default '0' comment ' 本次登录系统后,执行的操作总数  '

    ,session varchar(200) default '0' comment ' 通过 session 来判断用户的上线状态,通过 工作流中的每隔一段时间更新  '
    ,status int default '0' 

) ENGINE=MEMORY DEFAULT CHARSET=utf8 comment ' SESSION  内存表,弃用各种WEB服务端程序 APAHCE TOMCAT JBOSS 等系统自带的 SESSION内存机制,将SESSION功能迁移到数据库上来 ';

drop table if exists basic_user_moneylog ;
create table basic_user_moneylog (

     money int default '0' comment ' 充值金额 '
    ,code char(10) default '0000000000' comment ' 充值校验码 '
    ,ordernum varchar(200) default '0' comment ' 订单编号 在使用支付宝的时候 '

    #下面就是一些公共字段了，几乎每张业务表都会有
    ,id int primary key  comment ' 主键编号 '
    ,type int default '0' comment ' 类型 '    
    ,id_creater int default '0' comment ' 创建人编号 '
    ,id_creater_group int default '0' comment ' 创建人当时所在的行政部门 '
    ,code_creater_group int default '0' comment ' 创建人当时所在的行政部门编码,有层级关系,可用于统计  '
    ,time_created timestamp default CURRENT_TIMESTAMP comment ' 创建时间 '
    ,time_lastupdated datetime default '1900-01-01' comment ' 最后修改时间 '
    ,count_updated int default 0 comment ' 修改次数 '
    ,status int default 1 comment ' 状态 '
    ,remark text comment ' 备注 '

)  CHARSET=utf8 comment ' 用户的充值记录 ';

drop table if exists basic_memory ;
create table basic_memory (
     code varchar(200) default '0' comment ' 编码 '
    ,type int default '0'  comment ' 类型 '
    ,extend1 int  comment ' 扩展数字字段 '
    ,extend2 int  comment ' 扩展数字字段 '
    ,extend3 int  comment ' 扩展数字字段 '
    ,extend4 varchar(200) comment ' 扩展字符串字段 '
    ,extend5 varchar(200) comment ' 扩展字符串字段 '
    ,extend6 varchar(200) comment ' 扩展字符串字段 '
) ENGINE=MEMORY  CHARSET=utf8 comment ' 内存表,将被各个存储过程频繁调用 ' ;

drop table if exists  basic_group;
create table basic_group(
     name varchar(200) default '0' comment ' 用户组名称,随意修改不会影响业务逻辑 '
    ,code varchar(200) unique  COMMENT  ' 有级层联系的用户组编码,不能随便修改,影响业务逻辑,系统用户组的编码 10(管理员) 99(访客) 的非常重要,不能改  '

    ,count_users int default '0' COMMENT ' 用户数量 缓存数据 '

    ,id int primary key  comment ' 主键编号 '
    ,type int default '0' COMMENT '类型'
    ,status int default '1' comment '状态'
    ,remark text
) COMMENT ' 用户组表,权限模块中的核心表 ';

drop table if exists  basic_department;
create table basic_department(
     code varchar(200) default '0' unique  comment '部门编码 通过这个可以查看此部门的所有上级跟下级部门 ,与 basic_group 中的 code 保持一致'
    ,name varchar(200) default '0' not null comment  '名称'
    ,count_person int default '0'  comment '部门总人数,并不一定等于部门用户组总人数,可能包含非本公司的,临时外包成员'
    ,address varchar(200) default '0'  comment '部门办公地点'
    ,phone varchar(200) default '0' comment '办公室电话'
    ,fax varchar(200) default '0' comment '办公室传真'
    ,functions varchar(200) default '0' comment '部门主要职能'
    ,photo varchar(200) default '0' comment '部门的内景照片'

    ,manager_id int default '0' comment '管理员编号,参考 basic_user 表, 部门管理员可能不属于此部门'
    ,manager_name varchar(200) default '部门管理员' comment '用于前端列表显示所用'

    #下面就是一些公共字段了，几乎每张业务表都会有
    ,id int primary key  comment ' 主键编号 '
    ,type int default '0' comment ' 类型 '    
    ,id_creater int default '0' comment ' 创建人编号 '
    ,id_creater_group int default '0' comment ' 创建人当时所在的行政部门 '
    ,code_creater_group int default '0' comment ' 创建人当时所在的行政部门编码,有层级关系,可用于统计  '
    ,time_created timestamp default CURRENT_TIMESTAMP comment ' 创建时间 '
    ,time_lastupdated datetime default '1900-01-01' comment ' 最后修改时间 '
    ,count_updated int default 0 comment ' 修改次数 '
    ,status int default 1 comment ' 状态 '
    ,remark text comment ' 备注 '

) DEFAULT CHARSET=utf8 comment '行政部门,组织机构,一个员工属于并且只能属于一个 行政部门';

drop table if exists  basic_group_2_user ;
create table basic_group_2_user (
     id_user int default '0' comment ' 用户编号 '
    ,id_group int default '0' comment ' 用户组编号 '
    ,username varchar(200) default '0' comment ' 用户名称,为了便于运维 '
    ,code_group varchar(200) default '0' comment ' 用户的组织机构编码,有层级关系 '

    ,type int default '0'  comment '用户所属用户组类型, 此用户的主要身份归属查看  1 行政归属类型 2 业务处理类型 '
    ,UNIQUE KEY basic_g2u_u ( id_user,id_group )
    ,id int auto_increment primary key  comment ' 主键编号 '

)  COMMENT = ' 系统的权限是通过 权限-用户组-用户 来实现的 ';

drop table if exists  basic_permission ;
create table basic_permission (
     name varchar(200) default '0' comment ' 名称,前端展示用,随便改名称不影响业务逻辑 '
    ,type int default '0' comment ' 类型: 节点 页面 按钮 逻辑 '
    ,code varchar(200) unique COMMENT ' 权限编码,有级层关系的,不能随便改,会影响前端跟服务端的逻辑 '
    ,icon varchar(200) COMMENT ' 图标 具体使用时,其实是一个图标CLASS样式 '
    ,path varchar(200) COMMENT ' 如果这个节点是 图标 类型,点击以后会以iframe形式弹出一个新的窗口,这里就保存其路径 '

    ,id int primary key  comment ' 主键编号 '
    ,status int default '1' comment '状态'
    ,remark text
)COMMENT ' 权限表 系统的权限是通过 权限-用户组-用户 来实现的 ,非业务表,为系统基础表  ';

drop table if exists  basic_group_2_permission;
create table basic_group_2_permission(
     id_permission int default '0' not null comment ' 权限编号,int类型的数据在查询的时候速度更快,所以在初始化用户权限 这一过程中,依赖此字段 '
    ,id_group int default '0' not null comment ' 用户组编号 '
    ,code_group varchar(200) default '0' not null comment ' 用户组编码,有层级关系,也便于技术人员后台运维 '
    ,code_permission varchar(200) default '0' not null comment ' 权限编码,有层级关系 '
    ,cost int default '0' comment ' 执行此操作,将会从当前用户的 money 中扣除多少金币 '
    ,credits int default '0' comment ' 执行此操作,将会给当前用户增加多少积分 , 积分是用来判断某一个用户在系统的活跃程度的  '
   ,UNIQUE KEY basic_g2p_u ( id_permission,id_group )
    ,id int auto_increment primary key  comment ' 主键编号 '

)  comment ' 权限表 系统的权限是通过 权限-用户组-用户 来实现的 ,非业务表,为系统基础表 ' ;

#人员信息表
DROP TABLE IF EXISTS basic_person;
CREATE TABLE basic_person (
    name varchar(200) DEFAULT 'Default Name' COMMENT '姓名',
    birthday datetime DEFAULT '1900-01-01 00:00:00' COMMENT '生日',
    cardType int(11) DEFAULT '1' COMMENT '身份鉴别卡类型: 1 身份证, 2 军员证 ,3 港澳台证,等等其他类型',
    idcard varchar(200) DEFAULT '0' COMMENT '卡内容',
    photo varchar(200) DEFAULT '../file/tavatar.gif' COMMENT '照片',
    height int(11) DEFAULT '171' COMMENT '身高',
    nationality varchar(200) DEFAULT '中国' COMMENT '国籍',
    gender int(11) DEFAULT '1' COMMENT ' 根据国标 GB2261.1 要求， 男1 女2 默认男性 ',
    nation int(11) DEFAULT '1' COMMENT '民族 参考 GB3304 ',
    ismarried int(11) DEFAULT '2' comment '婚姻状况 1 已婚 2 未婚',
    degree int(11) DEFAULT '20' comment '最高学历 默认为 大学本科',
    degree_school varchar(200) default '四川师范大学' comment '最高学历对应的学校 ',
    degree_school_code varchar(200) default '0' comment '高校编码',
    politically int(11) DEFAULT '99' comment '政治面貌 默认为 无党派人士',
    address_birth varchar(200) DEFAULT '浙江省 宁波市 余姚市' comment '籍贯',
    address_birth_code varchar(200) default '330281' comment '籍贯 行政区划编码 在前端,这个是 input hidden'

    ,cellphone varchar(200) DEFAULT '13456105470' comment '手机号码'
    ,email varchar(200) DEFAULT 'wei1224hf@gmail.com' comment '邮箱'
    ,qq varchar(200) DEFAULT '0'
    ,address varchar(200) default '浙江省.宁波市.余姚市.陆埠镇.江南村 XXX路XX号' comment '居住地址'
    ,address_code varchar(200) default '居住地址编码' comment '居住地址的地理位置编码,用于统计'

    #下面就是一些公共字段了，几乎每张业务表都会有
    ,id int primary key  comment ' 主键编号 '
    ,type int default '0' comment ' 类型 '    
    ,id_creater int default '0' comment ' 创建人编号 '
    ,id_creater_group int default '0' comment ' 创建人当时所在的行政部门 '
    ,code_creater_group int default '0' comment ' 创建人当时所在的行政部门编码,有层级关系,可用于统计  '
    ,time_created timestamp default CURRENT_TIMESTAMP comment ' 创建时间 '
    ,time_lastupdated datetime default '1900-01-01' comment ' 最后修改时间 '
    ,count_updated int default 0 comment ' 修改次数 '
    ,status int default 1 comment ' 状态 '
    ,remark text comment ' 备注 '
) ;

drop table if exists education_subject ;
create table education_subject (
	  code varchar(200) default '0' comment ' 科目编码,有层级关系,可按层级统计 '
	,name varchar(200) default '0' comment ' 科目名称,将在其他业务表中冗余存储,用于前端显示 '

        ,count_papers int default '0' comment ' 这个科目对应的试卷总数,从 education_paper 统计 '
        ,count_questions int default '0' comment ' 这个科目对应的题目总数,从 education_question 统计 '
        ,count_students int default '0' comment ' 学生数目,从education_subject_2_group_2_teacher 统计  '

        ,type int default '0' 
        ,status int default '0' 
	,id int primary key comment ' 主键编号 '
	,remark text

) DEFAULT CHARSET=utf8 comment ' 学科分类编码,没有参照教育部发布的行业标准 学科分类编码 ,这里的编码将主要按照用户自己的需求设计 ';

drop table if exists education_subject_2_group_2_teacher ;
create table education_subject_2_group_2_teacher (

     subject_code varchar(200) comment ' 考试科目编码,层级编码,可以按层级统计 '
    ,subject_name  varchar(200) comment ' 考试科目名称 '
    ,group_code varchar(200) comment ' 考试科目编码,层级编码,可以按层级统计 '
    ,group_name varchar(200) comment ' 用户组名称 '
    ,teacher_id  int default '0' not null comment ' 出卷老师编号,用户编号,来源 basic_user,非 education_teacher '
    ,teacher_name varchar(200) default '0' comment ' 出卷老师姓名 '
    ,teacher_code varchar(200) default '0' comment ' 出卷老师编码,用户名,层级编码,可以按层级统计 ' 

    #下面就是一些公共字段了，几乎每张业务表都会有
    ,id int primary key auto_increment  comment ' 主键编号 '
    ,type int default '0' comment ' 类型 '    
    ,id_creater int default '0' comment ' 创建人编号 '
    ,id_creater_group int default '0' comment ' 创建人当时所在的行政部门 '
    ,code_creater_group int default '0' comment ' 创建人当时所在的行政部门编码,有层级关系,可用于统计  '
    ,time_created timestamp default CURRENT_TIMESTAMP comment ' 创建时间 '
    ,time_lastupdated datetime default '1900-01-01' comment ' 最后修改时间 '
    ,count_updated int default 0 comment ' 修改次数 '
    ,status int default 1 comment ' 状态 '
    ,remark text comment ' 备注 '

    ,UNIQUE KEY education_s2g2t_u ( subject_code, group_code, teacher_id )
)  type=innodb comment ' 科目 用户组 教师对应表 ';


drop table if exists  education_student ;
create table education_student (

    #班级信息
     code varchar(200) default '000000' unique not null comment '学号 也是basic_user 中的用户名 ' 
    ,name varchar(200) default ' 姓名 ' comment ' 姓名,数据冗余存储 '
    ,class_code varchar(200) default '0' not null comment ' 来源 basic_group ,有层级关系 ' 
    ,class_name varchar(200) default '0' comment ' 班级名称,冗余数据存储,用户列表前端展示 '
    ,class_teacher_name varchar(200) default '班主任姓名' comment '不用这个字段的话,就要从人员信息表里链接,很耗资源的,必须数据冗余'
    ,class_teacher_code varchar(200) default '班主任编码' comment ' 教师的用户名,来源 basic_user '
    ,class_teacher_id varchar(200) default '班主任id' comment ' 教师的编号 '
   
    ,specialty varchar(200) default '0' comment '艺体特长,多位存储 '
    ,hobby varchar(200) default '0' comment '兴趣爱好,多位存储'
    ,characters varchar(200) default '0' comment '性格特征,多位存储'

    ,growth varchar(200) default '0' comment '身体发育情况描述'
    ,health varchar(200) default '0' comment '身体健康状况描述'
    ,healthdefect varchar(200) default '0' comment '生理缺陷描述'
    ,mentalhealth varchar(200) default '0' comment '心理健康描述'
    
    ,attitude_life int default '0' comment '对生活的态度,单选'
    ,attitude_learn int default '0' comment '学习态度 0 未知 11 厌恶 1 抵触 2 附和 21 主动 22 喜欢'
    ,attitude_teacher int default '0' comment '对教师的态度 0 未知 1 不喜欢 2 普通 21 喜欢 22  '
    ,attitude_classmate int default '0' comment '对同学的态度'
    ,attitude_oppositesex int default '0' comment '对异性的态度'

    ,intelligence int default '0' comment '智力水平'

    ,class_manager int default '0' comment '班干部所担任职务,如果没有担任任何职务,就用0'

    ,junior_school varchar(200) default '0' comment '来自哪个初中'
    ,junior_graduated datetime default '1900-01-01'  comment '初中毕业时间'
    ,junior_scores int default '0' comment '中考考试总成绩'
    ,junior_rank int default '0' comment '全市中考总排名 '

    ,parents_name varchar(200) default '家长'  comment '家长姓名,可以填两个名字'
    ,parents_cellphone varchar(200) default '0' comment '家长的手机电话号码,只能填一个号码,自动短信功能要用到 '

    ,id_user int default '0' not null unique comment '用户编号'
    ,id_person int default '0' not null unique comment '人员信息编号'

    ,scorerank int default '0' comment '班级学习成绩排名, 每次月考 期中考 期末考 都会更新此字段   不可修改 系统自动生成'
    ,scorerank2 int default '0' comment '年级学习成绩排名'
    ,scorerank3 int default '0' comment '全市统考成绩排名'

    #下面就是一些公共字段了，几乎每张业务表都会有
    ,id int primary key  comment ' 主键编号 '
    ,type int default '0' comment ' 类型 '    
    ,id_creater int default '0' comment ' 创建人编号 '
    ,id_creater_group int default '0' comment ' 创建人当时所在的行政部门 '
    ,code_creater_group int default '0' comment ' 创建人当时所在的行政部门编码,有层级关系,可用于统计  '
    ,time_created timestamp default CURRENT_TIMESTAMP comment ' 创建时间 '
    ,time_lastupdated datetime default '1900-01-01' comment ' 最后修改时间 '
    ,count_updated int default 0 comment ' 修改次数 '
    ,status int default 1 comment ' 状态 '
    ,remark text comment ' 备注 '

) DEFAULT CHARSET=utf8 comment ' 学生信息表 TODO 很多没必要的字段 ';

drop table if exists  education_teacher ;
create table education_teacher (     

     code varchar(200) default '0' not null comment '教师编号'
    ,name varchar(200) default ' 姓名 ' comment ' 姓名,数据冗余存储 '
    ,department_code varchar(200) default '0' not null comment '组织机构编码'
    ,department_name varchar(200) default '0' comment '组织机构名称'
    ,is_head_teacherr int comment '是否是班主任 '

    ,certificate varchar(200) default '0' comment ' 教师资格证编号.如果没有教师资格证,就默认是0'
    ,title int default '0' comment '职称'
    ,years int default '1' comment '工作年龄'
    ,honor varchar(200) default '' comment '荣誉称号,多位存储'
    ,specialty varchar(200) comment '学术专业'
    ,specialty_code varchar(200) comment '学术专业编码'

    ,experience_work text comment '工作经历'
    ,experience_publish text comment '著作发表经历'
    ,experience_project text comment '科研项目经历'

    ,photo_certificate varchar(200) default '../file/nophoto.jpg' comment '教师资格证扫描照片' 
    ,photo_degree varchar(200) default '../file/nophoto.jpg' comment '最高学历文凭的扫描照片'
 
    #扩展链接数据
    ,id_person int default '0' comment ' 人员表扩展 '
    ,id_user int default '0' comment ' 默认的,每个教师都是一个系统用户 '

    #下面就是一些公共字段了，几乎每张业务表都会有
    ,id int primary key  comment ' 主键编号 '
    ,type int default '0' comment ' 类型 '    
    ,id_creater int default '0' comment ' 创建人编号 '
    ,id_creater_group int default '0' comment ' 创建人当时所在的行政部门 '
    ,code_creater_group int default '0' comment ' 创建人当时所在的行政部门编码,有层级关系,可用于统计  '
    ,time_created timestamp default CURRENT_TIMESTAMP comment ' 创建时间 '
    ,time_lastupdated datetime default '1900-01-01' comment ' 最后修改时间 '
    ,count_updated int default 0 comment ' 修改次数 '
    ,status int default 1 comment ' 状态 '
    ,remark text comment ' 备注 '

)comment '教师信息表' DEFAULT CHARSET=utf8;

drop table if exists education_paper ;
create table education_paper (

     subject_code varchar(200) comment ' 考试科目编码 '
    ,subject_name  varchar(200) comment ' 考试科目名称 '
    ,count_questions int default '0' comment ' 题目数量 '
    ,count_used int default '0' comment ' 被学生使用的次数 '
    ,title varchar(200) default '0' comment ' 试卷名称 '

    ,cost int default '0' comment  ' 所耗费的金币 '
    ,teacher_id  int default '0' not null comment ' 出卷老师编号 '
    ,teacher_name varchar(200) default '0' comment ' 出卷老师姓名 '
    ,teacher_code varchar(200) default '0' comment ' 出卷老师编码,用户名 ' 
    ,cent int default '0' comment ' 试卷总分 '

    #下面就是一些公共字段了，几乎每张业务表都会有
    ,type int default '0' comment '类别'
    ,id int  primary key  comment ' 主键编号 '
    ,id_creater int default '0' comment ' 创建人编号 '
    ,id_creater_group int default '0' comment ' 创建人当时所在的行政部门编号 '
    ,code_creater_group varchar(200) default '0' comment ' 数据创建人所在的部门编码,有层级关系,可用于统计 '
    ,time_created timestamp default CURRENT_TIMESTAMP comment ' 创建时间 '
    ,time_lastupdated datetime default '1900-01-01' comment ' 最后修改时间 '
    ,count_updated int default 0 comment ' 修改次数 '
    ,status int default 1 comment ' 状态 '
    ,remark text comment ' 备注 '

) DEFAULT CHARSET=utf8 comment ' 试卷表.考卷,统考卷,他们的试卷内容也是存在这张表中的 ' ENGINE = InnoDB;


drop table if exists education_paper_log ;
create table education_paper_log (

     paper_id int default '0' comment ' 试卷编号 '
    ,paper_title varchar(200) default '0' comment ' 试卷标题 '
    ,teacher_id  int default '0' not null comment ' 出卷老师编号,用户编号,来源 basic_user,非 education_teacher '
    ,teacher_name varchar(200) default '0' comment ' 出卷老师姓名 '
    ,teacher_code varchar(200) default '0' comment ' 出卷老师编码,用户名,层级编码,可以按层级统计 ' 

    ,cent int default '0' comment ' 试卷总分 '
    ,cent_subjective int default '0' comment ' 主观题分数,如果这张试卷中有 填空题 等需要教师批改的话,人工批改的得分 '
    ,cent_objective int default '0' comment ' 客观题分数,系统自动批改 '
    ,mycent int default '0' comment '我的得分'
    ,mycent_subjective int default '0' comment ' 学生的主观题分数 '
    ,mycent_objective int default '0' comment ' 学生的客观题分数 '

    ,count_right int default 0    comment '我做对的题目数'
    ,count_wrong int default 0  comment '我做错的题目数'
    ,count_giveup int default 0  comment '我放弃的题目数'
    ,count_total int default 0    comment '试卷的总题目数'
    ,count_subjective int default '0' comment ' 主观题目数 '
    ,count_objective int default '0' comment ' 客观题目数 '

    ,proportion int default 0 comment '我题目作对的比率  (做对总数/题总数)*100 '
								
    ,subject_code varchar(200) comment ' 考试科目编码,层级编码,可以按层级统计 '
    ,subject_name  varchar(200) comment ' 考试科目名称 '

    #下面就是一些公共字段了，几乎每张业务表都会有
    ,type int default '0' comment '类别'
    ,id int  primary key  comment ' 主键编号 '
    ,id_creater int default '0' comment ' 创建人编号 '
    ,id_creater_group int default '0' comment ' 创建人当时所在的行政部门编号 '
    ,code_creater_group varchar(200) default '0' comment ' 数据创建人所在的部门编码,有层级关系,可用于统计 '
    ,time_created timestamp default CURRENT_TIMESTAMP comment ' 创建时间 '
    ,time_lastupdated datetime default '1900-01-01' comment ' 最后修改时间 '
    ,count_updated int default 0 comment ' 修改次数 '
    ,status int default 1 comment ' 状态 '
    ,remark text comment ' 备注 '

) DEFAULT CHARSET=utf8 comment=' 试卷日志 ' ENGINE = InnoDB;
 

drop table if exists education_question ;
create table education_question ( 

    type2 varchar(200) default '0' comment '-- 用户自定义的题目类型 , 主要用在 组合题类型上, 比如完形填空 , 阅读理解 , 快速阅读 等 --'

    ,title text not null                comment '题目标题'
    ,answer text not null           comment '答案'
    ,optionlength int default 0   comment '题目选项数'
    ,option1 text                        comment '选项1'
    ,option2 text         		comment '选项2'
    ,option3 text
    ,option4 text
    ,option5 text
    ,option6 text
    ,option7 text                      comment '规定一道选择题 无论是 单选 多选  , 选项个数最多不能超过 7 个'
    ,description text                 comment '解体思路'
    ,cent float default 0            comment '分值'

    ,layout int default 1            comment '如果是 单选题 多选题,选项的排列方式'
    ,id_parent int default 0                             comment '--  像 完型填空 这样的组合题, 它有很多 子题目 的,这些子题目通过 id_parent 找到其归属的组合题编号  --'
    ,path_listen varchar(200) default '0'         comment '听力文件路径'     
    ,path_image varchar(200) default '0'       comment '图片路径'

    ,subject_code varchar(200) comment ' 考试科目编码,层级编码,可以按层级统计 '
    ,subject_name  varchar(200) comment ' 考试科目名称 '
    ,teacher_id  int default '0' not null comment ' 出卷老师编号,用户编号,来源 basic_user,非 education_teacher '
    ,teacher_name varchar(200) default '0' comment ' 出卷老师姓名 '
    ,teacher_code varchar(200) default '0' comment ' 出卷老师编码,用户名,层级编码,可以按层级统计 ' 

    ,count_used int default 0            comment '被使用的次数'
    ,count_right int default 0            comment '被答对的次数'
    ,count_wrong int default 0         comment '被答错的次数'
    ,count_giveup int default 0        comment '被放弃解答的次数'
     
    ,comment_ywrong_1 int default 0        comment '如果答错了,学生可以对这道题说明自己答错的原因: 我的确不会做'
    ,comment_ywrong_2 int default 0        comment '我粗心,不小心答错了'  
    ,comment_ywrong_3 int default 0        comment '看了解题思路后,我仍然不能明白错在哪里'               
    ,comment_ywrong_4 int default 0        comment '我没有做错,是题目本身错了'

    ,difficulty int default 0    	comment '难度'
    ,ids_level_knowledge varchar(200) default '0'   comment '知识点编号'

    #下面就是一些公共字段了，几乎每张业务表都会有
    ,type int default '0' comment '类别'
    ,id int  primary key  comment ' 主键编号 '
    ,id_creater int default '0' comment ' 创建人编号 '
    ,id_creater_group int default '0' comment ' 创建人当时所在的行政部门编号 '
    ,code_creater_group varchar(200) default '0' comment ' 数据创建人所在的部门编码,有层级关系,可用于统计 '
    ,time_created timestamp default CURRENT_TIMESTAMP comment ' 创建时间 '
    ,time_lastupdated datetime default '1900-01-01' comment ' 最后修改时间 '
    ,count_updated int default 0 comment ' 修改次数 '
    ,status int default 1 comment ' 状态 '
    ,remark text comment ' 备注 '

) DEFAULT CHARSET=utf8 comment=' 题目表 ' ENGINE = InnoDB;

drop table if exists education_paper_2_question ;
create table education_paper_2_question (
     id_paper int not null comment ' 试卷编号 '
    ,id_question int not null comment ' 题目编号 '
    ,rank int default '0' not null comment ' 显示排序 '
    ,UNIQUE KEY education_p2q ( id_paper,id_question )
    ,id int auto_increment primary key comment ' 主键 '
) comment ' 试卷跟题目的多对多映射表.为了支持 题库出卷 功能,改变了原来 题目只属于一张试卷 的业务逻辑 ' ENGINE = InnoDB;


drop table if exists education_question_log ;
create table education_question_log (

     id_paper int default 0  comment ' 试卷编号 '
    ,id_paper_log int default 0 comment ' 试卷日志编号 '
    ,id_question int default 0 comment ' 题目编号 '
            
    ,myanswer text comment ' 我的回答 '
    ,correct int default 0      comment ' 是否作对 1 作对 2 做错 0 放弃 '
    ,mycent float default 0 comment ' 我的得分 '
    ,application int default 0 comment ' 1 练习试卷 2 考试试卷 3 统考试卷 4  题型练习 5 单题做题 '


    ,id_teacher int default '0'    

    #下面就是一些公共字段了，几乎每张业务表都会有
    ,type int default '0' comment '类别'
    ,id int  primary key  comment ' 主键编号 '
    ,id_creater int default '0' comment ' 创建人编号 '
    ,id_creater_group int default '0' comment ' 创建人当时所在的行政部门编号 '
    ,code_creater_group varchar(200) default '0' comment ' 数据创建人所在的部门编码,有层级关系,可用于统计 '
    ,time_created timestamp default CURRENT_TIMESTAMP comment ' 创建时间 '
    ,time_lastupdated datetime default '1900-01-01' comment ' 最后修改时间 '
    ,count_updated int default 0 comment ' 修改次数 '
    ,status int default 1 comment ' 状态 '
    ,remark text comment ' 备注 '
                
) COMMENT ' 做题记录,不会有对应的前端列表 ' DEFAULT CHARSET=utf8 ENGINE = InnoDB;

drop table if exists education_question_log_wrongs ;
create table education_question_log_wrongs  (
				
     count_wrong int default '0' comment ' 作错次数 '
    ,count_right int default '0' comment ' 作对次数,当累计到4的时候,这行记录将被删除 '
    ,question_id int default '0' comment ' 题目编号 '
    ,question_title varchar(200) default '0' comment '题目的标题,冗余字段,方便前端查询'		
    ,paper_title varchar(200) default '0' comment ' 试卷的标题,这个做题日志的来源 '
    ,paper_id int default '0' comment ' 试卷编号 '
    ,teacher_id  int default '0' not null comment ' 出卷老师编号,用户编号,来源 basic_user,非 education_teacher '
    ,teacher_name varchar(200) default '0' comment ' 出卷老师姓名 '
    ,teacher_code varchar(200) default '0' comment ' 出卷老师编码,用户名,层级编码,可以按层级统计 ' 
    ,subject_code varchar(200) comment ' 考试科目编码,层级编码,可以按层级统计 '
    ,subject_name  varchar(200) comment ' 考试科目名称 '

    ,comment_student int default 0 comment '-- 用户对这道题目的评价 1 我做错了,我知识点掌握不足 2 我不小心错了,我粗心导致的 3 我还是没有弄懂为什么会错 4 我没有错,答案或者题目设计错了 --'
    ,comment_teracher varchar(200) default '0'  comment '-- 教师在批改这道题目的时候,下的评论.一般是在题目做错的情况下评论 --'
			
    #下面就是一些公共字段了，几乎每张业务表都会有
    ,type int default '0' comment '类别'
    ,id int  primary key  comment ' 主键编号 '
    ,id_creater int default '0' comment ' 创建人编号 '
    ,id_creater_group int default '0' comment ' 创建人当时所在的行政部门编号 '
    ,code_creater_group varchar(200) default '0' comment ' 数据创建人所在的部门编码,有层级关系,可用于统计 '
    ,time_created timestamp default CURRENT_TIMESTAMP comment ' 创建时间 '
    ,time_lastupdated datetime default '1900-01-01' comment ' 最后修改时间 '
    ,count_updated int default 0 comment ' 修改次数 '
    ,status int default 1 comment ' 状态 '
    ,remark text comment ' 备注 '

    ,UNIQUE KEY education_question_log_wrongs_u( id_creater,question_id )
) COMMENT ' 做题记录,查询条件 : 标题关键字 科目 权重 (重要程度)  所在试卷名称 出题老师  生成时间 ' DEFAULT CHARSET=utf8 ENGINE = InnoDB;

drop table if exists  education_exam ;
create table education_exam (
 
     subject_code varchar(200) comment ' 考试科目编码 '
    ,subject_name  varchar(200) comment ' 考试科目名称 '
    ,count_students_planed int default '0' comment ' 预计参加考试总人数 '
    ,count_students int default '0' comment ' 实际参加考试人数 '
    ,count_passed int default '0' comment '考试通过人数'
    ,title varchar(200) default '0' comment  ' 考试名称,跟 paper title 一致 '
    ,place varchar(200) default '网上在线考试' comment  ' 考试场所 '
    ,time_start datetime default '2012-01-01' comment  ' 考试开始时间 '
    ,time_end datetime default '2100-01-01' comment  ' 考试结束时间 '
    ,score int default '0' comment ' 试卷总分 '
    ,mode int default '0' comment ' 模式 '
    ,passline int default '0' comment '及格线'
    ,invigilator varchar(200) comment '监考老师'

    ,teacher_id  int default '0' not null comment ' 出卷老师编号 '
    ,teacher_name varchar(200) default '0' comment ' 出卷老师姓名,如果有批改的话,也是他批改 '
    ,teacher_code varchar(200) default '0' comment ' 出卷老师编码,用户名 ' 

    ,id_unified int default '0' comment ' 统考编号.如果这个是属于统考的话 '
    ,id_paper int default '0' comment ' 试卷编号 ' 

    #下面就是一些公共字段了，几乎每张业务表都会有
    ,type int default '0' comment '类别'
    ,id int  primary key  comment ' 主键编号 '
    ,id_creater int default '0' comment ' 创建人编号 '
    ,id_creater_group int default '0' comment ' 创建人当时所在的行政部门编号 '
    ,code_creater_group varchar(200) default '0' comment ' 数据创建人所在的部门编码,有层级关系,可用于统计 '
    ,time_created timestamp default CURRENT_TIMESTAMP comment ' 创建时间 '
    ,time_lastupdated datetime default '1900-01-01' comment ' 最后修改时间 '
    ,count_updated int default 0 comment ' 修改次数 '
    ,status int default 1 comment ' 状态 '
    ,remark text comment ' 备注 '

) DEFAULT CHARSET=utf8 COMMENT ' 多人在线测验,测验结果会被统计,得到排名结果 ' ;

drop table if exists  education_exam_2_class;
create table education_exam_2_class (
    paper_id int default '0' not null comment ' 试卷编号 '
    ,exam_id  int default '0' not null comment ' 考试编号 '
    ,exam_title varchar(200) default '0' comment '考试标题'
    ,class_id  int default '0' not null comment ' 班级编号 '
    ,class_code varchar(200) default '0' comment ' 班级编码 '
    ,class_name varchar(200) default '0' comment ' 班级名称 '   
    ,teacher_id  int default '0' not null comment ' 出卷老师编号 '
    ,teacher_name varchar(200) default '0' comment ' 出卷老师姓名 '
    ,teacher_code varchar(200) default '0' comment ' 出卷老师编码,用户名 ' 

    ,subject_code varchar(200) comment ' 考试科目编码 '
    ,subject_name  varchar(200) comment ' 考试科目名称 '

    ,rank int default '0' comment '  此班级在这次联合考试中的排名,按平均分算  '
    ,top_score int default '0'  comment '班级最高分'
    ,top_student varchar(200) default '0' comment '最高分学生姓名'
    ,top_student_id int default '0' comment '最高分学生的编号'
    ,avg_scroe int default '0' comment '平均分'
    ,count_students_planed int default '0' comment ' 预计参加考试总人数 '
    ,count_students int default '0' comment ' 实际参加考试人数 '
    ,count_passed int default '0' comment '考试通过人数'
    ,count_submit int default '0' comment ' 已经提交试卷的人数 '

    #下面就是一些公共字段了，几乎每张业务表都会有
    ,type int default '0' comment '类别'
    ,id int  primary key  comment ' 主键编号 '
    ,id_creater int default '0' comment ' 创建人编号 '
    ,id_creater_group int default '0' comment ' 创建人当时所在的行政部门编号 '
    ,code_creater_group varchar(200) default '0' comment ' 数据创建人所在的部门编码,有层级关系,可用于统计 '
    ,time_created timestamp default CURRENT_TIMESTAMP comment ' 创建时间 '
    ,time_lastupdated datetime default '1900-01-01' comment ' 最后修改时间 '
    ,count_updated int default 0 comment ' 修改次数 '
    ,status int default 1 comment ' 状态 '
    ,remark text comment ' 备注 '

    ,UNIQUE KEY education_e2c_u (exam_id,class_id)
) comment ' 班级考试记录,用户组考试记录 ';

drop table if exists  education_exam_2_student;
create table education_exam_2_student  (

     exam_id  int default '0' not null comment ' 考试编号 '
    ,exam_title varchar(200) default '0' comment '考试标题'
    ,class_id  int default '0' not null comment ' 班级编号 '
    ,class_code varchar(200) default '0' comment ' 班级编码 '
    ,class_name varchar(200) default '0' comment ' 班级名称 '   
    ,teacher_id  int default '0' not null comment ' 出卷老师编号 '
    ,teacher_name varchar(200) default '0' comment ' 出卷老师姓名 '
    ,teacher_code varchar(200) default '0' comment ' 出卷老师编码,用户名 ' 
    ,student_id  int default '0' not null comment ' 学生编号 '
    ,student_name varchar(200) default '0' comment ' 学生姓名 '
    ,student_code varchar(200) default '0' comment ' 学生编码,用户名 ' 

    ,subject_code varchar(200) comment ' 考试科目编码 '
    ,subject_name  varchar(200) comment ' 考试科目名称 '

    ,rank int default '0' comment ' 学生在这次考试中的总排名 '
    ,rank_class int default '0' comment ' 学生在这次考试中的班级排名 '
    ,score int default '0' comment '得分'
    ,passline int default '0' comment ' 及格线 '
    ,totalcent int default '0' comment ' 试卷总分 '
    ,id_paper int default '0' comment '试卷编号'
    ,id_paper_log int default '0' comment ' 做题日志编号 '

    ,time_start datetime default '2012-01-01' comment  ' 考试开始时间 '
    ,time_end datetime default '2100-01-01' comment  ' 考试结束时间 '
    ,time_submit datetime default '1987-03-18' comment  ' 交卷时间 ' 
    ,time_mark datetime default '1987-03-18' comment  ' 批卷时间 ' 

    #下面就是一些公共字段了，几乎每张业务表都会有
    ,type int default '0' comment '类别'
    ,id int  primary key  comment ' 主键编号 '
    ,id_creater int default '0' comment ' 创建人编号 '
    ,id_creater_group int default '0' comment ' 创建人当时所在的行政部门编号 '
    ,code_creater_group varchar(200) default '0' comment ' 数据创建人所在的部门编码,有层级关系,可用于统计 '
    ,time_created timestamp default CURRENT_TIMESTAMP comment ' 创建时间 '
    ,time_lastupdated datetime default '1900-01-01' comment ' 最后修改时间 '
    ,count_updated int default 0 comment ' 修改次数 '
    ,status int default 1 comment ' 状态 '
    ,remark text comment ' 备注 '

    ,UNIQUE KEY education_e2s_u ( exam_id , student_id )
) comment ' 学生考试记录 ';


drop table if exists  education_exam_unified ;
create table education_exam_unified (
 

     name varchar(200) default '统考' #名称
    ,schedule text #时间表,一个类似课程表的东西
    ,time_start datetime default '1900-01-01' #考试开始时间
    ,time_end datetime default '1900-01-01' #考试结束时间
    ,ids_classes varchar(200) default '0' #参加考试的班级编号. 编号来自 department 表

    #公共业务字段,来自主表,因为这是一张从表
    ,id int auto_increment primary key #主键 
) DEFAULT CHARSET=utf8;

";
$arr = explode(";",$sql);
for($i=0;$i<count($arr);$i++){
    mysql_query($arr[$i],$conn);
}
}

function createProcBasic($conn){
mysql_query("DELIMITER ;;",$conn);
$sql = "
-- ----------------------------
-- Procedure structure for b4initlog
-- ----------------------------
DROP PROCEDURE IF EXISTS `b4initlog`;;

CREATE PROCEDURE `b4initlog`()
BEGIN

truncate table education_question ;
truncate table education_paper_log ;
truncate table education_question_log ;
truncate table education_question_log_wrongs ;

END;;


-- ----------------------------
-- Procedure structure for basic_group__import
-- ----------------------------
DROP PROCEDURE IF EXISTS `basic_group__import`;;

CREATE PROCEDURE `basic_group__import`(IN in_guid char(36),OUT out_state int,OUT out_msg varchar(200),OUT out_ids varchar(2000))
pro_main:BEGIN
/**
批量导入用户组信息


用户将一个EXCEL文件上传到系统,系统将EXCEL文件中的内容读取到 basic_excel 表

数据库存储过程分析basic_excel表中的内容,读取出业务数据,插入到各个业务表中


前提条件:
basic_excel 有待处理的业务数据


@param in_guid 标识一次EXCEL文件导入的唯一健

@param out_state 存储过程执行结果,1为正确通过,其他都表示错误.系统处理错误,2 3 4 为业务数据错误

@param out_msg 存储过程执行后返回的执行描述结果

@version 201212
@author wei1224hf@gmail.com
@qqgroup 135426431 
*/
    declare fig int;         
    declare rowindex__ int;
    declare A_,B_,C_,D_,E_,F_,G_ varchar(200);    
    declare code_,row1_,row2_ varchar(200); 
    declare id_creater_,id_creater_group_,rowindex_ int default 0;   
    declare code_creater_group_ varchar(200);     

    #内存表游标,用于模拟数组,处理EXCEL表头列,检验格式

    declare cur_array cursor for     
        SELECT code,row1,row2 from array_group;           
    #核心游标,处理业务数据
    declare cur_group cursor for 
        SELECT A,B,C,D,E,F,G,rowindex from basic_excel where sheetname = basic_memory__il8n('group','basic_group',1)         
            and guid = in_guid            
            and rowindex > 1
            order by rowindex;         
    #MYSQL游标必须得变量

    declare CONTINUE HANDLER FOR SQLSTATE '02000' SET fig = 1;       

    #如果guid是空的,就报错

    if in_guid is null then        
        set out_state = 0;        
        set out_msg = 'null guid';   
        insert into basic_log (type,username,msg) values (1,'system','basic_group__import wrong , no guid' );               
        leave pro_main;
    end if;
        
    #数据库内存表,用于实现数组,处理EXCEL表头列

    drop TEMPORARY table if exists array_group;
    create  TEMPORARY  table array_group (
        code varchar(2)        
        ,row1 varchar(200)   
        ,row2 varchar(200)        
    ) engine = memory ;         
    
    select A,B,C,D,E,F,G,id_creater into A_,B_,C_,D_,E_,F_,G_,id_creater_ from basic_excel 
        where guid = in_guid 
        and rowindex = 1 
        and sheetname = basic_memory__il8n('group','basic_group',1); 
    if A_ is null then    
        #EXCEL中缺少必要的 sheet 
        set out_msg = basic_memory__il8n('sheetMissing','basic_excel',1); 
        set out_state = 0;        
        insert into basic_log (type,username,msg) values (1,'system',out_msg );             
        leave pro_main;
    end if;       
    
    set out_state = 0;    
    set out_msg = \"\";    
    set out_ids = \"\";      

    insert into array_group values 
        ('A', basic_memory__il8n( A_,'basic_group', 2) ,A_ ),        
        ('B', basic_memory__il8n( B_,'basic_group', 2) ,B_ ),        
        ('C', basic_memory__il8n( C_,'basic_group', 2) ,C_ ),        
        ('D', basic_memory__il8n( D_,'basic_group', 2) ,D_ ),        
        ('E', basic_memory__il8n( E_,'basic_group', 2) ,E_ ),        
        ('F', basic_memory__il8n( F_,'basic_group', 2) ,F_ ),        
        ('G', basic_memory__il8n( G_,'basic_group', 2) ,G_ )
    ;
    #select * from array_group;
        
    #开始检查excel列结构

    set @sufficient = \"name,code,type,remark\";
    set @keys = \"\";    
    set @columns = \"\";
    set fig = 0;    
    open cur_array;        
    fetch cur_array into code_,row1_,row2_;        
    while( fig = 0 ) do     
        if row1_ is null then        
            set out_msg = concat(row2_,\" \",code_,\"2\",\" \",\"wrong column\");                             
        elseif FIND_IN_SET(row1_,@sufficient) = 0 then        
            #如果有一些不需要的列

            set out_msg = concat(row2_,\" \",code_,\"2\",\" \",\"wrong column\");                   
            set out_state = 2;  
            insert into basic_log (type,username,msg) values (1,'system',out_msg );             
            leave pro_main;                
        else                    
            #select code_;
            set @keys = concat(\",\",row1_,@keys);       
            set @columns = concat(\",\",code_,@columns);         
        end if;  
    fetch cur_array into code_,row1_,row2_;     
    end while;
    close cur_array;          

    #如果一些必需的列没有
    if FIND_IN_SET('name',@keys) = 0 then    
        set out_state = 0;
        set out_msg = concat(basic_memory__il8n('columnMissing','basic_excel',1),\" \",basic_memory__il8n('name','basic_group',1));             
        insert into basic_log (type,username,msg) values (1,'system',out_msg );          
        leave pro_main;        
    elseif FIND_IN_SET('type',@keys) = 0 then    
        set out_state = 0;
        set out_msg = concat(basic_memory__il8n('columnMissing','basic_excel',1),\" \",basic_memory__il8n('type','basic_group',1));             
        insert into basic_log (type,username,msg) values (1,'system',out_msg );        
        leave pro_main;        
    elseif FIND_IN_SET('code',@keys) = 0 then    
        set out_state = 0;
        set out_msg = concat(basic_memory__il8n('columnMissing','basic_excel',1),\" \",basic_memory__il8n('code','basic_group',1));             
        insert into basic_log (type,username,msg) values (1,'system',out_msg );   
        leave pro_main;
    end if;     

    if id_creater_ = 1 then            
        #是超级管理员 admin 导入的数据        
        set id_creater_group_ = 1;        
        set code_creater_group_ = '10';
    else   
        select group_id,group_code into id_creater_group_,code_creater_group_ from basic_user where id =  id_creater_;
    end if;

    #select @keys;        
    #开始拼凑核心SQL语句
    set @keys = concat(@keys,\",\");        
    set @columns = concat(@columns,\",\");    
    set @columncount = basic_stringcount(@keys,\",\");    
    select max(rowindex) into @maxrow from basic_excel where guid = in_guid and sheetname = basic_memory__il8n('group','basic_group',1);  
    set @sql_insert = concat(\"insert into basic_group (id\",@keys,\"status) values \");         

    set fig = 0;    
    open cur_group; 
    fetch cur_group into A_,B_,C_,D_,E_,F_,G_,rowindex_;    
    while ( fig = 0 ) do            
        #select A_,B_,C_,D_,E_,F_,G_,rowindex_;
        set @sql_values = '';  
        set @p = 0;         
        set @spotpos = 1;   
        set @spotpos2 = 1;   
        set @spotpos_ = 1;   
        set @spotpos2_ = 1;             
        set @tempvalue = '';    
        set @student_sql_values = '';                            
        set @person_sql_values = '';           

        inerLoop: LOOP
            SET @p = @p + 1;  
            set @spotpos = LOCATE(',', @columns,@spotpos2);
			set @spotpos2 = LOCATE(',', @columns,@spotpos+1);
            set @alphaindex = SUBSTRING(@columns,@spotpos+1,@spotpos2-@spotpos-1);                

            set @spotpos_ = LOCATE(',', @keys,@spotpos2_);
			set @spotpos2_ = LOCATE(',', @keys,@spotpos_+1);
            set @keyindex = SUBSTRING(@keys,@spotpos_+1,@spotpos2_-@spotpos_-1); 
            
            if @alphaindex = 'A' then set @tempvalue = A_; end if; 
            if @alphaindex = 'B' then set @tempvalue = B_; end if; 
            if @alphaindex = 'C' then set @tempvalue = C_; end if; 
            if @alphaindex = 'D' then set @tempvalue = D_; end if; 
            if @alphaindex = 'E' then set @tempvalue = E_; end if; 
            if @alphaindex = 'F' then set @tempvalue = F_; end if; 
            if @alphaindex = 'G' then set @tempvalue = G_; end if;          
            #select @alphaindex,@keyindex,@tempvalue,@columns;            

            if @keyindex = 'type' then   
                #检查此用户组类型是否正确        
                set @temp = null;
                select code into @temp from basic_memory where extend5 = 'basic_group__type' and extend4 = @tempvalue;            
                #select @temp,@tempvalu;
                if @temp is null then                 
                    set out_state = 3;
                    set out_msg = concat( basic_memory__il8n('wrongType','basic_group',1) ,\" \",@tempvalue,\" \",@alphaindex,rowindex_);                    
                    insert into basic_log (type,username,msg) values (1,'system',out_msg );   
                    leave pro_main;
                end if;     
                set @tempvalue = @temp;                                
            elseif @keyindex = 'name' then                 
                set @groupname_temp = @tempvalue;      
            elseif @keyindex = 'code' then    
                #检查此用户组编码是否重复       
                set @codetemp = 0;       
                select count(*) into @codetemp from basic_group where code = @tempvalue ;                  
                if @codetemp > 0 then                                        
                    set out_state = 4;                        
                    set out_msg = concat(basic_memory__il8n('existCode','basic_group',1),\" \",@tempvalue,\" \",@alphaindex,rowindex_);  
                    insert into basic_log (type,username,msg) values (1,'system',out_msg );       
                    leave pro_main;
                end if;                                        
            end if;       
                     
            set @sql_values = concat(@sql_values,\",'\",@tempvalue,\"'\");
                     
            IF @p < @columncount-1 THEN
               ITERATE inerLoop;
            END IF;
            LEAVE inerLoop;            
        END LOOP inerLoop;          
        set @id = basic_memory__index('basic_group');   
        set out_ids = concat(out_ids,\",\",@id);       
        if rowindex_ = @maxrow then                 
            set @sql_insert = concat(@sql_insert,\"('\",@id,\"'\",@sql_values,\",1) ;\");            
        else
            set @sql_insert = concat(@sql_insert,\"('\",@id,\"'\",@sql_values,\",1) ,\");            
        end if;
        #select @sql_values;         
        #leave pro_main;
    fetch cur_group into A_,B_,C_,D_,E_,F_,G_,rowindex_;    
    end while;
    close cur_group;    

    #select @sql_insert;    

    PREPARE stmt FROM @sql_insert;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;             

    insert into basic_department (
           id,code,name,id_creater,id_creater_group,code_creater_group,type
    ) select basic_memory__index('basic_department'),code,name,1,1,10,(case when code like '50%' then 1 else 2 end)  from basic_group where type = 2;
    
    set out_state = 1;  
    set out_msg =  'OK';

    delete from basic_excel where guid = in_guid;           
END;;


-- ----------------------------
-- Procedure structure for basic_group_2_permission__import
-- ----------------------------
DROP PROCEDURE IF EXISTS `basic_group_2_permission__import`;;

CREATE PROCEDURE `basic_group_2_permission__import`(IN in_guid char(36),OUT out_state int,OUT out_msg varchar(200))
pro_main:BEGIN
/**
用户将一个EXCEL文件上传到系统,系统将EXCEL文件中的内容读取到 basic_excel 表

数据库存储过程分析basic_excel表中的内容,读取出业务数据,插入到各个业务表中

用户组-权限 的对应关系处理


前提条件:
basic_group , basic_permission 表已经被创建并有数据
basic_excel 有待处理的业务数据


@param in_guid 标识一次EXCEL文件导入的唯一健

@param out_state 存储过程执行结果,1为正确通过,其他都表示错误.系统处理错误,2 3 4 为业务数据错误

@param out_msg 存储过程执行后返回的执行描述结果

@version 201212
@author wei1224hf@gmail.com
@qqgroup 135426431 
*/
    declare fig int;           
    declare rowindex_ int;    
    declare code_,row1_ varchar(200);       
    declare B_,C_,D_,E_,F_,G_,H_,I_,J_,K_,L_,M_,N_,O_,P_,Q_,R_,S_,T_,U_,V_ varchar(200);          

    #内存表游标,模拟数组
    declare cur_array cursor for
        select code,row1 from array_g2p;       
    #核心业务游标    
    declare cur_g2p cursor for 
        SELECT B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,rowindex from basic_excel where sheetname = basic_memory__il8n('group2permission','basic_group_2_permission',1)         
            and guid = in_guid            
            and rowindex > 2 order by rowindex;   
    declare CONTINUE HANDLER FOR SQLSTATE '02000' SET fig = 1;       
        
    #如果 guid 是空的

    if in_guid is null then        
        set out_state = 0;        
        set out_msg = basic_memory__il8n('guidNULL','basic_excel',1);   
        insert into basic_log (type,username,msg) values (1,'system','basic_group_2_permission__import wrong , no guid , line 35' );            
        leave pro_main;
    end if;
        
    #初始化临时内存表,用于模拟数组,处理EXCEL表头列

    drop TEMPORARY  table if exists array_g2p;
    create TEMPORARY  table array_g2p (
        code varchar(2)        
        ,row1 varchar(200)         
    ) engine = memory ;      

    #检查第一行业务数据, C2 必定是 管理员用户组编码 10 ,如果空,则说明EXCEL错误
    select C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,maxcolumn into C_,D_,E_,F_,G_,H_,I_,J_,K_,L_,M_,N_,O_,P_,Q_,R_,S_,T_,U_,V_,@maxcolumn from basic_excel 
        where guid = in_guid 
        and rowindex = 2 
        and sheetname = basic_memory__il8n('group2permission','basic_group_2_permission',1) ;  
    if C_ is null then          
        set out_state = 0;        
        set out_msg = basic_memory__il8n('guidWrong','basic_excel',1);        
        insert into basic_log (type,username,msg) values (1,'system','basic_group_2_permission__import wrong , key group cde 10 missing , line 54' );    
        leave pro_main;
    end if;    
    
    set out_state = \"\";
    set out_msg = \"\";  
        
    #开始检查用户组,判断用户组是否正确

    insert into array_g2p values 
        ('C',	C_),
        ('D',	D_),
        ('E',	E_),
        ('F',	F_),
        ('G',	G_),
        ('H',	H_),
        ('I',	I_),
        ('J',	J_),
        ('K',	K_),
        ('L',	L_),
        ('M',	M_),
        ('N',	N_),
        ('O',	O_),
        ('P',	P_),
        ('Q',	Q_),
        ('R',	R_),
        ('S',	S_),
        ('T',	T_),
        ('U',	U_),
        ('V',	V_);        
    #select * from array_g2p;        
    set @columns = '';    
    set @groups = '';    
    set @groupids = '';
    set fig = 0;    
    open cur_array;        
    fetch cur_array into code_,row1_;        
    set @columnIndex = 1;
    while_arr:while( fig = 0 ) do           
        if row1_ is null then        
            set out_msg = concat(out_msg,code_,'1','null;');     
            leave while_arr;           
        end if;                         
        set @temp = null;
        select id into @temp from basic_group where code = row1_;     
        #如果用户组不存在   
        if @temp is null then                
            set out_state = 2;     
            if row1_ is null then
                set row1_ = 'null';
            end if;       
            set out_msg = concat( basic_memory__il8n('wrongGroupcode','basic_group_2_permission',1), row1_ );          
            insert into basic_log (type,username,msg) values (1,'system','basic_group_2_permission__import wrong , group worng , line 108' );    
            leave pro_main;
        end if;
        set @columns = concat(@columns,\",\",code_);      
        set @groups = concat(@groups,\",\",row1_);              
        set @groupids = concat(@groupids,\",\",@temp);      
    fetch cur_array into code_,row1_;    
    end while while_arr;
    close cur_array;  
    
    set @columns = concat(@columns,\",\");    
    set @groupids = concat(@groupids,\",\");      
    set @groups = concat(@groups,\",\");  
    set @columncount = basic_stringcount(@columns,\",\");
    #select @columns,@groupids,@columncount;         

    #开始拼凑SQL语句,准备插入 用户组-权限 的对应关系

    select max(rowindex) into @maxrow from basic_excel where guid = in_guid and sheetname = basic_memory__il8n('group2permission','basic_group_2_permission',1) ;             
    set @sql_insert = \"insert into basic_group_2_permission (id_permission,id_group,code_permission,code_group,cost,credits) values \";    
    set @x = 0;
    set fig = 0;    
    open cur_g2p;            
    fetch cur_g2p into B_,C_,D_,E_,F_,G_,H_,I_,J_,K_,L_,M_,N_,O_,P_,Q_,R_,S_,T_,U_,V_,rowindex_;    
    #开启游标,逐行检查数据

    cur_while: while (fig = 0) do    
        #先检查这一行的B列数据,也就是权限编码   
        set @temp = null;   
        select id into @temp from basic_permission where code = B_;        
        if @temp is null then                
            set out_state = 3;                        
            select rowindex_,@sql_insert;            
            if row1_ is null then             
                set row1_ = 'null' ;                               
            end if;
            set out_msg = concat( basic_memory__il8n('wrongPermissioncode','basic_group_2_permission',1), row1_ );  
            insert into basic_log (type,username,msg) values (1,'system','basic_group_2_permission__import wrong , permission worng , line 141' );    
            leave pro_main;
        end if;                
        
        set @sql_values = '';  
        set @p = 0;         
        set @spotpos = 1;   
        set @spotpos2 = 1;   
        set @spotpos_ = 1;   
        set @spotpos2_ = 1;    
        set @spotpos__ = 1;   
        set @spotpos2__ = 1;                   
        set @tempvalue = '';    
        set @sql_values = '';  
        set @keyindex = '';     
        set @alphaindex = '';                                  

        #循环检查这一行每一个单元格里的内容
        inerLoop: LOOP
            SET @p = @p + 1;   
            set @spotpos = LOCATE(',', @columns,@spotpos2);
			set @spotpos2 = LOCATE(',', @columns,@spotpos+1);
            set @alphaindex = SUBSTRING(@columns,@spotpos+1,@spotpos2-@spotpos-1);                

            set @spotpos_ = LOCATE(',', @groupids,@spotpos2_);
			set @spotpos2_ = LOCATE(',', @groupids,@spotpos_+1);
            set @groupid = SUBSTRING(@groupids,@spotpos_+1,@spotpos2_-@spotpos_-1);   
            
            set @spotpos__ = LOCATE(',', @groups,@spotpos2__);
			set @spotpos2__ = LOCATE(',', @groups,@spotpos__+1);
            set @groupcode = SUBSTRING(@groups,@spotpos__+1,@spotpos2__-@spotpos__-1);             

            if @groupid is null or @groupid = '' then  
                set out_state = 4;                    
                set out_msg = 'worng id or code';
                leave pro_main;
            end if;         
		
            if @alphaindex = 'C' then set @tempvalue = C_; end if; 		
            if @alphaindex = 'D' then set @tempvalue = D_; end if; 		
            if @alphaindex = 'E' then set @tempvalue = E_; end if; 		
            if @alphaindex = 'F' then set @tempvalue = F_; end if; 		
            if @alphaindex = 'G' then set @tempvalue = G_; end if; 		
            if @alphaindex = 'H' then set @tempvalue = H_; end if; 		
            if @alphaindex = 'I' then set @tempvalue = I_; end if; 		
            if @alphaindex = 'J' then set @tempvalue = J_; end if; 		
            if @alphaindex = 'K' then set @tempvalue = K_; end if; 		
            if @alphaindex = 'L' then set @tempvalue = L_; end if; 		
            if @alphaindex = 'M' then set @tempvalue = M_; end if; 		
            if @alphaindex = 'N' then set @tempvalue = N_; end if; 		
            if @alphaindex = 'O' then set @tempvalue = O_; end if; 		
            if @alphaindex = 'P' then set @tempvalue = P_; end if; 		
            if @alphaindex = 'Q' then set @tempvalue = Q_; end if; 		
            if @alphaindex = 'R' then set @tempvalue = R_; end if; 		
            if @alphaindex = 'S' then set @tempvalue = S_; end if; 		
            if @alphaindex = 'T' then set @tempvalue = T_; end if; 		
            if @alphaindex = 'U' then set @tempvalue = U_; end if; 	            

            if (@tempvalue is null) or @tempvalue = '' then         
               set out_msg = '';               
            else    
               set @x = @x + 1;              
               set @sql_insert = concat(@sql_insert,\"(\",@temp,\",\",@groupid,\",'\",B_,\"','\",@groupcode,\"',\",@tempvalue,\") ,\");
            end if;   
  
            IF @p < @columncount-1 THEN       
               ITERATE inerLoop;
            END IF;
            LEAVE inerLoop;
        END LOOP inerLoop;          
     
    fetch cur_g2p into B_,C_,D_,E_,F_,G_,H_,I_,J_,K_,L_,M_,N_,O_,P_,Q_,R_,S_,T_,U_,V_,rowindex_;
    end while cur_while;
    close cur_g2p;             

    #先删除原先已有的那些用户组原有的权限关系
    set @sql_delete = concat( \"delete from basic_group_2_permission where id_group in (99999\",@groupids,\"99998);\");    
    PREPARE stmt FROM @sql_delete;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;    
  
    set @sql_insert = SUBSTRING( @sql_insert , 1 , LENGTH(@sql_insert)-1 );    
    #select @x;
    #select @columns,@groups,@groupids;     
    #select @sql_insert;      

    PREPARE stmt FROM @sql_insert;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;      
    set out_state = 1;    
    set out_msg = 'done';

    drop TEMPORARY table if exists array_g2p; 
    delete from basic_excel where guid = in_guid; 
END;;


-- ----------------------------
-- Procedure structure for basic_group_2_user__update
-- ----------------------------
DROP PROCEDURE IF EXISTS `basic_group_2_user__update`;;

CREATE PROCEDURE `basic_group_2_user__update`(
in in_username varchar(200)
,in in_groupcodes varchar(200)
,out out_state int
,out out_msg varchar(200)
)
pro_main:BEGIN
/*
更新一个用户的用户组


@version 201301
@author wei1224hf@gmail.com
*/
declare _hasmain,
        _grouptype,        
        _groupid,        
        _userid,
        _groupcount int ;
declare _groupcode,
        _groupname varchar(100) ;

    if in_username = 'admin' or in_username = 'guest' or in_username is null then        
        set out_state = 0;        
        set out_msg = 'username wrong';        
        leave pro_main;
    end if;    
    select id into _userid from basic_user where username = in_username;    
    if _userid is NULL then        
        set out_state = 10;        
        set out_msg = 'username wrong';        
        leave pro_main;
    end if;
    START TRANSACTION;       
    delete from basic_group_2_user where username = in_username; 
    set _groupcount = basic_stringcount( in_groupcodes , ',' ) + 1;                
    set @pos = 0;        
    set @loop = 0;
    while _groupcount > 0 do        
        set @pos2 = LOCATE(',', in_groupcodes,@pos+1);  
        if @pos2 = 0 then        
            set _groupcode = SUBSTRING(in_groupcodes,@pos+1,char_length(in_groupcodes));                  
        else         
            set _groupcode = SUBSTRING(in_groupcodes,@pos+1,@pos2-1-@pos);                  
            set @pos = @pos2;             
        end if;   
        set _grouptype = NULL;
        select type,id,name into _grouptype,_groupid,_groupname from basic_group where code = _groupcode;        
        if _grouptype is NULL then                
            set out_state = 2;            
            set out_msg = 'wrong groupcode';                        
            rollback;
            leave pro_main;            
        elseif _grouptype = 2 then        
            if _hasmain = 1 then                        
                set out_state = 3;                
                set out_msg = 'one department only';                                
                rollback;
                leave pro_main;                
            else     
                update basic_user set
                    group_id = _groupid
                    ,group_name = _groupname
                    ,group_code = _groupcode  where username = in_username;       
                set _hasmain = 1;
            end if;
        end if;        
        insert into basic_group_2_user (
               username
               ,code_group
               ,id_user
               ,id_group
        ) values (
               in_username               
               ,_groupcode               
               ,_userid        
               ,_groupid
        );
        set _groupcount = _groupcount - 1;
    end while;  
    update basic_user set group_all = in_groupcodes where username = in_username;           
    COMMIT;    

    set out_state = 1;    
    set out_msg = 'OK';
END;;


-- ----------------------------
-- Procedure structure for basic_immunity
-- ----------------------------
DROP PROCEDURE IF EXISTS `basic_immunity`;;

CREATE PROCEDURE `basic_immunity`()
BEGIN
	#Routine body goes here...

END;;


-- ----------------------------
-- Procedure structure for basic_memory__init
-- ----------------------------
DROP PROCEDURE IF EXISTS `basic_memory__init`;;

CREATE PROCEDURE `basic_memory__init`()
BEGIN
/*
初始化系统内存表.
内存表中主要存放一些会被存储过程频繁读取调用的数据,
比如系统运行参数,各个模块的下拉列表中的业务数据,语言包,各个业务表的主键

@version 201209
@author wei1224hf@gmail.com
*/           
    declare id_ int;       #业务表索引





    
    #初始化业务表下拉列表参数
    delete from basic_memory where extend5 like '%\_%\_%\__%' ;
    insert into basic_memory (code,type,extend4,extend5)
        select code,1,value,reference from basic_parameter where reference like '%\_%\_%\__%' ;

    #初始化业务表索引    
    delete from basic_memory where code = 'basic_person' and type = 2 ;
    select max(id) from basic_person into id_;    
    if id_ is null then     
        set id_ = 0;        
    end if;
    insert into basic_memory (code,extend1,extend2,type) values ('basic_person',id_,0,'2');        

    delete from basic_memory where code = 'basic_user' and type = '2' ;
    select max(id) from basic_user into id_;    
    if id_ is null then     
        set id_ = 0;        
    end if;
    insert into basic_memory (code,extend1,extend2,type) values ('basic_user',id_,0,'2'); 
    
    delete from basic_memory where code = 'basic_group' and type = '2' ;
    select max(id) from basic_group into id_;    
    if id_ is null then     
        set id_ = 0;        
    end if;
    insert into basic_memory (code,extend1,extend2,type) values ('basic_group',id_,0,'2');         

    delete from basic_memory where code = 'basic_department' and type = '2' ;
    select max(id) from basic_department into id_;    
    if id_ is null then     
        set id_ = 0;        
    end if;
    insert into basic_memory (code,extend1,extend2,type) values ('basic_department',id_,0,'2');     
    
    delete from basic_memory where code = 'basic_permission' and type = '2' ;
    select max(id) from basic_permission into id_;    
    if id_ is null then     
        set id_ = 0;        
    end if;
    insert into basic_memory (code,extend1,extend2,type) values ('basic_permission',id_,0,'2');      
    
    delete from basic_memory where code = 'basic_workflow' and type = '2' ;
    select max(id) from basic_workflow into id_;    
    if id_ is null then     
        set id_ = 0;        
    end if;
    insert into basic_memory (code,extend1,extend2,type) values ('basic_workflow',id_,0,'2');    
    
    delete from basic_memory where code = 'basic_log' and type = '2' ;
    select max(id) from basic_log into id_;    
    if id_ is null then     
        set id_ = 0;        
    end if;
    insert into basic_memory (code,extend1,extend2,type) values ('basic_log',id_,0,'2');          

    delete from basic_memory where code = 'basic_department' and type = '2' ;
    select max(id) from basic_department into id_;    
    if id_ is null then     
        set id_ = 0;        
    end if;
    insert into basic_memory (code,extend1,extend2,type) values ('basic_department',id_,0,'2');   
    
    /****************************************************************************************/
    delete from basic_memory where code = 'education_subject' and type = '2' ;
    select max(id) from education_subject into id_;    
    if id_ is null then     
        set id_ = 0;        
    end if;
    insert into basic_memory (code,extend1,extend2,type) values (  'education_subject',id_,0,'2');   


    delete from basic_memory where code = 'education_paper' and type = '2' ;
    select max(id) from education_paper into id_;    
    if id_ is null then     
        set id_ = 0;        
    end if;
    insert into basic_memory (code,extend1,extend2,type) values (  'education_paper',id_,0,'2');         

    delete from basic_memory where code = 'education_paper_log' and type = '2' ;
    select max(id) from education_paper_log into id_;    
    if id_ is null then     
        set id_ = 0;        
    end if;
    insert into basic_memory (code,extend1,extend2,type) values (  'education_paper_log',id_,0,'2');  

    delete from basic_memory where code like 'education_exam' and type = '2' ;
    select max(id) from education_exam into id_;    
    if id_ is null then     
        set id_ = 0;        
    end if;
    insert into basic_memory (code,extend1,extend2,type) values (  'education_exam',id_,0,'2');  

    delete from basic_memory where code like 'education_exam_2_class' and type = '2' ;
    select max(id) from education_exam_2_class into id_;    
    if id_ is null then     
        set id_ = 0;        
    end if;
    insert into basic_memory (code,extend1,extend2,type) values (  'education_exam_2_class',id_,0,'2');  

    delete from basic_memory where code like 'education_exam_2_student' and type = '2' ;
    select max(id) from education_exam_2_student into id_;    
    if id_ is null then     
        set id_ = 0;        
    end if;
    insert into basic_memory (code,extend1,extend2,type) values (  'education_exam_2_student',id_,0,'2');  

    delete from basic_memory where code like 'education_exam_unified' and type = '2' ;
    select max(id) from education_exam_unified into id_;    
    if id_ is null then     
        set id_ = 0;        
    end if;
    insert into basic_memory (code,extend1,extend2,type) values (  'education_exam_unified',id_,0,'2');  

    delete from basic_memory where code like 'education_question' and type = '2' ;
    select max(id) from education_question into id_;    
    if id_ is null then     
        set id_ = 0;        
    end if;
    insert into basic_memory (code,extend1,extend2,type) values (  'education_question',id_,0,'2');    
    
    delete from basic_memory where code = 'education_question_log' and type = '2' ;
    select max(id) from education_question_log into id_;    
    if id_ is null then     
        set id_ = 0;        
    end if;
    insert into basic_memory (code,extend1,extend2,type) values (  'education_question_log',id_,0,'2');     
    
    delete from basic_memory where code = 'education_question_log_wrongs' and type = '2' ;
    select max(id) from education_question_log_wrongs into id_;    
    if id_ is null then     
        set id_ = 0;        
    end if;
    insert into basic_memory (code,extend1,extend2,type) values (  'education_question_log_wrongs',id_,0,'2');                   

    delete from basic_memory where code like 'education_student' and type = '2' ;
    select max(id) from education_student into id_;    
    if id_ is null then     
        set id_ = 0;        
    end if;
    insert into basic_memory (code,extend1,extend2,type) values (  'education_student',id_,0,'2');        

    delete from basic_memory where code like 'education_teacher' and type = '2' ;
    select max(id)  into id_ from education_teacher;
    if id_ is null then     
        set id_ = 0;        
    end if;
    insert into basic_memory (code,extend1,extend2,type) values (  'education_teacher',id_,0,'2');    

END;;


-- ----------------------------
-- Procedure structure for basic_permission__import
-- ----------------------------
DROP PROCEDURE IF EXISTS `basic_permission__import`;;

CREATE PROCEDURE `basic_permission__import`(IN in_guid char(36),OUT out_state int,OUT out_msg varchar(200),OUT out_ids varchar(2000))
pro_main:BEGIN 
/*
权限导入
服务端上传一个 EXCEL 文件,并读取存储到 basic_excel 中

然后此存储过程分析业务数据插入到业务表


@version 201212
@author wei1224hf@gmail.com
*/
    declare fig int;         
    declare rowindex__ int;
    declare A_,B_,C_,D_,E_,F_,G_ varchar(200);    
    declare code_,row1_,row2_ varchar(200); 
    declare id_creater_,id_creater_permission_,rowindex_ int default 0;    

    declare cur_array cursor for     
        SELECT code,row1,row2 from array_permission;   
    declare cur_permission cursor for 
        SELECT A,B,C,D,E,F,G,rowindex from basic_excel where sheetname = basic_memory__il8n('permission','basic_permission',1)         
            and guid = in_guid            
            and rowindex > 1
            order by rowindex;         
     
    #以下变量用于游标
    declare CONTINUE HANDLER FOR SQLSTATE '02000' SET fig = 1;       
    
    if in_guid is null then        
        set out_state = 0;        
        set out_msg = basic_memory__il8n('guidNULL','basic_excel',1);    
        leave pro_main;
    end if;
    
    drop TEMPORARY table if exists array_permission;
    create  TEMPORARY  table array_permission (
        code varchar(2)        
        ,row1 varchar(200)   
        ,row2 varchar(200)        
    ) engine = memory ;         
    
    select A,B,C,D,E,F,id_creater into A_,B_,C_,D_,E_,F_,id_creater_ from basic_excel 
        where guid = in_guid 
        and rowindex = 1 
        and sheetname = basic_memory__il8n('permission','basic_permission',1); 
    if A_ is null then
        set out_msg = basic_memory__il8n('guidWrong','basic_excel',1); 
        set out_state = 0;
        leave pro_main;
    end if;     

    set out_state = 0;    
    set out_msg = \"\";    
    set out_ids = \"\";                

    #select 'A', basic_memory__il8n( A_,'basic_permission', 2) ,A_ ,'G', basic_memory__il8n( G_,'basic_permission', 2) ,G_ ;leave pro_main;
    insert into array_permission values 
        ('A', basic_memory__il8n( A_,'basic_permission', 2) ,A_ ),        
        ('B', basic_memory__il8n( B_,'basic_permission', 2) ,B_ ),        
        ('C', basic_memory__il8n( C_,'basic_permission', 2) ,C_ ),        
        ('D', basic_memory__il8n( D_,'basic_permission', 2) ,D_ ),        
        ('E', basic_memory__il8n( E_,'basic_permission', 2) ,E_ ),        
        ('F', basic_memory__il8n( F_,'basic_permission', 2) ,F_ )
    ;
    #select * from array_permission;

    set @sufficient = \"name,code,type,remark,icon,path\";
    set @keys = \"\";
    set @columns = \"\";

    set fig = 0;    
    open cur_array;        
    fetch cur_array into code_,row1_,row2_;        
    while( fig = 0 ) do      
    
        if row1_ is null then        
            set out_msg = concat(row2_,\" \",code_,\"2\",\" \",\"wrong column\");                             
        elseif FIND_IN_SET(row1_,@sufficient) = 0 then             
            set out_msg = concat(row2_,\" \",code_,\"2\",\" \",\"wrong column\");                   
            set out_state = 0;                
            leave pro_main;                
        else            
            set @keys = concat(\",\",row1_,@keys);       
            set @columns = concat(\",\",code_,@columns);         
        end if;  

    fetch cur_array into code_,row1_,row2_;     
    end while;
    close cur_array;      
    if FIND_IN_SET('name',@keys) = 0 then    
        set out_state = 7;
        set out_msg = concat(basic_memory__il8n('columnMissing','basic_excel',1),\" \",basic_memory__il8n('name','basic_permission',1));                  
        leave pro_main;        
    elseif FIND_IN_SET('type',@keys) = 0 then    
        set out_state = 0;
        set out_msg = concat(basic_memory__il8n('columnMissing','basic_excel',1),\" \",basic_memory__il8n('type','basic_permission',1));                
        leave pro_main;        
    elseif FIND_IN_SET('code',@keys) = 0 then    
        set out_state = 0;
        set out_msg = concat(basic_memory__il8n('columnMissing','basic_excel',1),\" \",basic_memory__il8n('code','basic_permission',1));                 
        leave pro_main;
    end if; 

    #select @keys;    
    set @keys = concat(@keys,\",\");        
    set @columns = concat(@columns,\",\");    
    set @columncount = basic_stringcount(@keys,\",\");    
    select max(rowindex) into @maxrow from basic_excel where guid = in_guid and sheetname = basic_memory__il8n('permission','basic_permission',1);  
    set @sql_insert = concat(\"insert into basic_permission (id\",@keys,\"status) values \");        

    set fig = 0;    
    open cur_permission; 
    fetch cur_permission into A_,B_,C_,D_,E_,F_,G_,rowindex_;    
    while ( fig = 0 ) do        
        set @sql_values = '';  
        set @p = 0;         
        set @spotpos = 1;   
        set @spotpos2 = 1;   
        set @spotpos_ = 1;   
        set @spotpos2_ = 1;             
        set @tempvalue = '';    
        set @student_sql_values = '';                            
        set @person_sql_values = '';           

        inerLoop: LOOP
            SET @p = @p + 1;  
            set @spotpos = LOCATE(',', @columns,@spotpos2);
            set @spotpos2 = LOCATE(',', @columns,@spotpos+1);
            set @alphaindex = SUBSTRING(@columns,@spotpos+1,@spotpos2-@spotpos-1);                

            set @spotpos_ = LOCATE(',', @keys,@spotpos2_);
            set @spotpos2_ = LOCATE(',', @keys,@spotpos_+1);
            set @keyindex = SUBSTRING(@keys,@spotpos_+1,@spotpos2_-@spotpos_-1); 
            
            #select @alphaindex,@keyindex;
            
            if @alphaindex = 'A' then set @tempvalue = A_; end if; 
            if @alphaindex = 'B' then set @tempvalue = B_; end if; 
            if @alphaindex = 'C' then set @tempvalue = C_; end if; 
            if @alphaindex = 'D' then set @tempvalue = D_; end if; 
            if @alphaindex = 'E' then set @tempvalue = E_; end if; 
            if @alphaindex = 'F' then set @tempvalue = F_; end if; 
            if @alphaindex = 'G' then set @tempvalue = G_; end if;             

            if @keyindex = 'type' then           
                set @temp = null;
                select code into @temp from basic_memory where extend5 = 'basic_permission__type' and extend4 = @tempvalue;            
                #select @temp;
                if @temp is null then 
                    set out_state = 0;
                    set out_msg = concat( basic_memory__il8n('wrongType','basic_permission',1) ,\" \",@tempvalue,\" \",@alphaindex,rowindex_);                    
                    delete from basic_excel where guid = in_guid;
                    leave pro_main;
                end if;     
                set @tempvalue = @temp;  
            elseif @keyindex = 'code' then           
                set @codetemp = 0;       
                select count(*) into @codetemp from basic_permission where code = @tempvalue ;                  
                if @codetemp > 0 then                                        
                    set out_state = 0;                        
                    set out_msg = concat(basic_memory__il8n('existCode','basic_permission',1),\" \",@tempvalue,\" \",@alphaindex,rowindex_);  
                    delete from basic_excel where guid = in_guid;                   
                    leave pro_main;
                end if;                                               
            end if;       
                     
            set @sql_values = concat(@sql_values,\",'\",@tempvalue,\"'\");
                     
            IF @p < @columncount-1 THEN
               ITERATE inerLoop;
            END IF;
            LEAVE inerLoop;            
        END LOOP inerLoop;          
        set @id = basic_memory__index('basic_permission');   
        set out_ids = concat(out_ids,\",\",@id);       
        if rowindex_ = @maxrow then                 
            set @sql_insert = concat(@sql_insert,\"('\",@id,\"'\",@sql_values,\",1) ;\");            
        else
            set @sql_insert = concat(@sql_insert,\"('\",@id,\"'\",@sql_values,\",1) ,\");            
        end if;
        #select @sql_values;         
        #leave pro_main;
    fetch cur_permission into A_,B_,C_,D_,E_,F_,G_,rowindex_;    
    end while;
    close cur_permission;    

    #select @sql_insert;    

    PREPARE stmt FROM @sql_insert;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;       
    set out_state = 1;    
    set out_msg = 'done';
    delete from basic_excel where guid = in_guid;         
END;;


-- ----------------------------
-- Procedure structure for basic_user__action
-- ----------------------------
DROP PROCEDURE IF EXISTS `basic_user__action`;;

CREATE PROCEDURE `basic_user__action`(IN `in_username` varchar(200),IN `in_session` varchar(200),IN in_action varchar(32),OUT out_state int,OUT out_msg varchar(200) )
BEGIN
/*
用户在前端每一次操作,
如果那个操作是设计扣费或者记积分的,
都会调用此存储过程


用户的 积分-金币 策略,会根据用户组的关系来读取出 最该加积分-最低扣金币 的关系


@author wei1224hf@gmail.com
@version 201209
*/
    declare username_ varchar(200);
    declare session_ varchar(200);        
    declare permissions_ varchar(1000);    
    declare actionsub char(2);    
    declare cost_ int;    
    declare credits_ int;    
    declare mymoney_ int;

    select session,permissions into session_,permissions_ from basic_user_session where username = in_username;                  
    if session_ is null then
        set out_msg = 'no login';                    
        set out_state = 0;                    
        
    elseif ( in_session  = md5( concat(session_, hour(now())-0) ) )or                                                
           ( in_session  = md5( concat(session_, hour(now())-2) ) )or                       
           ( in_session  = md5( concat(session_, hour(now())-1) ) ) then  
        #这个用户的 session 是符合条件的,接下来要判断这个用户有没有这个权限了    
        if  locate(concat(',',in_action,','), permissions_) = 0 then                    
            set out_state = 0;                         
            set out_msg = 'access denied';       
        else      
            #拥有这个权限,那么就需要更新 session 表     
            update basic_user_session set 
                   lastaction = in_action
                   ,lastactiontime=now()
                   ,count_actions = count_actions + 1
            where username = in_username;     
            
            #如果这个操作是涉及扣金币的话 
            set actionsub = right(in_action,2);    
            if actionsub > '09' then                        
               select min(cost),max(credits) into cost_,credits_  from 
                (
                SELECT
                basic_group_2_permission.cost,
                basic_group_2_permission.credits
                FROM
                    basic_permission
                    Right Join basic_group_2_permission ON basic_permission.id = basic_group_2_permission.id_permission
                    Right Join basic_group_2_user ON basic_group_2_permission.id_group = basic_group_2_user.id_group
                    Right Join basic_user ON basic_group_2_user.id_user = basic_user.id
                WHERE
                basic_permission.code =  in_action AND
                basic_user.username =  in_username
                ) t;                            

                select money into mymoney_ from basic_user where username = in_username;                            
                if mymoney_ < cost_ then                            
                   set out_state = 2 ;
                   set out_msg = concat('need money:',cost_,'; but I have:', mymoney_);                               
                else   
                    update basic_user set money = money - cost_, money2 = money2 + credits_ where username = in_username;  
                    set out_state = 1;                         
                    set out_msg = concat('money cost ',cost_,'; now I have ', mymoney_ - cost_ );                      
                end if;                
            else         
                #如果这个操作不必扣金币,就直接输出. 一般情况下 , 查询 查看 等常用的功能,都是这样的   
                set out_state = 1;                         
                set out_msg = 'success';     
            end if;  
        end if;
    else        
        set out_msg = 'wrong session';                                     
        #set out_msg = concat( md5( concat(session_, hour(now())-0) )  , ' ', md5( concat(session_, hour(now())-1) )  , ' ', md5( concat(session_, hour(now())-2) ), ' ', md5( concat(session_, hour(now())+1) )  );
        set out_state = 0;                    
    end if;                
END;;


-- ----------------------------
-- Procedure structure for basic_user__delete
-- ----------------------------
DROP PROCEDURE IF EXISTS `basic_user__delete`;;

CREATE PROCEDURE `basic_user__delete`(
in in_user_ids varchar(80),
out out_state int,
out out_msg varchar(80)
)
pro_main:BEGIN
/*
删除多个用户
涉及多个用户组的操作

@author wei1224hf@gmail.com
@version 201212
*/
declare _person_id,
        _user_id,
        _student_id,
        _teacher_id,
        _usercount int ;      
    
    if in_user_ids is NULL then
        set out_state = 0;
        set out_msg = 'null';          
        leave pro_main;        
    end if;
    START TRANSACTION;       
    set _usercount = basic_stringcount( in_user_ids , ',' ) + 1;                
    set @pos = 0;        
    while _usercount > 0 do        
        set @pos2 = LOCATE(',', in_user_ids,@pos+1);  
        if @pos2 = 0 then        
            set _user_id = SUBSTRING(in_user_ids,@pos+1,char_length(in_user_ids));                  
        else         
            set _user_id = SUBSTRING(in_user_ids,@pos+1,@pos2-1-@pos);                  
            set @pos = @pos2;             
        end if;           
        set _person_id = NULL;
        select person_id into _person_id from basic_user where id = _user_id;        
        if _person_id is NULL then                
            set out_state = 0;            
            set out_msg = 'wrong user';  
            rollback;          
            leave pro_main;
        end if;
        delete from basic_person where id = _person_id;        
        delete from basic_user where id = _user_id;        
        delete from education_student where id_user = _user_id;        
        delete from education_teacher where id_user = _user_id;        
        set _usercount = _usercount - 1;        
    end while;
    commit;    

    set out_state = 1;    
    set out_msg = 'OK';
END;;


-- ----------------------------
-- Procedure structure for basic_user__import
-- ----------------------------
DROP PROCEDURE IF EXISTS `basic_user__import`;;

CREATE PROCEDURE `basic_user__import`(IN in_guid char(36),OUT out_state int,OUT out_msg varchar(200),OUT out_ids varchar(2000))
pro_main:BEGIN
/**
批量导入用户信息

用户将一个EXCEL文件上传到系统,系统将EXCEL文件中的内容读取到 basic_excel 表

数据库存储过程分析basic_excel表中的内容,读取出业务数据,插入到各个业务表中



前提条件:
basic_group , basic_permission 表已经被创建并有数据
basic_excel 有待处理的业务数据


@param in_guid 标识一次EXCEL文件导入的唯一健

@param out_state 存储过程执行结果,1为正确通过,其他都表示错误.系统处理错误,2 3 4 为业务数据错误

@param out_msg 存储过程执行后返回的执行描述结果
@param out_ids 成功插入多个用户后,返回的用户表编号集


@version 201212
@author wei1224hf@gmail.com
@qqgroup 135426431 
*/
    declare fig int;         
    declare rowindex__ int;
    declare A_,B_,C_,D_,E_,F_,G_ varchar(200);    
    declare code_,row1_,row2_ varchar(200); 
    declare id_creater_,id_creater_group_,rowindex_ int default 0;        
    declare code_creater_group_ varchar(200) default '0';

    #内存表游标,模拟数组
    declare cur_array cursor for     
        SELECT code,row1,row2 from array_user;           
    #核心业务游标
    declare cur cursor for 
        SELECT A,B,C,D,E,F,G,rowindex from basic_excel where sheetname = basic_memory__il8n('user','basic_user',1)         
            and guid = in_guid            
            and rowindex > 1
            order by rowindex;         
     
    #以下变量用于游标
    declare CONTINUE HANDLER FOR SQLSTATE '02000' SET fig = 1;       
        
    #如果 guid 是空的

    if in_guid is null then        
        set out_state = 0;        
        set out_msg = 'null guid';        
        insert into basic_log (type,username,msg) values (1,'system','basic_user__import wrong , no guid , line 47' );  
        leave pro_main;
    end if;
        
    #创建内存表,模拟数组
    drop TEMPORARY table if exists array_user;
    create  TEMPORARY  table array_user (
        code varchar(2)        
        ,row1 varchar(200)   
        ,row2 varchar(200)        
    ) engine = memory ;         
    
    select A,B,C,D,E,F,G,id_creater into A_,B_,C_,D_,E_,F_,G_,id_creater_ from basic_excel 
        where guid = in_guid 
        and rowindex = 1 
        and sheetname = basic_memory__il8n('user','basic_user',1); 
    if A_ is NULL then    
        set out_state = 0;        
        set out_msg = 'wrong guid';        
        leave pro_main;
    end if;  
    
    if id_creater_ = 1 then            
        #是超级管理员 admin 导入的数据        
        set id_creater_group_ = 1;        
        set code_creater_group_ = '10';
    else   
        select group_id,group_code into id_creater_group_,code_creater_group_ from basic_user where id =  id_creater_;
    end if;       
    
    set out_state = 0;    
    set out_msg = \"\";    
    set out_ids = \"\";          

    set @sql_keys = '';        
    set @sql_keys_excel = '';    
    set @sql_values = '';
    insert into array_user values 
        ('A', basic_memory__il8n( A_,'basic_user', 2) ,A_ ),        
        ('B', basic_memory__il8n( B_,'basic_user', 2) ,B_ ),        
        ('C', basic_memory__il8n( C_,'basic_user', 2) ,C_ ),        
        ('D', basic_memory__il8n( D_,'basic_user', 2) ,D_ ),        
        ('E', basic_memory__il8n( E_,'basic_user', 2) ,E_ ),        
        ('F', basic_memory__il8n( F_,'basic_user', 2) ,F_ ),        
        ('G', basic_memory__il8n( G_,'basic_user', 2) ,G_ )
	;
    #select * from array_user;
	
	set @sufficient = \"username,password,money,group_code,type\";
	set @keys = \"\";
	set @columns = \"\";
		
    set fig = 0;    
    open cur_array;        
    fetch cur_array into code_,row1_,row2_;        
    set @columnIndex = 1;
    while( fig = 0 ) do      
	
        if row1_ is null then        
			set out_msg = concat(row2_,\" \",code_,\"1\",\" \",\"wrong column\");                             
		elseif FIND_IN_SET(row1_,@sufficient) = 0 then             
			set out_msg = concat(row2_,\" \",code_,\"1\",\" \",\"wrong column\");                   
			set out_state = 0;    
            
			leave pro_main;                
		else            
			set @keys = concat(@keys,row1_,\",\");       
			set @columns = concat(@columns,code_,\",\");         
		end if;  

    fetch cur_array into code_,row1_,row2_;     
    end while;
    close cur_array;      
        
    set @keys = concat(\",\",@keys);  
    set @sql_insert = concat(\"insert into basic_user (
        id
        ,person_id
        ,person_name
        ,person_cellphone        
        ,person_email      
        ,group_id
        ,group_name              
        ,id_creater        
        ,id_creater_group        
        ,code_creater_group
        \",@keys,\"status ) values \");      
    set @user2group_sql_insert = \"insert into basic_group_2_user ( id_user,id_group,username,code_group,type ) values \";    
    set @person_insert = \"insert into basic_person (name,id) values \";    
    set @student_insert = \"insert into education_student ( code,class_code,id,id_person,id_user ) values \";    
    set @teacher_insert = \"insert into education_teacher ( code,department_code,id,id_person,id_user ) values \";    
    set @count_student = 0;
    set @count_teacher = 0;
    set @columns = concat(\",\",@columns);        
    set @columncount = basic_stringcount(@keys,\",\");   
    select max(rowindex) into @maxrow from basic_excel where guid = in_guid and sheetname = basic_memory__il8n('user','basic_user',1);                

    set fig = 0;    
    open cur; 
    fetch cur into A_,B_,C_,D_,E_,F_,G_,rowindex_;
    cur_while: while (fig = 0) do      

        set @sql_values = '';  
        set @p = 0;         
        set @spotpos = 1;   
        set @spotpos2 = 1;         
        set @spotpos_ = 1;   
        set @spotpos2_ = 1;             
        set @tempvalue = ''; 
                
        set @id_person = basic_memory__index('basic_person');               
        set @id_user = basic_memory__index('basic_user');          
                       
        inerLoop: LOOP
            SET @p = @p + 1;  
            set @spotpos = LOCATE(',', @columns,@spotpos2);
			set @spotpos2 = LOCATE(',', @columns,@spotpos+1);
            set @alphaindex = SUBSTRING(@columns,@spotpos+1,@spotpos2-@spotpos-1);  
            
            set @spotpos_ = LOCATE(',', @keys,@spotpos2_);
			set @spotpos2_ = LOCATE(',', @keys,@spotpos_+1);
            set @keyindex = SUBSTRING(@keys,@spotpos_+1,@spotpos2_-@spotpos_-1);    
                                          
            if @alphaindex = 'A' then set @tempvalue = A_; end if; 
            if @alphaindex = 'B' then set @tempvalue = B_; end if; 
            if @alphaindex = 'C' then set @tempvalue = C_; end if; 
            if @alphaindex = 'D' then set @tempvalue = D_; end if; 
            if @alphaindex = 'E' then set @tempvalue = E_; end if; 
            if @alphaindex = 'F' then set @tempvalue = F_; end if; 
            if @alphaindex = 'G' then set @tempvalue = G_; end if;           

            if @keyindex = 'username' then           
                set @temp = null;
                select count(username) into @temp from basic_user where username = @tempvalue;       
                #select @temp;
                if @temp > 0 then 
                    set out_state = 2;
                    set out_msg = @tempvalue;                    
                    
                    leave pro_main;
                end if;    
                set @username = @tempvalue;
            end if;                    
            if @keyindex = 'password' then           
                set @tempvalue = md5(@tempvalue);                    
            end if;    
            if @keyindex = 'group_code' then           
                set @id_user_group = null;                              
                select id,code,name into @id_user_group,@code_user_group,@name_user_group from basic_group where code = @tempvalue ;                    
                if @id_user_group is null then                                        
                    set out_state = 3;                        
                    set out_msg = concat('wrong ',@tempvalue);  
                                                       
                    leave pro_main;
                end if;                                         
            end if;  
            if @keyindex = 'type' then           
                set @temp = null;
                select code into @temp from basic_memory where extend5 = 'basic_user__type' and extend4 = @tempvalue;            
                #select @temp;
                if @temp is null then 
                    set out_state = 0;
                    set out_msg = @tempvalue;                    

                    leave pro_main;
                end if;          
                set @tempvalue = @temp;  
                
                if @temp = 2 then                
                    set @count_student = @count_student + 1;                    
                    set @student_insert = concat(@student_insert,\" ('\",@username,\"','\"
                                                                    ,@code_user_group,\"','\"
                                                                    ,basic_memory__index('education_student'),\"','\"
                                                                    ,@id_person,\"','\"
                                                                    ,@id_user,\"') ,\");                                                                    
                    #select @student_insert;
                elseif @temp = 3 then                
                    set @count_teacher = @count_teacher + 1;                    
                    set @teacher_insert = concat(@teacher_insert,\" ('\",@username,\"','\"
                                                                    ,@code_user_group,\"','\"
                                                                    ,basic_memory__index('education_teacher'),\"','\"
                                                                    ,@id_person,\"','\"
                                                                    ,@id_user,\"') ,\");
                end if;                         
            end if;                                            

            set @sql_values =  concat(@sql_values,\",'\",@tempvalue,\"'\");              

            IF @p < @columncount - 1 THEN
               ITERATE inerLoop;
            END IF;
            LEAVE inerLoop;
        END LOOP inerLoop;                
        
        set @sql_values = concat( 
        @id_user 
        ,\",\",@id_person        
        ,\",'\",@username        
        ,\"','13456111111'\"
        ,\",'wei1224hf@gmail.com'\"              
        ,\",'\",@id_user_group,\"'\"        
        ,\",'\",@name_user_group,\"'\"        
        ,\",'\",id_creater_,\"'\"        
        ,\",'\",id_creater_group_,\"'\"        
        ,\",'\",code_creater_group_,\"'\"
        ,@sql_values);                
        #select @sql_values;leave pro_main;
        set out_ids = concat(out_ids,\",\",@id_user);

        if rowindex_ = @maxrow then                 
            set @sql_insert = concat(@sql_insert,\"(\",@sql_values,\",1) ;\");  
            set @user2group_sql_insert = concat(@user2group_sql_insert,\"('\",@id_user,\"','\",@id_user_group,\"','\",@username,\"','\",@code_user_group,\"',1) ;\");  
            set @person_insert = concat(@person_insert,\"('\",@username,\"','\",@id_person ,\"') ;\");  
        else        
            set @sql_insert = concat(@sql_insert,\"(\",@sql_values,\",1) ,\");  
            set @user2group_sql_insert = concat(@user2group_sql_insert,\"('\",@id_user,\"','\",@id_user_group,\"','\",@username,\"','\",@code_user_group,\"',1) ,\");            
            set @person_insert = concat(@person_insert,\"('\",@username,\"','\",@id_person ,\"') ,\");  
        end if;
            
    fetch cur into A_,B_,C_,D_,E_,F_,G_,rowindex_;
    end while cur_while;
    close cur;        
        
    #执行批量插入
    #select @sql_insert;leave pro_main;
    PREPARE stmt FROM @sql_insert;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;  
           
    PREPARE stmt FROM @user2group_sql_insert;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;     
    set out_msg = @user2group_sql_insert;
    
    #select @person_insert;leave pro_main;           
    PREPARE stmt FROM @person_insert;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;    
    
    if @count_student > 0 then    
        set @student_insert = SUBSTRING( @student_insert , 1 , LENGTH(@student_insert)-1 ) ;       
        #select @student_insert;leave pro_main;
        PREPARE stmt FROM @student_insert;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;  
    end if;         
    if @count_teacher > 0 then    
        set @teacher_insert = SUBSTRING( @teacher_insert , 1 , LENGTH(@teacher_insert)-1 ) ;      
        #select @teacher_insert;  
        PREPARE stmt FROM @teacher_insert;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;  
    end if;         

    update basic_group set count_users = 
           (select count(*) from basic_user where basic_user.group_code = basic_group.code  ) ;

    set out_state = 1;    
    #set out_msg = 'ok';    
    delete from basic_excel where guid = in_guid; 
end;;


-- ----------------------------
-- Procedure structure for basic_user__insert
-- ----------------------------
DROP PROCEDURE IF EXISTS `basic_user__insert`;;

CREATE PROCEDURE `basic_user__insert`(
in in_username varchar(200),
in in_password varchar(200),
in in_type int,
in in_cellphone char(11),
in in_email varchar(200),
out out_state int,
out out_msg varchar(200)
)
pro_main:BEGIN
/*
对于 用户 表,核心业务属性字段就只有 用户名,密码,用户组,类型,状态 这几个

而 用户组,状态 这两个字段,是由系统或管理员设置的

所以,在新注册一个用户的时候,只要提供 用户名 密码 类型 即可
在 注册 过程中,无需提供 个人档案 跟 业务身份信息 

@author wei1224hf@gmail.com
@version 201301
*/	

declare _user_id,
        _person_id,
        _teacher_id,
        _student_id int;

   #判断必要项是否为空    
    if((in_username is NULL) ||    
       (in_password is NULL) ||       
       (in_type is NULL) ||       
       (in_cellphone is NULL) ||       
       (in_email is NULL)) then
        set out_state = 0;
        set out_msg = 'NULL input';           
        leave pro_main;
    end if;
    #判断用户名 手机号 邮箱 是否已存在        
    select id into _user_id from basic_user where (username = in_username) or 
                                 (person_cellphone = in_cellphone) or                                 
                                 (person_email = in_email);                                 
    if _user_id is not NULL then    
        set out_state = 2;        
        set out_msg = concat('username or cellphone or email , already exist',_user_id);        
        leave pro_main;        
    end if;    
   
    set _person_id = basic_memory__index('basic_person');        
    insert into basic_person (        
        id            
        ,cellphone            
        ,email
    ) values (        
        _person_id            
        ,in_cellphone            
        ,in_email
    );        

    set _user_id = basic_memory__index('basic_user');        
    insert into basic_user (
        id            
        ,username            
        ,password    
        ,person_cellphone
        ,person_email  
        ,person_id      
        ,status            
        ,type
    ) values (
        _user_id            
        ,in_username            
        ,MD5(in_password)             
        ,in_cellphone        
        ,in_email        
        ,_person_id
        ,4       
        ,in_type
    );    

    if in_type = 2 then  
        select basic_department.code,name,(select id from basic_group where basic_group.code = basic_department.code ) into @g_code,@g_name,@g_id from basic_department where type = '2' limit 1; 
        set _student_id = basic_memory__index('education_student');
        insert into education_student (
        id_user,id_person,code,id
        ) values (
        _user_id,_person_id,in_username,_student_id
        ) ; 
    elseif in_type = 3 then            
        select basic_department.code,name,(select id from basic_group where basic_group.code = basic_department.code ) into @g_code,@g_name,@g_id from basic_department where type = '2' limit 1; 
        set _teacher_id = basic_memory__index('education_teacher');
        insert into education_teacher (
        id_user,id_person,code,id
        ) values (
        _user_id,_person_id,in_username,_teacher_id
        ) ;         
    end if;
    update basic_user set group_code = @g_code, group_name = @g_name, group_id = @g_id where id = _user_id;       
    insert into basic_group_2_user (id_user,id_group,username,code_group) values (_user_id,@g_id,in_username,@g_code);
    set out_state = 1;    
    set out_msg = concat(_user_id,';',_person_id);
END;;


-- ----------------------------
-- Procedure structure for basic_user__login
-- ----------------------------
DROP PROCEDURE IF EXISTS `basic_user__login`;;

CREATE PROCEDURE `basic_user__login`(
IN in_username varchar(200), 
IN in_password varchar(200), 
IN in_ip varchar(200), 
OUT out_msg varchar(200), 
OUT out_state int )
pro_main:BEGIN
/**
 登录操作后产生的用户 session 信息将被保存在数据库内存表中 
 取消对服务端 session 的依赖 
 便于对系统单点登录的判断,以及多服务器负载均衡的实现 

 数据库session表是一张内存表,里面记录每一个用户的系统操作次数 ,登录IP, 当天登录次数,当天正常退出次数 
 这些 统计次数 数据,会在系统自检过程中,累加到磁盘数据表中 

 用户的登录操作,采用的是简单的 MD5+时间戳 加密,时间戳为当前系统小时,有效验证时间为2小时 
 在系统前端,这个 session 会每隔15分钟更新一次,以保证前端保持更新


 version: 201210 
 author: wei1224hf@gmail.com  
 prerequisites: basic_memory__init,basic_memory.il8n()
 server used: basic_user.login() 
 involve: basic_user,basic_group,basic_permission,basic_group_2_user,basic_group_2_permission 
          basic_randstring,basic_memory__il8n,basic_user__group,basic_user__permission
 */
    declare username_ varchar(200);        
    declare id_user_ int;    
    declare password_ varchar(200);
    declare session_ varchar(200);        
    declare return_session_ varchar(32);    
    declare permissions_ varchar(1000);    
    declare mymoney_ int;    
    declare cost_ int;    
    declare credits_ int;

    select username,password,id,group_code,money into username_,password_,id_user_,@id_group,mymoney_ from basic_user where username = in_username;        
    if TRIM( username_ ) is NULL then    
        #用户不存在     
        set out_state = 0;
        set out_msg = 'no such user';   
        
        #记录这一次异常的登录事件,这有可能是嗅探工具

        insert into basic_log (msg,username,type) values ( concat('no such user; unm:' ,in_username, '; pwd: ', in_password,'; ip:',in_ip),'system',2);            
    else     
        #用户存在,判断密码 , 有2个小时的延时允许                  
        if  (
             ( in_password = md5( concat(TRIM(password_  ), ( hour(now()) - 0 ) ) ) ) or             
             ( in_password = md5( concat(TRIM(password_  ), ( hour(now()) - 1 ) ) ) ) or             
             ( in_password = md5( concat(TRIM(password_  ), ( hour(now()) - 2 ) ) ) )             
            )then              
                       
              
            #登陆操作,先判断内存表中是否有这个人的记录了        
            select session into session_ from basic_user_session where username = in_username; 
       
            if session_ is NULL then                        
                #如果内存表中没有记录,就要新插入一条数据   
                set return_session_ = basic_randstring(32,5);                
                set permissions_ = basic_user__permission(in_username);                    
                #select permissions_;leave pro_main;
                insert into basic_user_session(id_user,id_group,username,ip,session,permissions,lastactiontime,lastaction,groups,status) 
                    values (id_user_,@id_group,username_,in_ip,return_session_,permissions_,now(),'login',basic_user__group(in_username),1);    
                    
                update basic_user set lastlogintime = now() where username = in_username;            
                set out_msg = return_session_;                               
                set out_state = 1;                

            else                
                if in_username = 'guest' then    
                    select session into return_session_ from basic_user_session where username = in_username;                                        
                else                    
                    #后续登陆的人,会将前面登陆的人T掉,会更新 session  
                    set return_session_ = basic_randstring(32,5);                     
                    update basic_user_session set session = return_session_,lastaction='login',lastactiontime=now(),status=1,count_login = count_login + 1, count_actions = count_actions + 1 where username = in_username;
                 end if;            
                 set out_msg = return_session_;                             
                 set out_state = 1;  
            end if;            

            #往日志表中插入一条记录,非正常登录

            insert into basic_log (msg,username,type) values ('unusual login',in_username,1); 
            set out_state = 1;                         
            #set out_msg = concat('money cost ',cost_,'; now I have ', mymoney_ - cost_ );                                      
            set out_msg = return_session_;
         
            
        else
            set out_msg = 'wrong password';                    
            set out_state = 0;            
        end if;        
    end if;
END;;


-- ----------------------------
-- Procedure structure for basic_user__logout
-- ----------------------------
DROP PROCEDURE IF EXISTS `basic_user__logout`;;

CREATE PROCEDURE `basic_user__logout`(
IN `in_username` varchar(200),
IN `in_session` varchar(200), 
OUT out_msg varchar(200) , 
out out_state int)
pro_main:BEGIN
/*
用户退出系统时的业务:
删除 basic_user_session 表中的对应数据


@version 201209
@author wei1224hf@gmail.com
*/
declare fig,
        id_user_ int;
declare username_, 
        password_,
        session_,
        return_session_ varchar(200);
declare permissions_ varchar(1000);

    select username,session into username_,session_ from basic_user_session where username = in_username;
    if TRIM( username_ ) is NULL then         
        set out_msg = 'no user';        
        set out_state = 0;        
    elseif ( in_session  = md5( concat(session_, hour(now())-0 )))or       
           ( in_session  = md5( concat(session_, hour(now())-1 )))or   
           ( in_session  = md5( concat(session_, hour(now())-2 ))) then                            
        delete from basic_user_session where username = in_username;    
        update basic_user set lastlogouttime = now();    
        set out_msg = 'done';        
        set out_state = 1;   
    else    
        set out_msg = 'session wrong';        
        set out_state = 0;    
    end if;
END;;


-- ----------------------------
-- Procedure structure for basic_workflow__check
-- ----------------------------
DROP PROCEDURE IF EXISTS `basic_workflow__check`;;

CREATE PROCEDURE `basic_workflow__check`(
IN `in_username` varchar(200),
IN `in_session` varchar(200),
OUT `out_msg` varchar(200),
OUT `out_state` varchar(200),
OUT `out_session` varchar(200),
OUT `out_app` varchar(200))
pro_main:BEGIN	
/*
工作流检查

同时更新 session 表中的session值,防止用户过期
然后将用户的未办事务列表拉出来TODO

@version 201210
@author wei1224hf@gmail.com
*/

declare username_,
        password_,
        session_ varchar(200);  

    select username,session into username_,session_ from basic_user_session where username = in_username;
    if TRIM( username_ ) is NULL then         
        set out_msg = 'no user';        
        set out_state = 0;        
    elseif ( in_session  = md5( concat(session_, hour(now())-0))) or    
           ( in_session  = md5( concat(session_, hour(now())-1))) or
           ( in_session  = md5( concat(session_, hour(now())-2))) then                                         
        set out_session = basic_randstring(32,5) ;
        update basic_user_session set session = out_session ,lastlogintime = now(),lastaction='workflow_check'  where username = in_username;
        set out_msg = 'done';        
        set out_state = 1;           
        set out_app = '';
    else    
        set out_msg = 'session wrong';        
        set out_state = 0;    
    end if;
END;;


";
$arr = explode(";;",$sql);
for($i=0;$i<count($arr);$i++){
    //echo $arr[$i]."<br/>";
    mysql_query($arr[$i],$conn);
}
}

function createProcEducation($conn){
$sql = "

-- ----------------------------
-- Procedure structure for education_exam__export
-- ----------------------------
DROP PROCEDURE IF EXISTS `education_exam__export`;
DELIMITER ;;
CREATE PROCEDURE `education_exam__export`(
in in_exam_id int
,in in_excelid varchar(200)
,in in_sheetcount int
,in in_sheetindex int
,out out_state int
,out out_msg varchar(200)
,out out_excelid varchar(200)
,out out_sheetcount int
,out out_sheetindex int)
pro_main:BEGIN
/*
多人统考 导出

@version 201301
@author wei1224hf@gmail.com
*/
declare _exam_id,     
        _paper_id,   
        _rownum 
    int;    

declare _excelid 
    varchar(200);

    select id,id_paper into _exam_id,_paper_id from education_exam 
        where id = in_exam_id limit 1;        
    if _exam_id is null then        
        set out_state = 0;set out_msg = 'wrong exam_id';
        leave pro_main;        
    end if;
  
    if in_excelid is null then
        set in_excelid = concat('',floor(rand()*100000));           
    end if;   
    set out_excelid = in_excelid;     

    if in_sheetindex is null then
        set in_sheetindex = 0;        
    end if;        
    set out_sheetindex = in_sheetindex + 1;        
        
    if in_sheetcount is null then
        set in_sheetcount = 1;        
    end if;      
    set out_sheetcount = in_sheetcount;

    insert into basic_excel (    
        guid
        ,sheets
        ,sheetindex
        ,sheetname
        ,rowindex
        ,maxcolumn

        ,A
        ,B
        ,C
        ,D
        ,E        
        ,F        
        ,G        
        ,H        
        ,I        
        ,J        

    ) values (         
        out_excelid 
        ,in_sheetcount        
        ,in_sheetindex     
        ,basic_memory__il8n('exam','education_exam',1)
        ,1
        ,11
        
        ,basic_memory__il8n('teacher_name','education_exam',1)
        ,basic_memory__il8n('subject','education_exam',1)
        ,basic_memory__il8n('mode','education_exam',1)
        ,basic_memory__il8n('type','education_exam',1)        
        ,basic_memory__il8n('time_start','education_exam',1)        
        ,basic_memory__il8n('time_end','education_exam',1)        
        ,basic_memory__il8n('passline','education_exam',1)        
        ,basic_memory__il8n('place','education_exam',1)        
        ,basic_memory__il8n('title','education_exam',1)        
        ,basic_memory__il8n('invigilator','education_exam',1)           
    );
       
    insert into basic_excel (    
        guid
        ,sheets
        ,sheetindex
        ,sheetname
        ,rowindex
        ,maxcolumn

        ,A
        ,B
        ,C
        ,D
        ,E        
        ,F        
        ,G        
        ,H        
        ,I        
        ,J  
        

    ) select         
        out_excelid
        ,in_sheetcount        
        ,in_sheetindex   
        ,basic_memory__il8n('exam','education_exam',1)
        ,2
        ,11
        
        ,teacher_code
        ,subject_code
        ,(select extend4 from basic_memory where extend5 = 'education_exam__mode' and code = education_exam.mode )        
        ,(select extend4 from basic_memory where extend5 = 'education_exam__type' and code = education_exam.type )        
        ,time_start        
        ,time_end
        ,passline        
        ,place
        ,title
        ,invigilator
                                                                            
     from education_exam where id = in_exam_id;     

     insert into basic_excel (    
        guid
        ,sheets
        ,sheetindex
        ,sheetname
        ,rowindex
        ,maxcolumn

        ,A
    ) values(       
        out_excelid
        ,in_sheetcount        
        ,in_sheetindex   
        ,basic_memory__il8n('exam','education_exam',1)
        ,3
        ,1
        
        ,basic_memory__il8n('class','education_exam',1)                                                                         
     );     

     set @rownum = 3;
     insert into basic_excel (    
        guid
        ,sheets
        ,sheetindex
        ,sheetname
        ,rowindex
        ,maxcolumn

        ,A
    ) select       
        out_excelid
        ,in_sheetcount        
        ,in_sheetindex   
        ,basic_memory__il8n('exam','education_exam',1)
        ,(@rownum:=@rownum+1)
        ,1
        
        ,education_exam_2_class.class_code                                                             
    from education_exam_2_class where exam_id = in_exam_id; 

    call education_paper__export(
    _paper_id
    ,in_excelid
    ,3
    ,out_sheetindex
    ,@out_state
    ,@out_msg
    ,@out_excelid
    ,@out_sheetcount
    ,@out_sheetindex);


END;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for education_exam__import
-- ----------------------------
DROP PROCEDURE IF EXISTS `education_exam__import`;
DELIMITER ;;
CREATE PROCEDURE `education_exam__import`(
IN in_guid char(36),
out out_id int,
OUT out_state int,
OUT out_msg varchar(200)
)
pro_main:BEGIN
    declare maxcolumn_ int ;
    declare fig int;   
    declare code_,row1_,row2_ varchar(200);           
    declare A_,B_,C_,D_,E_,F_,G_,H_,I_,J_,K_,L_,M_ varchar(200);    
    declare A__,B__,C__,D__,E__,F__,G__,H__,I__,J__,K__,L__,M__ varchar(2000);        
    declare id_creater_ int;    
    declare id_creater_group_ int;    
    declare _sql_insert varchar(2000);

    declare cur cursor for 
        SELECT code, row1, row2  from array_exam ;  
    declare cur_class cursor for
        select A from basic_excel where rowindex > 3 and sheetname = basic_memory__il8n('exam','education_exam',1) ;        
    #以下变量用于游标
    declare CONTINUE HANDLER FOR SQLSTATE '02000' SET fig = 1;            

    if in_guid is null then        
        set out_state = 0;        
        set out_msg = 'null guid';
        leave pro_main;
    end if;    
    
    drop TEMPORARY table if exists array_exam;
    create  TEMPORARY  table array_exam (
        code varchar(2)        
        ,row1 varchar(200)        
        ,row2 varchar(2000)
    ) engine = memory ;        

    select A,B,C,D,E,F,G,H,I,J,K,L,M,id_creater into A_,B_,C_,D_,E_,F_,G_,H_,I_,J_,K_,L_,M_,id_creater_ from basic_excel where guid = in_guid and rowindex = 1 and sheetindex = 1;    
    select A,B,C,D,E,F,G,H,I,J,K,L,M into A__,B__,C__,D__,E__,F__,G__,H__,I__,J__,K__,L__,M__ from basic_excel where guid = in_guid and rowindex = 2 and sheetindex = 1;
    insert into array_exam values 
        ('A', basic_memory__il8n( A_,'education_exam', 2), A__  ),        
        ('B', basic_memory__il8n( B_,'education_exam', 2), B__  ),        
        ('C', basic_memory__il8n( C_,'education_exam', 2), C__  ),        
        ('D', basic_memory__il8n( D_,'education_exam', 2), D__  ),        
        ('E', basic_memory__il8n( E_,'education_exam', 2), E__  ),        
        ('F', basic_memory__il8n( F_,'education_exam', 2), F__  ),        
        ('G', basic_memory__il8n( G_,'education_exam', 2), G__  ),        
        ('H', basic_memory__il8n( H_,'education_exam', 2), H__  ),              
        ('I', basic_memory__il8n( I_,'education_exam', 2), I__  ),              
        ('J', basic_memory__il8n( J_,'education_exam', 2), J__  ),              
        ('K', basic_memory__il8n( K_,'education_exam', 2), K__  ),              
        ('L', basic_memory__il8n( L_,'education_exam', 2), L__  ),              
        ('M', basic_memory__il8n( M_,'education_exam', 2), M__  )
    ;  
    #select * from array_exam;          
      
    set @sql_keys_exam = '';    
    set @sql_values_exam = '';    
    set @columnsimport = 'subject,title,place,time_start,time_end,mode,passline,teacher_name,type,invigilator,remark';    
    SET fig=0;    
    open cur;    
    fetch cur into code_,row1_, row2_;    
    while( fig = 0 ) do         
       # select row2_,row1_,cdoe_;
        if row1_ is null then              
            set out_msg = concat(out_msg,code_,'1','null;');  
        elseif FIND_IN_SET(row1_,@columnsimport) = 0 then 
            set out_state = 0;
            set out_msg = concat(out_msg,code_,'1','cant;');               
            leave pro_main;

       #对教师的特殊处理
        elseif row1_ = 'teacher_name' then 
 
            select person_name,id into @teacher_name,@teacher_id from basic_user where username = row2_;                                
            if @teacher_name is null then      
                set out_msg = 'teacher code wrong';      
                leave pro_main;                
            end if;
            set @sql_keys_exam = concat(@sql_keys_exam,'teacher_name,');              
            set @sql_values_exam = concat(@sql_values_exam,\"'\",@teacher_name,\"',\");     
            set @sql_keys_exam = concat(@sql_keys_exam,'teacher_id,');              
            set @sql_values_exam = concat(@sql_values_exam,\"'\",@teacher_id,\"',\");              
            set @sql_keys_exam = concat(@sql_keys_exam,'teacher_code,');              
            set @sql_values_exam = concat(@sql_values_exam,\"'\",row2_,\"',\");             

        #对考试批改模式的特殊处理 
        elseif row1_ = 'mode' then 
            select code into @temp from basic_parameter where reference = 'education_exam__mode' and value = row2_;
            if @temp is null then 
                set out_state = 0;
                set out_msg = concat(code_,'2 ',row2_,' : wrong mode');
                leave pro_main;
            end if;
            set @sql_keys_exam = concat(@sql_keys_exam,row1_,',');              
            set @sql_values_exam = concat(@sql_values_exam,\"'\",@temp,\"',\");     

        #对考试类型的特殊处理

        elseif row1_ = 'type' then 
            select code into @temp from basic_parameter where reference = 'education_exam__type' and value = row2_;
            if @temp is null then 
                set out_state = 0;
                set out_msg = 'wrong type';
                leave pro_main;
            end if;
            set @sql_keys_exam = concat(@sql_keys_exam,row1_,',');              
            set @sql_values_exam = concat(@sql_values_exam,\"'\",@temp,\"',\");   
            
        #对时间类型的处理
        elseif row1_ = 'time_start' then 
            select ( row2_ REGEXP   '^([0-9]{4})-([0-9]{2})-([0-9]{2}) ([0-9]{2}):([0-9]{2}):([0-9]{2})$' ) into @temp ;
            if @temp = 0 then 
                set out_state = 0;
                set out_msg = concat(code_,'2 ',row2_,' : wrong time');
                leave pro_main;
            end if;
            set @sql_keys_exam = concat(@sql_keys_exam,row1_,',');              
            set @sql_values_exam = concat(@sql_values_exam,\"'\",row2_,\"',\");   
        elseif row1_ = 'time_end' then 
            select ( row2_ REGEXP   '^([0-9]{4})-([0-9]{2})-([0-9]{2}) ([0-9]{2}):([0-9]{2}):([0-9]{2})$' ) into @temp ;
            if @temp = 0 then 
                set out_state = 0;
                set out_msg = concat(code_,'2 ',row2_,' : wrong time');
                leave pro_main;
            end if;
            set @sql_keys_exam = concat(@sql_keys_exam,row1_,',');              
            set @sql_values_exam = concat(@sql_values_exam,\"'\",row2_,\"',\");              

        #对 考试及格线 的处理

        elseif row1_ = 'passline' then 
            select ( row2_ REGEXP   '^[0-9]*$' ) into @temp ;
            if @temp = 0 then 
                set out_state = 0;
                set out_msg = concat(code_,'2 ',row2_,' : format wrong');
                leave pro_main;
            end if;
            set @sql_keys_exam = concat(@sql_keys_exam,row1_,',');              
            set @sql_values_exam = concat(@sql_values_exam,\"'\",row2_,\"',\");                                      
        elseif row1_ = 'subject' then 
            select code,name into @temp,@temp2 from education_subject where code = row2_;
            if @temp is null then 
                set out_state = 0;
                set out_msg = 'wrong subject';
                leave pro_main;
            end if;
            set @sql_keys_exam = concat(@sql_keys_exam,'subject_code',',');              
            set @sql_values_exam = concat(@sql_values_exam,\"'\",@temp,\"',\");               
            set @sql_keys_exam = concat(@sql_keys_exam,'subject_name',',');              
            set @sql_values_exam = concat(@sql_values_exam,\"'\",@temp2,\"',\");   
        else        
            #select 2;         
            set @sql_keys_exam = concat(@sql_keys_exam,row1_,',');              
            set @sql_values_exam = concat(@sql_values_exam,\"'\",row2_,\"',\");      
        end if;
    fetch cur into code_,row1_, row2_;    
    end while;
    close cur;  
        
    #判断一些必要的列是否存在    
    if FIND_IN_SET('mode',@sql_keys_exam) = 0 then      
        set out_state = 0;
        set out_msg = concat('request ',basic_memory__il8n( 'mode','education_exam', 2));        
        leave pro_main;
    end if;    
    if FIND_IN_SET('type',@sql_keys_exam) = 0 then        
        set out_state = 0;
        set out_msg = concat('request ',basic_memory__il8n( 'type','education_exam', 2));        
        leave pro_main;
    end if;    
    if FIND_IN_SET('time_start',@sql_keys_exam) = 0 then        
        set out_state = 0;
        set out_msg = concat('request ',basic_memory__il8n( 'time_start','education_exam', 2));        
        leave pro_main;
    end if;    
    if FIND_IN_SET('time_end',@sql_keys_exam) = 0 then        
        set out_state = 0;
        set out_msg = concat('request ',basic_memory__il8n( 'time_end','education_exam', 2));        
        leave pro_main;
    end if;    
    if FIND_IN_SET('passline',@sql_keys_exam) = 0 then        
        set out_state = 0;
        set out_msg = concat('request ',basic_memory__il8n( 'passline','education_exam', 2));        
        leave pro_main;
    end if;    

    set @total_students = 0;    
    set @education_exam_2_class_values = \"\";    
    set out_id = basic_memory__index('education_exam');    
    set fig = 0;
    open cur_class;     
    fetch cur_class into A_;    
    while ( fig = 0 ) do   
        select count_users into @count_students from basic_group where code = A_;           
        #select A_,@count_students;                    
        if @count_students is null then 
            set out_state = 0;
            set out_msg = concat('wrong class ',A_);
            leave pro_main;
        end if;   
        set @education_exam_2_class_values = concat(@education_exam_2_class_values ,\" ('\",A_,\"','\",out_id,\"') ,\");      
        set @total_students = @total_students + @count_students;   
    fetch cur_class into A_;    
    end while;   
    close cur_class;    

    drop TEMPORARY table if exists array_exam_group;
    create TEMPORARY table array_exam_group (
        id int auto_increment primary key
        ,codes varchar(200)
    ) engine = memory ;     
    set @group_codes = '';
    insert into array_exam_group ( codes ) 
           select A from basic_excel where guid = in_guid and sheetindex = 1 and rowindex > 3;               
    select max(id) into @maxid from array_exam_group ;    
    while @maxid > 0 do                    
        set @group_codes = concat(@group_codes,\",\",(select codes from array_exam_group where id = @maxid))        ;
        set @maxid = @maxid - 1;    
    end while;    
    set @group_codes = substring(@group_codes,2,length(@group_codes)-1);   
    
    set @p_id = 1; 


    call education_paper__import(in_guid,@p_state,@p_msg,@p_id); 
    if @p_state <> 1 then    
        set out_state = 2;        
        set out_msg = @p_msg;        
        leave pro_main;
    end if;                   


    set @sql_keys_exam = concat(@sql_keys_exam,'id_creater,id,count_students,id_paper');        
    set @sql_values_exam = concat(@sql_values_exam,\"'\",id_creater_,\"','\",out_id,\"','\",@total_students,\"','\",@p_id, \"'\");    
    set @_sql_insert = concat(\"insert into education_exam (\",@sql_keys_exam,\") values (\",@sql_values_exam,\")\");      
    #select _sql_insert;
    #leave pro_main;     

    PREPARE stmt FROM @_sql_insert;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;         


    call education_exam__setclass(out_id,@group_codes,@teacher_id,@x,@y,@z,@a);    


    set out_state = 1;
    set out_msg = 'ok';          

    drop TEMPORARY  table if exists array_exam;

END;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for education_exam__init4test
-- ----------------------------
DROP PROCEDURE IF EXISTS `education_exam__init4test`;
DELIMITER ;;
CREATE PROCEDURE `education_exam__init4test`(
in in_month_count int,
out out_state int,
out out_msg varchar(200)
)
pro_main:BEGIN
/*
模拟统考数据,整一年的数据
5个班级,201个学生,高三一年,8个月,每个月一次月考

2011年秋季到2012年春季,每个学生的做题情况,他们的成绩按月份逐月上升

月份 概率 浮动
9    60%  10%
10   70%  9%
11   80%  8% 
12   85%  7%

3    87%  6%
4    88%  5%
5    90%  4%
6    95%  3%

按月份循环 * 8
  插入一份统考试卷

  插入一张试卷  
  插入50道题目  
    插入30道单选题    
    插入10道多选题    
    插入10道单选题    
  循环5个班级,插入统考安排信息

    插入1条 考试-班级 信息    
    按每个学生循环 * 40   
      插入1条 学生-考试 信息    
      插入1条试卷做题日志    
      插入50条做题日志

        根据月份,设置每一题答对概率    
      卷子提交        
        更新卷子日志的成绩      
        更新 学生-考试 信息        
    更新班级的统考统计成绩: 平均分,最高分,及格人数

*/

#所有的试卷,卷子,都是由教师 JS0000002,张三2,5101,第一教研组 提供上传的

declare teacher_id_creater_ int default '206';
declare teacher_code_creater_ varchar(200) default 'JS0000002';
declare teacher_name_creater_ char(5) default '张三2';
declare teacher_id_creater_group_ int default '11';
declare teacher_code_creater_group_ char(4) default '5101';

declare student_id_,
        student_group_id_,
        fig,        
        status_,
        type_,        
        count_month,
        count_subject,
        count_paper,
        count_question,
        count_class,
        count_student,
        count_paperlog,
        count_questionlog,        
        paperid,
        examid,
        questionid,
        education_exam_2_class__id,
        education_exam_2_student__id,
        education_paper_log_id_,        
        userid,
        usergroupid
         int;
declare student_name_,
        student_code_,
        student_group_code_,
        student_group_name_,
        subject_code_,
        subject_name_,
        _exam_title,
        personname 
        varchar(200) default '0';

declare paperdate char(10);
declare cur_student2exam cursor for select 
    id ,    
    group_id
        from basic_user where group_code = student_group_code_ order by id;        
#以下变量用于游标
declare CONTINUE HANDLER FOR SQLSTATE '02000' SET fig = 1;


truncate table education_paper ;
truncate table education_paper_log ;
truncate table education_paper_2_question ;
truncate table education_question ;
truncate table education_question_log ;
truncate table education_question_log_wrongs ;
truncate table education_exam ;
truncate table education_exam_2_class ;
truncate table education_exam_2_student ;

update basic_memory set extend1 = 0 where type = 2 and code in (
'education_paper'    
,'education_paper_log'
,'education_paper_2_question'
,'education_question'
,'education_question_log'
,'education_question_log_wrongs'
,'education_exam'
,'education_exam_2_class'
,'education_exam_2_student'
);

if in_month_count is null then
    set out_state = 0;    
    set out_msg = 'null';
    leave pro_main;    
end if;
if in_month_count > 9 or in_month_count < 0 then
    set out_state = 0;    
    set out_msg = 'wrong count, 0 to 9 please';
    leave pro_main;    
end if;

#启用事务功能
START TRANSACTION; 

#根据月份循环
set count_month = in_month_count ;
while_month:while count_month >0 do
    set count_month = count_month - 1;  
    if count_month = 9 then set paperdate = '2011-05-01'; end if;              
    if count_month = 8 then set paperdate = '2011-06-01'; end if;    
    if count_month = 7 then set paperdate = '2011-09-01'; end if;    
    if count_month = 6 then set paperdate = '2011-10-01'; end if;    
    if count_month = 5 then set paperdate = '2011-11-01'; end if;    
    if count_month = 4 then set paperdate = '2011-12-01'; end if;    
    if count_month = 3 then set paperdate = '2012-03-01'; end if;    
    if count_month = 2 then set paperdate = '2012-04-01'; end if;    
    if count_month = 1 then set paperdate = '2012-05-01'; end if;    
    if count_month = 0 then set paperdate = '2012-06-01'; end if;        

    set status_ = 1; set type_ = 2;
    if paperdate = '2012-06-01' then            
        set status_ = 31;          
        set type_ = 4;                
    end if;

    #根据科目循环,科目编号从 50 到 53 
    set count_subject = 4;    
    while_subject: while count_subject > 0 do        
        set count_subject = count_subject - 1;        
        select code,name into subject_code_,subject_name_ from education_subject where code = concat('5',count_subject);                      
                                
        set @questionids = '';        
        set @question_count = 10;
        call education_paper__init4test_1(@question_count,NULL,subject_code_,@state,@msg,paperid,@questionids);          
        set examid = basic_memory__index('education_exam');            
        set _exam_title = concat('测试数据多人考试',round(rand()*10000))     ;
        
        set status_ = 22; set type_ = 1;                 

        #插入一张统考卷    
        insert into education_exam (                
            id            
            ,id_paper   
                    
            ,title   
            ,time_start
            ,time_end
            ,score
            ,`mode`
            ,passline
            ,`type`       
            ,place
            ,count_students_planed
            ,count_students   
            ,invigilator 
                   
            ,subject_code        
            ,subject_name   
            ,teacher_id            
            ,teacher_name            
            ,teacher_code                
            ,id_creater            
            ,id_creater_group            
            ,code_creater_group            
            ,time_created            
            ,status
        ) values (        
            examid            
            ,paperid         
            
            ,_exam_title
            ,paperdate                 
            ,'2014-01-01'          
            ,@question_count * 2             
            ,0            
            ,(@question_count * 2 * 0.6)            
            ,type_          
            ,'net oline'            
            ,0 
            ,0  
            ,'张三,李四,王五'
               
            ,subject_code_            
            ,subject_name_     
            ,teacher_id_creater_            
            ,teacher_name_creater_            
            ,teacher_code_creater_            
            ,teacher_id_creater_            
            ,teacher_id_creater_group_            
            ,teacher_code_creater_group_              
            ,paperdate            
            ,status_
        );           
                
        #只模拟两个班级

        call education_exam__setclass(examid,'5001,5002',teacher_id_creater_,@x,@x2,@x3,@x4);
    end while while_subject;
end while while_month;
commit;
END;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for education_exam__mark
-- ----------------------------
DROP PROCEDURE IF EXISTS `education_exam__mark`;
DELIMITER ;;
CREATE PROCEDURE `education_exam__mark`(
IN in_idexam int
,OUT out_state int
,OUT out_msg varchar(200)
,OUT out_passed int
,out out_failed int
,out out_giveup int
)
pro_main:BEGIN
/**
试卷批改
教师操作

启用事务,以便回滚
修改试卷状态 status 4 ,表示考试结束
遍历 paper_log , 将 logid 存在一张临时表中,因为将对 paper_log 表更新操作

  遍历 question_log , question 表,  
    判断 question_log 的做题结果是否正确,并累加 做对数 做错数 放弃数  
  更新 paper_log 表


@param id_exam 试卷编号
@param out_state 存储过程执行结果,1为正确通过,其他都表示错误.系统处理错误,2 3 4 为业务数据错误

@param out_msg 存储过程执行后返回的执行描述结果

@version 201212
@author wei1224hf@gmail.com
@qqgroup 135426431 
*/
declare id_paper_log_ int;
declare maxid int;
    set out_passed = 0;    
    set out_failed = 0;    
    set out_giveup = 0;    

    #如果编号为空或者编号不存在,就报错

    if in_idexam is null then         
        set out_state = 0;        
        set out_msg = 'null id';        
        leave pro_main;
    end if;        
    set @temp = null;
    select id into @temp from education_exam where id = in_idexam;        
    if @temp is null then    
        set out_state = 0;        
        set out_msg = 'wrong id';        
        leave pro_main;        
    end if;        

    #如果所有人都旷考,就报错

    set @temp = null;
    select id_paper_log into @temp from education_exam_2_student where exam_id = in_idexam and id_paper_log > 0 limit 1;   
    if @temp is null then    
        set out_state = 0;        
        set out_msg = 'no submit';        
        leave pro_main;        
    end if;    

    #创建临时内存表,用于统计
    drop TEMPORARY table if exists array_mark;
    create TEMPORARY table array_mark (
        id int,        
        id2 int auto_increment primary key,        
        score int,        
        rank int,        
        studentid int,        
        studentname varchar(200)
    ) engine = memory ;     
        
    #因为MYSQL对嵌套游标支持不高,所以用内存表加自动递增来伪游标
    insert into array_mark(
        id
        ,studentid
        ,studentname
    ) (
        select 
            id_paper_log             
            ,id_creater            
            ,student_name
        from education_exam_2_student 
        where exam_id = in_idexam and id_paper_log > 0
    );        
    select max(id2) into maxid from array_mark;
    
    #依次批改每张试卷,模仿游标
    while( maxid > 0 ) do  
        select id into id_paper_log_ from array_mark where id2 = maxid; 
        set maxid = maxid - 1;    

        call education_paper__mark(id_paper_log_,        
             @out_state ,
             @out_msg ,
             @out_totalCent ,
             @out_myTotalCent ,
             @out_count_right ,
             @out_count_wrong ,
             @out_count_giveup ,
             @out_count_byTeacher );   
                                                  
       #判断考试是否通过
       if @out_myTotalCent > 60 then       
           set @status = 41;   
           set out_passed = out_passed + 1;
       else       
           set @status = 42;       
           set out_failed = out_failed + 1;     
       end if;       
       update array_mark set score = @out_myTotalCent where id2 = maxid;      
                
       #更新单个学生的记录

       update education_exam_2_student set 
            score = @out_myTotalCent                         
            ,totalcent = @out_totalCent            
            ,status = @status
            ,time_lastupdated = now()    
            ,count_updated = count_updated + 1 
       where id_paper_log = id_paper_log_;       

       update array_mark set score = @out_myTotalCent where id = id_paper_log_;
    end while;  

    #统计旷考人数

    select count(*) into out_giveup from education_exam_2_student 
        where id_paper_log = 0 and exam_id = in_idexam; 
    update education_exam_2_student set 
        score = '0'                
        ,passline = '0'         
        ,totalcent = '0'            
        ,status = '43'
        ,time_lastupdated = now()    
        ,count_updated = count_updated + 1 
    where id_paper_log = 0 and exam_id = in_idexam;      
        
    #更新试卷状态

    update education_exam set 
        status = 1  
        ,count_passed = out_passed
        ,count_students = (out_passed + out_failed)
        ,time_lastupdated = now()    
        ,count_updated = count_updated + 1 where id = in_idexam ;   
        
    #总排名      
    set @rownum = 0;
    update education_exam_2_student set rank = (@rownum:=@rownum+1)
        where exam_id = in_idexam and id_paper_log > 0 order by score desc; 
        
    #整理班级内排名    
    truncate table array_mark;    
    insert into array_mark(
        id
    ) (
        select 
            class_id             
        from education_exam_2_class 
        where exam_id = in_idexam 
    );        
    set maxid = 0;
    select max(id2) into maxid from array_mark;    

    #依次统计每个组,模仿游标
    while( maxid > 0 ) do     
        set maxid = maxid - 1;            
        set @rownum = 0;
        update education_exam_2_student set rank_class = (@rownum:=@rownum+1)
            where exam_id = in_idexam and id_paper_log > 0 
            and id_creater_group = (select id from array_mark where id2 = maxid) 
            order by score desc; 
    end while;                              

    set out_state = 1;set out_msg = 'OK';
    drop TEMPORARY table if exists array_mark;
END;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for education_exam__setclass
-- ----------------------------
DROP PROCEDURE IF EXISTS `education_exam__setclass`;
DELIMITER ;;
CREATE PROCEDURE `education_exam__setclass`(
in in_exam_id int,
in in_class_codes varchar(200),
in in_teacher_id int,
out out_state int,
out out_msg varchar(200),
out out_class_count int,
out out_student_count int
)
pro_main:begin
/*
将试卷跟班级绑定

@vaersion 201301
@author wei1224hf@gmail.com
*/

declare _class_count
        ,_class_code        
        ,_class_name        
        ,_class_id        
        ,_paper_id                
        ,_subject_id        
        ,_pos        
        ,_pos2  
        ,_teacher_id 
        ,_id_creater
        ,_id_creater_group
        ,_code_creater_group        
        ,_exam2class_id        
        ,_count_student
        int;        
declare _exam_title
        ,_subject_code        
        ,_subject_name   
        ,_teacher_name
        ,_teacher_code                
        varchar(200);
	
    #如果参数为空,就报错退出

    if (in_exam_id is null) || (in_class_codes is null) || (in_teacher_id is null) then    
        set out_state = 0;        
        set out_msg = 'parameter null ';        
        leave pro_main;        
    end if;  
        
    #如果教师不存在,也报错退出

    select     
    id,username,person_name,group_id,group_code    
    into    
    _id_creater,_teacher_code,_teacher_name,_id_creater_group,_code_creater_group
    from basic_user where id = in_teacher_id and type = 3;          
    if _id_creater is null then    
        set out_state = 22;        
        set out_msg = 'teacher wrong';        
        leave pro_main;        
    end if;    
    set _teacher_id = _id_creater;

    #如果试卷不存在,也报错退出

    select     
    id_paper,title,subject_code,subject_name
    into         
    _paper_id,_exam_title,_subject_code,_subject_name
    from education_exam where id = in_exam_id;        
    if _paper_id is null then        
        set out_state = 2;        
        set out_msg = 'no exam associated';
    end if;
                                
    start transaction;     
    #允许重新分发试卷
    delete from education_exam_2_class where exam_id = in_exam_id;    

    #循环每一个班级编码

    set in_class_codes = concat(\",\",in_class_codes,\",\");
    set _class_count = basic_stringcount(in_class_codes,\",\") - 1;      
    set out_class_count = _class_count;
    set _pos = 1;       
    set _pos2 = 1;            
    set _count_student = 0;

    while _class_count > 0 do    
        set _pos = LOCATE(',', in_class_codes,_pos2);
		set _pos2 = LOCATE(',', in_class_codes,_pos+1);
        set _class_code = SUBSTRING(in_class_codes,_pos+1,_pos2-_pos-1);                  

        #如果其中某一个班级编码错误,就报错退出

        select name,id into _class_name,_class_id from basic_group where code = _class_code;        
        if _class_id is null then        
            set out_state = 21;            
            set out_msg = 'wrong class code';            
            leave pro_main;       
        end if;    
        
        set _exam2class_id = basic_memory__index('education_exam_2_class');
        insert into education_exam_2_class (        
            paper_id
            ,exam_id
            ,exam_title
            ,class_id
            ,class_code
            ,class_name
            ,teacher_id
            ,teacher_name
            ,teacher_code
            ,subject_code
            ,subject_name
            ,type
            ,status
            ,id
            ,id_creater
            ,id_creater_group
            ,code_creater_group
        ) values (
            _paper_id
            ,in_exam_id
            ,_exam_title
            ,_class_id
            ,_class_code
            ,_class_name
            ,_teacher_id
            ,_teacher_name
            ,_teacher_code
            ,_subject_code
            ,_subject_name
            ,1
            ,1
            ,_exam2class_id
            ,_id_creater
            ,_id_creater_group
            ,_code_creater_group
        );
        set _class_count = _class_count - 1;     
        #给学生分配试卷

        call education_exam__setstudent(in_exam_id,_class_code,@state,@msg);        
        set _count_student = _count_student + (select count(*) from basic_user where group_code = _class_code) ;
    end while;        
    update education_exam set count_students_planed = _count_student;
    commit;
        
    set out_state = 1;    
    set out_msg = 'OK';    
end;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for education_exam__setstudent
-- ----------------------------
DROP PROCEDURE IF EXISTS `education_exam__setstudent`;
DELIMITER ;;
CREATE PROCEDURE `education_exam__setstudent`(
in in_exam_id int,
in in_class_code varchar(200),
out out_state int,
out out_msg varchar(200)
)
pro_main:BEGIN
/*
将试卷分配给某个班的所有学生


@version 201301
@author wei1224hf@gmail.com
*/
declare  _exam_title
        ,_subject_code
        ,_subject_name   
        ,_class_name              
        ,_code_creater_group        
        ,_teacher_name        
        ,_teacher_code        
        ,_student_name        
        ,_student_code        
        ,_time_start            
        ,_time_end  
        varchar(200);        

declare  _paper_id
        ,_class_id        
        ,_id_creater        
        ,_id_creater_group           
        ,_teacher_id        
        ,_student_id        
        ,_exam2student_id        
        ,_id_paper_log                      
        ,_passline            
        ,_totalcent        
        ,_exam_status        
        ,_exam_type        
        ,_status        
        ,_type
        ,fig
        int;        

#TODO 尽量不适用游标,使用内存表模拟比较好
declare cur_exam2student cursor for 
        select id,username,person_name,group_code,group_name,group_id from basic_user where type = 2 and group_code = in_class_code;        
declare CONTINUE HANDLER FOR SQLSTATE '02000' SET fig = 1;

    #判断变量是否为空
    if (in_exam_id is null) || (in_class_code is null)  then    
        set out_state = 0;        
        set out_msg = 'parameter null ';        
        leave pro_main;        
    end if;  

    #判断试卷编号是否存在
    select     
    id_paper,title,subject_code,subject_name,score,time_start,time_end,passline,teacher_id,teacher_name,teacher_code,type,status
    into         
    _paper_id,_exam_title,_subject_code,_subject_name,_totalcent,_time_start,_time_end,_passline,_teacher_id,_teacher_name,_teacher_code,_type,_status
    from education_exam where id = in_exam_id;        
    if _paper_id is null then        
        set out_state = 2;        
        set out_msg = 'no exam associated';        
        leave pro_main;
    end if;        
    if _status = 4 then set _status = 22; end if;        

    #判断班级是否存在
    select name,id into _class_name,_class_id from basic_group where code = in_class_code;        
    if _class_id is null then        
        set out_state = 21;            
        set out_msg = 'wrong class code';            
        leave pro_main;       
    end if;      
    
    #允许重新分配试卷,如果要重考的话

    delete from education_exam_2_student where exam_id = in_exam_id and class_code = in_class_code;       

    #开启游标,向 education_exam_2_student 插入数据
    set fig = 0;    
    start transaction;
    open cur_exam2student;
    fetch cur_exam2student into _id_creater,_student_code,_student_name,_code_creater_group,_class_name,_id_creater_group;      
    WHILE ( fig = 0 ) DO     
        set _id_paper_log = 0 ;          

        set _exam2student_id = basic_memory__index('education_exam_2_student');
        insert into education_exam_2_student (        
             exam_id
            ,exam_title
            ,class_id
            ,class_code
            ,class_name
            ,teacher_id
            ,teacher_name
            ,teacher_code            
            ,student_id
            ,student_name
            ,student_code
            ,subject_code
            ,subject_name      
            ,id_paper      
            ,id_paper_log            
            ,time_start            
            ,time_end            
            ,passline            
            ,totalcent
            ,type
            ,status
            ,id
            ,id_creater
            ,id_creater_group
            ,code_creater_group            
            ,remark
        ) values (        
             in_exam_id
            ,_exam_title
            ,_id_creater_group
            ,_code_creater_group
            ,_class_name
            ,_teacher_id
            ,_teacher_name
            ,_teacher_code            
            ,_id_creater
            ,_student_name
            ,_student_code
            ,_subject_code
            ,_subject_name  
            ,_paper_id          
            ,_id_paper_log            
            ,_time_start            
            ,_time_end            
            ,_passline            
            ,_totalcent
            ,_type
            ,_status
            ,_exam2student_id
            ,_id_creater
            ,_id_creater_group
            ,_code_creater_group            
            ,'education_exam__setstudent'
        );
    fetch cur_exam2student into _id_creater,_student_code,_student_name,_code_creater_group,_class_name,_id_creater_group;      
    END WHILE;
    close cur_exam2student;         
    commit;    
    set out_state = 1;    
    set out_msg = 'ok';
END;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for education_exam__submit
-- ----------------------------
DROP PROCEDURE IF EXISTS `education_exam__submit`;
DELIMITER ;;
CREATE PROCEDURE `education_exam__submit`(IN in_id int,OUT out_state int,OUT out_msg varchar(200),OUT out_paperlogid int)
pro_main:BEGIN
/*
多人统考模式 试卷提交 ,
没有做 question_Log 操作, 那些数据的插入操作,直接在服务端代码实施

@version 201301
@author wei1224hf@gmail.com
*/
declare _paperlogid
        ,_exam_id         
        ,_paper_id
        int;        

declare _subject_code 
        ,_class_code
        varchar(200);

    if in_id is null then        
        set out_state = 0;        
        set out_msg = 'wrong';
        leave pro_main;        
    end if;      
      
    select id_paper_log into _paperlogid from education_exam_2_student 
        where id = in_id;  
    if _paperlogid is null then        
        set out_state = 0;        
        set out_msg = 'wrong id';
        leave pro_main;   
    end if;          
    if _paperlogid != 0 then        
        set out_state = 0;        
        set out_msg = 'submit already';
        leave pro_main;   
    end if;        
    
    select     
         exam_id         
        ,exam_title
        ,subject_name
        ,subject_code    
        ,teacher_id
        ,teacher_name
        ,teacher_code  
        ,id_paper         
        ,id_creater        
        ,id_creater_group        
        ,code_creater_group
        into        
         _exam_id         
        ,@exam_title
        ,@subject_name
        ,_subject_code
        ,@teacher_id
        ,@teacher_name
        ,@teacher_code         
        ,_paper_id    
        ,@id_creater        
        ,@id_creater_group     
        ,_class_code
    from education_exam_2_student where id = in_id;  

    set _paperlogid = basic_memory__index('education_paper_log');      
    insert into education_paper_log (
         paper_id
        ,paper_title
        ,teacher_id
        ,teacher_name
        ,teacher_code
        ,subject_code
        ,subject_name
        ,type
        ,id
        ,id_creater
        ,id_creater_group
        ,code_creater_group
        ,status
    ) values (
         _paper_id
        ,@exam_title         
        ,@teacher_id
        ,@teacher_name
        ,@teacher_code         
        ,@subject_name
        ,_subject_code        
        ,'0'        
        ,_paperlogid        
        ,@id_creater        
        ,@id_creater_group        
        ,@code_creater_group      
        ,'1'
    );    

    update education_exam_2_student set
        id_paper_log = _paperlogid
        ,status = '23' 
        ,time_submit = now()
        ,time_lastupdated = now()
        ,count_updated = count_updated + 1
        where id = in_id;    

    update education_exam_2_class set education_exam_2_class.count_submit = education_exam_2_class.count_submit + 1 
        where exam_id = _exam_id and class_code = _class_code;       
        
    set out_state = 1; set out_msg = 'OK'; set out_paperlogid = _paperlogid;
END;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for education_exam_2_student__init4test
-- ----------------------------
DROP PROCEDURE IF EXISTS `education_exam_2_student__init4test`;
DELIMITER ;;
CREATE PROCEDURE `education_exam_2_student__init4test`(in count_exam2student int)
pro_main:BEGIN
/*
模拟统考数据,整一年的数据
5个班级,201个学生,高三一年,8个月,每个月一次月考

2011年秋季到2012年春季,每个学生的做题情况,他们的成绩按月份逐月上升

月份 概率 浮动
9    60%  10%
10   70%  9%
11   80%  8% 
12   85%  7%
3    87%  6%
4    88%  5%
5    90%  4%
6    95%  3%

先决条件: 已经将 考卷-学生 记录数据准备好,内有 6400 条记录,
此存储过程将把其中的 6000 条记录处理掉,模拟6000次学生做题交卷过程


读取一条 学生-考卷 记录
  得到 考试编号  
       试卷编号  
       当天日期     
         通过 education_paper_2_question 得到这张试卷最大的题目编号,         
         从而可以推出这张试卷有关的所有100个题目编号         
       插入一条试卷做题日志,得到编号       
       循环100次,插入做题日志
*/

#科目信息
declare subject_code_ char(2) default '00';
declare subject_name_ char(4) default '0000';

#学生信息
declare student_id_,student_group_id_ int default '0';
declare student_name_,student_code_,student_group_code_,student_group_name_ varchar(200) default '0';

declare count_question int default '0';
declare paper_id_,exam_id_,paper_log_id_,question_id_,question_id_max_,question_id_min_,question_log_id_ int default '0';
declare thedate_ char(10) default '2000-01-01';
declare lograte,thelograte int default '0';
declare theanswer char(1) default 'A';

declare id_creater_group_ int default '0';
declare code_creater_group_ varchar(200) default '0';

#学生卷子编号
declare education_exam_2_student__id int default '0';

#declare 结束,以下是正式的业务代码----------------------------------------------
if count_exam2student is null then
    leave pro_main;    
end if;

select max(id) into education_exam_2_student__id from education_exam_2_student;

truncate table education_paper_log ;
truncate table education_question_log ;
truncate table education_question_log_wrongs ;

update basic_memory set extend1 = 0 where type = 2 and code in (
 'education_paper_log'
,'education_question_log_wrongs'  
,'education_question_log'  
);

#启用事务功能
START TRANSACTION; 

while_exam2student : while count_exam2student >0 do
    set count_exam2student = count_exam2student - 1;      
    set education_exam_2_student__id = education_exam_2_student__id - 1;     
    set paper_log_id_ = basic_memory__index('education_paper_log');   
    update education_exam_2_student set 
        status = 23
        ,id_paper_log = paper_log_id_
        where id = education_exam_2_student__id;   
    
    select 
        exam_id        
        ,student_id
        ,student_name
        ,student_code
        ,subject_code
        ,subject_name    
        ,id_creater_group
        ,code_creater_group    
        ,id_paper           
        ,time_created
        
        ,exam_title   
        ,teacher_id
        ,teacher_name
        ,teacher_code
    into 
        exam_id_
        ,student_id_
        ,student_name_
        ,student_code_
        ,subject_code_
        ,subject_name_   
        ,id_creater_group_
        ,code_creater_group_         
        ,paper_id_     
        ,thedate_   

        ,@exam_title        
        ,@teacher_id
        ,@teacher_name
        ,@teacher_code
    from education_exam_2_student where id = education_exam_2_student__id;  
    
    select time_start into thedate_ from education_exam where id = exam_id_;
    

    select cent into @paper_cent from education_paper where id = paper_id_;
         
    insert into education_paper_log (    
        paper_id
        ,paper_title
        ,teacher_id
        ,teacher_name
        ,teacher_code        
        ,student_id  
        ,student_name 
        ,student_code
        
        ,cent
        ,cent_subjective
        ,cent_objective
        
        ,subject_code
        ,subject_name
        
        ,type
        ,id
        ,id_creater
        ,id_creater_group
        ,code_creater_group
        ,time_created
        ,time_lastupdated
        ,status
        ,remark
    ) values (    
        paper_id_
        ,@exam_title
        ,@teacher_id
        ,@teacher_name
        ,@teacher_code        
        ,student_id_
        ,student_name_
        ,student_code_
        
        ,@paper_cent
        ,'0'
        ,@paper_cent
        
        ,subject_code_
        ,subject_name_
        
        ,'1'
        ,paper_log_id_
        ,student_id_
        ,id_creater_group_
        ,code_creater_group_
        ,thedate_
        ,thedate_
        ,'0'
        ,'education_exam_2_student__init4test'
    );        
    update education_exam_2_class set count_submit = count_submit + 1 where exam_id = exam_id_ and class_code = code_creater_group_;

    if thedate_ = '2011-09-01' then        
        set lograte = 60 + floor(rand()*10) - floor(rand()*10);
    end if;    
    if thedate_ = '2011-10-01' then                
        set lograte = 70 + floor(rand()*9) - floor(rand()*9);
    end if;    
    if thedate_ = '2011-11-01' then               
        set lograte = 80 + floor(rand()*8) - floor(rand()*8);
    end if;    
    if thedate_ = '2011-12-01' then             
        set lograte = 85 + floor(rand()*7) - floor(rand()*7);
    end if;    
    if thedate_ = '2012-03-01' then           
        set lograte = 87 + floor(rand()*6) - floor(rand()*6);
    end if;    
    if thedate_ = '2012-04-01' then             
        set lograte = 89 + floor(rand()*5) - floor(rand()*5);
    end if;    
    if thedate_ = '2012-05-01' then              
        set lograte = 91 + floor(rand()*4) - floor(rand()*4);
    end if;    
    if thedate_ = '2012-06-01' then              
        set lograte = 95 + floor(rand()*3) - floor(rand()*3);
    end if; 
    
    select max(id_question),min(id_question) into question_id_max_,question_id_min_ from education_paper_2_question where id_paper = paper_id_;    
   
    set count_question = question_id_max_ - question_id_min_ + 1;    
    while_question: while count_question > 0 do    
        set count_question = count_question - 1;          
        set question_id_ = question_id_max_ - count_question;                
        set question_log_id_ = basic_memory__index('education_question_log');          
        set thelograte = floor(rand()*100);   
        set theanswer = 'A';     
        if thelograte > lograte then                
            set theanswer = 'B';
        end if;    

        insert into education_question_log (        
            id_paper
            ,id_paper_log
            ,id_question
            ,myanswer         
            ,id_teacher
            
            ,type
            ,id
            ,id_creater
            ,id_creater_group
            ,code_creater_group
            ,time_created
            ,time_lastupdated
            ,status
            ,remark
        ) values (        
            paper_id_
            ,paper_log_id_
            ,question_id_
            ,theanswer
            ,@teacher_id
            
            ,'0'
            ,question_log_id_
            ,student_id_
            ,id_creater_group_
            ,code_creater_group_
            ,thedate_
            ,thedate_
            ,'0'
            ,'education_exam_2_student__init4test'
        );              
        
    end while while_question;          
    if mod(count_exam2student,500) = 0 then    
        commit;          
        START TRANSACTION; 
    end if;
end while while_exam2student;
commit;
END;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for education_immunity
-- ----------------------------
DROP PROCEDURE IF EXISTS `education_immunity`;
DELIMITER ;;
CREATE PROCEDURE `education_immunity`()
BEGIN
/*
教育模块的免疫系统

自动扫描教育模块每一张业务表近期创建的数据,检查数据的准确性

*/

END;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for education_paper__checkMoney
-- ----------------------------
DROP PROCEDURE IF EXISTS `education_paper__checkMoney`;
DELIMITER ;;
CREATE PROCEDURE `education_paper__checkMoney`(IN in_id_user int,IN in_id_paper int,OUT out_state int,OUT out_msg varchar(200),OUT out_money_left int)
pro_main:BEGIN
/*
学生要做试卷,但是试卷要扣金币的,得判断一下学生的金币够不够


@version 201209
@author wei1224hf@gmail.com
*/

declare id_user_ int;
declare money_ int;    
declare cost_ int;
declare id_paper_ int;    
                                        
    #判断用户编号是否正确
    select id,money into id_user_,money_ from basic_user where id = in_id_user;        
    if id_user_ is null then        
        set out_msg = 'no user';      
        set out_state = 0;          
        leave pro_main;        
    end if;
  
    #判断试卷编号是否正确
    select id,cost into id_paper_,cost_ from education_paper where id = in_id_paper;        
    if id_paper_ is null then                
        set out_msg = 'no paper';                        
        set out_state = 0;            
        leave pro_main;            
    end if;
                                  
    #判断金币是否充足
    if ( money_ < cost_ ) then            
        set out_msg = 'more money';                                
        set out_state = 0;                
        leave pro_main;
    end if; 
                                    
    #扣除金币,增加积分
    update basic_user set 
        money = ( money_ - cost_ )         
        ,time_lastupdated = now()        
        ,count_updated = count_updated + 1        
        ,money2 = money2 + 4
        where id = id_user_ ;  
                      
    set out_msg = 'ok';  
    set out_money_left = money_ - cost_ ;                        
    set out_state = 1;      

END;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for education_paper__delete
-- ----------------------------
DROP PROCEDURE IF EXISTS `education_paper__delete`;
DELIMITER ;;
CREATE PROCEDURE `education_paper__delete`(IN `ids` varchar(200),out out_state int,out out_msg varchar(200))
pro_main:begin
/*
批量删除试卷数据

@author wei1224hf@gmail.com
@version 201301
*/        
    if ids is null then        
        set out_state = 0;        
        set out_msg = 'ids null';        
        leave pro_main;        
    end if;
         
    set @sql_ = concat(\"delete from education_paper where id in (\",ids,\") \"); 
    PREPARE stmt FROM @sql_;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;      

    #删除题目
    set @sql_ = concat(\"delete from education_question where id in (select id_question from education_paper_2_question where id_paper in (\",ids,\")) \");
    PREPARE stmt FROM @sql_;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;     

    #删除 题目-试卷关系
    set @sql_ = concat(\"delete from education_paper_2_question where id_paper in (\",ids,\") \");
    PREPARE stmt FROM @sql_;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;     

    #删除做题日志
    set @sql_ = concat(\"delete from education_question_log where id_paper in (\",ids,\") \");
    PREPARE stmt FROM @sql_;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;     

    #删除试卷日志
    set @sql_ = concat(\"delete from education_paper_log where paper_id in (\",ids,\") \");
    PREPARE stmt FROM @sql_;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt; 

END;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for education_paper__export
-- ----------------------------
DROP PROCEDURE IF EXISTS `education_paper__export`;
DELIMITER ;;
CREATE PROCEDURE `education_paper__export`(
in in_paperid int
,in in_excelid varchar(200)
,in in_sheetcount int
,in in_sheetindex int
,out out_state int
,out out_msg varchar(200)
,out out_excelid varchar(200)
,out out_sheetcount int
,out out_sheetindex int)
pro_main:BEGIN
/*
试卷导出

@version 201301
@author wei1224hf@gmail.com
*/
declare _paperid,        
        _rownum 
    int;    

declare _excelid 
    varchar(200);

    select id into _paperid from education_paper 
        where id = in_paperid limit 1;        
    if _paperid is null then        
        set out_state = 0;set out_msg = 'wrong paperid';
        leave pro_main;        
    end if;
  
    if in_excelid is null then
        set in_excelid = concat('',floor(rand()*100000));           
    end if;   
    set out_excelid = in_excelid;     

    if in_sheetindex is null then
        set in_sheetindex = 0;        
    end if;        
    set out_sheetindex = in_sheetindex + 1;        
        
    if in_sheetcount is null then
        set in_sheetcount = 1;        
    end if;      
    set out_sheetcount = in_sheetcount;

    insert into basic_excel (    
        guid
        ,sheets
        ,sheetindex
        ,sheetname
        ,rowindex
        ,maxcolumn

        ,A
        ,B
        ,C
        ,D
        

    ) values (         
        out_excelid 
        ,in_sheetcount        
        ,in_sheetindex     
        ,basic_memory__il8n('education_paper','education_paper',1)
        ,1
        ,4
        
        ,basic_memory__il8n('subject_code','education_paper',1)
        ,basic_memory__il8n('title','education_paper',1)
        ,basic_memory__il8n('cost','education_paper',1)
        ,basic_memory__il8n('teacher_name','education_paper',1)
        
    );
       
    insert into basic_excel (    
        guid
        ,sheets
        ,sheetindex
        ,sheetname
        ,rowindex
        ,maxcolumn

        ,A
        ,B
        ,C
        ,D
        

    ) select         
        out_excelid
        ,in_sheetcount        
        ,in_sheetindex   
        ,basic_memory__il8n('paper','education_paper',1)
        ,2
        ,4
        
        ,subject_code
        ,title
        ,cost
        ,teacher_code
                                                                            
     from education_paper where id = in_paperid;     

     call education_question__export(in_paperid,in_excelid,(in_sheetcount+1),out_sheetindex,
          out_state,out_msg,out_excelid,out_sheetcount,out_sheetindex);

END;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for education_paper__import
-- ----------------------------
DROP PROCEDURE IF EXISTS `education_paper__import`;
DELIMITER ;;
CREATE PROCEDURE `education_paper__import`(
IN in_guid char(36),
OUT out_state int,
OUT out_msg varchar(200),
out out_id int)
pro_main:BEGIN
/*
导入一张试卷


@version 201209
@author wei1224hf@gmail.com
*/

declare fig int;     
declare A_,B_,C_,D_,E_,F_,G_,H_,I_,J_,K_,L_,M_ varchar(200);    
declare A__,B__,C__,D__,E__,F__,G__,H__,I__,J__,K__,L__,M__ varchar(2000);            
declare id_creater_,
        id_creater_group_ int;
declare code_,row1_,row2_,
        subject_code_,subject_name_,        
        teacher_name_,        
        teacher_code_,
        code_creater_group_ varchar(200);
declare cur_array cursor for     
    SELECT code,row1,row2 from array_paper;   
declare CONTINUE HANDLER FOR SQLSTATE '02000' SET fig = 1;       

    if in_guid is null then        
        set out_state = 0;        
        set out_msg = 'null guid';
        leave pro_main;
    end if;      
        
    /*创建一张临时内存表.数据库不支持 数组 对象 等在服务端代码中常见的,所以使用临时表*/
    drop TEMPORARY  table if exists array_paper;
    create  TEMPORARY  table array_paper (
        code varchar(2)        
        ,row1 varchar(200)   
        ,row2 varchar(200)        
    ) engine = memory ;             

    /*表头,用于检查一些必要的列是否存在*/
    select A,B,C,D,E,F,G,H,I,J,K,L,M,id_creater into A_,B_,C_,D_,E_,F_,G_,H_,I_,J_,K_,L_,M_,id_creater_ from basic_excel where guid = in_guid and rowindex = 1 and sheetname = basic_memory__il8n('paper','education_paper',1) ;       
    /*读取正式的试卷数据*/
    select A,B,C,D,E,F,G,H,I,J,K,L,M into A__,B__,C__,D__,E__,F__,G__,H__,I__,J__,K__,L__,M__           from basic_excel where guid = in_guid and rowindex = 2 and sheetname = basic_memory__il8n('paper','education_paper',1);        
    if A_ is null then          
        set out_state = 0;        
        set out_msg = 'wrong guid';
        leave pro_main;
    end if;        

    /*如果用户编号或用户组编号异常的话,就直接退出*/
    select id_creater,(select group_id from basic_user where id = basic_excel.id_creater ) into id_creater_,id_creater_group_  from basic_excel where guid = in_guid limit 1;
    if ( id_creater_ is null ) or  (id_creater_group_ is null ) then   
        set out_state = 0;
        set out_msg = 'id_user wrong';    
        leave pro_main;    
    end if;

    /*将读取到的业务数据放入到临时内存表中*/
    insert into array_paper values 
        ('A', basic_memory__il8n( A_,'education_paper', 2), A__  ),        
        ('B', basic_memory__il8n( B_,'education_paper', 2), B__  ),        
        ('C', basic_memory__il8n( C_,'education_paper', 2), C__  ),        
        ('D', basic_memory__il8n( D_,'education_paper', 2), D__  ),        
        ('E', basic_memory__il8n( E_,'education_paper', 2), E__  ),        
        ('F', basic_memory__il8n( F_,'education_paper', 2), F__  ),        
        ('G', basic_memory__il8n( G_,'education_paper', 2), G__  ),        
        ('H', basic_memory__il8n( H_,'education_paper', 2), H__  ),              
        ('I', basic_memory__il8n( I_,'education_paper', 2), I__  ),              
        ('J', basic_memory__il8n( J_,'education_paper', 2), J__  ),              
        ('K', basic_memory__il8n( K_,'education_paper', 2), K__  ),              
        ('L', basic_memory__il8n( L_,'education_paper', 2), L__  ),              
        ('M', basic_memory__il8n( M_,'education_paper', 2), M__  )
    ; 
    #select * from array_paper;         
        
    /*业务数据中只能含有这些数据:标题 科目 金币 备注*/
    set @columnsimport = 'title,subject_code,cost,teacher_name,remark';    
    set @sql_keys_paper = '';    
    set @sql_values_paper = '';        

    /*开启游标,开始验证业务数据的合理性*/
    set fig = 0;    
    open cur_array;    
    fetch cur_array into code_,row1_, row2_;    
    while( fig = 0 ) do      
        # select row2_,row1_,cdoe_;
        if row1_ is null then              
            set out_msg = concat(out_msg,code_,'1','null;');  
        elseif FIND_IN_SET(row1_,@columnsimport) = 0 then 
            #select 4;    
            #select concat(row1_,columnsimport);            
            #select FIND_IN_SET(row1_,columnsimport);
            set out_msg = concat(out_msg,code_,'1','cant;');      
               
        elseif row1_ = 'cost' then         
            select ( row2_ REGEXP   '^[0-9]*$' ) into @temp ;
            if @temp = 0 then             
                /*如果这一列是 分数 ,而分值却不是整数,就报错*/ 
                set out_state = 0;
                set out_msg = concat(code_,'2 ',row2_,' : format wrong');                
                leave pro_main;
            end if;            
            /*如果没有错,就拼凑SQL语句,分别加上 key 跟 value*/
            set @sql_keys_paper = concat(@sql_keys_paper,row1_,' , ');              
            set @sql_values_paper = concat(@sql_values_paper,\"'\",row2_,\"',\");                
        elseif row1_ = 'subject_code' then 
            select code,name into subject_code_,subject_name_ from education_subject where code = row2_;                
            if subject_code_ is null then             
                /*如果这一列是 科目 ,而这个科目编号却无法在科目表中找到,就报错*/
                set out_state = 0;
                set out_msg = 'wrong subject';  
                #select @temp;              
                leave pro_main;
            end if;          
            set @sql_keys_paper = concat(@sql_keys_paper,row1_,' ,subject_name, ');              
            set @sql_values_paper = concat(@sql_values_paper,\"'\",row2_,\"','\",subject_name_,\"',\");     
            
        elseif row1_ = 'teacher_name' then 
            select id,group_id,group_code,person_name into id_creater_,id_creater_group_,code_creater_group_,teacher_name_ from basic_user where username = row2_;                            
            #select id_creater_,id_creater_group_,code_creater_group_; leave pro_main;
            if id_creater_ is null then             
                /*如果这一列是 作者 ,也就是教师的用户名称,而无法在用户组中找到,就报错*/
                set out_state = 0;
                set out_msg = 'wrong author';                
                leave pro_main;
            end if;                                    
            set teacher_code_ = row2_;
        else        
            #select 2;         
            set @sql_keys_paper = concat(@sql_keys_paper,row1_,' , ');              
            set @sql_values_paper = concat(@sql_values_paper,\"'\",row2_,\"',\");      
        end if;                                     
    fetch cur_array into code_,row1_, row2_;    
    end while;    
    close cur_array;       

    set out_id = basic_memory__index('education_paper'); 
    call education_question__import(in_guid,out_id,@q_state,@q_msg,@q_ids,@q_cent,@q_count);   
    if @q_state <> 1 then        
        set out_state = 2;        
        set out_msg = @q_msg;              
        leave pro_main;
    end if;      

    
    set @sql_insert_paper = concat(\"insert into education_paper (\"
                             ,@sql_keys_paper
                             ,\" cent,id_creater,id_creater_group,code_creater_group,
                             teacher_id,teacher_code,teacher_name
                             ,id,count_questions) values ( \"
                             ,@sql_values_paper
                             ,\"'\",@q_cent,\"',\"
                             ,\"'\",id_creater_,\"',\"
                             ,\"'\",id_creater_group_,\"',\"  
                             ,\"'\",id_creater_,\"',\"
                             ,\"'\",id_creater_group_,\"',\"                             
                             ,\"'\",teacher_code_,\"',\"                                                        
                             ,\"'\",teacher_name_,\"',\"
                             ,\"'\",out_id,\"',\"
                             ,\"'\",@q_count,\"')\");                

    #select @sql_insert_paper;    leave pro_main;

    PREPARE stmt FROM @sql_insert_paper;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;      
    delete from basic_excel where guid = in_guid;              

    set out_state = 1;
    set out_msg = 'ok'; 

    drop TEMPORARY table if exists array_paper;
END;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for education_paper__init4test
-- ----------------------------
DROP PROCEDURE IF EXISTS `education_paper__init4test`;
DELIMITER ;;
CREATE PROCEDURE `education_paper__init4test`(in in_papercount int)
pro_main:BEGIN
/*
随机,自动得往系统中插入很多张试卷

@version 201209
@author wei1224hf@gmail.com
*/

declare _group_id,
        _question_id,
        _user_id,
        _paper_id,
        _count_questions int default 0;
declare _subject_code,
        _subject_name,
        _group_name,
        _papertitle,
        _questiontitle,
        _username,
        _personname,
        _group_code varchar(200) default '';

/*
truncate table education_paper;
truncate table education_question ;
truncate table education_paper_2_question ;
truncate table education_paper_log ;
truncate table education_question_log ;
truncate table education_question_log_wrongs ;

update basic_memory set extend1 = 0 where type = 2 and code in (
 'education_paper'    
,'education_paper_2_question'
,'education_question'
,'education_paper_log'
,'education_question_log'
,'education_question_log_wrongs'
);
*/

delete from education_paper where remark = 'education_paper__init4test';
delete from education_question where remark = 'education_paper__init4test';

start transaction;
if in_papercount is null or in_papercount = 0 then
    set in_papercount = 1;    
end if;

while_paper:while in_papercount > 0  do
    set in_papercount = in_papercount - 1;    
    
    #得到一个随机的教师     
    SELECT
        basic_user.username,
        basic_user.group_id,
        basic_user.group_code,
        basic_user.person_name,
        basic_user.id        
        into        
        _username        
        ,_group_id        
        ,_group_code        
        ,_personname        
        ,_user_id
        FROM
        basic_user
        where type = 3 order by rand() limit 1;         

    #得到一个随机的科目
    select code,name into _subject_code,_subject_name from education_subject
        where type = 2 or type = 3 order by rand() limit 1;     

    #插入一张试卷    
    set _papertitle = concat('测试数据试卷',round(rand()*10000));        
    set _paper_id = basic_memory__index('education_paper');  
    insert into education_paper (
        subject_code
        ,subject_name
        ,count_questions
        ,count_used
        ,title
        ,cost
        ,teacher_id
        ,teacher_name
        ,teacher_code
        ,cent
        ,type
        ,id
        ,id_creater
        ,id_creater_group
        ,code_creater_group
        ,status        
        ,remark
     ) values (
        _subject_code
        ,_subject_name
        ,'50'
        ,'0'
        ,_papertitle
        ,floor(rand()*5)
        ,_user_id
        ,_personname
        ,_username
        ,100
        ,1
        ,_paper_id
        ,_user_id
        ,_group_id
        ,_group_code        
        ,4        
        ,'education_paper__init4test'
    ); 

    #50道单项选择题

    set _question_id = basic_memory__index('education_question');       
    insert into education_question (
        type
        ,subject_code        
        ,subject_name
        ,title
        ,id
        ,id_creater
        ,id_creater_group          
        ,code_creater_group            
        ,teacher_id        
        ,teacher_name      
        ,teacher_code
        ,remark
    ) values (
        7
        ,_subject_code        
        ,_subject_name     
        ,'50道单项选择题'
        ,_question_id
        ,_user_id
        ,_group_id
        ,_group_code            
        ,_user_id        
        ,_personname        
        ,_username
        ,'education_paper__init4test'
    ); 
    insert into education_paper_2_question (id_paper,id_question) values (_paper_id,_question_id);
    set _count_questions = 10;    
    while_question:while _count_questions > 0 do        
        set _count_questions = _count_questions - 1;    
        set _question_id = basic_memory__index('education_question');    
        set _questiontitle = concat('测试数据题目',round(rand()*100000));    
        insert into education_question (            
             answer
            ,optionlength
            ,option1
            ,option2
            ,option3
            ,option4
            ,description
            ,cent            
            ,type
            ,subject_code        
            ,subject_name
            ,title
            ,id
            ,id_creater
            ,id_creater_group          
            ,code_creater_group            
            ,teacher_id        
            ,teacher_name      
            ,teacher_code
            ,remark            
        ) values (
             'A'
            ,4
            ,concat('测试数据选项',round(rand()*100000),' A')
            ,concat('测试数据选项',round(rand()*100000),' B')
            ,concat('测试数据选项',round(rand()*100000),' C')
            ,concat('测试数据选项',round(rand()*100000),' D')
            ,concat('测试数据解题思路',round(rand()*100000))
            ,2            
            ,1
            ,_subject_code        
            ,_subject_name     
            ,_questiontitle
            ,_question_id
            ,_user_id
            ,_group_id
            ,_group_code            
            ,_user_id        
            ,_personname        
            ,_username
            ,'education_paper__init4test'            
        );        
       insert into education_paper_2_question (id_paper,id_question) values (_paper_id,_question_id);
    end while while_question;    

    #30道多项选择题

    set _question_id = basic_memory__index('education_question');       
    insert into education_question (
        type
        ,subject_code        
        ,subject_name
        ,title
        ,id
        ,id_creater
        ,id_creater_group          
        ,code_creater_group            
        ,teacher_id        
        ,teacher_name      
        ,teacher_code
        ,remark
    ) values (
        7
        ,_subject_code        
        ,_subject_name     
        ,'30道多项选择题'
        ,_question_id
        ,_user_id
        ,_group_id
        ,_group_code            
        ,_user_id        
        ,_personname        
        ,_username
        ,'education_paper__init4test'
    ); 
    insert into education_paper_2_question (id_paper,id_question) values (_paper_id,_question_id);
    set _count_questions = 10;    
    while_question2:while _count_questions > 0 do        
        set _count_questions = _count_questions - 1;    
        set _question_id = basic_memory__index('education_question');    
        set _questiontitle = concat('测试数据题目',round(rand()*100000));    
        insert into education_question (            
             answer
            ,optionlength
            ,option1
            ,option2
            ,option3
            ,option4
            ,description
            ,cent            
            ,type
            ,subject_code        
            ,subject_name
            ,title
            ,id
            ,id_creater
            ,id_creater_group          
            ,code_creater_group            
            ,teacher_id        
            ,teacher_name      
            ,teacher_code
            ,remark            
        ) values (
             'A'
            ,4
            ,concat('测试数据选项',round(rand()*100000),' A')
            ,concat('测试数据选项',round(rand()*100000),' B')
            ,concat('测试数据选项',round(rand()*100000),' C')
            ,concat('测试数据选项',round(rand()*100000),' D')
            ,concat('测试数据解题思路',round(rand()*100000))
            ,2            
            ,2
            ,_subject_code        
            ,_subject_name     
            ,_questiontitle
            ,_question_id
            ,_user_id
            ,_group_id
            ,_group_code            
            ,_user_id        
            ,_personname        
            ,_username
            ,'education_paper__init4test'            
        );        
       insert into education_paper_2_question (id_paper,id_question) values (_paper_id,_question_id);
    end while while_question2;    

    #20道单选题
    set _question_id = basic_memory__index('education_question');       
    insert into education_question (
        type
        ,subject_code        
        ,subject_name
        ,title
        ,id
        ,id_creater
        ,id_creater_group          
        ,code_creater_group            
        ,teacher_id        
        ,teacher_name      
        ,teacher_code
        ,remark
    ) values (
        7
        ,_subject_code        
        ,_subject_name     
        ,'20道判断题'
        ,_question_id
        ,_user_id
        ,_group_id
        ,_group_code            
        ,_user_id        
        ,_personname        
        ,_username
        ,'education_paper__init4test'
    ); 
    insert into education_paper_2_question (id_paper,id_question) values (_paper_id,_question_id);
    set _count_questions = 10;    
    while_question3:while _count_questions > 0 do        
        set _count_questions = _count_questions - 1;    
        set _question_id = basic_memory__index('education_question');    
        set _questiontitle = concat('测试数据题目',round(rand()*100000));    
        insert into education_question (            
             answer
            ,description
            ,cent            
            ,type
            ,subject_code        
            ,subject_name
            ,title
            ,id
            ,id_creater
            ,id_creater_group          
            ,code_creater_group            
            ,teacher_id        
            ,teacher_name      
            ,teacher_code
            ,remark            
        ) values (
             'A'
            ,concat('测试数据解题思路',round(rand()*100000))
            ,2            
            ,3
            ,_subject_code        
            ,_subject_name     
            ,_questiontitle
            ,_question_id
            ,_user_id
            ,_group_id
            ,_group_code            
            ,_user_id        
            ,_personname        
            ,_username
            ,'education_paper__init4test'            
        );        
       insert into education_paper_2_question (id_paper,id_question) values (_paper_id,_question_id);
    end while while_question3;

end while while_paper;
commit;
END;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for education_paper__init4test_1
-- ----------------------------
DROP PROCEDURE IF EXISTS `education_paper__init4test_1`;
DELIMITER ;;
CREATE PROCEDURE `education_paper__init4test_1`(
in in_questioncount int,
in in_teacherid int,
in in_subjectcode varchar(200),
out out_state int,
out out_msg varchar(200),
out out_paperid int,
out out_questionids varchar(2000)
)
pro_main:BEGIN
/*
初始化一张试卷,试卷类型1,只包含:
单选题

@version 201301
@author wei1224hf@gmail.com
*/

declare _paper_id
        ,_question_id
        ,_group_id    
        ,_user_id          
        int;             

declare _username   
        ,_group_code        
        ,_personname  
        ,_subject_code
        ,_subject_name        
        varchar(200);
        
    if in_questioncount is NULL or in_questioncount < 5 then   
        set out_paperid = 0; 
        leave pro_main;        
    end if;
        
    start transaction;     

    if in_teacherid is null then
        #得到一个随机的教师     
        SELECT
            basic_user.username,
            basic_user.group_id,
            basic_user.group_code,
            basic_user.person_name,
            basic_user.id        
            into        
            _username        
            ,_group_id        
            ,_group_code        
            ,_personname        
            ,_user_id
            FROM
            basic_user
            where type = 3 order by rand() limit 1;            
    else     
        SELECT
            basic_user.username,
            basic_user.group_id,
            basic_user.group_code,
            basic_user.person_name,
            basic_user.id        
            into        
            _username        
            ,_group_id        
            ,_group_code        
            ,_personname        
            ,_user_id
            FROM
            basic_user
            where id = in_teacherid;
    end if;  
    
    if in_subjectcode is NULL then
        #得到一个随机的科目
        select code,name into _subject_code,_subject_name from education_subject
            where type = 2 or type = 3 order by rand() limit 1;            
            #类型为科目或章节
    else     
        select code,name into _subject_code,_subject_name from education_subject
            where code = in_subjectcode;
    end if;   
    
    set _paper_id = basic_memory__index('education_paper');     
    insert into education_paper (    
        subject_code    
        ,subject_name
        ,count_questions
        ,title
        ,cost
        ,teacher_id
        ,teacher_name
        ,teacher_code
        ,cent
        ,type
        ,id
        ,id_creater
        ,id_creater_group
        ,code_creater_group
        ,status
        ,remark
    ) values (
        _subject_code    
        ,_subject_name
        ,in_questioncount
        ,concat('测试数据试卷',round(rand()*100000))
        ,2
        ,_user_id
        ,_personname
        ,_username
        ,(in_questioncount*2)
        ,2
        ,_paper_id
        ,_user_id
        ,_group_id        
        ,_group_code  
        ,'4'
        ,'education_paper__init4test_1'
    );
    set out_paperid = _paper_id  ;   
    set out_questionids = '';            

    #都是单选题
    while in_questioncount > 0 do            
        set _question_id = basic_memory__index('education_question');                      
        set out_questionids = concat(out_questionids,',',_question_id);
        insert into education_question (            
             answer
            ,optionlength
            ,option1
            ,option2
            ,option3
            ,option4
            ,description
            ,cent            
            ,type
            ,subject_code        
            ,subject_name
            ,title
            ,id
            ,id_creater
            ,id_creater_group          
            ,code_creater_group            
            ,teacher_id        
            ,teacher_name      
            ,teacher_code
            ,remark            
        ) values (
             'A'
            ,4
            ,concat('测试数据选项',round(rand()*100000),' A')
            ,concat('测试数据选项',round(rand()*100000),' B')
            ,concat('测试数据选项',round(rand()*100000),' C')
            ,concat('测试数据选项',round(rand()*100000),' D')
            ,concat('测试数据解题思路',round(rand()*100000))
            ,2            
            ,1
            ,_subject_code        
            ,_subject_name     
            ,concat('测试数据题目',round(rand()*100000))
            ,_question_id
            ,_user_id
            ,_group_id
            ,_group_code            
            ,_user_id        
            ,_personname        
            ,_username
            ,'education_paper__init4test_1'            
        );        
        insert into education_paper_2_question (id_paper,id_question) values (_paper_id,_question_id);
        set in_questioncount = in_questioncount - 1;
    end while;        
    commit;
END;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for education_paper__mark
-- ----------------------------
DROP PROCEDURE IF EXISTS `education_paper__mark`;
DELIMITER ;;
CREATE PROCEDURE `education_paper__mark`(IN in_id_paper_log int,OUT out_state int,OUT out_msg VARCHAR(200),OUT out_totalCent int,OUT out_myTotalCent int,OUT out_count_right int,OUT out_count_wrong int,OUT out_count_giveup int,OUT out_count_byTeacher int)
pro_papermark:BEGIN
/*
批改一张卷子


@version 201209
@author wei1224hf@gmail.com
*/
declare id_creater_,
        id_creater_group_,
        id_paper_,
        fig,
        id_parent_,
        id_,
        id_question_,
        correct_,
        type_,
        cent_,
        mycent_,        
        isByTeacher,        
        wrongCounts
    int default 0;      

declare answer_,
        myanswer_,        
        wrongPaperTitle,       
        wrongTeacherName
    varchar(200) default '0';   

declare sqls,
        sql_
    text;    


#定义游标
declare cur cursor for     
    select        
    education_question.id,        
    education_question.answer,        
    education_question.cent,
    education_question.type,
    education_question.id_parent,        
    education_question_log.myanswer
    from education_question_log left join education_question 
        on education_question_log.id_question = education_question.id             
        where education_question_log.id_paper_log = in_id_paper_log;            

#以下变量用于游标
declare CONTINUE HANDLER FOR SQLSTATE '02000' SET fig = 1;
SET fig=0;
#DECLARE CONTINUE HANDLER FOR 1062 SET out_msg='wrong';         

    set sqls = '';    
    set sql_ = '';  
    set out_totalCent = 0;
    set out_myTotalCent = 0;
    set out_count_right = 0;
    set out_count_wrong = 0;
    set out_count_giveup = 0;
    set out_count_byTeacher = 0;  

    #检查这个日志是否存在,如果不存在就退出    
    select paper_id
           ,id_creater
           ,id_creater_group 
        into 
           id_paper_
           ,id_creater_
           ,id_creater_group_ 
        from education_paper_log where id = in_id_paper_log ;        
    if id_paper_ is null then         
        set out_msg = ' unexisted paperlog  ';        
        set out_state = 0;        
        leave pro_papermark;
    end if;    

    #检查这个日志对应的试卷是否存在,如果不存在,直接报错退出    
    select id into id_paper_ from education_paper where id = id_paper_;        
    if id_paper_ is NULL then               
        set out_msg = ' wrong paperlog , no paper associated ';  
        set out_state = 0;                  
        leave pro_papermark;
    end if;      

    START TRANSACTION; 
    open cur;
    fetch cur into id_question_,answer_,cent_,type_,id_parent_,myanswer_;      
    WHILE ( fig = 0 ) DO            
        set correct_ = 0;
        set out_totalCent = out_totalCent + cent_ ;    #累计试卷的总分    
        set mycent_ = 0;          
        if ( type_ = 1 or type_ = 2 or type_ = 3)  then #题目为 自动批改 模式                                

            if myanswer_ = 'I_DONT_KNOW' then                 #直接放弃不做了

                set correct_ = 0;                                    
                set out_count_giveup = out_count_giveup + 1;          #累计学生的放弃题目总数 
            elseif myanswer_ = answer_ then                           #做对了                    
                set correct_ = 1;   
                set mycent_ = cent_;
                set out_count_right = out_count_right + 1;            #累计学生的作对题目总数
                set out_myTotalCent = out_myTotalCent + mycent_ ;     #累计学生的得分

            else    
                #做错了,操作错题本

                set correct_ = 2;                            

                set out_count_wrong = out_count_wrong + 1;                            
                #select id_parent_ , type_;                        
                if ( ( id_parent_ = 0 ) && ( type_ = 1 or type_ = 2 or type_ = 3) ) then                   
                    #select id_question_,id_user_;
                    select count(id) into wrongCounts from education_question_log_wrongs where question_id = id_question_ and id_creater = id_creater_;                                                                                                
                    if wrongCounts = 0 then #错题记录不存在,就需要新插入一条                          
                        insert into education_question_log_wrongs 
                        (
                               question_id
                               ,id_creater                                       
                               ,id_creater_group
                               ,id
                               ,question_title                                       
                               ,paper_title                                       
                               ,subject_code                               
                               ,subject_name
                        ) 
                            values
                        (
                            id_question_
                            ,id_creater_                                    
                            ,id_creater_group_
                            ,basic_memory__index('education_question_log_wrongs')                                    
                            ,( select title from education_question where id = id_question_ )
                            ,( select title from education_paper where id = ( select paper_id from education_paper_log where id = in_id_paper_log ) )                                    
                            ,( select subject_code from education_question where id = id_question_ )                            
                            ,( select subject_name from education_question where id = id_question_ )
                        );                                                                   
                    else #错题记录存在,就需要累加错题次数        
                        update education_question_log_wrongs set 
                            count_wrong = count_wrong + 1 ,                                
                            time_lastupdated = now()
                            where question_id = id_question_ and id_creater = id_creater_;                                                                
                    end if;                                                    
                end if;                        
            end if;        
        elseif ( ( type_ = 4 and cent_ <> 0 ) or type_ = 6 ) then #题目需要教师批改        
            set out_count_byTeacher = out_count_byTeacher + 1;                  
        else                 
            # type = 5 or type = 7 ,组合题或大题,不用处理
            set out_msg = ' asdf ';            
        end if;   

/*
#这几行会导致速度极慢 TODO
                update education_question_log set
                    correct = correct_,
                    mycent = mycent_ 
                        where 
                            id_question = id_question_ ;
              */                 
                                           

    fetch cur into id_question_,answer_,cent_,type_,id_parent_,myanswer_ ;      
    END WHILE;
    close cur;                           

    update education_paper_log set 
        count_right = out_count_right,
        count_wrong = out_count_wrong,
        count_giveup = out_count_giveup,
        mycent_subjective = out_count_byTeacher,
        count_total = (out_count_right + out_count_wrong + out_count_giveup + out_count_byTeacher)  ,        
        mycent = out_myTotalCent 
            where id = in_id_paper_log ;                     

    set out_state = 1;                
    set out_msg = 'done';            
    commit;
END;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for education_paper__submit
-- ----------------------------
DROP PROCEDURE IF EXISTS `education_paper__submit`;
DELIMITER ;;
CREATE PROCEDURE `education_paper__submit`(
IN in_id int
,IN in_user_id int
,OUT out_state int
,OUT out_msg varchar(200)
,OUT out_paperlogid int)
pro_main:BEGIN
/*
 试卷提交 ,
没有做 question_Log 操作, 那些数据的插入操作,直接在服务端代码实施

@version 201301
@author wei1224hf@gmail.com
*/
declare _paperlogid
        ,_teacher_id         
        ,_id_paper        
        ,_cent        
        ,_group_id        
        ,_type
        int;

declare _title
        ,_subject_name        
        ,_subject_code        
        ,_teacher_name
        ,_teacher_code        
     
        ,_username        
        ,_person_name        
        ,_group_code        
        ,_group_name
        varchar(200);        

    #如果传递进来的参数为空,就报错,并直接退出

    if (in_id is null) or (in_user_id is null) then        
        set out_state = 0;        
        set out_msg = 'wrong';
        leave pro_main;        
    end if;        

    #将试卷的一些业务属性取出来,待会儿要当做冗余数据插入到试卷做题日志表    
    select     
         title
        ,subject_name
        ,subject_code    
        ,teacher_id
        ,teacher_name
        ,teacher_code  
        ,id      
        ,cent           
        ,type
        into               
         _title
        ,_subject_name
        ,_subject_code       
        ,_teacher_id
        ,_teacher_name
        ,_teacher_code         
        ,_id_paper     
        ,_cent           
        ,_type
    from education_paper where id = in_id;      

    #将当前操作用户的业务属性也读取出来,冗余数据
    select 
         username
        ,person_name
        ,group_id
        ,group_code
        ,group_name 
    into 
         _username        
        ,_person_name   
        ,_group_id     
        ,_group_code        
        ,_group_name
    from basic_user where id = in_user_id;

    #从内存表中得到试卷日志编号

    set _paperlogid = basic_memory__index('education_paper_log');          

    #做题日志的类别编号,跟试卷的类别编号完全一致

    insert into education_paper_log (
         paper_id
        ,paper_title
        ,teacher_id
        ,teacher_name
        ,teacher_code        
        ,student_id
        ,student_name
        ,student_code 
        ,subject_code
        ,subject_name        
        ,cent
        ,type    
        ,id
        ,id_creater
        ,id_creater_group
        ,code_creater_group
        ,status                     #0 运维处理 1 已批改 2 未批改

    ) values (
         _id_paper
        ,_title         
        ,_teacher_id
        ,_teacher_name
        ,_teacher_code  
        ,in_user_id
        ,_person_name
        ,_username  
        ,_subject_code          
        ,_subject_name
        ,_cent      
        ,_type       
        ,_paperlogid        
        ,in_user_id        
        ,_group_id        
        ,_group_name      
        ,'2'
    );    
        
    set out_state = 1; set out_msg = 'OK'; set out_paperlogid = _paperlogid;
END;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for education_paper_log__init4test_1
-- ----------------------------
DROP PROCEDURE IF EXISTS `education_paper_log__init4test_1`;
DELIMITER ;;
CREATE PROCEDURE `education_paper_log__init4test_1`(
in in_paper_id int,
in in_student_id int,
in in_rate int,
in in_date char(10),
out out_state int,
out out_msg varchar(200),
out out_paper_log_id int
)
pro_main:begin
/*
初始化一条试卷日志


@version 201301
@author wei1224hf@gmail.com
*/

declare  fig        
        ,_teacher_id
        ,_id_creater        
        ,_id_creater_group                
        ,_question_id                
        ,_question_log_id
        ,_paper_log_id
        ,_rate        
        ,_cent
        ,_type 
        int;
        
declare  _code_creater_group        
        ,_student_name        
        ,_student_code                    
        ,_answer       
        ,_myanswer          
        ,_teacher_name
        ,_teacher_code        
        ,_subject_code
        ,_subject_name
        ,_title
        varchar(200);

declare cur_question cursor for
        select education_question.id,education_question.answer
        from education_paper_2_question left join education_question on education_paper_2_question.id_question = education_question.id        
        where education_paper_2_question.id_paper = in_paper_id;  
declare CONTINUE HANDLER FOR SQLSTATE '02000' SET fig = 1;           

    if in_paper_id is null then        
        set out_state = 0;        
        set out_msg = 'parameter null';        
        leave pro_main;
    else
        select teacher_id, teacher_name, teacher_code, subject_code, subject_name, title, cent, type
        into _teacher_id, _teacher_name, _teacher_code, _subject_code, _subject_name, _title, _cent, _type 
        from education_paper where id = in_paper_id;        
        if _teacher_id is null then                
            set out_state = 0;            
            set out_msg = 'wrong paper id';
            leave pro_main;            
        end if;
    end if;    

    if in_student_id is null then        
        set out_state = 0;        
        set out_msg = 'parameter null';        
        leave pro_main;
    else
        select username,group_id,group_code,person_name into
        _student_code,_id_creater_group,_code_creater_group,_student_name from basic_user where id = in_student_id;        
        if _student_code is null then                
            set out_state = 0;            
            set out_msg = 'wrong student id';
            leave pro_main;            
        end if;
    end if;      

    if in_date is null then set in_date = '2012-01-01';end if;
              
    START TRANSACTION;
    set _paper_log_id = basic_memory__index('education_paper_log');
    insert into education_paper_log (    
         paper_title         
        ,paper_id
        ,teacher_id
        ,teacher_name
        ,teacher_code
        ,student_id
        ,student_name
        ,student_code
        ,cent
        ,subject_code
        ,subject_name
        ,type
        ,id
        ,id_creater
        ,id_creater_group
        ,code_creater_group        
        ,time_created
        ,status
        ,remark
    ) values (    
         _title         
        ,in_paper_id
        ,_teacher_id
        ,_teacher_name
        ,_teacher_code
        ,in_student_id
        ,_student_name
        ,_student_code
        ,_cent
        ,_subject_code
        ,_subject_name
        ,_type
        ,_paper_log_id
        ,in_student_id
        ,_id_creater_group
        ,_code_creater_group        
        ,in_date
        ,0
        ,'education_paper_log__init4test_1'
    );
    

    if in_rate is null then    
        set in_rate = floor(rand()*100);        
    end if;    

    set fig=0;   
    open cur_question;
    fetch cur_question into _question_id,_answer;
    WHILE ( fig = 0 ) DO        
        set _rate = floor(rand()*100);   
        set _myanswer = 'D';
        if _rate >= in_rate then                
            set _myanswer =  _answer;
        end if;  
        
        set _question_log_id = basic_memory__index('education_question_log');      
        insert into education_question_log (        
             id_paper
            ,id_paper_log
            ,id_question
            ,myanswer
            ,type
            ,id
            ,id_creater
            ,id_creater_group
            ,code_creater_group            
            ,time_created
            ,status
            ,remark
        ) values (        
             in_paper_id
            ,_paper_log_id
            ,_question_id
            ,_myanswer
            ,_type
            ,_question_log_id
            ,in_student_id
            ,_id_creater_group
            ,_code_creater_group            
            ,in_date
            ,0
            ,'education_paper_log__init4test_1'
        );
       
    fetch cur_question into _question_id,_answer;  
    END WHILE;
    close cur_question; 
    commit;     

    set out_state = 1;    
    set out_msg = 'ok';    
    set out_paper_log_id = _paper_log_id;
end;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for education_paper_log__init4test2
-- ----------------------------
DROP PROCEDURE IF EXISTS `education_paper_log__init4test2`;
DELIMITER ;;
CREATE PROCEDURE `education_paper_log__init4test2`()
pro_main:BEGIN
/*
模拟学生做卷子


模拟高三整一年的理科测试

前提条件:
插入了5个班级,每个班级40人，总计200人


插入了 语文 数学 英语 理综 4个科目


每个科目每个月3张练习卷,总计8个月

然后模拟2011年秋季到2012年春季,每个学生的做题情况,他们的成绩按月份逐月上升

月份 概率 浮动
9    60%  10%
10   70%  9%
11   80%  8% 
12   85%  7%

3    87%  6%
4    88%  5%
5    90%  4%
6    95%  3%

其中,女同学的话 语文 英语 加2%提升,男同学在 数学 理综 加2%

200*4*3*8 = 
*/

declare count_papers,count_questions int default 0;
declare papertitle varchar(200) default '';
declare questiontitle varchar(200) default '';
declare paperid,questionid,papaerlogid,questionlogid int default 0;
declare userid,usergroupid int default 0;
declare personname varchar(200) default '0' ;
declare subjectcode varchar(200) default '';

declare count_month,count_subject,count_paper,count_paperlog int default 0;
declare paperdate char(10);
declare theanswer char(1);

declare randmonth,lograte,thelograte int default 0;
declare x1,x2,x3,x4,x5,x6,x7 int default 0;
declare x8 varchar(200) default '';

leave pro_main;

truncate table education_paper ;
truncate table education_paper_2_question ;
truncate table education_question ;
truncate table education_paper_log ;
truncate table education_question_log ;
truncate table education_question_log_wrongs ;

START TRANSACTION; 
##先插入试卷


set count_month = 8 ;
while_month:while count_month >0 do
    set count_month = count_month - 1;        
    if count_month = 7 then set paperdate = '2011-09-01'; end if;    
    if count_month = 6 then set paperdate = '2011-10-01'; end if;    
    if count_month = 5 then set paperdate = '2011-11-01'; end if;    
    if count_month = 4 then set paperdate = '2011-12-01'; end if;    
    if count_month = 3 then set paperdate = '2012-03-01'; end if;    
    if count_month = 2 then set paperdate = '2012-04-01'; end if;    
    if count_month = 1 then set paperdate = '2012-05-01'; end if;    
    if count_month = 0 then set paperdate = '2012-06-01'; end if;    

    set count_subject = 4;    
    while_subject: while count_subject > 0 do        
        set count_subject = count_subject - 1;           
        
        set count_paper = 3 ;        
        while_paper: while count_paper > 0 do                 
            set count_paper = count_paper - 1;    
            
            set papertitle = concat('测试数据试卷',round(rand()*10000));        
            set paperid = basic_memory__index('education_paper');              
            set subjectcode = concat('5',count_subject);
                  
            select 
                 basic_user.id
                ,basic_user.id_group         
                ,basic_person.name
            into 
                 userid
                ,usergroupid         
                ,personname
            from basic_user left join basic_person on basic_user.id_person = basic_person.id 
                where type = 3 order by rand() limit 1;   
                
            insert into education_paper (
                subject
                ,title
                ,id_creater
                ,id_creater_group
                ,id
                ,cost        
                ,author                
                ,time_created
            ) values (
                 subjectcode        
                ,papertitle        
                ,userid
                ,usergroupid
                ,paperid
                ,floor(rand()*100)        
                ,personname                
                ,paperdate
            );    
            
            set questionid = basic_memory__index('education_question');       
            insert into education_question (
                type
                ,subject
                ,title
                ,id
                ,id_creater
                ,id_creater_group            
                ,author
            ) values (
                7
                ,subjectcode        
                ,'25道单项选择题'
                ,questionid
                ,userid
                ,usergroupid            
                ,personname
            ); 
            insert into education_paper_2_question (id_paper,id_question) values (paperid,questionid);
            set count_questions = 25;    
            while_question:while count_questions > 0 do        
                set count_questions = count_questions - 1;    
                set questionid = basic_memory__index('education_question');    
                set questiontitle = concat('测试数据题目',round(rand()*100000));    
                insert into education_question (
                    type
                    ,subject
                    ,title
                    ,answer
                    ,optionlength
                    ,option1
                    ,option2
                    ,option3
                    ,option4
                    ,description
                    ,cent
                    ,id
                    ,id_creater
                    ,id_creater_group            
                    ,author
                ) values (
                    1
                    ,subjectcode        
                    ,questiontitle
                    ,'A'
                    ,4
                    ,concat('测试数据选项',round(rand()*100000),' A')
                    ,concat('测试数据选项',round(rand()*100000),' B')
                    ,concat('测试数据选项',round(rand()*100000),' C')
                    ,concat('测试数据选项',round(rand()*100000),' D')
                    ,concat('测试数据解题思路',round(rand()*100000))
                    ,2
                    ,questionid
                    ,userid
                    ,usergroupid            
                    ,personname
                );        
               insert into education_paper_2_question (id_paper,id_question) values (paperid,questionid);
            end while while_question;

            set questionid = basic_memory__index('education_question');       
            insert into education_question (
                type
                ,subject
                ,title
                ,id
                ,id_creater
                ,id_creater_group            
                ,author
            ) values (
                7
                ,subjectcode        
                ,'15道多项选择题'
                ,questionid
                ,userid
                ,usergroupid            
                ,personname
            ); 
            insert into education_paper_2_question (id_paper,id_question) values (paperid,questionid);
            set count_questions = 15;    
            while_question2:while count_questions > 0 do        
                set count_questions = count_questions - 1;    
                set questionid = basic_memory__index('education_question');    
                set questiontitle = concat('测试数据题目',round(rand()*100000));    
                insert into education_question (
                    type
                    ,subject
                    ,title
                    ,answer
                    ,optionlength
                    ,option1
                    ,option2
                    ,option3
                    ,option4
                    ,description
                    ,cent
                    ,id
                    ,id_creater
                    ,id_creater_group            
                    ,author
                ) values (
                    2
                    ,subjectcode        
                    ,questiontitle
                    ,'A'
                    ,4
                    ,concat('测试数据选项',round(rand()*100000),' A')
                    ,concat('测试数据选项',round(rand()*100000),' B')
                    ,concat('测试数据选项',round(rand()*100000),' C')
                    ,concat('测试数据选项',round(rand()*100000),' D')
                    ,concat('测试数据解题思路',round(rand()*100000))
                    ,2
                    ,questionid
                    ,userid
                    ,usergroupid            
                    ,personname
                );        
               insert into education_paper_2_question (id_paper,id_question) values (paperid,questionid);
            end while while_question2; 

            set questionid = basic_memory__index('education_question');       
            insert into education_question (
                type
                ,subject
                ,title
                ,id
                ,id_creater
                ,id_creater_group            
                ,author
            ) values (
                7
                ,subjectcode        
                ,'10道单选题'
                ,questionid
                ,userid
                ,usergroupid            
                ,personname
            ); 
            insert into education_paper_2_question (id_paper,id_question) values (paperid,questionid);
            set count_questions = 10;    
            while_question3:while count_questions > 0 do        
                set count_questions = count_questions - 1;    
                set questionid = basic_memory__index('education_question');    
                set questiontitle = concat('测试数据题目',round(rand()*100000));    
                insert into education_question (
                    type
                    ,subject
                    ,title
                    ,answer
                    ,optionlength
                    ,description
                    ,cent
                    ,id
                    ,id_creater
                    ,id_creater_group            
                    ,author
                ) values (
                    3
                    ,subjectcode        
                    ,questiontitle
                    ,'A'
                    ,2
                    ,concat('测试数据解题思路',round(rand()*100000))
                    ,2
                    ,questionid
                    ,userid
                    ,usergroupid            
                    ,personname
                );        
               insert into education_paper_2_question (id_paper,id_question) values (paperid,questionid);
            end while while_question3;                                                                          

        end while while_paper;
    end while while_subject;
end while while_month;
select 'paper done';
commit;


END;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for education_paper_log__int4test
-- ----------------------------
DROP PROCEDURE IF EXISTS `education_paper_log__int4test`;
DELIMITER ;;
CREATE PROCEDURE `education_paper_log__int4test`(in count_paperlog int,out out_state int, out out_msg varchar(200))
pro_main:BEGIN
/*
为了便于模拟测试做题日志,错题本 等效果

在此,随机插入N条做题日志


@version 201210
@author wei1224hf@gmail.com
*/
declare count_papers,count_questions int default 0;
declare papertitle varchar(200) default '';
declare questiontitle varchar(200) default '';
declare paperid,questionid,papaerlogid,questionlogid int default 0;
declare userid,usergroupid int default 0;
declare personname,_username varchar(200) default '0' ;
declare subjectcode,_group_code varchar(200) default '';

declare count_month,count_subject,count_paper int default 0;
declare paperdate char(10);
declare theanswer char(1);

declare randmonth,lograte,thelograte int default 0;
declare plogmax,plogmin int default 0;
declare _title,
        _subject_name,        
        _subject_code,        
        _teacher_name,
        _teacher_code varchar(200);   

declare _teacher_id int;
truncate table education_paper_log ;
truncate table education_question_log ;
truncate table education_question_log_wrongs ;

update basic_memory set extend1 = 0 where type = 2 and code in (
 'education_paper_log'
,'education_question_log'
,'education_question_log_wrongs'
);

START TRANSACTION; 
##再插入做题日志

#至少需要 38000 条测试数据才可以

    if count_paperlog is null then
        set count_paperlog = 2;    
    end if;    

    while_log:while count_paperlog > 0 do
        set count_paperlog = count_paperlog - 1;    
           
        ##随机的一个学生 
        select 
             basic_user.id
            ,basic_user.group_id         
            ,basic_user.person_name            
            ,basic_user.group_code            
            ,basic_user.username
        into 
             userid
            ,usergroupid         
            ,personname            
            ,_group_code            
            ,_username
        from basic_user 
            where type = 2 and id < 10 order by rand() limit 1;          
            
        set randmonth = floor(rand()*8) ;    
        if randmonth = 7 then 
            set paperdate = '2011-09-01';         
            set lograte = 60 + floor(rand()*10) - floor(rand()*10);
        end if;    
        if randmonth = 6 then 
            set paperdate = '2011-10-01';         
            set lograte = 70 + floor(rand()*9) - floor(rand()*9);
        end if;    
        if randmonth = 5 then 
            set paperdate = '2011-11-01';         
            set lograte = 80 + floor(rand()*8) - floor(rand()*8);
        end if;    
        if randmonth = 4 then 
            set paperdate = '2011-12-01';         
            set lograte = 85 + floor(rand()*7) - floor(rand()*7);
        end if;    
        if randmonth = 3 then 
            set paperdate = '2012-03-01';         
            set lograte = 87 + floor(rand()*6) - floor(rand()*6);
        end if;    
        if randmonth = 2 then 
            set paperdate = '2012-04-01';         
            set lograte = 89 + floor(rand()*5) - floor(rand()*5);
        end if;    
        if randmonth = 1 then 
            set paperdate = '2012-05-01';         
            set lograte = 91 + floor(rand()*4) - floor(rand()*4);
        end if;    
        if randmonth = 0 then 
            set paperdate = '2012-06-01';         
            set lograte = 95 + floor(rand()*3) - floor(rand()*3);
        end if;      
    
        #select randmonth,lograte;
        #随机的一张试卷,因为所有学生都有权限访问所有科目

        select 
             education_paper.id         
             ,(select max(education_paper_2_question.id_question) from education_paper_2_question where education_paper_2_question.id_paper = education_paper.id )             
            ,title
            ,subject_name
            ,subject_code    
            ,teacher_id
            ,teacher_name
            ,teacher_code        

        into 
             paperid         
             ,questionid             
            ,_title
            ,_subject_name
            ,_subject_code       
            ,_teacher_id
            ,_teacher_name
            ,_teacher_code              

        from education_paper where type = 1 order by rand() limit 1;   
        set papaerlogid = basic_memory__index('education_paper_log');    
        
        insert into education_paper_log (
             paper_id
            ,paper_title
            ,teacher_id
            ,teacher_name
            ,teacher_code            
            ,student_id
            ,student_name
            ,student_code
            ,subject_code
            ,subject_name
            ,type
            ,id
            ,id_creater
            ,id_creater_group
            ,code_creater_group
            ,status            
            ,remark
        ) values (
             paperid
            ,_title         
            ,_teacher_id
            ,_teacher_name
            ,_teacher_code     
            ,userid
            ,personname
            ,_username
            ,_subject_name
            ,_subject_code        
            ,'0'        
            ,papaerlogid        
            ,userid    
            ,usergroupid       
            ,_group_code    
            ,'1'            
            ,'education_paper_log__init4test'
        );        
    
        set count_questions = 103;
        while_questionlog:while count_questions > 0 do    
            set count_questions = count_questions - 1;        
            #select 'S';
            set questionlogid = basic_memory__index('education_question_log'); 
            set thelograte = floor(rand()*100);   
            set theanswer = 'A';     
            if thelograte > lograte then                
                set theanswer = 'B';
            end if;        
     
            insert into education_question_log (        
                 id                    
    
                ,id_creater       
                ,id_creater_group       
                ,time_created        
                ,id_paper            
                ,id_paper_log            
                ,id_question            
                ,myanswer      
                ,code_creater_group    
                ,remark
            ) values (        
                 questionlogid     
                 
                ,userid                 
                ,usergroupid            
                ,paperdate
                ,paperid            
                ,papaerlogid            
                ,questionid            
                ,theanswer       
                ,_group_code                           
                ,'education_paper_log__init4test'
            );
    
            set questionid = questionid - 1;
        end while while_questionlog;        
        commit;        
        call education_paper__mark(papaerlogid,@x1,@x2,@x3,@x4,@x5,@x6,@x7,@x8);
        START TRANSACTION;   
    
    end while while_log;
    commit;
    set out_state = 1;set out_msg = 'OK';

END;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for education_paper_log__submit
-- ----------------------------
DROP PROCEDURE IF EXISTS `education_paper_log__submit`;
DELIMITER ;;
CREATE PROCEDURE `education_paper_log__submit`()
pro_main:BEGIN
declare fig,x1,x2,x3,x4,x5,x6,x7,logid int default 0;
declare x8 varchar(200) default '';
declare cur_array cursor for     
    SELECT id from array_paperlog;
declare CONTINUE HANDLER FOR SQLSTATE '02000' SET fig = 1;  

drop TEMPORARY  table if exists array_paperlog;
create  TEMPORARY  table array_paperlog (
    id int    
) engine = memory ; 

insert into array_paperlog (select id from education_paper_log where mycent = 0) ;
    set fig = 0;    
    open cur_array; 
    fetch cur_array into logid;    
    while( fig = 0 ) do       
        call education_paper__submit(logid,@x1,@x2,@x3,@x4,@x5,@x6,@x7,@x8);    
    fetch cur_array into logid;    
    end while;
    #call education_paper__submit(plogmin,@x1,@x2,@x3,@x4,@x5,@x6,@x7,@x8);    


drop TEMPORARY  table if exists array_paperlog;
END;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for education_question__delete
-- ----------------------------
DROP PROCEDURE IF EXISTS `education_question__delete`;
DELIMITER ;;
CREATE PROCEDURE `education_question__delete`(IN in_id int)
BEGIN
	#Routine body goes here...
    delete from education_question where id = in_id;
END;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for education_question__export
-- ----------------------------
DROP PROCEDURE IF EXISTS `education_question__export`;
DELIMITER ;;
CREATE PROCEDURE `education_question__export`(
in in_paperid int,in in_excelid varchar(200),in in_sheetcount int,in in_sheetindex int
,out out_state int,out out_msg varchar(200),out out_excelid varchar(200),out out_sheetcount int,out out_sheetindex int)
pro_main:BEGIN
/*
题目导出
先将业务数据从 education_question 导入到 basic_excel 中

再从服务端将 basic_excel 中的数据,保存到服务端文件
客户再下载

在导出题目的时候,必定是导出一张试卷的题目
不会完全按照 题目数据 来导出


@version 201301
@author wei1224hf@gmail.com
*/	
declare _paperid,        
        _rownum 
    int;    

declare _excelid 
    varchar(200);

    select id_paper into _paperid from education_paper_2_question 
        where id_paper = in_paperid limit 1;        
    if _paperid is null then        
        set out_state = 0;set out_msg = 'wrong paperid';
        leave pro_main;        
    end if;

    set @rownum = 0;    
    if in_excelid is null then
        set in_excelid = concat('',floor(rand()*100000));           
    end if;   
    set out_excelid = in_excelid;     

    if in_sheetindex is null then
        set in_sheetindex = 0;        
    end if;        
    set out_sheetindex = in_sheetindex + 1;        
        
    if in_sheetcount is null then
        set in_sheetcount = 1;        
    end if;      
    set out_sheetcount = in_sheetcount;

    insert into basic_excel (    
        guid
        ,sheets
        ,sheetindex
        ,sheetname
        ,rowindex
        ,maxcolumn

        ,A
        ,B
        ,C
        ,D
        ,E
        ,F
        ,G
        ,H
        ,I
        ,J
        ,K
        ,L
        ,M
        ,N
        ,O
        ,P
        ,Q
        ,R
        ,S
        ,T        
        ,U

    ) values (         
        out_excelid 
        ,in_sheetcount        
        ,in_sheetindex     
        ,basic_memory__il8n('question','education_question',1)
        ,(@rownum:=@rownum+1)        
        ,21
        
        ,basic_memory__il8n('type2','education_question',1)
        ,basic_memory__il8n('title','education_question',1)
        ,basic_memory__il8n('answer','education_question',1)
        ,basic_memory__il8n('optionlength','education_question',1)
        ,basic_memory__il8n('option1','education_question',1)
        ,basic_memory__il8n('option2','education_question',1)
        ,basic_memory__il8n('option3','education_question',1)
        ,basic_memory__il8n('option4','education_question',1)
        ,basic_memory__il8n('option5','education_question',1)
        ,basic_memory__il8n('option6','education_question',1)
        ,basic_memory__il8n('option7','education_question',1)
        ,basic_memory__il8n('description','education_question',1)
        ,basic_memory__il8n('cent','education_question',1)
        ,basic_memory__il8n('layout','education_question',1)
        ,basic_memory__il8n('id_parent','education_question',1)
        ,basic_memory__il8n('path_listen','education_question',1)
        ,basic_memory__il8n('path_image','education_question',1)
        ,basic_memory__il8n('subject_code','education_question',1)
        ,basic_memory__il8n('ids_level_knowledge','education_question',1)
        ,basic_memory__il8n('type','education_question',1)        
        ,basic_memory__il8n('id','normal',1)
    );
       
    insert into basic_excel (    
        guid
        ,sheets
        ,sheetindex
        ,sheetname
        ,rowindex
        ,maxcolumn

        ,A
        ,B
        ,C
        ,D
        ,E
        ,F
        ,G
        ,H
        ,I
        ,J
        ,K
        ,L
        ,M
        ,N
        ,O
        ,P
        ,Q
        ,R
        ,S
        ,T        
        ,U

    ) select         
        out_excelid
        ,in_sheetcount        
        ,in_sheetindex   
        ,basic_memory__il8n('question','education_question',1)
        ,(@rownum:=@rownum+1)        
        ,21
        
        ,type2
        ,title
        ,answer
        ,optionlength
        ,option1
        ,option2
        ,option3
        ,option4
        ,option5
        ,option6
        ,option7
        ,description
        ,cent
        ,basic_memory__il8n(layout,'education_question__layout',3)
        ,id_parent
        ,path_listen
        ,path_image
        ,subject_code
        ,ids_level_knowledge
        ,basic_memory__il8n(concat('',type),'education_question__type',3)    
        ,id                                                                               
     from education_question where id in (
          select id_question from education_paper_2_question 
          where id_paper = in_paperid) ;

END;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for education_question__import
-- ----------------------------
DROP PROCEDURE IF EXISTS `education_question__import`;
DELIMITER ;;
CREATE PROCEDURE `education_question__import`(
IN in_guid char(36),
in in_pid int,
OUT out_state int,
OUT out_msg varchar(200),
OUT out_ids varchar(2000),
OUT out_cent int,
out out_count int)
pro_main:BEGIN
declare fig int;         
declare rowindex__ int;
declare A_,B_,C_,D_,E_,F_,G_,H_,I_,J_,K_,L_,M_,N_,O_,P_,Q_,R_,S_,T_,U_,V_ varchar(200);    
declare A__,B__,C__,D__,E__,F__,G__,H__,I__,J__,K__,L__,M__,N__,O__,P__,Q__,R__,S__,T__,U__,V__ varchar(2000);  
declare columnsimport varchar(400) default ',type,type2,title,answer,optionlength,option1,option2,option3,option4,option5,option6,option7,description,cent,layout,id_parent,path_listen,path_image,subject_code,ids_level_knowledge,id_parent,remark';       
declare code_ varchar(2);      
declare row1_ varchar(200);    
declare id_creater_ int default 0;    
declare id_creater_group_ int default 0;           
declare sql_insert varchar(8000);
declare cur_array cursor for     
    SELECT code,row1 from array_question;   
declare cur cursor for 
    SELECT A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,rowindex from basic_excel where sheetname = basic_memory__il8n('question','education_question',1)         
        and guid = in_guid            
        and rowindex > 1
        order by rowindex;     
            
#以下变量用于游标
declare CONTINUE HANDLER FOR SQLSTATE '02000' SET fig = 1;       
    
    if in_guid is null then        
        set out_state = 0;        
        set out_msg = 'null guid';
        leave pro_main;
    end if;
    
    drop TEMPORARY  table if exists array_question;
    create  TEMPORARY  table array_question (
        code varchar(2)        
        ,row1 varchar(200)   
        ,row2 varchar(200)        
    ) engine = memory ;         
    
    select A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,id_creater into A_,B_,C_,D_,E_,F_,G_,H_,I_,J_,K_,L_,M_,N_,O_,P_,Q_,R_,S_,T_,U_,V_,id_creater_ from basic_excel 
        where guid = in_guid 
        and rowindex = 1 
        and sheetname = basic_memory__il8n('question','education_question',1);       
    if A_ is null then          
        set out_state = 0;        
        set out_msg = 'wrong guid';
        leave pro_main;
    end if;   
    
    select id_creater,(select group_id from basic_user where id = basic_excel.id_creater ) into id_creater_,id_creater_group_  from basic_excel where guid = in_guid limit 1;
    if ( id_creater_ is null ) or  (id_creater_group_ is null ) then   
        set out_state = 0;
        set out_msg = 'id_user wrong';    
        leave pro_main;    
    end if;

    set @sql_keys = '';        
    set @sql_keys_excel = '';    
    set @sql_values = '';
    insert into array_question values 
        ('A', basic_memory__il8n( A_,'education_question', 2) ,A_ ),        
        ('B', basic_memory__il8n( B_,'education_question', 2) ,B_ ),        
        ('C', basic_memory__il8n( C_,'education_question', 2) ,C_ ),        
        ('D', basic_memory__il8n( D_,'education_question', 2) ,D_ ),        
        ('E', basic_memory__il8n( E_,'education_question', 2) ,E_ ),        
        ('F', basic_memory__il8n( F_,'education_question', 2) ,F_ ),        
        ('G', basic_memory__il8n( G_,'education_question', 2) ,G_ ),        
        ('H', basic_memory__il8n( H_,'education_question', 2) ,H_ ),              
        ('I', basic_memory__il8n( I_,'education_question', 2) ,I_ ),              
        ('J', basic_memory__il8n( J_,'education_question', 2) ,J_ ),              
        ('K', basic_memory__il8n( K_,'education_question', 2) ,K_ ),              
        ('L', basic_memory__il8n( L_,'education_question', 2) ,L_ ),              
        ('M', basic_memory__il8n( M_,'education_question', 2) ,M_ ),
		('N', basic_memory__il8n( N_,'education_question', 2) ,N_ ),
		('O', basic_memory__il8n( O_,'education_question', 2) ,O_ ),
		('P', basic_memory__il8n( P_,'education_question', 2) ,P_ ),
		('Q', basic_memory__il8n( Q_,'education_question', 2) ,Q_ ),
		('R', basic_memory__il8n( R_,'education_question', 2) ,R_ ),
		('S', basic_memory__il8n( S_,'education_question', 2) ,S_ ),
		('T', basic_memory__il8n( T_,'education_question', 2) ,T_ ),
		('U', basic_memory__il8n( U_,'education_question', 2) ,U_ ),
		('V', basic_memory__il8n( V_,'education_question', 2) ,V_ )
	;
    #select * from array_question;
		
    set fig = 0;    
    open cur_array;        
    fetch cur_array into code_,row1_;        
    set @columnIndex = 1;
    while( fig = 0 ) do           
        if row1_ is null then        
            set out_msg = concat(out_msg,code_,'1','null;');                
        elseif FIND_IN_SET(row1_,columnsimport) = 0 then 
            #select 4;    
            #select concat(row1_,columnsimport);            
            #select FIND_IN_SET(row1_,columnsimport);
            set out_msg = concat(out_msg,code_,'1','cant;');  
        else  
            #select code_,row1_;     
            set @columnIndex = @columnIndex + 1; 
            set @sql_keys = concat(@sql_keys,row1_,\",\");            
            set @sql_keys_excel = concat(@sql_keys_excel,code_,\",\");            

            if row1_ = 'type' then        
                set @columnIndexType = @columnIndex;                
            end if;            
            if row1_ = 'subject_code' then        
                set @columnIndexSubject = @columnIndex;                
            end if;            
            if row1_ = 'layout' then        
                set @columnIndexLayout = @columnIndex;                
            end if;            
            if row1_ = 'cent' then        
                set @columnIndexCent = @columnIndex;                
            end if;            
            if row1_ = 'optionlength' then        
                set @columnIndexOptionlength = @columnIndex;                
            end if;            
            if row1_ = 'answer' then        
                set @columnIndexAnswer = @columnIndex;                
            end if;
        end if;         

    fetch cur_array into code_,row1_;    
    end while;
    close cur_array;       
    #select @sql_keys,@sql_keys_excel;    
    set @columncount = basic_stringcount(@sql_keys_excel,\",\");    

    set @sql_insert = concat(\"insert into education_question (id,id_creater,id_creater_group\",\",\",SUBSTRING( @sql_keys , 1 , LENGTH(@sql_keys)-1 ),\") values \");       

    set out_ids = '';   
    set out_count = 0; 
    set out_cent = 0;    
    select max(rowindex) into @maxrow from basic_excel where guid = in_guid and sheetname = basic_memory__il8n('question','education_question',1);             
    set fig = 0;    
    open cur;            
    fetch cur into A__,B__,C__,D__,E__,F__,G__,H__,I__,J__,K__,L__,M__,N__,O__,P__,Q__,R__,S__,T__,U__,V__,rowindex__;
    cur_while: while (fig = 0) do  
        set @sql_values = '';  
        set @p = 0;         
        set @spotpos = 1;        
        set @tempvalue = '';                
        set @questionType = null;
        inerLoop: LOOP
            SET @p = @p + 1;  
            set @spotpos = LOCATE(',', @sql_keys_excel,@spotpos+1);
            set @alphaindex = MID(@sql_keys_excel,@spotpos-1,1);            
            
            if @alphaindex = 'A' then set @tempvalue = A__; end if; 
            if @alphaindex = 'B' then set @tempvalue = B__; end if; 
            if @alphaindex = 'C' then set @tempvalue = C__; end if; 
            if @alphaindex = 'D' then set @tempvalue = D__; end if; 
            if @alphaindex = 'E' then set @tempvalue = E__; end if; 
            if @alphaindex = 'F' then set @tempvalue = F__; end if; 
            if @alphaindex = 'G' then set @tempvalue = G__; end if; 
            if @alphaindex = 'H' then set @tempvalue = H__; end if; 
            if @alphaindex = 'I' then set @tempvalue = I__; end if; 
            if @alphaindex = 'J' then set @tempvalue = J__; end if; 
            if @alphaindex = 'K' then set @tempvalue = K__; end if; 
            if @alphaindex = 'L' then set @tempvalue = L__; end if; 
            if @alphaindex = 'M' then set @tempvalue = M__; end if; 
            if @alphaindex = 'N' then set @tempvalue = N__; end if; 
            if @alphaindex = 'O' then set @tempvalue = O__; end if; 
            if @alphaindex = 'P' then set @tempvalue = P__; end if; 
            if @alphaindex = 'Q' then set @tempvalue = Q__; end if; 
            if @alphaindex = 'R' then set @tempvalue = R__; end if; 
            if @alphaindex = 'S' then set @tempvalue = S__; end if; 
            if @alphaindex = 'T' then set @tempvalue = T__; end if; 
            if @alphaindex = 'U' then set @tempvalue = U__; end if; 
            if @alphaindex = 'V' then set @tempvalue = V__; end if; 
                  
            if @columnIndexType = @p+1 then             
                #select @tempvalue,@columnIndexType;                
                set @temp = null;
                select code into @temp from basic_memory where extend5 = 'education_question__type' and extend4 = @tempvalue;                
                #select @temp;
                if @temp is null then 
                    set out_state = 0;
                    set out_msg = concat('wrong type ',rowindex__);                    
                    
                    leave pro_main;
                end if;                 
                set @tempvalue = @temp;                
                set @questionType = @temp;
            elseif @columnIndexSubject = @p+1 then            
                set @temp = null;
                select code into @temp from education_subject where code = @tempvalue;                
                #select @temp,@tempvalue;leave pro_main;
                if @temp is null then 
                    set out_state = 0;
                    set out_msg = 'wrong subject';                    
                    
                    leave pro_main;
                end if;                 
            elseif @columnIndexLayout = @p+1 then            
                set @temp = null;
                select code into @temp from basic_memory where extend5 = 'education_question__layout' and extend4 = @tempvalue;            
                #select @temp;
                if @temp is null then 
                    set out_state = 0;
                    set out_msg = 'wrong layout';                    
                    
                    leave pro_main;
                end if;                 
            elseif @columnIndexCent = @p+1 then            
                select ( @tempvalue REGEXP   '^[0-9]+$' ) into @temp ;
                if @temp = 0 then 
                    set out_state = 0;
                    set out_msg = concat('wrong cent ',rowindex__);                    
                   
                    leave pro_main;
                end if;          
                set out_cent = out_cent + @tempvalue;
            elseif @columnIndexOptionlength = @p+1 then            
                select ( @tempvalue REGEXP   '^[0-7]$' ) into @temp ;
                if @temp = 0 then 
                    set out_state = 0;
                    set out_msg = concat('wrong length ',rowindex__);                    
                    
                    leave pro_main;
                end if;                
            elseif @columnIndexAnswer = @p+1 then              
                set @quesTypeReg = '';
                if @questionType = 1 then                                
                   set @quesTypeReg = '^[ABCDEFG]$';
                elseif @questionType = 2 then     
                   set @quesTypeReg = '^(A,)?(B,)?(C,)?(D,)?(E,)?(F,)?[A-G]{1}$';     
                   #select       @quesTypeReg;
                elseif @questionType = 3 then                      
                   set @quesTypeReg = '^[AB]$';
                end if;     
                if @questionType < 4 then       
                    select ( @tempvalue REGEXP @quesTypeReg ) into @temp ;
                    if @temp = 0 then 
                        set out_state = 0;
                        set out_msg = concat('wrong answer ',rowindex__);
                        leave pro_main;
                    end if;                    
                end if;
            end if;    
            set @sql_values = concat(@sql_values,\",'\",@tempvalue,\"'\");              
            
            IF @p < @columncount THEN
               ITERATE inerLoop;
            END IF;
            LEAVE inerLoop;
        END LOOP inerLoop;        
        #leave pro_main;        
        #select @sql_values;        
               
        set @id_education_question = basic_memory__index('education_question');     
        insert into education_paper_2_question(id_paper,id_question) values (in_pid,@id_education_question);   
        set out_ids = concat(out_ids,\",\", @id_education_question);
        set @sql_values = concat(@id_education_question,\",\",id_creater_,\",\",id_creater_group_,@sql_values);  
        #select @sql_values;                        
        if rowindex__ = @maxrow then                 
            set @sql_insert = concat(@sql_insert,\"(\",@sql_values,\") \");             
        else
            set @sql_insert = concat(@sql_insert,\"(\",@sql_values,\") ,\");             
        end if;        
        set out_count = out_count + 1;
        #select sql_insert;
        #leave pro_main;
    fetch cur into A__,B__,C__,D__,E__,F__,G__,H__,I__,J__,K__,L__,M__,N__,O__,P__,Q__,R__,S__,T__,U__,V__,rowindex__;
    end while cur_while;
    close cur;        

    set out_ids = SUBSTRING( out_ids , 2  ) ;
    #select @sql_insert;    leave pro_main;
    PREPARE stmt FROM @sql_insert;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;  
    set out_state = 1;
    set out_msg = 'ok';  
    drop TEMPORARY  table if exists array_question;
    delete from basic_excel where guid = in_guid and sheetname = basic_memory__il8n('question','education_question',1) ;
END;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for education_question__setRelation
-- ----------------------------
DROP PROCEDURE IF EXISTS `education_question__setRelation`;
DELIMITER ;;
CREATE PROCEDURE `education_question__setRelation`(id_paper_input int)
BEGIN
/*
将一张试卷从EXCEL文件导入到数据库的时候,
如果这张试卷中有 编号 这一列的话,那么导入的时候比较麻烦.
因为数据库中的 编号 这一列,是自动生成的. 
也就是说,EXCEL文件中的 编号 一列,不能算数
在导入的过程中,会先将试卷中 编号 一列,存储到 comment_ywrong_1 中,将 上级编号 存储到 comment_ywrong_2 中



然后再用这个存储过程,将数据库表中的 id_parent 更新
*/
    declare fig int ;
    declare id_parent_ int;
    declare id_ int;    
    declare cent_ int;    
    declare type_ int;    
    declare totalCent int default 0;    
    declare count_questions_ int default 0;
    declare cur cursor for 
        select comment_ywrong_2,id,cent,type from education_question where id_paper=id_paper_input  order by id   ;
    declare continue handler for not found set fig = 1;
   #将这个ID所对应的所有题目原先的 编号 上级编号 存储到一张临时表中,临时表会在这个存储过程结束之后直接清掉





    create TEMPORARY  table tmp  as
       select id,id_parent,comment_ywrong_2,comment_ywrong_1 from education_question where id_paper = id_paper_input;
    set id_parent_ = 0;
    set id_ = 0;

    open cur;
    repeat
    fetch cur into id_parent_,id_,cent_,type_ ;
        if id_parent_ != 0  then
            update education_question set id_parent = (select id from tmp where tmp.comment_ywrong_1 = id_parent_ limit 1) where id = id_;
        end if;        
        if (type_ = 1 || type_ = 2 || type_ = 3 || ( type_ = 4 and cent_ <> 0 ) || type_ = 6 ) then                
            set count_questions_ = count_questions_ + 1;            
            set totalCent = totalCent + cent_ ;
        end if;
    until fig = 1 end repeat;
    close cur;
    update education_question set 
        comment_ywrong_2 = 0 , 
        comment_ywrong_1 = 0 
            where id_paper = id_paper_input ;            
                
    update education_paper set     
        cent = totalCent,        
        count_questions = count_questions_        
            where id = id_paper_input;

END;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for education_question_log_wrongs__submit
-- ----------------------------
DROP PROCEDURE IF EXISTS `education_question_log_wrongs__submit`;
DELIMITER ;;
CREATE PROCEDURE `education_question_log_wrongs__submit`(
IN in_id_paper_log int
,OUT out_state int
,OUT out_msg VARCHAR(200)
)
pro_papermark:BEGIN
/*
错题本批改


@version 201209
@author wei1224hf@gmail.com
*/
declare _myanswer,_answer,_count_wrong,_count_right,_id_question,_id_creater,_id,fig
    int default 0;      


#定义游标
declare cur cursor for     
    SELECT
    education_question_log.myanswer,
    education_question.answer,
    education_question_log_wrongs.count_wrong,
    education_question_log_wrongs.count_right,
    education_question_log.id_question,      
    education_question_log.id_creater,
    education_question_log_wrongs.id
    FROM
    education_question_log
    Left Join education_question ON education_question_log.id_question = education_question.id
    Left Join education_question_log_wrongs ON education_question_log.id_question = education_question_log_wrongs.question_id
    WHERE
    education_question_log.id_paper_log =  in_id_paper_log;
          

#以下变量用于游标
declare CONTINUE HANDLER FOR SQLSTATE '02000' SET fig = 1;
SET fig=0;
#DECLARE CONTINUE HANDLER FOR 1062 SET out_msg='wrong';         

    START TRANSACTION; 
    open cur;
    fetch cur into _myanswer,_answer,_count_wrong,_count_right,_id_question,_id_creater,_id;
    WHILE ( fig = 0 ) DO            
        if _myanswer = _answer then
            if _count_right = 4 then                         
                delete from education_question_log_wrongs where id = _id;
            else
                update education_question_log_wrongs set 
                    count_right = count_right + 1 ,                                
                    time_lastupdated = now()
                    where id = _id;                
            end if;
        else    
            update education_question_log_wrongs set 
                count_wrong = count_wrong + 1 ,                                
                time_lastupdated = now()
                where id = _id;
        end if;        

    fetch cur into _myanswer,_answer,_count_wrong,_count_right,_id_question,_id_creater,_id;
    END WHILE;
    close cur;                                              

    SELECT
    education_question.answer,
    education_question.description
    FROM
    education_question_log
    Left Join education_question ON education_question_log.id_question = education_question.id
    WHERE
    education_question_log.id_paper_log =  in_id_paper_log
    order by education_question.id;


    delete from education_question_log where id_paper_log = in_id_paper_log;

    set out_state = 1;                
    set out_msg = 'done';            
    commit;
END;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for education_student__import
-- ----------------------------
DROP PROCEDURE IF EXISTS `education_student__import`;
DELIMITER ;;
CREATE PROCEDURE `education_student__import`(
IN in_guid char(36)
,OUT out_state int
,OUT out_msg varchar(200)
,OUT out_ids varchar(2000))
pro_main:BEGIN
/*
系统前端将一个EXCEL文件上传到系统服务端
服务端先将这个EXCEL文件的内容,不管内容对错与格式,直接写入到 basic_excel 表,
并使用服务端产生的 guid 标识数据来源
然后,数据库端,使用此存储过程,判断EXCEL文件中数据的正确性

如果发现EXCEL文件中任何一处数据有错误,将取消这次导入,并且删除 basic_excel 表内容

如果没有错误,就会在最后将数据插入,并返回在批导入的时候,产生的学生编号


每增加一个学生,就需要在 学生表 个人信息表 用户表 用户-用户组 中插入一条记录


version: 201210 
author: wei1224hf@gmail.com  
prerequisites: basic_memory__init,basic_memory.il8n(),basic_group[data]
server used: education_student.import()
involve: basic_user,basic_group,education_student,basic_person
*/

#用以标识游标
declare fig int; 
            
#用以判断EXCEL数据准确性,因为学生的信息非常多,所以默认开设了很多个列    
#学生信息中,包含学生档案跟个人信息档案.     
#如果EXCEL文件中的列多于 AN , 将导入失败

declare A_,B_,C_,D_,E_,F_,G_,H_,I_,J_,K_,L_,M_,N_,O_,P_,Q_,R_,S_,T_,U_,V_,W_,X_,Y_,Z_,AA_,AB_,AC_,AD_,AE_,AF_,AG_,AH_,AI_,AJ_,AK_,AL_,AM_,AN_ varchar(200);    
declare A__,B__,C__,D__,E__,F__,G__,H__,I__,J__,K__,L__,M__,N__,O__,P__,Q__,R__,S__,T__,U__,V__,W__,X__,Y__,Z__,AA__,AB__,AC__,AD__,AE__,AF__,AG__,AH__,AI__,AJ__,AK__,AL__,AM__,AN__ varchar(2000);      

#在 basic_excel 中,有一列是 id_creater 用于标识这个EXCEL是谁上传的    
declare id_creater_,id_creater_group_,rowindex__ int default 0;        
declare code_creater_group_ varchar(200) default '0';
     
#用于临时内存表的数据操作
declare code_,row1_,row2_ varchar(200);          
declare cur_array cursor for     
    SELECT code,row1,row2 from array_student;   
                  
#核心游标,EXCEL文件中,第二行是表头,第三行才是主业务数据,
#excel 第一行是用于标识 学生档案 跟 个人信息档案 分界线的
declare cur_student cursor for
    select A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z,AA,AB,AC,AD,AE,AF,AG,AH,AI,AJ,AK,AL,AM,AN,rowindex
    from basic_excel 
    where guid = in_guid 
    and rowindex > 2
    and sheetname = basic_memory__il8n('education_student','education_student',1)
    order by rowindex;     
declare CONTINUE HANDLER FOR SQLSTATE '02000' SET fig = 1;     

    #得到EXCEL中,业务数据的总行数                    
    select max(rowindex) into @maxrow from basic_excel where guid = in_guid and sheetname = basic_memory__il8n('education_student','education_student',1);             

    if in_guid is null then        
        set out_state = 0;        
        set out_msg = 'null guid';        
        insert into basic_log (type,username,msg) values (1,'system',out_msg );
        leave pro_main;
    end if;           
        
    #处理第一行,得到 学生档案 跟 个人信息档案 的分界列
    select A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z,AA,AB,AC,AD,AE,AF,AG,AH,AI,AJ,AK,AL,AM,AN,id_creater into 
           A_,B_,C_,D_,E_,F_,G_,H_,I_,J_,K_,L_,M_,N_,O_,P_,Q_,R_,S_,T_,U_,V_,W_,X_,Y_,Z_,AA_,AB_,AC_,AD_,AE_,AF_,AG_,AH_,AI_,AJ_,AK_,AL_,AM_,AN_,id_creater_ 
           from basic_excel 
        where guid = in_guid 
        and rowindex = 1 
        and sheetname = basic_memory__il8n('education_student','education_student',1);     
    if ((id_creater_ is null) or (id_creater_ = 0 )) then    
        set out_state = 0;        
        set out_msg = basic_memory__il8n('guidWrong','basic_excel',1);        
        insert into basic_log (type,username,msg) values (1,'system',out_msg);
        leave pro_main;
    end if;   
    
    if id_creater_ = 1 then            
        #是超级管理员 admin 导入的数据        
        set id_creater_group_ = 1;        
        set code_creater_group_ = '10';
    else   
        select group_id,group_code into id_creater_group_,code_creater_group_ from basic_user where id =  id_creater_;
    end if;                     

    #使用临时内存表来模拟数组
    drop TEMPORARY  table if exists array_student;
    create  TEMPORARY  table array_student (
        code varchar(2)        
        ,row1 varchar(200)   
        ,row2 varchar(200)        
    ) engine = memory ;       

    insert into array_student values 
        ('A', basic_memory__il8n( A_,'education_student', 2) ,A_ ),
        ('B', basic_memory__il8n( B_,'education_student', 2) ,B_ ),
        ('C', basic_memory__il8n( C_,'education_student', 2) ,C_ ),
        ('D', basic_memory__il8n( D_,'education_student', 2) ,D_ ),
        ('E', basic_memory__il8n( E_,'education_student', 2) ,E_ ),
        ('F', basic_memory__il8n( F_,'education_student', 2) ,F_ ),
        ('G', basic_memory__il8n( G_,'education_student', 2) ,G_ ),
        ('H', basic_memory__il8n( H_,'education_student', 2) ,H_ ),
        ('I', basic_memory__il8n( I_,'education_student', 2) ,I_ ),
        ('J', basic_memory__il8n( J_,'education_student', 2) ,J_ ),
        ('K', basic_memory__il8n( K_,'education_student', 2) ,K_ ),
        ('L', basic_memory__il8n( L_,'education_student', 2) ,L_ ),
        ('M', basic_memory__il8n( M_,'education_student', 2) ,M_ ),
        ('N', basic_memory__il8n( N_,'education_student', 2) ,N_ ),
        ('O', basic_memory__il8n( O_,'education_student', 2) ,O_ ),
        ('P', basic_memory__il8n( P_,'education_student', 2) ,P_ ),
        ('Q', basic_memory__il8n( Q_,'education_student', 2) ,Q_ ),
        ('R', basic_memory__il8n( R_,'education_student', 2) ,R_ ),
        ('S', basic_memory__il8n( S_,'education_student', 2) ,S_ ),
        ('T', basic_memory__il8n( T_,'education_student', 2) ,T_ ),
        ('U', basic_memory__il8n( U_,'education_student', 2) ,U_ ),
        ('V', basic_memory__il8n( V_,'education_student', 2) ,V_ ),
        ('W', basic_memory__il8n( W_,'education_student', 2) ,W_ ),
        ('X', basic_memory__il8n( X_,'education_student', 2) ,X_ ),
        ('Y', basic_memory__il8n( Y_,'education_student', 2) ,Y_ ),
        ('Z', basic_memory__il8n( Z_,'education_student', 2) ,Z_ ),
        ('AA', basic_memory__il8n( AA_,'education_student', 2) ,AA_ ),
        ('AB', basic_memory__il8n( AB_,'education_student', 2) ,AB_ ),
        ('AC', basic_memory__il8n( AC_,'education_student', 2) ,AC_ ),
        ('AD', basic_memory__il8n( AD_,'education_student', 2) ,AD_ ),
        ('AE', basic_memory__il8n( AE_,'education_student', 2) ,AE_ ),
        ('AF', basic_memory__il8n( AF_,'education_student', 2) ,AF_ ),
        ('AG', basic_memory__il8n( AG_,'education_student', 2) ,AG_ ),
        ('AH', basic_memory__il8n( AH_,'education_student', 2) ,AH_ ),
        ('AI', basic_memory__il8n( AI_,'education_student', 2) ,AI_ ),
        ('AJ', basic_memory__il8n( AJ_,'education_student', 2) ,AJ_ ),
        ('AK', basic_memory__il8n( AK_,'education_student', 2) ,AK_ ),
        ('AL', basic_memory__il8n( AL_,'education_student', 2) ,AL_ ),
        ('AM', basic_memory__il8n( AM_,'education_student', 2) ,AM_ ),
        ('AN', basic_memory__il8n( AN_,'education_student', 2) ,AN_ )        
    ;    
    #select * from array_student;   
                  
    #检查表头,也就是列,看看列是否丢失或多余,就是检查格式

    set @divideColumn = '';    
    set @divideColumnIndex = 0;
    set @temp_index = 0;
    set fig = 0;    
    open cur_array;        
    fetch cur_array into code_,row1_,row2_;        
    set @columnIndex = 1;
    divide:while( fig = 0 ) do       
        set @temp_index = @temp_index + 1;
        #select code_;        
        if row1_ = 'doc_person' then
            #select code_;   
            set @divideColumn = code_;            
            if LENGTH(@divideColumn) = 1 then                        
                set @divideColumn = concat('0',@divideColumn);
            end if;
            leave divide;
        end if;
    fetch cur_array into code_,row1_,row2_;        
    end while;
    close cur_array;    
    #select @temp_index,@divideColumn;   
    #如果找不到分界列,就报错 
    if @divideColumn = '' then            
        set out_state = 2;        
        set out_msg = basic_memory__il8n('excelWrong_noDivideColumn','education_student',1);      
        
        leave pro_main;
    end if;

    #分析第二行,表头行,将每一个表头都插入到临时内存表
    delete from array_student;
    select A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z,AA,AB,AC,AD,AE,AF,AG,AH,AI,AJ,AK,AL,AM,AN,id_creater into 
           A_,B_,C_,D_,E_,F_,G_,H_,I_,J_,K_,L_,M_,N_,O_,P_,Q_,R_,S_,T_,U_,V_,W_,X_,Y_,Z_,AA_,AB_,AC_,AD_,AE_,AF_,AG_,AH_,AI_,AJ_,AK_,AL_,AM_,AN_,id_creater_ 
           from basic_excel 
        where guid = in_guid 
        and rowindex = 2 
        and sheetname = basic_memory__il8n('education_student','education_student',1);                   

    insert into array_student values 
        ('A', basic_memory__il8n( A_,'education_student', 2) ,A_ ),
        ('B', basic_memory__il8n( B_,'education_student', 2) ,B_ ),
        ('C', basic_memory__il8n( C_,'education_student', 2) ,C_ ),
        ('D', basic_memory__il8n( D_,'education_student', 2) ,D_ ),
        ('E', basic_memory__il8n( E_,'education_student', 2) ,E_ ),
        ('F', basic_memory__il8n( F_,'education_student', 2) ,F_ ),
        ('G', basic_memory__il8n( G_,'education_student', 2) ,G_ ),
        ('H', basic_memory__il8n( H_,'education_student', 2) ,H_ ),
        ('I', basic_memory__il8n( I_,'education_student', 2) ,I_ ),
        ('J', basic_memory__il8n( J_,'education_student', 2) ,J_ ),
        ('K', basic_memory__il8n( K_,'education_student', 2) ,K_ ),
        ('L', basic_memory__il8n( L_,'education_student', 2) ,L_ ),
        ('M', basic_memory__il8n( M_,'education_student', 2) ,M_ ),
        ('N', basic_memory__il8n( N_,'education_student', 2) ,N_ ),
        ('O', basic_memory__il8n( O_,'education_student', 2) ,O_ ),
        ('P', basic_memory__il8n( P_,'education_student', 2) ,P_ ),
        ('Q', basic_memory__il8n( Q_,'education_student', 2) ,Q_ ),
        ('R', basic_memory__il8n( R_,'education_student', 2) ,R_ ),
        ('S', basic_memory__il8n( S_,'education_student', 2) ,S_ ),
        ('T', basic_memory__il8n( T_,'education_student', 2) ,T_ ),
        ('U', basic_memory__il8n( U_,'education_student', 2) ,U_ ),
        ('V', basic_memory__il8n( V_,'education_student', 2) ,V_ ),
        ('W', basic_memory__il8n( W_,'education_student', 2) ,W_ ),
        ('X', basic_memory__il8n( X_,'education_student', 2) ,X_ ),
        ('Y', basic_memory__il8n( Y_,'education_student', 2) ,Y_ ),
        ('Z', basic_memory__il8n( Z_,'education_student', 2) ,Z_ ),
        ('AA', basic_memory__il8n( AA_,'education_student', 2) ,AA_ ),
        ('AB', basic_memory__il8n( AB_,'education_student', 2) ,AB_ ),
        ('AC', basic_memory__il8n( AC_,'education_student', 2) ,AC_ ),
        ('AD', basic_memory__il8n( AD_,'education_student', 2) ,AD_ ),
        ('AE', basic_memory__il8n( AE_,'education_student', 2) ,AE_ ),
        ('AF', basic_memory__il8n( AF_,'education_student', 2) ,AF_ ),
        ('AG', basic_memory__il8n( AG_,'education_student', 2) ,AG_ ),
        ('AH', basic_memory__il8n( AH_,'education_student', 2) ,AH_ ),
        ('AI', basic_memory__il8n( AI_,'education_student', 2) ,AI_ ),
        ('AJ', basic_memory__il8n( AJ_,'education_student', 2) ,AJ_ ),
        ('AK', basic_memory__il8n( AK_,'education_student', 2) ,AK_ ),
        ('AL', basic_memory__il8n( AL_,'education_student', 2) ,AL_ ),
        ('AM', basic_memory__il8n( AM_,'education_student', 2) ,AM_ ),
        ('AN', basic_memory__il8n( AN_,'education_student', 2) ,AN_ )        
    ;    
    #select * from array_student;   

    #逐一判断每一个表头的正确性

    set @student_sql_keys = '';    
    set @student_excel_columns = '';
    #如果表头中,有包含出此之外的表头,就是错误的EXCEL    
    set @student_sufficient = 'code,class_code,scorerank,scorerank2,scorerank3,specialty,hobby,characters,growth,health,healthdefect,mentalhealth,attitude_learn,attitude_teacher,attitude_life,attitude_classmate,attitude_oppositesex,intelligence,class_manager,junior_school,junior_graduated,junior_scores,junior_rank,parents_name,parents_cellphone';
    set @person_sql_keys = '';    
    set @person_excel_columns = '';        
    set @person_sufficient = 'name,birthday,cardType,idcard,photo,height,nationality,gender,nation,politically,address_birth,address,cellphone,email,qq';

    set fig = 0;    
    open cur_array;        
    fetch cur_array into code_,row1_,row2_;        
    set @columnIndex = 1;
    divide:while( fig = 0 ) do      
        set @code__ = code_;
        if length(code_) = 1 then
            set @code__ = concat('0',code_);            
        end if;               

        if @code__ < @divideColumn then        
            #select 'xxxx',code_,@divideColumn;      
            #leave pro_main;              
            if row1_ is null then     
                #如果这个单元格是空的,就报错   
                set out_msg = concat(row2_,\" \",code_,\"2 \",basic_memory__il8n('columnWrong','basic_excel',1));            
                set out_state = 21;   
                insert into basic_log (type,username,msg) values (1,'system',out_msg);
                leave pro_main;
            elseif FIND_IN_SET(row1_,@student_sufficient) = 0 then             
                set out_msg = concat(row2_,\" \",code_,\"2 \",basic_memory__il8n('columnWrong','basic_excel',1));                   
                set out_state = 22;    
                
                leave pro_main;                
            else            
                set @student_sql_keys = concat(@student_sql_keys,row1_,\",\");       
                set @student_excel_columns = concat(@student_excel_columns,code_,\",\");         
            end if;
        else         
            if row1_ is null then        
                set out_msg = concat(row2_,\" \",code_,\"2 \",basic_memory__il8n('columnWrong','basic_excel',1));         
                set out_state = 23;     
                
                leave pro_main;
            elseif FIND_IN_SET(row1_,@person_sufficient) = 0 then             
                set out_msg = concat(row2_,\" \",code_,\"2 \",basic_memory__il8n('columnWrong','basic_excel',1));     
                set out_state = 24;             
                
                leave pro_main;                
            else            
                set @person_sql_keys = concat(@person_sql_keys,row1_,\",\");    
                set @person_excel_columns = concat(@person_excel_columns,code_,\",\");                         
            end if;             
        end if;
    fetch cur_array into code_,row1_,row2_;        
    end while;
    close cur_array; 
    
    #检查一些必须要的列是否存在  
    if ( FIND_IN_SET(\"name\",@person_sql_keys) = 0 ) or        
       ( FIND_IN_SET(\"birthday\",@person_sql_keys) = 0 ) or           

       ( FIND_IN_SET(\"code\",@student_sql_keys) = 0 ) or       
       ( FIND_IN_SET(\"class_code\",@student_sql_keys) = 0 ) or       
       ( FIND_IN_SET(\"parents_name\",@student_sql_keys) = 0 ) or       
       ( FIND_IN_SET(\"parents_cellphone\",@student_sql_keys) = 0 ) 
        then     
        set out_msg = concat(row2_,\" \",code_,\"2 \",basic_memory__il8n('columnMissing','basic_excel',1));                   
        set out_state = 25;      
            
        leave pro_main; 
    end if;
        
    #开始准备拼凑 INSERT INTO 的SQL语句
    set @all_excel_columns = concat(\",\",@student_excel_columns,@person_excel_columns);    
    set @all_sql_keys = concat(\",\",@student_sql_keys,@person_sql_keys);              
    set @student_sql_insert = concat(\"insert into education_student ( 
        id
        ,id_user        
        ,class_name
        ,id_person
        ,id_creater       
        ,id_creater_group  
        ,code_creater_group         
        ,\",@student_sql_keys,\"status ) values \");    
    set @person_sql_insert = concat(\"insert into basic_person ( 
        id
        ,id_creater       
        ,id_creater_group  
        ,code_creater_group       
        ,\",@person_sql_keys,\"status ) values \");    
    set @user_sql_insert = \"insert into basic_user ( 
        id
        ,person_id
        ,group_id
        ,group_code
        ,group_name        
        ,id_creater
        ,id_creater_group
        ,code_creater_group
        ,username
        ,password
        ,type
        ,money) values \";    
    set @user2group_sql_insert = \"insert into basic_group_2_user ( id_user,id_group,username,code_group,type ) values \";
    set @columncount = basic_stringcount(@person_excel_columns,\",\") + basic_stringcount(@student_excel_columns,\",\");       

    #打开核心游标,开始读取主业务数据
    set out_ids = '';   
    set fig = 0;    
    open cur_student;            
    fetch cur_student into A__,B__,C__,D__,E__,F__,G__,H__,I__,J__,K__,L__,M__,N__,O__,P__,Q__,R__,S__,T__,U__,V__,W__,X__,Y__,Z__,AA__,AB__,AC__,AD__,AE__,AF__,AG__,AH__,AI__,AJ__,AK__,AL__,AM__,AN__,rowindex__;
    cur_while: while (fig = 0) do  
        set @sql_values = '';  
        set @p = 0;         
        set @spotpos = 1;   
        set @spotpos2 = 1;   
        set @spotpos_ = 1;   
        set @spotpos2_ = 1;             
        set @tempvalue = '';    
        set @student_sql_values = '';                            
        set @person_sql_values = '';           

        #逐一判断EXCEL文件中,每一个单元格内数据的准确性

        inerLoop: LOOP
            SET @p = @p + 1;  
            set @spotpos = LOCATE(',', @all_excel_columns,@spotpos2);
			set @spotpos2 = LOCATE(',', @all_excel_columns,@spotpos+1);
            set @alphaindex = SUBSTRING(@all_excel_columns,@spotpos+1,@spotpos2-@spotpos-1);                

            set @spotpos_ = LOCATE(',', @all_sql_keys,@spotpos2_);
			set @spotpos2_ = LOCATE(',', @all_sql_keys,@spotpos_+1);
            set @keyindex = SUBSTRING(@all_sql_keys,@spotpos_+1,@spotpos2_-@spotpos_-1);    
            #select @alphaindex,@keyindex;               

            if @alphaindex = 'A' then set @tempvalue = A__; end if; 
            if @alphaindex = 'B' then set @tempvalue = B__; end if; 
            if @alphaindex = 'C' then set @tempvalue = C__; end if; 
            if @alphaindex = 'D' then set @tempvalue = D__; end if; 
            if @alphaindex = 'E' then set @tempvalue = E__; end if; 
            if @alphaindex = 'F' then set @tempvalue = F__; end if; 
            if @alphaindex = 'G' then set @tempvalue = G__; end if; 
            if @alphaindex = 'H' then set @tempvalue = H__; end if; 
            if @alphaindex = 'I' then set @tempvalue = I__; end if; 
            if @alphaindex = 'J' then set @tempvalue = J__; end if; 
            if @alphaindex = 'K' then set @tempvalue = K__; end if; 
            if @alphaindex = 'L' then set @tempvalue = L__; end if; 
            if @alphaindex = 'M' then set @tempvalue = M__; end if; 
            if @alphaindex = 'N' then set @tempvalue = N__; end if; 
            if @alphaindex = 'O' then set @tempvalue = O__; end if; 
            if @alphaindex = 'P' then set @tempvalue = P__; end if; 
            if @alphaindex = 'Q' then set @tempvalue = Q__; end if; 
            if @alphaindex = 'R' then set @tempvalue = R__; end if; 
            if @alphaindex = 'S' then set @tempvalue = S__; end if; 
            if @alphaindex = 'T' then set @tempvalue = T__; end if; 
            if @alphaindex = 'U' then set @tempvalue = U__; end if; 
            if @alphaindex = 'V' then set @tempvalue = V__; end if; 
            if @alphaindex = 'W' then set @tempvalue = W__; end if; 
            if @alphaindex = 'X' then set @tempvalue = X__; end if; 
            if @alphaindex = 'Y' then set @tempvalue = Y__; end if; 
            if @alphaindex = 'Z' then set @tempvalue = Z__; end if; 
            if @alphaindex = 'AA' then set @tempvalue = AA__; end if; 
            if @alphaindex = 'AB' then set @tempvalue = AB__; end if; 
            if @alphaindex = 'AC' then set @tempvalue = AC__; end if; 
            if @alphaindex = 'AD' then set @tempvalue = AD__; end if; 
            if @alphaindex = 'AE' then set @tempvalue = AE__; end if; 
            if @alphaindex = 'AF' then set @tempvalue = AF__; end if; 
            if @alphaindex = 'AG' then set @tempvalue = AG__; end if; 
            if @alphaindex = 'AH' then set @tempvalue = AH__; end if; 
            if @alphaindex = 'AI' then set @tempvalue = AI__; end if; 
            if @alphaindex = 'AJ' then set @tempvalue = AJ__; end if; 
            if @alphaindex = 'AK' then set @tempvalue = AK__; end if; 
            if @alphaindex = 'AL' then set @tempvalue = AL__; end if; 
            if @alphaindex = 'AM' then set @tempvalue = AM__; end if; 
            if @alphaindex = 'AN' then set @tempvalue = AN__; end if;             

            if length(@alphaindex) = 1 then
                set @alphaindex = concat('0',@alphaindex);            
            end if;                    
            #select @alphaindex,@spotpos,@spotpos2,@person_excel_columns;     
            if @alphaindex >=  @divideColumn then                   
                #set @xxx = 1;                  
                if @keyindex = 'gender' then           
                    set @temp = null;
                    select code into @temp from basic_memory where extend5 = 'basic_person__gender' and extend4 = @tempvalue;            
                    #select @temp;
                    if @temp is null then 
                        set out_state = 26;
                        set out_msg = concat(@tempvalue,\" \",@alphaindex,rowindex__,\" \",basic_memory__il8n('cellWrong','basic_excel',1));                        
                        
                        leave pro_main;
                    end if;     
                    set @tempvalue = @temp;                
                end if;                
                if @keyindex = 'politically' then           
                    set @temp = null;
                    select code into @temp from basic_memory where extend5 = 'basic_person__politically' and extend4 = @tempvalue;            
                    #select @temp;
                    if @temp is null then 
                        set out_state = 27;
                        set out_msg = concat(@tempvalue,\" \",@alphaindex,rowindex__,\" \",basic_memory__il8n('cellWrong','basic_excel',1));                        
                        
                        leave pro_main;
                    end if;          
                    set @tempvalue = @temp;           
                end if;                
                if @keyindex = 'cardType' then           
                    set @temp = null;
                    select code into @temp from basic_memory where extend5 = 'basic_person__card' and extend4 = @tempvalue;            
                    #select @temp;
                    if @temp is null then 
                        set out_state = 28;
                        set out_msg = concat(@tempvalue,\" \",@alphaindex,rowindex__,\" \",basic_memory__il8n('cellWrong','basic_excel',1));                        
                        
                        leave pro_main;
                    end if;          
                    set @tempvalue = @temp;           
                end if;       
                if @keyindex = 'nation' then           
                    set @temp = null;
                    select code into @temp from basic_memory where extend5 = 'basic_person__nation' and extend4 = @tempvalue;            
                    #select @temp;
                    if @temp is null then 
                        set out_state = 29;
                        set out_msg = concat(@tempvalue,\" \",@alphaindex,rowindex__,\" \",basic_memory__il8n('cellWrong','basic_excel',1));                        
                       
                        leave pro_main;
                    end if;          
                    set @tempvalue = @temp;           
                end if;                           

                set @person_sql_values =  concat(@person_sql_values,\",'\",@tempvalue,\"'\");  
            else                         
                if @keyindex = 'code' then       
                    set @codetemp = 0;       
                    select count(*) into @codetemp from education_student where code = @tempvalue ;                  
                    if @codetemp > 0 then                                        
                        set out_state = 201;
                        set out_msg = concat(@tempvalue,\" \",@alphaindex,rowindex__,\" \",basic_memory__il8n('cellWrong','basic_excel',1));                        
                        
                        leave pro_main;
                    end if;                    
                    set @username = @tempvalue;
                end if;                
                if @keyindex = 'class_code' then   
                    set @temp = null;                              
                    select code,id,name into @temp,@basic_group_id,@user_group_name from basic_group where code = @tempvalue ;                    
                    if @temp is null then                                        
                        set out_state = 202;
                        set out_msg = concat(@tempvalue,\" \",@alphaindex,rowindex__,\" \",basic_memory__il8n('cellWrong','basic_excel',1));                        
                        
                        leave pro_main;
                    end if;                    
                    set @user_group_code = @temp;
                end if;                
                if @keyindex = 'specialty' then                                 
                    set @temp = null;
                    select code into @temp from basic_memory where extend5 = 'education_student__specialty' and extend4 = @tempvalue;            
                    #select @temp;
                    if @temp is null then 
                        set out_state = 203;
                        set out_msg = concat(@tempvalue,\" \",@alphaindex,rowindex__,\" \",basic_memory__il8n('cellWrong','basic_excel',1));                        
                        
                        leave pro_main;
                    end if;   
                    set @tempvalue = @temp;  
                end if;                
                if @keyindex = 'hobby' then                                 
                    set @temp = null;
                    select code into @temp from basic_memory where extend5 = 'education_student__hobby' and extend4 = @tempvalue;            
                    #select @temp;
                    if @temp is null then 
                        set out_state = 204;
                        set out_msg = concat(@tempvalue,\" \",@alphaindex,rowindex__,\" \",basic_memory__il8n('cellWrong','basic_excel',1));                        
                        
                        leave pro_main;
                    end if;   
                    set @tempvalue = @temp;  
                end if;                
                if @keyindex = 'characters' then                                 
                    set @temp = null;
                    select code into @temp from basic_memory where extend5 = 'education_student__characters' and extend4 = @tempvalue;            
                    #select @temp;
                    if @temp is null then 
                        set out_state = 205;
                        set out_msg = concat(@tempvalue,\" \",@alphaindex,rowindex__,\" \",basic_memory__il8n('cellWrong','basic_excel',1));                        
                        
                        leave pro_main;
                    end if;   
                    set @tempvalue = @temp;  
                end if;                
                if @keyindex = 'attitude_life' then                                 
                    set @temp = null;
                    select code into @temp from basic_memory where extend5 = 'education_student__attitude_life' and extend4 = @tempvalue;            
                    #select @temp;
                    if @temp is null then 
                        set out_state = 206;
                        set out_msg = concat(@tempvalue,\" \",@alphaindex,rowindex__,\" \",basic_memory__il8n('cellWrong','basic_excel',1));                        
                        
                        leave pro_main;
                    end if;   
                    set @tempvalue = @temp;  
                end if;                
                if @keyindex = 'attitude_learn' then                                 
                    set @temp = null;
                    select code into @temp from basic_memory where extend5 = 'education_student__attitude_learn' and extend4 = @tempvalue;            
                    #select @temp;
                    if @temp is null then 
                        set out_state = 0;
                        set out_msg = concat(@tempvalue,\" \",@alphaindex,rowindex__,\" \",basic_memory__il8n('cellWrong','basic_excel',1));                        
                        
                        leave pro_main;
                    end if;   
                    set @tempvalue = @temp;  
                end if;                
                if @keyindex = 'attitude_teacher' then                                 
                    set @temp = null;
                    select code into @temp from basic_memory where extend5 = 'education_student__attitude_teacher' and extend4 = @tempvalue;            
                    #select @temp;
                    if @temp is null then 
                        set out_state = 0;
                        set out_msg = concat(@tempvalue,\" \",@alphaindex,rowindex__,\" \",basic_memory__il8n('cellWrong','basic_excel',1));                        
                        
                        leave pro_main;
                    end if;   
                    set @tempvalue = @temp;  
                end if;                
                if @keyindex = 'attitude_classmate' then                                 
                    set @temp = null;
                    select code into @temp from basic_memory where extend5 = 'education_student__attitude_classmate' and extend4 = @tempvalue;            
                    #select @temp;
                    if @temp is null then 
                        set out_state = 0;
                        set out_msg = concat(@tempvalue,\" \",@alphaindex,rowindex__,\" \",basic_memory__il8n('cellWrong','basic_excel',1));                        
                        
                        leave pro_main;
                    end if;   
                    set @tempvalue = @temp;  
                end if;                
                if @keyindex = 'attitude_oppositesex' then                                 
                    set @temp = null;
                    select code into @temp from basic_memory where extend5 = 'education_student__attitude_oppositesex' and extend4 = @tempvalue;            
                    #select @temp;
                    if @temp is null then 
                        set out_state = 0;
                        set out_msg = concat(@tempvalue,\" \",@alphaindex,rowindex__,\" \",basic_memory__il8n('cellWrong','basic_excel',1));                        
                        
                        leave pro_main;
                    end if;   
                    set @tempvalue = @temp;  
                end if;                
                if @keyindex = 'intelligence' then                                 
                    set @temp = null;
                    select code into @temp from basic_memory where extend5 = 'education_student__intelligence' and extend4 = @tempvalue;            
                    #select @temp;
                    if @temp is null then 
                        set out_state = 0;
                        set out_msg = concat(@tempvalue,\" \",@alphaindex,rowindex__,\" \",basic_memory__il8n('cellWrong','basic_excel',1));                        
                        
                        leave pro_main;
                    end if;   
                    set @tempvalue = @temp;  
                end if;                
                if @keyindex = 'class_manager' then                                 
                    set @temp = null;
                    select code into @temp from basic_memory where extend5 = 'education_student__classmanager' and extend4 = @tempvalue;            
                    #select @temp;
                    if @temp is null then 
                        set out_state = 0;
                        set out_msg = concat(@tempvalue,\" \",@alphaindex,rowindex__,\" \",basic_memory__il8n('cellWrong','basic_excel',1));                        
                        
                        leave pro_main;
                    end if;   
                    set @tempvalue = @temp;  
                end if;
                set @student_sql_values =  concat(@student_sql_values,\",'\",@tempvalue,\"'\");                  
            end if;
            
            IF @p < @columncount THEN
               ITERATE inerLoop;
            END IF;
            LEAVE inerLoop;
        END LOOP inerLoop;             

        #生成业务表的主键
        set @id_person = basic_memory__index('basic_person');               
        set @id_user = basic_memory__index('basic_user');       
        set @id_student =  basic_memory__index('education_student'); 
        set out_ids = concat(out_ids,\",\",@id_student);          

        #拼凑SQL语句        
        set @student_sql_values_ = concat( 
            @id_student,\"
            ,'\",@id_user ,\"'         
            ,'\",@user_group_name,\"'
            ,'\",@id_person,\"'            
            ,'\",id_creater_,\"'
            ,'\",id_creater_group_,\"'            
            ,'\",code_creater_group_,\"'            
            \",@student_sql_values,\",1\"); 
                           
        set @person_sql_values_ = concat( 
            @id_person,\"            
            ,'\",id_creater_,\"'
            ,'\",id_creater_group_,\"'
            ,'\",code_creater_group_,\"'            
            \",@person_sql_values,\",1\"); 

        if rowindex__ = @maxrow then                 
            set @student_sql_insert = concat(@student_sql_insert,\"(\",@student_sql_values_,\") ;\");      
            set @person_sql_insert = concat(@person_sql_insert,\"(\",@person_sql_values_,\") ;\");                  
            set @user_sql_insert = concat(@user_sql_insert,\"('\"
                ,@id_user,\"'
                ,'\",@id_person,\"'
                ,'\",@basic_group_id,\"'
                ,'\",@user_group_code,\"'
                ,'\",@user_group_name,\"'                
                ,'\",id_creater_,\"'
                ,'\",id_creater_group_,\"'                
                ,'\",code_creater_group_,\"'
                ,'\",@username,\"'
                ,'\",md5(@username),\"'
                ,2
                ,1000) ;\");   
            set @user2group_sql_insert = concat(@user2group_sql_insert,\"(
                '\",@id_user,\"',
                '\",@basic_group_id,\"',
                '\",@username,\"',
                '\",@user_group_code,\"',
                '1') ;\");       
        else
            set @student_sql_insert = concat(@student_sql_insert,\"(\",@student_sql_values_,\") ,\");      
            set @person_sql_insert = concat(@person_sql_insert,\"(\",@person_sql_values_,\") ,\");                  
            set @user_sql_insert = concat(@user_sql_insert,\"('\"
                ,@id_user,\"'
                ,'\",@id_person,\"'
                ,'\",@basic_group_id,\"'
                ,'\",@user_group_code,\"'
                ,'\",@user_group_name,\"'                
                ,'\",id_creater_,\"'
                ,'\",id_creater_group_,\"'                
                ,'\",code_creater_group_,\"'
                ,'\",@username,\"'
                ,'\",md5(@username),\"'
                ,2
                ,1000) ,\");   
            set @user2group_sql_insert = concat(@user2group_sql_insert,\"(
                '\",@id_user,\"',
                '\",@basic_group_id,\"',
                '\",@username,\"',
                '\",@user_group_code,\"',
                '1') ,\"); 
        end if;
                
        #select @student_sql_insert,@person_sql_insert,@user_sql_insert,@user2group_sql_insert;leave pro_main;
    fetch cur_student into A__,B__,C__,D__,E__,F__,G__,H__,I__,J__,K__,L__,M__,N__,O__,P__,Q__,R__,S__,T__,U__,V__,W__,X__,Y__,Z__,AA__,AB__,AC__,AD__,AE__,AF__,AG__,AH__,AI__,AJ__,AK__,AL__,AM__,AN__,rowindex__;
    end while cur_while;
    close cur_student;         

    #select @student_sql_insert,@person_sql_insert,@user_sql_insert,@user2group_sql_insert;leave pro_main;    
    PREPARE stmt FROM @student_sql_insert;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;      
    PREPARE stmt FROM @person_sql_insert;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;          
    PREPARE stmt FROM @user_sql_insert;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;    
    PREPARE stmt FROM @user2group_sql_insert;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;    
                
    #更新用户组中的 用户人数统计 
    update basic_group set count_users = 
           (select count(*) from basic_user where basic_user.group_code = basic_group.code  ) ;
    
    set out_state = 1;
    set out_msg = 'ok'; 
    delete from basic_excel where guid = in_guid;
    drop TEMPORARY table if exists array_student;         
END;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for education_subject__import
-- ----------------------------
DROP PROCEDURE IF EXISTS `education_subject__import`;
DELIMITER ;;
CREATE PROCEDURE `education_subject__import`(IN in_guid char(36),OUT out_state int,OUT out_msg varchar(200),OUT out_ids varchar(2000))
pro_main:BEGIN
    declare fig int;         
    declare rowindex__ int;
    declare A_,B_,C_,D_,E_,F_,G_ varchar(200);    
    declare code_,row1_,row2_ varchar(200); 
    declare id_creater_,id_creater_group_,rowindex_ int default 0;    

    declare cur_array cursor for     
        SELECT code,row1,row2 from array_group;   
    declare cur_group cursor for 
        SELECT A,B,C,D,E,F,G,rowindex from basic_excel where sheetname = basic_memory__il8n('subject','education_subject',1)         
            and guid = in_guid            
            and rowindex > 1
            order by rowindex;  
               
     
    #以下变量用于游标
    declare CONTINUE HANDLER FOR SQLSTATE '02000' SET fig = 1;       
    
    if in_guid is null then        
        set out_state = 0;        
        set out_msg = 'null guid';        
        leave pro_main;
    end if;
    
    drop TEMPORARY table if exists array_group;
    create  TEMPORARY  table array_group (
        code varchar(2)        
        ,row1 varchar(200)   
        ,row2 varchar(200)        
    ) engine = memory ;         
    
    select A,B,C,D,E,F,G,id_creater into A_,B_,C_,D_,E_,F_,G_,id_creater_ from basic_excel 
        where guid = in_guid 
        and rowindex = 1 
        and sheetname = basic_memory__il8n('subject','education_subject',1); 
    if A_ is null then
        set out_msg = basic_memory__il8n('guidWrong','basic_excel',1); 
        set out_state = 0;
        leave pro_main;
    end if;     
    
    set out_state = 0;    
    set out_msg = \"\";    
    set out_ids = \"\";               

    insert into array_group values 
        ('A', basic_memory__il8n( A_,'education_subject', 2) ,A_ ),        
        ('B', basic_memory__il8n( B_,'education_subject', 2) ,B_ ),        
        ('C', basic_memory__il8n( C_,'education_subject', 2) ,C_ ),        
        ('D', basic_memory__il8n( D_,'education_subject', 2) ,D_ ),        
        ('E', basic_memory__il8n( E_,'education_subject', 2) ,E_ ),        
        ('F', basic_memory__il8n( F_,'education_subject', 2) ,F_ ),        
        ('G', basic_memory__il8n( G_,'education_subject', 2) ,G_ )
    ;
    #select * from array_group;
    
    set @sufficient = \"name,code,type,remark\";
    set @keys = \"\";
    set @columns = \"\";
        
    set fig = 0;    
    open cur_array;        
    fetch cur_array into code_,row1_,row2_;        
    while( fig = 0 ) do      
    
        if row1_ is null then        
            set out_msg = concat(row2_,\" \",code_,\"2\",\" \",\"wrong column\");                             
        elseif FIND_IN_SET(row1_,@sufficient) = 0 then             
            set out_msg = concat(row2_,\" \",code_,\"2\",\" \",\"wrong column\");                   
            set out_state = 0;                
            leave pro_main;                
        else            
            set @keys = concat(\",\",row1_,@keys);       
            set @columns = concat(\",\",code_,@columns);         
        end if;  

    fetch cur_array into code_,row1_,row2_;     
    end while;
    close cur_array;      
    if FIND_IN_SET('name',@keys) = 0 then    
        set out_state = 0;
        set out_msg = concat(basic_memory__il8n('columnMissing','basic_excel',1),\" \",basic_memory__il8n('name','education_subject',1));             
        delete from basic_excel where guid = in_guid;        
        leave pro_main;        
    elseif FIND_IN_SET('type',@keys) = 0 then    
        set out_state = 0;
        set out_msg = concat(basic_memory__il8n('columnMissing','basic_excel',1),\" \",basic_memory__il8n('type','education_subject',1));             
        delete from basic_excel where guid = in_guid;        
        leave pro_main;        
    elseif FIND_IN_SET('code',@keys) = 0 then    
        set out_state = 0;
        set out_msg = concat(basic_memory__il8n('columnMissing','basic_excel',1),\" \",basic_memory__il8n('code','education_subject',1));             
        delete from basic_excel where guid = in_guid;        
        leave pro_main;
    end if; 

    #select @keys;    
    set @keys = concat(@keys,\",\");        
    set @columns = concat(@columns,\",\");    
    set @columncount = basic_stringcount(@keys,\",\");    
    select max(rowindex) into @maxrow from basic_excel where guid = in_guid and sheetname = basic_memory__il8n('subject','education_subject',1);  
    set @sql_insert = concat(\"insert into education_subject (id\",@keys,\"status) values \");        

    set fig = 0;    
    open cur_group; 
    fetch cur_group into A_,B_,C_,D_,E_,F_,G_,rowindex_;    
    while ( fig = 0 ) do        
        set @sql_values = '';  
        set @p = 0;         
        set @spotpos = 1;   
        set @spotpos2 = 1;   
        set @spotpos_ = 1;   
        set @spotpos2_ = 1;             
        set @tempvalue = '';    
        set @student_sql_values = '';                            
        set @person_sql_values = '';           

        inerLoop: LOOP
            SET @p = @p + 1;  
            set @spotpos = LOCATE(',', @columns,@spotpos2);
            set @spotpos2 = LOCATE(',', @columns,@spotpos+1);
            set @alphaindex = SUBSTRING(@columns,@spotpos+1,@spotpos2-@spotpos-1);                

            set @spotpos_ = LOCATE(',', @keys,@spotpos2_);
            set @spotpos2_ = LOCATE(',', @keys,@spotpos_+1);
            set @keyindex = SUBSTRING(@keys,@spotpos_+1,@spotpos2_-@spotpos_-1); 
            
            #select @alphaindex,@keyindex;
            
            if @alphaindex = 'A' then set @tempvalue = A_; end if; 
            if @alphaindex = 'B' then set @tempvalue = B_; end if; 
            if @alphaindex = 'C' then set @tempvalue = C_; end if; 
            if @alphaindex = 'D' then set @tempvalue = D_; end if; 
            if @alphaindex = 'E' then set @tempvalue = E_; end if; 
            if @alphaindex = 'F' then set @tempvalue = F_; end if; 
            if @alphaindex = 'G' then set @tempvalue = G_; end if;             

            if @keyindex = 'type' then           
                set @temp = null;
                select code into @temp from basic_memory where extend5 = 'education_subject__type' and extend4 = @tempvalue;            
                #select @temp;
                if @temp is null then 
                    set out_state = 0;
                    set out_msg = concat( basic_memory__il8n('wrongType','education_subject',1) ,\" \",@tempvalue,\" \",@alphaindex,rowindex_);                    
                    delete from basic_excel where guid = in_guid;
                    leave pro_main;
                end if;     
                set @tempvalue = @temp;  
            elseif @keyindex = 'code' then           
                set @codetemp = 0;       
                select count(*) into @codetemp from education_subject where code = @tempvalue ;                  
                if @codetemp > 0 then                                        
                    set out_state = 0;                        
                    set out_msg = concat(basic_memory__il8n('existCode','education_subject',1),\" \",@tempvalue,\" \",@alphaindex,rowindex_);  
                    delete from basic_excel where guid = in_guid;                   
                    leave pro_main;
                end if;                                               
            end if;       
                     
            set @sql_values = concat(@sql_values,\",'\",@tempvalue,\"'\");
                     
            IF @p < @columncount-1 THEN
               ITERATE inerLoop;
            END IF;
            LEAVE inerLoop;            
        END LOOP inerLoop;          
        set @id = basic_memory__index('education_subject');   
        set out_ids = concat(out_ids,\",\",@id);       
        if rowindex_ = @maxrow then                 
            set @sql_insert = concat(@sql_insert,\"('\",@id,\"'\",@sql_values,\",1) ;\");            
        else
            set @sql_insert = concat(@sql_insert,\"('\",@id,\"'\",@sql_values,\",1) ,\");            
        end if;
        #select @sql_values;         
        #leave pro_main;
    fetch cur_group into A_,B_,C_,D_,E_,F_,G_,rowindex_;    
    end while;
    close cur_group;    

    #select @sql_insert;    

    PREPARE stmt FROM @sql_insert;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;          
    delete from basic_excel where guid = in_guid;          
END;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for education_subject_2_group_2_teacher__import
-- ----------------------------
DROP PROCEDURE IF EXISTS `education_subject_2_group_2_teacher__import`;
DELIMITER ;;
CREATE PROCEDURE `education_subject_2_group_2_teacher__import`(IN in_guid char(36),OUT out_state int,OUT out_msg varchar(200))
pro_main:BEGIN
/*
EXCEL导入 教师-班级-科目 的对应关心


系统前端将一个EXCEL文件上传到系统服务端
服务端先将这个EXCEL文件的内容,不管内容对错与格式,直接写入到 basic_excel 表,
并使用服务端产生的 guid 标识数据来源
然后,数据库端,使用此存储过程,判断EXCEL文件中数据的正确性


version: 201210 
author: wei1224hf@gmail.com  
prerequisites: basic_memory__init,basic_memory.il8n(),basic_group[data],
               education_student[data],education_subject[data],education_teacher[data]
server used: education_subject_2_group_2_teacher.import()
involve: basic_user,basic_group,basic_person,
         education_teacher,education_subject,education_student,
*/

    declare fig int;         
    declare rowindex_ int;    
    declare code_,row1_ varchar(200);       
    declare A_,B_,C_,D_,E_,F_,G_,H_,I_,J_,K_,L_,M_,N_,O_,P_,Q_,R_,S_,T_,U_,V_ varchar(200);  
    declare cur_array cursor for
        select code,row1 from array_s2g2t;       

    declare cur_g2p cursor for 
        SELECT A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,rowindex from basic_excel where sheetname = basic_memory__il8n('s2g2t','education_subject_2_group_2_teacher',1)         
            and guid = in_guid            
            and rowindex > 1;        
    declare CONTINUE HANDLER FOR SQLSTATE '02000' SET fig = 1;       
    
    if in_guid is null then        
        set out_state = 0;        
        set out_msg = basic_memory__il8n('guidNULL','basic_excel',1);     
        leave pro_main;
    end if;
    
    drop TEMPORARY  table if exists array_s2g2t;
    create TEMPORARY  table array_s2g2t (
        code varchar(2)        
        ,row1 varchar(200)         
    ) engine = memory ;      

    select B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,maxcolumn into B_,C_,D_,E_,F_,G_,H_,I_,J_,K_,L_,M_,N_,O_,P_,Q_,R_,S_,T_,U_,V_,@maxcolumn from basic_excel 
        where guid = in_guid 
        and rowindex = 1 
        and sheetname = basic_memory__il8n('s2g2t','education_subject_2_group_2_teacher',1) ;  
    if C_ is null then          
        set out_state = 0;        
        set out_msg = basic_memory__il8n('guidWrong','basic_excel',1);        
        delete from basic_excel where guid = in_guid;
        leave pro_main;
    end if;    
    
    set out_state = \"\";
    set out_msg = \"\";  

    insert into array_s2g2t values     
        ('B',   B_),
        ('C',   C_),
        ('D',   D_),
        ('E',   E_),
        ('F',   F_),
        ('G',   G_),
        ('H',   H_),
        ('I',   I_),
        ('J',   J_),
        ('K',   K_),
        ('L',   L_),
        ('M',   M_),
        ('N',   N_),
        ('O',   O_),
        ('P',   P_),
        ('Q',   Q_),
        ('R',   R_),
        ('S',   S_),
        ('T',   T_),
        ('U',   U_),
        ('V',   V_);        
    #select * from array_s2g2t;        

    set @columns = '';    
    set @groups = '';    
    set @groupids = '';
    set fig = 0;    
    open cur_array;        
    fetch cur_array into code_,row1_;        
    set @columnIndex = 1;
    while_arr:while( fig = 0 ) do           
        if row1_ is null then        
            set out_msg = concat(out_msg,code_,'1','null;');     
            leave while_arr;           
        end if;                         
        set @temp = null;
        select id into @temp from education_subject where code = row1_;        
        if @temp is null then                
            set out_state = 2;            
            set out_msg = concat( basic_memory__il8n('wrongSubjectcode','education_subject_2_group_2_teacher',1), row1_ );          
               
            leave pro_main;
        end if;
        set @columns = concat(@columns,\",\",code_);      
        set @groups = concat(@groups,\",\",row1_);              
        set @groupids = concat(@groupids,\",\",row1_);      
    fetch cur_array into code_,row1_;    
    end while while_arr;
    close cur_array;  
    
    set @columns = concat(@columns,\",\");    
    set @groupids = concat(@groupids,\",\");  
    set @columncount = basic_stringcount(@columns,\",\");
    #select @columns,@groupids,@columncount;     
    select max(rowindex) into @maxrow from basic_excel where guid = in_guid and sheetname = basic_memory__il8n('s2g2t','education_subject_2_group_2_teacher',1) ;             
    set @sql_insert = \"insert into education_subject_2_group_2_teacher (group_code,subject_code,teacher_code) values \";    
    
    set @x = 0;
    set fig = 0;    
    open cur_g2p;            
    fetch cur_g2p into A_,B_,C_,D_,E_,F_,G_,H_,I_,J_,K_,L_,M_,N_,O_,P_,Q_,R_,S_,T_,U_,V_,rowindex_;
    cur_while: while (fig = 0) do       
        
        set @temp = null;   
        select id into @temp from basic_group where code = A_;        
        if @temp is null then                
            set out_state = 3;            
            set out_msg = concat( basic_memory__il8n('wrongGroupcode','education_subject_2_group_2_teacher',1), A_ );              
            SELECT A_;
                      
            leave pro_main;
        end if;                
        

        set @sql_values = '';  
        set @p = 0;         
        set @spotpos = 1;   
        set @spotpos2 = 1;   
        set @spotpos_ = 1;   
        set @spotpos2_ = 1;             
        set @tempvalue = '';    
        set @sql_values = '';  
        set @keyindex = '';     
        set @alphaindex = '';                                  

        inerLoop: LOOP
            SET @p = @p + 1;                    

            set @spotpos = LOCATE(',', @columns,@spotpos2);
            set @spotpos2 = LOCATE(',', @columns,@spotpos+1);
            set @alphaindex = SUBSTRING(@columns,@spotpos+1,@spotpos2-@spotpos-1);                

            set @spotpos_ = LOCATE(',', @groupids,@spotpos2_);
            set @spotpos2_ = LOCATE(',', @groupids,@spotpos_+1);
            set @keyindex = SUBSTRING(@groupids,@spotpos_+1,@spotpos2_-@spotpos_-1);
                
            if @alphaindex = 'B' then set @tempvalue = B_; end if;      
            if @alphaindex = 'C' then set @tempvalue = C_; end if;      
            if @alphaindex = 'D' then set @tempvalue = D_; end if;      
            if @alphaindex = 'E' then set @tempvalue = E_; end if;      
            if @alphaindex = 'F' then set @tempvalue = F_; end if;      
            if @alphaindex = 'G' then set @tempvalue = G_; end if;      
            if @alphaindex = 'H' then set @tempvalue = H_; end if;      
            if @alphaindex = 'I' then set @tempvalue = I_; end if;      
            if @alphaindex = 'J' then set @tempvalue = J_; end if;      
            if @alphaindex = 'K' then set @tempvalue = K_; end if;      
            if @alphaindex = 'L' then set @tempvalue = L_; end if;      
            if @alphaindex = 'M' then set @tempvalue = M_; end if;      
            if @alphaindex = 'N' then set @tempvalue = N_; end if;      
            if @alphaindex = 'O' then set @tempvalue = O_; end if;      
            if @alphaindex = 'P' then set @tempvalue = P_; end if;      
            if @alphaindex = 'Q' then set @tempvalue = Q_; end if;      
            if @alphaindex = 'R' then set @tempvalue = R_; end if;      
            if @alphaindex = 'S' then set @tempvalue = S_; end if;      
            if @alphaindex = 'T' then set @tempvalue = T_; end if;      
            if @alphaindex = 'U' then set @tempvalue = U_; end if;              

            if (@tempvalue is null) or @tempvalue = '' then         
               set out_msg = '';               
            else    
               set @x = @x + 1;              
               set @sql_insert = concat(@sql_insert,\"('\",A_,\"','\",@keyindex,\"','\",@tempvalue,\"') ,\");
            end if;   
  
            IF @p < @columncount-1 THEN       
               ITERATE inerLoop;
            END IF;
            LEAVE inerLoop;
        END LOOP inerLoop;          
     
    fetch cur_g2p into A_,B_,C_,D_,E_,F_,G_,H_,I_,J_,K_,L_,M_,N_,O_,P_,Q_,R_,S_,T_,U_,V_,rowindex_;
    end while cur_while;
    close cur_g2p;             

    set @sql_delete = concat( \"delete from education_subject_2_group_2_teacher where subject_code in ('99999'\",@groupids,\"'99998');\");    
    PREPARE stmt FROM @sql_delete;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;    
  
    set @sql_insert = SUBSTRING( @sql_insert , 1 , LENGTH(@sql_insert)-1 );    
    #select @x;
    #select @columns,@groups,@groupids;     
    #select @sql_insert;      

    PREPARE stmt FROM @sql_insert;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;      
    set out_state = 1;    
    set out_msg = 'done';

    drop TEMPORARY table if exists array_s2g2t; 
    delete from basic_excel where guid = in_guid; 

END;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for education_teacher__delete
-- ----------------------------
DROP PROCEDURE IF EXISTS `education_teacher__delete`;
DELIMITER ;;
CREATE PROCEDURE `education_teacher__delete`(IN `id_teacher` int)
BEGIN
    declare id_person_ int ;
    declare id_user_ int;    
    declare username_ varchar(200);    
    set username_ = '';
    set id_person_ = 0;
   
    set id_user_ = 0;
    select id_person,id_user,code into id_person_ ,id_user_,username_ from education_teacher  where id = id_teacher;
    delete from basic_person where id = id_person_;
    delete from basic_user where id = id_user_;    
    delete from basic_group_2_user where username = username_;    
    delete from education_teacher where id = id_teacher;

END;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for education_teacher__import
-- ----------------------------
DROP PROCEDURE IF EXISTS `education_teacher__import`;
DELIMITER ;;
CREATE PROCEDURE `education_teacher__import`(IN in_guid char(36),OUT out_state int,OUT out_msg varchar(200),OUT out_ids varchar(2000))
pro_main:BEGIN
/*
系统前端将一个EXCEL文件上传到系统服务端
服务端先将这个EXCEL文件的内容,不管内容对错与格式,直接写入到 basic_excel 表,
并使用服务端产生的 guid 标识数据来源
然后,数据库端,使用此存储过程,判断EXCEL文件中数据的正确性

如果发现EXCEL文件中任何一处数据有错误,将取消这次导入,并且删除 basic_excel 表内容

如果没有错误,就会在最后将数据插入,并返回在批导入的时候,产生的教师编号


每增加一个教师,就需要在 教师表 个人信息表 用户表 用户-用户组 中插入一条记录


version: 201211 
author: wei1224hf@gmail.com  
prerequisites: basic_memory__init,basic_memory.il8n(),basic_group[data]
server used: education_teacher.import()
involve: basic_user,basic_group,education_teacher,basic_person
*/

    #用以标识游标
    declare fig int; 
                
    #用以判断EXCEL数据准确性,因为教师的信息非常多,所以默认开设了很多个列    
    #教师信息中,包含教师档案跟个人信息档案.     
    #如果EXCEL文件中的列多于 AN , 将导入失败

    declare A_,B_,C_,D_,E_,F_,G_,H_,I_,J_,K_,L_,M_,N_,O_,P_,Q_,R_,S_,T_,U_,V_,W_,X_,Y_,Z_,AA_,AB_,AC_,AD_,AE_,AF_,AG_,AH_,AI_,AJ_,AK_,AL_,AM_,AN_ varchar(200);    
    declare A__,B__,C__,D__,E__,F__,G__,H__,I__,J__,K__,L__,M__,N__,O__,P__,Q__,R__,S__,T__,U__,V__,W__,X__,Y__,Z__,AA__,AB__,AC__,AD__,AE__,AF__,AG__,AH__,AI__,AJ__,AK__,AL__,AM__,AN__ varchar(2000);      

    #在 basic_excel 中,有一列是 id_creater 用于标识这个EXCEL是谁上传的    
    declare id_creater_,id_creater_group_,rowindex__ int default 0;   
    declare code_creater_group_ varchar(200) default '0' ; 
         
    #用于临时内存表的数据操作
    declare code_,row1_,row2_ varchar(200);          
    declare cur_array cursor for     
        SELECT code,row1,row2 from array_teacher ;   
                  
    #核心游标,EXCEL文件中,第二行是表头,第三行才是主业务数据,
    #excel 第一行是用于标识 教师档案 跟 个人信息档案 分界线的
    declare cur_teacher cursor for
        select A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z,AA,AB,AC,AD,AE,AF,AG,AH,AI,AJ,AK,AL,AM,AN,rowindex
        from basic_excel 
        where guid = in_guid 
        and rowindex > 2
        and sheetname = basic_memory__il8n('teacher','education_teacher',1)
         order by rowindex;    
    declare CONTINUE HANDLER FOR SQLSTATE '02000' SET fig = 1;     

    #得到EXCEL中,业务数据的总行数                    
    select max(rowindex) into @maxrow from basic_excel where guid = in_guid and sheetname = basic_memory__il8n('teacher','education_teacher',1);             

    if in_guid is null then        
        set out_state = 0;        
        set out_msg = 'null guid';
        leave pro_main;
    end if;           
        
    #处理第一行,得到 教师档案 跟 个人信息档案 的分界列
    select A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z,AA,AB,AC,AD,AE,AF,AG,AH,AI,AJ,AK,AL,AM,AN,id_creater into 
           A_,B_,C_,D_,E_,F_,G_,H_,I_,J_,K_,L_,M_,N_,O_,P_,Q_,R_,S_,T_,U_,V_,W_,X_,Y_,Z_,AA_,AB_,AC_,AD_,AE_,AF_,AG_,AH_,AI_,AJ_,AK_,AL_,AM_,AN_,id_creater_ 
           from basic_excel 
        where guid = in_guid 
        and rowindex = 1 
        and sheetname = basic_memory__il8n('teacher','education_teacher',1);     
    if ( (id_creater_ is null) or (id_creater_ = 0 ) )then          
        set out_state = 0;        
        set out_msg = basic_memory__il8n('guidWrong','basic_excel',1);        
        
        leave pro_main;
    end if;       

    if id_creater_ = 1 then            
        #是超级管理员 admin 导入的数据        
        set id_creater_group_ = 1;        
        set code_creater_group_ = '10';
    else   
        select group_id,group_code into id_creater_group_,code_creater_group_ from basic_user where id =  id_creater_;
    end if;               

    drop TEMPORARY  table if exists array_teacher;
    create  TEMPORARY  table array_teacher (
        code varchar(2)        
        ,row1 varchar(200)   
        ,row2 varchar(200)        
    ) engine = memory ;       

    insert into array_teacher values 
        ('A', basic_memory__il8n( A_,'education_teacher', 2) ,A_ ),
        ('B', basic_memory__il8n( B_,'education_teacher', 2) ,B_ ),
        ('C', basic_memory__il8n( C_,'education_teacher', 2) ,C_ ),
        ('D', basic_memory__il8n( D_,'education_teacher', 2) ,D_ ),
        ('E', basic_memory__il8n( E_,'education_teacher', 2) ,E_ ),
        ('F', basic_memory__il8n( F_,'education_teacher', 2) ,F_ ),
        ('G', basic_memory__il8n( G_,'education_teacher', 2) ,G_ ),
        ('H', basic_memory__il8n( H_,'education_teacher', 2) ,H_ ),
        ('I', basic_memory__il8n( I_,'education_teacher', 2) ,I_ ),
        ('J', basic_memory__il8n( J_,'education_teacher', 2) ,J_ ),
        ('K', basic_memory__il8n( K_,'education_teacher', 2) ,K_ ),
        ('L', basic_memory__il8n( L_,'education_teacher', 2) ,L_ ),
        ('M', basic_memory__il8n( M_,'education_teacher', 2) ,M_ ),
        ('N', basic_memory__il8n( N_,'education_teacher', 2) ,N_ ),
        ('O', basic_memory__il8n( O_,'education_teacher', 2) ,O_ ),
        ('P', basic_memory__il8n( P_,'education_teacher', 2) ,P_ ),
        ('Q', basic_memory__il8n( Q_,'education_teacher', 2) ,Q_ ),
        ('R', basic_memory__il8n( R_,'education_teacher', 2) ,R_ ),
        ('S', basic_memory__il8n( S_,'education_teacher', 2) ,S_ ),
        ('T', basic_memory__il8n( T_,'education_teacher', 2) ,T_ ),
        ('U', basic_memory__il8n( U_,'education_teacher', 2) ,U_ ),
        ('V', basic_memory__il8n( V_,'education_teacher', 2) ,V_ ),
        ('W', basic_memory__il8n( W_,'education_teacher', 2) ,W_ ),
        ('X', basic_memory__il8n( X_,'education_teacher', 2) ,X_ ),
        ('Y', basic_memory__il8n( Y_,'education_teacher', 2) ,Y_ ),
        ('Z', basic_memory__il8n( Z_,'education_teacher', 2) ,Z_ ),
        ('AA', basic_memory__il8n( AA_,'education_teacher', 2) ,AA_ ),
        ('AB', basic_memory__il8n( AB_,'education_teacher', 2) ,AB_ ),
        ('AC', basic_memory__il8n( AC_,'education_teacher', 2) ,AC_ ),
        ('AD', basic_memory__il8n( AD_,'education_teacher', 2) ,AD_ ),
        ('AE', basic_memory__il8n( AE_,'education_teacher', 2) ,AE_ ),
        ('AF', basic_memory__il8n( AF_,'education_teacher', 2) ,AF_ ),
        ('AG', basic_memory__il8n( AG_,'education_teacher', 2) ,AG_ ),
        ('AH', basic_memory__il8n( AH_,'education_teacher', 2) ,AH_ ),
        ('AI', basic_memory__il8n( AI_,'education_teacher', 2) ,AI_ ),
        ('AJ', basic_memory__il8n( AJ_,'education_teacher', 2) ,AJ_ ),
        ('AK', basic_memory__il8n( AK_,'education_teacher', 2) ,AK_ ),
        ('AL', basic_memory__il8n( AL_,'education_teacher', 2) ,AL_ ),
        ('AM', basic_memory__il8n( AM_,'education_teacher', 2) ,AM_ ),
        ('AN', basic_memory__il8n( AN_,'education_teacher', 2) ,AN_ )        
    ;    
    #select * from array_teacher;   
         
    set @divideColumn = '';    
    set @divideColumnIndex = 0;
    set @temp_index = 0;
    set fig = 0;    
    open cur_array;        
    fetch cur_array into code_,row1_,row2_;        
    set @columnIndex = 1;
    divide:while( fig = 0 ) do       
        set @temp_index = @temp_index + 1;
        #select code_;        
        if row1_ = 'doc_person' then
            #select code_;   
            set @divideColumn = code_;            
            if LENGTH(@divideColumn) = 1 then                        
                set @divideColumn = concat('0',@divideColumn);
            end if;
            leave divide;
        end if;
    fetch cur_array into code_,row1_,row2_;        
    end while;
    close cur_array;    
    #select @temp_index,@divideColumn;    
    if @divideColumn = '' then            
        set out_state = 3;        
        set out_msg = basic_memory__il8n('excelWrong_noDivideColumn','education_teacher',1);      
         
        leave pro_main;
    end if;

    #分析第二行,表头行,将每一个表头都插入到临时内存表
    delete from array_teacher;
    select A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z,AA,AB,AC,AD,AE,AF,AG,AH,AI,AJ,AK,AL,AM,AN,id_creater into 
           A_,B_,C_,D_,E_,F_,G_,H_,I_,J_,K_,L_,M_,N_,O_,P_,Q_,R_,S_,T_,U_,V_,W_,X_,Y_,Z_,AA_,AB_,AC_,AD_,AE_,AF_,AG_,AH_,AI_,AJ_,AK_,AL_,AM_,AN_,id_creater_ 
           from basic_excel 
        where guid = in_guid 
        and rowindex = 2 
        and sheetname = basic_memory__il8n('teacher','education_teacher',1);                   

    insert into array_teacher values 
        ('A', basic_memory__il8n( A_,'education_teacher', 2) ,A_ ),
        ('B', basic_memory__il8n( B_,'education_teacher', 2) ,B_ ),
        ('C', basic_memory__il8n( C_,'education_teacher', 2) ,C_ ),
        ('D', basic_memory__il8n( D_,'education_teacher', 2) ,D_ ),
        ('E', basic_memory__il8n( E_,'education_teacher', 2) ,E_ ),
        ('F', basic_memory__il8n( F_,'education_teacher', 2) ,F_ ),
        ('G', basic_memory__il8n( G_,'education_teacher', 2) ,G_ ),
        ('H', basic_memory__il8n( H_,'education_teacher', 2) ,H_ ),
        ('I', basic_memory__il8n( I_,'education_teacher', 2) ,I_ ),
        ('J', basic_memory__il8n( J_,'education_teacher', 2) ,J_ ),
        ('K', basic_memory__il8n( K_,'education_teacher', 2) ,K_ ),
        ('L', basic_memory__il8n( L_,'education_teacher', 2) ,L_ ),
        ('M', basic_memory__il8n( M_,'education_teacher', 2) ,M_ ),
        ('N', basic_memory__il8n( N_,'education_teacher', 2) ,N_ ),
        ('O', basic_memory__il8n( O_,'education_teacher', 2) ,O_ ),
        ('P', basic_memory__il8n( P_,'education_teacher', 2) ,P_ ),
        ('Q', basic_memory__il8n( Q_,'education_teacher', 2) ,Q_ ),
        ('R', basic_memory__il8n( R_,'education_teacher', 2) ,R_ ),
        ('S', basic_memory__il8n( S_,'education_teacher', 2) ,S_ ),
        ('T', basic_memory__il8n( T_,'education_teacher', 2) ,T_ ),
        ('U', basic_memory__il8n( U_,'education_teacher', 2) ,U_ ),
        ('V', basic_memory__il8n( V_,'education_teacher', 2) ,V_ ),
        ('W', basic_memory__il8n( W_,'education_teacher', 2) ,W_ ),
        ('X', basic_memory__il8n( X_,'education_teacher', 2) ,X_ ),
        ('Y', basic_memory__il8n( Y_,'education_teacher', 2) ,Y_ ),
        ('Z', basic_memory__il8n( Z_,'education_teacher', 2) ,Z_ ),
        ('AA', basic_memory__il8n( AA_,'education_teacher', 2) ,AA_ ),
        ('AB', basic_memory__il8n( AB_,'education_teacher', 2) ,AB_ ),
        ('AC', basic_memory__il8n( AC_,'education_teacher', 2) ,AC_ ),
        ('AD', basic_memory__il8n( AD_,'education_teacher', 2) ,AD_ ),
        ('AE', basic_memory__il8n( AE_,'education_teacher', 2) ,AE_ ),
        ('AF', basic_memory__il8n( AF_,'education_teacher', 2) ,AF_ ),
        ('AG', basic_memory__il8n( AG_,'education_teacher', 2) ,AG_ ),
        ('AH', basic_memory__il8n( AH_,'education_teacher', 2) ,AH_ ),
        ('AI', basic_memory__il8n( AI_,'education_teacher', 2) ,AI_ ),
        ('AJ', basic_memory__il8n( AJ_,'education_teacher', 2) ,AJ_ ),
        ('AK', basic_memory__il8n( AK_,'education_teacher', 2) ,AK_ ),
        ('AL', basic_memory__il8n( AL_,'education_teacher', 2) ,AL_ ),
        ('AM', basic_memory__il8n( AM_,'education_teacher', 2) ,AM_ ),
        ('AN', basic_memory__il8n( AN_,'education_teacher', 2) ,AN_ )        
    ;    
    #select * from array_teacher;   

    #逐一判断每一个表头的正确性

    set @teacher_sql_keys = '';    
    set @teacher_excel_columns = '';
    #如果表头中,有包含出此之外的表头,就是错误的EXCEL    
    set @teacher_sufficient = 'code,department_name,certificate,title,years,type,honor,specialty,experience_work,experience_publish,experience_project,photo_certificate,photo_degree';
    set @person_sql_keys = '';    
    set @person_excel_columns = '';        
    set @person_sufficient = 'name,birthday,cardType,idcard,ismarried,degree,degree_school,photo,height,nationality,gender,nation,politically,address_birth,address,cellphone,email,qq';

    set fig = 0;    
    open cur_array;        
    fetch cur_array into code_,row1_,row2_;        
    set @columnIndex = 1;
    divide:while( fig = 0 ) do      
        set @code__ = code_;
        if length(code_) = 1 then
            set @code__ = concat('0',code_);            
        end if;               

        if @code__ < @divideColumn then        
            #select 'xxxx',code_,@divideColumn;      
            #leave pro_main;              
            if row1_ is null then        
                set out_msg = concat(row2_,\" \",code_,\"2 \",basic_memory__il8n('columnWrong','basic_excel',1));            
                set out_state = 4;   
                              
                leave pro_main;
            elseif FIND_IN_SET(row1_,@teacher_sufficient) = 0 then             
                set out_msg = concat(row2_,\" \",code_,\"2 \",basic_memory__il8n('columnWrong','basic_excel',1));                   
                set out_state = 41;    
                            
                leave pro_main;                
            else            
                set @teacher_sql_keys = concat(@teacher_sql_keys,row1_,\",\");       
                set @teacher_excel_columns = concat(@teacher_excel_columns,code_,\",\");         
            end if;
        elseif ( row2_ is not null ) then
            if row1_ is null then        
                set out_msg = concat(row2_,\" \",code_,\"2 \",basic_memory__il8n('columnWrong','basic_excel',1));         
                set out_state = 42;     
                           
                leave pro_main;
            elseif FIND_IN_SET(row1_,@person_sufficient) = 0 then             
                set out_msg = concat(row2_,\" \",code_,\"2 \",basic_memory__il8n('columnWrong','basic_excel',1));     
                set out_state = 43;             
                   
                leave pro_main;                
            else            
                set @person_sql_keys = concat(@person_sql_keys,row1_,\",\");    
                set @person_excel_columns = concat(@person_excel_columns,code_,\",\");                         
            end if;             
        end if;
    fetch cur_array into code_,row1_,row2_;        
    end while;
    close cur_array; 
    
    #检查一些必须要的列是否存在  
    if ( FIND_IN_SET(\"name\",@person_sql_keys) = 0 ) or        
       ( FIND_IN_SET(\"birthday\",@person_sql_keys) = 0 ) or    
       ( FIND_IN_SET(\"code\",@teacher_sql_keys) = 0 ) or       
       ( FIND_IN_SET(\"department_name\",@teacher_sql_keys) = 0 ) or       
       ( FIND_IN_SET(\"certificate\",@teacher_sql_keys) = 0 ) or       
       ( FIND_IN_SET(\"type\",@teacher_sql_keys) = 0 ) 
        then             
        select row2_,code2_;
        set out_msg = concat(row2_,\" \",code_,\"2 \",basic_memory__il8n('columnMissing','basic_excel',1));                   
        set out_state = 44;      
                  
        leave pro_main; 
    end if;
        
    #开始准备拼凑 INSERT INTO 的SQL语句
    set @all_excel_columns = concat(\",\",@teacher_excel_columns,@person_excel_columns);    
    set @all_sql_keys = concat(\",\",@teacher_sql_keys,@person_sql_keys);              
    set @teacher_sql_insert = concat(\"insert into education_teacher ( 
        id
        ,id_user        
        ,name        
        ,department_code
        ,id_person        
        ,code_creater_group
        ,id_creater_group
        ,id_creater
        ,\",@teacher_sql_keys
        ,\"status ) values \");    
    set @person_sql_insert = concat(\"insert into basic_person ( 
        id        
        ,code_creater_group
        ,id_creater_group
        ,id_creater
        ,\",@person_sql_keys
        ,\"status ) values \");    
    set @user_sql_insert = \"insert into basic_user ( 
        id
        ,person_id        
        ,person_name
        ,group_id
        ,group_code        
        ,group_name        
        ,code_creater_group
        ,id_creater_group
        ,id_creater
        ,username
        ,password
        ,type
        ,money) values \";    
    set @user2group_sql_insert = \"insert into basic_group_2_user ( id_user,id_group,type ) values \";
    set @columncount = basic_stringcount(@person_excel_columns,\",\") + basic_stringcount(@teacher_excel_columns,\",\");       

    #打开核心游标,开始读取主业务数据
    set out_ids = '';   
    set fig = 0;    
    open cur_teacher;            
    fetch cur_teacher into A__,B__,C__,D__,E__,F__,G__,H__,I__,J__,K__,L__,M__,N__,O__,P__,Q__,R__,S__,T__,U__,V__,W__,X__,Y__,Z__,AA__,AB__,AC__,AD__,AE__,AF__,AG__,AH__,AI__,AJ__,AK__,AL__,AM__,AN__,rowindex__;
    cur_while: while (fig = 0) do  
        set @sql_values = '';  
        set @p = 0;         
        set @spotpos = 1;   
        set @spotpos2 = 1;   
        set @spotpos_ = 1;   
        set @spotpos2_ = 1;             
        set @tempvalue = '';    
        set @teacher_sql_values = '';                            
        set @person_sql_values = '';           

        #逐一判断EXCEL文件中,每一个单元格内数据的准确性

        inerLoop: LOOP
            SET @p = @p + 1;  
            set @spotpos = LOCATE(',', @all_excel_columns,@spotpos2);
            set @spotpos2 = LOCATE(',', @all_excel_columns,@spotpos+1);
            set @alphaindex = SUBSTRING(@all_excel_columns,@spotpos+1,@spotpos2-@spotpos-1);                

            set @spotpos_ = LOCATE(',', @all_sql_keys,@spotpos2_);
            set @spotpos2_ = LOCATE(',', @all_sql_keys,@spotpos_+1);
            set @keyindex = SUBSTRING(@all_sql_keys,@spotpos_+1,@spotpos2_-@spotpos_-1);    
            #select @alphaindex,@keyindex;               

            if @alphaindex = 'A' then set @tempvalue = A__; end if; 
            if @alphaindex = 'B' then set @tempvalue = B__; end if; 
            if @alphaindex = 'C' then set @tempvalue = C__; end if; 
            if @alphaindex = 'D' then set @tempvalue = D__; end if; 
            if @alphaindex = 'E' then set @tempvalue = E__; end if; 
            if @alphaindex = 'F' then set @tempvalue = F__; end if; 
            if @alphaindex = 'G' then set @tempvalue = G__; end if; 
            if @alphaindex = 'H' then set @tempvalue = H__; end if; 
            if @alphaindex = 'I' then set @tempvalue = I__; end if; 
            if @alphaindex = 'J' then set @tempvalue = J__; end if; 
            if @alphaindex = 'K' then set @tempvalue = K__; end if; 
            if @alphaindex = 'L' then set @tempvalue = L__; end if; 
            if @alphaindex = 'M' then set @tempvalue = M__; end if; 
            if @alphaindex = 'N' then set @tempvalue = N__; end if; 
            if @alphaindex = 'O' then set @tempvalue = O__; end if; 
            if @alphaindex = 'P' then set @tempvalue = P__; end if; 
            if @alphaindex = 'Q' then set @tempvalue = Q__; end if; 
            if @alphaindex = 'R' then set @tempvalue = R__; end if; 
            if @alphaindex = 'S' then set @tempvalue = S__; end if; 
            if @alphaindex = 'T' then set @tempvalue = T__; end if; 
            if @alphaindex = 'U' then set @tempvalue = U__; end if; 
            if @alphaindex = 'V' then set @tempvalue = V__; end if; 
            if @alphaindex = 'W' then set @tempvalue = W__; end if; 
            if @alphaindex = 'X' then set @tempvalue = X__; end if; 
            if @alphaindex = 'Y' then set @tempvalue = Y__; end if; 
            if @alphaindex = 'Z' then set @tempvalue = Z__; end if; 
            if @alphaindex = 'AA' then set @tempvalue = AA__; end if; 
            if @alphaindex = 'AB' then set @tempvalue = AB__; end if; 
            if @alphaindex = 'AC' then set @tempvalue = AC__; end if; 
            if @alphaindex = 'AD' then set @tempvalue = AD__; end if; 
            if @alphaindex = 'AE' then set @tempvalue = AE__; end if; 
            if @alphaindex = 'AF' then set @tempvalue = AF__; end if; 
            if @alphaindex = 'AG' then set @tempvalue = AG__; end if; 
            if @alphaindex = 'AH' then set @tempvalue = AH__; end if; 
            if @alphaindex = 'AI' then set @tempvalue = AI__; end if; 
            if @alphaindex = 'AJ' then set @tempvalue = AJ__; end if; 
            if @alphaindex = 'AK' then set @tempvalue = AK__; end if; 
            if @alphaindex = 'AL' then set @tempvalue = AL__; end if; 
            if @alphaindex = 'AM' then set @tempvalue = AM__; end if; 
            if @alphaindex = 'AN' then set @tempvalue = AN__; end if;             

            if length(@alphaindex) = 1 then
                set @alphaindex = concat('0',@alphaindex);            
            end if;                    
            #select @alphaindex,@spotpos,@spotpos2,@person_excel_columns;     
            if @alphaindex >=  @divideColumn then                   
                #set @xxx = 1;                  
                if @keyindex = 'gender' then           
                    set @temp = null;
                    select code into @temp from basic_memory where extend5 = 'basic_person__gender' and extend4 = @tempvalue;            
                    #select @temp;
                    if @temp is null then 
                        set out_state = 6;
                        set out_msg = concat(@tempvalue,\" \",@alphaindex,rowindex__,\" \",basic_memory__il8n('cellWrong','basic_excel',1));                        
                         
                        leave pro_main;
                    end if;     
                    set @tempvalue = @temp;                
                end if;            
                if @keyindex = 'name' then                           
                   set @realname = @tempvalue;   
                end if;    
                if @keyindex = 'politically' then           
                    set @temp = null;
                    select code into @temp from basic_memory where extend5 = 'basic_person__politically' and extend4 = @tempvalue;            
                    #select @temp;
                    if @temp is null then 
                        set out_state = 61;
                        set out_msg = concat(@tempvalue,\" \",@alphaindex,rowindex__,\" \",basic_memory__il8n('cellWrong','basic_excel',1));                        
                         
                        leave pro_main;
                    end if;          
                    set @tempvalue = @temp;           
                end if;                
                if @keyindex = 'cardType' then           
                    set @temp = null;
                    select code into @temp from basic_memory where extend5 = 'basic_person__card' and extend4 = @tempvalue;            
                    #select @temp;
                    if @temp is null then 
                        set out_state = 62;
                        set out_msg = concat(@tempvalue,\" \",@alphaindex,rowindex__,\" \",basic_memory__il8n('cellWrong','basic_excel',1));                        
                         
                        leave pro_main;
                    end if;          
                    set @tempvalue = @temp;           
                end if;       
                if @keyindex = 'nation' then           
                    set @temp = null;
                    select code into @temp from basic_memory where extend5 = 'basic_person__nation' and extend4 = @tempvalue;            
                    #select @temp;
                    if @temp is null then 
                        set out_state = 63;
                        set out_msg = concat(@tempvalue,\" \",@alphaindex,rowindex__,\" \",basic_memory__il8n('cellWrong','basic_excel',1));                        
                         
                        leave pro_main;
                    end if;          
                    set @tempvalue = @temp;           
                end if;     
                if @keyindex = 'marriage' then           
                    set @temp = null;
                    select code into @temp from basic_memory where extend5 = 'basic_person__marriage' and extend4 = @tempvalue;            
                    #select @temp;
                    if @temp is null then 
                        set out_state = 64;
                        set out_msg = concat(@tempvalue,\" \",@alphaindex,rowindex__,\" \",basic_memory__il8n('cellWrong','basic_excel',1));                        
                         
                        leave pro_main;
                    end if;          
                    set @tempvalue = @temp;           
                end if;                       

                set @person_sql_values =  concat(@person_sql_values,\",'\",@tempvalue,\"'\");  
            else                         
                if @keyindex = 'code' then       
                    set @codetemp = 0;       
                    select count(*) into @codetemp from education_teacher where code = @tempvalue ;                  
                    if @codetemp > 0 then                                        
                        set out_state = 7;
                        set out_msg = concat(@tempvalue,\" \",@alphaindex,rowindex__,\" \",basic_memory__il8n('cellWrong','basic_excel',1));                        
                         
                        leave pro_main;
                    end if;                    
                    set @username = @tempvalue;
                end if;                
                if @keyindex = 'department_name' then   
                    set @temp = null;                              
                    select code,id into @temp,@basic_group_id from basic_group where name = @tempvalue ;                    
                    if @temp is null then                                        
                        set out_state = 71;
                        set out_msg = concat(@tempvalue,\" \",@alphaindex,rowindex__,\" \",basic_memory__il8n('cellWrong','basic_excel',1));                        
                         
                        leave pro_main;
                    end if;                    
                    set @user_group_code = @temp;                    
                    set @user_group_name = @tempvalue;
                end if;                
                if @keyindex = 'type' then                                 
                    set @temp = null;
                    select code into @temp from basic_memory where extend5 = 'education_teacher__type' and extend4 = @tempvalue;            
                    #select @temp;
                    if @temp is null then 
                        set out_state = 72;
                        set out_msg = concat(@tempvalue,\" \",@alphaindex,rowindex__,\" \",basic_memory__il8n('cellWrong','basic_excel',1));                        
                         
                        leave pro_main;
                    end if;   
                    set @tempvalue = @temp;  
                end if;     
                if @keyindex = 'honor' then                                 
                    set @temp = null;
                    select code into @temp from basic_memory where extend5 = 'education_teacher__honor' and extend4 = @tempvalue;            
                    #select @temp;
                    if @temp is null then 
                        set out_state = 73;
                        set out_msg = concat(@tempvalue,\" \",@alphaindex,rowindex__,\" \",basic_memory__il8n('cellWrong','basic_excel',1));                        
                         
                        leave pro_main;
                    end if;   
                    set @tempvalue = @temp;  
                end if;  
                if @keyindex = 'specialty' then                                 
                    set @temp = null;
                    select code into @temp from basic_parameter where reference = 'specialty' and value = @tempvalue;            
                    #select @temp;
                    if @temp is null then 
                        set out_state = 74;
                        set out_msg = concat(@tempvalue,\" \",@alphaindex,rowindex__,\" \",basic_memory__il8n('cellWrong','basic_excel',1));                        
                         
                        leave pro_main;
                    end if;   
                    set @tempvalue = @temp;  
                end if;     
                
                set @teacher_sql_values =  concat(@teacher_sql_values,\",'\",@tempvalue,\"'\");                  
            end if;
            
            IF @p < @columncount THEN
               ITERATE inerLoop;
            END IF;
            LEAVE inerLoop;
        END LOOP inerLoop;             

        #生成业务表的主键
        set @id_person = basic_memory__index('basic_person');               
        set @id_user = basic_memory__index('basic_user');       
        set @id_teacher =  basic_memory__index('education_teacher'); 
        set out_ids = concat(out_ids,\",\",@id_teacher);  
        #拼凑SQL语句
        set @teacher_sql_values_ = concat( 
            @id_teacher,\",\"
            ,@id_user ,\",\"            
            ,\"'\",@realname,\"',\"            
            ,\"'\",@user_group_code,\"',\"
            ,@id_person,\",\"            
            ,\"'\",code_creater_group_,\"',\"
            ,id_creater_group_,\",\"
            ,id_creater_
            ,@teacher_sql_values);        
        set @person_sql_values_ = concat( 
            @id_person,\",\"            
            ,\"'\",code_creater_group_,\"',\"
            ,id_creater_group_,\",\"
            ,id_creater_
            ,@person_sql_values); 

        if rowindex__ = @maxrow then                 
            set @teacher_sql_insert = concat(@teacher_sql_insert,\"(\",@teacher_sql_values_,\",1) ;\");      
            set @person_sql_insert = concat(@person_sql_insert,\"(\",@person_sql_values_,\",1) ;\");   
            set @user_sql_insert = concat(@user_sql_insert,\"('\"
                ,@id_user,\"','\"
                ,@id_person,\"','\"                
                ,@realname,\"','\"
                ,@basic_group_id,\"','\"                
                ,@user_group_code,\"','\"                
                ,@user_group_name,\"','\"
                ,code_creater_group_,\"','\"                
                ,id_creater_group_,\"','\"                
                ,id_creater_,\"','\"
                ,@username,\"','\"
                ,md5(@username),\"'
                ,3,1000) ;\");              
            set @user2group_sql_insert = concat(@user2group_sql_insert,\"('\",@id_user,\"','\",@basic_group_id,\"',1) ;\");        
        else
            set @teacher_sql_insert = concat(@teacher_sql_insert,\"(\",@teacher_sql_values_,\",1) ,\");            
            set @person_sql_insert = concat(@person_sql_insert,\"(\",@person_sql_values_,\",1) ,\");                  
            set @user_sql_insert = concat(@user_sql_insert,\"('\"
                ,@id_user,\"','\"
                ,@id_person,\"','\"                
                ,@realname,\"','\"
                ,@basic_group_id,\"','\"                
                ,@user_group_code,\"','\"                
                ,@user_group_name,\"','\"
                ,code_creater_group_,\"','\"                
                ,id_creater_group_,\"','\"                
                ,id_creater_,\"','\"
                ,@username,\"','\"
                ,md5(@username),\"'
                ,3,1000) ,\");   
            set @user2group_sql_insert = concat(@user2group_sql_insert,\"('\",@id_user,\"','\",@basic_group_id,\"',1) ,\");        
        end if;
        
    fetch cur_teacher into A__,B__,C__,D__,E__,F__,G__,H__,I__,J__,K__,L__,M__,N__,O__,P__,Q__,R__,S__,T__,U__,V__,W__,X__,Y__,Z__,AA__,AB__,AC__,AD__,AE__,AF__,AG__,AH__,AI__,AJ__,AK__,AL__,AM__,AN__,rowindex__;
    end while cur_while;
    close cur_teacher;     

    PREPARE stmt FROM @teacher_sql_insert;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;      
    PREPARE stmt FROM @person_sql_insert;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;          
    PREPARE stmt FROM @user_sql_insert;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;    
    PREPARE stmt FROM @user2group_sql_insert;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;                

    #更新用户组中的 用户人数统计 
    update basic_group set count_users = 
           (select count(*) from basic_user where basic_user.group_code = basic_group.code  ) ;
    
    set out_state = 1;
    set out_msg = 'ok'; 
    delete from basic_excel where guid = in_guid;
    drop TEMPORARY table if exists array_teacher;         
END;;
DELIMITER ;

-- ----------------------------
-- Function structure for basic_memory__il8n
-- ----------------------------
DROP FUNCTION IF EXISTS `basic_memory__il8n`;
DELIMITER ;;
CREATE FUNCTION `basic_memory__il8n`(in_key varchar(200),in_tablename varchar(200),flag int) RETURNS varchar(200)
BEGIN
	declare out_value varchar(200) CHARACTER SET utf8;
    if in_key is null then
        return NULL;
    end if;    

    if flag = 1 then
        
        if in_tablename = 'normal' then    
            select extend4 into out_value from basic_memory where code = in_key and extend6 = 'il8n' and extend5 is null limit 1;        
        else    
            select extend4 into out_value from basic_memory where code = in_key and extend6 = 'il8n' and extend5 = in_tablename limit 1;   
            if out_value is null then            
                select extend4 into out_value from basic_memory where code = in_key and extend6 = 'il8n' and extend5 is null limit 1;    
            end if;                             
        end if;  
    elseif flag = 3 then
        select extend4 into out_value from basic_memory where code = in_key and extend5 = in_tablename limit 1;             
    else 

        if in_tablename = 'normal' then    
            select code into out_value from basic_memory where extend4 = in_key and extend6 = 'il8n' and extend5 is null limit 1;        
        else    
            select code into out_value from basic_memory where extend4 = in_key and extend6 = 'il8n' and extend5 = in_tablename limit 1;     
            if out_value is null then            
                select code into out_value from basic_memory where extend4 = in_key and extend6 = 'il8n' and extend5 is null limit 1;                  
                #set out_value = 'x';
                if ( (out_value is null) AND ( (in_tablename = 'education_student') or (in_tablename = 'education_teacher') ) ) then                
                    #set out_value = 'y';
                    select code into out_value from basic_memory where extend4 = in_key and extend6 = 'il8n' and extend5 = 'basic_person' limit 1;                        
                end if;
            end if;           
        end if;  
    end if;
	RETURN out_value;
END;;
DELIMITER ;

-- ----------------------------
-- Function structure for basic_memory__index
-- ----------------------------
DROP FUNCTION IF EXISTS `basic_memory__index`;
DELIMITER ;;
CREATE FUNCTION `basic_memory__index`(in_tablename varchar(200)) RETURNS int(11)
BEGIN
    declare id_ int;
    
    select extend1 into id_ from basic_memory where type = 2 and code = in_tablename;    
    if id_ is null then 
        set id_ = 0;
    end if;
    update basic_memory set extend1 = extend1+1, extend2 = extend2 + 1 where type = 2 and code = in_tablename;  
    RETURN id_+1;
END;;
DELIMITER ;

-- ----------------------------
-- Function structure for basic_randstring
-- ----------------------------
DROP FUNCTION IF EXISTS `basic_randstring`;
DELIMITER ;;
CREATE FUNCTION `basic_randstring`(str_length int,str_type   int) RETURNS varchar(200)
BEGIN
 -- Function   : rand_string   
    -- Author     : reymondtu#opencfg.com   
    -- Date       : 2011/03/27   
    -- Params     : str_length int unsigned    
    --                  The random string length of random string   
    --              str_type   int unsigned   
    --                  The random string type   
    --                      1.0-9   
    --                      2.a-z   
    --                      3.A-Z   
    --                      4.a-zA-Z   
    --                      5.0-9a-zA-Z   
    --   
    -- Example    :   
    --   
    -- mysql> select rand_string(32,5) from dual;   
    -- +----------------------------------+   
    -- | rand_string(32,5)                |   
    -- +----------------------------------+   
    -- | HbPBz4DWSAiJNLt4SgExHVwQI34bI6mt |   
    -- +----------------------------------+   
    -- 1 row in set   
    declare counter int unsigned default 0;   
    declare const_chars varchar(64) default '0123456789';   
    declare result varchar(255) default '';   
    
    if str_type = 1 then  
        set const_chars = '0123456789';   
    elseif str_type = 2 then  
        set const_chars = 'abcdefghijklmnopqrstuvwxyz';   
    elseif str_type = 3 then  
        set const_chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';   
    elseif str_type = 4 then  
        set const_chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';   
    elseif str_type = 5 then  
        set const_chars = '0123456789abcdefghijklmnopqrstuvwxyz';   
    else  
        set const_chars = '0123456789';   
    end if;   
    
    while counter < str_length do     
        set result = concat(result,substr(const_chars,ceil(rand()*(length(const_chars)-1)),1));   
    set counter = counter + 1;   
    end while;   
  
    return result;   
END;;
DELIMITER ;

-- ----------------------------
-- Function structure for basic_stringcount
-- ----------------------------
DROP FUNCTION IF EXISTS `basic_stringcount`;
DELIMITER ;;
CREATE FUNCTION `basic_stringcount`(
    f_str varchar(8000), f_substr varchar(255)) RETURNS int(11)
BEGIN
        #Created by david yeung 20080226.
        declare i int default 0;
        declare remain_str varchar(8000) default '';
        set remain_str = f_str;
        while instr(remain_str,f_substr) > 0
        do
                set i = i + 1;
                set remain_str = substring(remain_str,instr(remain_str,f_substr) + char_length(f_substr));
        end while;
        return i;
END;;
DELIMITER ;

-- ----------------------------
-- Function structure for basic_user__group
-- ----------------------------
DROP FUNCTION IF EXISTS `basic_user__group`;
DELIMITER ;;
CREATE FUNCTION `basic_user__group`(`in_username` varchar(200)) RETURNS varchar(500)
BEGIN
    declare fig int;
    declare codes_ varchar(1000);    
    declare code_ varchar(200);
    declare cur cursor for 
        SELECT DISTINCT
        basic_group.code
        FROM
        basic_group
        Right Join basic_group_2_user ON basic_group.id = basic_group_2_user.id_group
        Right Join basic_user ON basic_group_2_user.id_user = basic_user.id
        WHERE
        basic_user.username =  in_username;

    declare CONTINUE HANDLER FOR SQLSTATE '02000' SET fig = 1;
    SET fig=0;
    set codes_ = ',';
        
    open cur;
         fetch cur into code_ ;      
         WHILE ( fig = 0 ) DO         
             set codes_ = concat(codes_,code_,',');
         fetch cur into code_ ;  
         END WHILE;
    close cur;     
	RETURN codes_;
END;;
DELIMITER ;

-- ----------------------------
-- Function structure for basic_user__permission
-- ----------------------------
DROP FUNCTION IF EXISTS `basic_user__permission`;
DELIMITER ;;
CREATE FUNCTION `basic_user__permission`(`in_username` varchar(200) ) RETURNS varchar(1000)
BEGIN
#获得某个用户的所有 权限编码 ,以 字符串 的形式返回


    declare fig int;
    declare ids_ varchar(1000);    
    declare id_ int;
    declare cur cursor for 
        SELECT DISTINCT
        basic_permission.code
        FROM
        basic_permission
        Right Join basic_group_2_permission ON basic_permission.id = basic_group_2_permission.id_permission
        Right Join basic_group_2_user ON basic_group_2_permission.id_group = basic_group_2_user.id_group
        Right Join basic_user ON basic_group_2_user.id_user = basic_user.id
        WHERE
        basic_user.username =  in_username
        ORDER BY
        basic_permission.code ASC;

    declare continue handler for not found set fig = 1;    
    set ids_ = ',';
    open cur;
    repeat
    fetch cur into id_ ;
        set ids_ = concat(ids_,id_,',');
    until fig = 1 end repeat;
    close cur;
	RETURN ids_;
END;;
DELIMITER ;
";
$sql = str_replace("DELIMITER ;;", "", $sql);            
$sql = str_replace(  "DELIMITER ;" ,"", $sql);    
$sql = str_replace( "`;", "`;;" , $sql);
$arr = explode(";;",$sql);
for($i=0;$i<count($arr);$i++){
    //echo $arr[$i]."<br/>";
    mysql_query($arr[$i],$conn);
}    
}

createtables($conn);
createProcBasic($conn);
createProcEducation($conn);

include_once '../libs/phpexcel/Classes/PHPExcel.php';
include_once '../libs/phpexcel/Classes/PHPExcel/IOFactory.php';
include_once '../libs/phpexcel/Classes/PHPExcel/Writer/Excel5.php';

$objPHPExcel = new PHPExcel();
$PHPReader = PHPExcel_IOFactory::createReader('Excel5');
$PHPReader->setReadDataOnly(true);
$obj = $PHPReader->load("../file/download/highschool/basic_parameter.xls");

mysql_query("truncate table basic_parameter ;",$conn);        
$currentSheet = $obj->getSheetByName("data");
$allRow = $currentSheet->getHighestRow();

$sql = "INSERT INTO basic_parameter(
	code,value,reference,remark
	) VALUES ";
for($i=2;$i<=$allRow;$i++){
    $code = $currentSheet->getCell("A".$i)->getValue();
    if($code=="")continue;
    //$code = str_replace("00", "", $code);
    $sql .="  (
	'".$code."'
	,'".$currentSheet->getCell("B".$i)->getValue()."'
	,'".$currentSheet->getCell("C".$i)->getValue()."'
	,'".$currentSheet->getCell("D".$i)->getValue()."'
	),";
}
$sql = substr($sql,0,strlen($sql)-1);
mysql_query($sql,$conn);

mysql_query("call basic_memory__init() ;",$conn);


        
$eval = "
class config{
	public static \$host = '".$db_server."';
	public static \$unm = '".$db_username."';
	public static \$pwd = '".$db_password."';
	public static \$db = '".$db_name."';
	public static \$language = 'zh-cn';
	public static \$phpexcel = \"../libs/phpexcel/Classes/\";
}
";
eval($eval);
require_once 'basic_memory.php';
require_once 'tools.php';
require_once 'basic_excel.php';

$obj = new basic_memory();
$obj->il8n();        
$_REQUEST['userid'] = 1;

basic_excel::import('../file/download/highschool/basic_permission.xls');
echo " basic_permission ".basic_excel::$guid; 	
mysql_query("call basic_permission__import('".basic_excel::$guid."',@state,@msg,@ids)",$conn);
$res = mysql_query("select @state,@msg,@ids",$conn);
$data = mysql_fetch_assoc($res);
print_r($data);


basic_excel::import('../file/download/highschool/basic_group2.xls');
echo " basic_group ".basic_excel::$guid."---------";
mysql_query("call basic_group__import('".basic_excel::$guid."',@state2,@msg2,@ids2)",$conn);
$res = mysql_query("select @state2,@msg2,@ids2",$conn);
$data = mysql_fetch_assoc($res);
print_r($data);

//导入权限对用户组
basic_excel::import('../file/download/highschool/basic_group_2_permission2.xls');
echo " basic_group_2_permission ".basic_excel::$guid;	
mysql_query("call basic_group_2_permission__import('".basic_excel::$guid."',@state,@msg)",$conn);
$res = mysql_query("select @state,@msg",$conn);  	
$data = mysql_fetch_assoc($res);	
print_r($data);		

//导入用户  
basic_excel::import('../file/download/highschool/basic_user.xls');
echo " basic_user ".basic_excel::$guid;
mysql_query("call basic_user__import('".basic_excel::$guid."',@state,@msg,@ids)",$conn);  
$res = mysql_query("select @state,@msg,@ids",$conn);
$data = mysql_fetch_assoc($res);
print_r($data); 

//导入科目
basic_excel::import('../file/download/highschool/education_subject2.xls');
echo " education_subject ".basic_excel::$guid;
mysql_query("call education_subject__import('".basic_excel::$guid."',@state,@msg,@ids)",$conn);  
$res = mysql_query("select @state,@msg,@ids",$conn);
$data = mysql_fetch_assoc($res);
print_r($data);	
 																		
//导入学生
basic_excel::import('../file/download/highschool/education_student2.xls');
echo " education_student ".basic_excel::$guid;
mysql_query("call education_student__import('".basic_excel::$guid."',@state,@msg,@ids)",$conn);  
$res = mysql_query("select @state,@msg,@ids",$conn);
$data = mysql_fetch_assoc($res);
print_r($data);	

//导入教师
basic_excel::import('../file/download/highschool/education_teacher2.xls');
echo " education_teacher ".basic_excel::$guid;
mysql_query("call education_teacher__import('".basic_excel::$guid."',@state,@msg,@ids)",$conn);  
$res = mysql_query("select @state,@msg,@ids",$conn);
$data = mysql_fetch_assoc($res);
print_r($data);			

//导入 科目-教师-班级 对应关系
basic_excel::import('../file/download/highschool/education_subject_2_group_2_teacher2.xls');
echo " education_subject_2_group_2_teacher ".basic_excel::$guid;
mysql_query("call education_subject_2_group_2_teacher__import('".basic_excel::$guid."',@state,@msg)",$conn);  
$res = mysql_query("select @state,@msg",$conn);
$data = mysql_fetch_assoc($res);
print_r($data);	

//模拟业务数据
mysql_query("call education_exam__init4test(4,@msg,@state)",$conn);  
mysql_query("call education_exam_2_student__init4test(80)",$conn);		
mysql_query("call education_paper__init4test(30)",$conn);	

$arr = array(
'host'=>$db_server
,'unm'=>$db_username
,'pwd'=>$db_password
,'db'=>$db_name
,'language'=>'zh-cn'
,'phpexcel'=>'../libs/phpexcel/Classes/'
);
$content = "<?php
class config{
";
$keys = array_keys($arr);
for($i=0;$i<count($arr);$i++){
	$content .= "
	public static \$".$keys[$i]." = '".$arr[$keys[$i]]."';";
}
$content.=
"
}";
$file_handle = fopen("config.php","w");
fwrite($file_handle,$content);
fclose($file_handle);
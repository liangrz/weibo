var div;
var uid;
var uname;
var wid_c=new Array();
var login_tip;
var _div_write_format = '<div class = "out">发布微博<input id="getout" class = "fr" type ="button" value="x"><br>'+
			'<textarea id="content" cols= "40" rows="10"></textarea><br>'+
			'<input id="write_over" type ="button" value="提交">'+
			'</div>';
var _div_block = "<div style='background:rgba(100,100,100,0.3);width:100%;height:100%;position:fixed;top:0px;left:0px;z-index:1;'></div>";
function _div_weibo_format(wid,content){					
	var tmp	= "<div class = 'weibo' data-wid="+wid+">"+
				"<div class = 'content'>"+
					"create_time "+uname+"<br>"+
					content+
				"</div>"+
				"<div class = 'action'>"+
					"<span class = 'zan_b fr'>赞<span>0</span></span>"+
					"<span class = 'comment_b fr'>评论</span>"+
					"<span class = 'delete_b fr'>删除</span>"+
				"</div>"+
			"</div>";
	return tmp;
}
function check(thisObj){
	arr = thisObj.getElementsByTagName('input');
	var user = arr['user'].value;
	var pwd = arr['pwd'].value;
	console.log(user);
	var flag;
	$.ajaxSetup({
		async:false
	});
	$.post('./ajax.php?action=check','user='+user+'&pwd='+pwd,function(rs){
		console.log(rs);
			rs = eval('('+rs+')');
			if(rs[0]){
				flag = true;
			}else{
				flag = false;
				if(login_tip ){
					login_tip .remove();
				}
					login_tip = document.createElement('span');
					login_tip .innerHTML = rs[1];
					thisObj.appendChild(login_tip);

			}
		});
	return flag?true:false;
}
window.onload = function(){
	getUid();
	$('#login').click(function(){
		out(function(){
			var _div ='<div class = "out"><form action="./login.php" onsubmit="return check(this)" method="post">'+
			'<input type = "text" name = "user" placeholder = "用户"><br>'+
			'<input type = "password" name = "pwd" placeholder = "密码"><br>'+
			'<input type = "submit"></form></div>';
			return _div;
		});
	});
	$('#register').click(function(){
		out(function(){
			var _div ='<div class = "out"><form action="./register.php" method="post">'+
			'<input type = "text" name = "user" placeholder = "用户"><br>'+
			'<input type = "password" name = "pwd1" placeholder = "密码"><br>'+
			'<input type = "password" name = "pwd2" placeholder = "确认密码"><br>'+
			'<input type = "submit"></form></div>';
			return _div;
		});
	});
	$('#write').click(function(){
		out(function(){
			var _div = _div_write_format+_div_block;
			return _div;
		});
		$('#getout').click(function(){
			out(function(){
				return;
			});
		});
		var thisObj = $(this);
		$('#write_over').click(function(){
			content = $('#content').val();
			$.post('./ajax.php?action=write','uid='+uid+'&content='+content,function(rs){
				if(rs){
					alert('success');
					thisObj.parent().next().after(_div_weibo_format(rs,content));
					out(function(){
						return;
					});
					$('.delete_b').unbind();
					$('.comment_b').unbind();
					$('.zan_b').unbind();
					$('.delete_b').click(function(){
							var thisObj = $(this);
							del(thisObj);
						});
					$('.comment_b').click(function(){
							var thisObj = $(this);//给post用
							comment(thisObj);
						});
					$('.zan_b').click(function(){
							thisObj = $(this);
							zan(thisObj);
						});
				}else{
					alert('cuowu');
				}
			});
		});
	});
	$('.delete_b').click(function(){
		var thisObj = $(this);
		del(thisObj);
	});
	$('.comment_b').click(function(){
		var thisObj = $(this);//给post用
		comment(thisObj);
	});
	$('.zan_b').click(function(){
		thisObj = $(this);
		zan(thisObj);
	});
}
function getUid(){
	var div = $('div');
	var re = /data-uid/;
	for(k in div){
		if(re.exec(div[k].innerHTML)){
			uid = div[k].dataset.uid;
			uname = div[k].dataset.uname;
			console.log(uid);
			console.log(uname);
			div[k].parentNode.removeChild(div[k]);
			break;
		}
	}
}
function out(callback){
	if(div&&div.parentNode){
		div.parentNode.removeChild(div);
	}
	var _div = callback();
	if(!_div){
		return;
	}
	div = document.createElement('div');
	div.innerHTML = _div;
	document.body.appendChild(div);
}
function del(thisObj){
	var wid = thisObj.parent().parent().data('wid');
	if(confirm('是否删除这条微博')){
		$.post('./ajax.php?action=del','wid='+wid,function(rs){
			if(!rs){
				thisObj.parent().parent().hide(1000,function(){
					$(this).remove();
				});
			}
		});
	}
}
function comment(thisObj){
	console.log(wid_c);
	var wid = thisObj.parent().parent().data('wid');
	if(wid_c[wid] != wid){
		var div_c='';
		$.post('./ajax.php?action=ask','wid='+wid,function(rs){
			rs = eval('('+rs+')');
			div_c += '<div class = "comment_list">'+
				'<div class = "comment">评论';
			if(!uid){
				div_c +='<span>请先登录</span>';
			}else{
				div_c +='<input>'+
					'<input class="comment_over" type ="button" value="提交">';
			}
			div_c += '</div>';	
			for(k in rs){
				div_c += "<div class = 'comment'>"+rs[k]['create_time']+"<br>"+
				rs[k]['content']+"</div>";
			}
			div_c += "</div>";
			thisObj.parent().parent().append(div_c);
			thisObj.parent().next().hide();
			thisObj.parent().next().show(500);
			$('.comment_over').click(function(){
				var thisObj = $(this);
				var content = $(this).prev().val();
				$.post('./ajax.php?action=comment','uid='+uid+'&wid='+wid+'&content='+content,function(rs){
					if(!rs){
						alert('success');
						thisObj.parent().after("<div class = 'comment'>"+'create_time'+"<br>"+content+"</div>");
						thisObj.parent().next().hide();
						thisObj.parent().next().show(500);
					}else{
						alert('cuowu');
					}
				});
			});
		});
		wid_c[wid] = wid;
	}else{
		thisObj.parent().next().hide(500,function(){
			this.remove();
		});
		wid_c[wid]='';
	}
}
function zan(thisObj){
	if(!uid){
		alert('请先登录');
	}else{
		//获得wid传参
		var wid = thisObj.parent().parent().data('wid');
		$.post('./ajax.php?action=zan','uid='+uid+'&wid='+wid);
		//实时更新数据
		var zan = parseInt(thisObj.children().html())+1;
		thisObj.children().html(zan);
	}
}
/*
function (fmt) { //author: meizz 
    var o = {
        "M+": this.getMonth() + 1, //月份 
        "d+": this.getDate(), //日 
        "h+": this.getHours(), //小时 
        "m+": this.getMinutes(), //分 
        "s+": this.getSeconds(), //秒 
        "q+": Math.floor((this.getMonth() + 3) / 3), //季度 
        "S": this.getMilliseconds() //毫秒 
    };
    if (/(y+)/.test(fmt)) fmt = fmt.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
    for (var k in o)
    if (new RegExp("(" + k + ")").test(fmt)) fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
    return fmt;
}
*/
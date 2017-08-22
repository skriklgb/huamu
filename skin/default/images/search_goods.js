$(function(){
	var map;
	//定义地图信息框样式
	var opts = {
			width : 250,     // 信息窗口宽度
			height: 80,     // 信息窗口高度
			title : "商品信息" , // 信息窗口标题
			enableMessage:true//设置允许信息窗发送短息
		   };
	/* 显示全部分类 */
    $("#show_category").click(function(){
        $("ul[ectype='ul_category'] li").show();
        $(this).hide();
    });

    /* 自定义价格区间 */
    $("#set_price_interval").click(function(){
        $("ul[ectype='ul_price'] li").show();
        $(this).hide();
    });

    /* 显示全部地区 */
    $("#show_region").click(function(){
        $("ul[ectype='ul_region'] li").show();
        $(this).hide();
    });

    /* 筛选事件 */
    $("ul[ectype='ul_category'] a").click(function(){
    	if(location.search){
    		replaceParam('cate_id', this.id);
    	}else{
    		replaceParamStatic('cate_id', this.id, 'search');
    	}
        return false;
    });

    $("ul[ectype='ul_price'] a").click(function(){
    	if(location.search){
    		replaceParam('price', $(this).attr("fkey"));
    	}else{
    		replaceParamStatic(3, $(this).attr("fkey"), 'search');
    	}
        return false;
    });
    $("#search_by_price").click(function(){
    	if(location.search){
    		replaceParam('price', $(this).siblings("input:first").val() + ':' + $(this).siblings("input:last").val());
    	}else{
    		replaceParamStatic(3, $(this).siblings("input:first").val() + ':' + $(this).siblings("input:last").val(), 'search');
    	}
        return false;
    });
    $("ul[ectype='ul_region'] a").click(function(){
        //replaceParam('region_id', this.id);
    	if(location.search){
    		replaceParam('region_id', this.id);
    	}else{
    		replaceParamStatic(2, this.id, 'search');
    	}
        return false;
    });
    $("li[ectype='li_filter'] img").click(function(){
    	if(location.search){
	    	if($(this).attr('ai')){
	    		dropParamEx($(this).attr('ai'));
	    	}else{
	    		dropParam($(this).attr("fkey"));
	    	}
    	}else{
	    	if($(this).attr('ai')){
	    		//dropParamEx($(this).attr('ai'));
	    		dropParamStaticEx($(this).attr('ai'));
	    	}else{
	    		//dropParam(this.title);
	    		dropParamStatic($(this).attr("fpos"));
	    	}
    	}
        return false;
    });
    $("[ectype='order_by']").change(function(){
    	if(location.search){
    		//replaceParam('order_by', this.value);
    		replaceParam('order', this.value);
    	}else{
    		if($(this).attr('pn')=='search'){
    			replaceParamStatic(5, this.value, 'search');
    		}else{
    			replaceParamStatic(2, this.value, 'category');
    		}
    	}
        return false;
    });

    /* 下拉过滤器 */
    $("li[ectype='dropdown_filter_title'] a").click(function(){
        var jq_li = $(this).parents("li[ectype='dropdown_filter_title']");
        var status = jq_li.find("img").attr("src") == upimg ? 'off' : 'on';
        switch_filter(jq_li.attr("ecvalue"), status)
    });

    /* 显示方式 */
    $("[hmtype='display_mode']").click(function(){
    	$(this).addClass('selected');
    	$(this).siblings('li').removeClass('selected');
    	$("[hmtype='current_display_mode']").attr('class', $(this).attr('hmvalue'));
    	$.setCookie('goodsDisplayMode', $(this).attr('hmvalue'));
    	if($(this).attr('hmvalue')=='map'){
    		if(map==undefined){
	    		map = new BMap.Map("allmap");
	    		var point = new BMap.Point(116.404, 39.915);
	    		map.centerAndZoom(point, 6);
	    		var myCity = new BMap.LocalCity();
	    		myCity.get(function(result){
	    			var cityName = result.name;
	    			map.setCenter(cityName);
	    		});
	    		var top_left_control = new BMap.ScaleControl({anchor: BMAP_ANCHOR_TOP_LEFT});// 左上角，添加比例尺
	    		var top_left_navigation = new BMap.NavigationControl();  //左上角，添加默认缩放平移控件
	    		//var top_right_navigation = new BMap.NavigationControl({anchor: BMAP_ANCHOR_TOP_RIGHT, type: BMAP_NAVIGATION_CONTROL_SMALL});
	    		map.addControl(top_left_control);
	    		map.addControl(top_left_navigation);
	    		//map.addControl(top_right_navigation);

	    		
	    		$('ul.list_pic>li').each(function(){
	    			if($(this).attr('lon')>0){
		    			var point = new BMap.Point($(this).attr('lon'), $(this).attr('lat'));
		    			var marker = new BMap.Marker(point);
		    			map.addOverlay(marker);
		    			
		    			var goodsName = $(this).find('.depict').first().text();
		    			var goodsUrl = $(this).find('a').first().attr('href');
		    			var goodsImg = $(this).find('a>img').first().attr('src');
		    			var goodsPrice = $(this).find('.price').first().text();
		    			var storeName = $(this).find('.info>a').first().text();
		    			var storeUrl = $(this).find('.info>a').first().attr('href');
		    			var storeGrade = $(this).attr('sg');
		    			
		    			//添加标签
		    			var label = new BMap.Label(goodsName,{offset:new BMap.Size(20,-10)});
		    			marker.setLabel(label);
		    			label.addEventListener('click', function(){window.open(goodsUrl,'_blank');}); 
		    			//marker.setAnimation(BMAP_ANIMATION_BOUNCE); //跳动的动画
		    			
		    			//添加标注点击事件
		    			var content = '<div style="float:left;">'+
							'<a href="'+goodsUrl+'" target="_blank"><img src="'+goodsImg+'" height="65" width="65" /></a>'+
							'</div><div style="float:left;margin-left:7px; width:140px;">'+
							'<div><a href="'+goodsUrl+'" target="_blank">'+goodsName+'</a></div>'+
							'<div><a href="'+storeUrl+'" style="max-width:108px;height:20px;line-height:25px;overflow:hidden;float:left;">'+storeName+'</a><a href="javascript:void(0);" class="sg_item sgrade_'+storeGrade+'"><span></span></a></div>'+
							'<div style="clear:both;"><span style="color:#E4393C;font-size:12px;">'+goodsPrice+'</span>/株</div>'+
							'</div>'; 
		    			addClickHandler(content, marker);
	    			}
	    		});
    		}
    		$('ul.list_pic').hide();
    		$('#allmap').show();
    	}else{
    		$('ul.list_pic').show();
    		$('#allmap').hide();
    	}
    });
    
    //地图点击事件
	function addClickHandler(content,marker){
		marker.addEventListener("mouseover",function(e){
			openInfo(content,e)}
		);
	}
	function openInfo(content,e){
		var p = e.target;
		var point = new BMap.Point(p.getPosition().lng, p.getPosition().lat);
		var infoWindow = new BMap.InfoWindow(content,opts);  // 创建信息窗口对象 
		map.openInfoWindow(infoWindow,point); //开启信息窗口
	}
    
});

/** 打开/关闭过滤器
 *  参数 filter 过滤器   brand | price | region
 *  参数 status 目标状态 on | off
 */
function switch_filter(filter, status)
{
    $("li[ectype='dropdown_filter_title']").attr('class', 'normal');
    $("li[ectype='dropdown_filter_title'] img").attr('src', downimg);
    $("div[ectype='dropdown_filter_content']").hide();

    if (status == 'on')
    {
        $("li[ectype='dropdown_filter_title'][ecvalue='" + filter + "']").attr('class', 'active');
        $("li[ectype='dropdown_filter_title'][ecvalue='" + filter + "'] img").attr('src', upimg);
        $("div[ectype='dropdown_filter_content'][ecvalue='" + filter + "']").show();
    }
}

/* 替换参数 */
function replaceParam(key, value)
{
    var params = location.search.substr(1).split('&');
    var found  = false;
    for (var i = 0; i < params.length; i++)
    {
        param = params[i];
        arr   = param.split('=');
        pKey  = arr[0];
        if (pKey == 'page')
        {
            params[i] = 'page=1';
        }
        if (pKey == key)
        {
            params[i] = key + '=' + value;
            found = true;
        }
    }
    if (!found)
    {
        value = transform_char(value);
        params.push(key + '=' + value);
    }
    location.assign(SITE_URL + '/index.php?' + params.join('&'));
}

/* 删除参数 */
function dropParam(key)
{
    var params = location.search.substr(1).split('&');
    for (var i = 0; i < params.length; i++)
    {
        param = params[i];
        arr   = param.split('=');
        pKey  = arr[0];
        if (pKey == 'page')
        {
            params[i] = 'page=1';
        }
        if (pKey == key)
        {
            params.splice(i, 1);
        }
    }
    location.assign(SITE_URL + '/index.php?' + params.join('&'));
}

/* 删除参数 */
function dropParamEx(key)
{
    var params = location.search.substr(1).split('&');
    for (var i = 0; i < params.length; i++)
    {
        param = params[i];
        arr   = param.split('=');
        pKey  = arr[0];
        if (pKey == 'page')
        {
            params[i] = 'page=1';
        }
        if (pKey == 'ev')
        {
        	ev_items = arr[1].split(';');
        	for(var j=0; j<ev_items.length; j++)
        	{
        		arr_item = ev_items[j].split(':');
        		if(arr_item[0] == key){
        			ev_items.splice(j, 1);
        		}
        	}
        	
            params[i] = 'ev=' + ev_items.join(';');
        }
    }
    location.assign(SITE_URL + '/index.php?' + params.join('&'));
}

function dropParamStatic(key)
{
    var paths = location.pathname.split('/');
    var params = paths[2].replace('.html','').split('_');
    
    for (var i = 1; i < params.length; i++)
    {
        param = params[i];
        //arr   = param.split('_');
        //pKey  = arr[0];
        if (i == 7)
        {
            params[i] = '1';
        }
        if (i == key)
        {
            //params.splice(i, 1);
        	params[i] = '';
        }
    }
    location.assign(SITE_URL + '/search_goods/' + params.join('_')+".html");
}

function dropParamStaticEx(key)
{
    var paths = location.pathname.split('/');
    var params = paths[2].replace('.html','').split('_');
    
    for (var i = 1; i < params.length; i++)
    {
        param = params[i];

        //page
        if (i == 7)
        {
            params[i] = '1';
        }
        //ev
        if (i == 4)
        {
        	ev_items = unescape(param).split(';');
        	for(var j=0; j<ev_items.length; j++)
        	{
        		arr_item = ev_items[j].split(':');
        		if(arr_item[0] == key){
        			ev_items.splice(j, 1);
        		}
        	}
        	
            params[i] = escape(ev_items.join(';'));
        }
    }
    location.assign(SITE_URL + '/search_goods/' + params.join('_')+".html");
}

/* 替换参数 */
function replaceParamStatic( key, value, type)
{
    var paths = location.pathname.split('/');
    var params = paths[2].replace('.html','').split('_');
    var found  = false;
    if(type == 'search'){
	    for (var i = 1; i < params.length; i++){
	        param = params[i];
	        if (i == 7)
	        {
	            params[i] = '1';
	        }
	        if (i == key)
	        {
	            params[i] = escape(value);
	            found = true;
	        }
	    }
	    if (!found)
	    {
	        value = transform_char(value);
	        params.push('_' + escape(value));
	    }
    
    	location.assign(SITE_URL + '/search_goods/' + params.join('_')+".html");
    }else{
    	var re=new RegExp("^[0-9]+$");
    	if(re.test(params[1])){
    		key = 3;
    	}
    	for (var i = 0; i < params.length; i++){
	        param = params[i];
	        
	        if (i == key)
	        {
	            params[i] = escape(value);
	            found = true;
	        }
    	}
	    if (!found)
	    {
	        value = transform_char(value);
	        params.push(escape(value));
	    }
    	location.assign(SITE_URL + '/fenlei/' + params.join('_')+".html");
    }
}
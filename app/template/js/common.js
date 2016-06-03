jQuery.fn.center = function () {
	//this.css('position','absolute');
        //--{{ path_for('jsFile',{'filename':'common'}) }}
	this.css('top', ( $(window).height() - this.height() ) /2  + 'px');
	this.css('left', ( $(window).width() - this.width() ) / 2+ 'px');return this;}
	
	
function checkmail(a){return/^\w+[\+\.\w-]*@([\w-]+\.)*\w+[\w-]*\.([a-z]{2,4}|\d+)$/i.test(a)}

function selectbox(selector, value){
    if(value){
        $(selector).val(value);
    }
}

function hideEbookLayer(){
		$("#dimLayer").css('display','none');
		$("#ebookLayer").css('display','none');
		return false;
	};

function showEbookForm(){
		$("#dimLayer").css('display','block');
		$("#ebookLayer").css('display','block');
		$("#ebookLayer").center();
		return false;
	}

$(function(){
	/*** ebook start ****/
	$("#dimLayer").height($("body").height());
	$(".goEbookLayer").on("click",showEbookForm);
	$("#dimLayer , #ebookClostBtn").on("click",hideEbookLayer);//
	$(window).resize(function(){
			$("#ebookLayer").center();
			$("#dimLayer").height($("body").height());
	});
	$("#email").focus(function(){
			if ( $(this).val() == 'Your email address here' ) { 
				$(this).val('').css('color','#000');
			} 
		}).blur(function(){
			if ( $(this).val() == '' ) {
				$(this).val('Your email address here').css('color','#666');
			}
		})
	$("#download_submit").click(function(){
		$("#downloadForm").submit();
		return false;
	});
	$("#downloadForm").submit(function(){
		if ( !checkmail($("#email").val()) ) { 
			$("#wrong_email").html('&nbsp;Please enter your valid email');
			return false;
		}else{ 
			return true;
		}
	})
	/*** ebook end ****/
	
	/** term of use ***/
	$("#dmBox").prop("checked",false);
	$("#dmBox").click(function(){
		window.location.href='/opt-out/';
	});
	/** term of use ***/
	
})



function goLang(){
	var clink = new Array;
	clink[1] = '/';  
	clink[2] = '/zhongwen/';  
	clink[3] = '/espanol/';  
	clink[4] = '/fr/';  
	clink[5] = '/pr/';  
	clink[6] = '/nl/';  
	clink[7] = '/ru/';
	clink[8] = '/de/';
	clink[9] = '/hk/';
	clink[10] = '/it/';
	var a = document.getElementById("cLang") ;

	if ( a.selectedIndex != 0 ) {
		//alert(clink[a.selectedIndex]);
		//alert(a.options[a.selectedIndex].value);
		location.href = clink[a.selectedIndex];
	}
}


function activeMenu(base_url){
	var uri = location.pathname;
	var uri2 = uri.slice(base_url.length,uri.length+1);
	$(".asidebox li").find("a[href='"+uri2+"']").addClass('active');
}

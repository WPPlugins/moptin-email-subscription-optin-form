/* 
 *  Author     : Shubham
 *  Author Url : http://mycodingtricks.com/
 *  Plugin Name: Moptin - Email Subscription Optin form
 *  Plugin Url: https://wordpress.org/plugins/moptin-email-subscription-optin-form/
*/
(function($){
    $.fn.moptin = function(options){
        
        var self = this,
            $self = $(self),
            body = $("body"),
            id = $self.attr("id"),
            forcedToClose=false,
            delay=0,
            scrollOffset=0,
            docHeight = jQuery(document).height();
    
        if($self.hasClass("moptin__display__delay-5")) delay = 5000;
        if($self.hasClass("moptin__display__delay-10")) delay = 10000;
        if($self.hasClass("moptin__display__scroll-little")) scrollOffset = 0.1*docHeight;
        if($self.hasClass("moptin__display__scroll-middle")) scrollOffset = 0.4*docHeight;
        
        var settings = $.extend({
            delay: delay,
            scrollOffset: scrollOffset
        },options);
        
        self.addCloseButton = function(){
            if($self.hasClass("moptin-takeover")===true && $self.find(".moptin__close-btn").length===0){
                var id = $self.attr('id');
                var closeBtn = document.createElement("a");
                closeBtn.setAttribute("class","moptin__close-btn");
                closeBtn.innerHTML = "&times;";
                $self.append(closeBtn);
                $self.on("click",".moptin__close-btn",function(){
                   $self.fadeOut(500,function(){
                       self.close();
//                       console.log("Closed from line 37");
                   }); 
                });
                if(self.getCookie(id)=="true" && $self.hasClass("moptin-cookie")===true){
                    $self.removeClass("moptin-cookie");
                    self.close(id);
//                       console.log("Closed from line 42");
                }
            }
        };
        self.close = function(id){            
            forcedToClose=true;
            $self.fadeOut(300,function(){
                if($self.hasClass("moptin-takeover")){
                    body.removeClass("moptin-takeover-body");
                }
                $self.remove();
            });
            if($self.hasClass("moptin-cookie")===true)  self.setCookie(id,"true",1);
        },
        self.setCookie = function(expirydays) {
            var name = id;
            var d = new Date();
            var value = "true";
            d.setTime(d.getTime() + (expirydays*24*60*60*1000));
            var expires = "expires="+ d.toUTCString();
            document.cookie = name + "=" + value + "; " + expires;
        };
        self.deleteCookie = function(){
            this.setCookie(-1);
        };
        self.getCookie= function(name){
            name = name + "=";
            var cookies = document.cookie.split(';');
            for(var i = 0; i <cookies.length; i++) {
                var cookie = cookies[i];
                while (cookie.charAt(0)==' ') {
                    cookie = cookie.substring(1);
                }
                if (cookie.indexOf(name) === 0) {
                    return cookie.substring(name.length,cookie.length);
                }
            }
            return "";
        };
        self.centerThisOptin = function(){
            var mContainer = $self.find(".moptin__container"),
                imgWrapper = mContainer.find(".moptin__img-wrapper"),
                img = imgWrapper.find("img"),
                contentWrapper = mContainer.find(".moptin__content-wrapper");
                
            if(img.length>0){
                imgWrapper.css("height","auto");
                contentWrapper.css("height","auto")
                contentWrapper.css("marginTop","0px");
                img.load(recenter);
                recenter();
            }
            
            var moptin__height = $self.height(),
                mContainer__height = mContainer.height()+30,
                mContainer__margin_Top = (moptin__height - mContainer__height)/2;
                mContainer__margin_Top = (mContainer__margin_Top>0) ? mContainer__margin_Top:10;
                mContainer.css("marginTop",mContainer__margin_Top+"px");
            function recenter(){
                    var height = {
                        imgWrapper: imgWrapper.outerHeight(),
                        contentWrapper: contentWrapper.outerHeight(),
                        img: img.height(),
                        content: contentWrapper.height()
                    };
//                    console.log(height);
                    var finalHeight=(height.imgWrapper>height.contentWrapper) ? height.imgWrapper:height.contentWrapper;
                    //imgWrapper.css("height",finalHeight+"px");
                    //contentWrapper.css("height",finalHeight+"px");
                    var img_margin_top = (finalHeight-height.img)/2;
                    var content_margin_top = (finalHeight-height.content)/2;
                    img.css("marginTop",img_margin_top+"px");
                    contentWrapper.css("marginTop",content_margin_top+"px");
            }
        };
        self.centerImg = function(){
            var img = $self.find(".moptin__img");
            if(img.length>0){
                img.css("max-height",($self.height()*0.8)+"px");
            }
            self.centerThisOptin();
        };
        self.show = function(){
            if(forcedToClose) return false;
            $self.fadeIn(500,function(){
                self.centerImg();
                self.addCloseButton();
                $(window).resize(self.centerImg);
                self.OptimiseForScroll();
            });
        }
        self.showOptin = function(){
            if(settings.delay!==0){
//                console.log("Method 1");
                setTimeout(self.show,settings.delay)
            }else if(settings.scrollOffset!==0){
//                console.log("Method 2");
                $(document).scroll(function(){
                    var scrollTop = $(this).scrollTop();
                    if(scrollTop>settings.scrollOffset) self.show();
                });
            }else if($self.hasClass("moptin__display-exit")){
                $(document).mousemove(function (e) {
                    var mX = e.pageX,
                        mY = e.pageY;
//                console.log("mY = "+mY);
                    if(mX<80 || mY<80) self.show();
                }).mouseover();
            }
            else{
                self.show();
            }
            //console.log(settings);
        };
        self.OptimiseForScroll = function(){
            if($self.hasClass("moptin-takeover")){
//                console.log(self);
//                console.log($(self));
                body.addClass("moptin-takeover-body");
            }
        };
        return self;
    };
})(jQuery);
function imageInside(i){var n,e,r,t,o={},a=i.containerWidth,u=i.containerHeight,d=i.imageWidth,c=i.imageHeight;return e=a*(c/d),e<u?(e=u,n=u*(d/c),t=(a-n)/2,r=0):(n=a,r=(u-e)/2,t=0),o={width:n,height:e,top:r,left:t}}function objectToTag(i,n,e){var r="";for(var t in n)r+=t+'="'+n[t]+'" ';return"<"+i+" "+r+("img"==i?"/>":">")+(isUndefined(e)?"":e)+("img"!=i&&"source"!=i?"</"+i+">":"")}function isUndefined(i){return"undefined"==typeof i}var isMobile={Android:function(){return!!navigator.userAgent.match(/Android/i)},BlackBerry:function(){return!!navigator.userAgent.match(/BlackBerry/i)},iOS:function(){return!!navigator.userAgent.match(/iPhone|iPad|iPod/i)},Windows:function(){return!!navigator.userAgent.match(/IEMobile/i)},any:function(){return isMobile.Android()||isMobile.BlackBerry()||isMobile.iOS()||isMobile.Windows()}};
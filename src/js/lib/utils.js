/* ****
 * This function return an object of parameters
 * where the image should be to fit inside the
 * container.
 * @param   Object params
 * @param       Number containerWidth
 * @param       Number containerHeight
 * @param       Number imageWidth
 * @param       Number imageHeight
 * @return Object
 */
function imageInside(params){
    var returnO = {};
    var cW = params.containerWidth;
    var cH = params.containerHeight;
    var iW = params.imageWidth;
    var iH = params.imageHeight;

    // new width, height, top and left
    var nW, nH, nT, nL;
    
    nH = cW * ( iH / iW );
    
    if( nH < cH){
        nH = cH;
        nW = cH * ( iW / iH );
        nL = (cW - nW)/2;
        nT = 0;
    } else {
        nW = cW;
        nT = (cH - nH)/2;
        nL = 0;
    }
    
    returnO = {
        width   : nW,
        height  : nH,
        top     : nT,
        left    : nL
    }

    return returnO;
}
/* ****
 * This object has functions to determine
 * if the app is running under mobile
 * and which platform is.
 * @param   void
 * @return Boolean
 */
var isMobile = {
    Android: function() {
        return navigator.userAgent.match(/Android/i) ? true : false;
    },
    BlackBerry: function() {
        return navigator.userAgent.match(/BlackBerry/i) ? true : false;
    },
    iOS: function() {
        return navigator.userAgent.match(/iPhone|iPad|iPod/i) ? true : false;
    },
    Windows: function() {
        return navigator.userAgent.match(/IEMobile/i) ? true : false;
    },
    any: function() {
        return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Windows());
    }
};

function objectToTag(tag, attrs, child){
    var attrs_str = "";
    for(var name in attrs){
        attrs_str+= name+'="'+attrs[name]+'" ';
    }
    return '<'+tag+' '+attrs_str+(tag=='img'?'/>':'>')+ (!isUndefined(child)?child:'')+((tag!='img'&&tag!='source')?'</'+tag+'>':'');
}
function isUndefined(o){
    return typeof(o)=='undefined';
}

